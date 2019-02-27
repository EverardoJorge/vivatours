(function() {
    tinymce.PluginManager.add('UWPM_Tags', function( editor, url ) {
        //editor.on('init', function(args){EWD_UFAQ_Disable_Non_Premium();});
        if (typeof uwpm_insert_variables !== 'undefined' && uwpm_insert_variables == 'Yes') {
        	var Custom_Element_Sections = EWD_UWPM_Get_Custom_Element_Sections();
        	var Menu_Array = 
        		[{
        	    	text: 'User Tags',
        	    	value: 'user-tags',
        	    	onclick: function() {
					    var win = editor.windowManager.open( {
					        title: 'Insert User Tag',
					        body: [{
        	    				type: 'listbox',
        	    				name: 'selected_tag',
        	    				label: 'Field Name:',
					            'values': EWD_UWPM_Create_User_Tag_List()
					        }],
					        onsubmit: function( e ) {
					        	if (e.data.selected_tag != '') {editor.insertContent( '['+e.data.selected_tag+']');}
					        }
					    });
					}
				},
				{
        	    	text: 'Post Tags',
        	    	value: 'post-tags',
        	    	onclick: function() {
					    var win = editor.windowManager.open( {
					        title: 'Insert Post Tag',
					        body: [{
        	    				type: 'listbox',
        	    				name: 'selected_tag',
        	    				label: 'Field Name:',
					            'values': EWD_UWPM_Create_Post_Tag_List()
					        }],
					        onsubmit: function( e ) {
					        	if (e.data.selected_tag != '') {editor.insertContent( '['+e.data.selected_tag+']');}
					        }
					    });
					}
				},
				{
        	    	text: 'Custom Elements',
        	    	value: 'custom-elements',
        	    	onclick: function() {
					    var win = editor.windowManager.open( {
					        title: 'Insert Custom Element',
					        body: [{
        	    				type: 'listbox',
        	    				name: 'selected_tag',
        	    				label: 'Tag Name:',
					            'values': EWD_UWPM_Create_Custom_Element_List('uncategorized')
					        }],
					        onsubmit: function( e ) { console.log(e);
					            jQuery(uwpm_custom_elements).each(function(index, el) {console.log(el);
					            	if (false) {}
					            	else if (e.data.selected_tag != '' && e.data.selected_tag != -1) {editor.insertContent( '['+e.data.selected_tag+']');}
					            });
					        }
					    });
					}
				}]; console.log(Custom_Element_Sections);
			jQuery(Custom_Element_Sections).each(function(index, el) {
				var Menu_Element = 
					{
        	   			text: el.text,
        	   			value: el.value,
        	   			onclick: function() {
						    var win = editor.windowManager.open( {
						        title: 'Insert ' + el.text,
						        body: [{
        	   						type: 'listbox',
        	   						name: 'selected_tag',
        	   						label: 'Tag Name:',
						            'values': EWD_UWPM_Create_Custom_Element_List(el.value)
						        }],
						        onsubmit: function( e ) { console.log(e);
					            jQuery(uwpm_custom_elements).each(function(index, el) {console.log(el);
					            	if (el.slug == e.data.selected_tag) {
					            		var selected_tag = e.data.selected_tag;
					            		if (el.attributes.length !== 0) {
					            			var attributes = [];
					            			jQuery(el.attributes).each(function(index, el) {
					            				var attribute_values = EWD_UWPM_Parse_If_JSON(el.attribute_values);
					            				attributes.push({
					            					type: el.attribute_type,
					            					name: el.attribute_name,
					            					label: el.attribute_label,
					            					'values': attribute_values
					            				});
					            			});
					            			var win = editor.windowManager.open( {
					    					    title: 'Additional Attributes for ' + el.name,
					    					    body: attributes,
					    					    onsubmit: function( e ) {
					    					    	var attribute_string = '';
					    					    	jQuery(e.data).each(function(index, attribute) {console.log(attribute);
					    					    		jQuery.each(attribute, function(key, value) {
					    					    			attribute_string += key + "='" + value + "' ";
					    					    		});
					    					    	});
					    					    	if (selected_tag != '') {
					    					    		editor.insertContent( '['+selected_tag+' '+attribute_string+']');
					    					    	}
					    					    }
					    					});
					            		}
					            		else if (e.data.selected_tag != '' && e.data.selected_tag != -1) {editor.insertContent( '['+e.data.selected_tag+']');}
					            	}
					            });
					        }
						    });
						}
					}
				Menu_Array.push(Menu_Element); console.log(Menu_Element);
			});

        	editor.addButton( 'UWPM_Tags', {
        	    title: 'Email Variables',
        	    text: 'Email Variables',
        	    type: 'menubutton',
        	    icon: 'wp_code',
        	    menu: Menu_Array,
        	});
        }
    });
})();


function EWD_UWPM_Create_User_Tag_List() {
	var result = [];

	jQuery(uwpm_user_tags).each(function(index, el) {
		var d = {};
		d['text'] = el.name;
		d['value'] = el.slug;
		result.push(d);
	});

    return result;
}

function EWD_UWPM_Create_Post_Tag_List() {
	var result = [];

	jQuery(uwpm_post_tags).each(function(index, el) {
		var d = {};
		d['text'] = el.name;
		d['value'] = el.slug;
		result.push(d);
	});

    return result;
}

function EWD_UWPM_Create_Custom_Element_List(section) {
	var result = [];

	jQuery(uwpm_custom_elements).each(function(index, el) {
		console.log(section + ' - ' + el.section);
		if (el.section == section) {
			var d = {};
			d['text'] = el.name;
			d['value'] = el.slug;
			result.push(d);
		}
	});

    return result;
}

function EWD_UWPM_Get_Custom_Element_Sections() {
	var result = [];

	jQuery(uwpm_custom_element_sections).each(function(index, el) {
		var d = {};
		d['text'] = el.name;
		d['value'] = el.slug;
		result.push(d);
	});

    return result;
}

function EWD_UWPM_Parse_If_JSON(attribute_values) {
	var result = '';

	try {result = JSON.parse(attribute_values);}
	catch (e) {}

    return result;
}

jQuery(document).ready(function($) {
	console.log(EWD_UWPM_Create_Custom_Element_List('ewd_otp_uwpm_elements'));
});