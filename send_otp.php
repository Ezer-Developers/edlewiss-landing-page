<?php
// send_otp.php

// Replace these credentials with your actual Twilio account credentials
$twilioAccountSid = 'AC866185e617be8e69050cc615e3c420a4';
$twilioAuthToken = '096ca7777c820cad3cc080cd8acb1e5f';
$twilioPhoneNumber = '+919633175758'; // This should be the Twilio phone number you purchased

require_once 'vendor/autoload.php';

use Twilio\Rest\Client;

// Generate a random 6-digit OTP
$otp = str_pad(mt_rand(100000, 999999), 6, '0', STR_PAD_LEFT);

// Save the OTP in the session (you can use a more secure storage mechanism)
session_start();
$_SESSION['otp'] = $otp;

// Send the OTP to the user's phone number using Twilio API
$mobileNumber = isset($_POST['MobileNumber']) ? $_POST['MobileNumber'] : null; // Fix parameter name

if (!$mobileNumber) {
    // If mobile number is not provided, respond with an error
    $response = array(
        'success' => false,
        'message' => 'Mobile number is required.',
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Your Twilio account credentials
$twilio = new Client($twilioAccountSid, $twilioAuthToken);

try {
    // Send the OTP message via Twilio
    $message = $twilio->messages->create(
        $mobileNumber, // The user's phone number
        array(
            'from' => $twilioPhoneNumber,
            'body' => "Your OTP is: $otp",
        )
    );

    // Respond with a success message
    $response = array(
        'success' => true,
        'message' => 'OTP sent successfully',
    );
    header('Content-Type: application/json');
    echo json_encode($response);
} catch (Exception $e) {
    // Handle Twilio API errors
    $response = array(
        'success' => false,
        'message' => 'Error sending OTP: ' . $e->getMessage(),
    );
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
