<?php
require_once('../../../wp-load.php');

$args = array(
    'post_type'      => 'blood_donor',
    'posts_per_page' => -1,
    'post_status'    => 'any',
    'order'          => 'ASC', // older first
);
$donors = get_posts($args);

$next_id = get_option('tatkhalsa_next_donor_id', 1);

foreach ($donors as $donor) {
    $existing = get_post_meta($donor->ID, 'donor_id_number', true);
    if (!$existing) {
        $donor_id_string = 'TKF-DON-' . $next_id;
        update_post_meta($donor->ID, 'donor_id_number', $donor_id_string);
        echo "Assigned $donor_id_string to {$donor->ID}\n";
        $next_id++;
    }
}
update_option('tatkhalsa_next_donor_id', $next_id);
echo "Next donor ID will be $next_id\n";
