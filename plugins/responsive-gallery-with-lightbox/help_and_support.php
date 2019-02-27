<div class="plug-rgwl">
    <h2 class="head_title"><span><?php _e( 'Responsive Gallery With Lightbox', WRGF_TEXT_DOMAIN ); ?></span></h2> 
    
        <h3 class="head_desc"><?php _e( 'Responsive Gallery allow you to add unlimited images galleries integrated with light box', WRGF_TEXT_DOMAIN ); ?>,<?php _e( 'animation hover effects', WRGF_TEXT_DOMAIN ); ?>,<?php _e( 'font styles', WRGF_TEXT_DOMAIN ); ?>,<?php _e( 'colors', WRGF_TEXT_DOMAIN ); ?>
            .</h3> 
</div>

<p class="well"><?php _e( 'Rate Us', WRGF_TEXT_DOMAIN ); ?></p>
<h4 class="para"><?php _e( 'If you are enjoying using our', WRGF_TEXT_DOMAIN ); ?> <b>Responsive Gallery
    </b> <?php _e( 'plugin and find it useful', WRGF_TEXT_DOMAIN ); ?>
    , <?php _e( 'then please consider writing a positive feedback', WRGF_TEXT_DOMAIN ); ?>
    . <?php _e( 'Your feedback will help us to encourage and support the plugin continued development and better user support', WRGF_TEXT_DOMAIN ); ?>
    .</h4>
<div  class="star-rate">
    <a class="acl-rate-us" style="text-align:center; text-decoration: none;font:normal 30px; "
       href="https://wordpress.org/plugins/responsive-photo-gallery/#reviews" target="_blank">
        <span class="dashicons dashicons-star-filled"></span>
        <span class="dashicons dashicons-star-filled"></span>
        <span class="dashicons dashicons-star-filled"></span>
        <span class="dashicons dashicons-star-filled"></span>
        <span class="dashicons dashicons-star-filled"></span>
    </a>
</div>
<p class="well"><?php _e( 'Share Us Your Suggestion', WRGF_TEXT_DOMAIN ); ?></p>
<h4 class="para"><?php _e( 'If you have any suggestion or features in your mind then please share us', WRGF_TEXT_DOMAIN ); ?>
    . <?php _e( 'We will try our best to add them in this plugin', WRGF_TEXT_DOMAIN ); ?>.</h4>

<p class="well"><?php _e( 'Language Contribution', WRGF_TEXT_DOMAIN ); ?></p>
<h4 class="para"><?php _e( 'Translate this plugin into your language', WRGF_TEXT_DOMAIN ); ?></h4>
<h4 class="para"><span class="list_point"><?php _e( 'Question', WRGF_TEXT_DOMAIN ); ?></span>
    : <?php _e( 'How to convert Plguin into My Language ', WRGF_TEXT_DOMAIN ); ?>?</h4>
<h4 class="para"><span class="list_point"><?php _e( 'Answer', WRGF_TEXT_DOMAIN ); ?></span>
    : <?php _e( 'Contact as to', WRGF_TEXT_DOMAIN ); ?>
    lizarweb@gmail.com <?php _e( 'for translate this plugin into your language', WRGF_TEXT_DOMAIN ); ?>.</h4>

<div class="rgwl">
    <h2><?php _e( 'Change Old Server Image URL', WRGF_TEXT_DOMAIN ); ?></h2>
    <form action="" method="post">
        <input type="submit" value="Change image URL" name="wrgfchangeurl" class="btn btn-primary btn-lg">
        <h6><b><?php _e( 'Note', WRGF_TEXT_DOMAIN ); ?>&nbsp;:&nbsp;</b><?php _e( 'Use this option after import', WRGF_TEXT_DOMAIN ); ?><b>Responsive Gallery</b> <?php _e( 'to change old server image url to new server image url', WRGF_TEXT_DOMAIN ); ?>.
        </h6>
    </form>
</div>

