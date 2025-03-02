const navItems = document.querySelectorAll(".sidebar ul li");
const content = document.getElementById("content");
const homeContent = document.querySelector(".home-content"); // Store home content
const username = document.querySelector(".username");

navItems.forEach(item => {
    console.log("Found item:", item.textContent); // Debugging line

    item.onclick = function () {
        console.log("Clicked item:", item.textContent); // Debugging line

        if (item.textContent.includes("Sign Up / Sign In")) {  
            loadAuthPage();
        } else if (item.textContent.includes("Home")) {
            loadHomePage();
        } else {
            content.innerHTML = `<h2>${item.textContent}</h2><p>Content for ${item.textContent}</p>`;
            homeContent.style.display = "none";
        }
    };
});


function loadHomePage() {
    content.innerHTML = "";
    homeContent.style.display = "block"; // Restore home content
}

function loadAuthPage() {
    homeContent.style.display = "none"; 
    console.log("Loading Auth Page..."); // Hide home content
    content.innerHTML = `
        <div class="auth-container animate__animated animate__fadeIn">
            <h2>Sign In</h2>
            <form onsubmit="return handleSignIn(event)">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Sign In</button>
            </form>
            <div class="social-login">
                <button class="btn btn-danger w-100 mt-2"><i class="fab fa-google"></i> Continue with Google</button>
                <button class="btn btn-primary w-100 mt-2"><i class="fab fa-facebook"></i> Continue with Facebook</button>
                <button class="btn btn-dark w-100 mt-2"><i class="fab fa-microsoft"></i> Continue with Microsoft</button>
            </div>
            <p class="mt-3 text-center">
                Don't have an account? 
                <span class="signup-link" onclick="askUserType()">Sign Up</span>
            </p>
        </div>
    `;
}

function askUserType() {
    content.innerHTML = `
        <div class="auth-container animate__animated animate__zoomIn">
            <h2>Select User Type</h2>
            <button class="btn btn-success w-100 mb-3" onclick="showSignupForm('Passenger')">Passenger</button>
            <button class="btn btn-warning w-100" onclick="showSignupForm('Driver')">Driver</button>
        </div>
    `;
}

function showSignupForm(userType) {
    let extraFields = "";
    if (userType === "Driver") {
        extraFields = `
            <div class="form-group">
                <label>License Number:</label>
                <input type="text" class="form-control" placeholder="Enter your License Number" required>
            </div>
            <div class="form-group">
                <label>Bus Number:</label>
                <input type="text" class="form-control" placeholder="Enter your Bus Number" required>
            </div>
            <div class="form-group">
                <label>Type of Bus:</label>
                <select class="form-control" required>
                    <option value="">Select Bus Type</option>
                    <option value="AC">AC</option>
                    <option value="Non-AC">Non-AC</option>
                    <option value="Sleeper">Sleeper</option>
                    <option value="Seater">Seater</option>
                </select>
            </div>
            <div class="form-group">
                <label>Route Number:</label>
                <input type="text" class="form-control" placeholder="Enter Route Number" required>
            </div>
        `;
    }
    content.innerHTML = `
        <div class="auth-container animate__animated animate__fadeIn">
            <h2>${userType} Sign Up</h2>
            <form onsubmit="return handleSignUp(event, '${userType}')">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" class="form-control" placeholder="Enter your name" required>
                </div>
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" class="form-control" placeholder="Enter your email" required>
                </div>
                ${extraFields}
                <button type="submit" class="btn btn-primary w-100">Sign Up</button>
            </form>
        </div>
    `;
}

function handleSignIn(e) {
    e.preventDefault();
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    fetch("auth.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `action=signin&email=${email}&password=${password}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            username.innerHTML = `Welcome, ${email.split("@")[0]}`;
            showSuccessMessage("Login Successful 🚀");
        } else {
            showSuccessMessage(data.message);
        }
    })
    .catch(err => console.error(err));
}

function handleSignUp(e, userType) {
    e.preventDefault();
    const formData = new FormData(e.target);
    formData.append("action", userType === "Passenger" ? "register_passenger" : "register_driver");

    fetch("api.php", { method: "POST", body: formData })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            username.innerHTML = `Welcome, ${formData.get("name")}`;
            showSuccessMessage(`${userType} Signup Successful 🎯`);
        } else {
            showSuccessMessage("Signup Failed ❌");
        }
    })
    .catch(err => console.error(err));
}

function showSuccessMessage(msg) {
    content.innerHTML = `<div class="success-message animate__animated animate__fadeIn">${msg}</div>`;
    setTimeout(loadHomePage, 3000);
}

window.onload = function () {
    loadHomePage();
};



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