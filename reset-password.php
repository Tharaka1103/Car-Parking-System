<?php
require_once 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $sql = "INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expires = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $email, $token, $expires, $token, $expires);

        if ($stmt->execute()) {
            // Send email with reset link (implement your email sending logic here)
            $reset_link = "http://yourwebsite.com/reset-password.php?token=" . $token;
            $message = "A password reset link has been sent to your email.";
        } else {
            $message = "An error occurred. Please try again.";
        }
        $stmt->close();
    } elseif (isset($_POST['new_password']) && isset($_POST['token'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $token = $_POST['token'];

        $sql = "SELECT email FROM password_resets WHERE token = ? AND expires > NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $email = $row['email'];

            $update_sql = "UPDATE users SET password = ? WHERE email = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $new_password, $email);

            if ($update_stmt->execute()) {
                $delete_sql = "DELETE FROM password_resets WHERE email = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("s", $email);
                $delete_stmt->execute();

                $message = "Your password has been successfully reset.";
            } else {
                $message = "An error occurred. Please try again.";
            }
            $update_stmt->close();
        } else {
            $message = "Invalid or expired token.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Car Parking System</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/reset-password.css">
</head>
<body>
    <div class="container">
        <div class="reset-password-form">
            <h1>Reset Password</h1>
            <?php if (!empty($message)) : ?>
                <p class="message"><?php echo $message; ?></p>
            <?php endif; ?>
            <?php if (!isset($_GET['token'])) : ?>
                <form id="requestResetForm" method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <button type="submit">Request Password Reset</button>
                </form>
            <?php else : ?>
                <form id="resetPasswordForm" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit">Reset Password</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const requestResetForm = document.getElementById('requestResetForm');
            const resetPasswordForm = document.getElementById('resetPasswordForm');

            if (requestResetForm) {
                requestResetForm.addEventListener('submit', function(e) {
                    const email = document.getElementById('email').value;
                    if (!isValidEmail(email)) {
                        e.preventDefault();
                        alert('Please enter a valid email address.');
                    }
                });
            }

            if (resetPasswordForm) {
                resetPasswordForm.addEventListener('submit', function(e) {
                    const newPassword = document.getElementById('new_password').value;
                    const confirmPassword = document.getElementById('confirm_password').value;

                    if (newPassword !== confirmPassword) {
                        e.preventDefault();
                        alert('Passwords do not match.');
                    } else if (newPassword.length < 8) {
                        e.preventDefault();
                        alert('Password must be at least 8 characters long.');
                    }
                });
            }

            function isValidEmail(email) {
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
        });

    </script>
</body>
</html>
