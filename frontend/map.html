<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Locator with Start and Destination</title>
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
        #location-container {
            margin-bottom: 20px;
        }
        #start-input, #destination-input {
            width: 70%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            margin-bottom: 10px;
        }
        #set-location {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        #set-location:hover {
            background-color: #218838;
        }
        #suggestions-start, #suggestions-destination {
            margin-top: 10px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            max-height: 150px;
            overflow-y: auto;
            display: none;
            position: absolute;
            width: 70%;
            z-index: 1000;
        }
        .suggestion-item {
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .suggestion-item:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

<div id="container">
    <div id="left-panel">
        <div id="location-container">
            <h3>Enter Start Location</h3>
            <input type="text" id="start-input" placeholder="Enter start location...">
            <div id="suggestions-start"></div>
            <h3>Enter Destination</h3>
            <input type="text" id="destination-input" placeholder="Enter destination...">
            <div id="suggestions-destination"></div>
            <button id="set-location">Set Locations</button>
        </div>
        <div id="bus-list">
            <!-- Bus details will be dynamically added here -->
        </div>
    </div>
    <div id="map"></div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fuse.js@6.6.2"></script>
<script>
    // Initialize the map
    var map = L.map('map').setView([13.0827, 80.2707], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Variables for start and destination locations
    let startLat = null, startLng = null;
    let destinationLat = null, destinationLng = null;
    let routingControl = null;
    let startMarker = null;
    let destinationMarker = null;
    let busMarker = null;

    // DOM elements
    const startInput = document.getElementById('start-input');
    const destinationInput = document.getElementById('destination-input');
    const setLocationButton = document.getElementById('set-location');
    const busList = document.getElementById('bus-list');
    const suggestionsStart = document.getElementById('suggestions-start');
    const suggestionsDestination = document.getElementById('suggestions-destination');

    // List of districts in Tamil Nadu
    const districts = [
        "Chennai", "Coimbatore", "Madurai", "Tiruchirappalli", "Salem",
        "Tirunelveli", "Vellore", "Erode", "Thoothukudi", "Dindigul"
    ];

    // Initialize Fuse.js for fuzzy search
    const fuse = new Fuse(districts, {
        includeScore: true,
        threshold: 0.3, // Adjust threshold for fuzzy search
    });

    // Function to show suggestions
    function showSuggestions(query, suggestionsContainer) {
        if (query.length === 0) {
            suggestionsContainer.style.display = 'none';
            return;
        }

        const results = fuse.search(query);
        suggestionsContainer.innerHTML = results
            .map(result => <div class="suggestion-item">${result.item}</div>)
            .join('');
        suggestionsContainer.style.display = 'block';
    }

    // Event listener for start input changes
    startInput.addEventListener('input', function() {
        showSuggestions(this.value, suggestionsStart);
    });

    // Event listener for destination input changes
    destinationInput.addEventListener('input', function() {
        showSuggestions(this.value, suggestionsDestination);
    });

    // Event listener for suggestion clicks
    suggestionsStart.addEventListener('click', function(e) {
        if (e.target.classList.contains('suggestion-item')) {
            startInput.value = e.target.textContent;
            suggestionsStart.style.display = 'none';
        }
    });

    suggestionsDestination.addEventListener('click', function(e) {
        if (e.target.classList.contains('suggestion-item')) {
            destinationInput.value = e.target.textContent;
            suggestionsDestination.style.display = 'none';
        }
    });

    // Function to calculate the route
    function calculateRoute() {
        if (!startLat || !startLng || !destinationLat || !destinationLng) {
            alert('Start location or destination not available.');
            return;
        }

        // Clear existing route
        if (routingControl) map.removeControl(routingControl);

        // Add routing control
        routingControl = L.Routing.control({
            waypoints: [
                L.latLng(startLat, startLng), // Start location
                L.latLng(destinationLat, destinationLng) // Destination location
            ],
            routeWhileDragging: true
        }).addTo(map);

        // Center the map on the route
        map.fitBounds([
            [startLat, startLng],
            [destinationLat, destinationLng]
        ]);

        // Add bus symbol along the route
        const midpointLat = (startLat + destinationLat) / 2;
        const midpointLng = (startLng + destinationLng) / 2;

        const busIcon = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/3097/3097007.png', // Replace with your bus image URL
            iconSize: [40, 40], // Size of the icon
            iconAnchor: [20, 40], // Point of the icon which will correspond to marker's location
        });

        if (busMarker) map.removeLayer(busMarker); // Remove existing bus marker
        busMarker = L.marker([midpointLat, midpointLng], { icon: busIcon })
            .addTo(map)
            .bindPopup("Bus Location")
            .openPopup();
    }

    // Function to display bus details
    function displayBusDetails(startLocation, destination) {
        // Generate unique bus details for each district
        const buses = districts.map((district, index) => ({
            id: index + 1,
            number: TN${index + 1}A,
            route: From ${startLocation} to ${destination},
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

    // Set start and destination locations and calculate route
    setLocationButton.addEventListener('click', function() {
        const startLocation = startInput.value.trim();
        const destination = destinationInput.value.trim();

        if (startLocation && destination) {
            // Geocode the start location
            fetch(https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(startLocation + ", Tamil Nadu")})
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        startLat = parseFloat(data[0].lat);
                        startLng = parseFloat(data[0].lon);

                        // Geocode the destination location
                        fetch(https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(destination + ", Tamil Nadu")})
                            .then(response => response.json())
                            .then(data => {
                                if (data.length > 0) {
                                    destinationLat = parseFloat(data[0].lat);
                                    destinationLng = parseFloat(data[0].lon);

                                    // Add markers for start and destination
                                    if (startMarker) map.removeLayer(startMarker);
                                    if (destinationMarker) map.removeLayer(destinationMarker);

                                    startMarker = L.marker([startLat, startLng])
                                        .addTo(map)
                                        .bindPopup("Start Location")
                                        .openPopup();

                                    destinationMarker = L.marker([destinationLat, destinationLng])
                                        .addTo(map)
                                        .bindPopup("Destination Location")
                                        .openPopup();

                                    calculateRoute();
                                    displayBusDetails(startLocation, destination);
                                } else {
                                    alert('Destination not found. Please try again.');
                                }
                            })
                            .catch(error => {
                                console.error('Error geocoding destination:', error);
                                alert('Error finding destination. Please try again.');
                            });
                    } else {
                        alert('Start location not found. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error geocoding start location:', error);
                    alert('Error finding start location. Please try again.');
                });
        } else {
            alert('Please enter valid start and destination locations.');
        }
    });
</script>
</body>
</html>