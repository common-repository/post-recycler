<?php
/*
Plugin Name: Post Recycler
Plugin URI: http://smheart.org/post-recycler/
Description: Once per day this plugin identifies the oldest post in WordPress and sends out a notification via Ping.fm and HelloTXT.com social notification networks.
Author: Matthew Phillips
Version: 1.0
Author URI: http://smheart.org


Copyright 2009 SMHeart Inc, Matthew Phillips  (email : matthew@smheart.org)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

http://www.gnu.org/licenses/gpl.txt


*/

/*
Version
        1.0   16 December 2009

*/

add_action('admin_menu', 'post_recycler_menu');
add_action('admin_head', 'post_recycler_styles');
add_action('post_recycler_schedule', 'post_recycler');
register_activation_hook(__FILE__, 'post_recycler_activation');
register_deactivation_hook(__FILE__, 'post_recycler_deactivation');
register_activation_hook(__FILE__, 'post_recycler_install');

function post_recycler_activation() {
	wp_schedule_event(time(), 'daily', 'post_recycler_schedule');
}
function post_recycler_deactivation() {
	wp_clear_scheduled_hook('post_recycler_schedule');
}


function post_recycler_install() {
	global $wpdb;
	if (!is_blog_installed()) return;
	add_option('post_recycler_ellipse', '...', '', 'no');
	add_option('post_recycler_pingfmemail', get_option("admin_email"), '', 'no');
	add_option('post_recycler_hellotxtemail', get_option("admin_email"), '', 'no');
	add_option('post_recycler_from_address', get_option("admin_email"), '', 'no');
	add_option('post_recycler_message_header','','','no');
	add_option('post_recycler_repost_frequency','daily','','no');
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	}

function post_recycler_menu() {
	add_options_page('Post Recycler Options', 'Post Recycler', 8, __FILE__, 'post_recycler_options');
	}

function post_recycler_styles() {
	?>
 	<link rel="stylesheet" href="/wp-content/plugins/post-recycler/post-recycler.css" type="text/css" media="screen" charset="utf-8"/>
	<?php
	}

