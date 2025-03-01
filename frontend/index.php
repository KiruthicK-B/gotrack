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
        <div id="content">
            <h1>Welcome to GoTrack</h1>
            <p>Your real-time bus tracking platform.</p>
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
    
    <script src="script.js"></script>
    </body>
    
</html>