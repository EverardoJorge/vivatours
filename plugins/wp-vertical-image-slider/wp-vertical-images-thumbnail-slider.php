<?php
    /* 
    Plugin Name: WordPress vertical Thumbnail Slider
    Plugin URI:http://www.i13websolution.com/wordpress-pro-plugins/wordpress-vertical-image-slider-pro-plugin.html
    Author URI:http://www.i13websolution.com/wordpress-pro-plugins/wordpress-vertical-image-slider-pro-plugin.html
    Description: This is beautiful thumbnail image slider plugin for WordPress.Add any number of images from admin panel.
    Author:I Thirteen Web Solution 
    Version:1.2.4
    Text Domain:wp-vertical-image-slider
    Domain Path:/languages
    */

    add_action('admin_menu', 'add_vertical_thumbnail_slider_admin_menu');
   // add_action( 'admin_init', 'my_vertical_thumbnailSlider_admin_init' );
    register_activation_hook(__FILE__,'install_vertical_thumbnailSlider');
    add_action('wp_enqueue_scripts', 'vertical_thumbnail_slider_load_styles_and_js');
    add_shortcode('print_vertical_thumbnail_slider', 'print_vertical_thumbnail_slider_func' );
    add_filter('widget_text', 'do_shortcode');
    add_action('admin_notices', 'vertical_thumbnail_slider_admin_notices');
    
    add_action('plugins_loaded', 'vts_load_lang_for_responsive_vertical_thumbnail_slider');

    function vts_load_lang_for_responsive_vertical_thumbnail_slider() {
            
            load_plugin_textdomain( 'wp-vertical-image-slider', false, basename( dirname( __FILE__ ) ) . '/languages/' );
    }

    function vertical_thumbnail_slider_load_styles_and_js(){
        if (!is_admin()) {                                                       

            wp_enqueue_style( 'images-vertical-thumbnail-slider-style', plugins_url('/css/images-vertical-thumbnail-slider-style.css', __FILE__) );
            wp_enqueue_style( 'vertical-responsive', plugins_url('/css/vertical-responsive.css', __FILE__) );
          
            wp_enqueue_script('jquery'); 
            wp_enqueue_script('images-vertical-thumbnail-slider-jc',plugins_url('/js/images-vertical-thumbnail-slider-jc.js', __FILE__));
            wp_enqueue_script('responsive-vertical-thumbnail-slider-jc',plugins_url('/js/responsive-vertical-thumbnail-slider-jc.js', __FILE__));

        }  
    }

    function install_vertical_thumbnailSlider(){
        global $wpdb;
        $table_name = $wpdb->prefix . "vertical_thumbnail_slider";

        $sql = "CREATE TABLE " . $table_name . " (
        id int(10) unsigned NOT NULL auto_increment,
        title varchar(1000) NOT NULL,
        image_name varchar(500) NOT NULL,
        createdon datetime NOT NULL,
        custom_link varchar(1000) default NULL,
        post_id int(10) unsigned default NULL,
        PRIMARY KEY  (id)
        );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $vertical_thumbnail_slider_settings=array('linkimage' => '1','pauseonmouseover' => '1','auto' =>'','speed' => '1000','circular' => '1','imageheight' => '120','imagewidth' => '120','visible'=> '5','scroll' => '1','resizeImages'=>'0','scollerBackground'=>'#FFFFFF','is_responsive'=>'0','pause'=>'1000','imageMargin'=>'5');

        if( !get_option( 'vertical_thumbnail_slider_settings' ) ) {

            update_option('vertical_thumbnail_slider_settings',$vertical_thumbnail_slider_settings);
        }else{
            
            $opts=get_option( 'vertical_thumbnail_slider_settings' ) ;
            if(!isset($opts['is_responsive'])){
                
                $opts['is_responsive']='0';
            }
            
            if(!isset($opts['pause'])){
                
                $opts['pause']='1000';
            }
            
            if(!isset($opts['imageMargin'])){
                
                $opts['imageMargin']='5';
            }
            
            update_option('vertical_thumbnail_slider_settings',$opts);
        } 
        
        
        $uploads = wp_upload_dir();
        $baseDir=$uploads['basedir'];
        $baseDir=str_replace("\\","/",$baseDir);
        $pathToImagesFolder=$baseDir.'/wp-vertical-image-slider';
        wp_mkdir_p($pathToImagesFolder);  

    } 

    
     function vertical_thumbnail_slider_admin_notices(){
        
        $uploads = wp_upload_dir();
        $baseDir=$uploads['basedir'];
        $baseDir=str_replace("\\","/",$baseDir);
        $pathToImagesFolder=$baseDir.'/wp-vertical-image-slider';
        
        $baseurl=$uploads['baseurl'];
        $baseurl.='/wp-vertical-image-slider/';
        
         if (is_plugin_active('wp-vertical-image-slider/wp-vertical-image-slider.php')) {
            
            $uploads = wp_upload_dir();
            $baseDir=$uploads['basedir'];
            $baseDir=str_replace("\\","/",$baseDir);
            $pathToImagesFolder=$baseDir.'/wp-vertical-image-slider';
            
            if(file_exists($pathToImagesFolder) and is_dir($pathToImagesFolder)){
                
                if( !is_writable($pathToImagesFolder)){
                        echo "<div class='updated'><p>".__( 'Vertical Image Slider is active but does not have write permission on','wp-vertical-image-slider').'</p><p><b>".$pathToImagesFolder."</b>'.__( 'directory.Please allow write permission.','wp-vertical-image-slider')."</p></div> ";
                }       
            }
            else{
               
                  wp_mkdir_p($pathToImagesFolder);  
                  if(!file_exists($pathToImagesFolder) and !is_dir($pathToImagesFolder)){
                    echo "<div class='updated'><p>".__('Vertical Image Slider is active but plugin does not have permission to create directory','wp-vertical-image-slider')."</p><p><b>".$pathToImagesFolder."</b> .".__('Please create Vertical Image Slider directory inside upload directory and allow write permission.','wp-vertical-image-slider')."</p></div> "; 
                    
                  }
            }
        }
        
    }
     

    function add_vertical_thumbnail_slider_admin_menu(){

        $hook_suffix_v_l=add_menu_page( __( 'Vertical Thumbnail Slider','wp-vertical-image-slider'), __( 'Vertical Thumbnail Slider','wp-vertical-image-slider' ), 'administrator', 'vertical_thumbnail_slider', 'vertical_thumbnail_slider_admin_options' );
        $hook_suffix_v_l=add_submenu_page( 'vertical_thumbnail_slider', __( 'Slider Setting','wp-vertical-image-slider'), __( 'Slider Setting','wp-vertical-image-slider' ),'administrator', 'vertical_thumbnail_slider', 'vertical_thumbnail_slider_admin_options' );
        $hook_suffix_v_l_1=add_submenu_page( 'vertical_thumbnail_slider', __( 'Manage Images','wp-vertical-image-slider'), __( 'Manage Images','wp-vertical-image-slider'),'administrator', 'vertical_thumbnail_slider_image_management', 'vertical_thumbnail_image_management' );
        $hook_suffix_v_l_2=add_submenu_page( 'vertical_thumbnail_slider', __( 'Preview Slider','wp-vertical-image-slider'), __( 'Preview Slider','wp-vertical-image-slider'),'administrator', 'vertical_thumbnail_slider_preview', 'verticalpreviewSliderAdmin' );

        add_action( 'load-' . $hook_suffix_v_l , 'my_vertical_thumbnailSlider_admin_init' );
        add_action( 'load-' . $hook_suffix_v_l_1 , 'my_vertical_thumbnailSlider_admin_init' );
        add_action( 'load-' . $hook_suffix_v_l_2 , 'my_vertical_thumbnailSlider_admin_init' );
    }

    function my_vertical_thumbnailSlider_admin_init(){

        $url = plugin_dir_url(__FILE__);  

        wp_enqueue_script( 'jquery.validate', $url.'js/jquery.validate.js' );  
        wp_enqueue_script( 'jc', $url.'js/images-vertical-thumbnail-slider-jc.js' );  
        wp_enqueue_script( 'responsive-vertical-thumbnail-slider-jc', $url.'js/responsive-vertical-thumbnail-slider-jc.js' );  
        wp_enqueue_style('images-vertical-thumbnail-slider-style',$url.'css/images-vertical-thumbnail-slider-style.css');
        wp_enqueue_style('vertical-responsive',$url.'css/vertical-responsive.css');
        vertical_thumbnail_slider_admin_scripts_init();
        
    }

    function vertical_thumbnail_slider_admin_options(){

        if(isset($_POST['btnsave'])){
            
            if(!check_admin_referer( 'action_settings_add_edit','add_edit_nonce' )){
                
                 wp_die('Security check fail'); 
            }

            $auto=trim($_POST['isauto']);

            if($auto=='auto')
              $auto=true;
            else if($auto=='manuall')
              $auto=false; 
            else
              $auto=2; 

            $speed=(int)trim(htmlentities(strip_tags($_POST['speed']),ENT_QUOTES));
            $pause=(int)trim(htmlentities(strip_tags($_POST['pause']),ENT_QUOTES));

            if(isset($_POST['circular']))
                $circular=true;  
            else
                $circular=false;  

       
            $visible=trim($_POST['visible']);


            if(isset($_POST['pauseonmouseover']))
                $pauseonmouseover=true;  
            else 
                $pauseonmouseover=false;

            if(isset($_POST['linkimage']))
                $linkimage=true;  
            else 
                $linkimage=false;

            $scroll=trim($_POST['scroll']);

            if($scroll=="")
                $scroll=1;

            $imageMargin=(int)trim(htmlentities(strip_tags($_POST['imageMargin']),ENT_QUOTES));  
            $is_responsive=htmlentities(strip_tags($_POST['is_responsive'],ENT_QUOTES));
            $imageheight=(int)trim(htmlentities(strip_tags($_POST['imageheight']),ENT_QUOTES));
            $imagewidth=(int)trim(htmlentities(strip_tags($_POST['imagewidth']),ENT_QUOTES));
            $resizeImages=(int)trim(htmlentities(strip_tags($_POST['resizeImages']),ENT_QUOTES));
            $scollerBackground=trim(htmlentities(strip_tags($_POST['scollerBackground']),ENT_QUOTES));

            $options=array();
            $options['linkimage']=$linkimage;  
            $options['pauseonmouseover']=$pauseonmouseover;  
            $options['auto']=$auto;  
            $options['speed']=$speed;  
            $options['pause']=$pause;  
            $options['circular']=$circular;  
            //$options['scrollerwidth']=$scrollerwidth;  
            $options['imageheight']=$imageheight;  
            $options['imagewidth']=$imagewidth;  
            $options['visible']=$visible;  
            $options['scroll']=$scroll;  
            $options['resizeImages']=$resizeImages;  
            $options['scollerBackground']=$scollerBackground;  
            $options['is_responsive']=$is_responsive;  
            $options['imageMargin']=$imageMargin;  


            $settings=update_option('vertical_thumbnail_slider_settings',$options); 
            $vertical_thumbnail_slider_messages=array();
            $vertical_thumbnail_slider_messages['type']='succ';
            $vertical_thumbnail_slider_messages['message']=__( 'Settings saved successfully.','wp-vertical-image-slider');
            update_option('vertical_thumbnail_slider_messages', $vertical_thumbnail_slider_messages);



        }  
        $settings=get_option('vertical_thumbnail_slider_settings');

    ?>      
    <div id="poststuff" >  
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <table><tr>
                        <td>
                          <div class="fb-like" data-href="https://www.facebook.com/i13websolution" data-layout="button" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                          <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=158817690866061&autoLogAppEvents=1';
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                      </td>
                        <td>
                            <a target="_blank" title="Donate" href="http://www.i13websolution.com/donate-wordpress_image_thumbnail.php">
                                <img id="help us for free plugin" height="30" width="90" src="<?php echo plugins_url( 'images/paypaldonate.jpg', __FILE__ );?>" border="0" alt="help us for free plugin" title="help us for free plugin">
                            </a>
                        </td>
                    </tr>
                </table>

                <?php
                    $messages=get_option('vertical_thumbnail_slider_messages'); 
                    $type='';
                    $message='';
                    if(isset($messages['type']) and $messages['type']!=""){

                        $type=$messages['type'];
                        $message=$messages['message'];

                    }  


                if(trim($type)=='err'){ echo "<div class='notice notice-error is-dismissible'><p>"; echo $message; echo "</p></div>";}
                else if(trim($type)=='succ'){ echo "<div class='notice notice-success is-dismissible'><p>"; echo $message; echo "</p></div>";}
        

                    update_option('vertical_thumbnail_slider_messages', array());     
                ?>      

                <span><h3 style="color: blue;"><a target="_blank" href="http://www.i13websolution.com/wordpress-pro-plugins/wordpress-vertical-image-slider-pro-plugin.html">UPGRADE TO PRO VERSION</a></h3></span>

                <h2><?php echo __( 'Slider Settings','wp-vertical-image-slider');?></h2>
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        <div id="post-body-content">
                            <form method="post" action="" id="scrollersettiings" name="scrollersettiings" >

                                <div class="stuffbox" id="namediv" style="width:100%;">
                                        <h3><label><?php echo __( 'Responsive Slider?','wp-vertical-image-slider');?></label></h3>
                                       <div class="inside">
                                            <table>
                                              <tr>
                                                <td>
                                                    <input style="width:20px;" type='radio' <?php if($settings['is_responsive']==true){echo "checked='checked'";}?>  name='is_responsive' value='1' id="is_responsive_yes" ><?php echo __( 'Yes','wp-vertical-image-slider');?> &nbsp;<input style="width:20px;" type='radio' name='is_responsive' <?php if($settings['is_responsive']==false){echo "checked='checked'";} ?> value='0' id="is_responsive_no" ><?php echo __( 'No','wp-vertical-image-slider');?>
                                                  <div style="clear:both"></div>
                                                  <div></div>
                                                </td>
                                              </tr>
                                            </table>
                                            <div style="clear:both"></div>
                                        </div>
                                 </div>    
                                <div class="stuffbox" id="namediv" style="">
                                    <h3><label><?php echo __( 'Link images with url ?','wp-vertical-image-slider');?></label></h3>
                                    <div class="inside">
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" id="linkimage" size="30" name="linkimage" value="" <?php if($settings['linkimage']==true){echo "checked='checked'";} ?> style="width:20px;">&nbsp;<?php echo __( 'Add link to image?','wp-vertical-image-slider');?> 
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <div style="clear:both"></div>
                                    </div>
                                </div>
                                <div class="stuffbox" id="namediv" style="width:100%;">
                                  <h3><label><?php echo __( 'Auto Scroll ?','wp-vertical-image-slider');?></label></h3>
                                  <div class="inside">
                                      <table>
                                          <tr>
                                              <td>
                                                  <?php $settings['auto']=(int)$settings['auto'];?>
                                                  <input style="width:20px;" type='radio' <?php if($settings['auto']==1){echo "checked='checked'";}?>  name='isauto' value='auto' ><?php echo __( 'Auto','wp-vertical-image-slider');?> &nbsp;<input style="width:20px;" type='radio' name='isauto' <?php if($settings['auto']==0){echo "checked='checked'";} ?> value='manuall' ><?php echo __( 'Scroll By Left & Right Arrow ','wp-vertical-image-slider');?>&nbsp; &nbsp;<input style="width:20px;" type='radio' name='isauto' <?php if($settings['auto']==2){echo "checked='checked'";} ?> value='both' ><?php echo __( 'Scroll Auto With Arrow','wp-vertical-image-slider');?>
                                                  <div style="clear:both"></div>
                                                  <div></div>
                                              </td>
                                          </tr>
                                      </table>
                                      <div style="clear:both"></div>
                                  </div>
                              </div>
                                <div class="stuffbox" id="namediv" style="">
                                    <h3><label ><?php echo __( 'Speed','wp-vertical-image-slider');?></label></h3>
                                    <div class="inside">
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="text" id="speed" size="30" name="speed" value="<?php echo $settings['speed']; ?>" style="width:100px;">
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <div style="clear:both"></div>

                                    </div>
                                </div>
                                <div class="stuffbox" id="pauseclass" style="width:100%;" >
                                    <h3><label ><?php echo __( 'Pause','wp-vertical-image-slider');?></label></h3>
                                   <div class="inside">
                                        <table>
                                          <tr>
                                            <td>
                                              <input type="text" id="pause" size="30" name="pause" value="<?php echo $settings['pause']; ?>" style="width:100px;">
                                                 <div style="clear:both;margin-top:3px"><?php echo __( 'Example 1000','wp-vertical-image-slider');?></div>
                                                 <div></div>
                                            </td>
                                          </tr>
                                        </table>
                                        <div style="clear:both"><?php echo __('The amount of time (in ms) between each auto transition','wp-vertical-image-slider');?></div>
                                    </div>
                                 </div>
                                <div class="stuffbox" id="namediv" style="">
                                    <h3><label ><?php echo __( 'Circular Slider?','wp-vertical-image-slider');?></label></h3>
                                    <div class="inside">
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" id="circular" size="30" name="circular" value="" <?php if($settings['circular']==true){echo "checked='checked'";} ?> style="width:20px;">&nbsp;Circular Slider ? 
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <div style="clear:both"></div>

                                    </div>
                                </div>
                                <div class="stuffbox" id="namediv" style="">
                                    <h3><label><?php echo __( 'Slider Background color','wp-vertical-image-slider');?></label></h3>
                                    <div class="inside">
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="text" id="scollerBackground" size="30" name="scollerBackground" value="<?php echo $settings['scollerBackground']; ?>" style="width:100px;">
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                        </table>

                                        <div style="clear:both"></div>
                                    </div>
                                </div>
                                <div class="stuffbox" id="namediv" style="">
                                    <h3><label><?php echo __( 'Visible','wp-vertical-image-slider');?></label></h3>
                                    <div class="inside">
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="text" id="visible" size="30" name="visible" value="<?php echo $settings['visible']; ?>" style="width:100px;">
                                                    <div style="clear:both"><?php echo __( 'This will decide your slider height automatically','wp-vertical-image-slider');?></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <?php echo __( 'Specify the number of items visible at all times within the slider.','wp-vertical-image-slider');?>
                                        <div style="clear:both"></div>

                                    </div>
                                </div>
                                <div class="stuffbox" id="namediv" style="">
                                    <h3><label><?php echo __( 'Scroll','wp-vertical-image-slider');?></label></h3>
                                    <div class="inside">
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="text" id="scroll" size="30" name="scroll" value="<?php echo $settings['scroll']; ?>" style="width:100px;">
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <?php echo __( 'You can specify the number of items to scroll when you click the next or prev buttons.','wp-vertical-image-slider');?>
                                        <div style="clear:both"></div>
                                    </div>
                                </div>
                                <div class="stuffbox" id="namediv" style="">
                                    <h3><label><?php echo __( 'Pause On Mouse Over?','wp-vertical-image-slider');?></label></h3>
                                    <div class="inside">
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" id="pauseonmouseover" size="30" name="pauseonmouseover" value="" <?php if($settings['pauseonmouseover']==true){echo "checked='checked'";} ?> style="width:20px;">&nbsp;Pause On Mouse Over ? 
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <div style="clear:both"></div>
                                    </div>
                                </div>
                           
                                <div class="stuffbox" id="namediv" style="">
                                    <h3><label><?php echo __( 'Image Height','wp-vertical-image-slider');?></label></h3>
                                    <div class="inside">
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="text" id="imageheight" size="30" name="imageheight" value="<?php echo $settings['imageheight']; ?>" style="width:100px;">
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                        </table>

                                        <div style="clear:both"></div>
                                    </div>
                                </div>
                                <div class="stuffbox" id="namediv" style="">
                                    <h3><label><?php echo __( 'Image Width','wp-vertical-image-slider');?></label></h3>
                                    <div class="inside">
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="text" id="imagewidth" size="30" name="imagewidth" value="<?php echo $settings['imagewidth']; ?>" style="width:100px;">
                                                    <div style="clear:both"></div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                        </table>

                                        <div style="clear:both"></div>
                                    </div>
                                </div>
                                <div class="stuffbox" id="namediv" style="">
                                    <h3><label><?php echo __( 'Physically resize images?','wp-vertical-image-slider');?></label></h3>
                                    <div class="inside">
                                        <table>
                                            <tr>
                                                <td>
                                                    <input style="width:20px;" type='radio' <?php if($settings['resizeImages']==1){echo "checked='checked'";}?>  name='resizeImages' value='1' ><?php echo __( 'Yes','wp-vertical-image-slider');?> &nbsp;<input style="width:20px;" type='radio' name='resizeImages' <?php if($settings['resizeImages']==0){echo "checked='checked'";} ?> value='0' ><?php echo __( 'Resize using css','wp-vertical-image-slider');?>
                                                    <div style="clear:both;padding-top:5px"><?php echo __( 'If you choose ','wp-vertical-image-slider');?>"<b><?php echo __( 'resize using css','wp-vertical-image-slider');?></b>" <?php echo __( 'the quality will be good but some times large images takes time to load','wp-vertical-image-slider');?> </div>
                                                    <div></div>
                                                </td>
                                            </tr>
                                        </table>
                                        <div style="clear:both"></div>
                                    </div>
                                </div>
                                <div class="stuffbox" id="image_margin" style="width:100%;">
                                        <h3><label><?php echo __( 'Image Margin','wp-vertical-image-slider');?></label></h3>
                                       <div class="inside">
                                            <table>
                                              <tr>
                                                <td>
                                                  <input type="text" id="imageMargin" size="30" name="imageMargin" value="<?php echo $settings['imageMargin']; ?>" style="width:100px;">
                                                  <div style="clear:both;padding-top:5px"><?php echo __( 'Gap between two images','wp-vertical-image-slider');?> </div>
                                                  <div></div>
                                                </td>
                                              </tr>
                                            </table>

                                            <div style="clear:both"></div>
                                        </div>
                                     </div>
                                <input type="submit"  name="btnsave" id="btnsave" value="<?php echo __( 'Save Changes','wp-vertical-image-slider');?>" class="button-primary">&nbsp;&nbsp;<input type="button" name="cancle" id="cancle" value="<?php echo __( 'Cancel','wp-vertical-image-slider');?>" class="button-primary" onclick="location.href='admin.php?page=vertical_thumbnail_slider_image_management'">
                                <?php wp_nonce_field('action_settings_add_edit','add_edit_nonce'); ?>
                            </form> 
                            <script type="text/javascript">

                                var $n = jQuery.noConflict();  
                                $n(document).ready(function() {

                                        $n("#scrollersettiings").validate({
                                                rules: {
                                                    isauto: {
                                                        required:true
                                                    },speed: {
                                                        required:true, 
                                                        number:true, 
                                                        maxlength:15
                                                    },
                                                    pause: {
                                                            required:true,
                                                            digits:true, 
                                                            maxlength:15
                                                        },
                                                    visible:{
                                                        required:true, 
                                                        number:true,
                                                        maxlength:15

                                                    },
                                                    scroll:{
                                                        required:true,
                                                        number:true,
                                                        maxlength:15  
                                                    },
                                                    scollerBackground:{
                                                        required:true,
                                                        maxlength:7  
                                                    },
                                                    /*scrollerwidth:{
                                                    required:true,
                                                    number:true,
                                                    maxlength:15    
                                                    },*/imageheight:{
                                                        required:true,
                                                        number:true,
                                                        maxlength:15    
                                                    },
                                                    imagewidth:{
                                                        required:true,
                                                        number:true,
                                                        maxlength:15    
                                                    },
                                                    imageMargin:{
                                                            required:true,
                                                            number:true,
                                                            maxlength:15    
                                                          }

                                                },
                                                errorClass: "image_error",
                                                errorPlacement: function(error, element) {
                                                    error.appendTo( element.next().next());
                                                } 


                                        })
                                        
                                         $n('#scollerBackground').wpColorPicker();
                                         
                                         
                                         $n('input[name=is_responsive]').change(function() {
                                        
                                        if($n('input[name=is_responsive]:checked').val()==1){
                                            
                                            $n("#pauseclass").show();
                                            $n("#image_margin").show();
                                            
                                            $n("#pause").rules('add', {
                                                            required: function(element) {
                                                                return $n('input[name=is_responsive]:checked').val()=='1';
                                                                },
                                                                digits:true, 
                                                                 maxlength:15
                                                        });
                                                        
                                            $n("#imageMargin").rules('add', {
                                                            required: function(element) {
                                                                return $n('input[name=is_responsive]:checked').val()=='1';
                                                                },
                                                                digits:true, 
                                                                 maxlength:15
                                                        });
                                                        
                                            
                                        }else{
                                            
                                            $n("#image_margin").hide();
                                            $n("#pauseclass").hide();
                                            $n('#pause').rules('remove', 'required'); 
                                            $n('#imageMargin').rules('remove', 'required'); 
                                        }
                                        
                                      });
                                      
                                        $n('input[name=is_responsive]').trigger( "change" );
                                });

                            </script> 

                        </div>
                    </div>
                </div>  
            </div>      

            <div id="postbox-container-1" class="postbox-container" style="margin-top: 15px;"> 

                <div class="postbox"> 
                    <h3 class="hndle"><span></span><?php echo __( 'Access All Themes In One Price','wp-vertical-image-slider');?></h3> 
                    <div class="inside">
                        <center><a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715_0_1_10" target="_blank"><img border="0" src="<?php echo plugins_url( 'images/300x250.gif', __FILE__ );?>" width="250" height="250"></a></center>

                        <div style="margin:10px 5px">

                        </div>
                    </div>
                </div>
                <div class="postbox"> 
                <h3 class="hndle"><span></span><?php echo __('Google For Business Coupon','wp-vertical-image-slider');?></h3> 
                    <div class="inside">
                        <center><a href="https://goo.gl/OJBuHT" target="_blank">
                                <img src="<?php echo plugins_url( 'images/g-suite-promo-code-4.png', __FILE__ );?>" width="250" height="250" border="0">
                            </a></center>
                        <div style="margin:10px 5px">
                        </div>
                    </div>
                    
                </div>
            </div>                                                 
            <div class="clear"></div>
        </div>
    </div>  
    <?php
    }        
    function vertical_thumbnail_image_management(){

        $uploads = wp_upload_dir();
        $baseDir=$uploads['basedir'];
        $baseDir=str_replace("\\","/",$baseDir);
        $pathToImagesFolder=$baseDir.'/wp-vertical-image-slider';
        
        $baseurl=$uploads['baseurl'];
        $baseurl.='/wp-vertical-image-slider/';
        
        $action='gridview';
        global $wpdb;


        if(isset($_GET['action']) and $_GET['action']!=''){


            $action=trim($_GET['action']);
        }

    ?>

    <?php 
        if(strtolower($action)==strtolower('gridview')){ 


            $wpcurrentdir=dirname(__FILE__);
            $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);



        ?> 
        <!--[if !IE]><!-->
        <style type="text/css">

            @media only screen and (max-width: 800px) {

                /* Force table to not be like tables anymore */
                #no-more-tables table, 
                #no-more-tables thead, 
                #no-more-tables tbody, 
                #no-more-tables th, 
                #no-more-tables td, 
                #no-more-tables tr { 
                    display: block; 

                }

                /* Hide table headers (but not display: none;, for accessibility) */
                #no-more-tables thead tr { 
                    position: absolute;
                    top: -9999px;
                    left: -9999px;
                }

                #no-more-tables tr { border: 1px solid #ccc; }

                #no-more-tables td { 
                    /* Behave  like a "row" */
                    border: none;
                    border-bottom: 1px solid #eee; 
                    position: relative;
                    padding-left: 50%; 
                    white-space: normal;
                    text-align:left;      
                }

                #no-more-tables td:before { 
                    /* Now like a table header */
                    position: absolute;
                    /* Top/left values mimic padding */
                    top: 6px;
                    left: 6px;
                    width: 45%; 
                    padding-right: 10px; 
                    white-space: nowrap;
                    text-align:left;
                    font-weight: bold;
                }

                /*
                Label the data
                */
                #no-more-tables td:before { content: attr(data-title); }
            }
        </style>
        <!--<![endif]-->
        <style type="text/css">
            .pagination {
                clear:both;
                padding:20px 0;
                position:relative;
                font-size:11px;
                line-height:13px;
            }

            .pagination span, .pagination a {
                display:block;
                float:left;
                margin: 2px 2px 2px 0;
                padding:6px 9px 5px 9px;
                text-decoration:none;
                width:auto;
                color:#fff;
                background: #555;
            }

            .pagination a:hover{
                color:#fff;
                background: #3279BB;
            }

            .pagination .current{
                padding:6px 9px 5px 9px;
                background: #3279BB;
                color:#fff;
            }
        </style>  
        <div id="poststuff"  class="wrap">
            <div id="post-body" class="metabox-holder columns-2">
                <table><tr>
                        <td>
                          <div class="fb-like" data-href="https://www.facebook.com/i13websolution" data-layout="button" data-action="like" data-size="large" data-show-faces="false" data-share="false"></div>
                          <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2&appId=158817690866061&autoLogAppEvents=1';
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                      </td>
                        <td>
                            <a target="_blank" title="Donate" href="http://www.i13websolution.com/donate-wordpress_image_thumbnail.php">
                                <img id="help us for free plugin" height="30" width="90" src="<?php echo plugins_url( 'images/paypaldonate.jpg', __FILE__ );?>" border="0" alt="help us for free plugin" title="help us for free plugin">
                            </a>
                        </td>
                    </tr>
                </table>

                <?php 

                    $messages=get_option('vertical_thumbnail_slider_messages'); 
                    $type='';
                    $message='';
                    if(isset($messages['type']) and $messages['type']!=""){

                        $type=$messages['type'];
                        $message=$messages['message'];

                    }  


                     if(trim($type)=='err'){ echo "<div class='notice notice-error is-dismissible'><p>"; echo $message; echo "</p></div>";}
                     else if(trim($type)=='succ'){ echo "<div class='notice notice-success is-dismissible'><p>"; echo $message; echo "</p></div>";}
        


                    update_option('vertical_thumbnail_slider_messages', array());     
                ?>


                <div id="post-body-content" >  
                    <span><h3 style="color: blue;"><a target="_blank" href="http://www.i13websolution.com/wordpress-pro-plugins/wordpress-vertical-image-slider-pro-plugin.html"><?php echo __( 'UPGRADE TO PRO VERSION','wp-vertical-image-slider');?></a></h3></span>

                    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
                    <h2><?php echo __( 'Images','wp-vertical-image-slider');?> <a class="button add-new-h2" href="admin.php?page=vertical_thumbnail_slider_image_management&action=addedit"><?php echo __( 'Add New','wp-vertical-image-slider');?></a> </h2>
                    <form method="POST" action="admin.php?page=vertical_thumbnail_slider_image_management&action=deleteselected"  id="posts-filter" onkeypress="return event.keyCode != 13;">
                        <div class="alignleft actions">
                            <select name="action_upper" id="action_upper">
                                <option selected="selected" value="-1"><?php echo __( 'Bulk Actions','wp-vertical-image-slider');?></option>
                                <option value="delete"><?php echo __( 'Delete','wp-vertical-image-slider');?></option>
                            </select>
                            <input type="submit" value="<?php echo __( 'Apply','wp-vertical-image-slider');?>" class="button-secondary action" id="deleteselected" name="deleteselected" onclick="return confirmDelete_bulk();">
                        </div>
                        <br class="clear">
                        <?php
                           
                             $setacrionpage='admin.php?page=vertical_thumbnail_slider_image_management';

                             if(isset($_GET['order_by']) and $_GET['order_by']!=""){
                              $setacrionpage.='&order_by='.$_GET['order_by'];   
                             }

                             if(isset($_GET['order_pos']) and $_GET['order_pos']!=""){
                              $setacrionpage.='&order_pos='.$_GET['order_pos'];   
                             }

                             $seval="";
                             if(isset($_GET['search_term']) and $_GET['search_term']!=""){
                              $seval=trim($_GET['search_term']);   
                             }

                         ?>
                        <?php
                            global $wpdb;
                            $settings=get_option('vertical_thumbnail_slider_settings'); 
                            $visibleImages=$settings['visible'];

                            $order_by='id';
                            $order_pos="asc";

                            if(isset($_GET['order_by'])){

                               $order_by=trim($_GET['order_by']); 
                            }

                            if(isset($_GET['order_pos'])){

                               $order_pos=trim($_GET['order_pos']); 
                            }
                             $search_term='';
                            if(isset($_GET['search_term'])){

                               $search_term= sanitize_text_field($_GET['search_term']);
                            }

                            $query = "SELECT * FROM " . $wpdb->prefix . "vertical_thumbnail_slider ";
                            $queryCount = "SELECT count(*) FROM " . $wpdb->prefix . "vertical_thumbnail_slider ";
                            if($search_term!=''){
                               $query.=" where id like '%$search_term%' or title like '%$search_term%' "; 
                               $queryCount.=" where id like '%$search_term%' or title like '%$search_term%' "; 
                            }

                            $order_by=sanitize_text_field($order_by);
                            $order_pos=sanitize_text_field($order_pos);

                            $query.=" order by $order_by $order_pos";
                      
                            $rowsCount=$wpdb->get_var($queryCount);
                            
                            
                            $rowCount = sizeof ( $rows );
                            
                             $allCount = $wpdb->get_var( "SELECT COUNT(*) FROM ".$wpdb->prefix."vertical_thumbnail_slider" );

                            ?>
                            <div style="padding-top:5px;padding-bottom:5px">
                               <b><?php echo __( 'Search','wp-vertical-image-slider');?> : </b>
                                 <input type="text" value="<?php echo $seval;?>" id="search_term" name="search_term">&nbsp;
                                 <input type='button'  value='<?php echo __( 'Search','wp-vertical-image-slider');?>' name='searchusrsubmit' class='button-primary' id='searchusrsubmit' onclick="SearchredirectTO();" >&nbsp;
                                 <input type='button'  value='<?php echo __( 'Reset Search','wp-vertical-image-slider');?>' name='searchreset' class='button-primary' id='searchreset' onclick="ResetSearch();" >
                           </div>  
                           <script type="text/javascript" >
                              var $n = jQuery.noConflict();   
                               $n('#search_term').on("keyup", function(e) {
                                      if (e.which == 13) {

                                          SearchredirectTO();
                                      }
                                 });   
                            function SearchredirectTO(){
                              var redirectto='<?php echo $setacrionpage; ?>';
                              var searchval=jQuery('#search_term').val();
                              redirectto=redirectto+'&search_term='+jQuery.trim(encodeURIComponent(searchval));  
                              window.location.href=redirectto;
                            }
                           function ResetSearch(){

                                var redirectto='<?php echo $setacrionpage; ?>';
                                window.location.href=redirectto;
                                exit;
                           }
                           </script>  
                       
                        <?php if($allCount<$visibleImages){ ?>
                            <h4 style="color: green"><?php echo __( 'Current slider setting - Total visible images','wp-vertical-image-slider');?> <?php echo $visibleImages; ?></h4>
                            <h4 style="color: green"><?php echo __( 'Please add atleast','wp-vertical-image-slider');?> <?php echo $visibleImages; ?> <?php echo __( 'images','wp-vertical-image-slider');?></h4>
                            <?php } else{
                                echo "<br/>";
                        }?>
                        <div id="no-more-tables">
                            <table cellspacing="0" id="gridTbl" class="table-bordered table-striped table-condensed cf" >
                                <thead>       
                                    <tr>
                                        <th class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>
                                        <?php if($order_by=="title" and $order_pos=="asc"):?>
                                            <th><a href="<?php echo $setacrionpage;?>&order_by=title&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Title','wp-vertical-image-slider');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                       <?php else:?>
                                           <?php if($order_by=="title"):?>
                                       <th><a href="<?php echo $setacrionpage;?>&order_by=title&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Title','wp-vertical-image-slider');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                           <?php else:?>
                                               <th><a href="<?php echo $setacrionpage;?>&order_by=title&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Title','wp-vertical-image-slider');?></a></th>
                                           <?php endif;?>    
                                       <?php endif;?>  
                                        <th ><span></span></th>
                                        <?php if($order_by=="createdon" and $order_pos=="asc"):?>
                                            <th><a href="<?php echo $setacrionpage;?>&order_by=createdon&order_pos=desc<?php echo $search_term_;?>"><?php echo __('Published On','wp-vertical-image-slider');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/desc.png', __FILE__); ?>"/></a></th>
                                        <?php else:?>
                                            <?php if($order_by=="createdon"):?>
                                        <th><a href="<?php echo $setacrionpage;?>&order_by=createdon&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Published On','wp-vertical-image-slider');?><img style="vertical-align:middle" src="<?php echo plugins_url('/images/asc.png', __FILE__); ?>"/></a></th>
                                            <?php else:?>
                                                <th><a href="<?php echo $setacrionpage;?>&order_by=createdon&order_pos=asc<?php echo $search_term_;?>"><?php echo __('Published On','wp-vertical-image-slider');?></a></th>
                                            <?php endif;?>    
                                        <?php endif;?>  
                                        <th><?php echo __( 'Edit','wp-vertical-image-slider');?></th>
                                        <th><?php echo __( 'Delete','wp-vertical-image-slider');?></th>
                                    </tr> 
                                </thead>

                                <tbody id="the-list">
                                    <?php

                                        if($rowsCount > 0){

                                            global $wp_rewrite;
                                            $rows_per_page = 10;

                                            $current = (isset($_GET['paged'])) ? ($_GET['paged']) : 1;
                                            $pagination_args = array(
                                                'base' => @add_query_arg('paged','%#%'),
                                                'format' => '',
                                                'total' => ceil($rowsCount/$rows_per_page),
                                                'current' => $current,
                                                'show_all' => false,
                                                'type' => 'plain',
                                            );


                                            
                                            $offset = ($current - 1) * $rows_per_page;
                                            
                                            $query.=" limit $offset, $rows_per_page";
                                            $rows = $wpdb->get_results ( $query ,'ARRAY_A' );
                                            
                                            foreach ($rows as $row ) {

                                                $delRecNonce=wp_create_nonce('delete_image');
                                                $id=$row['id'];
                                                $editlink="admin.php?page=vertical_thumbnail_slider_image_management&action=addedit&id=$id";
                                                $deletelink="admin.php?page=vertical_thumbnail_slider_image_management&action=delete&id=$id&nonce=$delRecNonce";
                                                $outputimgmain = $baseurl.$row['image_name']; 

                                            ?>   
                                            <tr valign="top">
                                                <td class="alignCenter check-column"   data-title="<?php echo __( 'Select Record','wp-vertical-image-slider');?>" ><input type="checkbox" value="<?php echo $row['id'] ?>" name="thumbnails[]"></td>
                                                <td   data-title="<?php echo __( 'Title','wp-vertical-image-slider');?>" ><strong><?php echo strip_tags($row['title']); ?></strong></td> 
                                                 <td  data-title="<?php echo __( 'Image','wp-vertical-image-slider');?>" class="alignCenter">
                                                    <img src="<?php echo $outputimgmain;?>" style="width:50px" height="50px"/>
                                                </td> 
                                                <td class="alignCenter"   data-title="<?php echo __( 'Published On','wp-vertical-image-slider');?>" ><?php echo $row['createdon']; ?></td>
                                                <td class="alignCenter"   data-title="<?php echo __( 'Edit Record','wp-vertical-image-slider');?>" ><strong><a href='<?php echo $editlink; ?>' title="<?php echo __( 'edit','wp-vertical-image-slider');?>"><?php echo __( 'Edit','wp-vertical-image-slider');?></a></strong></td>  
                                                <td class="alignCenter"   data-title="<?php echo __( 'Delete Record','wp-vertical-image-slider');?>" ><strong><a href='<?php echo $deletelink; ?>' onclick="return confirmDelete();"  title="<?php echo __( 'delete','wp-vertical-image-slider');?>"><?php echo __( 'Delete','wp-vertical-image-slider');?></a> </strong></td>  
                                            </tr>
                                            <?php 
                                            } 
                                        }
                                        else{
                                        ?>

                                        <tr valign="top" class="" id="">
                                            <td colspan="6" data-title="<?php echo __( 'No Record','wp-vertical-image-slider');?>" align="center"><strong><?php echo __( 'No Images Found','wp-vertical-image-slider');?></strong></td>  
                                        </tr>


                                        <?php 
                                        } 
                                    ?>      
                                </tbody>
                            </table>
                        </div>
                        <?php
                            if(sizeof($rowsCount)>0){
                                echo "<div class='pagination' style='padding-top:10px'>";
                                echo paginate_links($pagination_args);
                                echo "</div>";
                            }
                        ?>
                        <br/>
                        <div class="alignleft actions" id="action_bottom">
                            <select name="action">
                                <option selected="selected" value="-1"><?php echo __( 'Bulk Actions','wp-vertical-image-slider');?></option>
                                <option value="delete"><?php echo __( 'Delete','wp-vertical-image-slider');?></option>
                            </select>
                            <input type="submit" value="<?php echo __( 'Apply','wp-vertical-image-slider');?>" class="button-secondary action" id="deleteselected" name="deleteselected" onclick="return confirmDelete_bulk();">
                        </div>
                        <?php wp_nonce_field('action_settings_mass_delete','mass_delete_nonce'); ?>
                    </form>  
                    <script type="text/JavaScript">

                         function  confirmDelete_bulk(){
                            var topval=document.getElementById("action_bottom").value;
                            var bottomVal=document.getElementById("action_upper").value;
                       
                            if(topval=='delete' || bottomVal=='delete'){
                                
                            
                                var agree=confirm("<?php echo __( 'Are you sure you want to delete selected images?','wp-vertical-image-slider');?>");
                                if (agree)
                                    return true ;
                                else
                                    return false;
                            }
                        }
                        function  confirmDelete(){
                            var agree=confirm("<?php echo __( 'Are you sure you want to delete this image?','wp-vertical-image-slider');?>");
                            if (agree)
                                return true ;
                            else
                                return false;
                        }
                    </script>

                    <br class="clear">
                    <h3><?php echo __( 'To print this slider into WordPress Post/Page use below Short code','wp-vertical-image-slider');?></h3>
                    <input type="text" value="[print_vertical_thumbnail_slider]" style="width: 400px;height: 30px" onclick="this.focus();this.select()" />
                    <div class="clear"></div>
                    <h3><?php echo __( 'To print this slider into WordPress theme/template PHP files use below php code','wp-vertical-image-slider');?></h3>
                    <input type="text" value="echo do_shortcode('[print_vertical_thumbnail_slider]');" style="width: 400px;height: 30px" onclick="this.focus();this.select()" />

                    <div class="clear"></div> 
                </div>    
                <div id="postbox-container-1" class="postbox-container"> 
                    <div class="postbox"> 
                        <h3 class="hndle"><span></span><?php echo __( 'Recommended WordPress Themes','wp-vertical-image-slider');?></h3> 
                        <div class="inside">
                            <center><a href="http://www.elegantthemes.com/affiliates/idevaffiliate.php?id=11715_0_1_10" target="_blank"><img border="0" src="<?php echo plugins_url( 'images/300x250.gif', __FILE__ );?>" width="250" height="250"></a></center>

                            <div style="margin:10px 5px">

                            </div>
                        </div></div>
                        <div class="postbox"> 
                        <h3 class="hndle"><span></span><?php echo __('Google For Business Coupon','responsive-filterable-portfolio');?></h3> 
                            <div class="inside">
                                <center><a href="https://goo.gl/OJBuHT" target="_blank">
                                        <img src="<?php echo plugins_url( 'images/g-suite-promo-code-4.png', __FILE__ );?>" width="250" height="250" border="0">
                                    </a></center>
                                <div style="margin:10px 5px">
                                </div>
                            </div>

                        </div>
                </div>    

                <div style="clear: both;"></div>
                <?php $url = plugin_dir_url(__FILE__);  ?>


            </div>
        </div>  
        <?php 
        }   
        else if(strtolower($action)==strtolower('addedit')){
            $url = plugin_dir_url(__FILE__);

        ?>
        <?php        
            if(isset($_POST['btnsave'])){

                  if ( !check_admin_referer( 'action_image_add_edit','add_edit_image_nonce')){
                      
                      wp_die('Security check fail'); 
                  }
                
                        
                //edit save
                if(isset($_POST['imageid'])){

                    //add new
                    $location='admin.php?page=vertical_thumbnail_slider_image_management';
                    $title=trim(htmlentities(strip_tags($_POST['imagetitle']),ENT_QUOTES));
                    $imageurl=trim(htmlentities(strip_tags($_POST['imageurl']),ENT_QUOTES));
                    $imageid=trim(htmlentities(strip_tags($_POST['imageid']),ENT_QUOTES));
                    $imagename="";
                     $imagename="";
                    if(trim($_POST['HdnMediaSelection'])!=''){

                        $postThumbnailID=(int)htmlentities(strip_tags($_POST['HdnMediaSelection']),ENT_QUOTES);
                        $photoMeta = wp_get_attachment_metadata( $postThumbnailID );
                        if(is_array($photoMeta) and isset($photoMeta['file'])) {

                            $fileName=$photoMeta['file'];
                            $phyPath=ABSPATH;
                            $phyPath=str_replace("\\","/",$phyPath);

                            $pathArray=pathinfo($fileName);

                            $imagename=$pathArray['basename'];

                            $upload_dir_n = wp_upload_dir(); 
                            $upload_dir_n=$upload_dir_n['basedir'];
                            $fileUrl=$upload_dir_n.'/'.$fileName;
                            $fileUrl=str_replace("\\","/",$fileUrl);

                            $wpcurrentdir=dirname(__FILE__);
                            $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
                            $imageUploadTo=$pathToImagesFolder.'/'.$imagename;

                            @copy($fileUrl, $imageUploadTo);

                        }

                    }     



                    try{
                        if($imagename!=""){
                            $query = "update ".$wpdb->prefix."vertical_thumbnail_slider set title='$title',image_name='$imagename',
                            custom_link='$imageurl' where id=$imageid";
                        }
                        else{
                            $query = "update ".$wpdb->prefix."vertical_thumbnail_slider set title='$title',
                            custom_link='$imageurl' where id=$imageid";
                        } 
                        $wpdb->query($query); 

                        $vertical_thumbnail_slider_messages=array();
                        $vertical_thumbnail_slider_messages['type']='succ';
                        $vertical_thumbnail_slider_messages['message']=__('Image updated successfully.','wp-vertical-image-slider');
                        update_option('vertical_thumbnail_slider_messages', $vertical_thumbnail_slider_messages);


                    }
                    catch(Exception $e){

                        $vertical_thumbnail_slider_messages=array();
                        $vertical_thumbnail_slider_messages['type']='err';
                        $vertical_thumbnail_slider_messages['message']=__( 'Error while updating image.','wp-vertical-image-slider');
                        update_option('vertical_thumbnail_slider_messages', $vertical_thumbnail_slider_messages);
                    }  


                    echo "<script type='text/javascript'> location.href='$location';</script>";
                    exit;
                }
                else{

                    //add new

                    $location='admin.php?page=vertical_thumbnail_slider_image_management';
                    $title=trim(htmlentities(strip_tags($_POST['imagetitle']),ENT_QUOTES));
                    
                    $imageurl=trim(htmlentities(strip_tags($_POST['imageurl']),ENT_QUOTES));
                    $createdOn=date('Y-m-d h:i:s'); 
                    if(function_exists('date_i18n')){

                        $createdOn=date_i18n('Y-m-d'.' '.get_option('time_format') ,false,false);
                        if(get_option('time_format')=='H:i')
                            $createdOn=date('Y-m-d H:i:s',strtotime($createdOn));
                        else   
                            $createdOn=date('Y-m-d h:i:s',strtotime($createdOn));

                    }

                   try{
                       
                         $location='admin.php?page=vertical_thumbnail_slider_image_management';

                         if(trim($_POST['HdnMediaSelection'])!=''){

                                $postThumbnailID=(int) htmlentities($_POST['HdnMediaSelection'],ENT_QUOTES);
                                $photoMeta = wp_get_attachment_metadata( $postThumbnailID );

                                if(is_array($photoMeta) and isset($photoMeta['file'])) {

                                    $fileName=$photoMeta['file'];
                                    $phyPath=ABSPATH;
                                    $phyPath=str_replace("\\","/",$phyPath);

                                    $pathArray=pathinfo($fileName);

                                    $imagename=$pathArray['basename'];

                                    $upload_dir_n = wp_upload_dir(); 
                                    $upload_dir_n=$upload_dir_n['basedir'];
                                    $fileUrl=$upload_dir_n.'/'.$fileName;
                                    $fileUrl=str_replace("\\","/",$fileUrl);

                                    $wpcurrentdir=dirname(__FILE__);
                                    $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
                                    $imageUploadTo=$pathToImagesFolder.'/'.$imagename;

                                    @copy($fileUrl, $imageUploadTo);

                                }

                            }

                            $query = "INSERT INTO ".$wpdb->prefix."vertical_thumbnail_slider (title, image_name,createdon,custom_link) 
                            VALUES ('$title','$imagename','$createdOn','$imageurl')";

                            $wpdb->query($query); 

                            $vertical_thumbnail_slider_messages=array();
                            $vertical_thumbnail_slider_messages['type']='succ';
                            $vertical_thumbnail_slider_messages['message']=__( 'New image added successfully.','wp-vertical-image-slider');
                            update_option('vertical_thumbnail_slider_messages', $vertical_thumbnail_slider_messages);


                        }
                        catch(Exception $e){

                            $vertical_thumbnail_slider_messages=array();
                            $vertical_thumbnail_slider_messages['type']='err';
                            $vertical_thumbnail_slider_messages['message']=__( 'Error while adding image.','wp-vertical-image-slider');
                            update_option('vertical_thumbnail_slider_messages', $vertical_thumbnail_slider_messages);
                        }  

                    }     
                    echo "<script type='text/javascript'> location.href='$location';</script>";
                    exit;          

                } 

            
            else{ 

            ?>
            <div id="poststuff" >  
            <div id="post-body" class="metabox-holder columns-2"> 
                <div id="post-body-content">
                    <span><h3 style="color: blue;"><a target="_blank" href="http://www.i13websolution.com/wordpress-pro-plugins/wordpress-vertical-image-slider-pro-plugin.html">UPGRADE TO PRO VERSION</a></h3></span>
                    <div class="wrap">
                        <?php if(isset($_GET['id']) and $_GET['id']>0)
                            { 


                                $id= $_GET['id'];
                                $query="SELECT * FROM ".$wpdb->prefix."vertical_thumbnail_slider WHERE id=$id";
                                $myrow  = $wpdb->get_row($query);

                                if(is_object($myrow)){

                                    $title=  ($myrow->title);
                                    $image_link=($myrow->custom_link);
                                    $image_name=($myrow->image_name);

                                }   

                            ?>

                            <h2><?php echo __( 'Update Image','wp-vertical-image-slider');?> </h2>

                            <?php }else{ 

                                $title='';
                                $image_link='';
                                $image_name='';

                            ?>
                            <h2><?php echo __( 'Add Image','wp-vertical-image-slider');?> </h2>
                            <?php } ?>

                        <div id="poststuff">
                            <div id="post-body" class="metabox-holder columns-2">
                                <div id="post-body-content">
                                    <form method="post" action="" id="addimage" name="addimage" enctype="multipart/form-data" >

                                       <div class="stuffbox" id="namediv" style="">
                                        <h3><label for="link_name"><?php echo __( 'Upload Image','wp-vertical-image-slider');?></label></h3>
                                        <div class="inside" id="fileuploaddiv">
                                            <?php if($image_name!=""){ ?>
                                                <div><b><?php echo __( 'Current Image','wp-vertical-image-slider');?> : </b><a id="currImg" href="<?php echo $baseurl.$image_name; ?>" target="_new"><?php echo $image_name; ?></a></div>
                                                <?php } ?>      
                                            <div class="uploader">
                                                <br/>
                                               
                                                    <a  href="javascript:;" class="niks_media" id="myMediaUploader"><b><?php echo __( 'Click Here to Upload','wp-vertical-image-slider');?></b></a>
                                                    <input id="HdnMediaSelection" name="HdnMediaSelection" type="hidden" value="" />
                                                <br/>
                                            </div>  
                                                <script>

                                                    var $n = jQuery.noConflict();  
                                                    $n(document).ready(function() {
                                                            //uploading files variable
                                                            var custom_file_frame;
                                                            $n("#myMediaUploader").click(function(event) {
                                                                    event.preventDefault();
                                                                    //If the frame already exists, reopen it
                                                                    if (typeof(custom_file_frame)!=="undefined") {
                                                                        custom_file_frame.close();
                                                                    }

                                                                    //Create WP media frame.
                                                                    custom_file_frame = wp.media.frames.customHeader = wp.media({
                                                                            //Title of media manager frame
                                                                            title: "WP Media Uploader",
                                                                            library: {
                                                                                type: 'image'
                                                                            },
                                                                            button: {
                                                                                //Button text
                                                                                text: "<?php echo __( 'Set Image','wp-vertical-image-slider');?>"
                                                                            },
                                                                            //Do not allow multiple files, if you want multiple, set true
                                                                            multiple: false
                                                                    });

                                                                    //callback for selected image
                                                                    custom_file_frame.on('select', function() {

                                                                            var attachment = custom_file_frame.state().get('selection').first().toJSON();


                                                                            var validExtensions=new Array();
                                                                            validExtensions[0]='jpg';
                                                                            validExtensions[1]='jpeg';
                                                                            validExtensions[2]='png';
                                                                            validExtensions[3]='gif';
                                                                  
                                                                            var inarr=parseInt($n.inArray( attachment.subtype, validExtensions));

                                                                            if(inarr>0 && attachment.type.toLowerCase()=='image' ){

                                                                                var titleTouse="";
                                                                                var imageDescriptionTouse="";

                                                                                if($n.trim(attachment.title)!=''){

                                                                                    titleTouse=$n.trim(attachment.title); 
                                                                                }  
                                                                                else if($n.trim(attachment.caption)!=''){

                                                                                    titleTouse=$n.trim(attachment.caption);  
                                                                                }

                                                                                if($n.trim(attachment.description)!=''){

                                                                                    imageDescriptionTouse=$n.trim(attachment.description); 
                                                                                }  
                                                                                else if($n.trim(attachment.caption)!=''){

                                                                                    imageDescriptionTouse=$n.trim(attachment.caption);  
                                                                                }

                                                                                $n("#imagetitle").val(titleTouse);  
                                                                                $n("#image_description").val(imageDescriptionTouse);  

                                                                                if(attachment.id!=''){
                                                                                    $n("#HdnMediaSelection").val(attachment.id);  
                                                                                    $n("#err_daynamic").remove();
                                                                                }   

                                                                            }  
                                                                            else{

                                                                                alert('<?php echo __( 'Invalid image selection.','wp-vertical-image-slider');?>');
                                                                            }  
                                                                            
                                                                    });

                                                                    //Open modal
                                                                    custom_file_frame.open();
                                                            });
                                                    })
                                                </script>
                                                
                                        </div>
                                      </div>
                                        
                                        <div class="stuffbox" id="namediv" style="">
                                            <h3><label for="link_name"><?php echo __( 'Image Title','wp-vertical-image-slider');?></label></h3>
                                            <div class="inside">
                                                <input type="text" id="imagetitle"  tabindex="1" size="30" name="imagetitle" value="<?php echo $title;?>">
                                                <div style="clear:both"></div>
                                                <div></div>
                                                <div style="clear:both"></div>
                                                <p><?php echo __( 'Used in image alt for seo','wp-vertical-image-slider');?></p>
                                            </div>
                                        </div>
                                        <div class="stuffbox" id="namediv" style="">
                                            <h3><label for="link_name"><?php echo __( 'Image Url','wp-vertical-image-slider');?>(<?php echo __( 'On click redirect to this url.','wp-vertical-image-slider');?>)</label></h3>
                                            <div class="inside">
                                                <input type="text" id="imageurl" class=""  tabindex="1" size="30" name="imageurl" value="<?php echo $image_link; ?>">
                                                <div style="clear:both"></div>
                                                <div></div>
                                                <div style="clear:both"></div>
                                                <p><?php echo __( 'On image click users will redirect to this url.','wp-vertical-image-slider');?></p>
                                            </div>
                                        </div>
                                        
                                        <?php if(isset($_GET['id']) and $_GET['id']>0){ ?> 
                                            <input type="hidden" name="imageid" id="imageid" value="<?php echo $_GET['id'];?>">
                                            <?php
                                            } 
                                        ?>
                                        <input type="submit" tabindex="4" onclick="return validateFile();" name="btnsave" id="btnsave" value="<?php echo __( 'Save Changes','wp-vertical-image-slider');?>" class="button-primary">&nbsp;&nbsp;<input type="button" name="cancle" id="cancle" value="<?php echo __( 'Cancel','wp-vertical-image-slider');?>" class="button-primary" tabindex="5" onclick="location.href='admin.php?page=vertical_thumbnail_slider_image_management'">
                                        <?php wp_nonce_field('action_image_add_edit','add_edit_image_nonce'); ?>
                                    </form> 
                                    <script type="text/javascript">

                                        var $n = jQuery.noConflict();  
                                        $n(document).ready(function() {

                                                $n("#addimage").validate({
                                                        rules: {
                                                            imagetitle: {
                                                                required:true, 
                                                                maxlength: 200
                                                            },imageurl: {
                                                                url2:true,  
                                                                maxlength: 500
                                                            }
                                                        },
                                                        errorClass: "image_error",
                                                        errorPlacement: function(error, element) {
                                                            error.appendTo( element.next().next().next());
                                                        } 


                                                })
                                                
                                              
                                                  
                                        });

                                        function validateFile(){

                                        var $n = jQuery.noConflict();  
                                        if($n('#currImg').length>0 || $n.trim($n("#HdnMediaSelection").val())!="" ){
                                            return true;
                                        }
                                        else
                                            {
                                            $n("#err_daynamic").remove();
                                            $n("#myMediaUploader").after('<br/><label class="image_error" id="err_daynamic"><?php echo __( 'Please select file.','wp-vertical-image-slider');?></label>');
                                            return false;  
                                        } 
                                            
                                    }
                                      
                                    </script> 

                                </div>
                            </div>
                        </div>  
                    </div>      
                </div>
                                                      
            </div>
            <?php 
            } 
        }  

        else if(strtolower($action)==strtolower('delete')){

            $retrieved_nonce = '';
            
            if(isset($_GET['nonce']) and $_GET['nonce']!=''){
              
                $retrieved_nonce=$_GET['nonce'];
                
            }
            if (!wp_verify_nonce($retrieved_nonce, 'delete_image' ) ){
        
                
                wp_die('Security check fail'); 
            }
                    
            $location='admin.php?page=vertical_thumbnail_slider_image_management';
            $deleteId=(int)$_GET['id'];

            try{


                $query="SELECT * FROM ".$wpdb->prefix."vertical_thumbnail_slider WHERE id=$deleteId";
                $myrow  = $wpdb->get_row($query);

                if(is_object($myrow)){

                    $image_name=  $myrow->image_name;
                    $wpcurrentdir=dirname(__FILE__);
                    $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
                    //$imagename=$_FILES["image_name"]["name"];
                    $imagetoDel=$pathToImagesFolder.'/'.$image_name;
                    @unlink($imagetoDel);

                    $query = "delete from  ".$wpdb->prefix."vertical_thumbnail_slider where id=$deleteId";
                    $wpdb->query($query); 

                    $vertical_thumbnail_slider_messages=array();
                    $vertical_thumbnail_slider_messages['type']='succ';
                    $vertical_thumbnail_slider_messages['message']=__( 'Image deleted successfully.','wp-vertical-image-slider');
                    update_option('vertical_thumbnail_slider_messages', $vertical_thumbnail_slider_messages);
                }    


            }
            catch(Exception $e){

                $vertical_thumbnail_slider_messages=array();
                $vertical_thumbnail_slider_messages['type']='err';
                $vertical_thumbnail_slider_messages['message']=__( 'Error while deleting image.','wp-vertical-image-slider');
                update_option('vertical_thumbnail_slider_messages', $vertical_thumbnail_slider_messages);
            }  

            echo "<script type='text/javascript'> location.href='$location';</script>";
            exit;

        }  
        else if(strtolower($action)==strtolower('deleteselected')){

            if(!check_admin_referer('action_settings_mass_delete','mass_delete_nonce')){
               
                wp_die('Security check fail'); 
            }
                    
            $location='admin.php?page=vertical_thumbnail_slider_image_management'; 
            if(isset($_POST) and isset($_POST['deleteselected']) and  ( $_POST['action']=='delete' or $_POST['action_upper']=='delete')){

                if(sizeof($_POST['thumbnails']) >0){

                    $deleteto=$_POST['thumbnails'];
                    $implode=implode(',',$deleteto);   

                    try{

                        foreach($deleteto as $img){ 

                            $query="SELECT * FROM ".$wpdb->prefix."vertical_thumbnail_slider WHERE id=$img";
                            $myrow  = $wpdb->get_row($query);

                            if(is_object($myrow)){

                                $image_name=$myrow->image_name;
                                $wpcurrentdir=dirname(__FILE__);
                                $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
                                //$imagename=$_FILES["image_name"]["name"];
                                $imagetoDel=$pathToImagesFolder.'/'.$image_name;
                                @unlink($imagetoDel);
                                $query = "delete from  ".$wpdb->prefix."vertical_thumbnail_slider where id=$img";
                                $wpdb->query($query); 

                                $vertical_thumbnail_slider_messages=array();
                                $vertical_thumbnail_slider_messages['type']='succ';
                                $vertical_thumbnail_slider_messages['message']=__( 'Selected images deleted successfully.','wp-vertical-image-slider');
                                update_option('vertical_thumbnail_slider_messages', $vertical_thumbnail_slider_messages);
                            }

                        }

                    }
                    catch(Exception $e){

                        $vertical_thumbnail_slider_messages=array();
                        $vertical_thumbnail_slider_messages['type']='err';
                        $vertical_thumbnail_slider_messages['message']=__('Error while deleting image.','wp-vertical-image-slider');
                        update_option('vertical_thumbnail_slider_messages', $vertical_thumbnail_slider_messages);
                    }  

                    echo "<script type='text/javascript'> location.href='$location';</script>";
                    exit;


                }
                else{

                    echo "<script type='text/javascript'> location.href='$location';</script>"; 
                    exit;  
                }

            }
            else{

                echo "<script type='text/javascript'> location.href='$location';</script>";   
                exit;   
            }

        }      
    } 
    function verticalpreviewSliderAdmin(){
        $settings=get_option('vertical_thumbnail_slider_settings');
        
        $uploads = wp_upload_dir();
        $baseDir=$uploads['basedir'];
        $baseDir=str_replace("\\","/",$baseDir);
        $pathToImagesFolder=$baseDir.'/wp-vertical-image-slider';
        
        $baseurl=$uploads['baseurl'];
        $baseurl.='/wp-vertical-image-slider/';
        

    ?>      
    <div style="width: 100%;">  
        <div style="float:left;width:69%;">
            <div class="wrap">
                <h2><?php echo __( 'Slider Preview','wp-vertical-image-slider');?></h2>
                <br>
                <?php
                    $wpcurrentdir=dirname(__FILE__);
                    $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);

                ?>
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        <div id="post-body-content">
                            <div style="clear: both;"></div>
                            
                             <?php if(!$settings['is_responsive']):?> 
                                    <?php $url = plugin_dir_url(__FILE__);  ?>
                                   <div class="verticalmainTable"  style="background:<?php echo $settings['scollerBackground'];?>;display:inline-block">
                                       <?php $settings['auto']=(int)$settings['auto'];?>
                                        <?php if($settings['auto']==0 or $settings['auto']==2){?>
                                            <div class="uparrow">
                                                <img class="prev_vertical previmg" src="<?php echo plugin_dir_url(__FILE__);?>images/uparrow.png" />
                                            </div>
                                            <?php } ?>   
                                        <div class="verticalmainSliderDiv">
                                            <ul class="sliderUl">
                                                <?php
                                                    global $wpdb;
                                                    $imageheight=$settings['imageheight'];
                                                    $imagewidth=$settings['imagewidth'];
                                                    $query="SELECT * FROM ".$wpdb->prefix."vertical_thumbnail_slider order by createdon desc";
                                                    $rows=$wpdb->get_results($query,'ARRAY_A');

                                                    if(count($rows) > 0){
                                                        foreach($rows as $row){

                                                            $imagename=$row['image_name'];
                                                            $imageUploadTo=$pathToImagesFolder.'/'.$imagename;
                                                            $imageUploadTo=str_replace("\\","/",$imageUploadTo);
                                                            $pathinfo=pathinfo($imageUploadTo);
                                                            $filenamewithoutextension=$pathinfo['filename'];
                                                            $outputimg="";


                                                            if($settings['resizeImages']==0){

                                                                    $outputimg = $baseurl.$row['image_name']; 

                                                                }
                                                                else{
                                                                    
                                                                    $imagetoCheck=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                                    $imagetoCheckSmall=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                                            
                                                                    if(file_exists($imagetoCheck)){
                                                                        
                                                                        $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                                    }
                                                                    else if(file_exists($imagetoCheckSmall)){
                                                                        
                                                                        $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                                                                    }
                                                                    else{

                                                                        if(function_exists('wp_get_image_editor')){

                                                                            $image = wp_get_image_editor($pathToImagesFolder."/".$row['image_name']); 

                                                                            if ( ! is_wp_error( $image ) ) {
                                                                                $image->resize( $imagewidth, $imageheight, true );
                                                                                $image->save( $imagetoCheck );
                                                                                //$outputimg = plugin_dir_url(__FILE__)."imagestoscroll/".$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                                                
                                                                                 if(file_exists($imagetoCheck)){
                                                                                    $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                                                  }
                                                                                  else if(file_exists($imagetoCheckSmall)){
                                                                                      $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                                                                                  }
                                                                    
                                                                            }
                                                                            else{
                                                                               $outputimg = $baseurl.$row['image_name'];
                                                                            }     

                                                                        }
                                                                        else if(function_exists('image_resize')){

                                                                            $return=image_resize($pathToImagesFolder."/".$row['image_name'],$imagewidth,$imageheight) ;
                                                                            if ( ! is_wp_error( $return ) ) {

                                                                                $isrenamed=rename($return,$imagetoCheck);
                                                                                if($isrenamed){
                                                                                   // $outputimg = plugin_dir_url(__FILE__)."imagestoscroll/".$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];  
                                                                                    
                                                                                     if(file_exists($imagetoCheck)){
                                                                                         
                                                                                        $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                                                        
                                                                                       }
                                                                                        else if(file_exists($imagetoCheckSmall)){
                                                                                            
                                                                                            $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                                                                                            
                                                                                        }
                                                                            
                                                                            
                                                                                }
                                                                                else{
                                                                                    
                                                                                    $outputimg = $baseurl.$row['image_name']; 
                                                                                } 
                                                                            }
                                                                            else{
                                                                                
                                                                                $outputimg = $baseurl.$row['image_name']; 
                                                                            }  
                                                                        }
                                                                        else{

                                                                           $outputimg = $baseurl.$row['image_name']; 
                                                                        }  

                                                                        //$url = plugin_dir_url(__FILE__)."imagestoscroll/".$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];

                                                                    } 
                                                                }

                                                        ?>

                                                        <li class="sliderimgLiVertical" >
                                                            <?php if($settings['linkimage']==true){ ?> 
                                                                <a target="_blank" href="<?php if($row['custom_link']==""){echo '#';}else{echo $row['custom_link'];} ?>"><img src="<?php echo $outputimg; ?>" alt="<?php echo $row['title']; ?>" title="<?php echo $row['title']; ?>" style="width:<?php echo $settings['imagewidth']; ?>px;height:<?php echo $settings['imageheight']; ?>px"  /></a>
                                                                <?php }else{ ?>
                                                                <img src="<?php echo $outputimg;?>" alt="<?php echo $row['title']; ?>" title="<?php echo $row['title']; ?>" style="width:<?php echo $settings['imagewidth']; ?>px;height:<?php echo $settings['imageheight']; ?>px"  />
                                                                <?php } ?> 
                                                        </li>
                                                        <?php
                                                        }
                                                    }  
                                                ?>
                                            </ul>
                                        </div>
                                        <?php if($settings['auto']==0 or $settings['auto']==2){?>
                                            <div class="downarrow">
                                                <img class="next_vertical nextimg" src="<?php echo plugin_dir_url(__FILE__);?>images/downarrow.png" />
                                            </div>
                                            <?php }?>  
                             <script type="text/javascript">
                                var $n = jQuery.noConflict();  
                                $n(document).ready(function() {


                                        $n(".verticalmainSliderDiv").jCarouselLite({
                                                btnNext: ".next_vertical",
                                                btnPrev: ".prev_vertical",
                                                <?php if($settings['auto']){?>
                                                    auto: <?php echo $settings['speed']; ?>,
                                                    <?php } ?>
                                                speed: <?php echo $settings['speed']; ?>,
                                                <?php if($settings['pauseonmouseover'] and $settings['auto']){ ?>
                                                    hoverPause: true,
                                                    <?php }else{ if($settings['auto']){?>   
                                                        hoverPause: false,
                                                        <?php }} ?>
                                                circular: <?php echo ($settings['circular'])? 'true':'false' ?>,
                                                <?php if($settings['visible']!=""){ ?>
                                                    visible: <?php echo $settings['visible'].','; ?>
                                                    <?php } ?>
                                                scroll: <?php echo $settings['scroll']; ?>,
                                                vertical:'true'

                                        });

                                        $n("#verticalmainscollertd").css("visibility","visible")


                                });
                            </script>              
                        </div>
                         <?php else:?>
                                
                                <style type='text/css' >


                                        #vertical_thum_resp_slider_main .bx-wrapper div.bx-viewport{
                                        background: <?php echo $settings['scollerBackground']; ?> !important;
                                        padding-top:<?php echo $settings['imageMargin'];?>px;
                                        padding-bottom:<?php echo $settings['imageMargin'];?>px;



                                    }

                                    #vertical_thum_resp_slider_main .bx-wrapper .sliderimgLiVerticalres{padding-left:<?php echo $settings['imageMargin'];?>px;padding-right:<?php echo $settings['imageMargin'];?>px}
                                </style>

                                 <div  class="bxv vertical_thum_resp_slider" id="vertical_thum_resp_slider_main" >
                                     <div class="sub-vertical-slider" id="vertical_thum_resp_slider">

                                          <?php

                                            global $wpdb;
                                            $imageheight=$settings['imageheight'];
                                            $imagewidth=$settings['imagewidth'];
                                            $query="SELECT * FROM ".$wpdb->prefix."vertical_thumbnail_slider order by createdon desc";
                                            $rows=$wpdb->get_results($query,'ARRAY_A');

                                            if(count($rows) > 0){
                                                foreach($rows as $row){

                                                    $wpcurrentdir=dirname(__FILE__);
                                                    $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
                                                    $imagename=$row['image_name'];
                                                    $imageUploadTo=$pathToImagesFolder.'/'.$imagename;
                                                    $imageUploadTo=str_replace("\\","/",$imageUploadTo);
                                                    $pathinfo=pathinfo($imageUploadTo);
                                                    $filenamewithoutextension=$pathinfo['filename'];
                                                    $outputimg="";


                                                    if($settings['resizeImages']==0){

                                                        $outputimg = $baseurl.$row['image_name']; 

                                                    }
                                                    else{

                                                        $imagetoCheck=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                        $imagetoCheckSmall=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);

                                                        if(file_exists($imagetoCheck)){

                                                            $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                        }
                                                        else if(file_exists($imagetoCheckSmall)){

                                                            $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                                                        }
                                                        else{

                                                            if(function_exists('wp_get_image_editor')){

                                                                $image = wp_get_image_editor($pathToImagesFolder."/".$row['image_name']); 

                                                                if ( ! is_wp_error( $image ) ) {
                                                                    $image->resize( $imagewidth, $imageheight, true );
                                                                    $image->save( $imagetoCheck );
                                                                    //$outputimg = plugin_dir_url(__FILE__)."imagestoscroll/".$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];

                                                                     if(file_exists($imagetoCheck)){
                                                                        $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                                      }
                                                                      else if(file_exists($imagetoCheckSmall)){
                                                                          $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                                                                      }

                                                                }
                                                                else{
                                                                   $outputimg = $baseurl.$row['image_name'];
                                                                }     

                                                            }
                                                            else if(function_exists('image_resize')){

                                                                $return=image_resize($pathToImagesFolder."/".$row['image_name'],$imagewidth,$imageheight) ;
                                                                if ( ! is_wp_error( $return ) ) {

                                                                    $isrenamed=rename($return,$imagetoCheck);
                                                                    if($isrenamed){
                                                                       // $outputimg = plugin_dir_url(__FILE__)."imagestoscroll/".$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];  

                                                                         if(file_exists($imagetoCheck)){

                                                                            $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];

                                                                           }
                                                                            else if(file_exists($imagetoCheckSmall)){

                                                                                $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);

                                                                            }


                                                                    }
                                                                    else{

                                                                        $outputimg = $baseurl.$row['image_name']; 
                                                                    } 
                                                                }
                                                                else{

                                                                    $outputimg = $baseurl.$row['image_name']; 
                                                                }  
                                                            }
                                                            else{

                                                               $outputimg = $baseurl.$row['image_name']; 
                                                            }  


                                                        } 


                                                    }

                                                    $title="";
                                                     $rowTitle=$row['title'];
                                                     $rowTitle=str_replace("'","’",$rowTitle); 
                                                     $rowTitle=str_replace('"','”',$rowTitle); 


                                                ?>  
                                <div class="sliderimgLiVerticalres"> 
                                       <?php if($settings['linkimage']==true){ ?>                                                                                                                                                                                                                                                                                     
                                         <a <?php if($row['custom_link']!=""):?> href="<?php echo $row['custom_link'];?>" <?php endif;?> title="<?php echo $rowTitle; ?>" ><img src="<?php echo $outputimg; ?>" alt="<?php echo $rowTitle; ?>" title="<?php echo $rowTitle; ?>"   /></a>
                                       <?php }else{ ?>
                                             <img src="<?php echo $outputimg;?>" alt="<?php echo $rowTitle; ?>" title="<?php echo $rowTitle; ?>"   />
                                       <?php } ?> 
                                    </div>
                                  <?php }?> 

                               </div>
                             </div>
                       <script>
                          var $n = jQuery.noConflict();  
                          var slider='';
                          var maxHeightSlider=0;
                         $n(document).ready(function(){
                            sliderVertcal=$n('#vertical_thum_resp_slider').bxSlider_vertical({
                             mode: 'vertical',
                             slideWidth: <?php echo $imagewidth;?>,
                             moveSlides: <?php echo $settings['scroll'];?>,
                             minSlides: <?php echo $settings['visible'];?>,
                             maxSlides:<?php echo $settings['visible'];?>,
                             speed:<?php echo $settings['speed']; ?>,
                             pause:<?php echo $settings['pause']; ?>,
                             slideMargin:<?php echo $settings['imageMargin'];?>,
                             <?php if($settings['auto']==1 or $settings['auto']==2):?>
                               autoStart:true,
                               autoDelay:200,
                               auto:true,       
                              <?php endif;?>
                              <?php if($settings['circular']):?> 
                               infiniteLoop: true,
                              <?php else: ?>
                                infiniteLoop: false,
                              <?php endif;?>    
                             <?php if($settings['pauseonmouseover'] and ($settings['auto']==1 or $settings['auto']==2) ){ ?>
                               autoHover: true,
                             <?php }else{ if($settings['auto']==1 or $settings['auto']==2){?>   
                               autoHover:false,
                             <?php }} ?>
                             <?php if($settings['auto']==1):?>
                              controls:false,
                             <?php else: ?>
                               controls:true,
                             <?php endif;?>
                             pager:false,
                             useCSS:false,
                             onSliderLoad: function(currentIndex){

                                 maxHeightSlider=$n('#vertical_thum_resp_slider_main .bx-wrapper div.bx-viewport').css('height');
                                 $n("#vertical_thum_resp_slider").attr('data-height',maxHeightSlider);
                                 //$n("#vertical_thum_resp_slider_main").find(".bx-wrapper").find(".bx-prev").css({marginLeft:$n("#vertical_thum_resp_slider_main .bx-wrapper .bx-viewport").width()/2+'px'});
                                 //$n("#vertical_thum_resp_slider_main").find(".bx-wrapper").find(".bx-next").css({marginLeft:$n("#vertical_thum_resp_slider_main .bx-wrapper .bx-viewport").width()/2+'px'});

                             }




                           });

                           var timer;
                           var width = $n(window).width();
                           $n(window).bind('resize', function(){
                               if($n(window).width() != width){

                                   width = $n(window).width();
                                   timer && clearTimeout(timer);
                                   timer = setTimeout(onResizeVerticalSlider, 600);

                               }   
                           });

                           function onResizeVerticalSlider(){

                               $n("#vertical_thum_resp_slider").removeAttr('data-height');

                               var current = sliderVertcal.getCurrentSlide();
                               sliderVertcal.reloadSlider();

                                maxHeightSlider=$n('#vertical_thum_resp_slider_main .bx-wrapper div.bx-viewport').css('height');
                                 $n("#vertical_thum_resp_slider").attr('data-height',maxHeightSlider);

                               // $n("#vertical_thum_resp_slider_main").find(".bx-wrapper").find(".bx-prev").css({marginLeft:$n("#vertical_thum_resp_slider_main .bx-wrapper .bx-viewport").width()/2+'px'});
                               // $n("#vertical_thum_resp_slider_main").find(".bx-wrapper").find(".bx-next").css({marginLeft:$n("#vertical_thum_resp_slider_main .bx-wrapper .bx-viewport").width()/2+'px'});
                               
                                <?php if($settings['auto']==0):?>
                                   sliderVertcal.goToSlide(current);
                                <?php endif;?> 
                              
                               


                           }




                         });


                     </script> 


                    <?php }?>  

                   <?php endif;?>    
                    </div>      
                </div>  
            </div>      
        </div>
        <div class="clear"></div>
    </div>
    </div>
    <div class="clear"></div>
    <h3><?php echo __( 'To print this slider into WordPress Post/Page use below Short code','wp-vertical-image-slider');?></h3>
    <input type="text" value="[print_vertical_thumbnail_slider]" style="width: 400px;height: 30px" onclick="this.focus();this.select()" />
    <div class="clear"></div>
    <h3><?php echo __( 'To print this slider into WordPress theme/template PHP files use below php code','wp-vertical-image-slider');?></h3>
    <input type="text" value="echo do_shortcode('[print_vertical_thumbnail_slider]');" style="width: 400px;height: 30px" onclick="this.focus();this.select()" />
    <div class="clear"></div>
    <?php       
    }

    function print_vertical_thumbnail_slider_func(){

        $wpcurrentdir=dirname(__FILE__);
        $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
        $settings=get_option('vertical_thumbnail_slider_settings');
        
        $uploads = wp_upload_dir();
        $baseDir=$uploads['basedir'];
        $baseDir=str_replace("\\","/",$baseDir);
        $pathToImagesFolder=$baseDir.'/wp-vertical-image-slider';
        
        $baseurl=$uploads['baseurl'];
        $baseurl.='/wp-vertical-image-slider/';
        
        ob_start();
    ?><!-- print_vertical_thumbnail_slider_func --><div style="clear: both;"></div>
    <?php if(!$settings['is_responsive']):?> 
        <?php $url = plugin_dir_url(__FILE__);  ?>

        <div class="verticalmainTable"  style="background:<?php echo $settings['scollerBackground'];?>">

                <div id="verticalmainscollertd" style="background:<?php echo $settings['scollerBackground'];?>;display:inline-block">
                     <?php $settings['auto']=(int)$settings['auto'];?>
                      <?php if($settings['auto']==0 or $settings['auto']==2){?>
                        <div class="uparrow">
                            <img class="prev_vertical previmg" src="<?php echo plugin_dir_url(__FILE__);?>images/uparrow.png" />
                        </div>
                        <?php } ?>   

                        <div class="verticalmainSliderDiv">
                            <ul class="sliderUl">
                                <?php
                                    global $wpdb;
                                    $imageheight=$settings['imageheight'];
                                    $imagewidth=$settings['imagewidth'];
                                    $query="SELECT * FROM ".$wpdb->prefix."vertical_thumbnail_slider order by createdon desc";
                                    $rows=$wpdb->get_results($query,'ARRAY_A');

                                    if(count($rows) > 0){
                                        foreach($rows as $row){

                                            $imagename=$row['image_name'];
                                            $imageUploadTo=$pathToImagesFolder.'/'.$imagename;
                                            $imageUploadTo=str_replace("\\","/",$imageUploadTo);
                                            $pathinfo=pathinfo($imageUploadTo);
                                            $filenamewithoutextension=$pathinfo['filename'];
                                            $outputimg="";


                                            if($settings['resizeImages']==0){

                                                $outputimg = $baseurl.$row['image_name']; 

                                            }
                                             else{

                                                    $imagetoCheck=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                    $imagetoCheckSmall=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);


                                                  if(file_exists($imagetoCheck)){
                                                      $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];

                                                  }
                                                  else if(file_exists($imagetoCheckSmall)){
                                                      $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                                                  }
                                                  else{


                                                      if(function_exists('wp_get_image_editor')){


                                                          $image = wp_get_image_editor($pathToImagesFolder."/".$row['image_name']); 
                                                          if ( ! is_wp_error( $image ) ) {
                                                              $image->resize( $imagewidth, $imageheight, true );
                                                              $image->save( $imagetoCheck );
                                                              //$outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];

                                                              if(file_exists($imagetoCheck)){
                                                                  $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                              }
                                                              else if(file_exists($imagetoCheckSmall)){
                                                                  $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                                                              }
                                                          }
                                                          else{
                                                              $outputimg = $baseurl.$row['image_name'];
                                                          }     

                                                      }
                                                      else if(function_exists('image_resize')){

                                                          $return=image_resize($pathToImagesFolder."/".$row['image_name'],$imagewidth,$imageheight) ;
                                                          if ( ! is_wp_error( $return ) ) {

                                                              $isrenamed=rename($return,$imagetoCheck);
                                                              if($isrenamed){
                                                                  //$outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];  

                                                                    if(file_exists($imagetoCheck)){
                                                                          $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                                                      }
                                                                      else if(file_exists($imagetoCheckSmall)){
                                                                          $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                                                                      }


                                                              }
                                                              else{
                                                                  $outputimg = $baseurl.$row['image_name']; 
                                                              } 
                                                          }
                                                          else{
                                                              $outputimg = $baseurl.$row['image_name'];
                                                          }  
                                                      }
                                                      else{

                                                          $outputimg = $baseurl.$row['image_name'];
                                                      }  

                                                      //$url = plugin_dir_url(__FILE__)."imagestoscroll/".$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];

                                                  } 
                                              }

                                        ?>

                                        <li class="sliderimgLiVertical">
                                            <?php if($settings['linkimage']==true){ ?> 
                                                <a target="_blank" href="<?php if($row['custom_link']==""){echo '';}else{echo $row['custom_link'];} ?>"><img src="<?php echo $outputimg; ?>" alt="<?php echo $row['title']; ?>" title="<?php echo $row['title']; ?>" style="width:<?php echo $settings['imagewidth']; ?>px;height:<?php echo $settings['imageheight']; ?>px"  /></a>
                                                <?php }else{ ?>
                                                <img src="<?php echo $outputimg;?>" alt="<?php echo $row['title']; ?>" title="<?php echo $row['title']; ?>" style="width:<?php echo $settings['imagewidth']; ?>px;height:<?php echo $settings['imageheight']; ?>px"  />
                                                <?php } ?> 
                                        </li>
                                        <?php
                                        }
                                    }  
                                ?>
                            </ul>
                        </div>
                    <?php if($settings['auto']==0 or $settings['auto']==2){?>
                        <div class="downarrow">
                            <img class="next_vertical nextimg" src="<?php echo plugin_dir_url(__FILE__);?>images/downarrow.png" />
                        </div>
                        <?php }?>  
                </div>  

        </div>

        <script type="text/javascript">
            var $n = jQuery.noConflict();  
            $n(document).ready(function() {


                    $n(".verticalmainSliderDiv").jCarouselLite({
                            btnNext: ".next_vertical",
                            btnPrev: ".prev_vertical",
                            <?php if($settings['auto']){?>
                                auto: <?php echo $settings['speed']; ?>,
                                <?php } ?>
                            speed: <?php echo $settings['speed']; ?>,
                            <?php if($settings['pauseonmouseover'] and $settings['auto']){ ?>
                                hoverPause: true,
                                <?php }else{ if($settings['auto']){?>   
                                    hoverPause: false,
                                    <?php }} ?>
                            circular: <?php echo ($settings['circular'])? 'true':'false' ?>,
                            <?php if($settings['visible']!=""){ ?>
                                visible: <?php echo $settings['visible'].','; ?>
                                <?php } ?>
                            scroll: <?php echo $settings['scroll']; ?>,
                            vertical:'true'

                    });

                    $n("#verticalmainscollertd").css("visibility","visible")


            });
        </script>
        
    <?php else:?>
                                
        <style type='text/css' >


                #vertical_thum_resp_slider_main .bx-wrapper div.bx-viewport{
                background: <?php echo $settings['scollerBackground']; ?> !important;
                padding-top:<?php echo $settings['imageMargin'];?>px;
                padding-bottom:<?php echo $settings['imageMargin'];?>px;



            }
            
            #vertical_thum_resp_slider_main .bx-wrapper .sliderimgLiVerticalres{padding-left:<?php echo $settings['imageMargin'];?>px;padding-right:<?php echo $settings['imageMargin'];?>px}
        </style>

         <div  class="bxv vertical_thum_resp_slider" id="vertical_thum_resp_slider_main" >
             <div class="sub-vertical-slider" id="vertical_thum_resp_slider">

                  <?php

                    global $wpdb;
                    $imageheight=$settings['imageheight'];
                    $imagewidth=$settings['imagewidth'];
                    $query="SELECT * FROM ".$wpdb->prefix."vertical_thumbnail_slider order by createdon desc";
                    $rows=$wpdb->get_results($query,'ARRAY_A');

                    if(count($rows) > 0){
                        foreach($rows as $row){

                            $wpcurrentdir=dirname(__FILE__);
                            $wpcurrentdir=str_replace("\\","/",$wpcurrentdir);
                            $imagename=$row['image_name'];
                            $imageUploadTo=$pathToImagesFolder.'/'.$imagename;
                            $imageUploadTo=str_replace("\\","/",$imageUploadTo);
                            $pathinfo=pathinfo($imageUploadTo);
                            $filenamewithoutextension=$pathinfo['filename'];
                            $outputimg="";


                            if($settings['resizeImages']==0){

                                $outputimg = $baseurl.$row['image_name']; 

                            }
                            else{

                                $imagetoCheck=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                $imagetoCheckSmall=$pathToImagesFolder.'/'.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);

                                if(file_exists($imagetoCheck)){

                                    $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                }
                                else if(file_exists($imagetoCheckSmall)){

                                    $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                                }
                                else{

                                    if(function_exists('wp_get_image_editor')){

                                        $image = wp_get_image_editor($pathToImagesFolder."/".$row['image_name']); 

                                        if ( ! is_wp_error( $image ) ) {
                                            $image->resize( $imagewidth, $imageheight, true );
                                            $image->save( $imagetoCheck );
                                            //$outputimg = plugin_dir_url(__FILE__)."imagestoscroll/".$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];

                                             if(file_exists($imagetoCheck)){
                                                $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];
                                              }
                                              else if(file_exists($imagetoCheckSmall)){
                                                  $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);
                                              }

                                        }
                                        else{
                                           $outputimg = $baseurl.$row['image_name'];
                                        }     

                                    }
                                    else if(function_exists('image_resize')){

                                        $return=image_resize($pathToImagesFolder."/".$row['image_name'],$imagewidth,$imageheight) ;
                                        if ( ! is_wp_error( $return ) ) {

                                            $isrenamed=rename($return,$imagetoCheck);
                                            if($isrenamed){
                                               // $outputimg = plugin_dir_url(__FILE__)."imagestoscroll/".$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];  

                                                 if(file_exists($imagetoCheck)){

                                                    $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.$pathinfo['extension'];

                                                   }
                                                    else if(file_exists($imagetoCheckSmall)){

                                                        $outputimg = $baseurl.$filenamewithoutextension.'_'.$imageheight.'_'.$imagewidth.'.'.strtolower($pathinfo['extension']);

                                                    }


                                            }
                                            else{

                                                $outputimg = $baseurl.$row['image_name']; 
                                            } 
                                        }
                                        else{

                                            $outputimg = $baseurl.$row['image_name']; 
                                        }  
                                    }
                                    else{

                                       $outputimg = $baseurl.$row['image_name']; 
                                    }  


                                } 


                            }

                            $title="";
                             $rowTitle=$row['title'];
                             $rowTitle=str_replace("'","’",$rowTitle); 
                             $rowTitle=str_replace('"','”',$rowTitle); 


                        ?>  
                 <div class="sliderimgLiVerticalres"> 
                        <?php if($settings['linkimage']==true){ ?>                                                                                                                                                                                                                                                                                     
                          <a <?php if($row['custom_link']!=""):?> href="<?php echo $row['custom_link'];?>" <?php endif;?> title="<?php echo $rowTitle; ?>" ><img src="<?php echo $outputimg; ?>" alt="<?php echo $rowTitle; ?>" title="<?php echo $rowTitle; ?>"   /></a>
                        <?php }else{ ?>
                              <img src="<?php echo $outputimg;?>" alt="<?php echo $rowTitle; ?>" title="<?php echo $rowTitle; ?>"   />
                        <?php } ?> 
                     </div>
                   <?php }?> 

                </div>
              </div>
        <script>
           var $n = jQuery.noConflict();  
           var slider='';
           var maxHeightSlider=0;
          $n(document).ready(function(){
             sliderVertcal=$n('#vertical_thum_resp_slider').bxSlider_vertical({
              mode: 'vertical',
              slideWidth: <?php echo $imagewidth;?>,
              moveSlides: <?php echo $settings['scroll'];?>,
              minSlides: <?php echo $settings['visible'];?>,
              maxSlides:<?php echo $settings['visible'];?>,
              speed:<?php echo $settings['speed']; ?>,
              pause:<?php echo $settings['pause']; ?>,
              slideMargin:<?php echo $settings['imageMargin'];?>,
              <?php if($settings['auto']==1 or $settings['auto']==2):?>
                autoStart:true,
                autoDelay:200,
                auto:true,       
               <?php endif;?>
               <?php if($settings['circular']):?> 
                infiniteLoop: true,
               <?php else: ?>
                 infiniteLoop: false,
               <?php endif;?>    
              <?php if($settings['pauseonmouseover'] and ($settings['auto']==1 or $settings['auto']==2) ){ ?>
                autoHover: true,
              <?php }else{ if($settings['auto']==1 or $settings['auto']==2){?>   
                autoHover:false,
              <?php }} ?>
              <?php if($settings['auto']==1):?>
               controls:false,
              <?php else: ?>
                controls:true,
              <?php endif;?>
              pager:false,
              useCSS:false,
              onSliderLoad: function(currentIndex){

                  maxHeightSlider=$n('#vertical_thum_resp_slider_main .bx-wrapper div.bx-viewport').css('height');
                  $n("#vertical_thum_resp_slider").attr('data-height',maxHeightSlider);
                  //$n("#vertical_thum_resp_slider_main").find(".bx-wrapper").find(".bx-prev").css({marginLeft:$n("#vertical_thum_resp_slider_main .bx-wrapper .bx-viewport").width()/2+'px'});
                  //$n("#vertical_thum_resp_slider_main").find(".bx-wrapper").find(".bx-next").css({marginLeft:$n("#vertical_thum_resp_slider_main .bx-wrapper .bx-viewport").width()/2+'px'});
                                  
              }




            });

            var timer;
            var width = $n(window).width();
            $n(window).bind('resize', function(){
                if($n(window).width() != width){

                    width = $n(window).width();
                    timer && clearTimeout(timer);
                    timer = setTimeout(onResizeVerticalSlider, 600);

                }   
            });

            function onResizeVerticalSlider(){

                $n("#vertical_thum_resp_slider").removeAttr('data-height');

                var current = sliderVertcal.getCurrentSlide();
                sliderVertcal.reloadSlider();

                 maxHeightSlider=$n('#vertical_thum_resp_slider_main .bx-wrapper div.bx-viewport').css('height');
                  $n("#vertical_thum_resp_slider").attr('data-height',maxHeightSlider);

                // $n("#vertical_thum_resp_slider_main").find(".bx-wrapper").find(".bx-prev").css({marginLeft:$n("#vertical_thum_resp_slider_main .bx-wrapper .bx-viewport").width()/2+'px'});
                // $n("#vertical_thum_resp_slider_main").find(".bx-wrapper").find(".bx-next").css({marginLeft:$n("#vertical_thum_resp_slider_main .bx-wrapper .bx-viewport").width()/2+'px'});
                                 
                <?php if($settings['auto']==0):?>
                    sliderVertcal.goToSlide(current);
                 <?php endif;?> 
                

            }




          });


      </script><!-- end print_vertical_thumbnail_slider_func -->
     <?php }?>  

    <?php endif;?>    
    <?php
        $output = ob_get_clean();
        return $output;
    }
    
    
     function vertical_thumbnail_slider_get_wp_version() {

        global $wp_version;
        return $wp_version;
    }

    //also we will add an option function that will check for plugin admin page or not
    function vertical_thumbnail_slider_is_plugin_page() {

        $server_uri = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";

        foreach (array('vertical_thumbnail_slider_image_management','vertical_thumbnail_slider') as $allowURI) {
            if(stristr($server_uri, $allowURI)) return true;
        }
        return false;
    }

    //add media WP scripts
    function vertical_thumbnail_slider_admin_scripts_init() {

        if(vertical_thumbnail_slider_is_plugin_page()) {
            //double check for WordPress version and function exists
            if(function_exists('wp_enqueue_media') && version_compare(vertical_thumbnail_slider_get_wp_version(), '3.5', '>=')) {
                //call for new media manager
                wp_enqueue_media();
            }
            wp_enqueue_style('media');
             wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'wp-color-picker' );
        }
    }
    
    function vis_remove_extra_p_tags($content){

        if(strpos($content, 'print_vertical_thumbnail_slider_func')!==false){
        
            
            $pattern = "/<!-- print_vertical_thumbnail_slider_func -->(.*)<!-- end print_vertical_thumbnail_slider_func -->/Uis"; 
            $content = preg_replace_callback($pattern, function($matches) {


               $altered = str_replace("<p>","",$matches[1]);
               $altered = str_replace("</p>","",$altered);
              
                $altered=str_replace("&#038;","&",$altered);
                $altered=str_replace("&#8221;",'"',$altered);
              

              return @str_replace($matches[1], $altered, $matches[0]);
            }, $content);

              
            
        }
        
        $content = str_replace("<p><!-- print_vertical_thumbnail_slider_func -->","<!-- print_vertical_thumbnail_slider_func -->",$content);
        $content = str_replace("<!-- end print_vertical_thumbnail_slider_func --></p>","<!-- end print_vertical_thumbnail_slider_func -->",$content);
        
        
        return $content;
  }

  add_filter('widget_text_content', 'vis_remove_extra_p_tags', 999);
  add_filter('the_content', 'vis_remove_extra_p_tags', 999);
?>