<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $sliit_id = $_POST['sliit_id'];
    $nic = $_POST['nic'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET username=?, first_name=?, last_name=?, sliit_id=?, nic=?, gender=?, dob=?, email=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $username, $first_name, $last_name, $sliit_id, $nic, $gender, $dob, $email, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Car Parking System</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/profile.css">
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
                <span class="username">Welcome, <a href="profile.php"><?php echo htmlspecialchars($user['username']); ?></a></span>
                <a href="logout.php" class="btn">Logout</a>
            </div>
        </nav>
    </header>

    <main class="profile-container">
        <section class="user-details">
            <h2>User Details</h2>
            <div class="user-info">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                <p><strong>First Name:</strong> <?php echo htmlspecialchars($user['first_name']); ?></p>
                <p><strong>Last Name:</strong> <?php echo htmlspecialchars($user['last_name']); ?></p>
                <p><strong>SLIIT ID:</strong> <?php echo htmlspecialchars($user['sliit_id']); ?></p>
                <p><strong>NIC:</strong> <?php echo htmlspecialchars($user['nic']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            <button id="updateProfileBtn" class="btn">Update Profile</button>
        </section>

        <section class="user-bookings">
            <h2>Make monthly payment</h2>
            <div class="bookings-list">
                <p>Make your monthly payment of LKR 1000/= in here.</p>
                <br>
                <a href="payment.php" class="btn">Make payment</a>
            </div>
        </section>
    </main>

    <div id="updateProfileModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Update Profile</h2>
            <form id="updateProfileForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="sliit_id">SLIIT ID</label>
                    <input type="text" id="sliit_id" name="sliit_id" value="<?php echo htmlspecialchars($user['sliit_id']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="nic">NIC</label>
                    <input type="text" id="nic" name="nic" value="<?php echo htmlspecialchars($user['nic']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select id="gender" name="gender" required>
                        <option value="male" <?php echo $user['gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo $user['gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                        <option value="other" <?php echo $user['gender'] == 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <button type="submit" class="btn">Save Changes</button>
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
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('updateProfileModal');
        const btn = document.getElementById('updateProfileBtn');
        const span = document.getElementsByClassName('close')[0];
        const form = document.getElementById('updateProfileForm');

        btn.onclick = function() {
            modal.style.display = 'block';
        }

        span.onclick = function() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        form.onsubmit = function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            
            fetch('profile.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile updated successfully!');
                    modal.style.display = 'none';
                    updateUserInfo(formData);
                } else {
                    alert('Error updating profile: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the profile.');
            });
        }

        function updateUserInfo(formData) {
            const userInfo = document.querySelector('.user-info');
            userInfo.innerHTML = `
                <p><strong>Username:</strong> ${formData.get('username')}</p>
                <p><strong>First Name:</strong> ${formData.get('first_name')}</p>
                <p><strong>Last Name:</strong> ${formData.get('last_name')}</p>
                <p><strong>SLIIT ID:</strong> ${formData.get('sliit_id')}</p>
                <p><strong>NIC:</strong> ${formData.get('nic')}</p>
                <p><strong>Gender:</strong> ${formData.get('gender')}</p>
                <p><strong>Date of Birth:</strong> ${formData.get('dob')}</p>
                <p><strong>Email:</strong> ${formData.get('email')}</p>
            `;
            document.querySelector('.username a').textContent = formData.get('username');
        }
    });
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
