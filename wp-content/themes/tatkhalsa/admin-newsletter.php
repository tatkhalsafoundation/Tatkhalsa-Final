<?php
// Admin Newsletter Page for Blood Donors
function tatkhalsa_add_donor_newsletter_menu() {
    add_submenu_page(
        'blood-master-data',
        'Send Newsletter',
        'Send Newsletter',
        'manage_options',
        'blood-donor-newsletter',
        'tatkhalsa_render_newsletter_page'
    );
}
add_action( 'admin_menu', 'tatkhalsa_add_donor_newsletter_menu' );

function tatkhalsa_render_newsletter_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $donors_args = array(
        'post_type'      => 'blood_donor',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    );
    $donors_posts = get_posts( $donors_args );
    $verified_emails = array();
    foreach ( $donors_posts as $post ) {
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
        <p>Compose a newsletter that will be sent from <strong>info@tatkhalsa.in</strong> to all verified donors and subscribers.</p>
        
        <div id="newsletterAlert" style="display: none; padding: 10px; margin-bottom: 15px; border-left: 4px solid #46b450; background: #fff; box-shadow: 0 1px 1px rgba(0,0,0,.04);"></div>

        <form id="newsletterForm" onsubmit="window.sendAdminNewsletter(event)">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="newsletterTo">To (<?php echo $count; ?> Donors)</label></th>
                        <td>
                            <textarea id="newsletterTo" name="newsletterTo" style="width: 100%; max-width: 600px; padding: 12px; color: #333; resize: vertical; border: 1px solid #8c8f94;" rows="3"><?php echo esc_textarea( $emails_str ); ?></textarea>
                            <p class="description">You can edit the emails before sending. Separate multiple emails with a comma.</p>
                            <button type="button" class="button" onclick="document.getElementById('newsletterTo').value = '';" style="margin-top: 5px;">Clear All Emails (for testing)</button>
                            <?php if($count === 0): ?>
                            <p class="description">No verified emails found in directory.</p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="newsletterSubject">Subject</label></th>
                        <td>
                            <input type="text" id="newsletterSubject" name="newsletterSubject" required class="regular-text" style="width: 100%; max-width: 600px;" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="newsletterBody">Message</label></th>
                        <td>
                                                                                                                <?php
                            $content = '
<div style="background-color: #172a4f; padding: 20px; text-align: center; margin-bottom: 30px;">
    <h2 style="color: #f0a500; margin: 0; font-size: 20px; letter-spacing: 1px;">NIHUNG SANTHIA NEWSLETTER</h2>
    <p style="color: #ffffff; margin: 10px 0 0 0; font-size: 14px;">ISSUE X</p>
</div>
<h3 style="color: #f0a500; text-align: center;">PEHLA PARKASH SRI GURU GRANTH SAHIB JI</h3>
<p style="text-align: center;">
    <em>[Insert Image Here]</em>
</p>
<p style="text-align: justify;">In this section of the newsletter, we will be sharing sections from various Granths that inspire us in our study of Santhia and Sikhi Jeevan. This passage from Adhyatam Parkash...</p>
<p><strong>Padh Arth (word by word meaning):</strong> ...</p>
<p><strong>Arth (meaning):</strong> ...</p>

<div style="background-color: #f0a500; color: #ffffff; padding: 20px; margin-top: 40px; text-align: left;">
    <p style="margin: 0 0 10px 0;"><strong>12th September</strong> - Pehla Parkash Sri Guru Granth Sahib Ji</p>
    <p style="margin: 0;"><strong>15th September</strong> - Barsi Singh Sahib Giani Amarjeet Singh Ji (Hazur Sahib)</p>
</div>
';
                            $editor_id = 'newsletterBody';
                            $settings = array(
                                'textarea_name' => 'newsletterBody',
                                'editor_height' => 400,
                                'media_buttons' => true,
                                'tinymce'       => array(
                                    'toolbar1' => 'formatselect,bold,italic,bullist,numlist,blockquote,alignleft,aligncenter,alignright,link,unlink,wp_more,spellchecker,wp_adv',
                                    'toolbar2' => 'strikethrough,hr,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help',
                                ),
                            );
                            wp_editor( $content, $editor_id, $settings );
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <p class="submit">
                <button type="submit" id="newsletterSubmitBtn" class="button button-primary button-large" <?php echo $count === 0 ? 'disabled' : ''; ?>>Send to All Donors</button>
            </p>
        </form>
    </div>

    <script>
    window.sendAdminNewsletter = async function(e) {
        if (e) e.preventDefault();
        const btn = document.getElementById('newsletterSubmitBtn');
        const alertBox = document.getElementById('newsletterAlert');
        const subject = document.getElementById('newsletterSubject').value;
                let message = '';
        if (typeof tinymce !== 'undefined' && tinymce.get('newsletterBody')) {
            message = tinymce.get('newsletterBody').getContent();
        } else {
            message = document.getElementById('newsletterBody').value;
        }
        const toEmails = document.getElementById('newsletterTo').value;

        btn.disabled = true;
        btn.innerText = 'Sending...';
        alertBox.style.display = 'none';

        try {
            const formData = new FormData();
            formData.append('action', 'send_donor_newsletter');
            formData.append('subject', subject);
            formData.append('message', message);
            formData.append('newsletterTo', toEmails);

            const response = await fetch(ajaxurl, {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            
            alertBox.style.display = 'block';
            if (data.success) {
                alertBox.style.borderLeftColor = '#46b450';
                alertBox.innerText = data.data.message || 'Newsletter sent successfully.';
                document.getElementById('newsletterForm').reset();
            } else {
                alertBox.style.borderLeftColor = '#dc3232';
                alertBox.innerText = data.data.message || 'Failed to send newsletter.';
            }
        } catch (err) {
            alertBox.style.display = 'block';
            alertBox.style.borderLeftColor = '#dc3232';
            alertBox.innerText = 'Network error occurred.';
        } finally {
            btn.disabled = false;
            btn.innerText = 'Send to All Donors';
        }
    };
    </script>
    <?php
}
