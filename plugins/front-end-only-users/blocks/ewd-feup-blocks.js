var el = wp.element.createElement,
	registerBlockType = wp.blocks.registerBlockType,
	ServerSideRender = wp.components.ServerSideRender,
	TextControl = wp.components.TextControl,
	SelectControl = wp.components.SelectControl,
	InspectorControls = wp.editor.InspectorControls;

registerBlockType( 'front-end-only-users/ewd-feup-register-form-block', {
	title: 'Display Registration Form',
	icon: 'admin-users',
	category: 'ewd-feup-blocks',
	attributes: {
		redirect_page: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					label: 'Redirect Page URL',
					value: props.attributes.redirect_page,
					onChange: ( value ) => { props.setAttributes( { redirect_page: value } ); },
				} ),
			),
		);
		returnString.push( el( 'div', { class: 'ewd-feup-admin-block ewd-feup-admin-block-register-form' }, 'Registration Form Block' ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'front-end-only-users/ewd-feup-login-form-block', {
	title: 'Display Login Form',
	icon: 'admin-users',
	category: 'ewd-feup-blocks',
	attributes: {
		redirect_page: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					label: 'Redirect Page URL',
					value: props.attributes.redirect_page,
					onChange: ( value ) => { props.setAttributes( { redirect_page: value } ); },
				} ),
			),
		);
		returnString.push( el( 'div', { class: 'ewd-feup-admin-block ewd-feup-admin-block-login-form' }, 'Login Form Block' ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'front-end-only-users/ewd-feup-logout-block', {
	title: 'Logout',
	icon: 'admin-users',
	category: 'ewd-feup-blocks',
	attributes: {
		redirect_page: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					label: 'Redirect Page URL',
					value: props.attributes.redirect_page,
					onChange: ( value ) => { props.setAttributes( { redirect_page: value } ); },
				} ),
			),
		);
		returnString.push( el( 'div', { class: 'ewd-feup-admin-block ewd-feup-admin-block-logout' }, 'Logout Block' ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'front-end-only-users/ewd-feup-user-search-block', {
	title: 'Display User Search',
	icon: 'admin-users',
	category: 'ewd-feup-blocks',
	attributes: {
		login_necessary: { type: 'string' },
		login_page: { type: 'string' },
		search_fields: { type: 'string' },
		display_field: { type: 'string' },
		search_logic: { type: 'string' },
		user_profile_page: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( SelectControl, {
					label: 'Login Required?',
					value: props.attributes.login_necessary,
					options: [ {value: 'Yes', label: 'Yes'}, {value: 'No', label: 'No'} ],
					onChange: ( value ) => { props.setAttributes( { login_necessary: value } ); },
				} ),
				el( TextControl, {
					label: 'Login Page URL',
					value: props.attributes.login_page,
					onChange: ( value ) => { props.setAttributes( { login_page: value } ); },
				} ),
				el( TextControl, {
					label: 'Search Fields (comma-separated list)',
					value: props.attributes.search_fields,
					onChange: ( value ) => { props.setAttributes( { search_fields: value } ); },
				} ),
				el( TextControl, {
					label: 'Display Fields (comma-separated list)',
					value: props.attributes.display_field,
					onChange: ( value ) => { props.setAttributes( { display_field: value } ); },
				} ),
				el( TextControl, {
					label: 'Profile Page URL',
					value: props.attributes.user_profile_page,
					onChange: ( value ) => { props.setAttributes( { user_profile_page: value } ); },
				} ),
				el( SelectControl, {
					label: 'Search Logic',
					value: props.attributes.search_logic,
					options: [ {value: 'OR', label: 'OR'}, {value: 'AND', label: 'AND'} ],
					onChange: ( value ) => { props.setAttributes( { search_logic: value } ); },
				} ),
			),
		);
		returnString.push( el( 'div', { class: 'ewd-feup-admin-block ewd-feup-admin-block-user-search' }, 'Display User Search' ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'front-end-only-users/ewd-feup-user-list-block', {
	title: 'Display User List',
	icon: 'admin-users',
	category: 'ewd-feup-blocks',
	attributes: {
		login_necessary: { type: 'string' },
		login_page: { type: 'string' },
		field_name: { type: 'string' },
		field_value: { type: 'string' },
		display_field: { type: 'string' },
		user_profile_page: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( SelectControl, {
					label: 'Login Required?',
					value: props.attributes.login_necessary,
					options: [ {value: 'Yes', label: 'Yes'}, {value: 'No', label: 'No'} ],
					onChange: ( value ) => { props.setAttributes( { login_necessary: value } ); },
				} ),
				el( TextControl, {
					label: 'Login Page URL',
					value: props.attributes.login_page,
					onChange: ( value ) => { props.setAttributes( { login_page: value } ); },
				} ),
				el( TextControl, {
					label: 'Name of Field (to list users from)',
					value: props.attributes.field_name,
					onChange: ( value ) => { props.setAttributes( { field_name: value } ); },
				} ),
				el( TextControl, {
					label: 'Value of that Field (to list users from)',
					value: props.attributes.field_value,
					onChange: ( value ) => { props.setAttributes( { field_value: value } ); },
				} ),
				el( TextControl, {
					label: 'Display Field',
					value: props.attributes.display_field,
					onChange: ( value ) => { props.setAttributes( { display_field: value } ); },
				} ),
				el( TextControl, {
					label: 'Profile Page URL',
					value: props.attributes.user_profile_page,
					onChange: ( value ) => { props.setAttributes( { user_profile_page: value } ); },
				} ),
			),
		);
		returnString.push( el( 'div', { class: 'ewd-feup-admin-block ewd-feup-admin-block-user-list' }, 'Display User List' ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'front-end-only-users/ewd-feup-edit-account-block', {
	title: 'Edit Account',
	icon: 'admin-users',
	category: 'ewd-feup-blocks',
	attributes: {
		login_page: { type: 'string' },
		redirect_page: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					label: 'Login Page URL',
					value: props.attributes.login_page,
					onChange: ( value ) => { props.setAttributes( { login_page: value } ); },
				} ),
				el( TextControl, {
					label: 'Redirect Page URL',
					value: props.attributes.redirect_page,
					onChange: ( value ) => { props.setAttributes( { redirect_page: value } ); },
				} ),
			),
		);
		returnString.push( el( 'div', { class: 'ewd-feup-admin-block ewd-feup-admin-block-edit-account' }, 'Edit Account Block' ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'front-end-only-users/ewd-feup-forgot-password-block', {
	title: 'Forgot Password',
	icon: 'admin-users',
	category: 'ewd-feup-blocks',
	attributes: {
		reset_email_url: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					label: 'Reset Email URL (page with the confirm-forgot-password shortcode)',
					value: props.attributes.reset_email_url,
					onChange: ( value ) => { props.setAttributes( { reset_email_url: value } ); },
				} ),
			),
		);
		returnString.push( el( 'div', { class: 'ewd-feup-admin-block ewd-feup-admin-block-forgot-password' }, 'Forgot Password Block' ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );

registerBlockType( 'front-end-only-users/ewd-feup-confirm-forgot-password-block', {
	title: 'Confirm Forgot Password',
	icon: 'admin-users',
	category: 'ewd-feup-blocks',
	attributes: {
		login_page: { type: 'string' },
		redirect_page: { type: 'string' },
	},

	edit: function( props ) {
		var returnString = [];
		returnString.push(
			el( InspectorControls, {},
				el( TextControl, {
					label: 'Redirect Page URL',
					value: props.attributes.redirect_page,
					onChange: ( value ) => { props.setAttributes( { redirect_page: value } ); },
				} ),
				el( TextControl, {
					label: 'Login Page URL',
					value: props.attributes.login_page,
					onChange: ( value ) => { props.setAttributes( { login_page: value } ); },
				} ),
			),
		);
		returnString.push( el( 'div', { class: 'ewd-feup-admin-block ewd-feup-admin-block-confirm-forgot-password' }, 'Confirm Forgot Password Block' ) );
		return returnString;
	},

	save: function() {
		return null;
	},
} );




