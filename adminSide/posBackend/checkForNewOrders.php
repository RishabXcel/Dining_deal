<?php
session_start();
// Ensure session is started

//check if the user is logged in
require_once '../posBackend/checkIfLoggedIn.php';
require_once '../config.php';

$lastCheckedTime = isset($_SESSION['lastCheckedTime']) ? $_SESSION['lastCheckedTime'] : null;

$query = "SELECT * FROM kitchen WHERE time_ended IS NULL";
if ($lastCheckedTime) {
    $query .= " AND time_submitted > '$lastCheckedTime'";
}

$result = mysqli_query($link, $query);

$newOrders = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $newOrders[] = $row;
    }
}

// Update last checked time
$_SESSION['lastCheckedTime'] = date('Y-m-d H:i:s');

echo json_encode($newOrders);
