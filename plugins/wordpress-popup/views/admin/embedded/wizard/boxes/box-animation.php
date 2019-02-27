<div id="wph-wizard-settings-animation" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Animation settings", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

        <label class="wpmudev-helper"><?php esc_attr_e( "Choose how you want your embed to animate on entrance", Opt_In::TEXT_DOMAIN ); ?></label>

	</div>

	<div class="wpmudev-box-right">

		<label><?php esc_attr_e( "Embed entrance animation", Opt_In::TEXT_DOMAIN ); ?></label>

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

    </div>

</div><?php // #wph-wizard-settings-animation ?>
