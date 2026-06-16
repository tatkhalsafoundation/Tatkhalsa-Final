const fs = require("fs");
const path = require("path");

const themePath = path.join(__dirname, "wp-content", "themes", "tatkhalsa");

function replaceInDir(dir) {
  const files = fs.readdirSync(dir);
  for (const file of files) {
    const fullPath = path.join(dir, file);
    if (fs.statSync(fullPath).isDirectory()) {
      replaceInDir(fullPath);
    } else if (fullPath.endsWith(".php") || fullPath.endsWith(".css") || fullPath.endsWith(".js")) {
      let content = fs.readFileSync(fullPath, "utf-8");
      
      const newContent = content.replace(/\/blood-donors\//g, "/blood-on-can/")
                                .replace(/\/blood-donors/g, "/blood-on-can");
      
      if (content !== newContent) {
        fs.writeFileSync(fullPath, newContent);
        console.log("Updated", fullPath);
      }
    }
  }
}

replaceInDir(themePath);
