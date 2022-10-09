<?php
/***********************************************
****																				****
****								UPDATER									****
****																				****
***********************************************/

function ekhmp_chatbot_plugin_info( $res, $action, $args ) 
{
  if ($action !== 'plugin_information') 
	{return false;}
	
	if(EK_STARTER_S !== $args->slug) 
	{return $res;}

  if(false == $remote = get_transient('ekhmp_chatbot_upgrade'))
	{
		// info.json is the file with the actual information about plug-in on your server
		$remote = wp_remote_get(EK_UPDT_CHNL, [
			'timeout' => 10,
			'headers' => ['Accept' => 'application/json']
    ]);

		if(!is_wp_error($remote) && isset($remote['response']['code']) && $remote['response']['code']==200 && !empty($remote['body'])) 
		{set_transient('ekhmp_chatbot_upgrade', $remote, 300);}
	}
	
	if (!is_wp_error( $remote )) 
	{
		$remote = json_decode( $remote[ 'body' ] );

		$res = new stdClass();
		$res->name = $remote->name;
		$res->slug = $remote->slug;
		$res->version = $remote->version;
		$res->tested = $remote->tested;
		$res->requires = $remote->requires;
		$res->author = $remote->author;
		$res->author_profile = $remote->author_homepage;
		$res->download_link = $remote->download_link;
		$res->trunk = $remote->download_link;
		$res->last_updated = $remote->last_updated;
		$res->sections = array(
				'description' => $remote->sections->description,
				'installation' => $remote->sections->installation,
				'changelog' => $remote->sections->changelog,
		);
		$res->banners = array(
				'low' => $remote->banners->low,
				'high' => $remote->banners->high,
		);

		return $res;
  }
	return false;
}


function ekhmp_chatbot_push_update($transient) 
{
	if (empty( $transient->checked )) 
	{return $transient;}

	if (false == $remote = get_transient('ekhmp_chatbot_upgrade')) 
	{
		$remote = wp_remote_get(EK_UPDT_CHNL, [
			'timeout' => 10,
			'headers' => [
				'Accept' => 'application/json'
			]
		]);

		if (!is_wp_error($remote) && isset($remote['response']['code']) && $remote['response']['code'] == 200 && !empty( $remote[ 'body' ] )) 
		{
			set_transient('ekhmp_chatbot_upgrade', $remote, 300 ); // 6 hours cache
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
			$transient->response[$res->plugin] = $res;
		}
	}
	return $transient;

}



/**
 * Cache the results to make update process fast
 */
function ekhmp_chatbot_after_update($upgrader_object, $options) 
{
	if ($options['action'] == 'update' && $options['type'] === 'plugin') 
	{
		delete_transient('ekhmp_chatbot_upgrade');
	}

}

add_action('upgrader_process_complete', 'ekhmp_chatbot_after_update', 10, 2);
add_filter('site_transient_update_plugins', 'ekhmp_chatbot_push_update');
add_filter('plugins_api', 'ekhmp_chatbot_plugin_info', 20, 3);
