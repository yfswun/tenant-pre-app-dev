<?php
function pods_user_profile_display($user) {

    $theme = 'sg-window';

    //Escape all of the meta data we need into variables
    // $name = esc_attr($user->user_firstname) . '&nbsp;' . esc_attr($user->user_lastname);
    $name = esc_attr($user->display_name);
    $title = esc_attr($user->title);
    $email = esc_attr($user->user_email);
    // $phone = esc_attr($user->phone_number);
    // $street_1 = esc_attr($user->street_address_line_1);
    // $street_2 = esc_attr($user->street_address_line_2);
    $phone_mobile = esc_attr($user->phone_mobile);
    $phone_home = esc_attr($user->phone_home);
    $phone_work = esc_attr($user->phone_work);
    $mail_add_1 = esc_attr($user->mailing_address_line_1);
    $mail_add_2 = esc_attr($user->mailing_address_line_2);
    $city = esc_attr($user->city);
    $state = esc_attr($user->state);
    $zip = esc_attr($user->zip_code);
    $website = esc_url($user->user_url);
    $twitter = esc_url($user->twitter);
    $linkedin = esc_url($user->linkedin);
?>
    <div class="author-info">
        <h2><?php echo $name; ?></h1>
        <div class="author-avatar">
            <?php echo '<a href="' . pods_image_url( $user->picture, 'large') . '">' . pods_image ( $user->picture, 'thumbnail') . '</a>';
             ?>
        </div><!-- .author-avatar -->
        <div class="author-description">
            <p><strong><?php _e('Email:', $theme); ?></strong> <?php echo '<a href="mailto:' . $email . '">' . $email . '</a>'; ?></p>
            <p><strong><?php _e('Phone (Mobile):', $theme); ?></strong> <?php echo $phone_mobile; ?></p>
            <p><strong><?php _e('Phone (Home):', $theme); ?></strong> <?php echo $phone_home; ?></p>
            <p><strong><?php _e('Phone (Work):', $theme); ?></strong> <?php echo $phone_work; ?></p>
            <div><p><strong><?php  _e('Mailing Address:', $theme); ?></strong><br />
                  <?php echo $mail_add_1; ?><br />
                  <?php echo $mail_add_2; ?><br />
                  <?php echo ($city . ',' . $state . ' ' . $zip); ?>
                  </p>
            </div>
            <p><strong><?php _e('Website:', $theme); ?></strong> <?php echo '<a href="' . $website . '">' . $website . '</a>'; ?></p>
            <p><strong><?php _e('Twitter:', $theme); ?></strong> <?php echo '<a href="' . $twitter . '">' . $twitter . '</a>'; ?></p>
            <p><strong><?php _e('LinkedIn:', $theme); ?> </strong> <?php echo '<a href="' . $linkedin . '">' . $linkedin . '</a>'; ?></p>
        </div><!-- .author-description  -->
    </div><!-- .author-info -->
<?php
}
?>