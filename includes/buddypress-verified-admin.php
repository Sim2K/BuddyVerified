<?php
function bp_verified_admin(){
	global $bp, $wpdb;

	$aUsersID = $wpdb->get_col("
	SELECT user_id, meta_value
	FROM $wpdb->usermeta
	WHERE meta_key = 'bp-profile-verified'
	");

	?>
	<div class="wrap">
		<h2><?php _e( 'BuddyPress Verified', 'bp-verified') ?></h2>

		<form action="options.php" id="bp-verified-form" method="post">
			<table id="bp-verified" class="widefat">
				<thead>
					<tr>
						<th scope="col" colspan="4"><?php _e('Verified Users', 'bp-verified'); ?></th>
					</tr>
					<tr class="header">
						<td><?php _e( 'User', 'bp-verified' ); ?></td>
						<td></td>
						<td><?php _e( 'Stats', 'bp-verified' ); ?></td>
						<td></td>
					</tr>
				</thead>
				<tfoot>
					<tr><th colspan="4"></th></tr>
				</tfoot>

				<tbody>

				<?php foreach ( $aUsersID as $iUsersID ) :?>

					<?php $user_id = $iUsersID; ?>
					<?php $user_info = get_userdata( $user_id ); ?>
					<?php $meta_isverified = get_user_meta( $user_id,'bp-profile-verified' ); ?>

					<?php $registered = ( $user_info->user_registered . "\n" ); ?>
					<?php $last_active = ( $user_info->last_activity . "\n" ); ?>

					<?php
					$aUsersActivity = $wpdb->get_col( $wpdb->prepare("
						SELECT count(*)
						 FROM wp_bp_activity
						 WHERE user_id = %s
						 , $user_id ") );
				    $aUsersTopics = $wpdb->get_col( $wpdb->prepare("
						SELECT count(*)
						 FROM wp_bb_topics
						 WHERE topic_poster = %s
						 , $user_id ") );
					?>

					 <?php if ( $meta_isverified[0]['profile'] == 'yes' ) { ?>
					<tr>
						<td  width="50"><a href="<?php echo bloginfo('home'); ?>/<?php echo BP_MEMBERS_SLUG ?>/<?php echo $user_info->user_nicename; ?>/settings/verified"><?php echo bp_core_fetch_avatar( 'item_id='. $user_id ); ?></a></td>
						<td width="225"><span style="font-weight:bold;"><?php echo $user_info->user_nicename; ?></span></br><?php echo $user_info->user_email; ?></td>
						<td>Last Active: <?php echo date("n/j/Y", strtotime( $last_active ) ); ?></br>Register Date: <?php echo date("n/j/Y", strtotime( $registered ) ); ?></td>
						<td>Activity Updates:  <?php print_r( $aUsersActivity[0] ); ?></br>Forum Topics:  <?php print_r( $aUsersTopics[0] ); ?></td>
					</tr>
					<?php } ?>
				<?php endforeach;  /* End Foreach Field */ ?>

				</tbody>

			</table>

			<p></p>

			<table id="bp-verified-spammer" class="widefat">
				<thead>
					<tr>
						<th scope="col" colspan="4"><?php _e('Users Marked as Spam', 'bp-verified'); ?></th>
					</tr>
					<tr class="header">
						<td><?php _e( 'User', 'bp-verified' ); ?></td>
						<td></td>
						<td><?php _e( 'Stats', 'bp-verified' ); ?></td>
						<td></td>
				</thead>
				<tfoot>
					<tr><th colspan="4"></th></tr>
				</tfoot>

				<tbody>

				<?php foreach ( $aUsersID as $iUsersID ) :?>

					<?php $user_id = $iUsersID; ?>
					<?php $user_info = get_userdata( $user_id ); ?>
					<?php $meta_isverified = get_user_meta( $user_id,'bp-profile-verified' ); ?>

					<?php $registered = ( $user_info->user_registered . "\n"); ?>
					<?php $last_active = ( $user_info->last_activity . "\n"); ?>

					<?php
					$aUsersActivity = $wpdb->get_col( $wpdb->prepare("
						SELECT count(*)
						 FROM wp_bp_activity
						 WHERE user_id = %s
						 , $user_id ") );

					$aUsersTopics = $wpdb->get_col( $wpdb->prepare("
						SELECT count(*)
						 FROM wp_bb_topics
						 WHERE topic_poster = %s
						 , $user_id ") );

					?>

					 <?php if ( $meta_isverified[0]['profile'] == 'spammer' ) { ?>
					<tr>
						<td width="0"></td>
						<td width="225"><span style="font-weight:bold;"><?php echo $user_info->user_nicename; ?></span></br><?php echo $user_info->user_email; ?></td>
						<td>Last Active: <?php echo date("n/j/Y", strtotime( $last_active ) ); ?></br>Register Date: <?php echo date("n/j/Y", strtotime( $registered ) ); ?></td>

						<td>Activity Updates:  <?php print_r( $aUsersActivity[0] ); ?></br>Forum Topics:  <?php print_r( $aUsersTopics[0] ); ?></td>
					</tr>
					<?php } ?>
				<?php endforeach;  /* End Foreach Field */ ?>

				</tbody>

			</table>

			<p></p>

		</form>
	</div>
<?php
}


function bp_verified_delete_user(){
	global $wpdb;

    if ( current_user_can( 'manage_options' ) )

    $user_id = $_GET['user_id'];

    	require_once( ABSPATH.'wp-admin/includes/user.php' );

       	wp_delete_user( $user_id );

    	wp_redirect( '/wp-admin/admin.php?page=bp-verified' );

    exit();
}
if( isset( $_REQUEST['action'] ) && $_REQUEST['action']=='bp_verified_delete_user' ){
 bp_verified_delete_user();
}
?>
