<?php
$page_title = 'Booking - Resort Booking System';
$base_url = '../';
require_once '../config/database.php';

include '../includes/header.php';

$room_id = $_GET['room_id'] ?? 0;
$check_in = $_GET['check_in'] ?? $_POST['check_in'] ?? '';
$check_out = $_GET['check_out'] ?? $_POST['check_out'] ?? '';
$guests = $_GET['guests'] ?? $_POST['number_of_guests'] ?? '';

$room_query = "SELECT * FROM rooms WHERE room_id = ?";
$stmt = $conn->prepare($room_query);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();

if (!$room) {
    echo "<div class='container'><p>Room not found.</p></div>";
    include '../includes/footer.php';
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $guests = $_POST['number_of_guests'];
    
    $check_in_date = strtotime($check_in);
    $check_out_date = strtotime($check_out);
    $today = strtotime(date('Y-m-d'));
    
    if ($check_in_date < $today) {
        $error = "Check-in date cannot be in the past.";
    } elseif ($check_out_date <= $check_in_date) {
        $error = "Check-out date must be after check-in date.";
    } elseif ($guests > $room['capacity']) {
        $error = "Number of guests exceeds room capacity.";
    } else {
        $availability_query = "SELECT COUNT(*) as count FROM bookings 
                             WHERE room_id = ? AND status != 'cancelled' 
                             AND (
                                 (check_in <= ? AND check_out > ?) OR
                                 (check_in < ? AND check_out >= ?)
                             )";
        $stmt = $conn->prepare($availability_query);
        $stmt->bind_param("issss", $room_id, $check_out, $check_in, $check_out, $check_in);
        $stmt->execute();
        $availability = $stmt->get_result()->fetch_assoc();
        
        if ($availability['count'] > 0) {
            $error = "Room is not available for selected dates.";
        } else {
            $days = ($check_out_date - $check_in_date) / (60 * 60 * 24);
            $total_price = $room['price'] * $days;
            
            $booking_query = "INSERT INTO bookings (user_id, room_id, check_in, check_out, number_of_guests, total_price, status) 
                            VALUES (?, ?, ?, ?, ?, ?, 'pending')";
            $stmt = $conn->prepare($booking_query);
            $stmt->bind_param("iisssi", $user_id, $room_id, $check_in, $check_out, $guests, $total_price);
            
            if ($stmt->execute()) {
                $booking_id = $stmt->insert_id;
                $success = "Booking successful! Your booking ID is: " . $booking_id;
                header("Refresh: 3; url=booking-confirmation.php?booking_id=$booking_id");
            } else {
                $error = "Error processing booking. Please try again.";
            }
        }
    }
}

$price_preview = 0;
if ($check_in && $check_out) {
    $days = (strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24);
    $price_preview = $room['price'] * $days;
}
?>

