const fs = require('fs');
let content = fs.readFileSync('wp-content/themes/tatkhalsa/admin-newsletter.php', 'utf8');

const regex = /foreach \(\s*\$donors_posts as \$post\s*\) \{[\s\S]*?\<p\>Compose a newsletter that will be sent from <strong>info@tatkhalsa\.in<\/strong> to all verified donors in the directory\.<\/p>/s;

const replacement = `foreach ( $donors_posts as $post ) {
        $email = get_post_meta( $post->ID, 'donor_email', true );
        if ( ! empty( $email ) && is_email( $email ) ) {
            $verified_emails[] = $email;
        }
    }

    $general_subscribers = get_option('tatkhalsa_newsletter_subscribers', []);
    if ( is_array($general_subscribers) ) {
        foreach ( $general_subscribers as $email ) {
            if ( is_email( $email ) ) {
                $verified_emails[] = $email;
            }
        }
    }

    $unsubscribed = get_option('tatkhalsa_unsubscribed_emails', []);
    if ( is_array($unsubscribed) ) {
        $verified_emails = array_diff($verified_emails, $unsubscribed);
    }
    
    $verified_emails = array_unique($verified_emails);

    $count = count( $verified_emails );
    $emails_str = implode( ', ', $verified_emails );

    ?>
    <div class="wrap">
        <h1>Send Newsletter to Community</h1>
        <p>Compose a newsletter that will be sent from <strong>info@tatkhalsa.in</strong> to all verified donors and subscribers.</p>`;

content = content.replace(regex, replacement);
fs.writeFileSync('wp-content/themes/tatkhalsa/admin-newsletter.php', content);
console.log("Patched admin-newsletter.php");
