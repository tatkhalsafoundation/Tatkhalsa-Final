const fs = require('fs');
function findFiles(dir, match) {
  const files = fs.readdirSync(dir);
  for (const file of files) {
    const fullPath = dir + '/' + file;
    if (fs.statSync(fullPath).isDirectory()) {
      findFiles(fullPath, match);
    } else if (fullPath.includes(match)) {
      console.log(fullPath);
    }
  }
}
findFiles('/src', '1781128512768');
