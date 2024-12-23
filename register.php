<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $sliit_id = $_POST['sliit_id'];
    $nic = $_POST['nic'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, first_name, last_name, sliit_id, nic, gender, dob, email, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $username, $first_name, $last_name, $sliit_id, $nic, $gender, $dob, $email, $password);

    if ($stmt->execute()) {
        $success_message = "Registration successful!";
        header("Location: login.php");
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Car Parking System</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="container">
        <div class="registration-form">
            <h1>Register for Car Parking</h1>
            <?php
            if (isset($success_message)) {
                echo "<p class='success'>$success_message</p>";
            }
            if (isset($error_message)) {
                echo "<p class='error'>$error_message</p>";
            }
            ?>
            <form id="registerForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-row">
                    <div class="form-column">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" id="first_name" name="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" id="last_name" name="last_name" required>
                        </div>
                        <div class="form-group">
                            <label for="sliit_id">SLIIT ID</label>
                            <input type="text" id="sliit_id" name="sliit_id" required>
                        </div>
                        <div class="form-group">
                            <label for="nic">NIC</label>
                            <input type="text" id="nic" name="nic" required>
                        </div>
                    </div>
                    <div class="form-column">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <select id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dob">Date of Birth</label>
                            <input type="date" id="dob" name="dob" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                    </div>
                </div>
                <button type="submit">Register</button>
            </form>
            <p class="register-link">Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            
            form.addEventListener('submit', function(event) {
                let isValid = true;
                const inputs = form.querySelectorAll('input');
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('error');
                    } else {
                        input.classList.remove('error');
                    }
                });

                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('confirm_password');
                if (password.value !== confirmPassword.value) {
                    isValid = false;
                    password.classList.add('error');
                    confirmPassword.classList.add('error');
                    alert('Passwords do not match');
                }

                if (!isValid) {
                    event.preventDefault();
                    alert('Please fill in all fields correctly');
                }
            });

            const inputs = form.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.classList.add('active');
                });

                input.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        this.classList.remove('active');
                    }
                });
            });
        });
    </script>
</body>
</html>

