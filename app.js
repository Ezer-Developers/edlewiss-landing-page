const express = require('express');
const app = express();
const path = require('path');
const bodyParser = require('body-parser'); // This module is required to parse form data

// Set up the body-parser middleware to parse incoming form data
app.use(bodyParser.urlencoded({ extended: false }));

// Set the folder for serving static files (e.g., your HTML templates)
app.use(express.static(path.join(__dirname, 'public')));

// Replace the Twilio code with the code below

// Function to generate a random 4-digit OTP
function generateOTP() {
  return Math.floor(1000 + Math.random() * 9000).toString();
}

// Route to send OTP via Twilio
app.post('/send-otp', (req, res) => {
  const twilio = require('twilio');
  const accountSid = 'ACc092492efda9db10cd0387ba6e4bc29c';
  const authToken = 'c95644ac62d1f456f0d8ede99f96106b';
  const client = new twilio(accountSid, authToken);

  const otp = generateOTP();
  const recipientNumber = '+918921410487'; // Replace with the recipient's phone number (to whom you want to send OTP)

  client.messages
    .create({
      from: '+12179200182', // Replace with your Twilio phone number
      to: recipientNumber,
      body: `Your OTP for verification is: ${otp}`,
    })
    .then((message) => {
      console.log(`Message SID: ${message.sid}, OTP: ${otp}`);
      res.json({ success: true, message: 'OTP sent successfully.' });
    })
    .catch((error) => {
      console.error(error);
      res.status(500).json({ success: false, message: 'Failed to send OTP.' });
    });
});

// Start the server
const port = 3000; // You can choose any available port
app.listen(port, () => {
  console.log(`Server is running on http://localhost:${port}`);
});
