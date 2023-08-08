<?php
// form_handler.php

// Process the form submission and send the data to the API
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = $_POST; // Assuming the form data is sent via POST

    // Convert the form data to JSON
    $jsonFormData = json_encode($formData);

    // Set the API URL
    $apiUrl = 'https://sams.edelweisstokio.in/Wrapper/api/lead/WebAggregateSaveLead';

    // Create a cURL session to make the API call
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonFormData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

    // Execute the cURL session and get the API response
    $apiResponse = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close the cURL session
    curl_close($ch);

    // Check if the API call was successful
    if ($httpCode === 200) {
        // API call successful, proceed to redirect to the "Thank You" page
        header('Location: thankyou.html');
        exit;
    } else {
        // API call failed or returned an error
        // You can handle the error here or show an error message to the user
        // For example:
        echo 'An error occurred while processing your request. Please try again later.';
    }
}
?>