<?php 

require_once 'class-wonderplugin-carousel-list-table.php';
require_once 'class-wonderplugin-carousel-creator.php';

class WonderPlugin_Carousel_View {

	private $controller;
	private $list_table;
	private $creator;
	
	function __construct($controller) {
		
		$this->controller = $controller;
	}
	
	function add_metaboxes() {
		add_meta_box('overview_features', __('WonderPlugin Carousel Features', 'wonderplugin_carousel'), array($this, 'show_features'), 'wonderplugin_carousel_overview', 'features', '');
		add_meta_box('overview_upgrade', __('Upgrade to Commercial Version', 'wonderplugin_carousel'), array($this, 'show_upgrade_to_commercial'), 'wonderplugin_carousel_overview', 'upgrade', '');
		add_meta_box('overview_news', __('WonderPlugin News', 'wonderplugin_carousel'), array($this, 'show_news'), 'wonderplugin_carousel_overview', 'news', '');
		add_meta_box('overview_contact', __('Contact Us', 'wonderplugin_carousel'), array($this, 'show_contact'), 'wonderplugin_carousel_overview', 'contact', '');
	}
	
	function show_upgrade_to_commercial() {
		?>
		<ul class="wonderplugin-feature-list">
			<li>Use on commercial websites</li>
			<li>Remove the wonderplugin.com watermark</li>
			<li>Priority techincal support</li>
			<li><a href="http://www.wonderplugin.com/order/?product=carousel" target="_blank">Upgrade to Commercial Version</a></li>
		</ul>
		<?php
	}
	
	function show_news() {
		
		include_once( ABSPATH . WPINC . '/feed.php' );
		
		$rss = fetch_feed( 'http://www.wonderplugin.com/feed/' );
		
		$maxitems = 0;
		if ( ! is_wp_error( $rss ) )
		{
			$maxitems = $rss->get_item_quantity( 5 );
			$rss_items = $rss->get_items( 0, $maxitems );
		}
		?>
		
		<ul class="wonderplugin-feature-list">
		    <?php if ( $maxitems > 0 ) {
		        foreach ( $rss_items as $item )
		        {
		        	?>
		        	<li>
		                <a href="<?php echo esc_url( $item->get_permalink() ); ?>" target="_blank" 
		                    title="<?php printf( __( 'Posted %s', 'wonderplugin_carousel' ), $item->get_date('j F Y | g:i a') ); ?>">
		                    <?php echo esc_html( $item->get_title() ); ?>
		                </a>
		                <p><?php echo esc_html( $item->get_description() ); ?></p>
		            </li>
		        	<?php 
		        }
		    } ?>
		</ul>
		<?php
	}
	
	function show_features() {
		?>
		<ul class="wonderplugin-feature-list">
			<li>Support images, YouTube, Vimeo and MP4/WebM videos</li>
			<li>Works on mobile, tablets and all major web browsers, including iPhone, iPad, Android, Firefox, Safari, Chrome, Internet Explorer 7/8/9/10/11 and Opera</li>
			<li>Built-in lightbox effect</li>
			<li>Pre-defined professional skins</li>
			<li>Fully responsive</li>
			<li>Easy-to-use wizard style user interface</li>
			<li>Instantly preview</li>
			<li>Provide shortcode and PHP code to insert the carousel to pages, posts or templates</li>
		</ul>
		<?php
	}
	
	function show_contact() {
		?>
		<p>Technical support is available for Commercial Version users at support@wonderplugin.com. Please include your license information, WordPress version, link to your carousel, all related error messages in your email.</p> 
		<?php
	}
	
