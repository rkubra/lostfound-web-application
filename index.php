 <?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">University Lost & Found</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['username'])): ?>
                        <li class="nav-item"><a class="nav-link" href="report_item.php">Report Item</a></li>
                        <li class="nav-item"><a class="nav-link" href="user_dashboard.php">My Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['admin_logged_in'])): ?>
                        <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Admin Dashboard</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- START main-content wrapper for sticky footer -->
    <div class="main-content">
        <div class="hero">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <h1>Welcome to University Lost & Found</h1>
                <p>Reconnect with your lost items or help others recover theirs with our secure system.</p>
                <a href="register.php" class="btn btn-custom">Get Started Now</a>
            </div>
        </div>

        <div class="features">
            <div class="container">

                <!-- Lost Items Section -->
                <h2 class="text-center mb-4">Recently Reported Lost Items</h2>
                <div class="row">
                    <?php
                    // Only show items that are approved and type lost
                    $sql = "SELECT * FROM lost_items WHERE status = 'approved' AND type = 'lost' ORDER BY date_time DESC";
                    $result = $conn->query($sql);
                    $hasLost = false;
                    if ($result && $result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                            $hasLost = true;
                    ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['item_name']); ?></h5>
                                    <p class="card-text">Location: <?php echo htmlspecialchars($row['location']); ?></p>
                                    <?php if (!empty($row['image'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="Item Image" style="max-height: 200px; object-fit: cover;">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile;
                    endif;
                    if (!$hasLost) {
                        echo '<p class="text-center">No lost items have been reported yet.</p>';
                    }
                    ?>
                </div>

                <!-- Found Items Section -->
                <h2 class="text-center mb-4 mt-5">Recently Reported Found Items</h2>
                <div class="row">
                    <?php
                    // Only show items that are approved and type found
                    $sql = "SELECT * FROM lost_items WHERE status = 'approved' AND type = 'found' ORDER BY date_time DESC";
                    $result = $conn->query($sql);
                    $hasFound = false;
                    if ($result && $result->num_rows > 0):
                        while ($row = $result->fetch_assoc()):
                            $hasFound = true;
                    ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['item_name']); ?></h5>
                                    <p class="card-text">Location: <?php echo htmlspecialchars($row['location']); ?></p>
                                    <?php if (!empty($row['image'])): ?>
                                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="Item Image" style="max-height: 200px; object-fit: cover;">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile;
                    endif;
                    if (!$hasFound) {
                        echo '<p class="text-center">No found items have been reported yet.</p>';
                    }
                    ?>
                </div>
            </div>
        </div> 
        </div>
    <!-- END main-content wrapper for sticky footer -->

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 University Lost & Found. All Rights Reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Use</a></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
</body>
</html>