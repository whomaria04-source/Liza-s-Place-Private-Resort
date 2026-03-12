<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Resort Booking System'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c5f8d;
            --secondary-color: #f39c12;
            --light-bg: #f5f7fa;
            --dark-text: #2c3e50;
            --light-text: #7f8c8d;
            --border-color: #ecf0f1;
            --shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--dark-text);
            line-height: 1.6;
            background-color: #fff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* NAVIGATION BAR */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #1e3f5a 100%);
            color: white;
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow);
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .navbar-brand a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .navbar-brand a:hover {
            color: var(--secondary-color);
        }

        .navbar-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }

        .navbar-menu a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .navbar-menu a:hover {
            color: var(--secondary-color);
        }

        .btn-login {
            background-color: var(--secondary-color) !important;
            padding: 0.5rem 1.2rem !important;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #e67e22 !important;
            transform: translateY(-2px);
        }

        .user-menu {
            position: relative;
        }

        .user-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            color: var(--dark-text);
            list-style: none;
            border-radius: 8px;
            min-width: 200px;
            box-shadow: var(--shadow-lg);
            margin-top: 0.5rem;
            overflow: hidden;
        }

        .user-dropdown li {
            padding: 0;
        }

        .user-dropdown a {
            display: block;
            padding: 0.8rem 1.2rem;
            color: var(--dark-text);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .user-dropdown a:hover {
            background-color: var(--light-bg);
            color: var(--primary-color);
            padding-left: 1.5rem;
        }

        .user-menu:hover .user-dropdown {
            display: block;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            cursor: pointer;
        }

        .hamburger span {
            width: 25px;
            height: 3px;
            background-color: white;
            margin: 5px 0;
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        /* HERO SECTION */
        .hero {
            background: linear-gradient(rgba(44, 95, 141, 0.7), rgba(30, 63, 90, 0.7)), 
                        url('../images/hero-bg.jpg') center/cover no-repeat;
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            margin-bottom: 3rem;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-content p {
            font-size: 1.5rem;
            margin-bottom: 2rem;
            color: #ecf0f1;
        }

        /* BUTTONS */
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

        .btn-lg {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .navbar-menu {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .hamburger {
                display: flex;
            }

            .hero-content h1 {
                font-size: 2.2rem;
            }

            .hero-content p {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .navbar-brand {
                font-size: 1.3rem;
            }

            .hero-content h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="<?php echo $base_url ?? './'; ?>index.php">🏖️ Maputi Private Resort</a>
            </div>
            
            <ul class="navbar-menu">
                <li><a href="<?php echo $base_url ?? './'; ?>index.php">Home</a></li>
                <li><a href="<?php echo $base_url ?? './'; ?>pages/rooms.php">Book Now</a></li>
                <li><a href="<?php echo $base_url ?? './'; ?>pages/gallery.php">Gallery</a></li>
                <li><a href="<?php echo $base_url ?? './'; ?>pages/contact.php">Contact</a></li>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="user-menu">
                        <a href="#"><?php echo $_SESSION['user_name'] ?? 'Guest'; ?> <i class="fas fa-chevron-down"></i></a>
                        <ul class="user-dropdown">
                            <?php if (($_SESSION['user_role'] ?? null) == 'admin'): ?>
                                <li><a href="<?php echo $base_url ?? './'; ?>admin/dashboard.php"><i class="fas fa-chart-line"></i> Admin Dashboard</a></li>
                            <?php else: ?>
                                <li><a href="<?php echo $base_url ?? './'; ?>pages/booking-history.php"><i class="fas fa-calendar-check"></i> My Bookings</a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo $base_url ?? './'; ?>auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo $base_url ?? './'; ?>auth/login.php" class="btn-login">Login</a></li>
                <?php endif; ?>
            </ul>
            
            <div class="hamburger">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>