<?php
// register.php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

$errors = [];

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// ---- VALIDIMI BACKEND ----
if (empty($name)) {
    $errors[] = "Name cannot be empty";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
}

if (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters";
}

// Nëse ka gabime
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color:red;'>$error</p>";
    }
    exit;
}

// ---- SIGURIA ----
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// ---- DATABASE (shembull) ----
// këtu lidhja me DB (PDO ose MySQLi)
// kontrollo nëse email ekziston
// ruaj userin

/*
$stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->execute([$name, $email, $hashedPassword]);
*/

echo "<p style='color:green;'>Registration successful</p>";
?>