function post_recycler_options() {
	?>
	<div class="wrap">
		<h2>Post Recycler V1.0</h2>
		<div id="post_recycler_main">
			<div id="post_recycler_left_wrap">
				<div id="post_recycler_left_inside">
					<h3>Donate</h3>
					<p><em>If you like this plugin and find it useful, help keep this plugin free and actively developed by clicking the <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=10035844" target="paypal"><strong>donate</strong></a> button or send me a gift from my <a href="http://amzn.com/w/11GK2Q9X1JXGY" target="amazon"><strong>Amazon wishlist</strong></a>.  Also follow me on <a href="http://twitter.com/kestrachern/" target="twitter"><strong>Twitter</strong></a>.</em></p>
					<a target="paypal" title="Paypal Donate"href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=10035844"><img src="/wp-content/plugins/post-recycler/paypal.jpg" alt="Donate with PayPal" /></a>
					<a target="amazon" title="Amazon Wish List" href="http://amzn.com/w/11GK2Q9X1JXGY"><img src="/wp-content/plugins/post-recycler/amazon.jpg" alt="My Amazon wishlist" /> </a>
					<a target="Twitter" title="Follow me on Twitter" href="http://twitter.com/kestrachern/"><img src="/wp-content/plugins/post-recycler/twitter.jpg" alt="Twitter" /></a>	
				</div>
			</div>
			<div id="post_recycler_right_wrap">
				<div id="post_recycler_right_inside">
				<h3>About the Plugin</h3>
				<p> This plugin allows for the automatic daily reposting of the oldest post.  The selected post has the oldest post_modified date.  The post_modified date is changed to the current time and Ping.fm and HelloTXT.com social notification networks are updated with information from this post.  Any 'Update Services' configured in the Writing Settings in your Wordpress installation are also notified of the repost.</p>
				</div>
			</div>
		</div>
	<div style="clear:both;"></div>
	<fieldset class="options"><legend>Post Recycling Schedule</legend> 
	<form method="post" action="options.php">
		<?php
			$datetime = get_option('date_format') . ' ' . get_option('time_format');
			$next_cron = wp_next_scheduled('post_recycler_schedule');
			if ( ! empty( $next_cron ) ) :
		?>
		<p id="post-recycler-time-wrap">
			<?php printf('Next Repost Scheduled: <span id="next-repost-time">' . gmdate($datetime, $next_cron + (get_option('gmt_offset') * 3600)) . '</span>'); ?>
		</p>
		<?php endif; ?>
		<?php echo wp_nonce_field('update-options'); ?>
		<table class="form-table">
			<tr valign="top">
				<?php
					echo '<td><a href="http://ping.fm/email/" target="pingfm">PingFM Email</a><br/>  <input type="text" name="post_recycler_pingfmemail" value="'.get_option('post_recycler_pingfmemail').'" /></td>';
					echo '<td><a href="http://hellotxt.com/settings/devices/email" target="helloTXT">HelloTXT Email</a><br/><input type="text" name="post_recycler_hellotxtemail" value="'.get_option('post_recycler_hellotxtemail').'" /></td>';
					echo '<td>From Address  <br/><input type="text" name="post_recycler_from_address" value="'.get_option('post_recycler_from_address').'" /></td>';
					echo '</tr><tr valign="top">';
					echo '<td>RePost Frequency<br/>';
					echo '<select name="post_recycler_repost_frequency">';
					echo '<option value="daily">Daily</option>';
					echo '</select>';
					echo '</td>';
					echo '<td>Ellipse characters<br/><input type="text" name="post_recycler_ellipse" value="'.get_option('post_recycler_ellipse').'" /></td>';
					echo '<td>Preamble to your message<br/><input type="text" name="post_recycler_message_header" value="'.get_option('post_recycler_message_header').'" /></td>'; 
				?>
			</tr>
		</table>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="page_options" value="post_recycler_pingfmemail,post_recycler_hellotxtemail,post_recycler_from_address,post_recycler_days,post_recycler_ellipse,post_recycler_message_header" />
		<p class="submit">
			<input type="submit" name="Submit" value="Schedule rePosts" />
		</p>
	</form>
	</fieldset>
	<div style="clear:both;"></div>			
	<fieldset class="options"><legend>Feature Suggestion/Bug Report</legend> 
	<?php if ($_SERVER['REQUEST_METHOD'] != 'POST'){
      		$me = $_SERVER['PHP_SELF'].'?page=post-recycler/post-recycler.php';
		?>
		<form name="form1" method="post" action="<?php echo $me;?>">
		<table border="0" cellspacing="0" cellpadding="2">
		<tr>
			<td>
				Make a:
			</td>
			<td>
				<select name="MessageType">
				<option value="Feature Suggestion">Feature Suggestion</option>
				<option value="Bug Report">Bug Report</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>
				Name:
			</td>
			<td>
				<input type="text" name="Name">
			</td>
		</tr>
		<tr>
			<td>
				Your email:
			</td>
			<td>
				<input type="text" name="Email" value="<?php echo(get_option('admin_email')) ?>" />
			</td>
		</tr>
		<tr>
			<td valign="top">
				Message:
			</td>
			<td>
				<textarea name="MsgBody">
				</textarea>
			</td>
		</tr>
		<tr>
			<td>
				&nbsp;
			</td>
			<td>
				<input type="submit" name="Submit" value="Send">
			</td>
		</tr>
	</table>
</form>
<?php
   } else {
      error_reporting(0);
	$recipient = 'support@smheart.org';
	$subject = stripslashes($_POST['MessageType']).'- Post Recycler Plugin';
	$name = stripslashes($_POST['Name']);
	$email = stripslashes($_POST['Email']);
	if ($from == "") {
		$from = get_option('admin_email');
	}
	$header = "From: ".$name." <".$from.">\r\n."."Reply-To: ".$from." \r\n"."X-Mailer: PHP/" . phpversion();
	$msg = stripslashes($_POST['MsgBody']);
      if (mail($recipient, $subject, $msg, $header))
         echo nl2br("<h2>Message Sent:</h2>
         <strong>To:</strong> Post Recycler Support
         <strong>Subject:</strong> $subject
         <strong>Message:</strong> $msg");
      else
         echo "<h2>Message failed to send</h2>";
}
?>
	</fieldset>				
	</div>
	<?php
	}

function post_recycler() {
	global $wpdb;
	$pingfmemail= get_option('post_recycler_pingfmemail');
	$hellotxtemail= get_option('post_recycler_hellotxtemail');
	$ellipse = get_option('post_recycler_ellipse');
	$headers = "From:  ".get_option('post_recycler_from_address');
 			global $post;
 			$myposts = get_posts('numberposts=1&order=ASC&orderby=modified');
 			foreach($myposts as $post) :
				$link = get_permalink($post->ID);
				$subject = $post->post_title;
				$body = $post->post_content;
				$length = 140-strlen($ellipse);
				$content = substr(get_option('post_recycler_message_header')." ".$subject.": ".$link." - ".$body,0,$length).$ellipse;
				mail($pingfmemail,$subject,$content,$headers);
				mail($hellotxtemail,$subject,$content,$headers);
            			$timestamp = current_time('mysql',0);
				$gmtstamp  = current_time('mysql',1);
				$wpdb->query("UPDATE $wpdb->posts SET post_modified='$timestamp', post_modified_gmt='$gmtstamp' WHERE ID='$post->ID'");
				generic_ping($post->ID);
			endforeach;
			wp_cache_flush();
	}
?>