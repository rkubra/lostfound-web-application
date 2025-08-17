 <?php
session_start();
require 'vendor/autoload.php'; // For PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lostfound";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'insitute23@gmail.com';
        $mail->Password = 'imhbbeimmrwrtywy';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('insitute23@gmail.com', 'Lost & Found');
        $mail->addAddress($to);
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>