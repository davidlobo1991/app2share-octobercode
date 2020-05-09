<?php namespace App2share\App\Components;

use App2share\App\Models\SubscriptionsFreePack;
use Auth;
use Carbon\Carbon;
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
        $now = Carbon::now();

        $freeSubscription = SubscriptionsFreePack::where('user_id', $user->id)
            ->where('date_start', '<=', $now)
            ->where('date_end', '>=', $now)
            ->where('is_paid', true)
            ->first();

        if ($user && ($user->subscribed('main') || $freeSubscription) ) {
            $this->page['subscribed'] = true;
        } else {
            $this->page['subscribed'] = false;
        }
    }

    protected function redirect()
    {
        return Redirect::to($this->property('redirect'));
    }
}
