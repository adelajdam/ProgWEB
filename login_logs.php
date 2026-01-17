<?php
function logAttempt($user_id, $status){
    global $pdo;
    $ip = $_SERVER['REMOTE_ADDR'];

    $pdo->prepare("INSERT INTO login_logs (user_id, ip_address, status)
        VALUES (?, ?, ?)")
        ->execute([$user_id, $ip, $status]);
}

