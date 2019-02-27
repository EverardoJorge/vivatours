<?php
/**
 * Plugin Name: Responsive Gallery With Lightbox
 * Version: 1.7.2
 * Description: Responsive Gallery allow you to add unlimited images galleries integrated with light box, animation hover effects, font styles, colors.
 * Author: Weblizar
 * Author URI: https://weblizar.com/plugins/responsive-photo-gallery-pro/
 * Plugin URI: https://weblizar.com/plugins/responsive-photo-gallery-pro/
 */

/**
 * Constant Variable
 */
define( "WRGF_TEXT_DOMAIN", "weblizar_image_gallery" );
define( "WRGF_PLUGIN_URL", plugin_dir_url( __FILE__ ) );

// Image Crop Size Function 
add_image_size( 'wrgf_12_thumb', 500, 9999, array( 'center', 'top' ) );
add_image_size( 'wrgf_346_thumb', 400, 9999, array( 'center', 'top' ) );
add_image_size( 'wrgf_12_same_size_thumb', 500, 500, array( 'center', 'top' ) );
add_image_size( 'wrgf_346_same_size_thumb', 400, 400, array( 'center', 'top' ) );

/**
 * Support and Our Products Page
 */
add_action( 'admin_menu', 'wrgf_SettingsPage' );
function wrgf_SettingsPage() {
	add_submenu_page( 'edit.php?post_type=wrgf_gallery', __( 'Help and Support', WRGF_TEXT_DOMAIN ), __( 'Help and Support', WRGF_TEXT_DOMAIN ), 'administrator', 'WRGF-help-page', 'WRGF_Help_and_Support_page' );
	add_submenu_page( 'edit.php?post_type=wrgf_gallery', __( 'Pro Screenshots', WRGF_TEXT_DOMAIN ), __( 'Pro Screenshots', WRGF_TEXT_DOMAIN ), 'administrator', 'WRGF-Pro-Plugin', 'WRGF_Pro_page_Function' );
	add_submenu_page( 'edit.php?post_type=wrgf_gallery', __( 'Recommendation', WRGF_TEXT_DOMAIN ), __( 'Recommendation', WRGF_TEXT_DOMAIN ), 'administrator', 'WRGF-Recommendation-page', 'WRGF_Recommendation_page' );
}

function WRGF_Help_and_Support_page() {
	
	require_once( "help_and_support.php" );
}

/**
 * Get Responsive Gallery Pro Plugin Page
 */
function WRGF_Pro_page_Function() {
	//css
	wp_enqueue_style( 'wrgf-bootstrap-admin', WRGF_PLUGIN_URL . 'css/bootstrap-latest/bootstrap-admin.css' );
	wp_enqueue_style( 'wrgf-fontawesome-latest-5.0.8', WRGF_PLUGIN_URL . 'css/font-awesome-latest/css/fontawesome-all.min.css' );
	wp_enqueue_style( 'wrgf-pricing-table-css', WRGF_PLUGIN_URL . 'css/pricing-table.css' );
	require_once( "get-responsive-gallery-pro.php" );
}

function WRGF_Recommendation_page() {
	//css
	wp_enqueue_style( 'wrgf-recom-css', WRGF_PLUGIN_URL . 'css/recom.css' );
	require_once( "recommendations.php" );
}

/**
 * Weblizar Image Gallery Shortcode Detect Function
 */
