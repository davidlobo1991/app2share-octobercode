<?php namespace RW\Utils\Components;

use Cms\Classes\ComponentBase;
use Config;
use Event;
use RW\Utils\Models\Seo;
use RainLab\Translate\Classes\Translator;

class SeoPage extends ComponentBase
{
    public $seoContent;
    public $canonicalUrl;

    public function componentDetails()
    {
        return [
            'name'        => 'SeoPage Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        //Comprobar primero si la página tiene el dynamicseo definido.
        try {
            $this->canonicalUrl = $this->page['canonicalUrl'] = $this->setCanonicalUrl();

            Event::listen('rw.dynamicSeo', function ($dynamicSeo) {
                if ($dynamicSeo) {
                    $this->seoContent = $this->page['seoContent'] = $dynamicSeo;
                    return;
                }
            });
            $seoContent = Seo::where('page', $this->page->baseFileName)
                    ->with('seoImage')
                    ->firstOrFail();
            $this->seoContent = $this->page['seoContent'] = $seoContent;
        } catch (\Exception $exception) {
            //\Log::error($exception->getMessage());
        }
    }

    /**
     * Get canonical url withouth index.php
     * @return mixed|string
     */
    public function setCanonicalUrl()
    {
        $translator = Translator::instance();
        $currentLocale = $translator->getLocale(true);
        $baseUrl = url('/');

        $baseUrlLocale = $baseUrl;

        if ($currentLocale !== $translator->getDefaultLocale()) {
            $baseUrlLocale = $baseUrl . '/' . $currentLocale;
        }

        $currentUrl = $this->removeIndexPhp();

        $regex = '/(' . str_replace('/', '\/', $baseUrlLocale) . ')/';

        if (preg_match($regex, $currentUrl)) {
            return $currentUrl;
        }

        return str_replace($baseUrl, $baseUrlLocale, $currentUrl);
    }

    /**
     * Removes index.php from url
     * @return string
     */
    private function removeIndexPhp()
    {
        $currentUrl = $this->currentPageUrl();
        try {
            $indexPhp = '/\/index.php/';

            if (preg_match($indexPhp, $currentUrl)) {
                $stringSearch = $this->getSearchString();
                $currentUrl = str_replace($stringSearch, '', $currentUrl);
            }
            return $currentUrl;
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            return $currentUrl;
        }
    }

    /**
     * Get strings to search on url.
     * @return mixed array|string
     */
    private function getSearchString()
    {
        $translator = Translator::instance();
        $defaultLocale = $translator->getDefaultLocale();

        $prefixDefaultLocale = Config::get('rainlab.translate::prefixDefaultLocale');
        /*Si queremos ocultar el default locale, pasamos un array con los dos strings a eliminar.*/
        if (!$prefixDefaultLocale) {
            return [
                '/index.php/'.$defaultLocale,
                '/index.php',
            ];
        }
        return '/index.php';
    }
}
