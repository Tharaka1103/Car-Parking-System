<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Parking System - Home</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/faq.css">
    <link rel="stylesheet" href="css/privacy.css">

</head>
<body>
    <header>
        <nav>
            <div class="logo">Car Parking System</div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="username">Welcome, <a href="profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a></span>
                    <a href="logout.php" class="btn">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn">Login</a>
                    <a href="register.php" class="btn">Register</a>
                <?php endif; ?>
            </div>

        </nav>
    </header>
    
    <main class="faq-content">
        <h1>Frequently Asked Questions</h1>
        <div class="faq-list">
            <div class="faq-item">
                <h2>How do I make a parking reservation?</h2>
                <p>To make a reservation, log in to your account, select your desired parking location, date, and time, then follow the prompts to complete your booking.</p>
            </div>
            <div class="faq-item">
                <h2>Can I cancel my reservation?</h2>
                <p>Yes, you can cancel your reservation up to 24 hours before the scheduled parking time for a full refund. Cancellations made within 24 hours may be subject to a cancellation fee.</p>
            </div>
            <div class="faq-item">
                <h2>What happens if I arrive late for my reservation?</h2>
                <p>We allow a 15-minute grace period for late arrivals. If you arrive more than 15 minutes late, your space may be given to another customer, and you may need to make a new reservation.</p>
            </div>
            <div class="faq-item">
                <h2>Is my vehicle insured while parked in your facility?</h2>
                <p>While we take every precaution to ensure the safety of your vehicle, we are not responsible for any damage or theft. We recommend checking your personal auto insurance policy for coverage details.</p>
            </div>
            <div class="faq-item">
                <h2>Do you offer long-term parking?</h2>
                <p>Yes, we offer long-term parking options. Please contact our customer service for special rates on extended parking periods.</p>
            </div>
        </div>
    </main>

    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>About Us</h3>
                <p>Car Parking System revolutionizes urban parking with smart, efficient, and secure solutions. We're committed to making parking hassle-free for everyone.</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="faq.php">FAQ</a></li>
                    <li><a href="privacy.php">Privacy & Policies</a></li>
                    <li><a href="terms.php">Terms & Conditions</a></li>
                    <li><a href="about.php">About Us</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p>123 Parking Avenue, Cityville</p>
                <p>Phone: (123) 456-7890</p>
                <p>Email: info@carparking.com</p>
            </div>
            <div class="footer-section">
                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-section calendar-section">
                <h3>Calendar</h3>
                <div id="footer-calendar"></div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 Car Parking System. All rights reserved.</p>
        </div>
    </footer>



    <script src="home-script.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('footer-calendar');
        const today = new Date();
        const currentMonth = today.getMonth();
        const currentYear = today.getFullYear();

        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const firstDay = new Date(currentYear, currentMonth, 1).getDay();

        let calendarHTML = '<table><tr><th colspan="7">' + today.toLocaleString('default', { month: 'long' }) + ' ' + currentYear + '</th></tr>';
        calendarHTML += '<tr><th>Su</th><th>Mo</th><th>Tu</th><th>We</th><th>Th</th><th>Fr</th><th>Sa</th></tr><tr>';

        for (let i = 0; i < firstDay; i++) {
            calendarHTML += '<td></td>';
        }

        for (let day = 1; day <= daysInMonth; day++) {
            if ((day + firstDay - 1) % 7 === 0 && day !== 1) {
                calendarHTML += '</tr><tr>';
            }
            calendarHTML += '<td' + (day === today.getDate() ? ' class="today"' : '') + '>' + day + '</td>';
        }

        calendarHTML += '</tr></table>';
        calendarEl.innerHTML = calendarHTML;
    });
</script>

</body>
</html>
