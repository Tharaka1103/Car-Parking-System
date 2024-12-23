<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete_user') {
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
        
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "error";
        }

        $stmt->close();
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add_service') {
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $sql = "INSERT INTO services (service_name, description, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssd", $service_name, $description, $price);
    
    if ($stmt->execute()) {
        $success_message = "Service added successfully!";
    } else {
        $error_message = "Failed to add service.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - Car Parking System</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/admin-profile.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Car Parking System</div>
            <div class="auth-buttons">
                <span class="username">Welcome, Admin</span>
                <a href="logout.php" class="btn">Logout</a>
            </div>
        </nav>
    </header>

    <main class="admin-container">
        <aside class="sidebar">
            <ul>
                <li><a href="dashboard.php" data-content="dashboard">Dashboard</a></li>
                <li><a href="#" data-content="payments">Payments</a></li>
                <li><a href="#" data-content="reviews">Reviews</a></li>
                <li><a href="#" data-content="services">Services</a></li>
            </ul>
        </aside>
        <section class="content">
            <div id="dashboard" class="content-section">
                <h2>Dashboard</h2>
                <h3>Registered Users</h3>
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require_once 'config.php';
                        $sql = "SELECT id, username, email, created_at FROM users";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                                echo "<td><button class='delete-btn' data-id='" . $row["id"] . "'>Delete</button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No users found</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>

            <div id="payments" class="content-section" style="display: none;">
                <h2>Payments</h2>
                <p>View and manage all payments made through the car parking system.</p>
            </div>
            <div id="reviews" class="content-section" style="display: none;">
                <h2>Reviews</h2>
                <p>Read and respond to user reviews of the car parking system.</p>
            </div>
            <div id="services" class="content-section" style="display: none;">
                <h2>Services</h2>
                <div class="service-form">
                    <h3>Add New Service</h3>
                    <?php
                    if (isset($success_message)) {
                        echo "<p style='color: green;'>$success_message</p>";
                    } elseif (isset($error_message)) {
                        echo "<p style='color: red;'>$error_message</p>";
                    }
                    ?>
                    <form action="admin-profile.php" method="POST">
                        <input type="hidden" name="action" value="add_service">
                        <div class="form-group">
                            <label for="service_name">Service Name</label>
                            <input type="text" id="service_name" name="service_name" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Service Description</label>
                            <textarea id="description" name="description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Price (LKR)</label>
                            <input type="number" id="price" name="price" step="0.01" required>
                        </div>
                        <button type="submit">Add Service</button>
                    </form>
                </div>
            </div>

        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.sidebar a');
            const contentSections = document.querySelectorAll('.content-section');

            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const contentId = this.getAttribute('data-content');

                    contentSections.forEach(section => {
                        section.style.display = 'none';
                    });

                    document.getElementById(contentId).style.display = 'block';

                    sidebarLinks.forEach(link => {
                        link.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this user?')) {
                    fetch('admin-profile.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'action=delete_user&user_id=' + userId
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            this.closest('tr').remove();
                        } else {
                            alert('Failed to delete user');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the user');
                    });
                }
            });
        });
        const servicesLink = document.querySelector('a[data-content="services"]');
        const servicesSection = document.getElementById('services');

        servicesLink.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.content-section').forEach(section => {
                section.style.display = 'none';
            });
            servicesSection.style.display = 'block';
        });

    });
    </script>
</body>
</html>
