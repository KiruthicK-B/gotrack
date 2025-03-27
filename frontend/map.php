<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Locator with Traffic API</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <!-- Leaflet Routing Machine CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <!-- TomTom Maps SDK -->
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.23.0/maps/maps-web.min.js"></script>
    <link rel="stylesheet" href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.23.0/maps/maps.css">
    <style>
        /* Your existing CSS styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #container {
            display: flex;
            flex: 1;
            height: 100%;
        }

        #left-panel {
            width: 25%;
            background-color: #f8f9fa;
            padding: 20px;
            overflow-y: auto;
            border-right: 1px solid #ddd;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        #map {
            width: 100%;
            height: 100%;
        }

        #location-container {
            margin-bottom: 20px;
        }

        #start-input,
        #destination-input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            margin-bottom: 10px;
        }

        #set-location {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        #set-location:hover {
            background-color: #218838;
        }

        .route-card {
            background-color: white;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .route-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .route-card.selected {
            border: 2px solid #007bff;
        }

        .best-route-badge {
            background-color: #FF9800;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }

        .select-route-button {
            width: 100%;
            padding: 8px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 8px;
        }

        .select-route-button:hover {
            background-color: #218838;
        }

        .route-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .route-color-indicator {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .title-with-badge {
            display: flex;
            align-items: center;
        }

        #bus-details-header {
            margin-top: 20px;
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
        }

        .category-header {
            background-color: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-weight: bold;
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

        .book-button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .book-button:hover {
            background-color: #0056b3;
        }

        .suggestions {
            max-height: 150px;
            overflow-y: auto;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
            background-color: white;
            position: absolute;
            z-index: 1000;
            width: calc(100% - 20px);
        }

        .suggestion-item {
            padding: 8px 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .suggestion-item:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div id="container">
        <!-- Left Panel for Bus Details -->
        <div id="left-panel">
            <div id="location-container">
                <h3>Enter Start Location</h3>
                <input type="text" id="start-input" placeholder="Enter start location...">
                <div id="suggestions-start" class="suggestions"></div>
                <h3>Enter Destination</h3>
                <input type="text" id="destination-input" placeholder="Enter destination...">
                <div id="suggestions-destination" class="suggestions"></div>
                <button id="set-location">Set Locations</button>
            </div>
            <div id="route-options">
                <h3>Available Routes</h3>
                <div id="routes-list">
                    <!-- Route options will be dynamically added here -->
                </div>
            </div>
            <div id="bus-details-header"></div>
            <div id="bus-list">
                <!-- Bus details will be dynamically added here -->
            </div>
        </div>

        <!-- Map Container -->
        <div id="map"></div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <!-- Leaflet Routing Machine JS -->
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <!-- Your Custom Script -->
    <script>
        const TOMTOM_API_KEY = 'svsA4DLUJjsdN4e1F0poH4rMPvao7LOu'; // Replace with your TomTom API key
        const FUEL_EFFICIENCY = 5.5; // Fuel efficiency in km/l (adjust based on vehicle)
        const EMISSION_FACTOR = 2.31; // CO₂ emission factor in kg/l (for petrol)

        // Initialize the map
        var map = L.map('map').setView([13.0827, 80.2707], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Variables for start and destination locations
        let startLat = null, startLng = null;
        let destinationLat = null, destinationLng = null;
        let routingControls = [];
        let startMarker = null;
        let destinationMarker = null;
        let busMarkers = [];
        let selectedRouteIndex = null;
        let bestRouteIndex = null;

        // Route colors for multiple routes
        const routeColors = ['#FF5733', '#33A8FF', '#33FF57', '#FF33A8', '#A833FF'];

        // DOM elements
        const startInput = document.getElementById('start-input');
        const destinationInput = document.getElementById('destination-input');
        const setLocationButton = document.getElementById('set-location');
        const busList = document.getElementById('bus-list');
        const routesList = document.getElementById('routes-list');
        const suggestionsStart = document.getElementById('suggestions-start');
        const suggestionsDestination = document.getElementById('suggestions-destination');
        const busDetailsHeader = document.getElementById('bus-details-header');

        // Function to fetch location suggestions
        async function fetchSuggestions(query, suggestionsContainer) {
            if (!query || query.length < 3) {
                suggestionsContainer.innerHTML = '';
                return;
            }

            const url = `https://api.tomtom.com/search/2/search/${encodeURIComponent(query)}.json?key=${TOMTOM_API_KEY}&limit=5`;
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const data = await response.json();
                suggestionsContainer.innerHTML = ''; // Clear previous suggestions

                if (data.results && data.results.length > 0) {
                    data.results.forEach(result => {
                        const suggestion = document.createElement('div');
                        suggestion.className = 'suggestion-item';
                        suggestion.textContent = result.address.freeformAddress;
                        suggestion.addEventListener('click', () => {
                            if (suggestionsContainer === suggestionsStart) {
                                startInput.value = result.address.freeformAddress;
                                startLat = result.position.lat;
                                startLng = result.position.lon;
                            } else {
                                destinationInput.value = result.address.freeformAddress;
                                destinationLat = result.position.lat;
                                destinationLng = result.position.lon;
                            }
                            suggestionsContainer.innerHTML = ''; // Clear suggestions after selection
                        });
                        suggestionsContainer.appendChild(suggestion);
                    });
                } else {
                    suggestionsContainer.innerHTML = '<div class="suggestion-item">No results found</div>';
                }
            } catch (error) {
                console.error('Error fetching suggestions:', error);
                suggestionsContainer.innerHTML = '<div class="suggestion-item">Error fetching suggestions</div>';
            }
        }

        // Add event listeners for input fields
        startInput.addEventListener('input', () => {
            fetchSuggestions(startInput.value, suggestionsStart);
        });

        destinationInput.addEventListener('input', () => {
            fetchSuggestions(destinationInput.value, suggestionsDestination);
        });

        // Function to calculate route efficiency score
        function calculateRouteScore(distance, traffic, fuelConsumption, carbonFootprint) {
            const distanceWeight = 0.4; // Weight for distance
            const trafficWeight = 0.3; // Weight for traffic
            const fuelWeight = 0.2; // Weight for fuel consumption
            const carbonWeight = 0.1; // Weight for carbon footprint

            // Normalize values (optional, depending on your data range)
            const normalizedDistance = distance / 100; // Example normalization
            const normalizedTraffic = traffic / 60; // Example normalization
            const normalizedFuel = fuelConsumption / 10; // Example normalization
            const normalizedCarbon = carbonFootprint / 10; // Example normalization

            // Calculate score
            return (
                normalizedDistance * distanceWeight +
                normalizedTraffic * trafficWeight +
                normalizedFuel * fuelWeight +
                normalizedCarbon * carbonWeight
            );
        }

        // Function to create multiple routes
        async function createMultipleRoutes() {
            if (!startLat || !startLng || !destinationLat || !destinationLng) {
                alert('Start location or destination not available.');
                return;
            }

            // Clear existing routes
            clearRoutes();

            // Create waypoints
            const startPoint = L.latLng(startLat, startLng);
            const endPoint = L.latLng(destinationLat, destinationLng);

            // Fetch multiple routes using TomTom Routing API
            const url = `https://api.tomtom.com/routing/1/calculateRoute/${startLat},${startLng}:${destinationLat},${destinationLng}/json?key=${TOMTOM_API_KEY}&maxAlternatives=5`;
            
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const data = await response.json();
                const routes = data.routes;

                // Array to store route metrics for determining the best route
                const routeMetrics = [];

                routes.forEach((route, index) => {
                    const distance = route.summary.lengthInMeters / 1000; // Convert to kilometers
                    const time = route.summary.travelTimeInSeconds / 60; // Convert to minutes
                    const traffic = route.summary.trafficDelayInSeconds / 60; // Convert to minutes

                    // Calculate fuel consumption and carbon footprint
                    const fuelConsumption = distance / FUEL_EFFICIENCY; // Fuel in liters
                    const carbonFootprint = fuelConsumption * EMISSION_FACTOR; // CO₂ in kg

                    // Calculate route score
                    const score = calculateRouteScore(distance, traffic, fuelConsumption, carbonFootprint);

                    // Store route metrics
                    routeMetrics.push({ distance, time, traffic, fuelConsumption, carbonFootprint, score });

                    // Create route card
                    const routeCard = document.createElement('div');
                    routeCard.className = 'route-card';
                    routeCard.id = `route-${index}`;

                    // Add route details to the card (vertical layout)
                    routeCard.innerHTML = `
                        <div class="title-with-badge">
                            <div class="route-color-indicator" style="background-color: ${routeColors[index]};"></div>
                            <h4>Route ${index + 1}</h4>
                            ${index === bestRouteIndex ? '<span class="best-route-badge">Best Route</span>' : ''}
                        </div>
                        <div class="route-info">
                            <p><strong>Distance:</strong> ${distance.toFixed(2)} km</p>
                            <p><strong>Time:</strong> ${time.toFixed(2)} mins</p>
                            <p><strong>Traffic Delay:</strong> ${traffic.toFixed(2)} mins</p>
                            <p><strong>Fuel Consumption:</strong> ${fuelConsumption.toFixed(2)} liters</p>
                            <p><strong>Carbon Footprint:</strong> ${carbonFootprint.toFixed(2)} kg CO₂</p>
                        </div>
                        <button class="select-route-button">Select Route</button>
                    `;

                    routesList.appendChild(routeCard);

                    // Add route to the map using the coordinates directly from the TomTom response
                    const routeCoordinates = route.legs.flatMap(leg => 
                        leg.points.map(point => [point.latitude, point.longitude])
                    );
                    
                    const routePath = L.polyline(routeCoordinates, {
                        color: routeColors[index],
                        opacity: 0.7,
                        weight: 5
                    }).addTo(map);
                    
                    // Store the path reference in routingControls for later manipulation
                    routingControls.push({
                        _line: routePath,
                        _container: { 
                            style: {} 
                        },
                        routing: {
                            distance,
                            time,
                            traffic,
                            fuelConsumption,
                            carbonFootprint
                        }
                    });
                });

                // Determine the best route
                bestRouteIndex = determineBestRoute(routeMetrics);

                // Highlight the best route on the map
                if (bestRouteIndex !== null && routingControls[bestRouteIndex]._line) {
                    routingControls[bestRouteIndex]._line.setStyle({ 
                        color: routeColors[bestRouteIndex], 
                        opacity: 1, 
                        weight: 5 
                    });
                }

                // Automatically select the best route
                selectRoute(bestRouteIndex);

                // Add event listeners to route selection buttons
                addRouteSelectionListeners();
                
                // Adjust the map view to show all routes
                const bounds = L.latLngBounds([startPoint, endPoint]);
                map.fitBounds(bounds, { padding: [50, 50] });
                
            } catch (error) {
                console.error('Error fetching routes:', error);
                alert('Error fetching routes. Please try again.');
            }
        }

        // Function to determine the best route
        function determineBestRoute(routeMetrics) {
            let bestScore = Infinity;
            let bestIndex = 0;

            routeMetrics.forEach((metric, index) => {
                if (metric.score < bestScore) {
                    bestScore = metric.score;
                    bestIndex = index;
                }
            });

            return bestIndex;
        }

        // Function to select a route
        function selectRoute(routeIndex) {
            if (selectedRouteIndex === routeIndex) return;

            // Update selected route
            selectedRouteIndex = routeIndex;

            // Update UI
            document.querySelectorAll('.route-card').forEach((card, index) => {
                if (index === routeIndex) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });

            // Highlight the selected route
            routingControls.forEach((control, index) => {
                if (index === routeIndex) {
                    control._line.setStyle({ 
                        color: routeColors[index], 
                        opacity: 1, 
                        weight: 5 
                    });
                } else {
                    control._line.setStyle({ 
                        color: routeColors[index], 
                        opacity: 0.4, 
                        weight: 3 
                    });
                }
            });
        }

        // Function to clear all routes
        function clearRoutes() {
            routingControls.forEach(control => {
                if (control._line) {
                    map.removeLayer(control._line);
                }
            });
            routingControls = [];

            busMarkers.forEach(marker => {
                map.removeLayer(marker);
            });
            busMarkers = [];

            selectedRouteIndex = null;
            bestRouteIndex = null;

            busList.innerHTML = '';
            busDetailsHeader.innerHTML = '';
            routesList.innerHTML = '';
        }

        // Set start and destination locations and calculate routes
        setLocationButton.addEventListener('click', function() {
            const startLocation = startInput.value.trim();
            const destination = destinationInput.value.trim();

            if (startLocation && destination) {
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

                // Create multiple routes with best route recommendation
                createMultipleRoutes();
            } else {
                alert('Please enter valid start and destination locations.');
            }
        });

        // Function to handle route selection
        function handleRouteSelection(routeIndex) {
            // Highlight the selected route on the map
            selectRoute(routeIndex);

            // Update the UI to show the selected route
            document.querySelectorAll('.route-card').forEach((card, index) => {
                if (index === routeIndex) {
                    card.classList.add('selected');
                } else {
                    card.classList.remove('selected');
                }
            });
        }

        // Add event listeners to route selection buttons
        function addRouteSelectionListeners() {
            document.querySelectorAll('.select-route-button').forEach((button, index) => {
                button.addEventListener('click', () => {
                    handleRouteSelection(index);
                });
            });
        }
    </script>
</body>
</html>