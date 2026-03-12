<?php
$page_title = 'Gallery - Resort Booking System';
$base_url = '../';
include '../includes/header.php';

$gallery_items = [
    ['title' => 'Swimming Pool', 'image' => 'pool.jpg', 'category' => 'amenities'],
    ['title' => 'Luxury Suite', 'image' => 'suite.jpg', 'category' => 'rooms'],
    ['title' => 'Beach View', 'image' => 'beach.jpg', 'category' => 'amenities'],
    ['title' => 'Deluxe Room', 'image' => 'deluxe-room.jpg', 'category' => 'rooms'],
    ['title' => 'Restaurant', 'image' => 'restaurant.jpg', 'category' => 'amenities'],
    ['title' => 'Cottage Interior', 'image' => 'cottage-interior.jpg', 'category' => 'rooms'],
];
?>

<style>
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 2rem;
        margin: 2rem 0;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        height: 250px;
        cursor: pointer;
    }

    .gallery-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(44, 95, 141, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .gallery-item:hover .gallery-image {
        transform: scale(1.1);
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-overlay h3 {
        color: white;
        font-size: 1.5rem;
        text-align: center;
    }

    .container h1 {
        text-align: center;
        color: var(--dark-text);
        margin-bottom: 1rem;
        font-size: 2.2rem;
    }

    .gallery-intro {
        text-align: center;
        color: var(--light-text);
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .container h1 {
            font-size: 1.5rem;
        }

        .gallery-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .gallery-item {
            height: 200px;
        }
    }
</style>

<div class="container">
    <h1>Resort Gallery</h1>
    <p class="gallery-intro">Explore our beautiful resort and amenities</p>
    
    <div class="gallery-grid">
        <?php foreach ($gallery_items as $item): ?>
        <div class="gallery-item">
            <img src="../assets/images/<?php echo $item['image'] ?? 'placeholder.jpg'; ?>" alt="<?php echo $item['title']; ?>" class="gallery-image">
            <div class="gallery-overlay">
                <h3><?php echo $item['title']; ?></h3>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>