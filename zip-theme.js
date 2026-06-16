import AdmZip from 'adm-zip';
import path from 'path';
import fs from 'fs';

try {
  const zip = new AdmZip();
  const outputPath = path.join(process.cwd(), 'tatkhalsa.zip');
  const destFolder = 'tatkhalsa';

  const themeDir = path.join(process.cwd(), 'wp-content', 'themes', 'tatkhalsa');

  // Add the entire local theme folder recursively
  zip.addLocalFolder(themeDir, destFolder);
  
  // Write the zip file
  zip.writeZip(outputPath);
  
  console.log('------------------------------------------------------');
  console.log('SUCCESS: WordPress Theme packaged successfully!');
  console.log('File created at the workspace root: tatkhalsa.zip');
  console.log('------------------------------------------------------');
} catch (error) {
  console.error('An error occurred while zipping the theme:', error);
  process.exit(1);
}