function WRGF_ShortCodeDetect() {

	/**
	 * font awesome css
	 */
	wp_enqueue_style( 'wrgf-fontawesome-5.0.8', WRGF_PLUGIN_URL . 'css/font-awesome-latest/css/fontawesome-all.min.css' );
	wp_enqueue_style( 'wrgf-fontawesome', WRGF_PLUGIN_URL . 'css/font-awesome-latest/css/fontawesome.min.css' );

	/**
	 * js scripts
	 */
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'wrgf-hover-pack-js', WRGF_PLUGIN_URL . 'js/hover-pack.js', array( 'jquery' ) );

	/**
	 * Load Light Box 4 Swipebox JS CSS
	 */
	wp_enqueue_style( 'wl-wrgf-swipe-css', WRGF_PLUGIN_URL . 'lightbox/swipebox/swipebox.css' );
	wp_enqueue_script( 'wl-wrgf-swipe-js', WRGF_PLUGIN_URL . 'lightbox/swipebox/jquery.swipebox.js' );

	/**
	 * css scripts
	 */
	wp_enqueue_style( 'wrgf-hover-pack-css', WRGF_PLUGIN_URL . 'css/hover-pack.css' );
	wp_enqueue_style( 'wrgf-bootstrap-css', WRGF_PLUGIN_URL . 'css/bootstrap-latest/bootstrap.css' );
	wp_enqueue_style( 'wrgf-img-gallery-css', WRGF_PLUGIN_URL . 'css/img-gallery.css' );

	
	/**
	 * envira & isotope js
	 */
	wp_enqueue_script( 'wrgf_masonry', WRGF_PLUGIN_URL . 'js/masonry.pkgd.min.js', array( 'jquery' ) );
	wp_enqueue_script( 'wrgf_imagesloaded', WRGF_PLUGIN_URL . 'js/imagesloaded.pkgd.min.js', array( 'jquery' ) );
}

add_action( 'wp', 'WRGF_ShortCodeDetect' );

function wrgf_remove_image_box() {
	remove_meta_box( 'postimagediv', 'wrgf_gallery', 'side' );
}

add_action( 'do_meta_boxes', 'wrgf_remove_image_box' );

class WRGF {

	private static $instance;
	private $admin_thumbnail_size = 150;
	private $thumbnail_size_w = 150;
	private $thumbnail_size_h = 150;
	var $counter;

	public static function forge() {
		if ( ! isset( self::$instance ) ) {
			$className      = __CLASS__;
			self::$instance = new $className;
		}

		return self::$instance;
	}

	private function __construct() {
		$this->counter = 0;
		add_action( 'admin_print_scripts-post.php', array( &$this, 'wrgf_admin_print_scripts' ) );
		add_action( 'admin_print_scripts-post-new.php', array( &$this, 'wrgf_admin_print_scripts' ) );
		add_image_size( 'rpg_gallery_admin_thumb', $this->admin_thumbnail_size, $this->admin_thumbnail_size, true );
		add_image_size( 'rpg_gallery_thumb', $this->thumbnail_size_w, $this->thumbnail_size_h, true );
		add_shortcode( 'rpggallery', array( &$this, 'shortcode' ) );
		if ( is_admin() ) {
			add_action( 'init', array( &$this, 'wrgf_register_cpt_function' ), 1 );
			add_action( 'add_meta_boxes', array( &$this, 'add_all_wrgf_meta_boxes' ) );
			add_action( 'admin_init', array( &$this, 'add_all_wrgf_meta_boxes' ), 1 );

			add_action( 'save_post', array( &$this, 'wrgf_add_image_meta_box_save' ), 9, 1 );
			add_action( 'save_post', array( &$this, 'wrgf_settings_meta_save' ), 9, 1 );

			add_action( 'wp_ajax_wrgfgallery_get_thumbnail', array( &$this, 'ajax_get_thumbnail_wrgf' ) );
		}
	}

	//Required JS & CSS
	public function wrgf_admin_print_scripts() {
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'wrgf-media-uploader-js', WRGF_PLUGIN_URL . 'js/wrgf-multiple-media-uploader.js', array( 'jquery' ) );

		wp_enqueue_media();
		//custom add image box css
		wp_enqueue_style( 'wrgf-meta-css', WRGF_PLUGIN_URL . 'css/wrgf-meta.css' );

		//font awesome css
		wp_enqueue_style( 'wrgf-fontawesome-5.0.8', WRGF_PLUGIN_URL . 'css/font-awesome-latest/css/fontawesome-all.min.css' );

		//single media uploader js
		wp_enqueue_script( 'wrgf-media-uploads', WRGF_PLUGIN_URL . 'js/wrgf-media-upload-script.js', array(
			'media-upload',
			'thickbox',
			'jquery'
		) );

