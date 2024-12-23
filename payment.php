<?php
session_start();
require_once 'config.php'; // Ensure you have this file for database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the payment and user details
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $vehicle_number = $_POST['vehicle_number'];
    $amount = 1000; // LKR 1000 per month
    $payment_date = date('Y-m-d H:i:s');

    $sql = "INSERT INTO payments (user_id, name, email, contact_number, vehicle_number, amount, payment_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssis", $user_id, $name, $email, $contact_number, $vehicle_number, $amount, $payment_date);

    if ($stmt->execute()) {
        $success_message = "Payment successful!";
    } else {
        $error_message = "Payment failed. Please try again.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Parking System - Payment</title>
    <link rel="stylesheet" href="css/index.css">
    <style>
        .benefits, .payment-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .benefits ul {
            list-style-type: none;
            padding-left: 1rem;
        }
        .benefits li::before {
            content: "✓";
            color: #e94560;
            margin-right: 0.5rem;
        }
        .payment-container {
            display: flex;
            justify-content: space-between;
        }
        .user-details, .payment-form {
            width: 48%;
        }
        .payment-container input {
            width: 100%;
            margin-bottom: 1rem;
            padding: 0.8rem;
            border: none;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 1rem;
            transition: background 0.3s ease;
        }
        .payment-container input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
        .payment-container input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.3);
        }
        .payment-container button {
            width: 100%;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .payment-container label {
            display: block;
            margin-bottom: 0.5rem;
            color: #e94560;
            font-weight: bold;
        }
    </style>
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
        <section class="payment">
            <h2>Monthly Parking Ticket Payment</h2>
            
            <div class="benefits">
                <h3>Benefits of Our Parking System</h3>
                <ul>
                    <li>24/7 secure parking access</li>
                    <li>Guaranteed parking spot</li>
                    <li>Convenient mobile app for easy entry/exit</li>
                    <li>Priority customer support</li>
                    <li>Monthly parking history and analytics</li>
                    <li>Flexible payment options</li>
                </ul>
            </div>

            <form action="payment.php" method="POST">
                <div class="payment-container">
                    <div class="user-details">
                        <h3>User Details</h3>
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" required>
                        
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                        
                        <label for="contact_number">Contact Number</label>
                        <input type="tel" id="contact_number" name="contact_number" required>
                        
                        <label for="vehicle_number">Vehicle Number</label>
                        <input type="text" id="vehicle_number" name="vehicle_number" required>
                    </div>

                    <div class="payment-form">
                        <h3>Payment Details</h3>
                        <p>Monthly Fee: LKR 1000/=</p>
                        <?php
                        if (isset($success_message)) {
                            echo "<p style='color: green;'>$success_message</p>";
                        } elseif (isset($error_message)) {
                            echo "<p style='color: red;'>$error_message</p>";
                        }
                        ?>
                        <label for="card_number">Card Number</label>
                        <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" required>
                        
                        <label for="card_holder">Card Holder Name</label>
                        <input type="text" id="card_holder" name="card_holder" placeholder="John Doe" required>
                        
                        <label for="expiry_date">Expiry Date</label>
                        <input type="month" id="expiry_date" name="expiry_date" required>
                        
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" placeholder="123" required>
                        
                        <button type="submit" class="btn">Pay LKR 1000</button>
                    </div>
                </div>
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
