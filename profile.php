 <?php
include 'config.php';
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$result = $conn->query("SELECT * FROM lost_items WHERE user_id = (SELECT id FROM users WHERE username = '$username')");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost and Found - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="text-center mt-5 text-primary">University Lost and Found</h1>
        <div class="row justify-content-center mt-4">
            <div class="col-md-8">
                <div class="card p-4 shadow-sm">
                    <h3 class="text-center mb-4">Your Profile</h3>
                    <p class="text-center">Welcome! View your reported items below.</p>
                    <?php
                    if ($result->num_rows > 0) {
                        echo '<table class="table table-striped mt-4">';
                        echo '<thead><tr><th>Type</th><th>Name</th><th>Description</th><th>Email</th><th>Contact Info</th><th>Location</th><th>Date</th></tr></thead><tbody>';
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['item_type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . (isset($row['contact_info']) ? htmlspecialchars($row['contact_info']) : 'Not provided') . "</td>"; // Placeholder
                            echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['date_time']) . "</td>";
                            echo "</tr>";
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<p class="text-center mt-4">No items reported yet.</p>';
                    }
                    ?>
                    <div class="text-center mt-4">
                        <a href="index.php" class="btn btn-primary">Report New Item</a>
                        <a href="logout.php" class="btn btn-secondary ms-2">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>