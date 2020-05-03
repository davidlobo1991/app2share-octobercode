<?php namespace RW\Utils\Models;

use Cms\Classes\CmsObject;
use Cms\Classes\Controller;
use Cms\Classes\Page;
use Cms\Classes\Theme;
use DOMDocument;
use Event;
use Model;
use RainLab\Translate\Classes\Translator;
use RainLab\Translate\Models\Locale;
use System\Classes\PluginManager;
use Url;
use October\Rain\Router\Router as RainRouter;

/**
 * Model
 */
class Sitemap extends Model
{
    use \October\Rain\Database\Traits\Validation;

    const CHANGEFREQ = 'always';
    const PRIORITY = 0.5;
    const MAX_URLS = 50000;
    const MAX_GENERATED = 10000;
    protected $xmlObject;
    public $items;
    protected $urlSet;

    protected $theme;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'rw_utils_sitemap';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public function getThemeOptions()
    {
        $res = [];

        $activeTheme = Theme::getActiveTheme()->getDirName();
        $res[$activeTheme] = $activeTheme . ' - Activo';

        $themes = Theme::all();

        foreach ($themes as $theme) {
            $themeName = $theme->getDirName();
            if ($themeName != $activeTheme) {
                $res[$themeName] = $themeName;
            }
        }

        return $res;
    }

    public function getPageOptions($keyValue)
    {
        $theme = Theme::getActiveTheme();
        $pages = $theme->listPages()->pluck('title', 'baseFileName');
        $pagesUsed = $this->pluck('page')->flip();

        // Al actualizar no restar la pagina del registro actual
        if (!is_null($keyValue)) {
            $pagesUsed->forget($keyValue);
        }
        $pages = $pages->diffKeys($pagesUsed)->toArray();

        return $pages;
    }

    /**
     * Sitemap type options
     * @return array list with all types.
     */
    public function getTypeOptions()
    {
        $res = ['cms' => 'Cms'];
        /*
            El evento sirve para ejecutarlo en los plugins que queramos y que se utilicen para generar páginas con parametros.
            Lo único que necesitamos añadir en el el método `boot` del plugin es el siguiente código.
            Solo hay que devolver un array con los tipos de página que queremos dar de alta en este plugin. (Cada plugin debe tener un valor y nombre diferente)
            ```
            Event::listen('rw.sitemap.types', function () {
                return ['pages' => 'Páginas'];
            });
            ```
         */
        $types = Event::fire('rw.sitemap.types');
        if (is_array($types)) {
            foreach ($types as $type) {
                if (!is_array($type)) {
                    continue;
                }
                $res = array_merge($res, $type);
            }
        }

        return $res;
    }

    public function generateSitemap()
    {
        $theme = Theme::getActiveTheme();
        $this->theme = $theme;
        $activeTheme = $theme->getDirName();
        $items = Sitemap::where('theme', $activeTheme)->get();
        $xml = $this->makeXmlObject();
        $urlSet = $this->makeUrlSet();
        $xml->appendChild($urlSet);
        try {
            if ($items->count()) {
                foreach ($items as $item) {
                    if ($item->type == 'cms') {
                        $this->addItem($item);
                        continue;
                    }
                    $extraItems = Event::fire('rw.sitemap.generate', [$item, $theme]);
                    $this->manageExtraItems($extraItems);
                }
            }
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
        }

        return $xml->saveXML();
    }

    protected function manageExtraItems($extraItems)
    {
        if (!is_array($extraItems)) {
            return;
        }
        foreach (array_filter($extraItems) as $extraItem) {
            if (!is_array($extraItem) && !$extraItem->count()) {
                continue;
            }
            foreach ($extraItem as $item) {
                $this->addExtraItem($item);
            }
        }
    }

