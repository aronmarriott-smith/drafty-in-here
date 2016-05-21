<?php
/**
 * This script contains the view with minimal php
 */
?>
<div class="wrap">
	<h2><?php printf( esc_html__( '%s Settings', 'drafty-in-here' ), 'Drafty In Here' ); ?></h2>
	<form action="options.php" method="post">
		
		<?php 
		settings_fields('draftySettingsPage');
		do_settings_sections('draftySettingsPage');
		submit_button();
		?>

	</form>
</div>