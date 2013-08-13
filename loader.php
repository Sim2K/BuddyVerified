<?php
/*
Plugin Name: BuddyVerified
Plugin URI: http://taptappress.com
Description: Allows admins to specify verified accounts like on Twitter. Adds a check on user profiles
Author: modemlooper
Version: 2.0.3
Author URI: http://twitter.com/modemlooper
*/
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

function bp_verified_add_admin_menu() {
	add_options_page(  __('BuddyVerified', 'bp-verified'), __('BuddyVerified', 'bp-verified'), 'manage_options', 'bp-verified', 'bp_verified_admin' );
}
add_action( 'admin_menu', 'bp_verified_add_admin_menu' );
add_action( 'network_admin_menu', 'bp_verified_add_admin_menu' );


function buddyverified_init() {
		require ( dirname( __FILE__ ) . '/includes/buddypress-verified-admin.php' );
		require ( dirname( __FILE__ ) . '/includes/buddypress-verified-profile.php' );
}
add_action( 'bp_include', 'buddyverified_init' );

function buddyverified_textdomain_init() {
  load_plugin_textdomain( 'bp-verified', false, dirname( ( __FILE__ ) . '/languages/' ) );
}
add_action('plugins_loaded', 'buddyverified_textdomain_init');

function bp_show_verified_badge() {
  global $bp;

  	$is_verified = get_user_meta( $bp->displayed_user->id, 'bp-profile-verified', true );

  	$file = __FILE__ ;
	$plugin_url = plugin_dir_url( $file );

	if ( $is_verified['profile'] == 'yes' ):
		?>
			<?php if (  $is_verified['image'] == null ): ?>
			<span id="bp-verified"><img src="<?php echo $plugin_url; ?>/images/1.png">
			<?php else : ?>
				<span id="bp-verified"><img src="<?php echo $plugin_url; ?>/images/<?php echo $is_verified['image'] ?>.png">
			<?php endif ; ?>

			<?php if (  $is_verified['text'] == null ): ?>
				<span class="v-text"><?php _e('Verified User', 'bp-verified'); ?></span></span>
			<?php else : ?>
				<span class="v-text"><?php echo $is_verified['text'] ?></span></span>
			<?php endif ; ?>

<?php
  	endif;
}
add_action( 'bp_before_member_header_meta', 'bp_show_verified_badge' );


function bp_show_verified_badge_activity() {
  global $bp, $activities_template;

  	$is_verified = get_user_meta( $activities_template->activity->user_id, 'bp-profile-verified', true );

  	$file = __FILE__ ;
	$plugin_url = plugin_dir_url( $file );

	if ( $is_verified['profile'] == 'yes' ):
		?>
			<?php if (  $is_verified['image'] == null ): ?>
			<span id="bp-verified"><img src="<?php echo $plugin_url; ?>/images/1.png">
			<?php else : ?>
				<span id="bp-verified"><img src="<?php echo $plugin_url; ?>/images/<?php echo $is_verified['image'] ?>.png">
			<?php endif ; ?>

<?php
  	endif;
}


function bp_verified_insert_head() {
?>
<style type="text/css">
span#bp-verified {
	margin-left: 10px;
	height: 20px;
	display: inline-block;
}
span.v-text {
	vertical-align: middle;
	font-size: 12px;
	font-weight: normal;
	line-height: 190%;
	margin-bottom: 7px;
	color: #5e5e5e;
}
#bp-verified img {
	width: 23px;
	height: 23px;
	float: left;
	margin-right: 5px;
}
</style>

<?php
}
add_action('wp_head', 'bp_verified_insert_head');