	function print_overview() {
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-carousel" class="icon32"><br /></div>
		<div class="wondercarousellightbox_options" data-skinsfoldername=""  data-jsfolder="<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" style="display:none;"></div>
			
		<h2><?php echo __( 'WonderPlugin Carousel', 'wonderplugin_carousel' ) . ( (WONDERPLUGIN_CAROUSEL_VERSION_TYPE == "C") ? " Commercial Version" : " Free Version") . " " . WONDERPLUGIN_CAROUSEL_VERSION; ?> </h2>
		 
		<div id="welcome-panel" class="welcome-panel">
			<div class="welcome-panel-content">
				<h3>WordPress Image and Video Carousel Plugin</h3>
				<div class="welcome-panel-column-container">
					<div class="welcome-panel-column">
						<h4>Get Started</h4>
						<a class="button button-primary button-hero" href="<?php echo admin_url('admin.php?page=wonderplugin_carousel_add_new'); ?>">Create A New Carousel</a>
					</div>
					<div class="welcome-panel-column welcome-panel-last">
						<h4>More Actions</h4>
						<ul>
							<li><a href="<?php echo admin_url('admin.php?page=wonderplugin_carousel_show_items'); ?>" class="welcome-icon welcome-widgets-menus">Manage Existing Carousels</a></li>
							<li><a href="http://www.wonderplugin.com/wordpress-carousel/help/" target="_blank" class="welcome-icon welcome-learn-more">Help Document</a></li>
							<?php  if (WONDERPLUGIN_CAROUSEL_VERSION_TYPE !== "C") { ?>
							<li><a href="http://www.wonderplugin.com/order/?product=carousel" target="_blank" class="welcome-icon welcome-view-site">Upgrade to Commercial Version</a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div id="dashboard-widgets-wrap">
			<div id="dashboard-widgets" class="metabox-holder columns-2">
	 
	                 <div class="postbox-container">
	                    <?php 
	                    do_meta_boxes( 'wonderplugin_carousel_overview', 'features', '' ); 
	                    do_meta_boxes( 'wonderplugin_carousel_overview', 'contact', '' ); 
	                    ?>
	                </div>
	 
	                <div class="postbox-container">
	                    <?php 
	                    if (WONDERPLUGIN_CAROUSEL_VERSION_TYPE != "C")
	                    	do_meta_boxes( 'wonderplugin_carousel_overview', 'upgrade', ''); 
	                    do_meta_boxes( 'wonderplugin_carousel_overview', 'news', ''); 
	                    ?>
	                </div>
	 
	        </div>
        </div>
            
		<?php
	}
	
	function print_edit_settings() {
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-carousel" class="icon32"><br /></div>
			
		<h2><?php _e( 'Settings', 'wonderplugin_carousel' ); ?> </h2>
		<?php

		if ( isset($_POST['save-carousel-options']) && check_admin_referer('wonderplugin-carousel', 'wonderplugin-carousel-settings') )
		{		
			unset($_POST['save-carousel-options']);
			
			$this->controller->save_settings($_POST);
			
			echo '<div class="updated"><p>Settings saved.</p></div>';
		}
		
		$settings = $this->controller->get_settings();		
		$userrole = $settings['userrole'];
		$thumbnailsize = $settings['thumbnailsize'];
		$keepdata = $settings['keepdata'];
		$disableupdate = $settings['disableupdate'];
		$supportwidget = $settings['supportwidget'];
		$addjstofooter = $settings['addjstofooter'];
		$jsonstripcslash = $settings['jsonstripcslash'];
		
		?>
		
		<h3>This page is only available for users of Administrator role.</h3>
		
        <form method="post">
        
        <?php wp_nonce_field('wonderplugin-carousel', 'wonderplugin-carousel-settings'); ?>
        
        <table class="form-table">
        
        <tr valign="top">
			<th scope="row">Set minimum user role</th>
			<td>
				<select name="userrole">
				  <option value="Administrator" <?php echo ($userrole == 'manage_options') ? 'selected="selected"' : ''; ?>>Administrator</option>
				  <option value="Editor" <?php echo ($userrole == 'moderate_comments') ? 'selected="selected"' : ''; ?>>Editor</option>
				  <option value="Author" <?php echo ($userrole == 'upload_files') ? 'selected="selected"' : ''; ?>>Author</option>
				</select>
			</td>
		</tr>
		
		<tr valign="top">
			<th scope="row">Select the default image size from Media Library for carousel thumbnails</th>
			<td>
				<select name="thumbnailsize">
				  <option value="thumbnail" <?php echo ($thumbnailsize == 'thumbnail') ? 'selected="selected"' : ''; ?>>Thumbnail size</option>
				  <option value="medium" <?php echo ($thumbnailsize == 'medium') ? 'selected="selected"' : ''; ?>>Medium size</option>
				  <option value="large" <?php echo ($thumbnailsize == 'large') ? 'selected="selected"' : ''; ?>>Large size</option>
				  <option value="full" <?php echo ($thumbnailsize == 'full') ? 'selected="selected"' : ''; ?>>Full size</option>
				</select>
			</td>
		</tr>
		
		<tr>
			<th>Data option</th>
			<td><label><input name='keepdata' type='checkbox' id='keepdata' <?php echo ($keepdata == 1) ? 'checked' : ''; ?> /> Keep data when deleting the plugin</label>
			</td>
		</tr>
		
		<tr>
			<th>Update option</th>
			<td><label><input name='disableupdate' type='checkbox' id='disableupdate' <?php echo ($disableupdate == 1) ? 'checked' : ''; ?> /> Disable plugin version check and update</label>
			</td>
		</tr>
		
		<tr>
			<th>Display carousel in widget</th>
			<td><label><input name='supportwidget' type='checkbox' id='supportwidget' <?php echo ($supportwidget == 1) ? 'checked' : ''; ?> /> Support shortcode in text widget</label>
			</td>
		</tr>
		
		<tr>
			<th>Scripts position</th>
			<td><label><input name='addjstofooter' type='checkbox' id='addjstofooter' <?php echo ($addjstofooter == 1) ? 'checked' : ''; ?> /> Add plugin js scripts to the footer (wp_footer hook must be implemented by the WordPress theme)</label>
			</td>
		</tr>
		
		<tr>
			<th>JSON options</th>
			<td><label><input name='jsonstripcslash' type='checkbox' id='jsonstripcslash' <?php echo ($jsonstripcslash == 1) ? 'checked' : ''; ?> /> Remove backslashes in JSON string</label>
			</td>
		</tr>
					
        </table>
        
        <p class="submit"><input type="submit" name="save-carousel-options" id="save-carousel-options" class="button button-primary" value="Save Changes"  /></p>
        
        </form>
        
		</div>
		<?php
	}
		
	function print_register() {
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-carousel" class="icon32"><br /></div>
			
		<h2><?php _e( 'Register', 'wonderplugin_carousel' ); ?></h2>
		<?php
				
		if (isset($_POST['save-carousel-license']) && check_admin_referer('wonderplugin-carousel', 'wonderplugin-carousel-register') )
		{		
			unset($_POST['save-carousel-license']);

			$ret = $this->controller->check_license($_POST);
			
			if ($ret['status'] == 'valid')
				echo '<div class="updated"><p>The key has been saved.</p><p>WordPress caches the udpate information. If you still see the message "Automatic update is unavailable for this plugin", please wait for some time, then click the below button "Force WordPress To Check For Plugin Updates".</p></div>';
			else if ($ret['status'] == 'expired')
				echo '<div class="error"><p>Your free upgrade period has expired, please renew your license.</p></div>';
			else if ($ret['status'] == 'invalid')
				echo '<div class="error"><p>The key is invalid.</p></div>';
			else if ($ret['status'] == 'abnormal')
				echo '<div class="error"><p>You have reached the maximum website limit of your license key. Please log into the membership area and upgrade to a higher license.</p></div>';
			else if ($ret['status'] == 'misuse')
				echo '<div class="error"><p>There is a possible misuse of your license key, please contact support@wonderplugin.com for more information.</p></div>';
			else if ($ret['status'] == 'timeout')
				echo '<div class="error"><p>The license server can not be reached, please try again later.</p></div>';
			else if ($ret['status'] == 'empty')
				echo '<div class="error"><p>Please enter your license key.</p></div>';
			else if (isset($ret['message']))
				echo '<div class="error"><p>' . $ret['message'] . '</p></div>';
		}
		else if (isset($_POST['deregister-carousel-license']) && check_admin_referer('wonderplugin-carousel', 'wonderplugin-carousel-register') )
		{	
			$ret = $this->controller->deregister_license($_POST);
			
			if ($ret['status'] == 'success')
				echo '<div class="updated"><p>The key has been deregistered.</p></div>';
			else if ($ret['status'] == 'timeout')
				echo '<div class="error"><p>The license server can not be reached, please try again later.</p></div>';
			else if ($ret['status'] == 'empty')
				echo '<div class="error"><p>The license key is empty.</p></div>';
		}
		
		$settings = $this->controller->get_settings();
		$disableupdate = $settings['disableupdate'];
		
		$key = '';
		$info = $this->controller->get_plugin_info();
		if (!empty($info->key) && ($info->key_status == 'valid' || $info->key_status == 'expired'))
			$key = $info->key;
		
		?>
		
		<?php 
		if ($disableupdate == 1)
		{
			echo "<h3 style='padding-left:10px;'>The plugin version check and update is currently disabled. You can enable it in the Settings menu.</h3>";
		}
		else
		{
		?> <div style="padding-left:10px;padding-top:12px;"> <?php
			if (empty($key)) { ?>
				<form method="post">
				<?php wp_nonce_field('wonderplugin-carousel', 'wonderplugin-carousel-register'); ?>
				<table class="form-table">
				<tr>
					<th>Enter Your License Key:</th>
					<td><input name="wonderplugin-carousel-key" type="text" id="wonderplugin-carousel-key" value="" class="regular-text" /> <input type="submit" name="save-carousel-license" id="save-carousel-license" class="button button-primary" value="Register License Key"  />
					</td>
				</tr>
				</table>
				</form>
			<?php } else { ?>
				<form method="post">
				<?php wp_nonce_field('wonderplugin-carousel', 'wonderplugin-carousel-register'); ?>
				<p>You have entered your license key and this domain has been successfully registered. &nbsp;&nbsp;<input name="wonderplugin-carousel-key" type="hidden" id="wonderplugin-carousel-key" value="<?php echo esc_html($key); ?>" class="regular-text" /><input type="submit" name="deregister-carousel-license" id="deregister-carousel-license" class="button button-primary" value="Deregister Your License Key"  /></p>
				</form>
				<?php if ($info->key_status == 'expired') { ?>
				<p><strong>Your free upgrade period has expired.</strong> To get upgrades, please <a href="https://www.wonderplugin.com/renew/" target="_blank">renew your license</a>.</p>
				<?php } ?>
			<?php } ?>
			</div>
		<?php } ?>
		
		<div style="padding-left:10px;padding-top:30px;">
		<a href="<?php echo admin_url('update-core.php?force-check=1'); ?>"><button class="button-primary">Force WordPress To Check For Plugin Updates</button></a>
		</div>
					
		<div style="padding-left:10px;padding-top:20px;">
        <ul style="list-style-type:square;font-size:16px;line-height:28px;margin-left:24px;">
		<li><a href="https://www.wonderplugin.com/how-to-upgrade-a-commercial-version-plugin-to-the-latest-version/" target="_blank">How to upgrade to the latest version</a></li>
	    <li><a href="https://www.wonderplugin.com/register-faq/" target="_blank">Where can I find my license key and other frequently asked questions</a></li>
	    </ul>
        </div>
        
		</div>
		
		<?php
	}
		
	function print_items() {
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-carousel" class="icon32"><br /></div>
		<div class="wondercarousellightbox_options" data-skinsfoldername=""  data-jsfolder="<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" style="display:none;"></div>
			
		<h2><?php _e( 'Manage Carousels', 'wonderplugin_carousel' ); ?> <a href="<?php echo admin_url('admin.php?page=wonderplugin_carousel_add_new'); ?>" class="add-new-h2"> <?php _e( 'New Carousel', 'wonderplugin_carousel' ); ?></a> </h2>
				
		<form id="carousel-list-table" method="post">
		<input type="hidden" name="page" value="<?php echo esc_html($_REQUEST['page']); ?>" />
		<?php 
		
		if ( !is_object($this->list_table) )
			$this->list_table = new WonderPlugin_Carousel_List_Table($this);
		
		$this->process_actions();
		
		$this->list_table->list_data = $this->controller->get_list_data();
		$this->list_table->prepare_items();
		$this->list_table->views();
		$this->list_table->display();		
		?>								
        </form>
        
		</div>
		<?php
	}
	
	function print_item()
	{
		if ( !isset( $_REQUEST['itemid'] ) || !is_numeric( $_REQUEST['itemid'] ) )
			return;
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-carousel" class="icon32"><br /></div>
		<div class="wondercarousellightbox_options" data-skinsfoldername=""  data-jsfolder="<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" style="display:none;"></div>
					
		<h2><?php _e( 'View Carousel', 'wonderplugin_carousel' ); ?> <a href="<?php echo admin_url('admin.php?page=wonderplugin_carousel_edit_item') . '&itemid=' . $_REQUEST['itemid']; ?>" class="add-new-h2"> <?php _e( 'Edit Carousel', 'wonderplugin_carousel' ); ?>  </a> </h2>
		
		<div class="updated"><p style="text-align:center;">  <?php _e( 'To embed the carousel into your page, use shortcode: ', 'wonderplugin_carousel' ); ?> <?php echo esc_attr('[wonderplugin_carousel id="' . $_REQUEST['itemid'] . '"]'); ?></p></div>

		<div class="updated"><p style="text-align:center;">  <?php _e( 'To embed the carousel into your template, use php code: ', 'wonderplugin_carousel' ); ?> <?php echo esc_attr('<?php echo do_shortcode(\'[wonderplugin_carousel id="' . $_REQUEST['itemid'] . '"]\'); ?>'); ?></p></div>
		
		<?php 
		if (WONDERPLUGIN_CAROUSEL_VERSION_TYPE !== "C")
			echo '<div class="updated"><p style="text-align:center;">To remove the Free Version watermark, please <a href="https://www.wonderplugin.com/order/?product=carousel" target="_blank">Upgrade to Commercial Version</a>.</p></div>';

		echo $this->controller->generate_body_code( $_REQUEST['itemid'], null, true ); 
		?>	 
		
		</div>
		<?php
	}
	
	function process_actions()
	{
		if (!isset($_REQUEST['_wpnonce']) || (!wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-' . $this->list_table->_args['plural']) && !wp_verify_nonce($_REQUEST['_wpnonce'], 'wonderplugin-list-table-nonce')))
			return;
			
		if ( ((isset($_REQUEST['action']) && ($_REQUEST['action'] == 'trash')) || (isset($_REQUEST['action2']) && ($_REQUEST['action2'] == 'trash'))) && isset( $_REQUEST['itemid'] ) )
		{
			$trashed = 0;
	
			if ( is_array( $_REQUEST['itemid'] ) )
			{
				foreach( $_REQUEST['itemid'] as $id)
				{
					if ( is_numeric($id) )
					{
						$ret = $this->controller->trash_item($id);
						if ($ret > 0)
							$trashed += $ret;
					}
				}
			}
			else if ( is_numeric($_REQUEST['itemid']) )
			{
				$trashed = $this->controller->trash_item( $_REQUEST['itemid'] );
			}
	
			if ($trashed > 0)
			{
				echo '<div class="updated"><p>';
				printf( _n('%d carousel moved to the trash.', '%d carousels moved to the trash.', $trashed), $trashed );
				echo '</p></div>';
			}
		}
	
		if ( ((isset($_REQUEST['action']) && ($_REQUEST['action'] == 'restore')) || (isset($_REQUEST['action2']) && ($_REQUEST['action2'] == 'restore'))) && isset( $_REQUEST['itemid'] ) )
		{
			$restored = 0;
	
			if ( is_array( $_REQUEST['itemid'] ) )
			{
				foreach( $_REQUEST['itemid'] as $id)
				{
					if ( is_numeric($id) )
					{
						$ret = $this->controller->restore_item($id);
						if ($ret > 0)
							$restored += $ret;
					}
				}
			}
			else if ( is_numeric($_REQUEST['itemid']) )
			{
				$restored = $this->controller->restore_item( $_REQUEST['itemid'] );
			}
	
			if ($restored > 0)
			{
				echo '<div class="updated"><p>';
				printf( _n('%d carousel restored.', '%d carousels restored.', $restored), $restored );
				echo '</p></div>';
			}
		}
	
		if ( ((isset($_REQUEST['action']) && ($_REQUEST['action'] == 'delete')) || (isset($_REQUEST['action2']) && ($_REQUEST['action2'] == 'delete'))) && isset( $_REQUEST['itemid'] ) )
		{
			$deleted = 0;
				
			if ( is_array( $_REQUEST['itemid'] ) )
			{
				foreach( $_REQUEST['itemid'] as $id)
				{
					if ( is_numeric($id) )
					{
						$ret = $this->controller->delete_item($id);
						if ($ret > 0)
							$deleted += $ret;
					}
				}
			}
			else if ( is_numeric($_REQUEST['itemid']) )
			{
				$deleted = $this->controller->delete_item( $_REQUEST['itemid'] );
			}
				
			if ($deleted > 0)
			{
				echo '<div class="updated"><p>';
				printf( _n('%d carousel deleted.', '%d carousels deleted.', $deleted), $deleted );
				echo '</p></div>';
			}
		}
	
		if ( ((isset($_REQUEST['action']) && ($_REQUEST['action'] == 'clone')) || (isset($_REQUEST['action2']) && ($_REQUEST['action2'] == 'clone'))) && isset( $_REQUEST['itemid'] ) && is_numeric( $_REQUEST['itemid'] ))
		{
			$cloned_id = $this->controller->clone_item( $_REQUEST['itemid'] );
			if ($cloned_id > 0)
			{
				echo '<div class="updated"><p>';
				printf( 'New carousel created with ID: %d', $cloned_id );
				echo '</p></div>';
			}
			else
			{
				echo '<div class="error"><p>';
				printf( 'The carousel cannot be cloned.' );
				echo '</p></div>';
			}
		}
	}

	function print_add_new() {
		
		if ( !empty($_POST['wonderplugin-carousel-save-item-post-value']) && !empty($_POST['wonderplugin-carousel-save-item-post']) && check_admin_referer('wonderplugin-carousel', 'wonderplugin-carousel-saveform'))
		{
			$this->save_item_post($_POST['wonderplugin-carousel-save-item-post-value']);
			return;
		}
		
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-carousel" class="icon32"><br /></div>
		<div class="wondercarousellightbox_options" data-skinsfoldername=""  data-jsfolder="<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" style="display:none;"></div>
		
		<h2><?php _e( 'New Carousel', 'wonderplugin_carousel' ); ?> <a href="<?php echo admin_url('admin.php?page=wonderplugin_carousel_show_items'); ?>" class="add-new-h2"> <?php _e( 'Manage Carousels', 'wonderplugin_carousel' ); ?>  </a> </h2>
		
		<?php 
		$this->creator = new WonderPlugin_Carousel_Creator($this);		
		
		$settings = $this->controller->get_settings();
		echo $this->creator->render( -1, null, $settings['thumbnailsize'] );
	}
	
	function print_edit_item()
	{

		if ( !empty($_POST['wonderplugin-carousel-save-item-post-value']) && !empty($_POST['wonderplugin-carousel-save-item-post']) && check_admin_referer('wonderplugin-carousel', 'wonderplugin-carousel-saveform'))
		{
			$this->save_item_post($_POST['wonderplugin-carousel-save-item-post-value']);
			return;
		}
		
		if ( !isset( $_REQUEST['itemid'] ) || !is_numeric( $_REQUEST['itemid'] ) )
			return;
	
		?>
		<div class="wrap">
		<div id="icon-wonderplugin-carousel" class="icon32"><br /></div>
		<div class="wondercarousellightbox_options" data-skinsfoldername=""  data-jsfolder="<?php echo WONDERPLUGIN_CAROUSEL_URL . 'engine/'; ?>" style="display:none;"></div>
			
		<h2><?php _e( 'Edit Carousel', 'wonderplugin_carousel' ); ?> <a href="<?php echo admin_url('admin.php?page=wonderplugin_carousel_show_item') . '&itemid=' . $_REQUEST['itemid']; ?>" class="add-new-h2"> <?php _e( 'View Carousel', 'wonderplugin_carousel' ); ?>  </a> </h2>
		
		<?php 
		$this->creator = new WonderPlugin_Carousel_Creator($this);
		$settings = $this->controller->get_settings();
		echo $this->creator->render( $_REQUEST['itemid'], $this->controller->get_item_data( $_REQUEST['itemid'] ), $settings['thumbnailsize'] );
	}
	
	function save_item_post($item_post) {
			
		$jsonstripcslash = get_option( 'wonderplugin_carousel_jsonstripcslash', 1 );
		if ($jsonstripcslash == 1)
			$json_post = trim(stripcslashes($item_post));
		else
			$json_post = trim($item_post);
		$json_post = str_replace("\\\\", "\\\\\\\\", $json_post);
		$items = json_decode($json_post, true);
				
		if ( empty($items) )
		{
			$json_error = "json_decode error";
			if ( function_exists('json_last_error_msg') )
				$json_error .= ' - ' . json_last_error_msg();
			else if ( function_exists('json_last_error') )
				$json_error .= 'code - ' . json_last_error();
				
			$ret = array(
					"success" => false,
					"id" => -1,
					"message" => $json_error . ". <b>To fix the problem, in the Plugin Settings menu, please uncheck the option Remove backslashes in JSON string and try again.</b>",
					"errorcontent"	=> $json_post
			);
		}
		else
		{
			add_filter('safe_style_css', 'wonderplugin_carousel_css_allow');
			add_filter('wp_kses_allowed_html', 'wonderplugin_carousel_tags_allow', 'post');
			foreach ($items as $key => &$value)
			{
				if ($value === true)
					$value = "true";
				else if ($value === false)
					$value = "false";
				else if ( is_string($value) )
					$value = wp_kses_post($value);
			}
		
			if (isset($items["slides"]) && count($items["slides"]) > 0)
			{
				foreach ($items["slides"] as $key => &$slide)
				{
					foreach ($slide as $key => &$value)
					{
						if ($value === true)
							$value = "true";
						else if ($value === false)
							$value = "false";
						else if ( is_string($value) )
							$value = wp_kses_post($value);
					}
				}
			}
			remove_filter('wp_kses_allowed_html', 'wonderplugin_carousel_tags_allow', 'post');
			remove_filter('safe_style_css', 'wonderplugin_carousel_css_allow');
			
			$ret = $this->controller->save_item($items);
		}
		?>
				
		<div class="wrap">
		<div id="icon-wonderplugin-carousel" class="icon32"><br /></div>
		
		<?php 
		if (isset($ret['success']) && $ret['success'] && isset($ret['id']) && $ret['id'] >= 0) 
		{
			echo "<h2>Carousel Saved.";
			echo "<a href='" . admin_url('admin.php?page=wonderplugin_carousel_edit_item') . '&itemid=' . $ret['id'] . "' class='add-new-h2'>Edit Carousel</a>";
			echo "<a href='" . admin_url('admin.php?page=wonderplugin_carousel_show_item') . '&itemid=' . $ret['id'] . "' class='add-new-h2'>View Carousel</a>";
			echo "</h2>";
					
			echo "<div class='updated'><p>The carousel has been saved and published.</p></div>";
			echo "<div class='updated'><p>To embed the carousel into your page or post, use shortcode:  [wonderplugin_carousel id=\"" . $ret['id'] . "\"]</p></div>";
			echo "<div class='updated'><p>To embed the carousel into your template, use php code:  &lt;?php echo do_shortcode('[wonderplugin_carousel id=\"" . $ret['id'] . "\"]'); ?&gt;</p></div>"; 
		}
		else
		{
			echo "<h2>WonderPlugin Carousel</h2>";
			echo "<div class='error'><p>The carousel can not be saved.</p></div>";
			echo "<div class='error'><p>Error Message: " . ((isset($ret['message'])) ? $ret['message'] : "") . "</p></div>";
			echo "<div class='error'><p>Error Content: " . ((isset($ret['errorcontent'])) ? $ret['errorcontent'] : "") . "</p></div>";
		}	
	}

	function import_export()
	{
	?>
		<div class="wrap">
		<div id="icon-wonderplugin-carousel" class="icon32"><br /></div>
			
		<h2><?php _e( 'Import/Export', 'wonderplugin_carousel' ); ?></h2>
			
		<p><b>This function only imports/exports carousel configurations. It does not import/export media files.</b></p>
		
		<p>The plugin uses WordPress Media Library to manage media files. Please transfer your WordPress Media Library to the new site after importing/exporting the carousel.</p>	
		
		<ul class="wonderplugin-tab-buttons-horizontal" id="wonderplugin-popup-display-toolbar" data-panelsid="wonderplugin-popup-display-panels">
			<li class="wonderplugin-tab-button-horizontal wonderplugin-tab-button-horizontal-selected"><span class="dashicons dashicons-download" style="margin-right:8px;"></span><?php _e( 'Import', 'wonderplugin_carousel' ); ?></li>
			<li class="wonderplugin-tab-button-horizontal"><span class="dashicons dashicons-upload" style="margin-right:8px;"></span><?php _e( 'Export', 'wonderplugin_carousel' ); ?></li>
			<li class="wonderplugin-tab-button-horizontal"><span class="dashicons dashicons-search" style="margin-right:8px;"></span><?php _e( 'Search and Replace', 'wonderplugin_carousel' ); ?></li>
		</ul>
		
		<?php 
		$data = $this->controller->get_list_data(true);
		?>		
		<ul class="wonderplugin-tabs-horizontal" id="wonderplugin-popup-display-panels">
			<li class="wonderplugin-tab wonderplugin-tab-horizontal wonderplugin-tab-horizontal-selected">
			
			<?php 
			if (isset($_POST['wp-import']) && isset($_FILES['importxml']) && check_admin_referer('wonderplugin-carousel', 'wonderplugin-carousel-import'))
				$import_return = $this->controller->import_carousel($_POST, $_FILES);
			?>
			
			<form method="post" enctype="multipart/form-data">
			<?php wp_nonce_field('wonderplugin-carousel', 'wonderplugin-carousel-import'); ?>
			<?php 
			if (isset($import_return))
				echo '<div class="' . ($import_return['success'] ? 'wonderplugin-updated' : 'wonderplugin-error') . '"><p>' . $import_return['message'] . '</p></div>';
			$users = get_users();	
			?>
			<h2>Choose an exported .xml file to upload, then click Upload file and import.</h2>
			<div class='wonderplugin-error wonderplugin-error-message' id="wp-import-error"></div>
			<input type="file" name="importxml" id="wp-importxml" />
			<p><label><input type="radio" name="keepid" value=1 checked>Keep the same carousel ID</label></p>
        	<p><label><input type="radio" name="keepid" value=0>Append to the exiting carousel list </label></p>
        	<p>Assign to the user:
        	<select name="authorid">
        	<?php foreach ( $users as $user ) { ?>
        		<option value="<?php echo $user->ID; ?>"><?php echo $user->user_login; ?></option>
        	<?php } ?>
        	</select>
        	</p>
        	<h3>Search and replace</h3>
        	<div class='wonderplugin-error wonderplugin-error-message' id="wp-replace-error"></div>
        	<div id='wp-search-replace'></div>
        	<div id="wp-site-url" style="display:none;"><?php echo get_site_url(); ?></div>
        	<button class="button-secondary" id="wp-add-replace-list">Add Row</button>
			<p class="submit"><input type="submit" name="wp-import" id="wp-import-submit" class="button button-primary" value="Upload file and import"  />
			</form>
			</li>
			
			<li class="wonderplugin-tab wonderplugin-tab-horizontal">
			
			<?php 
        	if (empty($data)) {
        		echo '<p>No carousel found!</p>';
        	} else {
        	?>
        	<h2>Export to an .xml file.</h2>
			<form method="post" action="<?php echo admin_url('admin-post.php?action=wonderplugin_carousel_export'); ?>">
        	<?php wp_nonce_field('wonderplugin-carousel', 'wonderplugin-carousel-export'); ?>
        	
        	<p><label><input type="radio" name="allcarousel" value=1 checked>Export all carousels</label></p>
        	<p><label><input type="radio" name="allcarousel" value=0>Select a carousel: </label>
        	<select name="carouselid">
        	<?php foreach ($data as $carousel) { ?>
  				<option value="<?php echo $carousel['id']; ?>"><?php echo 'ID ' . $carousel['id'] . ' : ' . $carousel['name']; ?></option>
  			<?php } ?>
  			</select>
        	</p>
        	<p class="submit"><input type="submit" name="wp-export" class="button button-primary" value="Export"  />
        	<?php if ( WP_DEBUG ) { ?>
			<span style="margin-left:12px;">Warning: WP_DEBUG is enabled, the function "Export" may not work correctly. Please check your WordPress configuration file wp-config.php and change the WP_DEBUG to false.</span>
        	<?php } ?>
        	</p>
			</form>	
			<?php } ?>
			</li>
			
			<li class="wonderplugin-tab wonderplugin-tab-horizontal">
			
			<?php 
        	if (empty($data)) {
        		echo '<p>No carousel found!</p>';
        	} else {
        	?>
        	<h2>Search and Replace</h2>
			<form method="post">
        	<?php wp_nonce_field('wonderplugin-carousel', 'wonderplugin-carousel-search-replace'); ?>
        	<?php
        	if (isset($_POST['wp-search-replace-submit']) && check_admin_referer('wonderplugin-carousel', 'wonderplugin-carousel-search-replace'))
				$search_return = $this->controller->search_replace_items($_POST);
			
        	if (isset($search_return))
        		echo '<div class="' . ($search_return['success'] ? 'wonderplugin-updated' : 'wonderplugin-error') . '"><p>' . $search_return['message'] . '</p></div>';
        	?>
        	<p><label><input type="radio" name="allitems" value=1 checked>Apply to all carousels</label></p>
        	<p><label><input type="radio" name="allitems" value=0>Select a carousel: </label>
        	<select name="itemid">
        	<?php foreach ($data as $item) { ?>
  				<option value="<?php echo $item['id']; ?>"><?php echo 'ID ' . $item['id'] . ' : ' . $item['name']; ?></option>
  			<?php } ?>
  			</select>
        	</p>
        	
        	<h3>Search and replace</h3>
        	<div class='wonderplugin-error wonderplugin-error-message' id="wp-standalone-replace-error"></div>
        	<div id='wp-standalone-search-replace'></div>
        	<button class="button-secondary" id="wp-add-standalone-replace-list">Add Row</button>
        	<p class="submit"><input type="submit" name="wp-search-replace-submit" id="wp-search-replace-submit" class="button button-primary" value="Search and Replace"  />
        	</p>
			</form>	
			<?php } ?>
			</li>
		</ul>

		</div>
		<?php
	}
}