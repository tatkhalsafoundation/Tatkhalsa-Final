import AdmZip from 'adm-zip';
import path from 'path';
import fs from 'fs';

try {
  const themeDir = path.join(process.cwd(), 'wp-content', 'themes', 'tatkhalsa');

  // Ensure both lowercase and uppercase variations exist in the theme folder for UNIX compatibility
  const srcPng = path.join(themeDir, 'Logo.png');
  const destPng = path.join(themeDir, 'logo.png');
  if (fs.existsSync(srcPng)) {
    fs.copyFileSync(srcPng, destPng);
  }
  const srcJpg = path.join(themeDir, 'Logo.jpg');
  const destJpg = path.join(themeDir, 'logo.jpg');
  if (fs.existsSync(srcJpg)) {
    fs.copyFileSync(srcJpg, destJpg);
  }

  const zip = new AdmZip();
  const outputPath = path.join(process.cwd(), 'tatkhalsa.zip');
  const destFolder = 'tatkhalsa';

  // Add the root level WordPress theme files directly (with both uppercase and lowercase logos)
  const filesToZip = [
    'style.css',
    'Logo.png',
    'logo.png',
    'Logo.jpg',
    'logo.jpg',
    'functions.php',
    'header.php',
    'footer.php',
    'index.php',
    'template-about.php',
    'template-projects.php',
    'template-punjab-flood-relief.php',
    'template-volunteer.php'
  ];

  filesToZip.forEach(file => {
    let filePath = path.join(themeDir, file);
    
    // Fallback if Logo.png is not present but Logo.jpg exists
    if (!fs.existsSync(filePath)) {
      if (file === 'Logo.png') {
        const jpgPath = path.join(themeDir, 'Logo.jpg');
        if (fs.existsSync(jpgPath)) {
          filePath = jpgPath;
        } else {
          console.warn(`WARNING: File not found at ${filePath}`);
          return;
        }
      } else {
        console.warn(`WARNING: File not found at ${filePath}`);
        return;
      }
    }
    
    zip.addLocalFile(filePath, destFolder);
  });
  
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
