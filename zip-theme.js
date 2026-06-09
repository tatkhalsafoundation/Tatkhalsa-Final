import AdmZip from 'adm-zip';
import path from 'path';

try {
  const zip = new AdmZip();
  const themeDir = path.join(process.cwd(), 'wp-content', 'themes', 'tatkhalsa-theme');
  const outputPath = path.join(process.cwd(), 'tatkhalsa-theme.zip');

  // Add the local folder files at the root of the zip file so WP handles it natively.
  zip.addLocalFolder(themeDir);
  
  // Write the zip file
  zip.writeZip(outputPath);
  
  console.log('------------------------------------------------------');
  console.log('SUCCESS: WordPress Theme packaged successfully!');
  console.log('File created at the workspace root: tatkhalsa-theme.zip');
  console.log('------------------------------------------------------');
} catch (error) {
  console.error('An error occurred while zipping the theme:', error);
  process.exit(1);
}
