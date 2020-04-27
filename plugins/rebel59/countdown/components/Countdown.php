<?php namespace Rebel59\Countdown\Components;

use Cms\Classes\ComponentBase;

class Countdown extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'rebel59.countdown::lang.components.countdown.name',
            'description' => 'rebel59.countdown::lang.components.countdown.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'date' => [
                'title'             => 'rebel59.countdown::lang.components.countdown.properties.date.title',
                'description'       => 'rebel59.countdown::lang.components.countdown.properties.date.description',
                'type'              => 'string',
                'placeholder'       => 'YYYY/MM/DD HH:MM:SS',
                'validationPattern' => '^\d\d\d\d\/(0?[1-9]|1[0-2])\/(0?[1-9]|[12][0-9]|3[01]) (00|[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9]):([0-9]|[0-5][0-9])$',
                'validationMessage' => 'rebel59.countdown::lang.components.countdown.properties.date.validationMessage'
            ],
            'jquery' => [
                'title'             => 'rebel59.countdown::lang.components.countdown.properties.jquery.title',
                'description'       => 'rebel59.countdown::lang.components.countdown.properties.jquery.description',
                'type'              => 'checkbox',
                'default'           => false
            ],
            'countdown' => [
                'title'             => 'rebel59.countdown::lang.components.countdown.properties.countdown.title',
                'description'       => 'rebel59.countdown::lang.components.countdown.properties.countdown.description',
                'type'              => 'checkbox',
                'default'           => true
            ],
            'init' => [
                'title'             => 'rebel59.countdown::lang.components.countdown.properties.init.title',
                'description'       => 'rebel59.countdown::lang.components.countdown.properties.init.description',
                'type'              => 'checkbox',
                'default'           => true
            ],
            'css' => [
                'title'             => 'rebel59.countdown::lang.components.countdown.properties.css.title',
                'description'       => 'rebel59.countdown::lang.components.countdown.properties.css.description',
                'type'              => 'checkbox',
                'default'           => true
            ]

        ];
    }

    public function onRender(){
        $this->loadAssets();
    }

    protected function loadAssets(){
        if(!$this->property('date')){
            $this->page['error'] = true;
            return false;
        }

        if($this->property('css'))
            $this->addCss('/plugins/rebel59/countdown/assets/dist/app.countdown.min.css');

        if($this->property('jquery'))
            $this->addJs('/plugins/rebel59/countdown/assets/dist/vendor/jquery/jquery.3.2.0.production.js');

        if($this->property('countdown'))
            $this->addJs('/plugins/rebel59/countdown/assets/dist/vendor/countdown/jquery.countdown.min.js');

        if($this->property('init'))
            $this->addJs('/plugins/rebel59/countdown/assets/dist/app.countdown.min.js');
    }

    public function onCountdownDate(){
        return ['date' => $this->property('date')];
    }

}