<?php
if ( isset( $_REQUEST['wrgfchangeurl'] ) ) {
    $all_posts = wp_count_posts( 'wrgf_gallery' )->publish;
    $args      = array( 'post_type' => 'wrgf_gallery', 'posts_per_page' => $all_posts );
    global $wrgf_galleries;
    $wrgf_galleries = new WP_Query( $args );

    while ( $wrgf_galleries->have_posts() ) : $wrgf_galleries->the_post();

        $WRGF_Id               = get_the_ID();
        $WRGF_AllPhotosDetails = unserialize( base64_decode( get_post_meta( $WRGF_Id, 'wrgf_all_photos_details', true ) ) );

        $TotalImages = get_post_meta( $WRGF_Id, 'wrgf_total_images_count', true );

        if ( $TotalImages ) {
            foreach ( $WRGF_AllPhotosDetails as $WRGF_SinglePhotoDetails ) {
                $name = $WRGF_SinglePhotoDetails['wrgf_image_label'];
                $url  = $WRGF_SinglePhotoDetails['wrgf_image_url'];
                $url1 = $WRGF_SinglePhotoDetails['wrgf_12_thumb'];
                $url2 = $WRGF_SinglePhotoDetails['wrgf_346_thumb'];
                $url3 = $WRGF_SinglePhotoDetails['wrgf_12_same_size_thumb'];
                $url4 = $WRGF_SinglePhotoDetails['wrgf_346_same_size_thumb'];

                $upload_dir = wp_upload_dir();
                $data       = $url;
                if ( strpos( $data, 'uploads' ) !== false ) {
                    list( $oteher_path, $image_path ) = explode( "uploads", $data );
                    $url = $upload_dir['baseurl'] . $image_path;
                }

                $data = $url1;
                if ( strpos( $data, 'uploads' ) !== false ) {
                    list( $oteher_path, $image_path ) = explode( "uploads", $data );
                    $url1 = $upload_dir['baseurl'] . $image_path;
                }

                $data = $url2;
                if ( strpos( $data, 'uploads' ) !== false ) {
                    list( $oteher_path, $image_path ) = explode( "uploads", $data );
                    $url2 = $upload_dir['baseurl'] . $image_path;
                }

                $data = $url3;
                if ( strpos( $data, 'uploads' ) !== false ) {
                    list( $oteher_path, $image_path ) = explode( "uploads", $data );
                    $url3 = $upload_dir['baseurl'] . $image_path;
                }

                $data = $url4;
                if ( strpos( $data, 'uploads' ) !== false ) {
                    list( $oteher_path, $image_path ) = explode( "uploads", $data );
                    $url4 = $upload_dir['baseurl'] . $image_path;
                }

                $ImagesArray[] = array(
                    'wrgf_image_label'         => $name,
                    'wrgf_image_url'           => $url,
                    'wrgf_12_thumb'            => $url1,
                    'wrgf_346_thumb'           => $url2,
                    'wrgf_12_same_size_thumb'  => $url3,
                    'wrgf_346_same_size_thumb' => $url4
                );

            }
            update_post_meta( $WRGF_Id, 'wrgf_all_photos_details', base64_encode( serialize( $ImagesArray ) ) );
            $ImagesArray = "";
        }
    endwhile;
}
?>

<style>
    body {
        /* This has to be same as the text-shadows below */
        background: #fafafa;
    }

    .acl-rate-us span.dashicons {
        width: 30px;
        height: 30px;
    }

    .acl-rate-us span.dashicons-star-filled:before {
        content: "\f155";
        font-size: 30px;
    }

    .acl-rate-us {
        color: #FBD229 !important;
        padding-top: 5px !important;
    }

    .acl-rate-us span {
        display: inline-block;
    }

    h1 {
        font-family: Helvetica, Arial, sans-serif;
        font-weight: bold;
        font-size: 6em;
        line-height: 1em;
    }

    .inset-text {
        /* Shadows are visible under slightly transparent text color */
        color: rgba(10, 60, 150, 0.8);
        text-shadow: 1px 4px 6px #def, 0 0 0 #000, 1px 4px 6px #def;
    }

    /* Don't show shadows when selecting text */
    ::-moz-selection {
        background: #5af;
        color: #fff;
        text-shadow: none;
    }

    ::selection {
        background: #5af;
        color: #fff;
        text-shadow: none;
    }

    .well {
        min-height: 20px;
        padding: 19px;
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 20px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
    }

    .head_title {
        color: red;
        font-size: 30px;
    }

    .head_meta_title {
        color: #000;
        font-size: 26px;
    }

    .para {
        padding-left: 25px;
        font-size: 15px;
        font-weight: 600;
    }

    .list_point {
        color: #006799;
        font-weight: 700;
    }

    .plug_list_point {
        color: red;
        font-weight: 700;
    }

    .chng_btn {
        margin-top: 0px;
        margin-right: 10px;
        font-size: 18px;
        font-weight: 700;
        margin-left: 30px;
        color: #fff;
        background: #dc3232;
        text-decoration: none;
    }

    h3.head_desc {
        padding-top: 16px;
        padding-bottom: 20px;
    }

    .detail_btn {
        text-decoration: none;
        color: #000;
        background-color: #7bbaca;
        padding: 4px;
        border-radius: 4px;
        border-right: #ff003b solid 3px;
    }

    .detail_btn:hover {
        color: #000;
    }
</style>