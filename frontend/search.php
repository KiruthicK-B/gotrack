<?php
session_start();
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Route Search</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-left: 550px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        .form-group input:focus {
            border-color: #ff8c42;
            outline: none;
        }
        .btn {
            width: 100%;
            padding: 10px 15px;
            background-color: #ff8c42;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #e67e22;
        }
        .btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .error {
            color: red;
            margin-top: 10px;
            font-size: 14px;
        }
        .results-container {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width:60%;
            margin-left: 400px;
        }
        .route {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            background-color: #fff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .route:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .route-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .route-header h3 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .route-header .badge {
            background-color: #ff8c42;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        .route-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .route-details p {
            margin: 0;
            font-size: 14px;
            color: #555;
        }
        .route-details p strong {
            color: #333;
        }
/* Custom scrollbar for dropdown options */
/* Custom styles for the select dropdown */
.form-group select {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
    background-color: #fff;
    appearance: none; /* Remove default arrow */
    -webkit-appearance: none; /* Remove default arrow for Safari */
    -moz-appearance: none; /* Remove default arrow for Firefox */
    background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23ff8c42%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 12px;
    cursor: pointer;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

/* Hover and focus states */
.form-group select:hover {
    border-color: #ff8c42;
}

.form-group select:focus {
    border-color: #ff8c42;
    outline: none;
    box-shadow: 0 0 5px rgba(255, 140, 66, 0.5);
}

/* Disabled state */
.form-group select:disabled {
    background-color: #f4f4f4;
    cursor: not-allowed;
    opacity: 0.7;
}

/* Custom scrollbar for dropdown options */
.form-group select option {
    padding: 10px;
    background-color: #fff;
    color: #333;
    font-size: 14px;
}

.form-group select option:hover {
    background-color: #ff8c42;
    color: #fff;
}

/* Scrollbar styling for WebKit browsers */
.form-group select option::-webkit-scrollbar {
    width: 8px;
}

.form-group select option::-webkit-scrollbar-track {
    background: #f4f4f4;
    border-radius: 4px;
}

.form-group select option::-webkit-scrollbar-thumb {
    background: #ff8c42;
    border-radius: 4px;
}

.form-group select option::-webkit-scrollbar-thumb:hover {
    background: #e67e22;
}
    </style>
</head>
<body>
    <div class="sidebar">
        <h3><img src="logo.jpg" alt="GoTrack"></h3>
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="map.php"><i class="fas fa-map"></i> Track Bus</a></li>
            <li><a href="#"><i class="fas fa-bus"></i> Search Bus</a></li>            
            <?php if (!$username): ?>
            <li id="signinNav"><a href="signin.html"><i class="fas fa-user"></i> Sign Up / Sign In</a></li>
        <?php endif; ?>

        <!-- Logout Button (Hidden by default, shows after login) -->
        <?php if ($username): ?>
            <li id="logoutBtn"> <i class="fas fa-sign-out-alt"></i>Logout</li>
        <?php endif; ?>
        </ul>
    </div>
    <div class="container">
        <h1>Bus Route Search</h1>
        <form id="searchForm">
        <div class="form-group">
    <label for="from">From:</label>
    <select id="from" required>
        <option value="">Select starting location</option>
    </select>
</div>
<div class="form-group">
    <label for="to">To:</label>
    <select id="to" required>
        <option value="">Select destination</option>
    </select>
</div>
            <button type="submit" class="btn" id="searchBtn">Search</button>
            <div class="error" id="errorMessage"></div>
        </form>
    </div>
    <div class="results-container" id="results"></div>

    <script>
        const apiKey = '579b464db66ec23bdd000001108c39b466a947805079e1ce4d796f52';
        const baseUrl = 'https://api.data.gov.in/resource/1f10d3eb-a425-4246-8800-3f72bf7ad2b0';

        // Fetch unique "From" and "To" locations from the API
        async function fetchLocations() {
            const queryParams = new URLSearchParams({
                'api-key': apiKey,
                'format': 'json',
                'limit': '1000', // Increase limit to get more data
                'page': '1'
            });

            const url = `${baseUrl}?${queryParams.toString()}`;
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error('Failed to load bus routes');
            }

            const data = await response.json();

            if (data.status === 'error') {
                throw new Error(data.message);
            }

            const fromLocations = new Set();
            const toLocations = new Set();

            data.records.forEach(route => {
                fromLocations.add(route.from);
                toLocations.add(route.to);
            });

            return { fromLocations: Array.from(fromLocations), toLocations: Array.from(toLocations) };
        }

        // Populate dropdowns with unique locations
        async function populateDropdowns() {
            try {
                const { fromLocations, toLocations } = await fetchLocations();

                const fromDropdown = document.getElementById('from');
                const toDropdown = document.getElementById('to');

                fromLocations.forEach(location => {
                    const option = document.createElement('option');
                    option.value = location;
                    option.textContent = location;
                    fromDropdown.appendChild(option);
                });

                toLocations.forEach(location => {
                    const option = document.createElement('option');
                    option.value = location;
                    option.textContent = location;
                    toDropdown.appendChild(option);
                });
            } catch (error) {
                showError(`Failed to load locations: ${error.message}`);
            }
        }

        // Call the function to populate dropdowns when the page loads
        document.addEventListener('DOMContentLoaded', populateDropdowns);

        // Existing search functionality
        document.getElementById('searchForm').addEventListener('submit', async (e) => {
            e.preventDefault(); // Prevent form submission

            const from = document.getElementById('from').value.trim();
            const to = document.getElementById('to').value.trim();

            if (!from || !to) {
                showError('Please select both "From" and "To" locations.');
                return;
            }

            clearError();
            showLoading(true);

            try {
                const routes = await getRoutes(from, to);
                displayResults(routes);
            } catch (error) {
                showError(`Failed to load routes: ${error.message}`);
            } finally {
                showLoading(false);
            }
        });

        // Existing functions (getRoutes, calculateTicketPrice, calculateDuration, calculateArrivalTime, displayResults, bookNow, showError, clearError, showLoading)
        async function getRoutes(from, to) {
    const queryParams = new URLSearchParams({
        'api-key': apiKey,
        'format': 'json',
        'limit': '100',
        'page': '1',
        ...(from && { 'filters[from]': from }),
        ...(to && { 'filters[to]': to })
    });

    const url = `${baseUrl}?${queryParams.toString()}`;
    const response = await fetch(url);

    if (!response.ok) {
        throw new Error('Failed to load bus routes');
    }

    const data = await response.json();

    if (data.status === 'error') {
        throw new Error(data.message);
    }

    // Get today's date in YYYY-MM-DD format for checking seat availability
    const today = new Date().toISOString().split('T')[0];
    
    // Process routes and get seat availability for each departure time
    const processedRoutes = [];
    
    if (data.records) {
        for (const route of data.records) {
            const departureTimings = route.departure_timings.split(',');
            
            for (const departureTime of departureTimings) {
                // Get booked seats for this route and departure time
                const bookedSeats = await getBookedSeats(route.route_no_, today, departureTime.trim());
                const availableSeats = 39 - bookedSeats.length; // Assuming 39 total seats
                
                processedRoutes.push({
                    id: route.sl_no,
                    from: route.from,
                    to: route.to,
                    departureTime: departureTime.trim(),
                    arrivalTime: calculateArrivalTime(departureTime.trim(), route.route_length),
                    ticketPrice: calculateTicketPrice(route.route_length, route.type),
                    distance: `${Math.round(route.route_length)} km`,
                    duration: calculateDuration(route.route_length),
                    depot: route.depot,
                    routeNo: route.route_no_,
                    type: route.type,
                    numberOfServices: route.no_of_service,
                    availableSeats: availableSeats,
                    bookedSeats: bookedSeats
                });
            }
        }
    }
    
    return processedRoutes;
}
async function getBookedSeats(routeNo, travelDate, departureTime) {
    try {
        const response = await fetch(`get_booked_seats1.php?routeNo=${routeNo}&travelDate=${travelDate}&departure=${encodeURIComponent(departureTime)}`);
        
        if (!response.ok) {
            throw new Error('Failed to fetch booked seats');
        }
        
        const data = await response.json();
        return data.bookedSeats || [];
    } catch (error) {
        console.error('Error fetching booked seats:', error);
        return [];
    }
}


        function calculateTicketPrice(routeLength, type) {
            const pricePerKm = type === 'ULTRA' ? 0.20 : 0.15;
            return routeLength * pricePerKm;
        }

        function calculateDuration(routeLength) {
            const hours = routeLength / 60;
            const hoursInt = Math.floor(hours);
            const minutes = Math.round((hours - hoursInt) * 60);
            return `${hoursInt}h ${minutes}m`;
        }

        function calculateArrivalTime(departureTimings, routeLength) {
    // Simulate arrival time by adding duration to departure time
    const durationInHours = routeLength / 60;
    return departureTimings.split(',').map(time => {
        // Replace '.' with ':' to handle HH.MM format
        const formattedTime = time.trim().replace('.', ':');
        const [hours, minutes] = formattedTime.split(':').map(Number); // Convert to numbers
        const departureDate = new Date();
        departureDate.setHours(hours, minutes, 0, 0); // Set hours and minutes

        // Calculate arrival time by adding duration in milliseconds
        const arrivalDate = new Date(departureDate.getTime() + durationInHours * 60 * 60 * 1000);

        // Format the arrival time as HH:MM
        return arrivalDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });
    });
}

