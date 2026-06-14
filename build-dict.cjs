const fs = require('fs');
const https = require('https');
const path = require('path');

const themeDir = 'wp-content/themes/tatkhalsa';
const files = fs.readdirSync(themeDir).filter(f => f.endsWith('.php'));

let textsToTranslate = new Set();

files.forEach(f => {
  const content = fs.readFileSync(path.join(themeDir, f), 'utf8');
  // Simple regex to extract text between > and <
  const matches = content.match(/>([^<]+)</g);
  if (matches) {
    matches.forEach(m => {
      let t = m.replace(/[><]/g, '').trim();
      // Filter out code, empty, or very short punctuation strings
      if (t.length > 1 && !t.includes('?php') && !t.includes('-->') && !t.includes('{') && !t.includes('}')) {
        textsToTranslate.add(t.replace(/\s+/g, ' '));
      }
    });
  }
});

const texts = Array.from(textsToTranslate);
let dictionary = {};
let completed = 0;

async function translateText(text) {
  return new Promise((resolve) => {
    const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=pa&dt=t&q=${encodeURIComponent(text)}`;
    https.get(url, (res) => {
      let data = '';
      res.on('data', chunk => data += chunk);
      res.on('end', () => {
        try {
          const json = JSON.parse(data);
          let translated = '';
          if (json && json[0]) {
            json[0].forEach(item => {
              if (item[0]) translated += item[0];
            });
          }
          resolve(translated || text);
        } catch (e) {
          resolve(text);
        }
      });
    }).on('error', () => resolve(text));
  });
}

async function buildDictionary() {
  console.log(`Translating ${texts.length} strings...`);
  // Process in batches
  const batchSize = 10;
  for (let i = 0; i < texts.length; i += batchSize) {
    const batch = texts.slice(i, i + batchSize);
    const results = await Promise.all(batch.map(translateText));
    batch.forEach((text, idx) => {
      dictionary[text] = results[idx];
    });
    completed += batch.length;
    console.log(`Progress: ${completed} / ${texts.length}`);
    // Small delay to avoid rate limiting
    await new Promise(r => setTimeout(r, 200));
  }
  
  const jsContent = `const masterPunjabiDictionary = ${JSON.stringify(dictionary, null, 2)};\n`;
  fs.writeFileSync(path.join(themeDir, 'lang-dict.js'), jsContent);
  console.log('Dictionary generated at ' + path.join(themeDir, 'lang-dict.js'));
}

buildDictionary();
