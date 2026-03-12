<?php
$page_title = 'Contact Us - Resort Booking System';
$base_url = '../';
include '../includes/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($name) || empty($email) || empty($message)) {
        $error = "All fields are required.";
    } else {
        $success = "Thank you for your message. We will get back to you soon!";
    }
}
?>

<style>
    .contact-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin: 3rem 0;
    }

    .contact-form-section,
    .contact-info-section {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: var(--shadow);
    }

    .contact-form-section h2,
    .contact-info-section h2 {
        color: var(--dark-text);
        margin-bottom: 1.5rem;
    }

    .contact-form {
        display: flex;
        flex-direction: column;
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
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(44, 95, 141, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
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
        background-color: var(--primary-color);
        color: white;
    }

    .btn:hover {
        background-color: #1e3f5a;
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
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

    .contact-item {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .contact-item i {
        font-size: 1.5rem;
        color: var(--primary-color);
        min-width: 30px;
    }

    .contact-item h4 {
        margin-bottom: 0.5rem;
        color: var(--dark-text);
    }

    .contact-item p {
        color: var(--light-text);
    }

    .map-container {
        margin-top: 2rem;
        border-radius: 8px;
        overflow: hidden;
    }

    .container h1 {
        text-align: center;
        color: var(--dark-text);
        margin-bottom: 2rem;
        font-size: 2.2rem;
    }

    @media (max-width: 768px) {
        .contact-wrapper {
            grid-template-columns: 1fr;
        }

        .container h1 {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .contact-form-section,
        .contact-info-section {
            padding: 1.5rem;
        }

        .contact-item {
            gap: 1rem;
        }

        .contact-item i {
            font-size: 1.2rem;
        }
    }
</style>

<div class="container">
    <h1>Contact Us</h1>
    
    <div class="contact-wrapper">
        <div class="contact-form-section">
            <h2>Send us a Message</h2>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="" class="contact-form">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" required>
                </div>
                
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label>Phone</label>
                    <input type="tel" name="phone">
                </div>
                
                <div class="form-group">
                    <label>Message *</label>
                    <textarea name="message" required></textarea>
                </div>
                
                <button type="submit" class="btn">Send Message</button>
            </form>
        </div>
        
        <div class="contact-info-section">
            <h2>Contact Information</h2>
            
            <div class="contact-item">
                <i class="fas fa-map-marker-alt"></i>
                <div>
                    <h4>Address</h4>
                    <p>123 Resort Lane<br>Paradise City, PC 12345<br>Country</p>
                </div>
            </div>
            
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <div>
                    <h4>Phone</h4>
                    <p>+1 (555) 123-4567<br>+1 (555) 987-6543</p>
                </div>
            </div>
            
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <div>
                    <h4>Email</h4>
                    <p>info@resort.com<br>support@resort.com</p>
                </div>
            </div>
            
            <div class="contact-item">
                <i class="fas fa-clock"></i>
                <div>
                    <h4>Hours</h4>
                    <p>Mon - Fri: 9:00 AM - 6:00 PM<br>Sat - Sun: 10:00 AM - 4:00 PM</p>
                </div>
            </div>
            
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3024.1234567890!2d-74.0060!3d40.7128!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNDDCsDQyJzQ2LjAiTiA3NMKwMDAnMjEuNiJX!5e0!3m2!1sen!2sus!4v1234567890" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>