<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Transit layer</title>
    <style>
        html,
        body {
            height: 100%;
            width: 80%;
            margin: 0 auto;
            padding: 0;
        }
        #map-canvas {
            height: 100%;
            width: 100%;
        }
    </style>
   <script>
        function initMap() {
            var pointA = new google.maps.LatLng(29.622369, 52.513858),
                pointB = new google.maps.LatLng(29.624738, 52.526996),
                myOptions = {
                    zoom: 16,
                    center: pointA
                },
                map = new google.maps.Map(document.getElementById('map-canvas'), myOptions),
                // Instantiate a directions service.
                directionsService = new google.maps.DirectionsService,
                directionsDisplay = new google.maps.DirectionsRenderer({
                    map: map
                }),

                

                markerA = new google.maps.Marker({
                    position: pointA,
                    title: "خونه خاله",
                    label: "مبدا",
                    map: map,

                }),
                markerB = new google.maps.Marker({
                    position: pointB,
                    title: "point B",
                    label: "مقصد",
                    map: map,
                    icon:"{{URL::to('chicken.png')}}",

                });

            // get route from A to B
            calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB);
            // Allow each marker to have an info window

        }



        function calculateAndDisplayRoute(directionsService, directionsDisplay, pointA, pointB) {
            directionsService.route({
                origin: pointA,
                destination: pointB,
                travelMode: google.maps.TravelMode.WALKING,
            }, function(response, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(response);
                } else {
                    window.alert('Directions request failed due to ' + status);
                }
            });
        }

        initMap();
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMBCC_pf0JtSxH0gvs4_84PXVCL7ufOfE&signed_in=true&callback=initMap">

    </script>
</head>
<body>

<div id="map-canvas"></div>
</body>
</html>