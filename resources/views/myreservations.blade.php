@extends('layouts/app')

@section('content')
    <section class="site-hero overlay" data-stellar-background-ratio="0.5" style="background-image: url(/images/big_image_1.jpg);">
      <div class="container">
        <div class="row align-items-center site-hero-inner justify-content-center">
          <div class="col-md-12 text-center">

            <div class="mb-5 element-animate">
              <h1>My Reservations</h1>
              <p>Booking History & Reschedule</p>
            </div>

          </div>
        </div>
      </div>
    </section>

    <section class="site-section">
      <div class="container">
        <div class="row">
            <div class="table-responsive-lg col-12">
                <table class="table table-borderless">
                    <thead>
                        <tr>
                            <th>Checkin</th>
                            <th>Checkout</th>
                            <th>Hotel Name</th>
                            <th>Room Type</th>
                            <th>Room Number</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservations as $data)
                            <tr>
                                <td>{{ $data->tanggal_masuk }}</td>
                                <td>{{ $data->tanggal_keluar }}</td>
                                <td>{{ $data->hotel->nama_hotel }}</td>
                                <td>{{ ucwords($data->room->tipe_kamar) }} Room</td>
                                <td>{{ $data->room->nomor_kamar }}</td>
                                <td>
                                    @if(now() <= $data->tanggal_masuk)
                                        <a href='{{url("/reschedule/$data->id")}}' class="btn btn-primary btn-sm p-2">Reschedule</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $reservations->links() }}
            </div>
        </div>
      </div>
    </section>
@endsection