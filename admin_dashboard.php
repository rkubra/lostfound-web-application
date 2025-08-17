<?php
include 'config.php';
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_item'])) {
    $delete_id = intval($_POST['delete_id']);
    // Optionally, delete image file here if you want
    $conn->query("DELETE FROM lost_items WHERE id = $delete_id");
    // Optionally, delete related claims, etc.
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['approve_reject'])) {
        $id = $_POST['id'];
        $status = $_POST['status'];
        $sql = "UPDATE lost_items SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        if ($status == 'approved') {
            $item = $conn->query("SELECT * FROM lost_items WHERE id = $id")->fetch_assoc();
            sendEmail($item['email'], "Item Approved", "Your item {$item['item_name']} has been approved.");
        }
    } elseif (isset($_POST['send_announcement'])) {
        $message = $_POST['message'];
        $users = $conn->query("SELECT email FROM users");
        while ($user = $users->fetch_assoc()) {
            sendEmail($user['email'], "University Announcement", $message);
        }
        echo "<div class='alert alert-success'>Announcement sent to all users!</div>";
    } elseif (isset($_POST['verify_claim'])) {
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
}

$pending_reports = $conn->query("SELECT * FROM lost_items WHERE status = 'pending' ORDER BY date_time DESC");
$pending_claims = $conn->query("SELECT c.id, c.proof, c.status, l.item_name, u.username FROM claims c JOIN lost_items l ON c.item_id = l.id JOIN users u ON c.user_id = u.id WHERE c.status = 'pending'");
$users = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Lost & Found - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"> 
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Admin Dashboard</h2>
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item"><a class="nav-link active" href="#pending-reports" data-bs-toggle="tab">Pending Reports</a></li>
            <li class="nav-item"><a class="nav-link" href="#all-reports" data-bs-toggle="tab">All Reports</a></li>
            <li class="nav-item"><a class="nav-link" href="#manage-users" data-bs-toggle="tab">Manage Users</a></li>
            <li class="nav-item"><a class="nav-link" href="#send-announcement" data-bs-toggle="tab">Send Announcement</a></li>
            <li class="nav-item"><a class="nav-link" href="#pending-claims" data-bs-toggle="tab">Pending Claims</a></li>
        </ul>
        <div class="tab-content">
            <!-- Pending Reports Tab -->
            <div class="tab-pane fade show active" id="pending-reports">
                <h3>Pending Reports</h3>
                <div class="row">
                    <?php while ($row = $pending_reports->fetch_assoc()): ?>
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
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <select name="status" class="form-control mb-2">
                                            <option value="approved">Approve</option>
                                            <option value="rejected">Reject</option>
                                        </select>
                                        <button type="submit" name="approve_reject" class="btn btn-primary w-100">Update Status</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            

<!-- All Reports Tab -->
        <div class="tab-pane fade" id="all-reports">
    <h3>Lost Items</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Description</th>
                <th>Date/Time</th>
                <th>Location</th>
                <th>Category</th>
                <th>Email</th>
                <th>Status</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $lost_reports = $conn->query("SELECT * FROM lost_items WHERE type = 'lost' ORDER BY date_time DESC");
        while ($row = $lost_reports->fetch_assoc()):
        ?>
            <tr>
                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><?php echo htmlspecialchars($row['date_time']); ?></td>
                <td><?php echo htmlspecialchars($row['location']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                 <td>
                    <?php if ($row['image']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Item Image" style="max-width: 80px; max-height: 80px;">
                    <?php endif; ?>
                </td>
                <td>
                    <form method="post" onsubmit="return confirm('Delete this item?');">
                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_item" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
             <?php endwhile; ?>
        </tbody>
    </table>

    <h3 class="mt-5">Found Items</h3>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Description</th>
                <th>Date/Time</th>
                <th>Location</th>
                <th>Category</th>
                <th>Email</th>
                <th>Status</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $found_reports = $conn->query("SELECT * FROM lost_items WHERE type = 'found' ORDER BY date_time DESC");
         while ($row = $found_reports->fetch_assoc()):
        ?>
            <tr>
                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                <td><?php echo htmlspecialchars($row['description']); ?></td>
                <td><?php echo htmlspecialchars($row['date_time']); ?></td>
                <td><?php echo htmlspecialchars($row['location']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <?php if ($row['image']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Item Image" style="max-width: 80px; max-height: 80px;">
                    <?php endif; ?>
                </td>
                <td>
                    <form method="post" onsubmit="return confirm('Delete this item?');">
                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_item" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
                 </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <form method="post" action="export_reports.php">
        <button type="submit" class="btn btn-success mt-3">Export Reports (CSV)</button>
    </form>
</div>
            <!-- Manage Users Tab -->
            <div class="tab-pane fade" id="manage-users">
                <h3>Manage Users</h3>
                <table class="table">
                    <thead><tr><th>Username</th><th>Email</th><th>Role</th><th>Action</th></tr></thead>
                    <tbody>
                        <?php while ($row = $users->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo $row['role']; ?></td>
                                <td><a href="delete_user.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <!-- Send Announcement Tab -->
            <div class="tab-pane fade" id="send-announcement">
                <h3>Send Announcement</h3>
                <form method="post">
                    <div class="mb-3">
                        <textarea class="form-control" name="message" rows="4" placeholder="Enter announcement message" required></textarea>
                    </div>
                    <button type="submit" name="send_announcement" class="btn btn-primary">Send</button>
                </form>
            </div>
            <!-- Pending Claims Tab -->
            <div class="tab-pane fade" id="pending-claims">
                <h3>Pending Claims</h3>
                <div class="row">
                    <?php while ($row = $pending_claims->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Item: <?php echo htmlspecialchars($row['item_name']); ?></h5>
                                    <p>User: <?php echo htmlspecialchars($row['username']); ?></p>
                                    <p>Proof: <?php echo htmlspecialchars($row['proof']); ?></p>
                                    <form method="post">
                                        <input type="hidden" name="claim_id" value="<?php echo $row['id']; ?>">
                                        <select name="status" class="form-control mb-2">
                                            <option value="verified">Verify</option>
                                            <option value="rejected">Reject</option>
                                        </select>
                                        <button type="submit" name="verify_claim" class="btn btn-primary w-100">Update Status</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <a href="logout.php" class="btn btn-secondary mt-3">Logout</a>
    </div>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 University Lost & Found. All rights reserved.</p>
         </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>