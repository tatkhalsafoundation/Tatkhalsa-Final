<?php
$content = file_get_contents('wp-content/themes/tatkhalsa/admin-newsletter.php');
$target = <<<EOD
    foreach ( \$donors_posts as \$post ) {
        \$email = get_post_meta( \$post->ID, 'donor_email', true );
        if ( ! empty( \$email ) ) {
            \$verified_emails[] = \$email;
        }
    }
    
    \$count = count( \$verified_emails );
    \$emails_str = implode( ', ', \$verified_emails );

    ?>
    <div class="wrap">
        <h1>Send Newsletter to Donors</h1>
        <p>Compose a newsletter that will be sent from <strong>info@tatkhalsa.in</strong> to all verified donors in the directory.</p>
EOD;

$replacement = <<<EOD
    foreach ( \$donors_posts as \$post ) {
        \$email = get_post_meta( \$post->ID, 'donor_email', true );
        if ( ! empty( \$email ) && is_email( \$email ) ) {
            \$verified_emails[] = \$email;
        }
    }

    \$general_subscribers = get_option('tatkhalsa_newsletter_subscribers', []);
    if ( is_array(\$general_subscribers) ) {
        foreach ( \$general_subscribers as \$email ) {
            if ( is_email( \$email ) ) {
                \$verified_emails[] = \$email;
            }
        }
    }

    \$unsubscribed = get_option('tatkhalsa_unsubscribed_emails', []);
    if ( is_array(\$unsubscribed) ) {
        \$verified_emails = array_diff(\$verified_emails, \$unsubscribed);
    }
    
    \$verified_emails = array_unique(\$verified_emails);

    \$count = count( \$verified_emails );
    \$emails_str = implode( ', ', \$verified_emails );

    ?>
    <div class="wrap">
        <h1>Send Newsletter to Community</h1>
        <p>Compose a newsletter that will be sent from <strong>info@tatkhalsa.in</strong> to all verified donors and subscribers.</p>
EOD;

// Because of potential whitespace/CRLF mismatches, let's use a regex replace
$regex = '/foreach \(\s*\$donors_posts as \$post\s*\) \{.*?\<p\>Compose a newsletter that will be sent from <strong>info@tatkhalsa\.in<\/strong> to all verified donors in the directory\.<\/p>/s';

$new_content = preg_replace($regex, $replacement, $content);
file_put_contents('wp-content/themes/tatkhalsa/admin-newsletter.php', $new_content);
echo "Patched admin-newsletter.php\n";
