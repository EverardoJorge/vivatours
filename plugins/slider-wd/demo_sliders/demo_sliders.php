<?php
if( isset($_REQUEST['wds_import_submit']) && ! empty($_FILES['fileimport']) ) {
  require_once(WDS()->plugin_dir . '/framework/WDW_S_Library.php');
  global $wpdb;
  $flag = FALSE;
  $file = $_FILES['fileimport'];
  $dest_dir = ABSPATH . WDS()->upload_dir;
  if ( ! file_exists( $dest_dir ) ) {
    mkdir( $dest_dir, 0777, true );
  }
  if ( move_uploaded_file($file["tmp_name"], $dest_dir .'/'. $file["name"]) ) {
    $flag = WDW_S_Library::wds_import_zip_action( $dest_dir, $file["name"] );
  }

  $message_id = 24;
  if ( $flag ) {
    $message_id = 23;
  }
  WDW_S_Library::spider_redirect( add_query_arg( array( 'page' => 'sliders_wds', 'message' => $message_id), admin_url('admin.php') ) );
}

function spider_demo_sliders() {
  $demo_sliders = array(
	'presentation' => array(
						'name' => __('Presentation', WDS()->prefix),
						'href' => 'presentation',
					),
	'layers' =>  array(
					'name' => __('Layers', WDS()->prefix),
					'href' => 'layers'
				),
	'online_store' => array(
					'name' => __('Online store', WDS()->prefix),
					'href' => 'online-store'
				),
	'hotspot' => array(
					'name' => __('HotSpot', WDS()->prefix),
					'href' => 'hotspot'
				),
	'filmstrip' => array(
					'name' => __('Filmstrip', WDS()->prefix),
					'href' => 'filmstrip',
				),
	'carousel' => array(
					'name' => __('Carousel', WDS()->prefix),
					'href' => 'carousel'
				),
	'slider3d' => array(
					'name' => __('3D Slider', WDS()->prefix),
					'href' => '3d-slider'
				),
	'zoom' => array(
					'name' => __('Zoom', WDS()->prefix),
					'href' => 'zoom'
				),
	'video' => array(
					'name' => __('Video', WDS()->prefix),
					'href' => 'video'
				)
  );
  ?>
  <div id="main_featured_sliders_page">
    <div class="wd-table">
      <div class="wd-table-col wd-table-col-50 wd-table-col-left">
        <div class="wd-box-section">
          <div class="wd-box-title">
            <strong><?php _e('Import a slider', WDS()->prefix); ?></strong>
          </div>
          <div class="wd-box-content">
            <?php
            if ( WDS()->is_free ) {
              echo WDW_S_Library::message_id(0, __('This functionality is disabled in free version.', WDS()->prefix), 'error wd-notice-margin');
            }
            ?>
            <form method="post" enctype="multipart/form-data">
              <div class="wd-group">
                <input <?php echo WDS()->is_free ? 'disabled="disabled"' : ''; ?> type="file" name="fileimport" id="fileimport">
                <input <?php echo WDS()->is_free ? 'disabled="disabled"' : ''; ?> type="submit" name="wds_import_submit" class="button button-primary" onclick="<?php echo(WDS()->is_free ? 'alert(\'' . addslashes(__('This functionality is disabled in free version.', WDS()->prefix)) . '\'); return false;' : 'if(!wds_getfileextension(document.getElementById(\'fileimport\').value)){ return false; }'); ?>" value="<?php _e('Import', WDS()->prefix); ?>">
                <p class="description"><?php _e('Browse the .zip file of the slider.', WDS()->prefix); ?></p>
              </div>
            </form>
          </div>
        </div>
        <div class="wd-box-section">
          <div class="wd-box-title">
            <strong><?php _e('Download sliders', WDS()->prefix); ?></strong>
          </div>
          <div class="wd-box-content">
            <p><?php _e('You can download and import these demo sliders to your website using Import feature of Slider WD.', WDS()->prefix); ?></p>
            <ul id="featured-sliders-list">
              <?php foreach ( $demo_sliders as $key => $slider ) { ?>
                <li class="<?php echo $key;?>">
                  <div class="product"></div>
				  <p class="download-wrap">
					<span class="name"><?php echo $slider['name']; ?></span>
					<a target="_blank" href="https://demo.10web.io/slider/<?php echo $slider['href']; ?>" class="download"><span class="dashicons dashicons-download"></span> <?php _e('Download', WDS()->prefix); ?></a>
				  </p>
                </li>
                <?php } ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
}