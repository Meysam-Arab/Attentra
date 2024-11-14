<!DOCTYPE html>
<html>
<head>
    <title>Title of the document</title>
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

        // Display a map on the page
        map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        map.setTilt(45);

        // Multiple Markers
//        29.622369, 52.513858
//        29.624738, 52.516996
//        29.621212, 52.525869
//        29.624001, 52.524453
//        29.625726, 52.521503
//        29.626957, 52.522490
//
//        29.637877, 52.486149
//        29.629193, 52.487523
//        29.614487, 52.474849
//        29.613721, 52.490459
        var markers = [
            ['قصابی نوید, شیراز', 29.622369,52.513858],
            ['قصابی قصردشت, شیراز', 29.624738,52.516996],
            ['قصابی جعفر, شیراز', 29.621212,52.525869],
            ['قصابی زند, شیراز', 29.624001,52.525869],
            ['قصابی امید, شیراز', 29.625726,52.521503],
            ['کبابی قصردشت, شیراز', 29.621297,52.522490],
            ['کبابی نوید, شیراز', 29.637877, 52.486149],
            ['مرغ فروشی قصردشت, شیراز',29.629193, 52.487523],
            ['مرغ فروشی نوید, شیراز', 29.614487, 52.474849],
            ['مرغ فروشی قصردشت, شیراز', 29.613721, 52.490459],

            ['قصابی نوید, شیراز', 29.612369,52.513858],
            ['قصابی قصردشت, شیراز', 29.614738,52.516996],
            ['قصابی جعفر, شیراز', 29.611212,52.525869],
            ['قصابی زند, شیراز', 29.614001,52.525869],
            ['قصابی امید, شیراز', 29.615726,52.521503],
            ['کبابی قصردشت, شیراز', 29.611297,52.522490],
            ['کبابی نوید, شیراز', 29.617877, 52.486149],
            ['مرغ فروشی قصردشت, شیراز',29.619193, 52.487523],
            ['مرغ فروشی نوید, شیراز', 29.634487, 52.474849],
            ['مرغ فروشی قصردشت, شیراز', 29.643721, 52.490459],

            ['قصابی نوید, شیراز', 29.612369,52.533858],
            ['قصابی قصردشت, شیراز', 29.614738,52.536996],
            ['قصابی جعفر, شیراز', 29.611212,52.545869],
            ['قصابی زند, شیراز', 29.614001,52.545869],
            ['قصابی امید, شیراز', 29.615726,52.541503],
            ['کبابی قصردشت, شیراز', 29.611297,52.542490],
            ['کبابی نوید, شیراز', 29.617877, 52.446149],
            ['مرغ فروشی قصردشت, شیراز',29.619193, 52.447523],
            ['مرغ فروشی نوید, شیراز', 29.634487, 52.424849],
            ['مرغ فروشی قصردشت, شیراز', 29.643721, 52.410459],

        ];

        // Info Window Content
        var infoWindowContent = [
            ['<div class="info_content">' +
            '<h3>گاوداری برادران زارع</h3>' +
            '<p>چهار راه قصردشت - بلوار بهشتی - کوچه 25 </p>' +
            '<h3>در صورتی که شکایتی دارید <a href="#">اینجا</a> را کلیک کنید</h3>' +
            '</div>'],
            ['<div class="info_content">' +
            '<h3>Palace of Westminster</h3>' +
            '<p>The Palace of Westminster is the meeting place of the House of Commons and the House of Lords, the two houses of the Parliament of the United Kingdom. Commonly known as the Houses of Parliament after its tenants.</p>' +
            '</div>']
        ];

        // Display multiple markers on a map
        var infoWindow = new google.maps.InfoWindow(), marker, i;

        // Loop through our array of markers & place each one on the map
        for( i = 0; i < markers.length; i++ ) {
            var image='';
            if(i%3==0)
                 image = "{{URL::to('icons/cow.png')}}";
            if(i%3==1)
                 image = "{{URL::to('icons/chicken.png')}}";
            if(i%3==2)
                 image = "{{URL::to('icons/butcher.png')}}";
            var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
            bounds.extend(position);
            marker = new google.maps.Marker({
                position: position,
                map: map,
                icon:image,
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
            this.setZoom(14);
            google.maps.event.removeListener(boundsListener);
        });

    }
</script>



<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMBCC_pf0JtSxH0gvs4_84PXVCL7ufOfE&signed_in=true&callback=initMap">

</script>

</body>

</html>
