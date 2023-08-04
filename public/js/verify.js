// Replace these with your Twilio credentials
const accountSid = 'ACc092492efda9db10cd0387ba6e4bc29c';
const authToken = 'c95644ac62d1f456f0d8ede99f96106b';
const twilioPhoneNumber = '+919633175758';

// Function to send OTP
function sendOTP() {
  const phoneNumber = document.getElementById('phoneNumber').value;
  $.ajax({
    url: 'https://api.twilio.com/2010-04-01/Accounts/' + accountSid + '/Messages.json',
    type: 'POST',
    data: {
      To: phoneNumber,
      From: twilioPhoneNumber,
      Body: 'Your OTP is: ' + generateOTP(),
    },
    headers: {
      'Authorization': 'Basic ' + btoa(accountSid + ':' + authToken),
    },
    success: function (data) {
      console.log('OTP sent successfully');
      // Handle success (e.g., show a message to the user)
    },
    error: function (error) {
      console.error('Error sending OTP:', error);
      // Handle error (e.g., show an error message to the user)
    },
  });
}

// Function to generate a 6-digit OTP
function generateOTP() {
  return Math.floor(100000 + Math.random() * 900000);
}

// Function to verify OTP
function verifyOTP() {
  const enteredOTP = document.getElementById('otp').value;
  // Compare enteredOTP with the OTP sent to the user and handle success/failure accordingly
  // You can implement your own logic here based on how you store and verify the OTP
}
