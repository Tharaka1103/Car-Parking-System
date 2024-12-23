<?php
session_start();
// Database connection
require_once 'config.php';

// Fetch services from the database
$sql = "SELECT * FROM services";
$result = $conn->query($sql);
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $owner_name = $_POST["owner_name"];
    $contact_number = $_POST["contact_number"];
    $email = $_POST["email"];
    $car_number = $_POST["car_number"];
    $payment_date = date("Y-m-d H:i:s");
    $service_id = $_POST["service_id"];

    // Check if the service_id exists in the services table
    $check_service = "SELECT id FROM services WHERE id = ?";
    $check_stmt = $conn->prepare($check_service);
    $check_stmt->bind_param("i", $service_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Service exists, proceed with booking
        $sql = "INSERT INTO bookings (owner_name, contact_number, email, car_number, payment_date, service_id) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $owner_name, $contact_number, $email, $car_number, $payment_date, $service_id);

        if ($stmt->execute()) {
            echo "<script>alert('Booking successful!');</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error: Invalid service selected.');</script>";
    }

    $check_stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Parking Services</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/services.css">
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

    <div class="container" >
        <h1>Car Parking Services</h1>
        <div class="services-grid">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='service-card'>";
                    echo "<h2>" . $row["service_name"] . "</h2>";
                    echo "<p>" . $row["description"] . "</p>";
                    echo "<p class='price'>$" . $row["price"] . "</p>";
                    echo "<button class='book-now' onclick='openBookingForm(" . $row["id"] . ")'>Book Now</button>";
                    echo "</div>";
                }
            } else {
                echo "No services available.";
            }
            ?>
        </div>
    </div>

    <div id="bookingFormModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeBookingForm()">&times;</span>
            <h2>Booking Form</h2>
            <form id="bookingForm" method="POST">
                <input type="hidden" id="service_id" name="service_id">
                <label for="owner_name">Owner Name:</label>
                <input type="text" id="owner_name" name="owner_name" required>
                
                <label for="contact_number">Contact Number:</label>
                <input type="tel" id="contact_number" name="contact_number" required>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="car_number">Car Number:</label>
                <input type="text" id="car_number" name="car_number" required>
                
                <h3>Payment Details</h3>
                <label for="card_type">Card Type:</label>
                <select id="card_type" name="card_type" required>
                    <option value="visa">Visa</option>
                    <option value="mastercard">Mastercard</option>
                    <option value="amex">American Express</option>
                </select>
                
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number" required>
                
                <label for="expiry_date">Expiry Date:</label>
                <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>
                
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" required>
                
                <input type="submit" value="Submit Booking">
            </form>
        </div>
    </div>
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
    <script>
        function openBookingForm(serviceId) {
            document.getElementById("service_id").value = serviceId;
            document.getElementById("bookingFormModal").style.display = "block";
        }

        function closeBookingForm() {
            document.getElementById("bookingFormModal").style.display = "none";
        }
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
