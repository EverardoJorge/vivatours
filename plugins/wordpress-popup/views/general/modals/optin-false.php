<?php
$close_icon = '<svg width="150" height="150" viewBox="0 0 150 150" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" class="hustle-icon hustle-i_close"><path d="M91.667 75L150 16.667 133.333 0 75 58.333 16.667 0 0 16.667 58.333 75 0 133.333 16.667 150 75 91.667 133.333 150 150 133.333 91.667 75z" fill-rule="evenodd"/></svg>';
?>

<script id="wpmudev-hustle-modal-without-optin-tpl" type="text/template">

<div class="hustle-modal hustle-modal-{{ ( 'simple' === design.style || 'minimal' === design.style ) ? design.style : 'cabriolet' }}<# if ( ( _.isTrue( content.use_feature_image ) && 'none' === design.feature_image_fit ) && _.isFalse( content.has_title ) && '' === content.main_content && _.isFalse( content.show_cta ) ) { #> hustle-modal-image_only<# } #> {{ ( ( ( '' !== settings.animation_in && 'no_animation' !== settings.animation_in ) || ( '' !== settings.animation_out && 'no_animation' !== settings.animation_out ) )  && ( 'undefined' === typeof is_preview  || _.isFalse( is_preview ) ) ) ? 'hustle-animated' : 'hustle-modal-static' }}">

	<# if ( 'simple' === design.style ) { #>

		<# if ( 'embedded' !== module_type ) { #>

		<div class="hustle-modal-close" aria-hidden="true"><?php echo $close_icon; //phpcs:ignore ?></div>

		<# } #>

		<div class="hustle-modal-body hustle-modal-image_{{ design.feature_image_position }}">

			<# if ( 'left' === design.feature_image_position && ( _.isTrue( content.use_feature_image ) && '' !== content.feature_image ) ) { #>

				<div class="hustle-modal-image hustle-modal-image_{{ design.feature_image_fit }}<# if ( _.isTrue( content.feature_image_hide_on_mobile ) ) { #> hustle-modal-mobile_hidden<# } #>">

					<# if ( 'contain' === design.feature_image_fit || 'cover' === design.feature_image_fit ) { #>

						<# if ( 'custom' !== design.feature_image_horizontal || 'custom' !== design.feature_image_vertical ) { #>
							<img src="{{ content.feature_image }}" class="hustle-modal-image_{{ design.feature_image_horizontal }}{{ design.feature_image_vertical }}">
						<# } else { #>
							<img src="{{ content.feature_image }}">
						<# } #>

					<# } else { #>

						<img src="{{ content.feature_image }}">

					<# } #>

				</div>

			<# } #>

			<# if (
				content.main_content !== '' ||
				( _.isTrue( content.has_title ) && ( '' !== content.title || '' !== content.sub_title ) ) ||
				( _.isTrue( content.show_cta ) && ( '' !== content.cta_label && '' !== content.cta_url ) )
			) { #>

				<div class="hustle-modal-content">

					<div class="hustle-modal-wrap--content">

						<# if ( _.isTrue( content.has_title ) && ( '' !== content.title || '' !== content.sub_title ) ) { #>

							<header class="hustle-modal-header">

								<# if ( '' !== content.title ) { #>
									<h1 class="hustle-modal-title">{{ content.title }}</h1>
								<# } #>

								<# if ( '' !== content.sub_title ) { #>
									<h2 class="hustle-modal-subtitle">{{ content.sub_title }}</h2>
								<# } #>

							</header>

						<# } #>

						<# if ( '' !== content.main_content ) { #>

							<div class="hustle-modal-message">

								{{{ content.main_content }}}

							</div>

						<# } #>

						<# if ( _.isTrue( content.show_cta ) && ( '' !== content.cta_label && '' !== content.cta_url ) ) { #>

							<div class="hustle-modal-footer">

								<# if ( _.isTrue( content.show_cta ) && ( '' !== content.cta_label && '' !== content.cta_url ) ) { #>

									<a target="_{{ content.cta_target }}" href="{{ content.cta_url }}" class="hustle-modal-cta">{{ content.cta_label }}</a>

								<# } #>

							</div>

						<# } #>

					</div>

				</div>

			<# } #>

			<# if ( 'right' === design.feature_image_position && ( _.isTrue( content.use_feature_image ) && '' !== content.feature_image ) ) { #>

				<div class="hustle-modal-image hustle-modal-image_{{ design.feature_image_fit }}<# if ( _.isTrue( content.feature_image_hide_on_mobile ) ) { #> hustle-modal-mobile_hidden<# } #>">

					<# if ( 'contain' === design.feature_image_fit || 'cover' === design.feature_image_fit ) { #>

						<# if ( 'custom' !== design.feature_image_horizontal || 'custom' !== design.feature_image_vertical ) { #>
							<img src="{{ content.feature_image }}" class="hustle-modal-image_{{ design.feature_image_horizontal }}{{ design.feature_image_vertical }}">
						<# } else { #>
							<img src="{{ content.feature_image }}">
						<# } #>

					<# } else { #>

						<img src="{{ content.feature_image }}">

					<# } #>

				</div>

			<# } #>
		</div>

	<# } else if ( 'minimal' === design.style ) { #>

		<# if ( 'embedded' !== module_type ) { #>

			<div class="hustle-modal-close" aria-hidden="true"><?php echo $close_icon; //phpcs:ignore ?></div>

		<# } #>

		<div class="hustle-modal-body hustle-modal-image_{{ design.feature_image_position }}">

			<# if ( _.isTrue( content.has_title ) && ( '' !== content.title || '' !== content.sub_title ) ) { #>

				<header class="hustle-modal-header">

					<# if ( '' !== content.title ) { #>
						<h1 class="hustle-modal-title">{{ content.title }}</h1>
					<# } #>

					<# if ( '' !== content.sub_title ) { #>
						<h2 class="hustle-modal-subtitle">{{ content.sub_title }}</h2>
					<# } #>

				</header>

			<# } #>

			<# if (
				'' !== content.main_content ||
				( _.isTrue( content.use_feature_image ) && '' !== content.feature_image )
			) { #>

				<section class="hustle-modal-content">

					<# if ( 'left' === design.feature_image_position && ( _.isTrue( content.use_feature_image ) && '' !== content.feature_image ) ) { #>

						<div class="hustle-modal-image hustle-modal-image_{{ design.feature_image_fit }}<# if ( _.isTrue( content.feature_image_hide_on_mobile ) ) { #> hustle-modal-mobile_hidden<# } #>">

							<# if ( 'contain' === design.feature_image_fit || 'cover' === design.feature_image_fit ) { #>

								<# if ( 'custom' !== design.feature_image_horizontal || 'custom' !== design.feature_image_vertical ) { #>
									<img src="{{ content.feature_image }}" class="hustle-modal-image_{{ design.feature_image_horizontal }}{{ design.feature_image_vertical }}">
								<# } else { #>
									<img src="{{ content.feature_image }}">
								<# } #>

							<# } else { #>

								<img src="{{ content.feature_image }}">

							<# } #>

						</div>

					<# } #>

					<# if ( '' !== content.main_content ) { #>

						<div class="hustle-modal-wrap--content">

							<div class="hustle-modal-message">{{{ content.main_content }}}</div>

						</div>

					<# } #>

					<# if ( 'right' === design.feature_image_position && ( _.isTrue( content.use_feature_image ) && '' !== content.feature_image ) ) { #>

						<div class="hustle-modal-image hustle-modal-image_{{ design.feature_image_fit }}<# if ( _.isTrue( content.feature_image_hide_on_mobile ) ) { #> hustle-modal-mobile_hidden<# } #>">

							<# if ( 'contain' === design.feature_image_fit || 'cover' === design.feature_image_fit ) { #>

								<# if ( 'custom' !== design.feature_image_horizontal || 'custom' !== design.feature_image_vertical ) { #>
									<img src="{{ content.feature_image }}" class="hustle-modal-image_{{ design.feature_image_horizontal }}{{ design.feature_image_vertical }}">
								<# } else { #>
									<img src="{{ content.feature_image }}">
								<# } #>

							<# } else { #>

								<img src="{{ content.feature_image }}">

							<# } #>

						</div>

					<# } #>

				</section>

			<# } #>

			<# if ( _.isTrue( content.show_cta ) && '' !== content.cta_label && '' !== content.cta_url ) { #>

				<footer class="hustle-modal-footer">

					<# if ( _.isTrue( content.show_cta ) && ( '' !== content.cta_label && '' !== content.cta_url ) ) { #>

						<a target="_{{ content.cta_target }}" href="{{ content.cta_url }}" class="hustle-modal-cta">{{ content.cta_label }}</a>

					<# } #>

				</footer>

			<# } #>

		</div>

	<# } else { // ( 'cabriolet' === design.style ) #>

		<div class="hustle-modal-body hustle-modal-image_{{ design.feature_image_position }}">

			<# if ( _.isTrue( content.has_title ) && ( '' !== content.title || '' !== content.sub_title ) ) { #><header class="hustle-modal-header hustle-modal-with-title"><# } else { #><header class="hustle-modal-header"><# } #>

				<# if ( 'embedded' !== module_type ) { #>
				
					<div class="hustle-modal-close" aria-hidden="true"><?php echo $close_icon; //phpcs:ignore ?></div>

				<# } #>

				<# if ( _.isTrue( content.has_title ) && '' !== content.title ) { #>
					<h1 class="hustle-modal-title">{{ content.title }}</h1>
				<# } #>

				<# if ( _.isTrue( content.has_title ) && '' !== content.sub_title ) { #>
					<h2 class="hustle-modal-subtitle">{{ content.sub_title }}</h2>
				<# } #>

			</header>

			<# if (
				'' !== content.main_content ||
				( _.isTrue( content.use_feature_image ) && '' !== content.feature_image ) ||
				( _.isTrue( content.show_cta ) && ( '' !== content.cta_label && '' !== content.cta_url ) )
			) { #>

				<section class="hustle-modal-content">

					<# if ( 'left' === design.feature_image_position && ( _.isTrue( content.use_feature_image ) && '' !== content.feature_image ) ) { #>

						<div class="hustle-modal-image hustle-modal-image_{{ design.feature_image_fit }}<# if ( _.isTrue( content.feature_image_hide_on_mobile ) ) { #> hustle-modal-mobile_hidden<# } #>">

							<# if ( 'contain' === design.feature_image_fit || 'cover' === design.feature_image_fit ) { #>

								<# if ( 'custom' !== design.feature_image_horizontal || 'custom' !== design.feature_image_vertical ) { #>
									<img src="{{ content.feature_image }}" class="hustle-modal-image_{{ design.feature_image_horizontal }}{{ design.feature_image_vertical }}">
								<# } else { #>
									<img src="{{ content.feature_image }}">
								<# } #>

							<# } else { #>

								<img src="{{ content.feature_image }}">

							<# } #>

						</div>

					<# } #>

					<# if (
						'' !== content.main_content ||
						( _.isTrue( content.show_cta ) && ( '' !== content.cta_label && '' !== content.cta_url ) )
					) { #>

						<div class="hustle-modal-wrap--content">

							<div class="hustle-modal-align">

								<# if ( '' !== content.main_content ) { #>

									<div class="hustle-modal-message">{{{ content.main_content }}}</div>

								<# } #>

								<# if ( _.isTrue( content.show_cta ) && '' !== content.cta_label && '' !== content.cta_url ) { #>

									<div class="hustle-modal-footer">

										<# if ( _.isTrue( content.show_cta ) && ( '' !== content.cta_label && '' !== content.cta_url ) ) { #>

											<a target="_{{ content.cta_target }}" href="{{ content.cta_url }}" class="hustle-modal-cta">{{ content.cta_label }}</a>

										<# } #>

									</div>

								<# } #>

							</div>

						</div>

					<# } #>

					<# if ( 'right' === design.feature_image_position && ( _.isTrue( content.use_feature_image ) && '' !== content.feature_image ) ) { #>

						<div class="hustle-modal-image hustle-modal-image_{{ design.feature_image_fit }}<# if ( _.isTrue( content.feature_image_hide_on_mobile ) ) { #> hustle-modal-mobile_hidden<# } #>">

							<# if ( 'contain' === design.feature_image_fit || 'cover' === design.feature_image_fit ) { #>

								<# if ( 'custom' !== design.feature_image_horizontal || 'custom' !== design.feature_image_vertical ) { #>
									<img src="{{ content.feature_image }}" class="hustle-modal-image_{{ design.feature_image_horizontal }}{{ design.feature_image_vertical }}">
								<# } else { #>
									<img src="{{ content.feature_image }}">
								<# } #>

							<# } else { #>

								<img src="{{ content.feature_image }}">

							<# } #>

						</div>

					<# } #>

				</section>

			<# } #>

		</div>

	<# } #>

</div>

</script>
