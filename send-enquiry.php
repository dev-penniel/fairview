<?php

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.'
    ]);

    exit;
}

// Your email address
$to = 'info@fairview.co.ls';

// Get and sanitize form data
$name = trim($_POST['name'] ?? '');
$organization = trim($_POST['organization'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$service = trim($_POST['service'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validate required fields
if ($name === '' || $email === '' || $service === '' || $message === '') {

    echo json_encode([
        'success' => false,
        'message' => 'Please complete all required fields.'
    ]);

    exit;
}

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo json_encode([
        'success' => false,
        'message' => 'Please enter a valid email address.'
    ]);

    exit;
}

// Prevent header injection
$name = str_replace(["\r", "\n"], '', $name);
$email = str_replace(["\r", "\n"], '', $email);
$organization = str_replace(["\r", "\n"], '', $organization);
$phone = str_replace(["\r", "\n"], '', $phone);
$service = str_replace(["\r", "\n"], '', $service);

// Email subject
$subject = 'New Consultation Enquiry - Fairview Solutions';

// Email body
$emailBody = "A new consultation enquiry has been submitted.\n\n";

$emailBody .= "Name: " . $name . "\n";

$emailBody .= "Organization: " .
    ($organization ?: 'Not provided') . "\n";

$emailBody .= "Email: " . $email . "\n";

$emailBody .= "Phone: " .
    ($phone ?: 'Not provided') . "\n";

$emailBody .= "Service: " . $service . "\n\n";

$emailBody .= "Message:\n";

$emailBody .= $message . "\n\n";

$emailBody .= "----------------------------------------\n";

$emailBody .= "Sent from the Fairview Solutions website.\n";

// Email headers
$headers = [];

$headers[] = 'From: Fairview Website <info@fairview.co.ls>';

$headers[] = 'Reply-To: ' . $email;

$headers[] = 'MIME-Version: 1.0';

$headers[] = 'Content-Type: text/plain; charset=UTF-8';

// Send email
$sent = mail(
    $to,
    $subject,
    $emailBody,
    implode("\r\n", $headers)
);

// Return response to AJAX
if ($sent) {

    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your enquiry has been sent successfully. We will get back to you shortly.'
    ]);

    exit;
}

// Failed
echo json_encode([
    'success' => false,
    'message' => 'We could not send your enquiry. Please try again or contact us directly.'
]);

exit;