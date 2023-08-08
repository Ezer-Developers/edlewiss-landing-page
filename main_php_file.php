<?php
// main_php_file.php

// Retrieve the submitted OTP and other form data
$submittedOTP = isset($_POST['txtOTP']) ? $_POST['txtOTP'] : null;
$mobileNumber = isset($_POST['MobileNumber']) ? $_POST['MobileNumber'] : null;

// Other form data...
$firstName = isset($_POST['F_Name']) ? $_POST['F_Name'] : null;
$lastName = isset($_POST['L_Name']) ? $_POST['L_Name'] : null;
$email = isset($_POST['EmailID']) ? $_POST['EmailID'] : null;
$city = isset($_POST['City']) ? $_POST['City'] : null;
// End of other form data...

// Retrieve the saved OTP from the session
session_start();
$savedOTP = isset($_SESSION['otp']) ? $_SESSION['otp'] : null;

// Check if the submitted OTP matches the saved OTP
if ($submittedOTP === $savedOTP) {
    // OTP validation successful, proceed with submitting form data to the API

    // Prepare the data to be sent to the API endpoint
    $postData = array(
        "AffiliateCode" => "A001",
        "F_Name" => $firstName,
        "L_Name" => $lastName,
        "MobileNumber" => $mobileNumber,
        "EmailID" => $email,
        "City" => $city,
        "State" => "18", // Assuming you want to hardcode the State as "18"
        "SubCampaignCode" => "C001",
        "CampaignCode" => "CM01",
    );

    // Convert the data to JSON format
    $jsonData = json_encode($postData);

    // Make a cURL request to the API endpoint
    $ch = curl_init("https://sams.edelweisstokio.in/Wrapper/api/lead/WebAggregateSaveLead");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ));

    // Execute the cURL request
    $result = curl_exec($ch);

    // Check for cURL errors and handle the response
    if (curl_errno($ch)) {
        // Handle cURL error
        $response = array(
            'success' => false,
            'message' => 'Error making API request: ' . curl_error($ch),
        );
    } else {
        // Decode the API response
        $response = json_decode($result, true);

        // Handle the API response as per your requirements
        if ($response['Code'] === 200 || ($response['Code'] === 400 && strpos($response['Message'], 'Lead Already Exists') !== false)) {
            // API request successful or duplicate lead, redirect to the "Thank You" page
            header('Location: thankyou.html');
            exit;
        } else {
            // Handle other error scenarios
            $response = array(
                'success' => false,
                'message' => 'An error occurred. Please try again later.',
            );
        }
    }

    // Close the cURL session
    curl_close($ch);

    // Clear the saved OTP from the session after successful validation
    unset($_SESSION['otp']);
} else {
    // Invalid OTP
    $response = array(
        'success' => false,
        'message' => 'Invalid OTP',
    );
}

// Respond with the final result as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
