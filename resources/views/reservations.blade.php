@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
    var path = "{{ route('autocomplete-locations') }}";
  
    $( "#location" ).autocomplete({
        source: function( request, response ) {
          $.ajax({
            url: path,
            type: 'GET',
            dataType: "json",
            data: {
               search: request.term
            },
            success: function( data ) {
               response( data );
            }
          });
        },
        select: function (event, ui) {
           $('#location').val(ui.item.label);
           $('#city').val(ui.item.id);
           return false;
        }
      });
  
</script>
@endsection

@section('content')
    <section class="site-hero overlay" data-stellar-background-ratio="0.5" style="background-image: url(/images/big_image_1.jpg);">
      <div class="container">
        <div class="row align-items-center site-hero-inner justify-content-center">
          <div class="col-md-12 text-center">

            <div class="mb-5 element-animate">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Search Hotels</h5>
                        <form action="#" method="GET">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="location" class="form-label">City</label>
                                    <input type="text" class="form-control" id="location" autocomplete="off" placeholder="Enter City">
                                    <input type="hidden" name="city" id="city">
                                </div>
                                <div class="col-md-3">
                                    <label for="check-in" class="form-label">Check-in Date</label>
                                    <input type="date" class="form-control" id="check-in" name="check_in" value="{{$checkin}}">
                                </div>
                                <div class="col-md-3">
                                    <label for="check-out" class="form-label">Check-out Date</label>
                                    <input type="date" class="form-control" id="check-out" name="check_out" value="{{$checkout}}">
                                </div>
                                <div class="col-md-3 pt-4">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                        </form>
                      </div>
                    </div>
                  </div>

          </div>
        </div>
      </div>
    </section>
    
    @if($hotels)
    <section class="site-section">
      <div class="container">
        <div class="row">
          @if(count($hotels) > 0)
            @foreach($hotels as $hotel)
            <div class="col-md-4 mb-4">
                <div class="media d-block room mb-0">
                <figure class="img-responsive-16by9">
                    <img src="/storage/{{$hotel->foto[0]}}" alt="Generic placeholder image" class="img-fluid">
                    <div class="overlap-text">
                    <span>
                        @for($i = 0; $i < $hotel->rating; $i++)
                        <span class="ion-ios-star"></span>
                        @endfor
                    </span>
                    </div>
                </figure>
                <div class="media-body">
                    <h3 class="mt-0"><a href="#">{{$hotel->nama_hotel}}</a></h3>
                    <ul class="room-specs">
                        <li><span class="ion-ios-location-outline"></span>{{$hotel->kabupaten->name}}</li>
                    </ul>
                    <p>{{$hotel->alamat}}</p>
                    <p><a href='{{url("/rooms?hotel_id=$hotel->id&check_in=$checkin&check_out=$checkout")}}' class="btn btn-primary btn-sm">Choose Room</a></p>
                </div>
                </div>
            </div>
            @endforeach
          @else
            <div class="mb-5 element-animate">
              <h1>No Hotels Found</h1>
            </div>
          @endif
        </div>
      </div>
    </section>
    @endif
@endsection