    protected function addExtraItem($item)
    {
        if ($this->urlCount >= self::MAX_URLS) {
            return false;
        }
        $this->urlCount++;
        $urlSet = $this->makeUrlSet();
        $xml = $this->makeXmlObject();

        $url = $xml->createElement('url');
        $url->appendChild($xml->createElement('loc', $item->url));

        if (isset($item->alternates)) {
            foreach ($item->alternates as $alternate) {
                $hrefLang = $xml->createElementNS('http://www.w3.org/1999/xhtml', 'xhtml:link');
                $hrefLang->setAttribute('rel', 'alternate');
                $hrefLang->setAttribute('hreflang', $alternate['locale']);
                $hrefLang->setAttribute('href', $alternate['url']);

                $url->appendChild($hrefLang);
            }
        }

        $url->appendChild(
            $xml->createElement('lastmod', $item->lastMod)
        );
        $url->appendChild(
            $xml->createElement('changefreq', $item->changeFreq ?? self::CHANGEFREQ)
        );
        $url->appendChild(
            $xml->createElement('priority', $item->priority ?? self::PRIORITY)
        );

        $urlSet->appendChild($url);
    }

    protected function addItem($item)
    {
        if ($this->urlCount >= self::MAX_URLS) {
            return false;
        }

        $this->urlCount++;
        $urlSet = $this->makeUrlSet();
        $plugins = PluginManager::instance();

        if ($plugins->exists('RainLab.Translate') &&
            !$plugins->isDisabled('RainLab.Translate')) {
            $langs = Locale::listEnabled();

            foreach (array_keys($langs) as $lang) {
                $url = $this->getCmsPageDetails($item, $langs, $lang);
                $urlSet->appendChild($url);
            }

            return;
        }

        $url = $this->getCmsPageDetails($item);
        $urlSet->appendChild($url);

        return;
    }

    protected function makeXmlObject()
    {
        if ($this->xmlObject !== null) {
            return $this->xmlObject;
        }

        $xml = new DOMDocument;
        $xml->encoding = 'UTF-8';

        return $this->xmlObject = $xml;
    }

    protected function makeUrlSet()
    {
        if ($this->urlSet !== null) {
            return $this->urlSet;
        }
        $xml = $this->makeXmlObject();

        $urlSet = $xml->createElement('urlset');
        $urlSet->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlSet->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $urlSet->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');

        return $this->urlSet = $urlSet;
    }

    protected function getCmsPageDetails($item, $langs = null, $lang = null)
    {
        $xml = $this->makeXmlObject();
        $url = $xml->createElement('url');

        $pageUrl = $this->getLocalizedUrl($item, $lang);

        $url->appendChild($xml->createElement('loc', $pageUrl)); //generar url + hreflang

        if ($langs) {
            foreach (array_keys($langs) as $lang) {
                $langUrl = $this->getLocalizedUrl($item, $lang);
                $hrefLang = $xml->createElementNS('http://www.w3.org/1999/xhtml', 'xhtml:link');
                $hrefLang->setAttribute('rel', 'alternate');
                $hrefLang->setAttribute('hreflang', $lang);
                $hrefLang->setAttribute('href', $langUrl);
                $url->appendChild($hrefLang);
            }
        }

        $url->appendChild(
            $xml->createElement('lastmod', date('c'))
        );
        $url->appendChild(
            $xml->createElement('changefreq', $item->changefreq ?? self::CHANGEFREQ)
        );
        $url->appendChild(
            $xml->createElement('priority', $item->priority ?? self::PRIORITY)
        );

        return $url;
    }

    protected function getItemUrl($item)
    {
        $controller = Controller::getController() ?? new Controller;
        $pageUrl = $controller->pageUrl($item->page, [], false);

        //$pageUrl = $controller->pageUrl($item->page, ['slug' => 'first-blog-post'], false);
        return $pageUrl;
    }

    protected function getLocalizedUrl($item, $lang)
    {
        $translator = Translator::instance();
        $page = Page::load($this->theme, $item->page . '.htm');

        $page->rewriteTranslatablePageUrl($lang);
        $router = new RainRouter;
        $localeUrl = $router->urlFromPattern($page->url);

        return Url::to("/") . '/' . $translator->getPathInLocale($localeUrl, $lang);
    }
}
