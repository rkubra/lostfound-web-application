 <?php
include 'config.php';
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $claim_id = $_POST['claim_id'];
    $status = $_POST['status'];
    $sql = "UPDATE claims SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $claim_id);
    $stmt->execute();
    if ($status == 'verified') {
        $claim = $conn->query("SELECT item_id FROM claims WHERE id = $claim_id")->fetch_assoc();
        $sql = "UPDATE lost_items SET status = 'claimed' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $claim['item_id']);
        $stmt->execute();
        $user = $conn->query("SELECT email FROM users WHERE id = (SELECT user_id FROM claims WHERE id = $claim_id)")->fetch_assoc();
        sendEmail($user['email'], "Claim Verified", "Your claim has been verified.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Lost & Found - Claim Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Review Claims</h2>
        <div class="row">
            <?php
            $sql = "SELECT c.id, c.proof, c.proof_file, c.status, l.item_name, u.username 
                    FROM claims c 
                    JOIN lost_items l ON c.item_id = l.id 
                    JOIN users u ON c.user_id = u.id 
                    WHERE c.status = 'pending'";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Item: <?php echo htmlspecialchars($row['item_name']); ?></h5>
                            <p>User: <?php echo htmlspecialchars($row['username']); ?></p>
                            <p>Proof: <?php echo htmlspecialchars($row['proof']); ?></p>
                            <?php if (!empty($row['proof_file'])): ?>
                                <p>Proof File: 
                                    <a href="uploads/proofs/<?php echo htmlspecialchars($row['proof_file']); ?>" target="_blank">
                                        View File
                                    </a>
                                </p>
                            <?php endif; ?>
                            <form method="post">
                                <input type="hidden" name="claim_id" value="<?php echo $row['id']; ?>">
                                <select name="status" class="form-control mb-2">
                                    <option value="verified">Verify</option>
                                    <option value="rejected">Reject</option>
                                </select>
                                <button type="submit" class="btn btn-primary w-100">Update Status</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back</a>
    </div>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 University Lost & Found. All Rights Reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Use</a></p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap