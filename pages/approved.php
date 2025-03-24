<?php
require_once '../model/server.php';

if (isset($_GET['booking_id']) && isset($_GET['action'])) {
    $booking_id = $_GET['booking_id'];
    $action = $_GET['action'];
    $connector = new Connector();

    // Initialize $sql
    $sql = '';

    if ($action === 'checked-in') {
        $sql = "UPDATE booking_tb SET booking_status = 'checked-in' WHERE booking_id = :booking_id";
    } elseif ($action === 'checked-out') {
        $sql = "UPDATE booking_tb SET booking_status = 'checked-out' WHERE booking_id = :booking_id";
    }

    // Only proceed if $sql is not empty
    if (!empty($sql)) {
        $params = [':booking_id' => $booking_id];
        
        if ($connector->executeUpdate($sql, $params)) {
            header("Location: ../pages/admin_booking.php?status=success");
        } else {
            header("Location: ../pages/admin_booking.php?status=error");
        }
    } else {
        header("Location: ../pages/admin_booking.php?status=invalid_action");
    }
    exit();
}
?>