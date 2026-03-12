<?php
$page_title = 'Home - Resort Booking System';
$base_url = './';
require_once 'config/database.php';
include 'includes/header.php';

$rooms_query = "SELECT * FROM rooms WHERE status = 'available' LIMIT 3";
$rooms_result = $conn->query($rooms_query);

$reviews = [
    ['name' => 'John Doe', 'rating' => 5, 'comment' => 'Amazing experience! The resort is absolutely beautiful and the staff is very friendly.'],
    ['name' => 'Sarah Smith', 'rating' => 5, 'comment' => 'Best vacation ever! The rooms are spacious and the amenities are top-notch.'],
    ['name' => 'Mike Johnson', 'rating' => 4, 'comment' => 'Great location and wonderful service. Highly recommended!']
];
?>

<style>
    /* Quick Booking Form */
    .quick-booking {
        background-color: var(--light-bg);
        padding: 2.5rem;
        border-radius: 12px;
        margin-bottom: 3rem;
        box-shadow: var(--shadow);
    }

    .quick-booking h2 {
        margin-bottom: 1.5rem;
        color: var(--dark-text);
        font-size: 2rem;
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

    /* Room Cards */
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

    /* Grids */
    .rooms-grid,
    .reviews-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }

    .featured-rooms,
    .reviews {
        margin: 3rem 0;
    }

    .featured-rooms h2,
    .reviews h2 {
        text-align: center;
        margin-bottom: 2rem;
        color: var(--dark-text);
        font-size: 2.2rem;
    }

    /* Review Cards */
    .review-card {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
    }

    .review-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .review-stars {
        color: var(--secondary-color);
        margin-bottom: 1rem;
        font-size: 1.1rem;
    }

    .review-text {
        font-style: italic;
        color: var(--light-text);
        margin-bottom: 1rem;
        line-height: 1.8;
    }

    .review-author {
        font-weight: 600;
        color: var(--dark-text);
    }

    @media (max-width: 768px) {
        .rooms-grid,
        .reviews-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .booking-filter-form {
            flex-direction: column;
        }
    }

    @media (max-width: 480px) {
        .rooms-grid,
        .reviews-grid {
            grid-template-columns: 1fr;
        }

        .featured-rooms h2,
        .reviews h2 {
            font-size: 1.5rem;
        }
    }
</style>

<section class="hero">
    <div class="hero-content">
        <h1>Welcome to Liza's Place Private Resort</h1>
        <p>Experience the Ultimate Luxury Getaway</p>
        <a href="pages/rooms.php" class="btn btn-primary btn-lg">Explore Our Rooms</a>
    </div>
</section>

<div class="container">
    <section class="quick-booking">
        <h2>Quick Booking</h2>
        <form method="POST" action="pages/rooms.php" class="booking-filter-form">
            <div class="form-row">
                <div class="form-group">
                    <label>Check-in Date</label>
                    <input type="date" name="check_in" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Check-out Date</label>
                    <input type="date" name="check_out" min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                </div>
                
                <div class="form-group">
                    <label>Number of Guests</label>
                    <select name="guests" required>
                        <option value="">Select</option>
                        <option value="1">1 Guest</option>
                        <option value="2">2 Guests</option>
                        <option value="3">3 Guests</option>
                        <option value="4">4 Guests</option>
                        <option value="5">5 Guests</option>
                        <option value="6">6+ Guests</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Search Rooms</button>
                </div>
            </div>
        </form>
    </section>

    <section class="featured-rooms">
        <h2>Featured Rooms & Cottages</h2>
        <div class="rooms-grid">
            <?php while ($room = $rooms_result->fetch_assoc()): ?>
            <div class="room-card">
                <div class="room-image">
                    <img src="assets/images/<?php echo $room['image'] ?? 'placeholder.jpg'; ?>" alt="<?php echo $room['room_name']; ?>">
                    <span class="room-type-badge"><?php echo $room['room_type']; ?></span>
                </div>
                <div class="room-info">
                    <h3><?php echo $room['room_name']; ?></h3>
                    <p class="room-description"><?php echo substr($room['description'], 0, 100) . '...'; ?></p>
                    <div class="room-details">
                        <span><i class="fas fa-users"></i> <?php echo $room['capacity']; ?> Guests</span>
                        <span><i class="fas fa-star"></i> <?php echo rand(4, 5); ?>/5</span>
                    </div>
                    <div class="room-footer">
                        <span class="room-price">$<?php echo number_format($room['price'], 2); ?>/night</span>
                        <a href="pages/booking.php?room_id=<?php echo $room['room_id']; ?>" class="btn btn-primary">Book Now</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section class="reviews">
        <h2>What Our Guests Say</h2>
        <div class="reviews-grid">
            <?php foreach ($reviews as $review): ?>
            <div class="review-card">
                <div class="review-stars">
                    <?php for ($i = 0; $i < $review['rating']; $i++): ?>
                        <i class="fas fa-star"></i>
                    <?php endfor; ?>
                </div>
                <p class="review-text">"<?php echo $review['comment']; ?>"</p>
                <p class="review-author">- <?php echo $review['name']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>