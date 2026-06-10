const fs = require('fs');
const path = require('path');
const https = require('https');

const dir = 'wp-content/themes/tatkhalsa';
const files = ['footer.php', 'index.php', 'template-blog.php', 'template-projects.php', 'template-about.php', 'style.css'];

const regex = /https:\/\/(images\.unsplash\.com|upload\.wikimedia\.org)[^\"\'\s\)]+/g;

const urls = new Set();
files.forEach(f => {
  const p = path.join(dir, f);
  if (fs.existsSync(p)) {
    const content = fs.readFileSync(p, 'utf8');
    let match;
    while ((match = regex.exec(content)) !== null) {
      urls.add(match[0]);
    }
  }
});

fs.writeFileSync('urls.json', JSON.stringify(Array.from(urls), null, 2));
console.log('Found ' + urls.size + ' unique URLs');
