<?php
defined( 'ABSPATH' ) || exit;



function ekstater_registerblock_frtdbgr() 
{
	if (!function_exists('register_block_type')) {return;}

	wp_register_script(
		'frtdbgr-blk',
		plugin_dir_url(__FILE__).'config.js',
		['wp-blocks', 'wp-element', 'wp-editor'],
		filemtime(plugin_dir_path(__FILE__).'config.js')
	);

	wp_register_style(
		'frtdbgr-stl',
		plugin_dir_url(__FILE__).'editor.css',
		['wp-admin'],
		filemtime(plugin_dir_path(__FILE__).'editor.css')
	);
	
	register_block_type(
		'ekstrtcat/frtdbgr',
		[
			'editor_style' 		=> 'frtdbgr-stl',
			'editor_script' 	=> 'frtdbgr-blk',
			'attributes' => [
				'titre' 		=> [
					'type'			=>	'text',
					'default'		=>	'Fenêtre de debug'
				],
				'isdebug' 	=> [
					'type'			=> 'boolean', 
					'default'		=>	true
				],
			],
			'render_callback' => 'ekstater_render_frtdbgr',
		]
	);
}
add_action('init', 'ekstater_registerblock_frtdbgr');


function ekstater_render_frtdbgr($atts)
{
	$atts = shortcode_atts([
			'titre'		=>	'Fenêtre de debug',
			'isdebug'	=>	true,
		], $atts
  );

	$html = '<div class="ekstrtwin" data-sndto="'.admin_url('admin-ajax.php').'">';
	$html.= '<h2>'.$atts['titre'].'</h2>';
	$html.= ekstater_debugHTML($atts['isdebug']);
	$html.= '</div>';
	
	return $html;
}

