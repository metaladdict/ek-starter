$ = jQuery;

function ekstater_init()
{
	$('.ekstater_ajaxsend').click(ekstater_envoyer);
}


function ekstater_envoyer()
{
	var dt = {
    'action'    : 'ekreqin',
		'reqword'		:	$('#req_word').val(),
		'reqid'			:	$('#req_id').val(),
	};

	$.post(ajaxurl, dt, ekstater_traiter_retour, "JSON");

	return false;
}


function ekstater_traiter_retour(r) 
{
	if(r.accept)
	{
		$('#ekstater_urlreq').html(r.reponse.url);
		$('#ekstater_messag').html(r.reponse.message);
		$('#ekstater_kodret').html(r.reponse.code);

		if(ekstater_isJSON(r.reponse.body))
		{$('#ekstater_retour').html(JSON.stringify(JSON.parse(r.reponse.body),null,'\t')+'</textarea>');}
		else if(Array.isArray(r.reponse.body) || typeof r.reponse.body === 'object')
		{$('#ekstater_retour').html(JSON.stringify(r.reponse.body,null,'\t')+'</textarea>');}
		else
		{$('#ekstater_retour').html('<textarea>'+r.reponse.body+'</textarea>');}
	}
	else
	{
		$('#ekstater_urlreq').html('-');
		$('#ekstater_messag').html(r.message);
		$('#ekstater_retour').html('-');
		$('#ekstater_kodret').html('-');
	}

  $('#ekstater_requet').html(JSON.stringify(r.varin,null,'\t'));
}



function ekstater_isJSON(str) 
{
	try 
	{return (JSON.parse(str) && !!str);} 
	catch (e) 
	{return false;}
}