import AdmZip from 'adm-zip';
import path from 'path';

try {
  const zip = new AdmZip();
  const outputPath = path.join(process.cwd(), 'tatkhalsa-theme.zip');
  const destFolder = 'tatkhalsa-theme';

  // Add the root level WordPress theme files directly
  const filesToZip = [
    'style.css',
    'Logo.jpg',
    'functions.php',
    'header.php',
    'footer.php',
    'index.php',
    'template-about.php',
    'template-projects.php',
    'template-punjab-flood-relief.php',
    'template-volunteer.php'
  ];

  const themeDir = path.join(process.cwd(), 'wp-content', 'themes', 'tatkhalsa');

  filesToZip.forEach(file => {
    const filePath = path.join(themeDir, file);
    zip.addLocalFile(filePath, destFolder);
  });
  
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
