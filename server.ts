import express from "express";
import path from "path";
import { createServer as createViteServer } from "vite";
import fs from "fs";

async function startServer() {
  const app = express();
  const PORT = 3000;

  const THEME_PATH = path.join(process.cwd(), "wp-content", "themes", "tatkhalsa");

  // Render processed PHP theme content
  function renderPHP(filePath: string): string {
    let content = fs.readFileSync(filePath, "utf8");

    // Replace header
    if (content.includes("get_header()")) {
      const headerPath = path.join(THEME_PATH, "header.php");
      const headerContent = fs.existsSync(headerPath) ? fs.readFileSync(headerPath, "utf8") : "";
      content = content.replace(/<\?php\s*get_header\(\);\s*\?>/g, headerContent);
      content = content.replace(/<\?php\s*get_header\(\);\?>/g, headerContent);
      content = content.replace(/get_header\(\);/g, headerContent);
    }

    // Replace footer
    if (content.includes("get_footer()")) {
      const footerPath = path.join(THEME_PATH, "footer.php");
      const footerContent = fs.existsSync(footerPath) ? fs.readFileSync(footerPath, "utf8") : "";
      content = content.replace(/<\?php\s*get_footer\(\);\s*\?>/g, footerContent);
      content = content.replace(/<\?php\s*get_footer\(\);\?>/g, footerContent);
      content = content.replace(/get_footer\(\);/g, footerContent);
    }

    // Replace common WordPress functions with standard static equivalents for preview
    content = content.replace(/<\?php\s*language_attributes\(\);\s*\?>/g, 'lang="en"');
    content = content.replace(/<\?php\s*bloginfo\(\s*['"]charset['"]\s*\);\s*\?>/g, 'UTF-8');
    content = content.replace(/<\?php\s*bloginfo\(\s*['"]name['"]\s*\);\s*\?>/g, 'Tatkhalsa Foundation');
    content = content.replace(/<\?php\s*wp_head\(\);\s*\?>/g, '<link rel="stylesheet" href="/style.css">');
    content = content.replace(/<\?php\s*body_class\(\);\s*\?>/g, 'class="home blog"');
    content = content.replace(/<\?php\s*wp_body_open\(\);\s*\?>/g, '');
    content = content.replace(/<\?php\s*wp_footer\(\);\s*\?>/g, '');

    // Resolve template directory and logo or home URLs
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*get_template_directory_uri\(\)\s*\.\s*['"]\/Logo\.(jpg|png)['"]\s*\)\s*;\s*\?>/g, '/Logo.png');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*get_template_directory_uri\(\)\s*\.\s*['"]\/Logo\.(jpg|png)['"]\s*\);\s*\?>/g, '/Logo.png');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*tatkhalsa_get_logo_url\(\)\s*\)\s*;?\s*\?>/g, '/Logo.png');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/['"]\s*\)\s*\);\s*\?>/g, '/');
    
    // Resolve navigation links
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/about\/['"]\s*\)\s*\);\s*\?>/g, '/about');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/projects\/['"]\s*\)\s*\);\s*\?>/g, '/projects');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/volunteer\/['"]\s*\)\s*\);\s*\?>/g, '/volunteer');
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*home_url\(\s*['"]\/punjab-flood-relief\/['"]\s*\)\s*\);\s*\?>/g, '/punjab-flood-relief');

    // Any remaining dynamic template directory calls
    content = content.replace(/<\?php\s*echo\s*esc_url\(\s*get_template_directory_uri\(\)\s*\);\s*\?>/g, '.');

    // Replace WordPress nav menu block
    const navMenuReg = /<\?php\s*if\s*\(\s*has_nav_menu\([\s\S]*?<\?php\s*\}\s*\?>/g;
    content = content.replace(navMenuReg, `
              <div class="nav-links">
                <a href="/">Home</a>
                <a href="/about">About Us</a>
                <a href="/projects">Projects</a>
                <a href="/volunteer">Volunteer</a>
                <a href="/punjab-flood-relief">Punjab Flood Relief</a>
              </div>
    `);

    // Clean up any remaining small php tag strings that are simple Echo/Esc statements
    content = content.replace(/<\?php\s*echo\s*date\(['"]Y['"]\);\s*\?>/g, new Date().getFullYear().toString());

    return content;
  }

  // Support direct static serving of style.css and Logo.jpg/Logo.png from theme path
  app.get("/style.css", (req, res) => {
    res.sendFile(path.join(THEME_PATH, "style.css"));
  });

  app.get("/Logo.png", (req, res) => {
    const pngPath = path.join(THEME_PATH, "Logo.png");
    if (fs.existsSync(pngPath)) {
      res.sendFile(pngPath);
    } else {
      res.sendFile(path.join(THEME_PATH, "Logo.jpg"));
    }
  });

  app.get("/Logo.jpg", (req, res) => {
    const jpgPath = path.join(THEME_PATH, "Logo.jpg");
    if (fs.existsSync(jpgPath)) {
      res.sendFile(jpgPath);
    } else {
      res.sendFile(path.join(THEME_PATH, "Logo.png"));
    }
  });

  // Intercept requests to / or index.php
  app.get("/", (req, res) => {
    const indexPath = path.join(THEME_PATH, "index.php");
    if (fs.existsSync(indexPath)) {
      const parsedHTML = renderPHP(indexPath);
      res.setHeader("Content-Type", "text/html");
      res.send(parsedHTML);
    } else {
      res.status(404).send("index.php (theme default) not found");
    }
  });

  app.get("/index.php", (req, res) => {
    const indexPath = path.join(THEME_PATH, "index.php");
    if (fs.existsSync(indexPath)) {
      const parsedHTML = renderPHP(indexPath);
      res.setHeader("Content-Type", "text/html");
      res.send(parsedHTML);
    } else {
      res.status(404).send("index.php not found");
    }
  });

  // Intercept page paths and resolve them to their corresponding page template files
  const pageTemplateMap: Record<string, string> = {
    "about": "template-about.php",
    "about.php": "template-about.php",
    "projects": "template-projects.php",
    "projects.php": "template-projects.php",
    "volunteer": "template-volunteer.php",
    "volunteer.php": "template-volunteer.php",
    "punjab-flood-relief": "template-punjab-flood-relief.php",
    "punjab-flood-relief.php": "template-punjab-flood-relief.php",
    "punjab-flood-relief.html": "template-punjab-flood-relief.php"
  };

  app.get("/:page", (req, res, next) => {
    let pageName = req.params.page;
    if (pageName.endsWith(".html")) {
      pageName = pageName.replace(".html", "");
    }
    const templateName = pageTemplateMap[pageName] || pageTemplateMap[pageName.toLowerCase()];

    if (templateName) {
      const filePath = path.join(THEME_PATH, templateName);
      if (fs.existsSync(filePath)) {
        const parsedHTML = renderPHP(filePath);
        res.setHeader("Content-Type", "text/html");
        res.send(parsedHTML);
        return;
      }
    }
    
    // Fall back to general static files
    const generalFilePath = path.join(process.cwd(), pageName);
    if (fs.existsSync(generalFilePath) && fs.statSync(generalFilePath).isFile()) {
      res.sendFile(generalFilePath);
    } else {
      next();
    }
  });

  if (process.env.NODE_ENV !== "production") {
    const vite = await createViteServer({
      server: { middlewareMode: true },
      appType: "spa",
    });
    app.use(vite.middlewares);
  } else {
    const distPath = path.join(process.cwd(), 'dist');
    app.use(express.static(distPath));
    app.use(express.static(process.cwd())); // serve files from root in prod
    app.use(express.static(THEME_PATH)); // serve files from theme path in prod (e.g. style.css)
  }

  app.listen(PORT, "0.0.0.0", () => {
    console.log(`Server running on http://localhost:${PORT}`);
  });
}

startServer();
