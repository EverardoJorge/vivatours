<div id="wph-wizard-settings-animation" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Animation settings", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

        <label class="wpmudev-helper"><?php esc_attr_e( "Choose how you want your pop-up to animate on entrance & exit", Opt_In::TEXT_DOMAIN ); ?></label>

	</div>

	<div class="wpmudev-box-right">

		<label><?php esc_attr_e( "Pop-up entrance animation", Opt_In::TEXT_DOMAIN ); ?></label>

        <select class="wpmudev-select" data-attribute="animation_in">
            <option value="no_animation" {{ ( 'no_animation' === animation_in || '' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "No Animation", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="bounceIn" {{ ( 'bounceIn' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Bounce In", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="bounceInUp" {{ ( 'bounceInUp' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Bounce In Up", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="bounceInRight" {{ ( 'bounceInRight' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Bounce In Right", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="bounceInDown" {{ ( 'bounceInDown' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Bounce In Down", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="bounceInLeft" {{ ( 'bounceInLeft' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Bounce In Left", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="fadeIn" {{ ( 'fadeIn' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Fade In", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="fadeInUp" {{ ( 'fadeInUp' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Fade In Up", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="fadeInRight" {{ ( 'fadeInRight' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Fade In Right", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="fadeInDown" {{ ( 'fadeInDown' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Fade In Down", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="fadeInLeft" {{ ( 'fadeInLeft' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Fade In Left", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="rotateIn" {{ ( 'rotateIn' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Rotate In", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="rotateInDownLeft" {{ ( 'rotateInDownLeft' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Rotate In Down Left", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="rotateInDownRight" {{ ( 'rotateInDownRight' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Rotate In Down Right", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="rotateInUpLeft" {{ ( 'rotateInUpLeft' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Rotate In Up Left", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="rotateInUpRight" {{ ( 'rotateInUpRight' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Rotate In Up Right", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="slideInUp" {{ ( 'slideInUp' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Slide In Up", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="slideInRight" {{ ( 'slideInRight' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Slide In Right", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="slideInDown" {{ ( 'slideInDown' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Slide In Down", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="slideInLeft" {{ ( 'slideInLeft' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Slide In Left", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="zoomIn" {{ ( 'zoomIn' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Zoom In", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="zoomInUp" {{ ( 'zoomInUp' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Zoom In Up", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="zoomInRight" {{ ( 'zoomInRight' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Zoom In Right", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="zoomInDown" {{ ( 'zoomInDown' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Zoom In Down", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="zoomInLeft" {{ ( 'zoomInLeft' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Zoom In Left", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="rollIn" {{ ( 'rollIn' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Roll In", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="lightSpeedIn" {{ ( 'lightSpeedIn' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Light Speed In", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="newspaperIn" {{ ( 'newspaperIn' === animation_in ) ? 'selected' : '' }}><?php esc_attr_e( "Newspaper In", Opt_In::TEXT_DOMAIN ); ?></option>
        </select>

        <label><?php esc_attr_e( "Pop-up exit animation", Opt_In::TEXT_DOMAIN ); ?></label>

        <select class="wpmudev-select" data-attribute="animation_out">
            <option value="no_animation" {{ ( 'no_animation' === animation_out || '' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "No Animation", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="bounceOut" {{ ( 'bounceOut' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Bounce Out", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="bounceOutUp" {{ ( 'bounceOutUp' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Bounce Out Up", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="bounceOutRight" {{ ( 'bounceOutRight' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Bounce Out Right", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="bounceOutDown" {{ ( 'bounceOutDown' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Bounce Out Down", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="bounceOutLeft" {{ ( 'bounceOutLeft' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Bounce Out Left", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="fadeOut" {{ ( 'fadeOut' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Fade Out", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="fadeOutUp" {{ ( 'fadeOutUp' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Fade Out Up", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="fadeOutRight" {{ ( 'fadeOutRight' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Fade Out Right", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="fadeOutDown" {{ ( 'fadeOutDown' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Fade Out Down", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="fadeOutLeft" {{ ( 'fadeOutLeft' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Fade Out Left", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="rotateOut" {{ ( 'rotateOut' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Rotate Out", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="rotateOutUpLeft" {{ ( 'rotateOutUpLeft' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Rotate Out Up Left", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="rotateOutUpRight" {{ ( 'rotateOutUpRight' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Rotate Out Up Right", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="rotateOutDownLeft" {{ ( 'rotateOutDownLeft' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Rotate Out Down Left", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="rotateOutDownRight" {{ ( 'rotateOutDownRight' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Rotate Out Down Right", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="slideOutUp" {{ ( 'slideOutUp' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Slide Out Up", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="slideOutRight" {{ ( 'slideOutRight' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Slide Out Right", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="slideOutDown" {{ ( 'slideOutDown' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Slide Out Down", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="slideOutLeft" {{ ( 'slideOutLeft' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Slide Out Left", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="zoomOut" {{ ( 'zoomOut' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Zoom Out", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="zoomOutUp" {{ ( 'zoomOutUp' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Zoom Out Up", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="zoomOutRight" {{ ( 'zoomOutRight' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Zoom Out Right", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="zoomOutDown" {{ ( 'zoomOutDown' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Zoom Out Down", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="zoomOutLeft" {{ ( 'zoomOutLeft' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Zoom Out Left", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="rollOut" {{ ( 'rollOut' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Roll Out", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="lightSpeedOut" {{ ( 'lightSpeedOut' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Light Speed Out", Opt_In::TEXT_DOMAIN ); ?></option>
            <option value="newspaperOut" {{ ( 'newspaperOut' === animation_out ) ? 'selected' : '' }}><?php esc_attr_e( "Newspaper Out", Opt_In::TEXT_DOMAIN ); ?></option>
        </select>

    </div>

</div><?php // #wph-wizard-settings-animation ?>
