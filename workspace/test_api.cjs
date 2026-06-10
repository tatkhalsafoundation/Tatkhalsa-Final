const https = require('https');

https.get('https://api.gurbaninow.com/v2/search/tu%20prabh/?searchtype=1', (res) => {
  let data = '';
  res.on('data', chunk => data += chunk);
  res.on('end', () => console.log(data));
}).on('error', err => console.log(err.message));
