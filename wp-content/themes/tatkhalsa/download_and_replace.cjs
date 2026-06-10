const fs = require('fs');
const path = require('path');
const https = require('https');

const dir = 'wp-content/themes/tatkhalsa';
const mediaDir = path.join(dir, 'assets', 'images');
const filesToProcess = ['footer.php', 'index.php', 'template-blog.php', 'template-projects.php', 'template-about.php', 'style.css'];

if (!fs.existsSync(mediaDir)) {
  fs.mkdirSync(mediaDir, { recursive: true });
}

const urls = JSON.parse(fs.readFileSync('urls.json', 'utf8'));

let doneCount = 0;
const urlMap = {};

function download(url, dest) {
  return new Promise((resolve, reject) => {
    const file = fs.createWriteStream(dest);
    https.get(url, (response) => {
      // follow redirects
      if (response.statusCode === 301 || response.statusCode === 302) {
        https.get(response.headers.location, (res) => {
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
      const localPathPhp = "<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/" + mapInfo.filename;
      const localPathCss = "./assets/images/" + mapInfo.filename;

      if (f.endsWith('.css')) {
        content = content.split(url).join(localPathCss);
      } else {
        content = content.split(url).join(localPathPhp);
      }
    }
    fs.writeFileSync(p, content);
    console.log("Updated", f);
  });
}

async function run() {
  for (let i = 0; i < urls.length; i++) {
    const url = urls[i];
    let ext = '.jpg';
    if (url.includes('.webm')) ext = '.webm';
    if (url.includes('.mp4')) ext = '.mp4';
    if (url.includes('.png')) ext = '.png';
    const filename = `media_${Date.now()}_${i}${ext}`;
    const dest = path.join(mediaDir, filename);
    console.log(`Downloading ${i+1}/${urls.length}: ${url}`);
    try {
      await download(url, dest);
      urlMap[url] = { filename };
    } catch (e) {
      console.error(`Failed to download ${url}: ${e.message}`);
    }
  }
  processFiles();
}

run();
