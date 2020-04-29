<?php namespace App2share\App\Components;

use App2share\App\Models\Offer;
use App2share\App\Models\OfferRating as OfferRatingModel;
use App2share\App\Models\OfferUser;
use Cms\Classes\ComponentBase;
use Hybridauth\Exception\Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use October\Rain\Exception\AjaxException;
use October\Rain\Support\Facades\Flash;
use RainLab\User\Facades\Auth;
use ValidationException;
use SystemException;
use Validator;

class UserOffer extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'OfferUser Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onUserOffer()
    {

        try {

            $request = Input::all();

            $rules = [
                'ip_ct_number' => 'same:ct_number'
            ];

            $validation = Validator::make($request, $rules);

            if ($validation->fails()) {
                Flash::error('Los números introducidos no concuerdan. Vuelva a introducirlos.');
                throw new ValidationException($validation);
            }


            $user = Auth::getUser();

            $offerUser = OfferUser::where('user_id', $user->id)
                ->where('offer_id', $request['offer_id'])
                ->where('created_at', '<', Carbon::now())
                ->where('valid_to', '>', Carbon::now())
                ->first();

            if ($offerUser) {
                $validTo = new Carbon($offerUser->valid_to);
                $hoursLeft = $validTo->diff(Carbon::now())->format('%H:%I:%S');

                $this->page['offerUser'] = $offerUser;

                Flash::error('Ya se usado esta oferta hoy. Podrá volver a usar la oferta en ' . $hoursLeft);
                return;
            }

            $now = new \DateTime();
            $validTo = $now->modify('+1 day');

            $offerUser = new OfferUser();
            $offerUser->user_id = $user->id;
            $offerUser->offer_id = $request['offer_id'];
            $offerUser->valid_to = $validTo;
            $offerUser->save();

            $this->page['offerUser'] = $offerUser;

            Flash::success('Oferta usada. Muchas gracias por confiar en App2Share');

            return [
                'validTo' => $offerUser->valid_to
            ];

        } catch (\Exception $e) {
            Log::error($e);
            Flash::error('Error al usar la oferta. Pongase en contacto con info@app2share.es para más información');
        }
    }
}
