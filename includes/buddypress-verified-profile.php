<?php
function bp_setup_verified_nav() {
	global $bp;
	
	if ( !$bp->loggedin_user->is_site_admin )
		return false;
		
		/* Add a nav item for this*/
	bp_core_new_subnav_item( array(
		'name' => __( 'Verify', 'bp-verified' ),
		'slug' => 'verified',
		'parent_slug' => $bp->settings->slug,
		'parent_url' => $bp->displayed_user->domain . $bp->settings->slug . '/',
		'screen_function' => 'bp_verified_screen_settings_menu',
		'position' => 40
	) );
}
add_action( 'bp_setup_nav', 'bp_setup_verified_nav' );


function mark_spammer(){
	global $bp, $wpdb;

	$user_id = $bp->displayed_user->id;
	
	$wpdb->query("
	UPDATE $wpdb->users 
	SET user_status = 1
	WHERE ID = $user_id
	");
	
	// Hide this user's activity
	if ( bp_is_active( 'activity' ) ) {
		bp_activity_hide_user_activity( $user_id );
	}
}


function unmark_spammer(){
	global $bp, $wpdb;

	$user_id = $bp->displayed_user->id;
	
	$wpdb->query("
	UPDATE $wpdb->users 
	SET user_status = 0
	WHERE ID = $user_id
	");
	
		// Unhide this user's activity
	if ( bp_is_active( 'activity' ) ) {
		bp_activity_unhide_user_activity($user_id);
	}
	
}

function bp_activity_unhide_user_activity(){
	global $bp, $wpdb;

	$wpdb->query("
	UPDATE wp_bp_activity 
	SET hide_sitewide = 0
	WHERE user_id = $user_id
	");
}

function bp_verified_screen_settings_menu() {

	global $bp, $wpdb, $current_user, $bp_settings_updated, $pass_error;

/* thanks @Boone! */
	
		if ( isset( $_POST['submit'] )) {
			
				$selected_radio = $_POST['bp-verified-profile'];
				$verified_text = $_POST['bp-verified-text'];
				$verified_image = $_POST['bp-verified-image'];
				
				$bp_verified_arr = array(
				'profile' => $selected_radio, 
				'text' => $verified_text,
				'image' => $verified_image
				);
				
				if ($selected_radio == 'yes') {
					update_user_meta($bp->displayed_user->id, 'bp-profile-verified', $bp_verified_arr);
					unmark_spammer();

				} else 
			
				if ($selected_radio == 'no') {
					update_user_meta($bp->displayed_user->id, 'bp-profile-verified', $bp_verified_arr);
					unmark_spammer();
				} else 
			
				if ($selected_radio == 'spammer') {
					update_user_meta($bp->displayed_user->id, 'bp-profile-verified', $bp_verified_arr);
					mark_spammer();
				}
						
	 	bp_core_add_message( 'Settings updated!' );
	 	bp_core_redirect( bp_displayed_user_domain() . $bp->settings->slug . '/verified' );
	 
	 	}
	 	
	add_action( 'bp_template_content', 'bp_verified_screen_settings_menu_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	
}


function bp_verified_screen_settings_menu_content() {
	global $bp;
	
	$bp_verified_data = get_user_meta($bp->displayed_user->id, 'bp-profile-verified', true);
	
	$file = dirname(__FILE__) . '/';
	$plugin_url = plugin_dir_url($file);
?>
<h3><?php _e('Verify Options', 'bp-verified'); ?></h3>


<form action="" method="post" id="standard-form" class="standard-form" name="settings-form">
	<table class="notification-settings" id="activity-notification-settings">
		<thead>
			<tr>
				<th class="title"><?php _e('Verified', 'bp-verified'); ?></th>
				<th class="yes"><?php _e('Yes', 'bp-verified'); ?></th>
				<th class="no"><?php _e('No', 'bp-verified'); ?></th>
				<th class="no">
				<?php if ( bp_displayed_user_id() != '1' ) { ?>
				<?php _e('Spam', 'bp-verified'); ?>
				<?php } ?>
				</th>
			</tr>
		</thead>
		
		<tbody>
			<tr id="activity-notification-settings">
				<td>sss</td>
				<td><?php _e( 'Verify user', 'bp-verified' ); ?></td>
				<td class="yes"><input type="radio" name="bp-verified-profile" value="yes" <?php if ( $bp_verified_data['profile'] == 'yes') echo 'checked="checked"' ?> /></td>
				<td class="no"><input type="radio" name="bp-verified-profile" value="no"  <?php if ($bp_verified_data['profile'] == 'no') echo 'checked="checked"' ?>/></td>
				<td class="no">
				<?php if ( bp_displayed_user_id() != '1' ) { ?>
				<input type="radio" name="bp-verified-profile" value="spammer" <?php if ($bp_verified_data['profile'] == 'spammer') echo 'checked="checked"' ?> />
				<?php } ?>
				</td>
			</tr>
		</tbody>
	</table>
		<p></p>
		
		<!-- verified image table -->
	<table class="notification-settings" id="activity-notification-settings">
		<thead>
			<tr>
				<th class="title"><?php _e('Verified Image', 'bp-verified'); ?></th>
				<th class="yes"><img src="<?php echo $plugin_url; ?>/images/1.png"></th>
				<th class="yes"><img src="<?php echo $plugin_url; ?>/images/2.png"></th>
				<th class="yes"><img src="<?php echo $plugin_url; ?>/images/3.png"></th>
				<th class="yes"><img src="<?php echo $plugin_url; ?>/images/4.png"></th>
				<th class="yes"><img src="<?php echo $plugin_url; ?>/images/5.png"></th>
				<th class="yes"><img src="<?php echo $plugin_url; ?>/images/6.png"></th>
			</tr>
		</thead>
		
		<tbody>
			<tr id="activity-notification-settings">
				<td></td>
				<td><?php _e( 'Choose image', 'bp-verified' ); ?></td>
				
				<td class="yes"><input type="radio" name="bp-verified-image" value="1" <?php if ($bp_verified_data['image'] == '1') echo 'checked="checked"' ?> /></td>
				<td class="no"><input type="radio" name="bp-verified-image" value="2"  <?php if ($bp_verified_data['image'] == '2') echo 'checked="checked"' ?>/></td>
				<td class="no"><input type="radio" name="bp-verified-image" value="3" <?php if ($bp_verified_data['image'] == '3') echo 'checked="checked"' ?> /></td>
				<td class="no"><input type="radio" name="bp-verified-image" value="4" <?php if ($bp_verified_data['image'] == '4') echo 'checked="checked"' ?> /></td>
				<td class="no"><input type="radio" name="bp-verified-image" value="5" <?php if ($bp_verified_data['image'] == '5') echo 'checked="checked"' ?> /></td>
				<td class="no"><input type="radio" name="bp-verified-image" value="6" <?php if ($bp_verified_data['image'] == '6') echo 'checked="checked"' ?> /></td>	
			</tr>
		</tbody>

	</table>

	<label><?php _e('Custom Verified Text', 'bp-verified'); ?></label>
    <input class="settings-input" id="bp-verified-text" name="bp-verified-text" type="text"  size="150" value="" placeholder="<?php _e('Verified User', 'bp-verified'); ?>" style="width: 98%;"/>
    		
    <div class="submit" style="width:150px; display:inline-block;">
		<input type="submit" name="submit" id="submit" value="<?php _e( 'Save Changes', 'bp-verified' ); ?>" />
	</div>
	
	
	<span class="delete" style="margin:30px 0; float:right;">
	<?php if ( bp_displayed_user_id() != '1' ) { ?>
	<a style="color:red;" href="<?php echo wp_nonce_url( $bp->displayed_user->domain . 'admin/delete-user/', 'delete-user' ) ?>" class="confirm"><?php printf( __( "Delete %s's Account", 'buddypress' ), esc_attr( $bp->displayed_user->fullname ) ); ?></a>
	<?php } ?>
	</span>

	</form>
<?php } ?>