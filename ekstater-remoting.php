<?php

/***********************************************
****																				****
****								EXTERNE									****
****																				****
***********************************************/
function ekstater_requeteOut($req)
{
	// Envoi de requête POST vers ekstrtr_serveur
	if(is_user_logged_in())
	{
		// Ajout du user_login si l'utilisateur est loggé
		$usr = wp_get_current_user();
		if(!empty($usr->data->user_login))
		{$req['user_login'] = $usr->data->user_login;}
	}

	$url = get_option('ekstrtr_serveur');
	$result = wp_remote_post($url, [
		'headers'			=> [
			'content-type'	=> 'application/json',
			'secuKod'				=>	'Bearer '.get_option('ekstrtr_token'),
		],
    'method'      =>	'POST',
    'timeout'     =>	15,
		//'body' 				=>	$req,
		'body' 				=>	json_encode($req), // si le serveur attend une requête formatée en JSON
		'sslverify'		=>	false,
	]);

	// Construction de l'objet de réponse
	if(is_array($result))
	{
		$retour = [
			'status' 		=>	'OK',
			'url' 			=>	$url,
			'reponse' 	=>	$result,
			'code'			=>	wp_remote_retrieve_response_code($result),
			'message'		=>	wp_remote_retrieve_response_message($result),
			'body'			=>	wp_remote_retrieve_body($result),
		];
	}
	else
	{
		$retour = [
			'status' 		=>	'erreur',
			'reponse' 	=>	$result,
			'code'			=>	$result->get_error_code(),
			'message'		=>	$result->get_error_messages(),
			'body'			=>	wp_remote_retrieve_body($result),
		];
	}

	return $retour;
}



/***********************************************
****																				****
****								INTERNE									****
****																				****
***********************************************/
function ekstater_requeteIn()
{
	// Reception d'une demande et envoi vers ekstater_requeteOut()
	$vars = [
		'ID'		=>	$_POST['reqid'],
		'word'	=>	$_POST['reqword'],
	];
		
	// Construction de la réponse
	if(is_numeric($vars['ID']) && is_string($vars['word']))
	{
		$retour = [
			'accept'	=>	true,
			'varin'		=>	$vars,
			'reponse' =>	ekstater_requeteOut($vars),
		];
	}
	else
	{
		$retour = [
			'accept'	=>	false,
			'varin'		=>	$vars,
			'message'	=>	'Requête invalide',
		];
	}
		
	wp_die(json_encode($retour));
}

add_action('wp_ajax_nopriv_ekreqin', 'ekstater_requeteIn');
add_action('wp_ajax_ekreqin', 'ekstater_requeteIn');



