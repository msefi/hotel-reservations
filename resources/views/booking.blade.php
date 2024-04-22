<?php

use App\Helpers\Helper;

?>
@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js" integrity="sha512-Ixzuzfxv1EqafeQlTCufWfaC6ful6WFqIz4G+dWvK0beHw0NVJwvCKSgafpy5gwNqKmgUfIBraVwkKI+Cz0SEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.owl-carousel').owlCarousel({
            items:1,
            lazyLoad:true,
            loop:true,
            margin:10
        });

        function hitungTotalHarga() {
            var checkin = new Date($('#check-in').val());
            var checkout= new Date($('#check-out').val());

            if (checkout < checkin) {
                alert('Tanggal check-out harus setelah tanggal check-in');
                $('#check-out').val('');
                $('#total_price').text('Rp. ' + 0);
                return;
            }

            var selisihHari     = (checkout - checkin) / (1000 * 3600 * 24);
            if(selisihHari == 0) {
                selisihHari = 1;
            }
            
            var hargaPermalam   = parseInt($('#price').data('price'));

            var totalHarga      = hargaPermalam * selisihHari;

            $('#total_price').text('Rp. ' + totalHarga.toLocaleString());
        }

        $('#check-in, #check-out').on('change', function() {
            hitungTotalHarga();
        });

        hitungTotalHarga();
    });

</script>
@endsection

@section('content')
    <section class="site-hero overlay" data-stellar-background-ratio="0.5" style="background-image: url(/images/big_image_1.jpg);">
      <div class="container">
        <div class="row align-items-center site-hero-inner justify-content-center">
          <div class="col-md-12 text-center">

            <div class="mb-5 element-animate">
              <h1>{{$hotels->nama_hotel}}</h1>
              <p>{{ucwords($rooms->tipe_kamar)}} Room</p>
            </div>

          </div>
        </div>
      </div>
    </section>

    <section class="site-section">
      <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h3 class="mb-5">{{ucwords($rooms->tipe_kamar)}} Room</h3>
                <div class="row mb-5">
                    <div class="owl-carousel owl-theme">
                        @foreach($rooms->foto as $foto)
                            <div class="img-responsive-16by9">
                                <a href="/storage/{{$foto}}" data-lightbox="rooms">
                                    <img src="/storage/{{$foto}}" alt="Generic placeholder image" class="img-fluid">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <h6>Room Facilities</h6>
                <ul class="room-specs">
                    @foreach($rooms->fasilitas as $data)
                        <li>{!!Helper::FasilitasIcon($data)!!}</li>
                    @endforeach
                </ul>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vitae labore aspernatur cumque inventore voluptatibus odit doloribus! Ducimus, animi perferendis repellat. Ducimus harum alias quas, quibusdam provident ea sed, sapiente quo.</p>
                <p>Ullam cumque eveniet, fugiat quas maiores, non modi eos deleniti minima, nesciunt assumenda sequi vitae culpa labore nulla! Cumque vero, magnam ab optio quidem debitis dignissimos nihil nesciunt vitae impedit!</p>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
                <h2 class="mb-5">Booking Form</h2>
                <form action="#" method="post">
                    @csrf
                    <div class="col-md-12 mb-3">
                        <label for="price" class="form-label">Price</label>
                        <h6 id="price" data-price="{{$rooms->harga}}">Rp. {{number_format($rooms->harga)}} / Night</h6>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="check-in" class="form-label">Check-in Date</label>
                        <input type="date" class="form-control" id="check-in" name="check_in" value="{{ $checkin }}">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="check-out" class="form-label">Check-out Date</label>
                        <input type="date" class="form-control" id="check-out" name="check_out" value="{{ $checkout }}">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="guest" class="form-label">Guest</label>
                        <input type="number" class="form-control" id="guest" name="guest" min="1" value="{{ $guest }}">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="total_price" class="form-label">Total Price</label>
                        <h6 id="total_price"></h6>
                    </div>
                    <div class="col-md-12 mb-3">
                        <h6><i>Checkin / Checkout : 12:00 PM</i></h6>
                    </div>
                    <div class="col-md-12">
                        @php
                            $currentRoute = \Illuminate\Support\Facades\Route::currentRouteName();
                        @endphp
                        @if($currentRoute == 'reschedule')
                            <input type="submit" value="Reschedule" class="btn btn-primary">
                        @else
                            <input type="submit" value="Book Now" class="btn btn-primary">
                        @endif
                    </div>
                </form>
            </div>
        </div>
      </div>
    </section>
@endsection