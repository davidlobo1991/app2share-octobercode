<?php namespace App2share\App\Components;

use Cms\Classes\ComponentBase;
use Hybridauth\Exception\Exception;
use Hybridauth\User\Contact;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use October\Rain\Exception\AjaxException;
use October\Rain\Support\Facades\Flash;
use App2share\App\Models\ContactPartner as ContactPartnerModel;

class ContactPartner extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'ContactPartner Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onSaveContact() {

        try {
            $contactPartner = new ContactPartnerModel();
            $contactPartner->fill(Input::all());
            $contactPartner->save();
            Flash::success('Mensaje enviado correctamente! Partner');
        } catch (\Exception $e) {
            Log::error($e);
            Flash::error('Error al enviar el mensaje Partner. Pongase en contacto con info@app2share.es para más información');
        }
    }
}
