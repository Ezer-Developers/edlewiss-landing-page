const express = require('express');
const app = express();
const path = require('path');
const bodyParser = require('body-parser'); 

app.use(bodyParser.urlencoded({ extended: false }));

app.use(express.static(path.join(__dirname, 'public')));

function generateOTP() {
    return Math.floor(1000 + Math.random() * 9000).toString();
}

app.post('/send-otp', (req, res) => {
    const twilio = require('twilio');
    const accountSid = 'ACc092492efda9db10cd0387ba6e4bc29c';
    const authToken = 'c95644ac62d1f456f0d8ede99f96106b';
    const client = new twilio(accountSid, authToken);

    const otp = generateOTP();
    const recipientNumber = req.body.recipientNumber; 
    client.messages
        .create({
            from: '+12179200182', 
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

const port = 443; 
app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});
