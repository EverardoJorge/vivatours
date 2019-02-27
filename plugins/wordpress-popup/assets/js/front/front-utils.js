var Optin = Optin || {};
(function( $, doc ) {
	"use strict";

	$.each(['hustle_show', 'hustle_hide'], function (i, ev) {
		var el = $.fn[ev];
		$.fn[ev] = function () {
			this.trigger(ev);
			return el.apply(this, arguments);
		};
	});

	// posts/pages bounce in animation
	var $animation_elements = $('.inc_opt_inline_wrap');
	var $window = $(window);

	function check_if_in_view() {
		var window_height = $window.height();
		var window_top_position = $window.scrollTop();
		var window_bottom_position = (window_top_position + window_height);

		$.each($animation_elements, function() {
			var $element = $(this);
			var element_height = $element.outerHeight();
			var element_top_position = $element.offset().top;
			var element_bottom_position = (element_top_position + element_height);

			//check to see if this current container is within viewport
			if ((element_bottom_position >= window_top_position) &&
				(element_top_position <= window_bottom_position)) {
				$element.addClass('in-view');
			} else {
				$element.removeClass('in-view');
			}
		});
	}

	$(doc).on("hustle:module:displayed", _.debounce(on_display, 100, false));

	$window.on('scroll resize', _.debounce( check_if_in_view, 100, false ) );
	$window.trigger('scroll');
	
	$(document).on('blur', '.hustle-modal-optin_form input, .hustle-modal-optin_form textarea, .hustle-modal-optin_form select', function(){
	    var $this = $(this);
	    if($this.is(':input[type=button], :input[type=submit], :input[type=reset]')) return;
	    if($this.val().trim() !== '') {
		    $this.closest('.hustle-modal-optin_field').addClass('hustle-input-filled');
		} else{
			$this.closest('.hustle-modal-optin_field').removeClass('hustle-input-filled');
		}
	});

	$(document).on('focus', '.wpoi-optin input.required', function(){
		$(this).next('label').find('i.wphi-font').removeClass('i-error');
	});
	
	/**
	 * Callback after all modules were displayed on the front end
	*/
	function on_display(e, data){
		// console.log('Module displayed!');
		// console.log(data);
		
		// start your custom js here:
	}
	
	Optin.apply_custom_size = function( data, $this ) {
		var content_data = data.content,
			design_data = data.design,
			style = design_data.style,
			layout = design_data.form_layout;
			
		// modal parts
		var $modal = $this.find('.hustle-modal'),
			$modal_body = $modal.find('.hustle-modal-body');
	
		// If the parent container is embed and small, style accordingly.
		$window.on('resize', embed_size);
		$window.ready(embed_size);

		function embed_size() {
			if (
				data.module_type === 'embedded'
				&& $modal_body.width() < 500
				&& Math.max( document.documentElement.clientWidth, window.innerWidth || 0 ) > 783
			) {
				$modal_body.addClass('hustle-size-small');
			} else {
				$modal_body.removeClass('hustle-size-small');
			}
		}

		$modal.find( '.hustle-modal-success' ).css({
			'height': $modal.find('.hustle-modal-body').height() + 'px'
		});

		// Set correct height for success modal
		$window.on('resize', success_modal);

		function success_modal() {
			$modal.find( '.hustle-modal-success' ).css({
				'height': $modal.find('.hustle-modal-body').height() + 'px'
			});
		}

		if ( $modal.find( '.hustle-modal-success_message' ).html() && $modal.find( '.hustle-modal-success_message' ).html().length == 0 ) {
			$modal.find( '.hustle-modal-success_message' ).remove();
		}

		// custom size
		if ( _.isTrue( design_data.customize_size ) ) {
			$modal.css({
				'width': design_data.custom_width + 'px',
				'max-width': 'unset',
				'height': design_data.custom_height +  'px',
				'max-height': 'unset',
			});
		}
	}
	
	
}(jQuery, document));
