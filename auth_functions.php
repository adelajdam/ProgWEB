<?php

require_once __DIR__ . "/config.php";

function logAttempt($user_id, $status, $ip = null)
{
    global $conn;

    $ip = $ip ?? ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');

    $stmt = $conn->prepare(
        "INSERT INTO login_logs (user_id, status, ip_address, created_at)
         VALUES (?, ?, ?, NOW())"
    );
    $stmt->bind_param("iss", $user_id, $status, $ip);
    $stmt->execute();
    $stmt->close();
}

function handleFailedAttempt($user_id)
{
    global $conn;

    $stmt = $conn->prepare(
        "SELECT attempts FROM login_attempts WHERE user_id = ?"
    );
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        $stmt = $conn->prepare(
            "INSERT INTO login_attempts (user_id, attempts, last_attempt)
             VALUES (?, 1, NOW())"
        );
        $stmt->bind_param("i", $user_id);
    } else {
        $attempts = $row['attempts'] + 1;
        $blocked_until = null;

        if ($attempts >= 7) {
            $blocked_until = date("Y-m-d H:i:s", time() + 1800);
        }

        $stmt = $conn->prepare(
            "UPDATE login_attempts
             SET attempts = ?, blocked_until = ?, last_attempt = NOW()
             WHERE user_id = ?"
        );
        $stmt->bind_param("isi", $attempts, $blocked_until, $user_id);
    }

    $stmt->execute();
    $stmt->close();
}

function resetAttempts($user_id)
{
    global $conn;

    $stmt = $conn->prepare(
        "UPDATE login_attempts
         SET attempts = 0, blocked_until = NULL
         WHERE user_id = ?"
    );
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

