<?php namespace App2share\App\Components;

use Auth;
use Cms\Classes\ComponentBase;
use Event;
use Redirect;

class NeedsSubscription extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'offline.cashier::lang.components.needsSubscription.name',
            'description' => 'offline.cashier::lang.components.needsSubscription.description',
        ];
    }

    public function defineProperties()
    {
        return [
            'redirect'     => [
                'type'        => 'string',
                'title'       => 'offline.cashier::lang.components.needsSubscription.properties.redirect.title',
                'description' => 'offline.cashier::lang.components.needsSubscription.properties.redirect.description',
            ],
            'subscription' => [
                'type'        => 'text',
                'title'       => 'offline.cashier::lang.components.needsSubscription.properties.subscription.title',
                'description' => 'offline.cashier::lang.components.needsSubscription.properties.subscription.description',
                'default'     => 'main',
            ],
        ];
    }

    public function onRun()
    {
        $user   = Auth::getUser();
        $checks = collect(Event::fire('offline.cashier::subscription.check', [$user, $this]));

        if ($user && $user->subscribed('main')) {
            $this->page['subscribed'] = true;
        } else {
            $this->page['subscribed'] = false;
        }

      /*  if ($checks->contains(true)) {
            $this->page['subscribed'] = true;
        }

        if ($checks->contains(false)) {
            $this->page['subscribed'] = false;
        }*/

    }

    protected function redirect()
    {
        return Redirect::to($this->property('redirect'));
    }
}
