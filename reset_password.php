<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/config.php";

$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Invalid token");
}

$stmt = $conn->prepare("
    SELECT user_id 
    FROM password_resets 
    WHERE reset_token = ? 
      AND expires_at > NOW()
");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$reset = $result->fetch_assoc();
$stmt->close();

if (!$reset) {
    die("Invalid or expired token");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (strlen($_POST['password']) < 6) {
        die("Password too short");
    }

    $newPass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $newPass, $reset['user_id']);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM password_resets WHERE user_id = ?");
    $stmt->bind_param("i", $reset['user_id']);
    $stmt->execute();
    $stmt->close();

    echo "<p style='color:green;text-align:center;'>Password successfully reset</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

    <style>
        body {
            background: #f4f6f8;
            font-family: Arial, sans-serif;
        }

        .reset-container {
            width: 360px;
            margin: 80px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .reset-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .reset-container input {
            width: 100%;
            padding: 12px;
            margin-bottom: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .reset-container button {
            width: 100%;
            padding: 12px;
            background: #d97e8a;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            margin-top: 10px;
        }

        .reset-container button:hover {
            background: #c56a76;
        }

        .error {
            color: red;
            font-size: 13px;
            margin-top: 4px;
            display: none;
        }
    </style>
</head>

<body>

<div class="reset-container">
    <h2>Reset Password</h2>

    <form method="POST" id="resetForm">
        <input type="password" name="password" id="password" placeholder="New password" required>
        <div class="error" id="passError">
            Password must be at least 6 characters
        </div>
        <button type="submit">Reset Password</button>
    </form>
</div>

<script>
    document.getElementById("resetForm").addEventListener("submit", function(e) {
        const password = document.getElementById("password").value.trim();
        const error = document.getElementById("passError");

        if (password.length < 6) {
            error.style.display = "block";
            e.preventDefault(); // ❌ mos dërgo formën
        } else {
            error.style.display = "none";
        }
    });
</script>

</body>
</html>


