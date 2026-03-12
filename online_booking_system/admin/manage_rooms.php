<?php
$page_title = 'Manage Rooms - Admin Panel';
$base_url = '../';
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include '../includes/header.php';

$error = '';
$success = '';
$action = $_GET['action'] ?? '';
$room_id = $_GET['room_id'] ?? 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_name = trim($_POST['room_name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $capacity = intval($_POST['capacity']);
    $room_type = trim($_POST['room_type']);
    $amenities = trim($_POST['amenities']);
    $status = $_POST['status'] ?? 'available';
    $image = $_POST['image'] ?? '';
    
    if (empty($room_name) || empty($description) || $price <= 0) {
        $error = "Please fill in all required fields.";
    } else {
        if ($action == 'edit' && $room_id > 0) {
            // Update room
            $update_query = "UPDATE rooms SET room_name = ?, description = ?, price = ?, capacity = ?, room_type = ?, amenities = ?, status = ?, image = ? WHERE room_id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ssdissssi", $room_name, $description, $price, $capacity, $room_type, $amenities, $status, $image, $room_id);
            
            if ($stmt->execute()) {
                $success = "Room updated successfully!";
            } else {
                $error = "Error updating room.";
            }
        } else {
            // Add new room
            $insert_query = "INSERT INTO rooms (room_name, description, price, capacity, room_type, amenities, status, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param("ssdissss", $room_name, $description, $price, $capacity, $room_type, $amenities, $status, $image);
            
            if ($stmt->execute()) {
                $success = "Room added successfully!";
            } else {
                $error = "Error adding room.";
            }
        }
    }
}

// Handle delete
if ($action == 'delete' && $room_id > 0) {
    $delete_query = "DELETE FROM rooms WHERE room_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $room_id);
    
    if ($stmt->execute()) {
        $success = "Room deleted successfully!";
    } else {
        $error = "Error deleting room.";
    }
    
    header("Refresh: 1; url=manage_rooms.php");
}

// Fetch room for editing
$room_to_edit = null;
if ($action == 'edit' && $room_id > 0) {
    $edit_query = "SELECT * FROM rooms WHERE room_id = ?";
    $stmt = $conn->prepare($edit_query);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $room_to_edit = $stmt->get_result()->fetch_assoc();
}

// Fetch all rooms
$rooms_result = $conn->query("SELECT * FROM rooms ORDER BY room_name ASC");
?>

<div class="container admin-container">
    <h1>Manage Rooms & Cottages</h1>
    
    <?php if (!empty($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <div class="admin-form-wrapper">
        <h2><?php echo $action == 'edit' ? 'Edit Room' : 'Add New Room';