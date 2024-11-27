<?php
include('config/db.php');
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['learner_num'])) {
    $learner_num = mysqli_real_escape_string($conn, $_POST['learner_num']);
    
    try {
        // Start transaction
        mysqli_begin_transaction($conn);

        // Obtain parent email before deleting
        $obtain_parent_email = "SELECT email FROM parent AS p 
                                JOIN learner AS l ON p.parent_num = l.parent_num 
                                WHERE l.learner_num = '$learner_num'";
        $result = mysqli_query($conn, $obtain_parent_email);
        if (!$result) {
            throw new Exception("Error fetching parent email: " . mysqli_error($conn));
        }

        // Check if an email was found
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $email = $row['email'];
        } else {
            throw new Exception("No email found for the given parent number.");
        }

        // First DELETE query
        $delete_query_learner = "DELETE FROM learner WHERE learner_num = '$learner_num'";
        if (!mysqli_query($conn, $delete_query_learner)) {
            throw new Exception("Error deleting from learner table: " . mysqli_error($conn));
        }

        // Second DELETE query (if needed, for another table)
        $delete_query_approval = "DELETE FROM approved_list WHERE learner_num = '$learner_num'";
        if (!mysqli_query($conn, $delete_query_approval)) {
            throw new Exception("Error deleting from approval table: " . mysqli_error($conn));
        }

        // Commit transaction if all operations are successful
        mysqli_commit($conn);
// Function to send an email
function sendMail($email, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = MAILHOST;
        $mail->Username = USERNAME;
        $mail->Password = PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom(SEND_FROM, SEND_FROM_NAME);
        $mail->addAddress($email);
        $mail->addReplyTo(REPLY_TO, REPLY_TO_NAME);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);
        return $mail->send();
    } catch (Exception $e) {
        throw new Exception("Mail could not be sent. Error: {$mail->ErrorInfo}");
    }
}
// Send the email
if (sendMail($email, 'Deletion Confirmation', 'Your student has been successfully removed from the bus.')) {
    header("Location: intermediate_remove.php?message=" . urlencode('An email confirmation has been sent to ' . $email) . "&num=" . urlencode($parent_num). '&name=' . urlencode($parent_name));
    exit();
} else {
    echo "Error sending email.";
}

} catch (Exception $e) {
// Rollback transaction in case of error
mysqli_rollback($conn);
echo "Error: " . $e->getMessage();
}
}

?>