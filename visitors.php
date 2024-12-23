<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_type = $_POST['vehicle_type'];
    $date = $_POST['date'];
    $hours = $_POST['hours'];
    $num_vehicles = $_POST['num_vehicles'];
    $contact_number = $_POST['contact_number'];

    $sql = "INSERT INTO visitor_passes (vehicle_type, date, hours, num_vehicles, contact_number) 
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiis", $vehicle_type, $date, $hours, $num_vehicles, $contact_number);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Visitor pass request submitted successfully!";
    } else {
        $_SESSION['error_message'] = "Error submitting visitor pass request. Please try again.";
    }
    if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
    } elseif (isset($_SESSION['error_message'])) {
        echo '<div class="alert alert-error">' . $_SESSION['error_message'] . '</div>';
        unset($_SESSION['error_message']);
    }
    $stmt->close();
    $conn->close();

    header("Location: visitors.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Parking System - Home</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/visitors.css">
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
    <main>
    <section class="visitor-pass">
        <h2>Request Visitor Pass</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="vehicle_type">Vehicle Type:</label>
                <select id="vehicle_type" name="vehicle_type" required>
                    <option value="">Select vehicle type</option>
                    <option value="Car">Car</option>
                    <option value="Motorcycle">Motorcycle</option>
                    <option value="Van">Van</option>
                    <option value="Truck">Truck</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="hours">Hours:</label>
                <input type="number" id="hours" name="hours" min="1" max="24" required>
            </div>
            <div class="form-group">
                <label for="num_vehicles">Number of Vehicles:</label>
                <input type="number" id="num_vehicles" name="num_vehicles" min="1" required>
            </div>
            <div class="form-group">
                <label for="contact_number">Contact Number:</label>
                <input type="tel" id="contact_number" name="contact_number" required>
            </div>
            <button type="submit" class="btn">Request Pass</button>
        </form>
    </section>
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
