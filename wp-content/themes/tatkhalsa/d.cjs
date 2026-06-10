const https = require('https');
const fs = require('fs');
const dest = 'wp-content/themes/tatkhalsa/assets/images/qr_250_main.png';
const url = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=upi%3A%2F%2Fpay%3Fpa%3Dmab.037215043540097%40axisbank%26pn%3DTatkhalsa%2520Foundation%26cu%3DINR';
const file = fs.createWriteStream(dest);
https.get(url, (response) => {
  response.pipe(file);
});
