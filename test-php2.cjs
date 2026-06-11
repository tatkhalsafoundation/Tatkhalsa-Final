const fs = require('fs');
const engine = require('php-parser');
const parser = new engine({
  parser: {
    extractDoc: true,
    php7: true,
  },
  ast: {
    withPositions: true,
  },
});
const phpCode = fs.readFileSync('wp-content/themes/tatkhalsa/template-blood-donors.php', 'utf8');
try {
  parser.parseCode(phpCode);
  console.log("PHP is valid.");
} catch(e) {
  console.error(e);
}
