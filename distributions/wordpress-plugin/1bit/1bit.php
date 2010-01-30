<?php
/*
Plugin Name: 1 Bit Audio Player
Plugin URI: http://1bit.markwheeler.net
Description: A very simple and lightweight Flash audio player for previewing tracks in a WordPress blog.
Version: 1.4
Author: Mark Wheeler
Author URI: http://www.markwheeler.net
*/

// add javascript to head
function oneBitJsHead() {
	if(get_option('oneBitSWFObject') == 1)
		echo '<script type="text/javascript" src="'.get_settings('siteurl').'/wp-content/plugins/1bit/swfobject.js"></script>'."\n";
    echo '<script type="text/javascript" src="'.get_settings('siteurl').'/wp-content/plugins/1bit/1bit.js"></script>'."\n";
    echo '<script type="text/javascript">'."\n";
    echo "oneBit = new OneBit('".get_settings('siteurl')."/wp-content/plugins/1bit/1bit.swf');\n";
	echo "oneBit.ready(function() {\n";
	if (get_settings('oneBitForeColor'))
	    echo "oneBit.specify('color', '#".get_settings('oneBitForeColor')."');\n";
	echo "oneBit.specify('background', '";
	if (get_settings('oneBitBackColor' != 'transparent'))
	    echo "#";
	echo get_settings('oneBitBackColor')."');\n";
	if (get_settings('oneBitSize'))
	    echo "oneBit.specify('playerSize', '".get_settings('oneBitSize')."');\n";
	echo "oneBit.specify('position', '".get_settings('oneBitPosition')."');\n";    
	if (get_settings('oneBitAnalytics'))
		echo "oneBit.specify('analytics', true);\n";
	echo "oneBit.apply('".get_settings('oneBitSelector')."');\n";
	echo "});\n";
	echo "</script>\n";
}

function oneBitOptions() {
    if (function_exists('add_options_page')) {
		add_options_page('1 Bit Audio Player Options', '1 Bit Audio Player', 8, basename(__FILE__), 'oneBitOptionsPage');
    }
 }
 
