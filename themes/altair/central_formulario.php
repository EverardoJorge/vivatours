<?php
/**
 * Template Name: Central Formulario
 * The main template file for display page.
 *
 * @package WordPress
*/

get_header();

/* Get Current page object */

$page = get_page($post->ID);

/* Get current page id */
$current_page_id = '';

if (isset($page->ID)) {
    $current_page_id = $page->ID;
}

 //Get Page RevSlider
    $page_revslider = get_post_meta($current_page_id, 'page_revslider', true);
    $page_menu_transparent = get_post_meta($current_page_id, 'page_menu_transparent', true);
    $page_header_below = get_post_meta($current_page_id, 'page_header_below', true);

    if (!empty($page_revslider) && $page_revslider != -1 && empty($page_header_below)) {
        echo '<div class="page_slider ';
        if (!empty($page_menu_transparent)) {
            echo 'menu_transparent';
        }
        echo '">'.do_shortcode('[rev_slider '.$page_revslider.']').'</div>';
    }

     //Get page header display setting
     $page_hide_header = get_post_meta($current_page_id, 'page_hide_header', true);

     if ($page_revslider != -1 && !empty($page_menu_transparent)) {
         $page_hide_header = 1;
     }

     if (empty($page_hide_header) && ($page_revslider == -1 or empty($page_revslider) or !empty($page_header_below))) {
         $pp_page_bg = '';
         //Get page featured image
         if (has_post_thumbnail($current_page_id, 'full')) {
             $image_id = get_post_thumbnail_id($current_page_id);
             $image_thumb = wp_get_attachment_image_src($image_id, 'full', true);
             $pp_page_bg = $image_thumb[0];
         }

         if (isset($image_thumb[0])) {
             $background_image = $image_thumb[0];
             $background_image_width = $image_thumb[1];
             $background_image_height = $image_thumb[2];
         } ?>

  <div id="page_caption" <?php if (!empty($pp_page_bg)) {
             ?>class="hasbg parallax <?php if (empty($page_menu_transparent)) {
                 ?>notransparent<?php
             } ?>" data-image="<?php echo $background_image; ?>" data-width="<?php echo $background_image_width; ?>" data-height="<?php echo $background_image_height; ?>"<?php
         } ?>>
  	<div class="page_title_wrapper">
  		<h1 <?php if (!empty($pp_page_bg) && !empty($global_pp_topbar)) {
             ?>class ="withtopbar"<?php
         } ?>><?php the_title(); ?></h1>
  		<?php
            $pp_breadcrumbs_display = get_option('pp_breadcrumbs_display');

         if (!empty($pp_breadcrumbs_display)) {
             echo dimox_breadcrumbs();
         } ?>
  	</div>
  	<?php if (!empty($pp_page_bg)) {
             echo '<div class="parallax_overlay_header"></div>';
         } ?>
  </div>
  <?php
     }

  //Check if use page builder
    $ppb_form_data_order = '';
    $ppb_form_item_arr = array();
    $ppb_enable = get_post_meta($current_page_id, 'ppb_enable', true);

    global $global_pp_topbar;


    if (!empty($ppb_enable)) {
        ?>
<div class="ppb_wrapper <?php if (!empty($pp_page_bg)) {
            ?>hasbg<?php
        } ?> <?php if (!empty($pp_page_bg) && !empty($global_pp_topbar)) {
            ?>withtopbar<?php
        } ?>">
<?php
        tg_apply_builder($current_page_id); ?>
</div>
<?php
    } else { //Star Else?>
    <!-- Begin content -->
<div id="page_content_wrapper" class="<?php if (!empty($pp_page_bg)) {
        ?>hasbg<?php
    } ?> <?php if (!empty($pp_page_bg) && !empty($global_pp_topbar)) {
        ?>withtopbar<?php
    } ?>">
    <!-- inner content -->
  <div class="inner">

    <div class="inner_wrapper">
      <div class="sidebar_content full_width">
        <?php
          /////datos del usuario
          $usuario = do_shortcode("[user-data field_name='Username']");
          $User = $wpdb->get_row($wpdb->prepare("SELECT * FROM vvt_EWD_FEUP_Users WHERE Username='".$usuario."'"));
          $max_cliente = $User->User_ID;
          $EmailCliente = $User->Username;
          var_dump ($User);
        ?>
      </div>

    </div>
      <!-- End inner content -->
  </div>
<?php
    } //End else
 ?>
