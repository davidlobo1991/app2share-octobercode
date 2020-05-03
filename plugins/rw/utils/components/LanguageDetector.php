<?php namespace Rw\Utils\Components;

use Event;
use RW\Utils\Models\Settings;
use Session;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Request;
use RainLab\Translate\Models\Locale;
use RainLab\Translate\Classes\Translator;
use Illuminate\Support\Facades\Route;

class LanguageDetector extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'LanguageDetector Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        if (\App::runningInBackend()) {
            return;
        }

        $settings = Settings::instance();

        $translator = Translator::instance();

        $segment = Request::segment(1);

        $localeSession = Session::get($translator::SESSION_LOCALE);

        $locale = $segment;

        if (post('locale') && $locale != post('locale')) {
            $translator->setLocale(post('locale'));
            $locale = post('locale');
        }

        if (!$locale && !$localeSession && ($settings->detect_browser_language && isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))) {
            $accepted = $this->parseLanguageList($_SERVER['HTTP_ACCEPT_LANGUAGE']);
            $available = Locale::listEnabled();

            $matches = $this->findMatches($accepted, $available);

            if (!empty($matches)) {
                $locale = array_keys($matches)[0];
                $translator->setLocale($locale);

                return $this->forceRedirect($locale, $translator);
            }
        }

        /*if (!$locale || !Locale::isValid($locale)) {
            if (($settings->prefer_user_session && $localeSession) || $segment) {
//                $available = array_keys(Locale::listEnabled());
                if (!$locale && request()->getRequestUri() == '/') {
                    $locale = $translator->getDefaultLocale();

                }
//                if (is_null($segment) && !in_array($segment, $available)) {
//                    $locale = $translator->getDefaultLocale();
//                }
                $translator->setLocale($locale);

                if ($locale != $translator->getDefaultLocale()) {

                    return $this->forceRedirect($locale, $translator);
                }
            }
        }*/

        $locale = $translator->getLocale();

        if (!Locale::isValid($locale)) {
            $translator->setLocale($translator->getDefaultLocale());
        }

        if ($segment == $translator->getDefaultLocale()) {
            if ($translator->getLocale() == $translator->getDefaultLocale()) {
                return;
            }

            return redirect($translator->getPathInLocale('/', $locale, env('PREFIX_DEFAULT_LOCALE')), 301);
        }

        return;
    }

    /**
     * @param $locale
     * @param Translator $translator
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function forceRedirect($locale, Translator $translator)
    {
        Route::group(['prefix' => $locale, 'middleware' => 'web'], function () {
            Route::any('{slug}', 'Cms\Classes\CmsController@run')->where('slug', '(.*)?');
        });

        Route::any($locale, 'Cms\Classes\CmsController@run')->middleware('web');

        Event::listen('cms.route', function () use ($locale) {
            Route::group(['prefix' => $locale, 'middleware' => 'web'], function () {
                Route::any('{slug}', 'Cms\Classes\CmsController@run')->where('slug', '(.*)?');
            });
        });

        return redirect($translator->getPathInLocale('/', $locale, env('PREFIX_DEFAULT_LOCALE')));
    }

    protected function parseLanguageList($languageList)
    {
        if (is_null($languageList)) {
            if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                return [];
            }
            $languageList = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }
        $languages = [];
        $languageRanges = explode(',', trim($languageList));
        foreach ($languageRanges as $languageRange) {
            if (preg_match('/(\*|[a-zA-Z0-9]{1,8}(?:-[a-zA-Z0-9]{1,8})*)(?:\s*;\s*q\s*=\s*(0(?:\.\d{0,3})|1(?:\.0{0,3})))?/', trim($languageRange), $match)) {
                if (!isset($match[2])) {
                    $match[2] = '1.0';
                } else {
                    $match[2] = (string) floatval($match[2]);
                }
                if (!isset($languages[$match[2]])) {
                    $languages[$match[2]] = strtolower($match[1]);
                }
            }
        }
        krsort($languages);

        return $languages;
    }

    /*compare two parsed arrays of language tags and find the matches*/
    protected function findMatches($accepted, $available)
    {
        $matches = [];
        $any = false;
        foreach ($available as $availableLocale => $availableName) {
            foreach ($accepted as $acceptedQuality => $acceptedLocale) {
                $acceptedQuality = floatval($acceptedQuality);
                if ($acceptedQuality === 0.0) {
                    continue;
                }
                if ($acceptedLocale === '*') {
                    $any = true;
                }
                $matchingGrade = $this->matchLanguage($acceptedLocale, $availableLocale);
                if ($matchingGrade > 0) {
                    $q = ($acceptedQuality * $matchingGrade);
                    if (!array_key_exists($availableLocale, $matches) || $matches[$availableLocale] < $q) {
                        $matches[$availableLocale] = $q;
                    }
                }
            }
        }
        if (count($matches) === 0 && $any) {
            $matches = $available;
        }
        arsort($matches);

        return $matches;
    }

    /**
     * compare two language tags and distinguish the degree of matching
     * edit: actually matching "en-us" with "en" will always return "1"
     * @param $a [] user-accepted
     * @param $b [] backend-available
     * @return float|int
     */
    protected function matchLanguage($a, $b)
    {
        // convert 'en-US' to 'en-us'
        $b = strtolower($b);
        $a = explode('-', $a);
        $b = explode('-', $b);
        $perfect_match = false;
        for ($i = 0, $n = min(count($a), count($b)); $i < $n; $i++) {
            if ($a[$i] !== $b[$i]) {
                break;
            }
            if (count($a) == count($b) && $i == $n - 1) {
                $perfect_match = true;
            }
        }
        $val = $i === 0 ? 0 : (float) $i / count($a);

        return $perfect_match ? 2 : $val;
    }


}
