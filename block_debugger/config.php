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

/*
function shrt_frontchart($atts)
{
	return ekstater_render_frtdbgr($atts);
}
function shrt_backchart($atts)
{
	wp_register_style('ekhmp_frtdbgr_css_back', plugin_dir_url(__DIR__).'/chatbot_back.css', false, EK_STARTER_V);
	wp_enqueue_style('ekhmp_frtdbgr_css_back' );
	wp_enqueue_script('ekhmp_frtdbgr_js_back', plugin_dir_url(__DIR__).'chatbot_back.js', array('jquery'), EK_STARTER_V, true );
	wp_add_inline_script('ekhmp_frtdbgr_js_back', 'var dtOpts = '.json_encode($chatbotChoices).';var jxnnc_b22 = "'.wp_create_nonce(get_option('ekstrtr_cle')).'";var ajaxurl= "'.admin_url('admin-ajax.php').'";ekstater_init_back();', 'after');
	
	$out = '<div class="chatwin">';
	$out.= ekstater_testpage();
	$out.= '</div>';

	return $out;
}

add_shortcode('frontChat','shrt_frontchart');
add_shortcode('testBot','shrt_backchart');
*/