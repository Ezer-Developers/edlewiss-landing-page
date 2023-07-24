const express = require('express');
const axios = require('axios');

const app = express();
const port = 3000; // Change this to any available port number

app.use(express.json());

// Set up CORS headers to allow cross-origin requests
app.use((req, res, next) => {
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');
  next();
});

app.post('/api-proxy', async (req, res) => {
  try {
    const apiUrl = 'https://sams.edelweisstokio.in/Wrapper/api/lead/WebAggregateSaveLead';
    const response = await axios.post(apiUrl, req.body);
    res.json(response.data);
  } catch (error) {
    res.status(error.response?.status || 500).json({
      error: 'Error occurred while making the API request.',
    });
  }
});

app.listen(port, () => {
  console.log(`Proxy server is running on port ${port}`);
});
