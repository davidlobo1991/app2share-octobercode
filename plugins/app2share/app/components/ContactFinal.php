<?php namespace App2share\App\Components;

use App2share\App\Models\ContactFinal as ContactFinalModel;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use October\Rain\Support\Facades\Flash;

class ContactFinal extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'ContactFinal Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onSaveContact() {
        try {
            $contactFinal = new ContactFinalModel();
            $contactFinal->fill(Input::all());
            $contactFinal->save();
            Flash::success('Mensaje enviado correctamente!');
        } catch (\Exception $e) {
            Log::error($e);
            Flash::error('Error al enviar el mensaje del cliente final. Pongase en contacto con info@app2share.es para más información');
        }
    }
}