		// enqueue style and script of code mirror
		wp_enqueue_style( 'wrgf_codemirror-css', WRGF_PLUGIN_URL . 'css/codemirror/codemirror.css' );
		wp_enqueue_style( 'wrgf_blackboard', WRGF_PLUGIN_URL . 'css/codemirror/blackboard.css' );
		wp_enqueue_style( 'wrgf_show-hint-css', WRGF_PLUGIN_URL . 'css/codemirror/show-hint.css' );

		wp_enqueue_script( 'wrgf_codemirror-js', WRGF_PLUGIN_URL . 'css/codemirror/codemirror.js', array( 'jquery' ) );
		wp_enqueue_script( 'wrgf_css-js', WRGF_PLUGIN_URL . 'css/codemirror/wrgf-css.js', array( 'jquery' ) );
		wp_enqueue_script( 'wrgf_css-hint-js', WRGF_PLUGIN_URL . 'css/codemirror/css-hint.js', array( 'jquery' ) );
	}

	// Register Custom Post Type
	public function wrgf_register_cpt_function() {
		$labels = array(
			'name'               => _x( 'Responsive Gallery', 'wrgf_gallery' ),
			'singular_name'      => _x( 'Responsive Gallery', 'wrgf_gallery' ),
			'add_new'            => _x( 'Add New Gallery', 'wrgf_gallery' ),
			'add_new_item'       => _x( 'Add New Gallery', 'wrgf_gallery' ),
			'edit_item'          => _x( 'Edit Photo Gallery', 'wrgf_gallery' ),
			'new_item'           => _x( 'New Gallery', 'wrgf_gallery' ),
			'view_item'          => _x( 'View Gallery', 'wrgf_gallery' ),
			'search_items'       => _x( 'Search Galleries', 'wrgf_gallery' ),
			'not_found'          => _x( 'No galleries found', 'wrgf_gallery' ),
			'not_found_in_trash' => _x( 'No galleries found in Trash', 'wrgf_gallery' ),
			'parent_item_colon'  => _x( 'Parent Gallery:', 'wrgf_gallery' ),
			'all_items'          => __( 'All Galleries', WRGF_TEXT_DOMAIN ),
			'menu_name'          => _x( 'Responsive Gallery', 'wrgf_gallery' ),
		);

		$args = array(
			'labels'              => $labels,
			'hierarchical'        => false,
			'supports'            => array( 'title', 'thumbnail' ),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 10,
			'menu_icon'           => 'dashicons-format-gallery',
			'show_in_nav_menus'   => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'has_archive'         => true,
			'query_var'           => true,
			'can_export'          => true,
			'rewrite'             => false,
			'capability_type'     => 'post'
		);

		register_post_type( 'wrgf_gallery', $args );
		add_filter( 'manage_edit-wrgf_gallery_columns', array( &$this, 'wrgf_gallery_columns' ) );
		add_action( 'manage_wrgf_gallery_posts_custom_column', array( &$this, 'wrgf_gallery_manage_columns' ), 10, 2 );
	}

	function wrgf_gallery_columns( $columns ) {
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'title'     => __( 'Gallery' ),
			'shortcode' => __( 'Gallery Shortcode' ),
			'date'      => __( 'Date' )
		);

		return $columns;
	}

	function wrgf_gallery_manage_columns( $column, $post_id ) {
		global $post;
		switch ( $column ) {
			case 'shortcode' :
				echo '<input type="text" value="[WRGF id=' . $post_id . ']" readonly="readonly" />';
				break;
			default :
				break;
		}
	}

	public function add_all_wrgf_meta_boxes() {
		add_meta_box( __( 'Add Images', WRGF_TEXT_DOMAIN ), __( 'Add Images', WRGF_TEXT_DOMAIN ), array(
			&$this,
			'wrgf_generate_add_image_meta_box_function'
		), 'wrgf_gallery', 'normal', 'low' );
		add_meta_box( __( 'Apply Setting On Photo Gallery', WRGF_TEXT_DOMAIN ), __( 'Apply Setting On Photo Gallery', WRGF_TEXT_DOMAIN ), array(
			&$this,
			'wrgf_settings_meta_box_function'
		), 'wrgf_gallery', 'normal', 'low' );
		add_meta_box( __( 'Responsive Gallery Shortcode', WRGF_TEXT_DOMAIN ), __( 'Responsive Gallery Shortcode', WRGF_TEXT_DOMAIN ), array(
			&$this,
			'wrgf_shotcode_meta_box_function'
		), 'wrgf_gallery', 'side', 'low' );
		add_meta_box( __( 'Responsive Photo Gallery Pro', WRGF_TEXT_DOMAIN ), __( 'Responsive Photo Gallery Pro', WRGF_TEXT_DOMAIN ), array(
			&$this,
			'wrgf_pro_features'
		), 'wrgf_gallery', 'side', 'low' );
		add_meta_box( __( 'Rate us on WordPress', WRGF_TEXT_DOMAIN ), __( 'Rate us on WordPress', WRGF_TEXT_DOMAIN ), array(
			&$this,
			'wrgf_rate_us_function'
		), 'wrgf_gallery', 'side', 'low' );
		add_meta_box( __( 'Upgrade To Pro Version', WRGF_TEXT_DOMAIN ), __( 'Upgrade To Pro Version', WRGF_TEXT_DOMAIN ), array(
			&$this,
			'wrgf_upgrade_to_pro_function'
		), 'wrgf_gallery', 'side', 'low' );
	}

	/**    Rate us **/
	function wrgf_rate_us_function() { ?>
        <div style="text-align:center">
            <h3><?php _e( 'If you like our plugin then please show us some love', WRGF_TEXT_DOMAIN ); ?> </h3>
            <style>
                .wrg-rate-us span.dashicons {
                    width: 30px;
                    height: 30px;
                }

                .wrg-rate-us span.dashicons-star-filled:before {
                    content: "\f155";
                    font-size: 30px;
                }
            </style>
            <a class="wrg-rate-us" style="text-align:center; text-decoration: none;font:normal 30px/l;"
               href="http://wordpress.org/plugins/responsive-photo-gallery/" target="_blank">
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
                <span class="dashicons dashicons-star-filled"></span>
            </a>
            <div class="upgrade-to-pro-demo" style="text-align:center;margin-bottom:10px;margin-top:10px;">
                <a href="http://wordpress.org/plugins/responsive-photo-gallery/" target="_new"
                   class="button button-primary button-hero"><?php _e( 'Click Here', WRGF_TEXT_DOMAIN ); ?></a>
            </div>
        </div>
		<?php
	}

	/**    Upgarde to Pro **/
	function wrgf_upgrade_to_pro_function() { ?>
        <div class="upgrade-to-pro-demo" style="text-align:center;margin-bottom:10px;margin-top:10px;">
            <a href="http://demo.weblizar.com/responsive-photo-gallery-pro/" target="_new"
               class="button button-primary button-hero"><?php _e( 'View Live Demo', WRGF_TEXT_DOMAIN ); ?></a>
        </div>
        <div class="upgrade-to-pro-admin-demo" style="text-align:center;margin-bottom:10px;">
            <a href="http://demo.weblizar.com/responsive-photo-gallery-admin-demo/" target="_new"
               class="button button-primary button-hero"><?php _e( 'View Admin Demo', WRGF_TEXT_DOMAIN ); ?></a>
        </div>
        <div class="upgrade-to-pro" style="text-align:center;margin-bottom:10px;">
            <a href="http://weblizar.com/plugins/responsive-photo-gallery-pro/" target="_new"
               class="button button-primary button-hero"><?php _e( 'Upgarde To Pro', WRGF_TEXT_DOMAIN ); ?></a>
        </div>
		<?php
	}

	//Pro Features
	function wrgf_pro_features() {
		$imgpath = WRGF_PLUGIN_URL . "images/rpg_pro.jpg";
		?>
        <div class="">
            <div class="update_pro_button"><a target="_blank"
                                              href="https://weblizar.com/plugins/responsive-photo-gallery-pro/"><?php _e( 'Buy Now $10', WRGF_TEXT_DOMAIN ); ?></a>
            </div>
            <div class="update_pro_image">
                <img class="rpg_getpro" src="<?php echo $imgpath; ?>">
            </div>
            <div class="update_pro_button">
                <a class="upg_anch" target="_blank"
                   href="https://weblizar.com/plugins/responsive-photo-gallery-pro/"><?php _e( 'Buy Now $10', WRGF_TEXT_DOMAIN ); ?></a>
            </div>
        </div>
		<?php
	}

	/**
	 * This function display Add New Image interface
	 * Also loads all saved gallery photos into photo gallery
	 */
	public function wrgf_generate_add_image_meta_box_function( $post ) { ?>
        <div id="rpggallery_container">
            <input type="hidden" id="rpg_wl_action" name="rpg_wl_action" value="rpg-save-settings">
            <ul id="wrgf_gallery_thumbs" class="clearfix">
				<?php
				/* load saved photos into gallery */
				$WRGF_AllPhotosDetails = unserialize( base64_decode( get_post_meta( $post->ID, 'wrgf_all_photos_details', true ) ) );
				$TotalImages           = get_post_meta( $post->ID, 'wrgf_total_images_count', true );
				if ( $TotalImages ) {
					foreach ( $WRGF_AllPhotosDetails as $WRGF_SinglePhotoDetails ) {
						$name         = $WRGF_SinglePhotoDetails['wrgf_image_label'];
						$UniqueString = substr( str_shuffle( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), 0, 5 );
						$url          = $WRGF_SinglePhotoDetails['wrgf_image_url'];
						$url1         = $WRGF_SinglePhotoDetails['wrgf_12_thumb'];
						$url2         = $WRGF_SinglePhotoDetails['wrgf_346_thumb'];
						$url3         = $WRGF_SinglePhotoDetails['wrgf_12_same_size_thumb'];
						$url4         = $WRGF_SinglePhotoDetails['wrgf_346_same_size_thumb'];
						?>
                        <li class="rpg-image-entry" id="rpg_img">
                            <a class="gallery_remove wrgfgallery_remove" href="#wrgf_gallery_remove"
                               id="wrgf_remove_bt"><img
                                        src="<?php echo WRGF_PLUGIN_URL . 'images/Close-icon-new.png'; ?>"/></a>
                            <img src="<?php echo $url; ?>" class="rpg-meta-image" alt="" style="">
                            <input type="button" id="upload-background-<?php echo $UniqueString; ?>"
                                   name="upload-background-<?php echo $UniqueString; ?>" value="Upload Image"
                                   class="button-primary " onClick="weblizar_image('<?php echo $UniqueString; ?>')"/>
                            <input type="text" id="wrgf_image_label[]" name="wrgf_image_label[]"
                                   value="<?php echo htmlentities( $name ); ?>" placeholder="Enter Image Label"
                                   class="rpg_label_text">

                            <input type="text" id="wrgf_image_url[]" name="wrgf_image_url[]" class="rpg_label_text"
                                   value="<?php echo $url; ?>" readonly="readonly" style="display:none;"/>
                            <input type="text" id="wrgf_image_url1[]" name="wrgf_image_url1[]" class="rpg_label_text"
                                   value="<?php echo $url1; ?>" readonly="readonly" style="display:none;"/>
                            <input type="text" id="wrgf_image_url2[]" name="wrgf_image_url2[]" class="rpg_label_text"
                                   value="<?php echo $url2; ?>" readonly="readonly" style="display:none;"/>
                            <input type="text" id="wrgf_image_url3[]" name="wrgf_image_url3[]" class="rpg_label_text"
                                   value="<?php echo $url3; ?>" readonly="readonly" style="display:none;"/>
                            <input type="text" id="wrgf_image_url4[]" name="wrgf_image_url4[]" class="rpg_label_text"
                                   value="<?php echo $url4; ?>" readonly="readonly" style="display:none;"/>
                        </li>
						<?php
					} // end of foreach
				} else {
					$TotalImages = 0;
				}
				?>
            </ul>
        </div>

        <!--Add New Image Button-->
        <div class="rpg-image-entry add_rpg_new_image" id="wrgf_gallery_upload_button"
             data-uploader_title="Upload Image" data-uploader_button_text="Select">
            <div class="dashicons dashicons-plus"></div>
            <p>
				<?php _e( 'Add New Image', WRGF_TEXT_DOMAIN ); ?>
            </p>
        </div>
        <!--Delete Image Button-->
        <div class="rpg-image-entry del_rpg_image" id="wrgf_delete_all_button">
            <div class="dashicons dashicons-trash"></div>
            <p><?php _e( 'Delete All Images', WRGF_TEXT_DOMAIN ); ?></p>
        </div>
        <div style="clear:left;"></div>
        <p><strong><?php _e( 'Tips', WRGF_TEXT_DOMAIN ); ?>
                :</strong> <?php _e( 'Plugin crop images with same size thumbnails', WRGF_TEXT_DOMAIN ); ?>
            . <?php _e( 'So', WRGF_TEXT_DOMAIN ); ?>
            , <?php _e( 'please upload all gallery images using Add New Image button', WRGF_TEXT_DOMAIN ); ?>
            . <?php _e( 'Don', WRGF_TEXT_DOMAIN ); ?>'<?php _e( 't use', WRGF_TEXT_DOMAIN ); ?>
            /<?php _e( 'add pre', WRGF_TEXT_DOMAIN ); ?>
            -<?php _e( 'uploaded images which are uploaded previously using Media', WRGF_TEXT_DOMAIN ); ?>
            /<?php _e( 'Post', WRGF_TEXT_DOMAIN ); ?>/<?php _e( 'Page', WRGF_TEXT_DOMAIN ); ?>.</p>
		<?php
	}

	/**
	 * This function display Add New Image interface
	 * Also loads all saved gallery photos into photo gallery
	 */
	public function wrgf_settings_meta_box_function( $post ) {
		require_once( 'responsive-gallery-settings-metabox.php' );
	}

	public function wrgf_shotcode_meta_box_function() { ?>
        <p><?php _e( "Use below shortcode in any Page/Post to publish your gallery", WRGF_TEXT_DOMAIN ); ?></p>
        <input readonly="readonly" type="text" value="<?php echo "[WRGF id=" . get_the_ID() . "]"; ?>">
		<?php
	}

	public function admin_thumb( $id ) {
		$image        = wp_get_attachment_image_src( $id, 'rpggallery_admin_medium', true );
		$image1       = wp_get_attachment_image_src( $id, 'wrgf_12_thumb', true );
		$image2       = wp_get_attachment_image_src( $id, 'wrgf_346_thumb', true );
		$image3       = wp_get_attachment_image_src( $id, 'wrgf_12_same_size_thumb', true );
		$image4       = wp_get_attachment_image_src( $id, 'wrgf_346_same_size_thumb', true );
		$UniqueString = substr( str_shuffle( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), 0, 5 );
		?>
        <li class="rpg-image-entry" id="rpg_img">
            <a class="gallery_remove wrgfgallery_remove" href="#wrgf_gallery_remove" id="wrgf_remove_bt"><img
                        src="<?php echo WRGF_PLUGIN_URL . 'images/Close-icon-new.png'; ?>"/></a>
            <img src="<?php echo $image[0]; ?>" class="rpg-meta-image" alt="" style="">
            <input type="button" id="upload-background-<?php echo $UniqueString; ?>"
                   name="upload-background-<?php echo $UniqueString; ?>" value="Upload Image" class="button-primary "
                   onClick="weblizar_image('<?php echo $UniqueString; ?>')"/>
            <input type="text" id="wrgf_image_label[]" name="wrgf_image_label[]" placeholder="Enter Image Label"
                   value="" class="rpg_label_text">

            <input type="text" id="wrgf_image_url[]" name="wrgf_image_url[]" class="rpg_label_text"
                   value="<?php echo $image[0]; ?>" readonly="readonly" style="display:none;"/>
            <input type="text" id="wrgf_image_url1[]" name="wrgf_image_url1[]" class="rpg_label_text"
                   value="<?php echo $image1[0]; ?>" readonly="readonly" style="display:none;"/>
            <input type="text" id="wrgf_image_url2[]" name="wrgf_image_url2[]" class="rpg_label_text"
                   value="<?php echo $image2[0]; ?>" readonly="readonly" style="display:none;"/>
            <input type="text" id="wrgf_image_url3[]" name="wrgf_image_url3[]" class="rpg_label_text"
                   value="<?php echo $image3[0]; ?>" readonly="readonly" style="display:none;"/>
            <input type="text" id="wrgf_image_url4[]" name="wrgf_image_url4[]" class="rpg_label_text"
                   value="<?php echo $image4[0]; ?>" readonly="readonly" style="display:none;"/>
        </li>
		<?php
	}

	public function ajax_get_thumbnail_wrgf() {
		echo $this->admin_thumb( $_POST['imageid'] );
		die;
	}

	public function wrgf_add_image_meta_box_save( $PostID ) {
		if ( isset( $PostID ) && isset( $_POST['rpg_wl_action'] ) ) {
			$TotalImages = count( $_POST['wrgf_image_url'] );
			$ImagesArray = array();
			if ( $TotalImages ) {
				for ( $i = 0; $i < $TotalImages; $i ++ ) {
					$image_label   = stripslashes( $_POST['wrgf_image_label'][ $i ] );
					$url           = $_POST['wrgf_image_url'][ $i ];
					$url1          = $_POST['wrgf_image_url1'][ $i ];
					$url2          = $_POST['wrgf_image_url2'][ $i ];
					$url3          = $_POST['wrgf_image_url3'][ $i ];
					$url4          = $_POST['wrgf_image_url4'][ $i ];
					$ImagesArray[] = array(
						'wrgf_image_label'         => $image_label,
						'wrgf_image_url'           => $url,
						'wrgf_12_thumb'            => $url1,
						'wrgf_346_thumb'           => $url2,
						'wrgf_12_same_size_thumb'  => $url3,
						'wrgf_346_same_size_thumb' => $url4
					);
				}
				update_post_meta( $PostID, 'wrgf_all_photos_details', base64_encode( serialize( $ImagesArray ) ) );
				update_post_meta( $PostID, 'wrgf_total_images_count', $TotalImages );
			} else {
				$TotalImages = 0;
				update_post_meta( $PostID, 'wrgf_total_images_count', $TotalImages );
				$ImagesArray = array();
				update_post_meta( $PostID, 'wrgf_all_photos_details', base64_encode( serialize( $ImagesArray ) ) );
			}
		}
	}

	//save settings meta box values
	public function wrgf_settings_meta_save( $PostID ) {
		if ( isset( $PostID ) && isset( $_POST['wl_wrgf_action'] ) ) {
			$WL_Show_Gallery_Title   = $_POST['wl-show-gallery-title'];
			$WL_Show_Image_Label     = $_POST['wl-show-image-label'];
			$WL_Image_Label_Position = $_POST['wl-image-label-position'];
			$WL_Hover_Animation      = $_POST['wl-hover-animation'];
			$WL_Gallery_Layout       = $_POST['wl-gallery-layout'];
			$WL_Thumbnail_Layout     = $_POST['wl-thumbnail-layout'];
			$WL_Hover_Color          = $_POST['wl-hover-color'];
			$WL_Hover_Text_Color     = $_POST['wl-hover-text-color'];
			$WL_Footer_Text_Color    = $_POST['wl-footer-text-color'];
			$WL_Hover_Color_Opacity  = $_POST['wl-hover-color-opacity'];
			$WL_Font_Style           = $_POST['wl-font-style'];
			$WL_Custom_Css           = $_POST['wl-custom-css'];

			$WRGF_Settings_Array = serialize( array(
				'WL_Show_Gallery_Title'   => $WL_Show_Gallery_Title,
				'WL_Show_Image_Label'     => $WL_Show_Image_Label,
				'WL_Image_Label_Position' => $WL_Image_Label_Position,
				'WL_Hover_Animation'      => $WL_Hover_Animation,
				'WL_Gallery_Layout'       => $WL_Gallery_Layout,
				'WL_Thumbnail_Layout'     => $WL_Thumbnail_Layout,
				'WL_Hover_Color'          => $WL_Hover_Color,
				'WL_Hover_Text_Color'     => $WL_Hover_Text_Color,
				'WL_Footer_Text_Color'    => $WL_Footer_Text_Color,
				'WL_Hover_Color_Opacity'  => $WL_Hover_Color_Opacity,
				'WL_Font_Style'           => $WL_Font_Style,
				'WL_Custom_Css'           => $WL_Custom_Css
			) );

			$WRGF_Gallery_Settings = "WRGF_Gallery_Settings_" . $PostID;
			update_post_meta( $PostID, $WRGF_Gallery_Settings, $WRGF_Settings_Array );
		}
	}
}

