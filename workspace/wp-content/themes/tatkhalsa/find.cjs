const fs = require('fs');
function findFiles(dir, match) {
  try {
    const files = fs.readdirSync(dir);
    for (const file of files) {
      if (file === 'node_modules' || file === '.git') continue;
      const fullPath = dir + '/' + file;
      try {
        if (fs.statSync(fullPath).isDirectory()) {
          findFiles(fullPath, match);
        } else if (fullPath.includes(match)) {
          console.log(fullPath);
        }
      } catch (e) {}
    }
  } catch (e) {}
}
findFiles('/workspace', '1781128512768');
findFiles('/', '1781128512768'); // Just in case
