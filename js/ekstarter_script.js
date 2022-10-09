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
		console.log('ok');
	}
	else
	{
		console.log('error');
	}

  console.log(r);
}


