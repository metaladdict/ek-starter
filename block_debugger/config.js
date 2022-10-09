var el = wp.element.createElement,
	Fragment = wp.element.Fragment,
	InspectorControls = wp.blockEditor.InspectorControls,
	PanelRow = wp.components.PanelRow,
	PanelBody = wp.components.PanelBody,
	ToggleControl = wp.components.ToggleControl,
	TextControl = wp.components.TextControl,
	registerBlockType = wp.blocks.registerBlockType;


	registerBlockType('ekstrtcat/frtdbgr', {
		title: 'Fenêtre Debugger',
		icon: 'images-alt',
		image: 'preview.png',
		description: 'EK Starter Debugger',
		category: 'ekstrtcat',
		
		attributes: {
			titre: 		{type: 'string', 	default:'Fenêtre de debug'},
			isdebug: 	{type: 'boolean', default:true}
	},
		

	edit: function( props ) {
		var attr = props.attributes;
		
		return (
			el(Fragment, null,
				el(InspectorControls, null,
					el(PanelBody, {title: 'Configuration', initialOpen: true},
						el(PanelRow, null, 
							el(ToggleControl,
								{
									label: 'Debug',
									checked: attr.isdebug,
									onChange: function(val) {props.setAttributes({isdebug:val});},
								}
							)
						),
						el(PanelRow, null, 
							el(TextControl, {
								label:'Titre', 
								value:attr.titre, 
								onChange: function(val) {props.setAttributes({titre:val});},
							})
						)
					)
				),

				el(ServerSideRender, {block:"ekstrtcat/frtdbgr", attributes: attr})
			)
		);
	}
});