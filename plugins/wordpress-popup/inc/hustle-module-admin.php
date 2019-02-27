<?php
if( !class_exists("Hustle_Module_Admin") ):

/**
 * Class Hustle_Module_Admin
 */
class Hustle_Module_Admin {

	const ADMIN_PAGE = 'hustle';
	const DASHBOARD_PAGE = 'hustle_dashboard';
	const POPUP_LISTING_PAGE = 'hustle_popup_listing';
	const POPUP_WIZARD_PAGE = 'hustle_popup';
	const SLIDEIN_LISTING_PAGE = 'hustle_slidein_listing';
	const SLIDEIN_WIZARD_PAGE = 'hustle_slidein';
	const EMBEDDED_LISTING_PAGE = 'hustle_embedded_listing';
	const EMBEDDED_WIZARD_PAGE = 'hustle_embedded';
	const SOCIAL_SHARING_LISTING_PAGE = 'hustle_sshare_listing';
	const SOCIAL_SHARING_WIZARD_PAGE = 'hustle_sshare';
	const SETTINGS_PAGE = 'hustle_settings';
	const UPGRADE_MODAL_PARAM = 'requires_pro';

	private $_hustle;

	public function __construct( Opt_In $hustle ){

		$this->_hustle = $hustle;

		add_action( 'admin_init', array( $this, "init" ) );
		add_action("current_screen", array( $this, "set_proper_current_screen" ) );

		if( $this->_is_admin_module() ) {
			add_action( 'admin_enqueue_scripts', array( $this, "register_scripts" ), 99 );
			add_action( 'admin_print_styles', array( $this, "register_styles" ) );
			// add_action("admin_footer", array($this, "add_layout_templates"));
			add_filter( 'admin_body_class', array( $this, 'admin_body_class' ), 99 );
			add_filter("user_can_richedit", '__return_true'); // allow rich editor in
			add_filter( 'tiny_mce_before_init', array( $this, 'set_tinymce_settings' ) );
			add_filter("wp_default_editor", array( $this, 'set_editor_to_tinymce' ));
			add_filter("tiny_mce_plugins", array( $this, 'remove_despised_editor_plugins' ));

			// Show upgrade notice only if this is free, and Hustle Pro is not already installed.
			if ( Opt_In_Utils::_is_free() && ! file_exists( WP_PLUGIN_DIR . '/hustle/opt-in.php' ) ) {
				add_action( 'admin_notices', array( $this, 'show_hustle_pro_available_notice' ) );
			}
		}

		add_filter( 'w3tc_save_options', array( $this, 'filter_w3tc_save_options' ), 10, 1 );
		add_filter('plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 5 );
		add_filter('network_admin_plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 5 );

	}

	// force reject minify for hustle js and css
	public function filter_w3tc_save_options( $config ) {

		// reject js
		$defined_rejected_js = $config['new_config']->get("minify.reject.files.js");
		$reject_js = array(
			$this->_hustle->get_static_var( "plugin_url" ) . 'assets/js/admin.min.js',
			$this->_hustle->get_static_var( "plugin_url" ) . 'assets/js/ad.js',
			$this->_hustle->get_static_var( "plugin_url" ) . 'assets/js/front.min.js'
		);
		foreach( $reject_js as $r_js ) {
			if ( !in_array( $r_js, $defined_rejected_js, true ) ) {
				array_push($defined_rejected_js, $r_js);
			}
		}
		$config['new_config']->set("minify.reject.files.js", $defined_rejected_js);

		// reject css
		$defined_rejected_css = $config['new_config']->get("minify.reject.files.css");
		$reject_css = array(
			$this->_hustle->get_static_var( "plugin_url" ) . 'assets/css/front.min.css',
			$this->_hustle->get_static_var( "plugin_url" ) . 'assets/css/admin.min.css',
		);
		foreach( $reject_css as $r_css ) {
			if ( !in_array( $r_css, $defined_rejected_css, true ) ) {
				array_push($defined_rejected_css, $r_css);
			}
		}
		$config['new_config']->set("minify.reject.files.css", $defined_rejected_css);

		return $config;
	}

	/**
	 * Removes unnecessary editor plugins
	 *
	 * @param $plugins
	 * @return mixed
	 */
	public function remove_despised_editor_plugins( $plugins ){
		$k = array_search( "fullscreen", $plugins, true );
		if( false !== $k ){
			unset( $plugins[ $k ] );
		}
		$plugins[] = "paste";
		return $plugins;
	}

	/**
	 * Sets default editor to tinymce for opt-in admin
	 *
	 * @param $editor_type
	 * @return string
	 */
	public function set_editor_to_tinymce( $editor_type ){
		return "tinymce";
	}

	/**
	 * Inits admin
	 *
	 * @since 3.0
	 */
	public function init(){
		$this->add_privacy_message();
	}

	/**
	 *
	 * @since 3.0.7
	 * @param array $settings Display settings
	 * @param string $type posts|pages|tags|categories|{cpt}
	 * @return array
	 */
	private function get_conditions_ids( $settings, $type ) {
		if ( !empty( $settings['conditions'] ) && !empty( $settings['conditions'][ $type ] ) &&
				( !empty( $settings['conditions'][ $type ][ $type ] ) || !empty( $settings['conditions'][ $type ][ 'selected_cpts' ] ) ) ) {
			$ids = !empty( $settings['conditions'][ $type ][ $type ] ) ? $settings['conditions'][ $type ][ $type ]
					: $settings['conditions'][ $type ][ 'selected_cpts' ];
		} else {
			$ids = array();
		}

		return $ids;
	}


	/**
	 * Register scripts for the admin page
	 *
	 * @since 1.0
	 */
	public function register_scripts( $page_slug ){

		/**
		 * Register popup requirements
		 */
		lib3()->ui->add( TheLib_Ui::MODULE_CORE );
		lib3()->ui->add( TheLib_Ui::MODULE_SELECT );
		lib3()->ui->add( TheLib_Ui::MODULE_ANIMATION );

		wp_enqueue_script('thickbox');
		wp_enqueue_media();
		wp_enqueue_script('media-upload');
		wp_enqueue_script('jquery-ui-sortable');

		wp_register_script( 'optin_admin_ace', $this->_hustle->get_static_var( "plugin_url" ) . 'assets/js/vendor/ace/ace.js', array(), $this->_hustle->get_const_var( "VERSION" ), true );
		wp_register_script( 'optin_admin_fitie', $this->_hustle->get_static_var( "plugin_url" ) . 'assets/js/vendor/fitie/fitie.js', array(), $this->_hustle->get_const_var( "VERSION" ), true );
		wp_register_script( 'hustle_google_chart', 'https://www.gstatic.com/charts/loader.js', array(), $this->_hustle->get_const_var( "VERSION" ), true );

		wp_enqueue_script(  'optin_admin_ace' );
		wp_enqueue_script(  'hustle_google_chart' );
		wp_enqueue_script(  'optin_admin_popup' );
		wp_enqueue_script(  'optin_admin_select2' );

		wp_enqueue_script(  'optin_admin_fitie' );

		/**
		 * reCAPTCHA
		 * @since 3.0.7
		 */
		$recaptcha_settings = Hustle_Module_Model::get_recaptcha_settings();
		$is_wizard_page = preg_match( '/hustle_(popup|slidein|embedded)$/', $page_slug );
		if ( $is_wizard_page && isset( $recaptcha_settings['enabled'] ) && '1' === $recaptcha_settings['enabled'] ) {
			wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js?render=explicit' );
		}

		add_filter( 'script_loader_tag', array($this, "handle_specific_script"), 10, 2 );
		add_filter( 'style_loader_tag', array($this, "handle_specific_style"), 10, 2 );

		$is_edit =  self::is_edit();
		$post_ids = array();
		$page_ids = array();
		$tag_ids = array();
		$cat_ids = array();
		if ( $is_edit ) {
			$module = Hustle_Module_Model::instance()->get( filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT) );
			$settings = $module->get_display_settings()->to_array();

			$post_ids = $this->get_conditions_ids( $settings, 'posts' );
			$page_ids = $this->get_conditions_ids( $settings, 'pages' );
			$tag_ids = $this->get_conditions_ids( $settings, 'tags' );
			$cat_ids = $this->get_conditions_ids( $settings, 'categories' );
		}

		$tags = array_map(array($this, "terms_to_select2_data"), get_categories(array(
			"hide_empty" =>false,
			'include' => $tag_ids,
			'taxonomy' => 'post_tag'
		)));

		$cats = array_map(array($this, "terms_to_select2_data"), get_categories(array(
			'include' => $cat_ids,
			"hide_empty" =>false,
		)));


		$posts = $this->get_select2_data( 'post', $post_ids );

		/**
		 * Add all posts
		 */
		$all_posts = new stdClass();
		$all_posts->id = "all";
		$all_posts->text = __("All Posts");
		array_unshift($posts, $all_posts);

		$pages = $this->get_select2_data( 'page', $page_ids );

		/**
		 * Add all pages
		 */
		$all_pages = new stdClass();
		$all_pages->id = "all";
		$all_pages->text = __("All Pages");
		array_unshift($pages, $all_pages);

		/**
		 * Add all custom post types
		 */
		$post_types = array();
		$cpts = get_post_types( array(
			'public'   => true,
		   '_builtin' => false
		), 'objects' );
		foreach( $cpts as $cpt ) {

			// skip ms_invoice
			if ( 'ms_invoice' === $cpt->name ) {
				continue;
			}
			if ( $is_edit ) {
				$cpt_ids = $this->get_conditions_ids( $settings, $cpt->label );
			} else {
				$cpt_ids = array();
			}

			$cpt_array['name'] = $cpt->name;
			$cpt_array['label'] = $cpt->label;
			$cpt_array['data'] = $this->get_select2_data( $cpt->name, $cpt_ids );

			// all posts under this custom post type
			$all_cpt_posts = new stdClass();
			$all_cpt_posts->id = "all";
			$all_cpt_posts->text = !empty( $cpt->labels ) && !empty( $cpt->labels->all_items )
					? $cpt->labels->all_items : __( "All Items", Opt_In::TEXT_DOMAIN );
			array_unshift($cpt_array['data'], $all_cpt_posts);

			$post_types[$cpt->name] = $cpt_array;
		}

		$optin_vars = array(
			'messages' => array(
				'settings_rows_updated' => __( ' number of IPs removed from database successfully.', Opt_In::TEXT_DOMAIN ),
				'settings_saved' => __( 'Settings saved.' , Opt_In::TEXT_DOMAIN ),
				'dont_navigate_away' => __("Changes are not saved, are you sure you want to navigate away?", Opt_In::TEXT_DOMAIN),
				'ok' => __("Ok", Opt_In::TEXT_DOMAIN),
				'something_went_wrong' => '<label class="wpmudev-label--notice"><span>' . __("Something went wrong. Please try again.", Opt_In::TEXT_DOMAIN ) . '</span></label>',
				'settings' => array(
					'popup' => __("Pop-up", Opt_In::TEXT_DOMAIN ),
					'slide_in' => __("Slide-in", Opt_In::TEXT_DOMAIN ),
					'magic_bar' => __("Magic Bar", Opt_In::TEXT_DOMAIN ),
					'after_content' => __("After Content", Opt_In::TEXT_DOMAIN ),
					'floating_social' => __("Floating Social", Opt_In::TEXT_DOMAIN ),
				),
				'conditions' => array(
					'only_on_not_found' => __("404 page", Opt_In::TEXT_DOMAIN ),
					'visitor_logged_in' => __("Visitor is logged in", Opt_In::TEXT_DOMAIN ),
					'visitor_not_logged_in' => __("Visitor not logged in", Opt_In::TEXT_DOMAIN ),
					'shown_less_than' => __("{type_name} shown less than", Opt_In::TEXT_DOMAIN ),
					'only_on_mobile' => __("Only on mobile devices", Opt_In::TEXT_DOMAIN ),
					'not_on_mobile' => __("Not on mobile devices", Opt_In::TEXT_DOMAIN ),
					'from_specific_ref' => __("From a specific referrer", Opt_In::TEXT_DOMAIN ),
					'not_from_specific_ref' => __("Not from a specific referrer", Opt_In::TEXT_DOMAIN ),
					'not_from_internal_link' => __("Not from an internal link", Opt_In::TEXT_DOMAIN ),
					'from_search_engine' => __("From a search engine", Opt_In::TEXT_DOMAIN ),
					'on_specific_url' => __("On specific URL", Opt_In::TEXT_DOMAIN ),
					'not_on_specific_url' => __("Not on specific URL", Opt_In::TEXT_DOMAIN ),
					'visitor_has_commented' => __("Visitor has commented before", Opt_In::TEXT_DOMAIN ),
					'visitor_has_never_commented' => __("Visitor has never commented", Opt_In::TEXT_DOMAIN ),
					'in_a_country' => __("In a specific Country", Opt_In::TEXT_DOMAIN ),
					'not_in_a_country' => __("Not in a specific Country", Opt_In::TEXT_DOMAIN ),
					'posts' => __("Posts", Opt_In::TEXT_DOMAIN ),
					'pages' => __("Pages", Opt_In::TEXT_DOMAIN ),
					'categories' => __("Categories", Opt_In::TEXT_DOMAIN ),
					'tags' => __("Tags", Opt_In::TEXT_DOMAIN ),
				),
				'condition_labels' => array(
					'only_on_not_found' => __("Only on 404 page", Opt_In::TEXT_DOMAIN ),
					'visitor_logged_in' => __("Only when visitor has logged in", Opt_In::TEXT_DOMAIN ),
					'visitor_not_logged_in' => __("Only when visitor has not logged in", Opt_In::TEXT_DOMAIN ),
					'shown_less_than' => __("{type_name} shown less than a certain times", Opt_In::TEXT_DOMAIN ),
					'only_on_mobile' => __("Only on mobile devices", Opt_In::TEXT_DOMAIN ),
					'not_on_mobile' => __("Not on mobile devices", Opt_In::TEXT_DOMAIN ),
					'from_specific_ref' => __("From a specific referrer", Opt_In::TEXT_DOMAIN ),
					'not_from_specific_ref' => __("Not from a specific referrer", Opt_In::TEXT_DOMAIN ),
					'not_from_internal_link' => __("Not from an internal link", Opt_In::TEXT_DOMAIN ),
					'from_search_engine' => __("From a search engine", Opt_In::TEXT_DOMAIN ),
					'on_specific_url' => __("On specific URLs", Opt_In::TEXT_DOMAIN ),
					'not_on_specific_url' => __("Not on specific URLs", Opt_In::TEXT_DOMAIN ),
					'visitor_has_commented' => __("Visitor has commented before", Opt_In::TEXT_DOMAIN ),
					'visitor_has_never_commented' => __("Visitor has never commented", Opt_In::TEXT_DOMAIN ),
					'in_a_country' => __("In specific countries", Opt_In::TEXT_DOMAIN ),
					'not_in_a_country' => __("Not in specific countries", Opt_In::TEXT_DOMAIN ),
					'posts' => __("On certain posts", Opt_In::TEXT_DOMAIN ),
					'all_posts' => __("All posts", Opt_In::TEXT_DOMAIN ),
					'all' => __("All", Opt_In::TEXT_DOMAIN ),
					'no' => __("No", Opt_In::TEXT_DOMAIN ),
					'no_posts' => __("No posts", Opt_In::TEXT_DOMAIN ),
					'only_on_these_posts' => __("Only {number} posts", Opt_In::TEXT_DOMAIN ),
					'number_posts' => __("{number} posts", Opt_In::TEXT_DOMAIN ),
					'except_these_posts' => __("All posts except {number}", Opt_In::TEXT_DOMAIN ),
					'pages' => __("On certain pages", Opt_In::TEXT_DOMAIN ),
					'all_pages' => __("All pages", Opt_In::TEXT_DOMAIN ),
					'no_pages' => __("No pages", Opt_In::TEXT_DOMAIN ),
					'only_on_these_pages' => __("Only {number} pages", Opt_In::TEXT_DOMAIN ),
					'number_pages' => __("{number} pages", Opt_In::TEXT_DOMAIN ),
					'except_these_pages' => __("All pages except {number}", Opt_In::TEXT_DOMAIN ),
					'categories' => __("On certain categories", Opt_In::TEXT_DOMAIN ),
					'all_categories' => __("All categories", Opt_In::TEXT_DOMAIN ),
					'no_categories' => __("No categories", Opt_In::TEXT_DOMAIN ),
					'only_on_these_categories' => __("Only {number} categories", Opt_In::TEXT_DOMAIN ),
					'number_categories' => __("{number} categories", Opt_In::TEXT_DOMAIN ),
					'except_these_categories' => __("All categories except {number}", Opt_In::TEXT_DOMAIN ),
					'tags' => __("On certain tags", Opt_In::TEXT_DOMAIN ),
					'all_tags' => __("All tags", Opt_In::TEXT_DOMAIN ),
					'no_tags' => __("No tags", Opt_In::TEXT_DOMAIN ),
					'only_on_these_tags' => __("Only {number} tags", Opt_In::TEXT_DOMAIN ),
					'number_tags' => __("{number} tags", Opt_In::TEXT_DOMAIN ),
					'except_these_tags' => __("All tags except {number}", Opt_In::TEXT_DOMAIN ),
					"everywhere" => __("Show everywhere", Opt_In::TEXT_DOMAIN)
				),
				'conditions_body' => array(
					'only_on_not_found' => __('Shows the {type_name} on the 404 page.', Opt_In::TEXT_DOMAIN),
					'visitor_has_commented' => __('Shows the {type_name} if the user has already left a comment. You may want to combine this condition with either "Visitor is logged in" or "Visitor is not logged in".', Opt_In::TEXT_DOMAIN),
					'visitor_has_never_commented' => __('Shows the {type_name} if the user has never left a comment. You may want to combine this condition with either "Visitor is logged in" or "Visitor is not logged in".', Opt_In::TEXT_DOMAIN),
					'from_search_engine' => __('Shows the {type_name} if the user arrived via a search engine.', Opt_In::TEXT_DOMAIN),
					'not_from_internal_link' => __('Shows the {type_name} if the user did not arrive on this page via another page on your site.', Opt_In::TEXT_DOMAIN),
					'not_on_mobile' => __('Shows the {type_name} to visitors that are using a normal computer or laptop (i.e. not a Phone or Tablet).', Opt_In::TEXT_DOMAIN),
					'only_on_mobile' => __('<label class="wph-label--alt">Shows the {type_name} to visitors that are using a mobile device (Phone or Tablet).</label>', Opt_In::TEXT_DOMAIN),
					'visitor_not_logged_in' => __('<label class="wph-label--alt">Shows the {type_name} if the user is not logged in to your site.</label>', Opt_In::TEXT_DOMAIN),
					'visitor_logged_in' => __('<label class="wph-label--alt">Shows the {type_name} if the user is logged in to your site.</label>', Opt_In::TEXT_DOMAIN),
				),
				'form_fields' => array(
					'errors' => array(
						'custom_field_not_supported' => __('Custom fields are not supported by the active provider', Opt_In::TEXT_DOMAIN)
					),
				),
				"media_uploader" => array(
					"select_or_upload" => __("Select or Upload Image", Opt_In::TEXT_DOMAIN),
					"use_this_image" => __("Use this image", Opt_In::TEXT_DOMAIN)
				),
				"dashboard" => array(
					"not_enough_data" => __("There is no enough data yet, please try again later.", Opt_In::TEXT_DOMAIN)
				),
			),
			'url' => get_home_url(),
			'includes_url' => includes_url(),
			'palettes' => $this->_hustle->get_palettes(),
			'preview_image' => "",
			'cats' => $cats,
			'tags' => $tags,
			'posts' => $posts,
			'post_types' => $post_types,
			'pages' => $pages,
			'is_edit' => $is_edit,
			'current' => array(),
			'is_admin' => (int) is_admin(),
			// 'get_module_field_nonce' => wp_create_nonce( 'optin_add_module_field' ),
			'error_log_nonce' => wp_create_nonce( 'hustle_get_error_logs' ),
			'clear_log_nonce' => wp_create_nonce( 'optin_clear_logs' ),
		);

		$ap_vars = array(
			'url' => get_home_url(),
			'includes_url' => includes_url()
		);

		$optin_vars['countries'] = $this->_hustle->get_countries();
		//$optin_vars['animations'] = $this->_hustle->get_animations();
		$optin_vars['providers'] = $this->_hustle->get_providers();

		$optin_vars = apply_filters("hustle_optin_vars", $optin_vars);

		$optin_vars['is_free'] = (int) Opt_In::is_free();

		if( isset($_GET['page'] ) && 'hustle' === $_GET['page'] ) { // WPCS: CSRF ok.
			wp_enqueue_script( 'jquery-sortable' );
		}
		if(isset( $_GET['page'] ) && 'hustle' !== $_GET['page']) // WPCS: CSRF ok.
			wp_enqueue_script( 'wp-color-picker-alpha', $this->_hustle->get_static_var( "plugin_url" ) . 'assets/js/vendor/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), '1.2.2', true );
		wp_register_script( 'optin_admin_scripts', $this->_hustle->get_static_var( "plugin_url" ) . 'assets/js/admin.min.js', array( 'jquery', 'backbone', 'jquery-effects-core' ), $this->_hustle->get_const_var( "VERSION" ), true );
		wp_localize_script( 'optin_admin_scripts', 'optin_vars', $optin_vars );
		//wp_localize_script( 'optin_admin_scripts', 'hustle_vars', $optin_vars );
		wp_enqueue_script( 'optin_admin_scripts' );

	}

	/**
	 * Is the admin page being viewed in edit mode
	 *
	 * @since 1.0.0.
	 *
	 * @return mixed
	 */
	public static function is_edit(){
		return  (bool) filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	}

	/**
	 * Determine what admin section for Pop-up module
	 *
	 * @since 3.0.0.
	 *
	 * @return mixed, string or boolean
	 */
	public static function get_current_section(){
		$section = filter_input(INPUT_GET, "section", FILTER_SANITIZE_STRING);
		return ( is_null($section) || empty($section) )
			? false
			: $section;
	}

	/**
	 * Handling specific scripts for each scenario
	 *
	 */
	public function handle_specific_script( $tag, $handle ) {
		if ( 'optin_admin_fitie' === $handle ) {
			$tag = "<!--[if IE]>$tag<![endif]-->";
		}
		return $tag;
	}

	/**
	 * Handling specific style for each scenario
	 *
	 */
	public function handle_specific_style( $tag, $handle ) {
		if ( 'hustle_admin_ie' === $handle ) {
			$tag = "<!--[if IE]>". $tag ."<![endif]-->";
		}
		return $tag;
	}

	public function set_proper_current_screen( $current ){
		global $current_screen;
		if ( !Opt_In_Utils::_is_free() ) {
			$current_screen->id = Opt_In_Utils::clean_current_screen($current_screen->id);
		}
	}

	/**
	 * Registers styles for the admin
	 *
	 *
	 */
	public function register_styles(){

		$sanitize_version = str_replace( '.', '-', HUSTLE_SUI_VERSION );
		$sui_body_class   = "sui-$sanitize_version";

		wp_enqueue_style('thickbox');

		wp_register_style( 'optin_admin_select2', $this->_hustle->get_static_var( "plugin_url" ) . 'assets/js/vendor/select2/css/select2.min.css', array(), $this->_hustle->get_const_var( "VERSION" ));
		wp_register_style( 'wpoi_admin', $this->_hustle->get_static_var( "plugin_url" ) . 'assets/css/admin.min.css', array(), $this->_hustle->get_const_var( "VERSION" ));
		wp_register_style( 'hustle_admin_ie', $this->_hustle->get_static_var( "plugin_url" ) . 'assets/css/ie-admin.min.css', array(), $this->_hustle->get_const_var( "VERSION" ));
		wp_register_style( 'hstl-roboto', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i', $this->_hustle->get_const_var( "VERSION" ) );
		wp_register_style( 'hstl-opensans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i', $this->_hustle->get_const_var( "VERSION" ) );
		wp_register_style( 'hstl-source', 'https://fonts.googleapis.com/css?family=Source+Code+Pro', $this->_hustle->get_const_var( "VERSION" ) );

		wp_enqueue_style( 'optin_admin_select2' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'wdev_ui' );
		wp_enqueue_style( 'wdev_notice' );
		wp_enqueue_style( 'wpoi_admin' );
		wp_enqueue_style( 'hustle_admin_ie' );
		wp_enqueue_style( 'hstl-roboto' );
		wp_enqueue_style( 'hstl-opensans' );
		wp_enqueue_style( 'hstl-source' );

		wp_enqueue_style(
			'sui_styles',
			$this->_hustle->get_static_var( 'plugin_url' ) . 'assets/css/shared-ui.min.css',
			array(),
			$sui_body_class
		);

	}

	/**
	 * Converts term object to usable object for select2
	 * @param $term Term
	 * @return stdClass
	 */
	public static function terms_to_select2_data( $term ){
		$obj = new stdClass();
		$obj->id = $term->term_id;
		$obj->text = $term->name;
		return $obj;
	}

	/**
	 * Get usable objects for select2
	 *
	 * @param string $post_type post type
	 * @param array $include_ids IDs
	 * @return array
	 */
	private function get_select2_data( $post_type, $include_ids ) {
		if ( empty( $include_ids ) ) {
			$data = array();
		} else {
			global $wpdb;
			$data = $wpdb->get_results( $wpdb->prepare( "SELECT ID as id, post_title as text FROM {$wpdb->posts} "
			. "WHERE post_type = %s AND post_status = 'publish' AND ID IN ('" . implode( "','", $include_ids ) . "')", $post_type ) ); //phpcs:ignore
		}

		return $data;
	}


	/**
	 * Checks if it's module admin page
	 *
	 * @return bool
	 */
	private function _is_admin_module() {
		return isset( $_GET['page'] ) && in_array( $_GET['page'], array( // WPCS: CSRF ok.
			self::ADMIN_PAGE,
			self::DASHBOARD_PAGE,
			self::POPUP_LISTING_PAGE,
			self::POPUP_WIZARD_PAGE,
			self::SLIDEIN_LISTING_PAGE,
			self::SLIDEIN_WIZARD_PAGE,
			self::EMBEDDED_LISTING_PAGE,
			self::EMBEDDED_WIZARD_PAGE,
			self::SOCIAL_SHARING_LISTING_PAGE,
			self::SOCIAL_SHARING_WIZARD_PAGE,
			self::SETTINGS_PAGE,
		), true );

	}

	/**
	 * Modify admin body class to our own advantage!
	 *
	 * @param $classes
	 * @return mixed
	 */
	public function admin_body_class( $classes ) {

		$sanitize_version = str_replace( '.', '-', HUSTLE_SUI_VERSION );
		$sui_body_class   = "sui-$sanitize_version";

		$screen = get_current_screen();

		$classes = ' wpmud ';

		// Do nothing if not a hustle page
		if ( strpos( $screen->base, '_page_hustle' ) === false ) {
			return $classes;
		}

		$classes .= $sui_body_class;

		return $classes;

	}

	/**
	 * Modify tinymce editor settings
	 *
	 * @param $settings
	 */
	public function set_tinymce_settings( $settings ) {
		$settings['paste_as_text'] = 'true';
		return $settings;
	}

	/**
	 * Add Privacy Messages
	 *
	 * @since 3.0.6
	 */
	public function add_privacy_message() {
		if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
			$external_integrations_list = '';
			$external_integrations_privacy_url_list = '';
			$params = array(
				'external_integrations_list' => apply_filters( 'hustle_privacy_external_integrations_list', $external_integrations_list ),
				'external_integrations_privacy_url_list' => apply_filters( 'hustle_privacy_url_external_integrations_list', $external_integrations_privacy_url_list )
			);
			// TODO: get the name from a variable instead
			$content = $this->_hustle->render( 'general/policy-text', $params, true );
			wp_add_privacy_policy_content( 'Hustle', wp_kses_post( $content ) );
		}
	}

	/**
	 * Adds custom links on plugin page
	 *
	 */
	public function add_plugin_action_links( $actions, $plugin_file ) {
		static $plugin;

		if (!isset($plugin))
			$plugin = Opt_In::$plugin_base_file;

		if ($plugin === $plugin_file) {
			$settings = array();
			if ( ! is_network_admin() ) {
				$dashboard_url = 'admin.php?page=hustle';
				$settings = array('settings' => '<a href="'. $dashboard_url .'">' . __('Settings', Opt_In::TEXT_DOMAIN) . '</a>');
			}
			$actions = array_merge( $actions, $settings );

			// Documentation link.
//			$actions['docs'] = '<a href="' . lib3()->get_link( 'hustle', 'docs', '' ) . '" aria-label="' . esc_attr( __( 'View Hustle Documentation', Opt_In::TEXT_DOMAIN ) ) . '" target="_blank">' . esc_html__( 'Docs', Opt_In::TEXT_DOMAIN ) . '</a>';

			// Upgrade link.
			if ( Opt_In_Utils::_is_free() ) {
				if ( ! lib3()->is_member() ) {
					$url = lib3()->get_link( 'hustle', 'plugin', 'hustle_pluginlist_upgrade' );
				} else {
					$url = lib3()->get_link( 'hustle', 'install_plugin', '' );
				}
				if ( is_network_admin() || ! is_multisite() ) {
					$actions['upgrade'] = '<a href="' . esc_url( $url ) . '" aria-label="' . esc_attr( __( 'Upgrade to Hustle Pro', Opt_In::TEXT_DOMAIN ) ) . '" target="_blank" style="color: #1ABC9C;">' . esc_html__( 'Upgrade', Opt_In::TEXT_DOMAIN ) . '</a>';
				}
			}
		}

		return $actions;
	}

	/**
	 * Displays an admin notice when the user is an active member and doesn't have Hustle Pro installed
	 *
	 * @since 3.0.6
	 */
	public function show_hustle_pro_available_notice() {
		// Show the notice only to super admins who are members.
		if ( ! is_super_admin() || ! lib3()->is_member() ) {
			return;
		}

		// The notice was already dismissed.
		$dismissed_notices = array_filter( explode( ',', (string) get_user_meta( get_current_user_id(), 'hustle_dismissed_admin_notices', true ) ) );
		if ( in_array( 'hustle_pro_is_available', $dismissed_notices, true ) ) {
			return;
		}

		$link = lib3()->html->element( array(
			'type' => 'html_link',
			'value' => esc_html__( 'Upgrade' ),
			'url' => esc_url( lib3()->get_link( 'hustle', 'install_plugin', '' ) ),
			'class' => 'button-primary',
		), true );

		$profile = get_site_option( 'wdp_un_profile_data', '' );
		$name = ! empty( $profile ) ? $profile['profile']['name'] : 'Hey';

		$message = esc_html( sprintf( __( '%s, it appears you have an active WPMU DEV membership but haven\'t upgraded Hustle to the pro version. You won\'t lose an any settings upgrading, go for it!', Opt_In::TEXT_DOMAIN ), $name ) );

		$html = '<div id="hustle-notice-pro-is-available" class="notice notice-info is-dismissible"><p>' . $message . '</p><p>' . $link . '</p></div>';

		echo $html; // WPCS: XSS ok.

	}
}

endif;
