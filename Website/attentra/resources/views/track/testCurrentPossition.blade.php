<!DOCTYPE html>
<html>
<head>
    <title>search</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #map_wrapper {
            height: 400px;
        }

        #map_canvas {
            width: 100%;
            height: 700px;
        }
        .info_content
        {
            direction: rtl;
            font-size: 20px;
            font-weight: 500;
        }
    </style>
</head>

<body>
<div id="map_wrapper">
    <div id="map_canvas" class="mapping"></div>
</div>
<script src="{{URL::to('style/profile/test/js/jquery.js')}}"></script>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
    jQuery(function($) {
        // Asynchronously Load the map API
        var script = document.createElement('script');
        script.src = "//maps.googleapis.com/maps/api/js?sensor=false&callback=initialize";
        document.body.appendChild(script);
    });







    function initialize() {
        var map;
        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
            mapTypeId: 'roadmap'
        };




        var lat = '29.61720000';
        var lng = '52.54339833';

        // Display a map on the page
        map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        map.setTilt(45);

        // Multiple Markers
        var markers = [
            ['London Eye, London', lat,lng],
//                ['Palace of Westminster, London', 29.624738,52.516996],
//                ['London Eye, London', 29.621212,52.525869],
//                ['Palace of Westminster, London', 29.624001,52.524453],
//                ['London Eye, London', 29.625726,52.521503],
//                ['Palace of Westminster, London', 29.626957,52.522490]
        ];

        // Info Window Content
        var infoWindowContent = [
            ['<div class="info_content">' +
            '<h3>مامور بهداشت آقای اسکندر رحمانی</h3>' +
            '<h3>فعلا هیچ شکایتی نشده است</h3>' +
            '<p></p>' +        '</div>'],
            ['<div class="info_content">' +
            '<h3>قصابی گوشت تک</h3>' +
            '<p>آدرس : خیابان ملاصدرا - جنب درب شرقی پارک خلدبرین</p>' +
            '<a href="#">در صورتی که از این واحد شکایت دارید اینجا کلیک کنید</a>' +
            '</div>']
        ];

        // Display multiple markers on a map
        var infoWindow = new google.maps.InfoWindow(), marker, i;

        // Loop through our array of markers & place each one on the map
        for( i = 0; i < markers.length; i++ ) {
            var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
            bounds.extend(position);
                    {{--if(i == 0)--}}
                    {{--var image = "{{URL::to('butcher.png')}}";--}}
                    {{--else--}}
                    {{--var image = "{{URL::to('person2.png')}}";--}}
            var image = 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png';
            marker = new google.maps.Marker({
                position: position,
                icon:image,
                map: map,
                title: markers[i][0]
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
            this.setZoom(17);
            google.maps.event.removeListener(boundsListener);
        });

    }
</script>



{{--<script async defer--}}
        {{--src="https://maps.googleapis.com/maps/api/js?key={{APIKEY}}&signed_in=true&callback=initMap">--}}

{{--</script>--}}

</body>

</html>