global $WRGF;
$WRGF = WRGF::forge();

/**
 * Responsive Gallery Short Code [WRGF]
 */
require_once( "responsive-gallery-short-code.php" );

/**
 * Hex Color code to RGB Color Code converter function
 */
if ( ! function_exists( 'WRGF_hex2rgb' ) ) {
	function WRGF_hex2rgb( $hex ) {
		$hex = str_replace( "#", "", $hex );

		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = array( $r, $g, $b );

		return $rgb; // returns an array with the rgb values
	}
}

add_action( 'media_buttons_context', 'add_wrgf_custom_button' );
add_action( 'admin_footer', 'add_wrgf_inline_popup_content' );
function add_wrgf_custom_button( $context ) {
	$img          = plugins_url( '/images/Photos-icon.png', __FILE__ );
	$container_id = 'WRGF';
	$title        = 'Select Responsive Gallery to insert into post';
	$context      .= '<a class="button button-primary thickbox" title="Select Gallery to insert into post" href="#TB_inline?width=400&inlineId=' . $container_id . '">
		<span class="wp-media-buttons-icon" style="background: url(' . $img . '); background-repeat: no-repeat; background-position: left bottom;"></span>
	Responsive Gallery Shortcode
	</a>';

	return $context;
}

// Add settings link on plugin page
$rpgwl_plugin_name = plugin_basename(__FILE__);
add_filter("plugin_action_links_$rpgwl_plugin_name", 'rpgwl_settings_link_rpg' );
function rpgwl_settings_link_rpg($links) {
    $rpgwl_settings_link1 = '<a href="https://weblizar.com/plugins/responsive-photo-gallery-pro/" target="_blank" style="font-weight:700; color:#e35400">Go Pro</a>';
    $rpgwl_settings_link2= '<a href="edit.php?post_type=wrgf_gallery">Settings</a>';
    array_unshift($links, $rpgwl_settings_link1, $rpgwl_settings_link2);
    return $links;
}

