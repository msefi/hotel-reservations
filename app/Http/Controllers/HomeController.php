<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Hotel;
use App\Models\Kabupaten;
use App\Models\Kamar;
use App\Models\Reservation;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function reservations(Request $request)
    {
        $city_id    = $request->get('city');
        $checkin    = $request->get('check_in');
        $checkout   = $request->get('check_out');

        $hotels = null;

        if($city_id) {
            $hotels = Hotel::where('kota_id', $city_id)->with('kabupaten')->get();
        }

        return view('reservations', [
            'hotels'    => $hotels,
            'checkin'   => $checkin,
            'checkout'  => $checkout
        ]);
    }

    public function rooms(Request $request)
    {
        $hotel_id   = $request->get('hotel_id');
        $checkin    = $request->get('check_in');
        $checkout   = $request->get('check_out');

        $hotels = null;
        $rooms  = null;

        $hotels = Hotel::where('id', $hotel_id)->with('kabupaten')->first();

        $rooms = Kamar::select('tipe_kamar', 'fasilitas', 'foto', 'harga')
                        ->where('hotel_id', $hotel_id)
                        ->whereNotExists(function ($query) use ($checkin, $checkout) {
                            $query->select('id')
                                ->from('reservations')
                                ->whereRaw('kamar_id = kamars.id')
                                ->where(function ($query) use ($checkin, $checkout) {
                                    $query->whereBetween('tanggal_masuk', [$checkin, $checkout])
                                        ->orWhereBetween('tanggal_keluar', [$checkin, $checkout])
                                        ->orWhere(function ($query) use ($checkin, $checkout) {
                                            $query->where('tanggal_masuk', '<=', $checkin)
                                                ->where('tanggal_keluar', '>=', $checkout);
                                        });
                                });
                        })
                        ->groupBy('tipe_kamar', 'fasilitas', 'foto', 'harga')
                        ->get();

        return view('rooms', [
            'hotels'    => $hotels,
            'rooms'     => $rooms,
            'checkin'   => $checkin,
            'checkout'  => $checkout
        ]);
    }

    public function booking(Request $request)
    {
        $hotel_id   = $request->get('hotel_id');
        $room_type  = $request->get('room_type');
        $checkin    = $request->get('check_in');
        $checkout   = $request->get('check_out');

        $hotels = null;
        $rooms  = null;

        $hotels = Hotel::where('id', $hotel_id)->with('kabupaten')->first();

        $rooms = Kamar::where('hotel_id', $hotel_id)
                        ->where('tipe_kamar', $room_type)
                        ->whereNotExists(function ($query) use ($checkin, $checkout) {
                            $query->select('id')
                                ->from('reservations')
                                ->whereRaw('kamar_id = kamars.id')
                                ->where(function ($query) use ($checkin, $checkout) {
                                    $query->whereBetween('tanggal_masuk', [$checkin, $checkout])
                                        ->orWhereBetween('tanggal_keluar', [$checkin, $checkout])
                                        ->orWhere(function ($query) use ($checkin, $checkout) {
                                            $query->where('tanggal_masuk', '<=', $checkin)
                                                ->where('tanggal_keluar', '>=', $checkout);
                                        });
                                });
                        })
                        ->first();

        if ($request->isMethod('get')) {
            return view('booking', [
                'hotels'    => $hotels,
                'rooms'     => $rooms,
                'checkin'   => $checkin,
                'checkout'  => $checkout,
                'guest'     => null
            ]);
        }
        else {
            $checkin    = $request->post('check_in');
            $checkout   = $request->post('check_out');
            $jumlah_hari= Helper::GetTotalDays($checkin, $checkout);
            
            $reservation                = new Reservation();
            $reservation->hotel_id      = $rooms->hotel_id;
            $reservation->kamar_id      = $rooms->id;
            $reservation->customer_id   = auth('customer')->user()->id;
            $reservation->tanggal_masuk = $checkin;
            $reservation->tanggal_keluar= $checkout;
            $reservation->jumlah_tamu   = $request->post('guest');
            $reservation->total_harga   = $jumlah_hari * $rooms->harga;

            $reservation->save();

            $room           = Kamar::find($rooms->id);
            $room->status   = 'dipesan';
            $room->save();

            return redirect()->route('myreservations');
        }
    }

    public function myReservations()
    {
        $data   = Reservation::where('customer_id', auth('customer')->user()->id)->with('hotel', 'room')->latest()->paginate();

        return view('myreservations', [
            'reservations'  => $data  
        ]);
    }

    public function reschedule(Request $request, $reservation_id)
    {
        $reservation    = Reservation::where('id', $reservation_id)->where('customer_id', auth('customer')->user()->id)->first();
        $hotel          = Hotel::find($reservation->hotel_id);
        $room           = Kamar::find($reservation->kamar_id);

        if ($request->isMethod('get')) {
            return view('booking', [
                'hotels'    => $hotel,
                'rooms'     => $room,
                'checkin'   => $reservation->tanggal_masuk,
                'checkout'  => $reservation->tanggal_keluar,
                'guest'     => $reservation->jumlah_tamu
            ]);
        }
        else {
            $checkin    = $request->post('check_in');
            $checkout   = $request->post('check_out');
            $jumlah_hari= Helper::GetTotalDays($checkin, $checkout);

            $reservation->tanggal_masuk = $checkin;
            $reservation->tanggal_keluar= $checkout;
            $reservation->jumlah_tamu   = $request->post('guest');
            $reservation->total_harga   = $jumlah_hari * $room->harga;
            $reservation->save();

            return redirect()->route('myreservations');
        }
    }

    public function locations(Request $request)
    {
        $data   = Kabupaten::Autocomplete($request->get('search'));

        return response()->json($data);
    }
}
