const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});
// ---------- SIGN UP VALIDATION ----------
document.getElementById("signupForm").addEventListener("submit", function(e){
    let valid = true;

    let name = signupName.value.trim();
    let email = signupEmail.value.trim();
    let password = signupPassword.value.trim();

    nameError.textContent = "";
    emailError.textContent = "";
    passwordError.textContent = "";

    if(name === ""){
        nameError.textContent = "Name cannot be empty";
        valid = false;
    }

    let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if(!email.match(emailPattern)){
        emailError.textContent = "Enter a valid email";
        valid = false;
    }

    if(password.length < 6){
        passwordError.textContent = "Password must be at least 6 characters";
        valid = false;
    }

    if(!valid) e.preventDefault();
});


// ---------- LOGIN VALIDATION ----------
document.getElementById("loginForm").addEventListener("submit", function(e){
    let valid = true;

    loginEmailError.textContent = "";
    loginPasswordError.textContent = "";

    let email = loginEmail.value.trim();
    let password = loginPassword.value.trim();

    let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if(!email.match(emailPattern)){
        loginEmailError.textContent = "Enter a valid email";
        valid = false;
    }

    if(password === ""){
        loginPasswordError.textContent = "Password cannot be empty";
        valid = false;
    }

    if(!valid) e.preventDefault();
});