function displayResults(routes) {
    const resultsDiv = document.getElementById('results');
    resultsDiv.innerHTML = '';

    if (routes.length === 0) {
        showError('No routes found for the given locations.');
        return;
    }

    routes.forEach(route => {
        const routeDiv = document.createElement('div');
        routeDiv.className = 'route';
        
        // Determine seat availability status
        let seatStatus = '';
        let seatClass = '';
        if (route.availableSeats <= 0) {
            seatStatus = 'No seats available';
            seatClass = 'no-seats';
        } else if (route.availableSeats < 10) {
            seatStatus = `Only ${route.availableSeats} seats left`;
            seatClass = 'few-seats';
        } else {
            seatStatus = `${route.availableSeats} seats available`;
            seatClass = 'available-seats';
        }
        
        routeDiv.innerHTML = `
            <div class="route-header">
                <h3>Route No: ${route.routeNo}</h3>
                <span class="badge">${route.type}</span>
            </div>
            <div class="route-details">
                <p><strong>From:</strong> ${route.from}</p>
                <p><strong>To:</strong> ${route.to}</p>
                <p><strong>Departure:</strong> ${route.departureTime}</p>
                <p><strong>Arrival:</strong> ${route.arrivalTime}</p>
                <p><strong>Duration:</strong> ${route.duration}</p>
                <p><strong>Distance:</strong> ${route.distance}</p>
                <p><strong>Ticket Price:</strong> ₹${route.ticketPrice.toFixed(2)}</p>
                <p class="${seatClass}"><strong>Seat Availability:</strong> ${seatStatus}</p>
                <p><strong>Depot:</strong> ${route.depot}</p>
                <p><strong>Services:</strong> ${route.numberOfServices}</p>
            </div>
            <button class="btn book-now-btn" 
                onclick="bookNow('${route.from}', '${route.to}', '${route.departureTime}', 
                        '${route.arrivalTime}', '${route.routeNo}', '${route.ticketPrice.toFixed(2)}')"
                ${route.availableSeats <= 0 ? 'disabled' : ''}>
                ${route.availableSeats <= 0 ? 'No Seats Available' : 'Book Now'}
            </button>
        `;
        resultsDiv.appendChild(routeDiv);
    });
}


function bookNow(from, to, departureTime, arrivalTime, routeNo, ticketPrice) {
    // Redirect to the booking page with route details as query parameters
    window.location.href = `test.html?from=${encodeURIComponent(from)}&to=${encodeURIComponent(to)}&departure=${encodeURIComponent(departureTime)}&arrival=${encodeURIComponent(arrivalTime)}&routeNo=${encodeURIComponent(routeNo)}&price=${encodeURIComponent(ticketPrice)}`;
}

        function showError(message) {
            document.getElementById('errorMessage').textContent = message;
        }

        function clearError() {
            document.getElementById('errorMessage').textContent = '';
        }

        function showLoading(isLoading) {
            document.getElementById('searchBtn').disabled = isLoading;
            document.getElementById('searchBtn').textContent = isLoading ? 'Searching...' : 'Search';
        }
        document.addEventListener("DOMContentLoaded", function () {
            const logoutBtn = document.getElementById("logoutBtn");
            if (logoutBtn) {
                logoutBtn.addEventListener("click", function () {
                    window.location.href = "logout.php";
                });
            }
        });

    </script>
</body>
</html>