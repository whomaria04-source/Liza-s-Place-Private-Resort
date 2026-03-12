<?php
$page_title = 'Rooms & Cottages - Resort Booking System';
$base_url = '../';
require_once '../config/database.php';
include '../includes/header.php';

$check_in = $_POST['check_in'] ?? '';
$check_out = $_POST['check_out'] ?? '';
$guests = $_POST['guests'] ?? '';

$query = "SELECT * FROM rooms WHERE status = 'available'";

if ($check_in && $check_out && $guests) {
    $query = "SELECT r.* FROM rooms r 
              WHERE r.status = 'available' 
              AND r.capacity >= $guests
              AND r.room_id NOT IN (
                  SELECT room_id FROM bookings 
                  WHERE status != 'cancelled' 
                  AND (
                      (check_in <= '$check_out' AND check_out >= '$check_in')
                  )
              )";
} else {
    $query = "SELECT * FROM rooms WHERE status = 'available' ORDER BY price ASC";
}

$result = $conn->query($query);
?>

<style>
    .filter-section {
        background-color: var(--light-bg);
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
    }

    .rooms-section {
        margin: 3rem 0;
    }

    .rooms-section h1 {
        text-align: center;
        margin-bottom: 2rem;
        color: var(--dark-text);
        font-size: 2.2rem;
    }

    .booking-filter-form {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .form-row {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
        width: 100%;
    }

    .form-group {
        flex: 1;
        min-width: 150px;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--dark-text);
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.8rem;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        font-size: 1rem;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(44, 95, 141, 0.1);
    }

    .rooms-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }

    .room-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
    }

    .room-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
    }

    .room-image {
        position: relative;
        overflow: hidden;
        height: 250px;
    }

    .room-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .room-card:hover .room-image img {
        transform: scale(1.1);
    }

    .room-type-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background-color: var(--secondary-color);
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 25px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .room-info {
        padding: 1.5rem;
    }

    .room-info h3 {
        color: var(--dark-text);
        margin-bottom: 0.5rem;
        font-size: 1.3rem;
    }

    .room-description {
        color: var(--light-text);
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .room-amenities {
        margin-bottom: 1rem;
    }

    .room-amenities h4 {
        font-size: 0.9rem;
        color: var(--dark-text);
        margin-bottom: 0.3rem;
    }

    .room-amenities p {
        font-size: 0.8rem;
        color: var(--light-text);
    }

    .room-details {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 0.9rem;
        color: var(--light-text);
    }

    .room-details span {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .room-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--border-color);
    }

    .room-price {
        font-size: 1.3rem;
        font-weight: bold;
        color: var(--primary-color);
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
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .rooms-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .booking-filter-form {
            flex-direction: column;
        }

        .form-row {
            flex-direction: column;
        }

        .form-group {
            min-width: 100%;
        }
    }

    @media (max-width: 480px) {
        .rooms-grid {
            grid-template-columns: 1fr;
        }

        .rooms-section h1 {
            font-size: 1.5rem;
        }
    }
</style>

<div class="container">
    <h1>Available Rooms & Cottages</h1>
    
    <section class="filter-section">
        <form method="POST" action="" class="booking-filter-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Check-in Date</label>
                    <input type="date" name="check_in" value="<?php echo $check_in; ?>" min="<?php echo date('Y-m-d'); ?>">
                </div>
                
                <div class="form-group">
                    <label>Check-out Date</label>
                    <input type="date" name="check_out" value="<?php echo $check_out; ?>" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                </div>
                
                <div class="form-group">
                    <label>Guests</label>
                    <select name="guests">
                        <option value="">Any</option>
                        <option value="1" <?php echo $guests == '1' ? 'selected' : ''; ?>>1 Guest</option>
                        <option value="2" <?php echo $guests == '2' ? 'selected' : ''; ?>>2 Guests</option>
                        <option value="4" <?php echo $guests == '4' ? 'selected' : ''; ?>>4 Guests</option>
                        <option value="6" <?php echo $guests == '6' ? 'selected' : ''; ?>>6+ Guests</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </section>

    <section class="rooms-section">
        <div class="rooms-grid">
            <?php while ($room = $result->fetch_assoc()): ?>
            <div class="room-card">
                <div class="room-image">
                    <img src="../assets/images/<?php echo $room['image'] ?? 'placeholder.jpg'; ?>" alt="<?php echo $room['room_name']; ?>">
                    <span class="room-type-badge"><?php echo $room['room_type']; ?></span>
                </div>
                <div class="room-info">
                    <h3><?php echo $room['room_name']; ?></h3>
                    <p class="room-description"><?php echo $room['description']; ?></p>
                    
                    <div class="room-amenities">
                        <h4>Amenities:</h4>
                        <p><?php echo $room['amenities']; ?></p>
                    </div>
                    
                    <div class="room-details">
                        <span><i class="fas fa-users"></i> Up to <?php echo $room['capacity']; ?> Guests</span>
                        <span><i class="fas fa-star"></i> <?php echo rand(4, 5); ?>/5</span>
                    </div>
                    
                    <div class="room-footer">
                        <span class="room-price">$<?php echo number_format($room['price'], 2); ?>/night</span>
                        <a href="booking.php?room_id=<?php echo $room['room_id']; ?><?php echo $check_in ? '&check_in=' . $check_in : ''; ?><?php echo $check_out ? '&check_out=' . $check_out : ''; ?><?php echo $guests ? '&guests=' . $guests : ''; ?>" class="btn btn-primary">Book Now</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
</div>

<?php include '../includes/footer.php'; ?>