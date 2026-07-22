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
        if ( ! empty( $email ) ) {
            $verified_emails[] = $email;
        }
    }
    
    $count = count( $verified_emails );
    $emails_str = implode( ', ', $verified_emails );
    ?>
    <div class="wrap">
        <h1>Send Newsletter to Donors</h1>
        <p>Compose a newsletter that will be sent from <strong>info@tatkhalsa.in</strong> to all verified donors in the directory.</p>
        
        <div id="newsletterAlert" style="display: none; padding: 10px; margin-bottom: 15px; border-left: 4px solid #46b450; background: #fff; box-shadow: 0 1px 1px rgba(0,0,0,.04);"></div>

        <form id="newsletterForm" onsubmit="window.sendAdminNewsletter(event)">
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row"><label for="newsletterTo">To (<?php echo $count; ?> Donors)</label></th>
                        <td>
                            <textarea id="newsletterTo" name="newsletterTo" style="width: 100%; max-width: 600px; padding: 12px; color: #333; resize: vertical; border: 1px solid #8c8f94;" rows="3"><?php echo esc_textarea( $emails_str ); ?></textarea>
                            <p class="description">You can edit the emails before sending. Separate multiple emails with a comma.</p>
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
                            <textarea id="newsletterBody" name="newsletterBody" rows="8" required style="width: 100%; max-width: 600px;"></textarea>
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
        const message = document.getElementById('newsletterBody').value;
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
