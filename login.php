<?php
// login.php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.html");
    exit;
}

$errors = [];

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

// ---- VALIDIMI BACKEND ----
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email";
}

if (empty($password)) {
    $errors[] = "Password cannot be empty";
}

if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
    exit;
}

// ---- DATABASE (shembull) ----
// merr userin nga DB sipas email
/*
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();
*/

$user = null; // vetëm për shembull

/*
if (!$user || !password_verify($password, $user['password'])) {
    echo "<p style='color:red;'>Email or password incorrect</p>";
    exit;
}
*/

// ---- SESSION ----
session_start();
$_SESSION['user_email'] = $email;

// Remember me (opsionale)
if ($remember) {
    setcookie("remember_email", $email, time() + (86400 * 30), "/");
}

echo "<p style='color:green;'>Login successful</p>";
?>

