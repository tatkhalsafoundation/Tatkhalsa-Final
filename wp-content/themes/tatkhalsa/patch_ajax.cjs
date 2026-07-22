const fs = require('fs');
let content = fs.readFileSync('wp-content/themes/tatkhalsa/admin-ajax-handlers.php', 'utf8');
content = content.replace(
    /update_post_meta\(\$id, 'donor_name', sanitize_text_field\(\$payload\['name'\]\)\);/,
    `if (isset($payload['donorNumber'])) {
        update_post_meta($id, 'donor_id_number', sanitize_text_field($payload['donorNumber']));
    }
    update_post_meta($id, 'donor_name', sanitize_text_field($payload['name']));`
);
fs.writeFileSync('wp-content/themes/tatkhalsa/admin-ajax-handlers.php', content);
console.log("Patched admin-ajax-handlers.php");
