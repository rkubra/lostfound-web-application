<?php
include 'config.php';
if (!isset($_SESSION['username'])) {
    // Redirect to login or show error
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type = $_POST['type'];
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $date_time = $_POST['date_time'];
    $location = $_POST['location'];
    $category = $_POST['category'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validate image
    if (isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != "") {
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check === false) {
            $uploadOk = 0;
        }
        if ($_FILES['image']['size'] > 2000000) {
            $uploadOk = 0;
        }
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            $uploadOk = 0;
        }
    } else {
        $uploadOk = 0;
    }

    if ($uploadOk && move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        $sql = "INSERT INTO lost_items (type, item_name, description, date_time, location, image, email, category, contact_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $type, $item_name, $description, $date_time, $location, $image, $email, $category, $contact_number);
        if ($stmt->execute()) {
            sendEmail('kabralkabral022@gmail.com', "New Report", "New item reported: $item_name by $email, Contact: $contact_number");
            echo "<div class='alert alert-success'>Report submitted for approval!</div>";
        } else {
            echo "<div class='alert alert-danger'>Database error. Please try again.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Image upload failed. Max 2MB, JPG/JPEG/PNG/GIF only.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Lost & Found - Report Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Report Lost/Found Item</h2>
        <div class="card p-4">
            <form method="post" enctype="multipart/form-data">
                <label for="type">Type</label>
                  <select name="type" id="type" class="form-control" required>
                   <option value="lost">Lost</option>
                     <option value="found">Found</option>
                </select>
                <div class="mb-3">
                    <input type="text" class="form-control" name="item_name" placeholder="Item Name" required>
                </div>
                <div class="mb-3">
                    <textarea class="form-control" name="description" placeholder="Description" required></textarea>
                </div>
                <div class="mb-3">
                    <input type="datetime-local" class="form-control" name="date_time" required>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" name="location" placeholder="Location" required>
                </div>
                <div class="mb-3">
                    <select class="form-control" name="category" required>
                        <option value="Electronics">Electronics</option>
                        <option value="Books">Books</option>
                        <option value="Clothing">Clothing</option>
                        <option value="Accessories">Accessories</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <input type="tel" class="form-control" name="contact_number" placeholder="Contact Number (e.g., +1234567890)" pattern="[0-9+]{10,15}" title="Enter a valid phone number (10-15 digits with optional +)" required>
                </div>
               
                <div class="mb-3">
                    <input type="file" class="form-control" name="image" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit Report</button>
            </form>
            <p class="mt-3 text-center"><a href="index.php">Back</a></p>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 University Lost & Found. All Rights Reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Use</a></p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>