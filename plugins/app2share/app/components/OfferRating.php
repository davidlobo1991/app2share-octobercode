<?php namespace App2share\App\Components;

use App2share\App\Models\Offer;
use App2share\App\Models\OfferRating as OfferRatingModel;
use Cms\Classes\ComponentBase;
use Hybridauth\Exception\Exception;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use October\Rain\Support\Facades\Flash;

class OfferRating extends ComponentBase
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

    public function onRender()
    {
        $comments = OfferRatingModel::where('offer_id', $this->property('offer'))
            ->orderBy('created_at', 'asc')
            ->get();

        $this->page['comments'] = $comments;
    }

    public function onSaveValoration() {

        try {
            $contactPartner = new OfferRatingModel();
            $request = Input::all();
            $contactPartner->fill($request);
            $contactPartner->save();

            try {
                $comments = OfferRatingModel::where('offer_id', $request['offer_id'])
                    ->orderBy('created_at', 'asc')
                    ->get();
            } catch (\Exception $e) {
               Log::error($e->getMessage());
            }

            $this->page['comments'] = $comments;


            Flash::success('Voto registrado correctamente');
        } catch (\Exception $e) {
            Log::error($e);
            Flash::error('Error al registrar la votación. Pongase en contacto con info@app2share.es para más información');
        }
    }
}
