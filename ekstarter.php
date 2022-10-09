<?php
/**
 * Plugin Name: 				EK Starter
 * Description: 				Starter de plugin avec appel en POST
 * Requires at least: 	5.8
 * Requires PHP:      	7.0
 * Version: 						1.0.1
 * Author: 							Erwan Kuznik
 * Text Domain:       	ekstarter
 */

define('EK_STARTER_V', '1.0.1');
define('EK_STARTER_S', 'ekstarter');


include_once(plugin_dir_path(__FILE__).'/ekstater-nav.php');
include_once(plugin_dir_path(__FILE__).'/ekstater-remoting.php');

// Block gutenberg
include_once(plugin_dir_path(__FILE__).'/block_debugger/config.php');

/***********************************************
****																				****
****								SETTINGS								****
****																				****
***********************************************/
function ekstater_settings()
{
	// Paramètre du plugin
	register_setting('ekstater_settings', 'ekstrtr_serveur', ['type'=>'text', 'description'=>'Serveur de destination']);
	register_setting('ekstater_settings', 'ekstrtr_token', ['type'=>'text', 'description'=>'Clé à passer en header']);
	
	register_setting('ekstater_settings', 'ekstrtr_titre', ['type'=>'text', 'description'=>'Titre de la fenêtre de front']);
}

add_action('admin_init', 'ekstater_settings' );



function ekstater_gutenblock_cat($categories, $post) 
{
	// ajoute la categorie EKStarter à gutenberg (editeur de wordpress)
	return array_merge(
		[[
			'slug'	=> 'ekstrtcat',
			'title'	=> 'EK Starter',
		]],
	$categories
	);
}
add_filter('block_categories', 'ekstater_gutenblock_cat', 10, 2);


/***********************************************
****																				****
****								JS & CSS								****
****																				****
***********************************************/

function ekstater_adminscript() 
{
	// Scripts et styles à charger systématiquement dans le backoffice
	wp_register_style(EK_STARTER_S.'_css', plugin_dir_url(__DIR__).EK_STARTER_S.'/css/ekstarter.css', false, EK_STARTER_V);
	wp_enqueue_style(EK_STARTER_S.'_css' );
	
}
add_action('admin_enqueue_scripts', 'ekstater_adminscript');


function ekstater_frontscript() 
{
	// Scripts et styles à charger systématiquement en front
	wp_register_style(EK_STARTER_S.'_css', plugin_dir_url(__DIR__).EK_STARTER_S.'/css/ekstarter.css', false, EK_STARTER_V);
	wp_enqueue_style(EK_STARTER_S.'_css' );
	
}

add_action('wp_enqueue_scripts', 'ekstater_frontscript' );




/***********************************************
****																				****
****									HTML									****
****																				****
***********************************************/
function ekstater_debugHTML($debug=true)
{
	//ajout JS et CSS pour les requetes, uniquement si on ajoute la fenêtre 
	if($debug)
	{wp_enqueue_script(EK_STARTER_S.'_js', plugin_dir_url(__DIR__).EK_STARTER_S.'/js/ekstarter_debug.js', ['jquery'], EK_STARTER_V, true);}
	else
	{wp_enqueue_script(EK_STARTER_S.'_js', plugin_dir_url(__DIR__).EK_STARTER_S.'/js/ekstarter_script.js', ['jquery'], EK_STARTER_V, true);}

	wp_add_inline_script(EK_STARTER_S.'_js', "var ajaxurl= \"".admin_url('admin-ajax.php')."\";jQuery(document).ready(ekstater_init);", 'after');
	
	// code HTML du testeur de requetes
	$out =	'<div class="zonereq">';
	$out.=	'	<table>';
	$out.=	'		<tr>';
	$out.=	'			<td><label for="req_word">Word : </label></td>';
	$out.=	'			<td><input data-fldname="req_word" type="text" id="req_word" size="97"></td>';
	$out.=	'		</tr>';
	$out.=	'		<tr>';
	$out.=	'			<td><label for="req_id">ID : </label></td>';
	$out.=	'			<td><input data-fldname="req_id" type="number" id="req_id" size="3" value="1" min="1" max="100"></td>';
	$out.=	'		</tr>';
	$out.=	'	</table>';
	$out.=	'	<a href="#" class="ekstater_ajaxsend button">Envoyer</a>';
	$out.=	'</div>';

	if($debug)
	{
		$out.=	'<div class="zonerep">';
		$out.=	'	<table class="znret">';
		$out.=	'		<tr>';
		$out.=	'			<th>URL</th>';
		$out.=	'			<td class="tar" colspan="3"><div id="ekstater_urlreq">&nbsp;</div></td>';
		$out.=	'		</tr>';
		$out.=	'		<tr>';
		$out.=	'			<td colspan="4"><div id="ekstater_requet">_</div></td>';
		$out.=	'		</tr>';
		$out.=	'		<tr>';
		$out.=	'			<th colspan="4">Réponse</th>';
		$out.=	'		</tr>';
		$out.=	'		<tr>';
		$out.=	'			<td><div id="ekstater_kodret">_</div></td>';
		$out.=	'			<td class="tar" colspan="3"><div id="ekstater_messag">&nbsp;</div></td>';
		$out.=	'		</tr>';
		$out.=	'		<tr>';
		$out.=	'			<td colspan="4"><div id="ekstater_retour">_</div></td>';
		$out.=	'		</tr>';
		$out.=	'	</table>';
		$out.=	'</div>';
	}


	return $out;
}

