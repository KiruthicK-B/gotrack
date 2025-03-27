<?php
session_start();
$username = isset($_SESSION["username"]) ? $_SESSION["username"] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GoTrack</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<style>
    /* Custom Styles */
    .home-content {
        background-color: #f8f9fa;
        padding: 60px 0;
    }
    .features-section, .how-it-works-section, .testimonials-section, .cta-section, .newsletter-section {
        padding: 60px 0;
    }
    .feature-card, .testimonial-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    .testimonial-card img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-bottom: 15px;
    }
    .newsletter-section {
        background-color: #007bff;
        color: white;
    }
    .newsletter-section input {
        padding: 10px;
        border-radius: 5px;
        border: none;
        width: 300px;
        margin-right: 10px;
    }
    .newsletter-section button {
        padding: 10px 20px;
        border-radius: 5px;
        border: none;
        background-color: #ffc107;
        color: black;
    }
</style>
</head>

<body>
<div class="sidebar">
        <h3><img src="logo.jpg" alt="GoTrack"></h3>
        <ul>
            <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="map.php"><i class="fas fa-map"></i> Track Bus</a></li>
            <li><a href="search.php"><i class="fas fa-bus"></i> Search Bus</a></li>            
            <?php if (!$username): ?>
                <li id="signinNav"><a href="signin.html"><i class="fas fa-user"></i> Sign Up / Sign In</a></li>
            <?php endif; ?>

            <!-- Logout Button (Hidden by default, shows after login) -->
            <?php if ($username): ?>
                <li id="logoutBtn"> <i class="fas fa-sign-out-alt"></i>Logout</li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="main-content">
        <div class="navbar">
            <span class="username" id="displayUsername">
                Welcome, <?= $username ? htmlspecialchars($username) : "Guest" ?>
            </span>
        </div>

        <!-- Hero Section -->
        <div class="home-content py-5">
            <div class="container text-center">
                <h1>Welcome to GoTrack</h1>
                <p class="lead">Your ultimate solution for real-time bus tracking and booking.</p>
                <button onclick="window.location.href='booking.html'" class="btn btn-lg btn-primary mt-4">Book Tickets Now →</button>
            </div>
        </div>

        <!-- Features Section -->
        <div class="features-section">
            <div class="container">
                <h2 class="text-center mb-5">Why Choose GoTrack?</h2>
                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-card text-center">
                            <i class="fas fa-bus fa-3x mb-3"></i>
                            <h4>Real-Time Tracking</h4>
                            <p>Track your bus in real-time and never miss your ride.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card text-center">
                            <i class="fas fa-ticket-alt fa-3x mb-3"></i>
                            <h4>Instant Booking</h4>
                            <p>Book your tickets instantly with just a few clicks.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="feature-card text-center">
                            <i class="fas fa-route fa-3x mb-3"></i>
                            <h4>Dynamic Routes</h4>
                            <p>Get the best routes and suggestions for your journey.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <!-- How It Works Section -->
<div class="how-it-works-section">
    <div class="container">
        <h2 class="text-center mb-5">How It Works</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <h4>Search</h4>
                    <p>Search for buses based on your route and schedule.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-ticket-alt fa-3x mb-3"></i>
                    <h4>Book</h4>
                    <p>Book your tickets instantly with secure payment options.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card text-center">
                    <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                    <h4>Track</h4>
                    <p>Track your bus in real-time and stay updated.</p>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Testimonials Section -->
        

        <!-- Call-to-Action Section -->
        <div class="cta-section text-center">
            <div class="container">
                <h2>Ready to Travel Smarter?</h2>
                <p>Join thousands of happy users and experience seamless bus travel.</p>
                <button class="btn btn-lg btn-primary">Get Started Now →</button>
            </div>
        </div>

        <!-- Newsletter Section -->
        <div class="newsletter-section text-center">
            <div class="container">
                <h2>Subscribe to Our Newsletter</h2>
                <p>Get the latest updates and offers directly in your inbox.</p>
                <form>
                    <input type="email" placeholder="Enter your email" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
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