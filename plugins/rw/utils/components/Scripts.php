<?php namespace RW\Utils\Components;

use Cms\Classes\ComponentBase;
use October\Rain\Support\Facades\Block;
use RW\Utils\Models\Script;

class Scripts extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Scripts Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        try {
            $scripts = Script::isActive()->get();
            $this->addScriptsToPage($scripts);
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    private function addScriptsToPage($scripts)
    {
        Block::append('analytics', $scripts->pluck('script')->implode(''));
        Block::append('noscripts', $scripts->pluck('noscript')->implode(''));
    }
}