<style>
    .booking-container {
        margin: 3rem 0;
    }

    .booking-wrapper {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        margin-top: 2rem;
    }

    .booking-form-section h1 {
        margin-bottom: 2rem;
        color: var(--dark-text);
    }

    .booking-form {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: var(--shadow);
    }

    .booking-form h3 {
        margin: 2rem 0 1.5rem 0;
        color: var(--primary-color);
        font-size: 1.3rem;
        border-bottom: 2px solid var(--secondary-color);
        padding-bottom: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--dark-text);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.8rem;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 1rem;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(44, 95, 141, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .booking-summary {
        position: sticky;
        top: 100px;
    }

    .room-summary-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .room-summary-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .summary-details {
        padding: 1.5rem;
    }

    .summary-details h3 {
        margin-bottom: 0.5rem;
        color: var(--dark-text);
    }

    .room-type {
        color: var(--light-text);
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .summary-details p {
        margin-bottom: 0.8rem;
        color: var(--dark-text);
    }

    .dates-summary {
        padding: 1rem 0;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 1rem;
    }

    .dates-summary p {
        font-size: 0.95rem;
    }

    .price-summary {
        background-color: var(--light-bg);
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .price-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.8rem;
        font-size: 0.95rem;
    }

    .price-row.total {
        border-top: 2px solid var(--primary-color);
        padding-top: 0.8rem;
        font-weight: bold;
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    .alert-error {
        background-color: #fadbd8;
        color: #c0392b;
        border-left: 4px solid #e74c3c;
    }

    .alert-success {
        background-color: #eafaf1;
        color: #27ae60;
        border-left: 4px solid #27ae60;
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
        margin-right: 0.5rem;
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

    .btn-lg {
        padding: 1rem 2.5rem;
        font-size: 1.1rem;
        width: 100%;
    }

    @media (max-width: 768px) {
        .booking-wrapper {
            grid-template-columns: 1fr;
        }

        .booking-summary {
            position: static;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .booking-container {
            margin: 1rem 0;
        }

        .booking-form {
            padding: 1.5rem;
        }

        .btn-lg {
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
        }
    }
</style>

<div class="container booking-container">
    <div class="booking-wrapper">
        <div class="booking-form-section">
            <h1>Book <?php echo $room['room_name']; ?></h1>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" class="booking-form">
                <h3>Your Details</h3>
                
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="guest_name" value="<?php echo $_SESSION['user_name'] ?? ''; ?>" disabled>
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="guest_email" value="<?php echo $_SESSION['user_email'] ?? ''; ?>" disabled>
                </div>
                
                <h3>Booking Details</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Check-in Date *</label>
                        <input type="date" name="check_in" value="<?php echo $check_in; ?>" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Check-out Date *</label>
                        <input type="date" name="check_out" value="<?php echo $check_out; ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Number of Guests *</label>
                    <select name="number_of_guests" required>
                        <option value="">Select number of guests</option>
                        <?php for ($i = 1; $i <= $room['capacity']; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $guests == $i ? 'selected' : ''; ?>><?php echo $i; ?> Guest(s)</option>
                        <?php endfor; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Special Requests</label>
                    <textarea name="special_requests" placeholder="Any special requests or requirements..."></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg">Complete Booking</button>
                <a href="rooms.php" class="btn btn-secondary" style="width: auto; display: inline-block; margin-top: 1rem;">Cancel</a>
            </form>
        </div>
        
        <div class="booking-summary">
            <div class="room-summary-card">
                <img src="../assets/images/<?php echo $room['image'] ?? 'placeholder.jpg'; ?>" alt="<?php echo $room['room_name']; ?>">
                
                <div class="summary-details">
                    <h3><?php echo $room['room_name']; ?></h3>
                    <p class="room-type"><?php echo $room['room_type']; ?></p>
                    
                    <p><strong>Capacity:</strong> <?php echo $room['capacity']; ?> Guests</p>
                    <p><strong>Price per night:</strong> $<?php echo number_format($room['price'], 2); ?></p>
                    
                    <?php if ($check_in && $check_out): ?>
                        <div class="dates-summary">
                            <p><strong>Check-in:</strong> <?php echo date('M d, Y', strtotime($check_in)); ?></p>
                            <p><strong>Check-out:</strong> <?php echo date('M d, Y', strtotime($check_out)); ?></p>
                            <p><strong>Number of nights:</strong> <?php echo intval((strtotime($check_out) - strtotime($check_in)) / (60 * 60 * 24)); ?></p>
                        </div>
                        
                        <div class="price-summary">
                            <div class="price-row">
                                <span>Room charges:</span>
                                <span>$<?php echo number_format($price_preview, 2); ?></span>
                            </div>
                            <div class="price-row">
                                <span>Taxes & fees:</span>
                                <span>$<?php echo number_format($price_preview * 0.1, 2); ?></span>
                            </div>
                            <div class="price-row total">
                                <span>Total:</span>
                                <span>$<?php echo number_format($price_preview * 1.1, 2); ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <p style="color: #666; font-size: 0.9rem;">Select dates to see pricing</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>