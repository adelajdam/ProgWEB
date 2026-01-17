<?php
function handleFailedAttempt($user_id){
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM login_attempts WHERE user_id=?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch();

    if (!$row) {
        $pdo->prepare("INSERT INTO login_attempts (user_id, attempts) VALUES (?,1)")
            ->execute([$user_id]);
    } else {
        $attempts = $row['attempts'] + 1;
        $blocked = ($attempts >= 7)
            ? date("Y-m-d H:i:s", time()+1800)
            : null;

        $pdo->prepare("UPDATE login_attempts 
            SET attempts=?, blocked_until=?, last_attempt=NOW() 
            WHERE user_id=?")
            ->execute([$attempts,$blocked,$user_id]);
    }
}

function resetAttempts($user_id){
    global $pdo;
    $pdo->prepare("UPDATE login_attempts SET attempts=0, blocked_until=NULL WHERE user_id=?")
        ->execute([$user_id]);
}

