const fs = require('fs');
const https = require('https');
const path = require('path');

const themeDir = 'wp-content/themes/tatkhalsa';
const files = fs.readdirSync(themeDir).filter(f => f.endsWith('.php'));

let textsToTranslate = new Set();
let textsArray = [];

files.forEach(f => {
  const content = fs.readFileSync(path.join(themeDir, f), 'utf8');
  const matches = content.match(/>([^<]+)</g);
  if (matches) {
    matches.forEach(m => {
      let t = m.replace(/[><]/g, '').trim();
      if (t.length > 2 && !t.includes('?php') && !t.includes('-->') && !t.includes('{') && !t.includes('}')) {
          let clean = t.replace(/\s+/g, ' ');
          if (!textsToTranslate.has(clean)) {
              textsToTranslate.add(clean);
              textsArray.push(clean);
          }
      }
    });
  }
});

let dictionary = {};

async function translateBatch(batch) {
    const textStr = batch.join(' ||| ');
    return new Promise((resolve) => {
        const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=pa&dt=t&q=${encodeURIComponent(textStr)}`;
        https.get(url, (res) => {
            let data = '';
            res.on('data', chunk => data += chunk);
            res.on('end', () => {
                let translatedStr = '';
                try {
                    const json = JSON.parse(data);
                    if (json && json[0]) {
                        json[0].forEach(item => {
                            if (item[0]) translatedStr += item[0];
                        });
                    }
                    const translatedItems = translatedStr.split(' ||| ');
                    batch.forEach((text, i) => {
                         dictionary[text] = translatedItems[i] ? translatedItems[i].trim() : text;
                    });
                } catch (e) {
                    batch.forEach(text => { dictionary[text] = text; });
                }
                resolve();
            });
        }).on('error', () => {
            batch.forEach(text => { dictionary[text] = text; });
            resolve();
        });
    });
}

async function buildDictionary() {
    console.log(`Translating ${textsArray.length} strings...`);
    const batchSize = 15;
    for (let i = 0; i < textsArray.length; i += batchSize) {
        const batch = textsArray.slice(i, i + batchSize);
        await translateBatch(batch);
        console.log(`Processed ${i + batch.length} / ${textsArray.length}`);
    }
    const jsContent = `const masterPunjabiDictionary = ${JSON.stringify(dictionary, null, 2)};\n`;
    fs.writeFileSync(path.join(themeDir, 'lang-dict.js'), jsContent);
    console.log('Dictionary generated at ' + path.join(themeDir, 'lang-dict.js'));
}

buildDictionary();
