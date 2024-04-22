<?php

use App\Helpers\Helper;

?>
@extends('layouts/app')

@section('content')
    <section class="site-hero overlay" data-stellar-background-ratio="0.5" style="background-image: url(/images/big_image_1.jpg);">
      <div class="container">
        <div class="row align-items-center site-hero-inner justify-content-center">
          <div class="col-md-12 text-center">

            <div class="mb-5 element-animate">
              <h1>{{$hotels->nama_hotel}}</h1>
                @if($rooms)
                    <p><a href="#rooms" class="btn btn-primary">Select Room</a></p>
                @else
                    <p>No Rooms Available</p>
                    <p><a href="/reservations" class="btn btn-primary">Back</a></p>
                @endif
            </div>

          </div>
        </div>
      </div>
    </section>
    
    @if($rooms)
    <section id="rooms" class="site-section">
      <div class="container">
        <div class="row">
            @foreach($rooms as $room)
            <div class="col-md-4 mb-4">
                <div class="media d-block room mb-0">
                <div class="img-responsive-16by9">
                    <img src="/storage/{{$room->foto[0]}}" alt="Generic placeholder image" class="img-fluid">
                </div>
                <div class="media-body">
                    <h3 class="mt-0"><a href="#">{{ucwords($room->tipe_kamar)}} Room</a></h3>
                    <ul class="room-specs">
                        @foreach($room->fasilitas as $data)
                            <li>{!!Helper::FasilitasIcon($data)!!}</li>
                        @endforeach
                    </ul>
                    <p>Rp. {{number_format($room->harga)}}</p>
                    <p><a href='{{url("/booking?hotel_id=$hotels->id&room_type=$room->tipe_kamar&check_in=$checkin&check_out=$checkout")}}' class="btn btn-primary btn-sm">Book Now</a></p>
                </div>
                </div>
            </div>
            @endforeach
        </div>
      </div>
    </section>
    @endif
@endsection