const https = require('https');
https.get('https://www.tatkhalsa.in/', (resp) => {
  let data = '';
  resp.on('data', (chunk) => { data += chunk; });
  resp.on('end', () => {
    console.log(data.substring(0, 1500));
  });
}).on("error", (err) => {
  console.log("Error: " + err.message);
});
