 <?php
include 'config.php';
session_destroy();
header("Location: index.php");
exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - University Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Logging Out...</h2>
    </div>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 University Lost & Found. All Rights Reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Use</a></p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>