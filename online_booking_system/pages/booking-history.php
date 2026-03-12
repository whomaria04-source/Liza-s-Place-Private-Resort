<?php
$page_title = 'My Bookings - Resort Booking System';
$base_url = '../';
require_once '../config/database.php';


include '../includes/header.php';

$user_id = $_SESSION['user_id'];

$bookings_query = "SELECT b.*, r.room_name, r.price, r.image 
                  FROM bookings b 
                  JOIN rooms r ON b.room_id = r.room_id 
                  WHERE b.user_id = ? 
                  ORDER BY b.created_at DESC";
$stmt = $conn->prepare($bookings_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings_result = $stmt->get_result();
?>

<style>
    .bookings-table-wrapper {
        overflow-x: auto;
        margin: 2rem 0;
    }

    .bookings-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .bookings-table thead {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1e3f5a 100%);
        color: white;
    }

    .bookings-table th {
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .bookings-table td {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .bookings-table tbody tr {
        transition: all 0.3s ease;
    }

    .bookings-table tbody tr:hover {
        background-color: var(--light-bg);
    }

    .bookings-table tbody tr:last-child td {
        border-bottom: none;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
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

    .btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 0.9rem;
        background-color: var(--primary-color);
        color: white;
    }

    .btn:hover {
        background-color: #1e3f5a;
        transform: translateY(-2px);
    }

    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        background: white;
        border-radius: 12px;
        box-shadow: var(--shadow);
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--light-text);
        margin-bottom: 1rem;
    }

    .empty-state h2 {
        color: var(--dark-text);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--light-text);
        margin-bottom: 1.5rem;
    }

    .empty-state .btn {
        background-color: var(--primary-color);
    }

    .container h1 {
        color: var(--dark-text);
        margin-bottom: 2rem;
        font-size: 2.2rem;
    }

    @media (max-width: 768px) {
        .bookings-table-wrapper {
            overflow-x: auto;
        }

        .bookings-table {
            min-width: 600px;
        }

        .bookings-table th,
        .bookings-table td {
            padding: 0.75rem;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .container h1 {
            font-size: 1.5rem;
        }

        .bookings-table th,
        .bookings-table td {
            padding: 0.6rem;
            font-size: 0.8rem;
        }

        .status-badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
        }

        .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
    }
</style>

<div class="container">
    <h1>My Bookings</h1>
    
    <?php if ($bookings_result->num_rows > 0): ?>
        <div class="bookings-table-wrapper">
            <table class="bookings-table">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Guests</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = $bookings_result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo str_pad($booking['booking_id'], 6, '0', STR_PAD_LEFT); ?></td>
                        <td><?php echo $booking['room_name']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($booking['check_in'])); ?></td>
                        <td><?php echo date('M d, Y', strtotime($booking['check_out'])); ?></td>
                        <td><?php echo $booking['number_of_guests']; ?></td>
                        <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
                        <td><span class="status-badge status-<?php echo $booking['status']; ?>"><?php echo ucfirst($booking['status']); ?></span></td>
                        <td>
                            <a href="booking-confirmation.php?booking_id=<?php echo $booking['booking_id']; ?>" class="btn">Details</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-calendar"></i>
            <h2>No Bookings Yet</h2>
            <p>You haven't made any bookings yet. Start exploring our rooms!</p>
            <a href="rooms.php" class="btn">Browse Rooms</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>