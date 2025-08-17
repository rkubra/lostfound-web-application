 <?php
include 'config.php';
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $proof = $_POST['proof'];
    $user_id = $conn->query("SELECT id FROM users WHERE username = '{$_SESSION['username']}'")->fetch_assoc()['id'];
    $sql = "INSERT INTO claims (item_id, user_id, proof) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $item_id, $user_id, $proof);
    $stmt->execute();
    echo "<div class='alert alert-success'>Claim submitted for review!</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Lost & Found - User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"> 
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">My Dashboard</h2>
        <h3>Available Items</h3>
        <div class="row">
            <?php
            $sql = "SELECT * FROM lost_items WHERE status = 'approved' ORDER BY date_time DESC";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['item_name']); ?></h5>
                            <p class="card-text">Description: <?php echo htmlspecialchars($row['description']); ?></p>
                            <p>Location: <?php echo htmlspecialchars($row['location']); ?></p>
                            <?php if ($row['image']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="Item Image" style="max-height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <form method="post">
                                <input type="hidden" name="item_id" value="<?php echo $row['id']; ?>">
                                <textarea class="form-control mb-2" name="proof" placeholder="Proof of Ownership" required></textarea>
                                <button type="submit" class="btn btn-primary w-100">Claim Item</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <a href="index.php" class="btn btn-secondary mt-3">Back</a>
    </div>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 University Lost & Found. All Rights Reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Use</a></p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>