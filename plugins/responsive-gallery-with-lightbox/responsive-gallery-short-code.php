<?php
add_shortcode( 'WRGF', 'WRGF_ShortCode_Page' );
function WRGF_ShortCode_Page( $Id ) {

	ob_start();
	/**
	 * Load Saved Responsive Gallery Settings
	 */
	if ( ! isset( $Id['id'] ) ) {
		$Id['id']                = "";
		$WL_Show_Gallery_Title   = "yes";
		$WL_Show_Image_Label     = "yes";
		$WL_Image_Label_Position = "hover";
		$WL_Hover_Animation      = "stroke";
		$WL_Gallery_Layout       = "col-md-6";
		$WL_Thumbnail_Layout     = "same-size";
		$WL_Hover_Color          = "#0AC2D2";
		$WL_Hover_Text_Color     = "#FFFFFF";
		$WL_Footer_Text_Color    = "#000000";
		$WL_Hover_Color_Opacity  = "yes";
		$WL_Font_Style           = "Arial";
		$WL_Custom_Css           = "";
	} else {
		$WRGF_Id               = $Id['id'];
		$WRGF_Gallery_Settings = "WRGF_Gallery_Settings_" . $WRGF_Id;
		$WRGF_Gallery_Settings = unserialize( get_post_meta( $WRGF_Id, $WRGF_Gallery_Settings, true ) );
		if ( count( $WRGF_Gallery_Settings ) ) {
			$WL_Show_Gallery_Title   = $WRGF_Gallery_Settings['WL_Show_Gallery_Title'];
			$WL_Show_Image_Label     = $WRGF_Gallery_Settings['WL_Show_Image_Label'];
			$WL_Image_Label_Position = $WRGF_Gallery_Settings['WL_Image_Label_Position'];
			$WL_Hover_Animation      = $WRGF_Gallery_Settings['WL_Hover_Animation'];
			$WL_Gallery_Layout       = $WRGF_Gallery_Settings['WL_Gallery_Layout'];
			$WL_Thumbnail_Layout     = $WRGF_Gallery_Settings['WL_Thumbnail_Layout'];
			$WL_Hover_Color          = $WRGF_Gallery_Settings['WL_Hover_Color'];
			$WL_Hover_Text_Color     = $WRGF_Gallery_Settings['WL_Hover_Text_Color'];
			$WL_Footer_Text_Color    = $WRGF_Gallery_Settings['WL_Footer_Text_Color'];
			$WL_Hover_Color_Opacity  = $WRGF_Gallery_Settings['WL_Hover_Color_Opacity'];
			$WL_Font_Style           = $WRGF_Gallery_Settings['WL_Font_Style'];
			$WL_Custom_Css           = $WRGF_Gallery_Settings['WL_Custom_Css'];
		}
	}

	$RGB           = WRGF_hex2rgb( $WL_Hover_Color );
	$HoverColorRGB = implode( ", ", $RGB );
	?>

    <style>
        #weblizar_<?php echo $WRGF_Id; ?> .wrgf-header-label {
            color: <?php echo $WL_Hover_Text_Color; ?> !important;
        }

        #weblizar_<?php echo $WRGF_Id; ?> .wrgf-footer-label {
            color: <?php echo $WL_Footer_Text_Color; ?> !important;
            font-size: 16px;
            margin-bottom: 5px;
            margin-top: 5px;
            text-align: center;
            font-weight: normal;
        }

        #weblizar_<?php echo $WRGF_Id; ?> .b-link-stroke .b-top-line {
            background: rgba(<?php echo $HoverColorRGB; ?>, <?php if( $WL_Hover_Color_Opacity=="yes"){echo "0.5";} else{echo "1.0";} ?>);
        }

        #weblizar_<?php echo $WRGF_Id; ?> .b-link-stroke .b-bottom-line {
            background: rgba(<?php echo $HoverColorRGB; ?>, <?php if( $WL_Hover_Color_Opacity=="yes"){echo "0.5";} else{echo "1.0";} ?>);
        }

        #weblizar_<?php echo $WRGF_Id; ?> .b-wrapper {
            font-family: <?php echo str_ireplace("+", " ", $WL_Font_Style); ?>;
        / / real name pass here
        }

        #weblizar_<?php echo $WRGF_Id; ?> .wrgf-header-label {
            font-family: <?php echo str_ireplace("+", " ", $WL_Font_Style); ?> !important;
        / / real name pass here
        }

        #weblizar_<?php echo $WRGF_Id; ?> .wrgf-footer-label {
            font-family: <?php echo str_ireplace("+", " ", $WL_Font_Style); ?> !important;
        / / real name pass here
        }

        @media (min-width: 992px) {
            .col-md-6 {
                width: 49.97% !important;
                padding-right: 10px;
                padding-left: 10px;
            }

            .col-md-4 {
                width: 33.30% !important;
                padding-right: 10px;
                padding-left: 10px;
            }

            .col-md-3 {
                width: 24.90% !important;
                padding-right: 10px;
                padding-left: 10px;
            }
        }

        <?php if ($WL_Image_Label_Position == "hover"){ ?>
        @media (max-width: 992px) {
            #weblizar_<?php echo $WRGF_Id; ?> .wrgf-header-label {
                display: none;
            }
        }

        @media (min-width: 993px) {
            #weblizar_<?php echo $WRGF_Id; ?> .wrgf-footer-label {
                display: none;
            }
        }

        <?php }else { ?>
        #weblizar_<?php echo $WRGF_Id; ?> .wrgf-header-label {
            display: none;
        }

        <?php }?>
        #weblizar_<?php echo $WRGF_Id; ?> a {
            border-bottom: none;
            overflow: hidden;
            float: left;
            margin-right: 0px;
            padding-left: 0px;
        }

        <?php echo $WL_Custom_Css; ?>
    </style>

	<?php

	/**
	 * Load All Image Gallery Custom Post Type
	 */
	$IG_CPT_Name  = "wrgf_gallery";
	$AllGalleries = array( 'p' => $Id['id'], 'post_type' => $IG_CPT_Name, 'orderby' => 'ASC' );
	$loop         = new WP_Query( $AllGalleries );
	?>
    <div class="gal-container">
		<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
            <!--get the post id-->
			<?php $post_id = get_the_ID(); ?>

			<?php if ( $WL_Show_Gallery_Title == "yes" ) { ?>
                <!-- gallery title -->
                <div style="font-weight: bolder; padding-bottom:10px; border-bottom:2px solid #cccccc; margin-bottom:20px; font-size:24px; font-family: <?php echo $WL_Font_Style; ?>">
					<?php echo get_the_title( $post_id ); ?>
                </div>
			<?php } ?>

            <div class="gallery1" id="weblizar_<?php echo get_the_ID(); ?>">
				<?php
				/**
				 * Get All Photos from Gallery Details Post Meta
				 */
				get_the_ID();
				$WRGF_AllPhotosDetails = unserialize( base64_decode( get_post_meta( get_the_ID(), 'wrgf_all_photos_details', true ) ) );
				$TotalImages           = get_post_meta( get_the_ID(), 'wrgf_total_images_count', true );
				$i                     = 1;

				foreach ( $WRGF_AllPhotosDetails as $WRGF_SinglePhotoDetails ) {
					$name = $WRGF_SinglePhotoDetails['wrgf_image_label'];
					$url  = $WRGF_SinglePhotoDetails['wrgf_image_url'];
					$url1 = $WRGF_SinglePhotoDetails['wrgf_12_thumb'];
					$url2 = $WRGF_SinglePhotoDetails['wrgf_346_thumb'];
					$url3 = $WRGF_SinglePhotoDetails['wrgf_12_same_size_thumb'];
					$url4 = $WRGF_SinglePhotoDetails['wrgf_346_same_size_thumb'];
					$i ++;

					if ( $name == "" ) {
						// if slide title blank then
						global $wpdb;
						$post_table_prefix = $wpdb->prefix . "posts";
						if ( $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT `post_title` FROM `$post_table_prefix` WHERE `guid` LIKE '%s'", $url ) ) ) {
							// attachment title as alt
							$slide_alt = $attachment[0];
							if ( empty( $attachment[0] ) ) {
								// post title as alt
								$slide_alt = get_the_title( $post_id );
							}
						}

					} else {
						// slide title as alt
						$slide_alt = $name;
					}

					if ( $WL_Gallery_Layout == "col-md-12" ) { // one column
						$Thummb_Url = $url;
					}
					if ( $WL_Gallery_Layout == "col-md-6" ) { // two column
						if ( $WL_Thumbnail_Layout == "same-size" ) {
							$Thummb_Url = $url3;
						}
						if ( $WL_Thumbnail_Layout == "masonry" ) {
							$Thummb_Url = $url1;
						}
						if ( $WL_Thumbnail_Layout == "original" ) {
							$Thummb_Url = $url;
						}
					}
					if ( $WL_Gallery_Layout == "col-md-4" || $WL_Gallery_Layout == "col-md-3" || $WL_Gallery_Layout == "col-md-2" ) {// 3 4 6 column
						if ( $WL_Thumbnail_Layout == "same-size" ) {
							$Thummb_Url = $url4;
						}
						if ( $WL_Thumbnail_Layout == "masonry" ) {
							$Thummb_Url = $url2;
						}
						if ( $WL_Thumbnail_Layout == "original" ) {
							$Thummb_Url = $url;
						}
					}

					?>
                    <div class="<?php echo $WL_Gallery_Layout; ?> col-sm-6 wl-gallery">
                        <div class="b-link-<?php echo $WL_Hover_Animation; ?> b-animate-go">

							<?php
							// swipe box
							?>
                            <a alt="<?php echo $name; ?>" class="swipebox" href="<?php echo $url; ?>"
                               title="<?php echo $name; ?>">
                                <img src="<?php echo $Thummb_Url; ?>" class="gall-img-responsive"
                                     alt="<?php echo $slide_alt; ?>">
                                <div class="b-wrapper">
									<?php if ( $WL_Gallery_Layout == "col-md-12" || $WL_Gallery_Layout == "col-md-6" || $WL_Gallery_Layout == "col-md-4" ) { ?>
										<?php if ( $WL_Show_Image_Label == "yes" ) { ?>
                                            <h2 class="b-from-left b-animate b-delay03 wrgf-header-label"><?php echo $name; ?> </h2>
										<?php } ?>
									<?php }
									?>
                                </div>
                            </a>
							<?php


							?>
                        </div>
						<?php if ( $WL_Show_Image_Label == "yes" ) { ?>
                            <h4 class="wrgf-footer-label"><?php echo $name; ?></h4>
						<?php } ?>
                    </div>
					<?php
				}
				?>
            </div>
		<?php endwhile; ?>
    </div>

    <!-- swipe box-->
    <script type="text/javascript">
        ;(function (jQuery) {
            jQuery('.swipebox').swipebox({
                hideBarsDelay: 0,
                hideCloseButtonOnMobile: false,
            });
        })(jQuery);

        jQuery('.gallery1').imagesLoaded(function () {
            jQuery('.gallery1').masonry({
                itemSelector: '.wl-gallery',
                isAnimated: true,
                isFitWidth: true
            });
        });
    </script>

	<?php wp_reset_query();

	return ob_get_clean();
}
?>