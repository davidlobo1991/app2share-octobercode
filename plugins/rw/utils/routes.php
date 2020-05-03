<?php

use Cms\Classes\Controller;
use RW\Utils\Models\Sitemap;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use RainLab\Translate\Classes\Translator;


Route::get('sitemap.xml', function () {
    $sitemap = (new Sitemap)->generateSitemap();

    return Response::make($sitemap)
        ->header("Content-Type", "application/xml");
});


Route::get('robots.txt', function () {
    try {
        $content = Storage::get('robots.txt');

        return response($content, 200)->header('Content-Type', 'text/plain');
    } catch (\Exception $exception) {
        \Log::info($exception->getMessage());

        return (new Controller)->run('404');
    }
});
