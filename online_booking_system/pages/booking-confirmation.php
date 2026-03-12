<?php
$page_title = 'Booking Confirmation - Resort Booking System';
$base_url = '../';
require_once '../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

include '../includes/header.php';

$booking_id = $_GET['booking_id'] ?? 0;

$booking_query = "SELECT b.*, r.room_name, r.price, u.name, u.email, u.phone 
                 FROM bookings b 
                 JOIN rooms r ON b.room_id = r.room_id 
                 JOIN users u ON b.user_id = u.id 
                 WHERE b.booking_id = ? AND b.user_id = ?";
$stmt = $conn->prepare($booking_query);
$stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    echo "<div class='container'><p>Booking not found.</p></div>";
    include '../includes/footer.php';
    exit();
}

$check_in_date = strtotime($booking['check_in']);
$check_out_date = strtotime($booking['check_out']);
$nights = ($check_out_date - $check_in_date) / (60 * 60 * 24);
?>

<style>
    .confirmation-container {
        display: flex;
        justify-content: center;
        margin: 3rem 0;
    }

    .confirmation-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow-lg);
        max-width: 600px;
        width: 100%;
        overflow: hidden;
    }

    .confirmation-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1e3f5a 100%);
        color: white;
        padding: 2rem;
        text-align: center;
    }

    .confirmation-header i {
        font-size: 3rem;
        margin-bottom: 1rem;
        animation: bounce 0.6s ease;
    }

    .confirmation-header h1 {
        margin-bottom: 0.5rem;
        font-size: 2rem;
    }

    .confirmation-header p {
        margin: 0;
        color: #ecf0f1;
    }

    .confirmation-details {
        padding: 2rem;
    }

    .confirmation-details h3 {
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .detail-section {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }

    .detail-section:last-child {
        border-bottom: none;
    }

    .detail-section h4 {
        color: var(--dark-text);
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .detail-section p {
        margin-bottom: 0.5rem;
        color: var(--light-text);
    }

    .detail-section strong {
        color: var(--dark-text);
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-pending {
        background-color: #fef5e7;
        color: #f39c12;
    }

    .status-confirmed {
        background-color: #eafaf1;
        color: #27ae60;
    }

    .status-cancelled {
        background-color: #fadbd8;
        color: #e74c3c;
    }

    .status-message {
        background-color: var(--light-bg);
        padding: 1rem;
        border-radius: 8px;
        font-size: 0.95rem;
        margin-top: 0.5rem;
    }

    .price-breakdown {
        background-color: var(--light-bg);
        padding: 1rem;
        border-radius: 8px;
    }

    .price-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.8rem;
        font-size: 0.95rem;
    }

    .price-item.total {
        border-top: 2px solid var(--primary-color);
        padding-top: 0.8rem;
        font-weight: bold;
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .confirmation-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border-color);
    }

    .btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 1rem;
        flex: 1;
        text-align: center;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: #1e3f5a;
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .btn-secondary {
        background-color: var(--light-text);
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5d6d7b;
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    @media (max-width: 768px) {
        .confirmation-actions {
            flex-direction: column;
        }

        .confirmation-actions .btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .confirmation-card {
            margin: 0 auto;
        }

        .confirmation-header {
            padding: 1.5rem;
        }

        .confirmation-header i {
            font-size: 2rem;
        }

        .confirmation-header h1 {
            font-size: 1.5rem;
        }

        .confirmation-details {
            padding: 1.5rem;
        }

        .detail-section h4 {
            font-size: 1rem;
        }
    }
</style>

<div class="container">
    <div class="confirmation-container">
        <div class="confirmation-card">
            <div class="confirmation-header">
                <i class="fas fa-check-circle"></i>
                <h1>Booking Confirmed!</h1>
                <p>Thank you for your reservation</p>
            </div>
            
            <div class="confirmation-details">
                <h3>Booking Reference: <strong>#<?php echo str_pad($booking['booking_id'], 6, '0', STR_PAD_LEFT); ?></strong></h3>
                
                <div class="detail-section">
                    <h4>Guest Information</h4>
                    <p><strong>Name:</strong> <?php echo $booking['name']; ?></p>
                    <p><strong>Email:</strong> <?php echo $booking['email']; ?></p>
                    <p><strong>Phone:</strong> <?php echo $booking['phone']; ?></p>
                </div>
                
                <div class="detail-section">
                    <h4>Room Details</h4>
                    <p><strong>Room:</strong> <?php echo $booking['room_name']; ?></p>
                    <p><strong>Check-in:</strong> <?php echo date('M d, Y', $check_in_date); ?></p>
                    <p><strong>Check-out:</strong> <?php echo date('M d, Y', $check_out_date); ?></p>
                    <p><strong>Number of Nights:</strong> <?php echo $nights; ?></p>
                    <p><strong>Number of Guests:</strong> <?php echo $booking['number_of_guests']; ?></p>
                </div>
                
                <div class="detail-section">
                    <h4>Pricing Breakdown</h4>
                    <div class="price-breakdown">
                        <div class="price-item">
                            <span><?php echo $nights; ?> nights × $<?php echo number_format($booking['price'], 2); ?></span>
                            <span>$<?php echo number_format($booking['total_price'], 2); ?></span>
                        </div>
                        <div class="price-item">
                            <span>Taxes & Fees (10%)</span>
                            <span>$<?php echo number_format($booking['total_price'] * 0.1, 2); ?></span>
                        </div>
                        <div class="price-item total">
                            <span>Total Amount</span>
                            <span>$<?php echo number_format($booking['total_price'] * 1.1, 2); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h4>Booking Status</h4>
                    <p>
                        <span class="status-badge status-<?php echo $booking['status']; ?>">
                            <?php echo ucfirst($booking['status']); ?>
                        </span>
                    </p>
                    <p class="status-message">Your booking is pending confirmation. An admin will review and confirm your reservation shortly.</p>
                </div>
            </div>
            
            <div style="padding: 0 2rem 2rem 2rem;">
                <div class="confirmation-actions">
                    <a href="booking-history.php" class="btn btn-primary">View My Bookings</a>
                    <a href="../index.php" class="btn btn-secondary">Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>