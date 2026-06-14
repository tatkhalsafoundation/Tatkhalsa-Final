const fs = require('fs');
const files = fs.readdirSync('wp-content/themes/tatkhalsa').filter(f => f.endsWith('.php'));
let texts = [];
files.forEach(f => {
  const content = fs.readFileSync('wp-content/themes/tatkhalsa/' + f, 'utf8');
  const matches = content.match(/>([^<]+)</g);
  if(matches) {
    texts.push(...matches.map(m => m.replace(/[><]/g, '').trim()).filter(t => t.length > 2 && !t.includes('?php') && !t.includes('-->') && !t.includes('{') && !t.includes('}')));
  }
});
fs.writeFileSync('texts.txt', Array.from(new Set(texts)).join('\n'));
console.log("Done");
