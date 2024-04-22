<?php

namespace App\Helpers;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Provinsi;
use DateTime;

class Helper extends Controller {

    public static function ListTipeKamar() {
        $tipeKamarHotel = [
            'single' => 'Single Room',
            'double' => 'Double Room',
            'twin' => 'Twin Room',
            'suite' => 'Suite',
            'deluxe' => 'Deluxe Room'
        ];

        return $tipeKamarHotel;
    }

    public static function ListFasilitasKamar() {
        $fasilitasKamarHotel = [
            'wifi' => 'Wi-Fi',
            'tv' => 'TV',
            'ac' => 'Air Conditioning',
            'mini_bar' => 'Mini Bar',
            'safe_box' => 'Safe Box',
            'hair_dryer' => 'Hair Dryer'
        ];
        
        return $fasilitasKamarHotel;
    }

    public static function GenerateNoKamar($hotel_id) {
        $nomorKamarTertinggi = Kamar::where('hotel_id', $hotel_id)
            ->max('nomor_kamar');

        if ($nomorKamarTertinggi === null) {
            return '111';
        }

        $nomorKamarBaru = (int) $nomorKamarTertinggi + 1;

        return $nomorKamarBaru;
    }

    public static function FasilitasIcon($fasilitas) {
        $label  = ucwords(str_replace('_', ' ', $fasilitas));


        switch ($fasilitas) {
            case 'wifi':
                $icon_code = "fa-wifi";
                break;
            case 'tv':
                $icon_code = "fa-tv";
                break;
            case 'ac':
                $icon_code = "fa-snowflake>";
                break;
            case 'mini_bar':
                $icon_code = "fa-glass";
                break;
            case 'safe_box':
                $icon_code = "fa-lock";
                break;
            case 'hair_dryer':
                $icon_code = "fa-scissors";
                break;
        }

        $icon   = '<i class="fa '.$icon_code.'" aria-hidden="true"></i>';

        $output = $icon .' '. $label;

        return $output;
    }

    public static function GetTotalDays($checkin, $checkout) {
        $checkin_obj    = new DateTime($checkin);
        $checkout_obj   = new DateTime($checkout);
    
        $selisih = $checkout_obj->diff($checkin_obj);
    
        $totalHari = $selisih->days;

        if($totalHari == 0) {
            $totalHari = 1;
        }
    
        return $totalHari;
    }


}