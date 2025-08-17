 <?php
include 'config.php';
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="reports.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Item Name', 'Description', 'Date/Time', 'Location', 'Email', 'Status', 'Category']);

$sql = "SELECT * FROM lost_items ORDER BY date_time DESC";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [$row['id'], $row['item_name'], $row['description'], $row['date_time'], $row['location'], $row['email'], $row['status'], $row['category']]);
}
fclose($output);
exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Reports - University Lost & Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet"> <!-- Updated path -->
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">Exporting Reports...</h2>
    </div>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 University Lost & Found. All Rights Reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Use</a></p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>