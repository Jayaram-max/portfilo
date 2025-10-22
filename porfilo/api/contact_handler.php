<?php
/**
 * Contact Form Handler
 * Portfolio Website - Jayaram
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Include database configuration
require_once '../config/database.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// If JSON input is not available, try form data
if (!$input) {
    $input = $_POST;
}

// Validate required fields
$required_fields = ['name', 'email', 'subject', 'message'];
$errors = [];

foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        $errors[] = ucfirst($field) . ' is required';
    }
}

// Validate email format
if (!empty($input['email']) && !DatabaseUtils::validateEmail($input['email'])) {
    $errors[] = 'Please enter a valid email address';
}

// Check for errors
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $errors
    ]);
    exit;
}

try {
    // Sanitize input data
    $name = DatabaseUtils::sanitize($input['name']);
    $email = DatabaseUtils::sanitize($input['email']);
    $subject = DatabaseUtils::sanitize($input['subject']);
    $message = DatabaseUtils::sanitize($input['message']);

    // Get database connection
    $db = new Database();
    $conn = $db->getConnection();

    // Insert message into database
    $stmt = $conn->prepare("
        INSERT INTO messages (name, email, subject, message, created_at) 
        VALUES (?, ?, ?, ?, NOW())
    ");

    $result = $stmt->execute([$name, $email, $subject, $message]);

    if ($result) {
        // Log visitor
        DatabaseUtils::logVisitor('contact');
        
        // Send email notification (optional)
        sendEmailNotification($name, $email, $subject, $message);
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Thank you for your message! I will get back to you soon.'
        ]);
    } else {
        throw new Exception('Failed to save message');
    }

} catch (Exception $e) {
    error_log("Contact form error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Sorry, there was an error sending your message. Please try again later.'
    ]);
}

/**
 * Send email notification to admin
 */
function sendEmailNotification($name, $email, $subject, $message) {
    try {
        $admin_email = 'jayaram.be@email.com'; // Change this to your email
        $email_subject = "New Contact Form Message from $name";
        
        $email_body = "
        <h2>New Contact Form Message</h2>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Subject:</strong> $subject</p>
        <p><strong>Message:</strong></p>
        <p>$message</p>
        <hr>
        <p><em>This message was sent from your portfolio contact form.</em></p>
        ";
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Portfolio Contact Form <noreply@jayaram.com>" . "\r\n";
        $headers .= "Reply-To: $email" . "\r\n";
        
        mail($admin_email, $email_subject, $email_body, $headers);
        
    } catch (Exception $e) {
        error_log("Email notification error: " . $e->getMessage());
    }
}
?>
