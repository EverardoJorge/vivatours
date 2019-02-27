var el = wp.element.createElement,
	registerBlockType = wp.blocks.registerBlockType,
	ServerSideRender = wp.components.ServerSideRender,
	TextControl = wp.components.TextControl,
	SelectControl = wp.components.SelectControl,
	CheckboxControl = wp.components.CheckboxControl,
	InspectorControls = wp.editor.InspectorControls;

registerBlockType( 'ultimate-wp-mail/ewd-uwpm-subscription-interests-block', {
	title: 'Subscription Interests',
	icon: 'email-alt',
	category: 'ewd-uwpm-blocks',
	attributes: {
		display_interests: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					label: 'Display Interests',
					value: props.attributes.display_interests,
					onChange: ( value ) => { props.setAttributes( { display_interests: value } ); },
				} ),
			),
		);
		returnString.push( el( 'div', { class: 'ewd-uwpm-admin-block ewd-uwpm-admin-block-subscription-interests' }, 'Subscription Interests' ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

