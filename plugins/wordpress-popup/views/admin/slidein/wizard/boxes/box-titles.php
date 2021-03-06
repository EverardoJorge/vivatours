<div id="wph-wizard-content-titles" class="wpmudev-box-content">

	<div class="wpmudev-box-left">

		<h4><strong><?php esc_attr_e( "Slide-in titles", Opt_In::TEXT_DOMAIN ); ?></strong></h4>

		<label class="wpmudev-helper"><?php esc_attr_e( "Titles are an optional part of the design", Opt_In::TEXT_DOMAIN ); ?></label>

	</div>

	<div class="wpmudev-box-right">

		<div class="wpmudev-switch-labeled">

			<div class="wpmudev-switch">

				<input id="wph-slidein-titles" class="toggle-checkbox" type="checkbox" data-attribute="has_title" {{_.checked(_.isTrue(has_title), true)}}>

				<label class="wpmudev-switch-design" for="wph-slidein-titles" aria-hidden="true"></label>

			</div>

			<label class="wpmudev-switch-label" for="wph-slidein-titles"><?php esc_attr_e( "Use titles", Opt_In::TEXT_DOMAIN ); ?></label>

		</div>

		<div id="wph-wizard-content-title-textboxes" class="wpmudev-box-gray {{ ( _.isFalse(has_title) ) ? 'wpmudev-hidden' : '' }}">

			<label><?php esc_attr_e( "Title (optional)", Opt_In::TEXT_DOMAIN ); ?></label>

			<input type="text" data-attribute="title" id="wph_slidein_new_title" class="wpmudev-input_text" name="title" placeholder="<?php esc_attr_e('Type title here...', Opt_In::TEXT_DOMAIN); ?>" value="{{title}}">

			<label><?php esc_attr_e( "Subtitle (optional)", Opt_In::TEXT_DOMAIN ); ?></label>

			<input type="text" data-attribute="sub_title" id="wph_slidein_new_subtitle" class="wpmudev-input_text" name="sub_title" placeholder="<?php esc_attr_e('Type subtitle here...', Opt_In::TEXT_DOMAIN); ?>" value="{{sub_title}}">

		</div>

	</div>

</div><?php // #wph-wizard-content-titles ?>
