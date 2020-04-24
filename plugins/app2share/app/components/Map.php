<?php namespace App2share\App\Components;

use App2share\App\Models\Offer;
use App2share\App\Models\Partner;
use App2share\App\Models\PartnerType;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Config;
use function foo\func;

class Map extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'map Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {

        $offers = Offer::with('partner.partner_type')->with('offerRating')
            ->where('active', 1)
            ->orderBy('name', 'asc')
            ->get();

        foreach($offers as $offer) {
            $logoPartner = $offer->partner->logo;
            $pathLogoPartner = null;

            if ($logoPartner) {
                $pathLogoPartner = $logoPartner->getPath();
            } else {
                $pathLogoPartner = url('/') . '/themes/app2share/assets/image/imagenot.jpg';
            }

            $averageRating = 0;
            $offerRating = $offer->offerRating;


            if ($offerRating->count() > 0) {
                foreach ($offerRating as $rating) {
                    $averageRating += $rating->stars;
                }

                $offer['ratingAverage'] = $averageRating / $offerRating->count();
            } else {
                $offer['ratingAverage'] = 'none';
            }

            $offer->partner['image'] = $pathLogoPartner;
        }

        $partnerTypes = PartnerType::all();

        $partnerTypes->map(function ($type) {
            $type['logoPath'] = $type->logo->getPath();
            return $type;
        });

        $this->page['partnerTypes'] = $partnerTypes;
        $this->page['offers'] = $offers;
    }

}
