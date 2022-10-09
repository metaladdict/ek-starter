<?php

/***********************************************
****																				****
****							MENU BACK									****
****																				****
***********************************************/
function ekstater_menu() 
{
	add_menu_page('ekstrtr', 'EK Starter', 'administrator', 'ekstrtr-settings', 'ekstater_configuration', 'dashicons-rss');
	add_submenu_page('ekstrtr-settings', 'Test', 'Testeur', 'administrator', 'ekstrtr-test', 'ekstater_testpage');
  	
}
add_action('admin_menu', 'ekstater_menu');


/***********************************************
****																				****
****							PAGES BACK								****
****																				****
***********************************************/
function ekstater_configuration()
{
	// Page de config du plugin
	?>
	<h1>Configuration du plugin</h1>
	<form method="post" action="options.php">

	<?php 
		settings_fields('ekstater_settings');
		do_settings_sections('ekstater_settings');
	?>
	<label for="ekstrtr_serveur">Serveur distant : </label><br>
	<input name="ekstrtr_serveur" value="<?php echo get_option('ekstrtr_serveur');?>" type="text" id="ekstrtr_serveur_fld" size="50"><br>
	<br>
	<label for="ekstrtr_token">Token : </label><br>
	<input name="ekstrtr_token" value="<?php echo get_option('ekstrtr_token');?>" type="text" id="ekstrtr_team_fld" size="50"><br>
	<br>

	<?php
		submit_button(); 
	?>
	</form>
	<?php
}


function ekstater_testpage()
{
  // Page de test du bakc office
	echo ekstater_debugHTML();
}
