const http = require('http');

const options = {
    hostname: 'localhost',
    port: 3000,
    path: '/wp-admin/admin-ajax.php?action=sync_all_brevo_contacts',
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
    }
};

const req = http.request(options, (res) => {
    let data = '';
    res.on('data', chunk => data += chunk);
    res.on('end', () => {
        console.log("Response:", data.substring(0, 500));
    });
});
req.write('action=sync_all_brevo_contacts');
req.end();
