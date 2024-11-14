
@extends('layouts.profile')
@section('style')
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #map {
            height: 100%;
        }
    </style>
@stop


@section('content')
    <div id="map" style="width: 100%;height: 700px;background-image:url('{{URL::to('style/profile/img/bg/bg.png')}}');">لطفا منتظر بمانید . . . .</div>
    <?php
    $trackTime=array();
    $trackDate=array();
    for($i=0; $i<count($tracks); $i++){

        $temp=\Morilog\Jalali\jDateTime::strftime('Y/m/d', strtotime($tracks[$i]->created_at));
        $convertedTodate=\Morilog\Jalali\jDateTime::convertNumbers($temp);

        $temp=\Morilog\Jalali\jDateTime::strftime('H:i:s', strtotime($tracks[$i]->created_at));
        $convertedToTime=\Morilog\Jalali\jDateTime::convertNumbers($temp);


        $battery_power=$tracks[$i]->battery_power;
        $battery_status=$tracks[$i]->battery_status;
        $charge_status=$tracks[$i]->charge_status;
        $charge_type=$tracks[$i]->charge_type;
        $signal_power=$tracks[$i]->signal_power;
        array_push($trackTime,[$convertedTodate,$convertedToTime,$battery_power,$charge_status,$battery_status,$signal_power,$charge_type]);
    }



    ?>
@endsection

@section('script')




    <script type="text/javascript">
        // This example adds a predefined symbol (an arrow) to a polyline.
        // Setting offset to 100% places the arrow at the end of the line.

        function initMap() {
            var a = [@foreach($tracks as $track)
                '{{ $track->latitude }}','{{ $track->longitude }}',
                @endforeach ];
            var created_at=[@foreach($tracks as $track)
                '{{ $track->created_at }}',
                @endforeach ];
            var alaki=new google.maps.LatLng(29.833940, 52.326045);
            var myLatlng = new google.maps.LatLng(a[a.length-2], a[a.length-1]);


            {{--var image = "{{URL::to('person2.png')}}";--}}
            {{--var image = "{{public_path('icons\person.png')}}";--}}
            {{--var image="{{ route('icons.images', ['filename' =>'person.png'])}}";--}}
            {{--var image="{{File::get(storage_path('app\icons.png'))}}";--}}
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 14,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.TERRAIN
            });
            map.setTilt(45);

            // [START region_polyline]
            // Define a symbol using a predefined path (an arrow)
            // supplied by the Google Maps JavaScript API.
            var lineSymbol = {
                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW
            };
            //, {lat: 29.6122, lng: 52.5475},{lat: 29.6162, lng: 52.5495}, {lat: 29.6192, lng: 52.5415}
            // Create the polyline and add the symbol via the 'icons' property.
            for (i = 0; i < a.length; i+=2)
            {
                var firstPoint = new google.maps.LatLng(a[i], a[i+1]);
                var secondPoint = new google.maps.LatLng(a[i+2], a[i+3]);
                var line = new google.maps.Polyline({
                    path: [firstPoint, secondPoint],
                    icons: [{
                        icon: lineSymbol,
                        offset: '100%',
                        geodesic: true,
                        strokeColor: '#2e6da4',
                        strokeOpacity: 1.0,
                        strokeWeight: 2
                    }],
                    map: map
                });
                {{--if(i == 0)--}}
                    {{--var image = "{{URL::to('icons/butcher.png')}}";--}}
                    {{--else--}}
                    {{--var image = "{{URL::to('icons/person2.png')}}";--}}
//                var beachMarker = new google.maps.Marker({
//                    position: firstPoint,
//                    map: map,
//                    icon:image,
//                    animation: google.maps.Animation.DROP,
//                    title: created_at[i/2].toString()
//                });
//                beachMarker.addListener('click', toggleBounce);



            }
            var bounds = new google.maps.LatLngBounds();
            var mapOptions = {
                mapTypeId: 'roadmap'
            };
            var markers= [
                @foreach($tracks as $track)
                   ['{{ $track->latitude }}','{{ $track->longitude }}','{{ $track->created_at }}'],
                @endforeach
            ];


            var infoWindowContent = [

                    @foreach($trackTime as $track)
                [
                    '<div class="row"><div style="float:right;margin:1px" class="alert alert-success"><strong>' + '{{ $status_lable[5] }}' + ':</strong> '+'{{ $track[0]}}'+'</div>'+
                    '<div style="float:right;margin:1px" class="alert alert-success"><strong>' + '{{ $status_lable[6] }}' + ':</strong> '+'{{ $track[1]}}'+'</div></div>'+
                    '<div class="row"><div style="float:right;margin:1px" class="alert alert-info"><strong>' + '{{ $status_lable[0] }}' + ':</strong> '+'{{ $track[2]}}'+'%</div>'+
                    '<div style="float:right;margin:1px" class="alert alert-info"><strong>' + '{{ $status_lable[1] }}' + ':</strong> '+'{{ $track[3]}}'+'</div></div>'+
                    '<div class="row"><div style="float:right;margin:1px" class="alert alert-info"><strong>' + '{{ $status_lable[2] }}' + ':</strong> '+'{{ $track[4]}}'+'</div>'+
                    '<div style="float:right;margin:1px" class="alert alert-info"><strong>' + '{{ $status_lable[3] }}' + ':</strong> '+'{{ $track[5]}}'+'</div></div>'+
                    '<div class="row"><div style="float:right;margin:1px" class="alert alert-info"><strong>' + '{{ $status_lable[4] }}' + ':</strong> '+'{{ $track[6]}}'+'</div></div>'
               ],
                    @endforeach
            ];
            // Display multiple markers on a map
            var infoWindow = new google.maps.InfoWindow(), marker, i;

            // Loop through our array of markers & place each one on the map
            for( i = 0; i < markers.length; i++ ) {
                var image="{{ route('icons.images', ['filename' =>'person.png']) }}"
                var position = new google.maps.LatLng(markers[i][0], markers[i][1]);
                bounds.extend(position);
                marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    icon:image,
                    title: markers[i][2]
                });

                // Allow each marker to have an info window
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        infoWindow.setContent(infoWindowContent[i][0]);
                        infoWindow.open(map, marker);
                    }
                })(marker, i));

                // Automatically center the map fitting all markers on the screen
                map.fitBounds(bounds);
            }
            // Override our map zoom level once our fitBounds function runs (Make sure it only runs once)
            var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
                this.setZoom(14);
                google.maps.event.removeListener(boundsListener);
            });
        }

        function toggleBounce() {
            if (marker.getAnimation() !== null) {
                marker.setAnimation(null);
            } else {
                marker.setAnimation(google.maps.Animation.BOUNCE);
            }
        }
    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMBCC_pf0JtSxH0gvs4_84PXVCL7ufOfE&signed_in=true&callback=initMap">
    </script>
@stop
