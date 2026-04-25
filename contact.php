<?php
header('Content-Type: application/json');

// Honeypot: bots fill hidden fields, real users don't
if (!empty($_POST['website'])) {
    echo json_encode(['success' => true]);
    exit;
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Please enter a valid email address.']);
    exit;
}

$to      = 'brookseastman@gmail.com';
$subject = 'Portfolio Contact: ' . mb_substr($name, 0, 80);
$body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";

$domain  = $_SERVER['HTTP_HOST'] ?? 'localhost';
$headers = implode("\r\n", [
    'From: noreply@' . $domain,
    'Reply-To: ' . $email,
    'Content-Type: text/plain; charset=UTF-8',
    'X-Mailer: PHP/' . phpversion(),
]);

if (mail($to, $subject, $body, $headers)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Message could not be sent. Please email me directly.']);
}