function oneBitOptionsPage() {
	if (isset($_POST['info_update'])) { ?>
		<div class="updated">
		<p>
		<strong>
		<?php
		if($_POST['oneBitSize'] < 4 && !$_POST['oneBitAutoSize']) {
			_e('Please set the player size to a valid whole number equal to or greater than 4.', 'English');
		} else {
			if(!$_POST['oneBitSelector']) {
				_e('Please set a selector string (if in doubt just enter \'a\').', 'English');
			} else {
				if($_POST['oneBitAutoSize'])
					update_option('oneBitSize', '');
				else
				    update_option('oneBitSize', $_POST['oneBitSize']);
				if($_POST['oneBitAutoColor'])
				    update_option('oneBitForeColor', '');
				else
					update_option('oneBitForeColor', str_pad(strtoupper($_POST['oneBitForeColor']), 6, '0'));
				if($_POST['oneBitTransparent'])
				    update_option('oneBitBackColor', 'transparent');
				else
					update_option('oneBitBackColor', str_pad(strtoupper($_POST['oneBitBackColor']), 6, '0'));
				update_option('oneBitPosition', $_POST['oneBitPosition']);
				update_option('oneBitAnalytics', $_POST['oneBitAnalytics']);
				update_option('oneBitSelector', $_POST['oneBitSelector']);
				update_option('oneBitSWFObject', $_POST['oneBitSWFObject']);
				_e('Options saved.', 'English');
			}
		}
		?>
		</strong>
		</p>
		</div>
	<?php } ?>
	<div class=wrap>
	<form method="post">
		<h2>1 Bit Audio Player Options</h2>
		<div class="submit">
		<input type="submit" name="info_update" value="<?php _e('Update Options', 'English'); ?> &raquo;" />
		</div>
		<p>
		<?php _e('The 1 Bit Audio Player will be inserted automatically next to any links to MP3 files. See the <a href="http://1bit.markwheeler.net">1 Bit website</a> for documentation and updates.', 'English'); ?>
		</p>
		<fieldset name="foreColor">
		<h3><?php _e('Icon color', 'English'); ?></h3>
		<p>
		<label><input name="oneBitAutoColor" type="radio" value="1" class="tog" <?php if(!get_option('oneBitForeColor')) echo 'checked == "1" '; ?>/><?php _e('Set the player icon color automatically to match text links', 'English') ?></label>
		</p>
		<p>
		<label><input name="oneBitAutoColor" type="radio" value="0" class="tog" <?php if(get_option('oneBitForeColor')) echo 'checked == "1" '; ?>/><?php _e("Set the player icon color manually. ", 'English') ?></label>
		<label for="oneBitBackColor"><?php _e('Hex value for icon: #', 'English') ?></label>
		<input type="text" name="oneBitForeColor" id="oneBitForeColor" maxlength="6" size="10" value="<?php echo get_option('oneBitForeColor'); ?>" />
		</p>
		</fieldset>
		<fieldset name="backColor">
		<h3><?php _e('Background color', 'English'); ?></h3>
		<p>
		<label><input name="oneBitTransparent" type="radio" value="0" class="tog" <?php if(get_option('oneBitBackColor') != 'transparent') echo 'checked == "1" '; ?>/><?php _e("Use a solid background color (recommended). ", 'English') ?></label>
		<label for="oneBitBackColor"><?php _e('Hex value for background: #', 'English') ?></label>
		<input type="text" name="oneBitBackColor" id="oneBitBackColor" maxlength="6" size="10" value="<?php if(get_option('oneBitBackColor') != 'transparent') echo get_option('oneBitBackColor'); ?>" />
		</p>
		<p>
		<label><input name="oneBitTransparent" type="radio" value="1" class="tog" <?php if(get_option('oneBitBackColor') == 'transparent') echo 'checked == "1" '; ?>/><?php _e('Make the background transparent (can cause <a href="http://www.google.com/search?q=wmode+firefox">\'focus\' bugs</a> in Firefox)', 'English') ?></label>
		</p>
		</fieldset>
		<fieldset name="size">
		<h3><?php _e('Player size', 'English'); ?></h3>
		<p>
		<label><input name="oneBitAutoSize" type="radio" value="1" class="tog" <?php if(!get_option('oneBitSize')) echo 'checked == "1" '; ?>/><?php _e('Set 1 Bit\'s size automatically', 'English') ?></label>
		</p>
		<p>
		<label><input name="oneBitAutoSize" type="radio" value="0" class="tog" <?php if(get_option('oneBitSize')) echo 'checked == "1" '; ?>/><?php _e("Set a manual size. ", 'English') ?></label>
		<label for="oneBitSize"><?php _e('Size in pixels (the player is always square): ', 'English') ?></label>
		<input type="text" name="oneBitSize" id="oneBitSize" maxlength="3" size="3" value="<?php echo get_option('oneBitSize'); ?>" />
		</p>
		</fieldset>
		<fieldset name="position">
		<h3><?php _e('Player position', 'English'); ?></h3>
		<p>
		<label><input name="oneBitPosition" type="radio" value="after" class="tog" <?php if(get_option('oneBitPosition') == 'after') echo 'checked == "1" '; ?>/><?php _e('Position the 1 Bit player after MP3 links', 'English') ?></label>
		</p>
		<p>
		<label><input name="oneBitPosition" type="radio" value="before" class="tog" <?php if(get_option('oneBitPosition') == 'before') echo 'checked == "1" '; ?>/><?php _e("Position the 1 Bit player before MP3 links", 'English') ?></label>
		</p>
		</fieldset>
		<fieldset name="analytics">
		<h3><?php _e('Google Analytics', 'English'); ?></h3>
		<p>
		<?php _e("1 Bit Audio Player can generate 'hits' in your <a href=\"http://analytics.google.com\">Google Analytics</a> statistics every time an audio file is played. Ensure you have the <a href=\"http://www.google.com/support/googleanalytics/bin/answer.py?answer=75129&amp;topic=10981\">newer version</a> of Google Analytics installed and this option enabled and you will begin to see pages with a prefix of /1bit/ appearing amongst your regular page views.", 'English'); ?>
		</p>
		<p>
		<label><input name="oneBitAnalytics" type="radio" value="1" class="tog" <?php if(get_option('oneBitAnalytics') == 1) echo 'checked == "1" '; ?>/><?php _e('Enable Google Analytics support', 'English') ?></label>
		</p>
		<p>
		<label><input name="oneBitAnalytics" type="radio" value="0" class="tog" <?php if(get_option('oneBitAnalytics') == 0) echo 'checked == "1" '; ?>/><?php _e("Disable Google Analytics support", 'English') ?></label>
		</p>
		</fieldset>
		<fieldset name="selector">
		<h3><?php _e('Selector', 'English'); ?></h3>
		<p>
		<label for="oneBitSelector"><?php _e('CSS selector to apply 1 Bit to: ', 'English') ?></label>
		<input type="text" name="oneBitSelector" id="oneBitSelector" maxlength="50" value="<?php echo get_option('oneBitSelector'); ?>" />
		</p>
		<p>
		<?php _e('See <a href="http://en.wikipedia.org/wiki/Cascading_Style_Sheets#Selectors_.26_Pseudo_Classes">Wikipedia</a> for some basic reference. The default selector is simply \'a\'.', 'English'); ?>
		</p>
		</fieldset>
		<fieldset name="javascript">
		<h3><?php _e('Include SWFObject 1.5', 'English'); ?></h3>
		<p>
		<?php _e("The 1 Bit Audio Player plugin requires <a href=\"http://blog.deconcept.com/swfobject/\">SWFObject 1.5</a> in order to embed Flash. If you're already using SWFObject 1.5 on your site then you can tell 1 Bit to skip it's inclusion (and avoid having the script on your pages twice). Unfortunately 1 Bit is not yet compatible with SWFObject 2.0.", 'English'); ?>
		</p>
		<p>
		<label><input name="oneBitSWFObject" type="radio" value="1" class="tog" <?php if(get_option('oneBitSWFObject') == 1) echo 'checked == "1" '; ?>/><?php _e('Please add SWFObject 1.5 to my site', 'English') ?></label>
		</p>
		<p>
		<label><input name="oneBitSWFObject" type="radio" value="0" class="tog" <?php if(get_option('oneBitSWFObject') == 0) echo 'checked == "1" '; ?>/><?php _e("I already use SWFObject 1.5, don't add it again", 'English') ?></label>
		</p>
		</fieldset>
		<div class="submit">
		<input type="submit" name="info_update" value="<?php _e('Update Options', 'English'); ?> &raquo;" />
		</div>
	</form>
	</div>
	<?php
}

//set initial defaults
add_option('oneBitForeColor', '');
add_option('oneBitBackColor', 'FFFFFF');
add_option('oneBitSize', 10);
add_option('oneBitPosition', 'after');
add_option('oneBitAnalytics', 0);
add_option('oneBitSelector', 'a');
add_option('oneBitSWFObject', 1);

add_action('wp_head', 'oneBitJsHead');
add_action('admin_menu', 'oneBitOptions');
?>