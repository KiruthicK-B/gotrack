<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GoTrack</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="sidebar">
        <h3><img src="logo.jpg" alt="GoTrack"></h3>
        <ul>
            <li><i class="fas fa-home"></i> Home</li>
            <li><i class="fas fa-bus"></i> Booking</li>
            <li><i class="fas fa-map"></i> Track Bus</li>
            <li onclick="scrollToFooter()"><i class="fas fa-phone"></i> Contact Us</li>
            <li><i class="fas fa-user"></i> Sign Up / Sign In</li>
        </ul>
    </div>

    <div class="main-content">
        <div class="navbar">
            <span class="username">Welcome, UserName</span>
            <button>Logout</button>
        </div>

        <!-- <div id="content">
            <h1>Welcome to GoTrack</h1>
            <p>Your real-time bus tracking platform.</p>
        </div> -->
<br><br><br><br>

        <div class="home-content py-5">
    <div class="container text-center">
        <div class="row align-items-center">
            <!-- Text Section -->
            <div class="col-md-6">
                <div class="text-content">
                    <h2 class="display-3 fw-bold">Book Your Tickets Instantly!</h2>
                    <p class="lead">
                        Travel smarter with <strong>GoTrack</strong>! Enjoy **real-time seat availability, instant confirmation, and live route tracking** for a hassle-free journey. 
                        Unlock exclusive deals, early-bird discounts, and last-minute offers tailored just for you.
                        Avoid long queues and book your ticket in seconds with a **seamless online experience**.
                        <span class="text-success fw-bold">Use code <u>GOTRACK20</u> to get 20% OFF on your first ride!</span>
                    </p>
                    <button class="btn btn-lg btn-primary mt-4" onclick="scrollToBooking()">Book Tickets Now →</button>
                </div>
            </div>
            <!-- Image Section -->
            <div class="col-md-6">
                <img src="book.jpeg" alt="Bus Ticket Booking" class="img-fluid w-100 rounded shadow">
            </div>
        </div>
    </div>
</div>

        <div id="booking-section" class="hidden">
            <h2 class="text-center mt-5">Select Your Journey Details</h2>
            <p class="text-center">Choose your destination, date, and time to proceed with booking.</p>
        </div>
    </div>

    <footer id="footer">
        <div class="footer-container">
            <div class="footer-section">
                <h3>About GoTrack</h3>
                <p>GoTrack is your travel companion for real-time seat status and dynamic route suggestions.</p>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>Email: support@gotrack.com</p>
                <p>Phone: +91 9876543210</p>
            </div>
        </div>
    </footer>

    <script>
        function scrollToBooking() {
            document.getElementById("booking-section").classList.remove("hidden");
            document.getElementById("booking-section").scrollIntoView({
                behavior: "smooth"
            });
        }

        function scrollToFooter() {
            document.getElementById("footer").scrollIntoView({
                behavior: "smooth"
            });
        }
    </script>
</body>
</html>
