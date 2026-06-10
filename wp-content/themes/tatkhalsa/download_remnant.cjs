const fs = require('fs');
const path = require('path');
const https = require('https');

const dir = 'wp-content/themes/tatkhalsa';
const mediaDir = path.join(dir, 'assets', 'images');
const filesToProcess = ['footer.php', 'index.php', 'template-blog.php', 'template-projects.php', 'template-about.php', 'style.css'];

if (!fs.existsSync(mediaDir)) {
  fs.mkdirSync(mediaDir, { recursive: true });
}

const regex = /https:\/\/(secure\.gravatar\.com|api\.qrserver\.com\/v1\/create-qr-code\/\?size=140x140)[^\"\'\s\)]+/g;

const urls = new Set();
filesToProcess.forEach(f => {
  const p = path.join(dir, f);
  if (fs.existsSync(p)) {
    const content = fs.readFileSync(p, 'utf8');
    let match;
    while ((match = regex.exec(content)) !== null) {
      // Exclude template string vars
      if (!match[0].includes('${')) {
        urls.add(match[0]);
      }
    }
  }
});

const urlsArray = Array.from(urls);
const urlMap = {};

function download(url, dest) {
  return new Promise((resolve, reject) => {
    const file = fs.createWriteStream(dest);
    https.get(url, (response) => {
      // follow redirects
      if (response.statusCode === 301 || response.statusCode === 302 || response.statusCode === 303) {
        let location = response.headers.location;
        if (!location.startsWith('http')) {
           location = new URL(location, url).href;
        }
        https.get(location, (res) => {
          res.pipe(file);
          file.on('finish', () => { file.close(resolve); });
        }).on('error', (err) => { fs.unlink(dest, () => {}); reject(err); });
      } else {
        response.pipe(file);
        file.on('finish', () => { file.close(resolve); });
      }
    }).on('error', (err) => {
      fs.unlink(dest, () => {});
      reject(err);
    });
  });
}

function processFiles() {
  filesToProcess.forEach(f => {
    const p = path.join(dir, f);
    if (!fs.existsSync(p)) return;
    let content = fs.readFileSync(p, 'utf8');
    for (const [url, mapInfo] of Object.entries(urlMap)) {
      const escapedUrl = url.replace(/&amp;/g, '&'); // Sometimes URLs in HTML have &amp;
      const localPathPhp = "<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/" + mapInfo.filename;
      
      content = content.split(url).join(localPathPhp);
      content = content.split(escapedUrl).join(localPathPhp);
    }
    fs.writeFileSync(p, content);
    console.log("Updated", f);
  });
}

async function run() {
  for (let i = 0; i < urlsArray.length; i++) {
    const url = urlsArray[i];
    let ext = '.png';
    if (url.includes('gravatar')) ext = '.jpg';
    
    // Convert HTML entity if present
    const actualUrl = url.replace(/&amp;/g, '&');
    
    const filename = `media_${Date.now()}_qr_gravatar_${i}${ext}`;
    const dest = path.join(mediaDir, filename);
    console.log(`Downloading ${i+1}/${urlsArray.length}: ${actualUrl}`);
    try {
      await download(actualUrl, dest);
      urlMap[url] = { filename };
    } catch (e) {
      console.error(`Failed to download ${url}: ${e.message}`);
    }
  }
  processFiles();
}

run();
