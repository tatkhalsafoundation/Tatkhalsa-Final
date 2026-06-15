const fetch = require('node-fetch'); // wait node-fetch doesn't come natively, let's use global fetch
async function test() {
  try {
    const res = await fetch('http://localhost:3000/api/admin/send-otp', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ contact: '+919876543210' })
    });
    console.log("Status:", res.status);
    const text = await res.text();
    console.log("Response Body:", text);
  } catch (e) {
    console.error("Fetch Exception:", e);
  }
}
test();
