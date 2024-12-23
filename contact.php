<?php
session_start();
require_once 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message_content = $_POST['message'];

    $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $subject, $message_content);

    if ($stmt->execute()) {
        $message = "Your message has been sent successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Parking System - Home</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/contact.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Car Parking System</div>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="contact.php">Contact</a></li>
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

    <main class="contact-content">
        <h1>.</h1>
        <?php if (!empty($message)) : ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <div class="contact-container">
            <div class="contact-details">
                <h2>Get in Touch</h2>
                <p><strong>Address:</strong> New kandy road, Malabe</p>
                <p><strong>Phone:</strong> (+94)73 456-7890</p>
                <p><strong>Email:</strong> info@carparking.com</p>
                <p><strong>Working Hours:</strong> Monday - Friday, 9am - 5pm</p>
            </div>
            <div class="contact-form-map">
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.575840574999!2d79.97075661477253!3d6.914682820409654!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae256db1a6771c5%3A0x2c63e344ab9a7536!2sSri%20Lanka%20Institute%20of%20Information%20Technology!5e0!3m2!1sen!2slk!4v1679929045374!5m2!1sen!2slk" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <form id="contactForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <h2>Send us a message</h2>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" required></textarea>
                    </div>
                    <button type="submit">Send Message</button>
                </form>
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
    document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('contactForm');
            form.addEventListener('submit', function(e) {
                const name = document.getElementById('name').value;
                const email = document.getElementById('email').value;
                const subject = document.getElementById('subject').value;
                const message = document.getElementById('message').value;

                if (!name || !email || !subject || !message) {
                    e.preventDefault();
                    alert('Please fill in all fields');
                }
            });
        });
</script>

</body>
</html>
