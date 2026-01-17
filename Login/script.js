const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

const signupForm = document.getElementById("signupForm");
const signupName = document.getElementById("signupName");
const signupEmail = document.getElementById("signupEmail");
const signupPassword = document.getElementById("signupPassword");

const nameError = document.getElementById("nameError");
const emailError = document.getElementById("emailError");
const passwordError = document.getElementById("passwordError");
const generalError = document.getElementById("generalError");
const successMsg = document.getElementById("successMsg");




registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});
// ---------- SIGN UP VALIDATION ----------
document.getElementById("signupForm").addEventListener("submit", function(e) {
    e.preventDefault(); // NDALON RELOAD

    let name = signupName.value.trim();
    let email = signupEmail.value.trim();
    let password = signupPassword.value.trim();

    nameError.textContent = "";
    emailError.textContent = "";
    passwordError.textContent = "";
    generalError.textContent = "";
    successMsg.textContent = "";

    let valid = true;
    let nameRegex = /^[A-Za-z ]{3,}$/;
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

    if (!nameRegex.test(name)) {
        nameError.textContent = "Name must contain only letters (min 3)";
        valid = false;
    }

    if (!emailRegex.test(email)) {
        emailError.textContent = "Invalid email";
        valid = false;
    }

    if (password.length < 6) {
        passwordError.textContent = "Password must be at least 6 characters";
        valid = false;
    }

    if (!valid) return;

    // AJAX REQUEST
    fetch("../register.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            name: name,
            email: email,
            password: password
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === "error") {
                generalError.textContent = data.message;
            } else {
                successMsg.textContent = data.message;
                signupForm.reset();
            }
        })
        .catch(err => {
            generalError.textContent = "Server error!";
        });
});





// ---------- LOGIN VALIDATION ----------
document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let formData = new FormData(this);

    fetch("../login.php", {  // path relativ sipas vendndodhjes së login.php
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            console.log(data); // kontrollo në console
            if (data.status === "error") {
                alert(data.message);
            } else if (data.status === "success") {
                // redirect
                window.location.href = "../profile.php";  // relative path
            }
        })
        .catch(err => console.log(err));
});

