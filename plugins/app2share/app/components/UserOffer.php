<?php namespace App2share\App\Components;

use App2share\App\Models\Offer;
use App2share\App\Models\OfferRating as OfferRatingModel;
use Cms\Classes\ComponentBase;
use Hybridauth\Exception\Exception;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use October\Rain\Support\Facades\Flash;

class UserOffer extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'OfferRating Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onUserOffer() {

        try {
            Flash::success('Que nooo, que aun no está acabado. Mañana seguro!');
        } catch (\Exception $e) {
            Log::error($e);
            Flash::error('Error al registrar la votación. Pongase en contacto con info@app2share.es para más información');
        }
    }
}
