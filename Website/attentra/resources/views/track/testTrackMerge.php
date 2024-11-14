<!DOCTYPE html>
<html>
<head>
    <title>Title of the document</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #map {

            margin: 30px auto;
            width: 1000px;
            height: 100%;
        }
    </style>
</head>

<body>
<div id="map"></div>

<script>

    // This example requires the Drawing library. Include the libraries=drawing
    // parameter when you first load the API. For example:


    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 29.622369, lng: 52.513858},
            zoom: 16
        });

        var drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: google.maps.drawing.OverlayType.MARKER,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: ['marker', 'circle', 'polygon', 'polyline', 'rectangle']
            },
            markerOptions: {icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'},

        });
        drawingManager.setMap(map);
    }
</script>


<script async defer
        src="https://maps.googleapis.com/maps/api/js?key={{APIKEY}}&signed_in=true&callback=initMap&libraries=drawing">

</script>

</body>

</html>
