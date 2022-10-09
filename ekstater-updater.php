<?php
/***********************************************
****																				****
****								UPDATER									****
****																				****
***********************************************/
define('EK_STARTER_T', 60);
define('EK_STARTER_U', 'https://wpgrade.wdev.pro/ekstarter/info.json');

function ekstater_plugin_info($res, $action, $args) 
{
  if ($action !== 'plugin_information') 
	{return false;}
	
	if(EK_STARTER_S !== $args->slug) 
	{return $res;}

  if(false == $remote = get_transient(EK_STARTER_S.'_upgrade'))
	{
		$remote = wp_remote_get(EK_STARTER_U, [
			'timeout' => 10,
			'headers' => ['Accept' => 'application/json']
    ]);

		if(!is_wp_error($remote) && isset($remote['response']['code']) && $remote['response']['code']==200 && !empty($remote['body'])) 
		{set_transient(EK_STARTER_S.'_upgrade', $remote, EK_STARTER_T);}
	}
	
	if (!is_wp_error($remote)) 
	{
		$remote = json_decode($remote['body']);

		$res = (object) [
			'name'						=> $remote->name,
			'slug'						=> $remote->slug,
			'version'					=> $remote->version,
			'tested'					=> $remote->tested,
			'requires'				=> $remote->requires,
			'author'					=> $remote->author,
			'author_profile'	=> $remote->author_profile,
			'download_link'		=> $remote->download_link,
			'trunk'						=> $remote->download_link,
			'last_updated'		=> $remote->last_updated,
			'sections'				=> [
				'description'			=> $remote->sections->description,
				'installation'		=> $remote->sections->installation,
				'changelog'				=> $remote->sections->changelog,
			],
			'banners'					=> [
				'low' 						=> $remote->banners->low,
				'high'						=> $remote->banners->high,
			],
		];
		

		return $res;
  }
	return false;
}


function ekstater_push_update($transient) 
{
	if (empty( $transient->checked )) 
	{return $transient;}

	if (false == $remote = get_transient(EK_STARTER_S.'_upgrade')) 
	{
		$remote = wp_remote_get(EK_STARTER_U, [
			'timeout' => 10,
			'headers' => [
				'Accept' => 'application/json'
			]
		]);

		if (!is_wp_error($remote) && isset($remote['response']['code']) && $remote['response']['code'] == 200 && !empty( $remote[ 'body' ] )) 
		{
			set_transient(EK_STARTER_S.'_upgrade', $remote, EK_STARTER_T ); // 6 hours cache
		}
	}

	if ($remote) 
	{
		$remote = json_decode($remote['body']);

		if ($remote && version_compare(EK_STARTER_V, $remote->version, '<') && version_compare($remote->requires, get_bloginfo('version'), '<')) 
		{
			$res = new stdClass();
			$res->slug = EK_STARTER_S;
			$res->plugin = EK_STARTER_S.'/'.EK_STARTER_S.'.php';
			$res->new_version = $remote->version;
			$res->tested = $remote->tested;
			$res->package = $remote->download_link;
			$res->icons = [
				'2x' 							=> $remote->icons->high,
				'1x' 							=> $remote->icons->low,
			];
			$transient->response[$res->plugin] = $res;
		}
	}
	return $transient;

}



/**
 * Cache the results to make update process fast
 */
function ekstater_after_update($upgrader_object, $options) 
{
	if ($options['action'] == 'update' && $options['type'] === 'plugin') 
	{
		delete_transient(EK_STARTER_S.'_upgrade');
	}

}

add_action('upgrader_process_complete', 'ekstater_after_update', 10, 2);
add_filter('site_transient_update_plugins', 'ekstater_push_update');
add_filter('plugins_api', 'ekstater_plugin_info', 20, 3);
