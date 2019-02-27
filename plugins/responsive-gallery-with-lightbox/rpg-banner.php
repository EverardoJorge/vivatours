<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wp_enqueue_style( 'rpg-banner', WRGF_PLUGIN_URL . 'css/rpg-banner.css' );
$rpg_imgpath = WRGF_PLUGIN_URL . "images/rpg_pro.png";
?>
<div class="wb_plugin_feature notice  is-dismissible">
    <div class="wb_plugin_feature_banner default_pattern pattern_ ">
        <div class="wb-col-md-6 wb-col-sm-12 box">
            <div class="ribbon"><span>Go Pro</span></div>
            <img class="wp-img-responsive" src="<?php echo $rpg_imgpath; ?>" alt="img">
        </div>
        <div class="wb-col-md-6 wb-col-sm-12 wb_banner_featurs-list">
            <span class="gp_banner_head"><h2><?php _e( 'Responsive Photo Gallery Pro Features', WRGF_TEXT_DOMAIN ); ?> </h2></span>
            <ul>
                <li><?php _e( 'Gallery Layout', WRGF_TEXT_DOMAIN ); ?></li>
                <li><?php _e( 'Unlimited Hover Color', WRGF_TEXT_DOMAIN ); ?></li>
                <li><?php _e( '500 plus Font Style', WRGF_TEXT_DOMAIN ); ?></li>
                <li><?php _e( 'Isotope or Masonary Effects', WRGF_TEXT_DOMAIN ); ?></li>
                <li><?php _e( '10 Types Hover Color Opacity', WRGF_TEXT_DOMAIN ); ?></li>
                <li><?php _e( '8 Type of Hover Animations', WRGF_TEXT_DOMAIN ); ?></li>
                <li><?php _e( 'Multiple Image uploader', WRGF_TEXT_DOMAIN ); ?></li>
                <li><?php _e( '8 Types of Lightbox Integrated', WRGF_TEXT_DOMAIN ); ?></li>
                <li><?php _e( 'Drag and Drop image Position', WRGF_TEXT_DOMAIN ); ?></li>
                <li><?php _e( 'Shortcode Button on post or page', WRGF_TEXT_DOMAIN ); ?></li>
                <li><?php _e( 'Font Icon Customization & Many More', WRGF_TEXT_DOMAIN ); ?></li>
				<li><?php _e( 'Hide or Show Gallery Title and Label', WRGF_TEXT_DOMAIN ); ?></li>
            </ul>
            <div class="wp_btn-grup">
                <a class="wb_button-primary" href="http://demo.weblizar.com/responsive-photo-gallery-pro/"
                   target="_blank"><?php _e( 'View Demo', WRGF_TEXT_DOMAIN ); ?></a>
                <a class="wb_button-primary" href="https://weblizar.com/plugins/responsive-photo-gallery-pro/"
                   target="_blank"><?php _e( 'Buy Now', WRGF_TEXT_DOMAIN ); ?> $10</a>
            </div>

        </div>
    </div>
</div>