function add_wrgf_inline_popup_content() {
	?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('#wrgfgalleryinsert').on('click', function () {
                var id = jQuery('#wrgf-gallery-select option:selected').val();
                window.send_to_editor('<p>[WRGF id=' + id + ']</p>');
                tb_remove();
            })
        });
    </script>
    <div id="WRGF" style="display:none;">
        <h3><?php _e( 'Select Responsive Gallery to insert into post', WRGF_TEXT_DOMAIN ); ?></h3>
		<?php
		$all_posts = wp_count_posts( 'wrgf_gallery' )->publish;
		$args      = array( 'post_type' => 'wrgf_gallery', 'posts_per_page' => $all_posts );
		global $wrgf_galleries;
		$wrgf_galleries = new WP_Query( $args );
		if ( $wrgf_galleries->have_posts() ) { ?>
            <select id="wrgf-gallery-select"> <?php
				while ( $wrgf_galleries->have_posts() ) : $wrgf_galleries->the_post(); ?>
                    <option value="<?php echo get_the_ID(); ?>"><?php the_title(); ?></option>
				<?php endwhile; ?>
            </select>
            <button class='button primary'
                    id='wrgfgalleryinsert'><?php _e( 'Insert Gallery Shortcode', WRGF_TEXT_DOMAIN ); ?></button>
			<?php
		} else {
			_e( "No Gallery Found", WRGF_TEXT_DOMAIN );
		} ?>
    </div>
	<?php
}

// Review Notice Box
add_action( "admin_notices", "review_admin_notice_rpg_free" );
function review_admin_notice_rpg_free() {
	global $pagenow;
	$rpg_screen = get_current_screen();
	if ( $pagenow == 'edit.php' && $rpg_screen->post_type == "wrgf_gallery" ) {
			include( 'rpg-banner.php' );
		}
}
?>