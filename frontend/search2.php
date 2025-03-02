<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Locator with District Search</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
        }
        #container {
            display: flex;
            height: 100vh;
        }
        #left-panel {
            width: 30%;
            background-color: #f4f4f4;
            padding: 20px;
            overflow-y: auto;
        }
        .bus-card {
            background-color: white;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .bus-card h3 {
            margin-bottom: 10px;
        }
        .bus-card p {
            margin: 5px 0;
        }
        #map {
            width: 70%;
            height: 100%;
        }
        #destination-container {
            margin-bottom: 20px;
        }
        #destination-input {
            width: 70%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
        }
        #set-destination {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        #set-destination:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div id="container">
    <div id="left-panel">
        <div id="destination-container">
            <h3>Enter Destination (District in Tamil Nadu)</h3>
            <input type="text" id="destination-input" placeholder="Enter district name...">
            <button id="set-destination">Set Destination</button>
        </div>
        <div id="bus-list">
            <!-- Bus details will be dynamically added here -->
        </div>
    </div>
    <div id="map"></div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
<script>
    // Initialize the map
    var map = L.map('map').setView([13.0827, 80.2707], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Variables for user and destination locations
    let userLat = null, userLng = null;
    let destinationLat = null, destinationLng = null;
    let routingControl = null;
    let userMarker = null;

    // DOM elements
    const destinationInput = document.getElementById('destination-input');
    const setDestinationButton = document.getElementById('set-destination');
    const busList = document.getElementById('bus-list');

    // Function to calculate the route
    function calculateRoute() {
        if (!userLat || !userLng || !destinationLat || !destinationLng) {
            alert('User location or destination not available.');
            return;
        }

        // Clear existing route
        if (routingControl) map.removeControl(routingControl);

        // Add routing control
        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(userLat, userLng),
                L.latLng(destinationLat, destinationLng)
            ],
            routeWhileDragging: true
        }).addTo(map);

        // Center the map on the route
        map.fitBounds([
            [userLat, userLng],
            [destinationLat, destinationLng]
        ]);
    }

    // Function to display bus details
    function displayBusDetails(destination) {
        // Sample bus data for 10 districts in Tamil Nadu
        const districts = [
            "Chennai", "Coimbatore", "Madurai", "Tiruchirappalli", "Salem",
            "Tirunelveli", "Vellore", "Erode", "Thoothukudi", "Dindigul"
        ];

        // Generate unique bus details for each district
        const buses = districts.map((district, index) => ({
            id: index + 1,
            number: TN${index + 1}A,
            route: From Your Location to ${destination} via ${district},
            timing: ${8 + index}:00 AM,
            seats: Math.floor(Math.random() * 20) + 1 // Random seats between 1 and 20
        }));

        // Clear existing bus details
        busList.innerHTML = '';

        // Display bus details
        buses.forEach(bus => {
            const busCard = document.createElement('div');
            busCard.className = 'bus-card';
            busCard.innerHTML = `
                <h3>Bus ${bus.number}</h3>
                <p><strong>Route:</strong> ${bus.route}</p>
                <p><strong>Timing:</strong> ${bus.timing}</p>
                <p><strong>Seats Available:</strong> ${bus.seats}</p>
            `;
            busList.appendChild(busCard);
        });
    }

    // Set destination and calculate route
    setDestinationButton.addEventListener('click', function() {
        const destination = destinationInput.value.trim();
        if (destination) {
            // Geocode the destination address (using a geocoding service like Nominatim)
            fetch(https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(destination + ", Tamil Nadu")})
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        destinationLat = parseFloat(data[0].lat);
                        destinationLng = parseFloat(data[0].lon);
                        calculateRoute();
                        displayBusDetails(destination);
                    } else {
                        alert('Destination not found. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error geocoding destination:', error);
                    alert('Error finding destination. Please try again.');
                });
        } else {
            alert('Please enter a valid destination.');
        }
    });

    // Get user's current location
    navigator.geolocation.getCurrentPosition(
        function(position) {
            userLat = position.coords.latitude;
            userLng = position.coords.longitude;

            // Add marker for the user's live location
            userMarker = L.marker([userLat, userLng])
                .addTo(map)
                .bindPopup("You are here")
                .openPopup();

            // Center the map on the user's location
            map.setView([userLat, userLng], 14);
        },
        function(error) {
            alert('Error getting your location. Please enable location access.');
        },
        {
            enableHighAccuracy: true,
            timeout: 20000,
            maximumAge: 0
        }
    );
</script>
</body>
</html>