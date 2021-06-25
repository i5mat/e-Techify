<!DOCTYPE html>
<html>
<head>
    <title>Travel Modes in Directions</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <!-- jsFiddle will insert css and js -->

    <style>
        /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
        #map {
            height: 300px;
            width: 100%;
        }
    </style>
</head>
<body>
<div id="floating-panel">
    <b>Mode of Travel: </b>
    <select id="mode">
        <option value="DRIVING">Driving</option>
        <option value="WALKING">Walking</option>
        <option value="BICYCLING">Bicycling</option>
        <option value="TRANSIT">Transit</option>
    </select>
</div>
<div id="map"></div>

<!-- Async script executes immediately and must be after any DOM elements used in callback. -->
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7jC89-qmnCWo2FSQy8zg0LxOvNlncp9I&callback=initMap&libraries=&v=weekly"
    async
></script>
</body>

<script>
    function initMap() {
        const directionsRenderer = new google.maps.DirectionsRenderer();
        const directionsService = new google.maps.DirectionsService();
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 14,
            center: { lat: 3.22097, lng: 101.724 },
        });
        directionsRenderer.setMap(map);
        calculateAndDisplayRoute(directionsService, directionsRenderer);
    }

    function calculateAndDisplayRoute(directionsService, directionsRenderer) {
        const selectedMode = document.getElementById("mode").value;
        directionsService.route(
            {
                origin: { lat: 3.22097, lng: 101.724 },
                destination: { lat: {{ $recipientInfo->longitude }}, lng: {{ $recipientInfo->latitude }} },
                // Note that Javascript allows us to access the constant
                // using square brackets and a string value as its
                // "property."
                travelMode: google.maps.TravelMode[selectedMode],
            },
            (response, status) => {
                if (status == "OK") {
                    directionsRenderer.setDirections(response);
                } else {
                    window.alert("Directions request failed due to " + status);
                }
            }
        );
    }
</script>

</html>
