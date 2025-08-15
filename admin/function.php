<?php
// includes/functions.php

/**
 * Log an action to security_logs.
 * $user_id can be null for anonymous/failed login events.
 */
function logAction(PDO $conn, $user_id, string $action, $details = null) {
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? null;

        $stmt = $conn->prepare("
            INSERT INTO security_logs (user_id, action, details, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?)
        ");
        // Ensure types: if user_id is null then pass null (PDO handles it)
        $stmt->execute([$user_id, $action, $details, $ip, $ua]);
        return true;
    } catch (Exception $e) {
        // For development: echo/throw; in production log to file instead
        error_log("logAction error: " . $e->getMessage());
        return false;
    }
}
