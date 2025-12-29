<?php

// PHPMailer SMTP configuration
return [
    'host' => 'smtp.example.com', // Your SMTP server
    'port' => 587,                 // SMTP port (587 for TLS, 465 for SSL)
    'username' => 'your_email@example.com', // Your SMTP username
    'password' => 'your_smtp_password',    // Your SMTP password
    'from_email' => 'no-reply@jshop.vn',
    'from_name' => 'JSHOP',
    'smtp_auth' => true,           // Enable SMTP authentication
    'smtp_secure' => 'tls',        // Enable encryption (tls or ssl)
];