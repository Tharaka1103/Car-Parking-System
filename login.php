
<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check for admin credentials
    if ($username === 'admin' && $password === 'admin1234') {
        session_start();
        $_SESSION['user_id'] = 'admin';
        $_SESSION['username'] = 'admin';
        header("Location: admin-profile.php");
        exit();
    }
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Invalid username or password";
        }
    } else {
        $error_message = "Invalid username or password";
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
    <title>Login - Car Parking System</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>
    <div class="container">
        <div class="login-form">
            <h1>Login to Car Parking</h1>
            <?php
            if (isset($error_message)) {
                echo "<p class='error'>$error_message</p>";
            }
            ?>
            <form id="loginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
            <p class="register-link">Forget password? <a href="reset-password.php">Reset password</a></p>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            
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

                if (!isValid) {
                    event.preventDefault();
                    alert('Please fill in all fields');
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
