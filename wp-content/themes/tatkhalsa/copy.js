import fs from 'fs';
import path from 'path';

function findAndCopy() {
    try {
        const dirs = fs.readdirSync('src/assets/images');
        for (const dir of dirs) {
            const fullPath = path.join('src/assets/images', dir);
            if (fs.statSync(fullPath).isDirectory()) {
                const files = fs.readdirSync(fullPath);
                for (const file of files) {
                    if (file === 'regenerated_image_1781128512768.jpg') {
                        const targetPath = 'wp-content/themes/tatkhalsa/assets/images/' + file;
                        fs.copyFileSync(path.join(fullPath, file), targetPath);
                        console.log('Copied to ' + targetPath);
                    }
                }
            }
        }
    } catch (e) {
        console.error(e);
    }
}
findAndCopy();
