<?php
$page_title = 'Admin Dashboard - Resort Booking System';
$base_url = '../';
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

include '../includes/header.php';

$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
$total_users = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'customer'")->fetch_assoc()['count'];
$total_rooms = $conn->query("SELECT COUNT(*) as count FROM rooms")->fetch_assoc()['count'];
$pending_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'")->fetch_assoc()['count'];

$recent_bookings = $conn->query("SELECT b.*, r.room_name, u.name FROM bookings b 
                                JOIN rooms r ON b.room_id = r.room_id 
                                JOIN users u ON b.user_id = u.id 
                                ORDER BY b.created_at DESC LIMIT 5");
?>

<style>
    .admin-container {
        margin-top: 2rem;
        margin-bottom: 2rem;
    }

    .admin-container h1 {
        color: var(--dark-text);
        margin-bottom: 2rem;
        font-size: 2.2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: var(--shadow);
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .stat-card.warning {
        border-left: 4px solid var(--warning-color);
    }

    .stat-icon {
        font-size: 2.5rem;
        color: var(--primary-color);
        min-width: 60px;
        text-align: center;
    }

    .stat-card.warning .stat-icon {
        color: var(--warning-color);
    }

    .stat-info h3 {
        color: var(--light-text);
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;