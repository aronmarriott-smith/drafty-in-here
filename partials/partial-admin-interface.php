<?php
/**
 * This script contains the view with minimal php
 */
?>
<div class="wrap">
	<h2><?php _e( 'Drafty In Here Settings', 'drafty-in-here' ); ?></h2>
	<form action="options.php" method="post">
		
		<?php 
		settings_fields('draftySettingsPage');
		do_settings_sections('draftySettingsPage');
		submit_button();
		?>

	</form>
</div>