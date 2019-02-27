<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function hugeit_vp_get_youtube_thumb_id_from_url($url){
	if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
		return $match[1];
	}
}

function hugeit_vp_hex2RGB($hexStr, $returnAsString = true, $seperator = ',') {
    $hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
    $rgbArray = array();
    if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
        $colorVal = hexdec($hexStr);
        $rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
        $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
        $rgbArray['blue'] = 0xFF & $colorVal;
    } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
        $rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
        $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
        $rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
    } else {
        return false; //Invalid hex color code
    }
    return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
}

function hugeit_vp_front_end_video_player($videos, $paramssld, $video_player) {
	ob_start();
	if(isset($video_player[0]->id)) :
        $video_playerID = absint($video_player[0]->id);
        $video_playertitle = esc_html($video_player[0]->name);
        $video_playeralbum = esc_html($video_player[0]->album_single);
        $videoAautoPlay = absint($video_player[0]->autoplay);
        $width = absint($video_player[0]->width);
	$path_site = plugins_url("../images", __FILE__);
	switch($video_playeralbum){
		case 'single':
			$j=0;
			?>
			<script>
				jQuery(document).ready(function(){
					var tag = document.createElement('script');
					tag.src = "https://www.youtube.com/iframe_api";
					var firstScriptTag = document.getElementsByTagName('script')[0];
					firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
				});
				function onYouTubeIframeAPIReady(){
					<?php
					foreach($videos as $video){
						if($video->sl_type=="youtube"){
							$video_thumb_url = hugeit_vp_get_youtube_thumb_id_from_url($video->video_url_1);
							$video_id = absint($video->id);
							$last = 0;
                            if ($video_id == $videos[0]->id) {
                                $last = 1;
                            }
							?>
							var youtube_single_player_<?php echo $video_id; ?>;
							youtube_single_player_<?php echo $video_id; ?> = new YT.Player('youtube_single_player_<?php echo $video_id; ?>',{
								videoId		: '<?php echo $video_thumb_url; ?>',
								enablejsapi      : 1,
								playerVars:{
									'autohide':			<?php echo absint($paramssld['video_pl_yt_autohide']); ?>,
									'autoplay':			<?php if( $j==0 && $last == 1 ){ echo absint($video_player[0]->autoplay); }else{ echo 0; } ?>,
									'controls': 		1,
									'fs':				<?php echo absint($paramssld['video_pl_yt_fullscreen']); ?>,
									'disablekb':		0,
									'modestbranding':	1,
									'enablejsapi': 1,
									// 'cc_load_policy': 1, // forces closed captions on
									'iv_load_policy':	<?php echo absint($paramssld['video_pl_yt_annotation']); ?>, // annotations, 1=on, 3=off
									'rel':				<?php echo absint($paramssld['video_pl_yt_related']); ?>,
									'showinfo':			<?php echo absint($paramssld['video_pl_yt_showinfo']); ?>,
									'theme':			'<?php echo esc_html($paramssld['video_pl_yt_theme']); ?>',	// dark, light
									'color':			'<?php echo esc_html($paramssld['video_pl_yt_color']); ?>'	// red, white
								},

							});
							
							if(<?php echo absint($video_player[0]->autoplay); ?>==0 <?php echo '||' . $last; ?>==0){
								jQuery("#youtube_single_player_container_<?php echo $video_id; ?> .thumbnail_block").css({display:'block'});
							}
							jQuery("#youtube_single_player_container_<?php echo $video_id; ?> .thumbnail_block").on("click",function(){
								jQuery("#youtube_single_player_container_<?php echo $video_id; ?> .thumbnail_block").css({display:'none'});
								youtube_single_player_<?php echo $video_id; ?>.playVideo();
							});

							function ythw<?php echo $video_id; ?>(){
								var w=<?php echo $width; ?>;
								if(jQuery("#youtube_single_player_container_<?php echo $video_id; ?>").parent().width()<w){
									document.getElementById("youtube_single_player_container_<?php echo $video_id; ?>").style.width="100%";
									var w=document.getElementById("youtube_single_player_container_<?php echo $video_id; ?>").offsetWidth;
									document.getElementById("youtube_single_player_container_<?php echo $video_id; ?>").style.height=w*0.56+"px";
								}else{
									document.getElementById("youtube_single_player_container_<?php echo $video_id; ?>").style.width="<?php echo $width; ?>px";
									document.getElementById("youtube_single_player_container_<?php echo $video_id; ?>").style.height="<?php echo $width*0.56; ?>px";
								}
							}
							ythw<?php echo $video_id; ?>();
							jQuery(window).on("resize",function(){
								ythw<?php echo $video_id; ?>();
							});

							<?php
						}
					}
					?>
				}
			</script>
			<?php 
			
			foreach($videos as $video){
					$video_playeralbum = $video->sl_type;
                    $video_id = absint($video->id);
                    $margin_left = absint($paramssld['video_pl_margin_left']);
                    $margin_right = absint($paramssld['video_pl_margin_right']);
                    $margin_top = absint($paramssld['video_pl_margin_top']);
                    $margin_bottom = absint($paramssld['video_pl_margin_bottom']);
                    $border_size = absint($paramssld['video_pl_border_size']);
                    $border_color = esc_html($paramssld['video_pl_border_color']);

					switch($video_playeralbum){
						case 'video':
                        $i=rand(1,100000);
                        ?>
                        <script type="text/javascript">
                            /* init single video player when document is ready */
                            jQuery(document).ready(function(){
                                var huge_it_single_player_interval_<?php echo $i; ?> = setInterval(function(){
                                    if(jQuery("#huge_it_sigle_video_player_<?php echo $i; ?>").is(":visible")){
                                        init_huge_it_single_video_player_<?php echo $i; ?>("#huge_it_sigle_video_player_<?php echo $i; ?>");
                                        clearInterval(huge_it_single_player_interval_<?php echo $i; ?>);
                                    }
                                },100);
                            });

                            /* Single Player */
                            function init_huge_it_single_video_player_<?php echo $i; ?>(video_container_id){

                                var container,video,thumb_box,thumb,paused,dragging,volume_dragging,volume,progress_duration_bar,progress_bar,progress_played_bar,progress_thumb,progress_buffered,current_time,duration_time,current_time_text,duration_time_text,autoplay,video_param_width,video_width,play_btn,center_play_icon,center_pause_icon,center_wait_icon,backward_btn,forward_btn,mute_btn,volume_handle,volume_before,volume_current,volume_after,fullscreen_btn,hover_timer_box,hover_timer;
                                current_time="00";
                                duration_time="00";

                                volume=1;
                                dragging="";
                                paused="";
                                volume_dragging="";
                                /* PLUGIN PARAMETERS */
                                <?php
                                $last = 0;
                                if ($video_id == $videos[0]->id) {
                                    $last = 1;
                                }
                                if($video_player[0]->autoplay==1 && $last == 1){
                                    $autoplay="true";
                                }else{
                                    $autoplay="false";
                                }
                                ?>
                                autoplay='<?php echo $autoplay; ?>';
                                video_width=<?php echo $width; ?>;
                                video_param_width=<?php echo $width; ?>;

                                /* check if video player exists */
                                container=document.querySelector(video_container_id);
                                if(container){
                                    /* SET OBJECT LISTENERS */
                                    /* *** */
                                    video=container.querySelector("video");
                                    play_btn=container.querySelector(".play_pause");
                                    center_play_icon=container.querySelector(".center_play");
                                    center_pause_icon=container.querySelector(".center_pause");
                                    center_wait_icon=container.querySelector(".center_wait");
                                    backward_btn=container.querySelector(".fast_back");
                                    forward_btn=container.querySelector(".fast_forward");
                                    mute_btn=container.querySelector(".mute_button");
                                    volume_handle=container.querySelector(".volume_handle");
                                    volume_before=container.querySelector(".volume_before");
                                    volume_current=container.querySelector(".volume_current");
                                    volume_after=container.querySelector(".volume_after");
                                    fullscreen_btn=container.querySelector(".full_screen");
                                    current_time_text=container.querySelector(".current_time");
                                    duration_time_text=container.querySelector(".duration_time");
                                    progress_bar=container.querySelector(".huge_it_video_player_duration_slide");
                                    progress_played_bar=container.querySelector(".played");
                                    progress_thumb=container.querySelector(".thumb");
                                    progress_buffered=container.querySelector(".buffered");
                                    hover_timer_box=container.querySelector(".hover_timer");
                                    hover_timer=container.querySelector(".hover_timer_time");
                                    thumb_box=container.querySelector(".thumbnail_block");
                                    thumb=container.querySelector(".thumbnail_block img");
                                    /* *** */
                                    /* FUNCTIONS */

                                    function timeupdate(){
                                        if(!isNaN(video.currentTime)){
                                            current_time=Math.floor(video.currentTime);
                                        }
                                        if(!isNaN(video.duration)){
                                            duration_time=Math.floor(video.duration);
                                        }

                                        video_width=video.offsetWidth;
                                        var curmins = Math.floor(video.currentTime / 60);
                                        var cursecs = Math.floor(video.currentTime - curmins * 60);
                                        var durmins = Math.floor(video.duration / 60);
                                        var dursecs = Math.floor(video.duration - durmins * 60);
                                        if(cursecs < 10){ cursecs = "0"+cursecs; }
                                        if(dursecs < 10){ dursecs = "0"+dursecs; }
                                        if(curmins < 10){ curmins = "0"+curmins; }
                                        if(durmins < 10){ durmins = "0"+durmins; }
                                        current_time_text.innerHTML = curmins+":"+cursecs;
                                        duration_time_text.innerHTML = durmins+":"+dursecs;

                                        var current_time_percent = current_time*(100/duration_time);
                                        var progress_bar_pixels=(current_time_percent*video_width)/100;
                                        progress_played_bar.style.width=progress_bar_pixels+"px";
                                        if(progress_bar_pixels+progress_thumb.offsetWidth/2 > video_width){
                                            var last_px = video_width-progress_thumb.offsetWidth/2;
                                            progress_thumb.style.left=last_px+"px";

                                        }else if(progress_bar_pixels < progress_thumb.offsetWidth/2){
                                            var first_px = progress_thumb.offsetWidth/2;
                                            progress_thumb.style.left=first_px+"px";
                                        }else{
                                            progress_thumb.style.left=progress_bar_pixels+"px";
                                        }

                                    }

                                    function progressHandler(){
                                        if(!isNaN(video.currentTime)){
                                            current_time=Math.floor(video.currentTime);
                                        }
                                        if(!isNaN(video.duration)){
                                            duration_time=Math.floor(video.duration);
                                        }
                                        if(!video.paused){
                                            play_btn.innerHTML='<i class="hugeicons hugeicons-pause"></i>';
                                        }
                                        if(thumb_box.style.display=="block" && !video.paused){
                                            thumb_box.style.display="none";
                                        }
                                        if(video.buffered.length > 0){
                                            var i=video.buffered.length;
                                            var buffered_end=video.buffered.end(i-1);
                                            var loaded_percent=(buffered_end/duration_time)*100;
                                            var progress_bar_pixels=(loaded_percent*video_width)/100;
                                            progress_buffered.style.width=progress_bar_pixels+"px";
                                        }
                                        if(!isFullScreen() && hasClass("hide_controls",container)){
                                            removeClass("hide_controls",container);
                                        }
                                    }

                                    function VideoClickPlayPause(){
                                        if(video.paused){
                                            /* PLAY THE VIDEO */
                                            video.play();
                                            paused="";
                                            addClass("playing",container);
                                            play_btn.innerHTML='<i class="hugeicons hugeicons-pause"></i>';
                                            center_play_icon.style.webkitAnimationName="popup";
                                            center_play_icon.style.display="block";
                                            setTimeout(function(){
                                                center_play_icon.removeAttribute("style");
                                            },500);
                                        }else{
                                            /* PAUSE THE VIDEO */
                                            video.pause();
                                            paused=1;
                                            removeClass("playing",container);
                                            play_btn.innerHTML='<i class="hugeicons hugeicons-play"></i>';
                                            center_pause_icon.style.webkitAnimationName="popup";
                                            center_pause_icon.style.display="block";
                                            setTimeout(function(){
                                                center_pause_icon.removeAttribute("style");
                                            },500);
                                        }
                                    }

                                    function PlayPause(){

                                        if(hasClass("poster",container)){
                                            video.play();
                                            paused="";
                                            thumb_box.style.display="none";
                                            removeClass("poster",container);
                                            addClass("playing",container);
                                            play_btn.innerHTML='<i class="hugeicons hugeicons-pause"></i>';
                                        }else{
                                            if(video.paused){
                                                /* PLAY THE VIDEO */
                                                video.play();
                                                paused="";
                                                addClass("playing",container);
                                                play_btn.innerHTML='<i class="hugeicons hugeicons-pause"></i>';
                                            }else{
                                                /* PAUSE THE VIDEO */
                                                video.pause();
                                                paused=1;
                                                removeClass("playing",container);
                                                play_btn.innerHTML='<i class="hugeicons hugeicons-play"></i>';
                                            }
                                        }
                                    }

                                    function step_back(){
                                        if(!isNaN(video.currentTime)){
                                            current_time=Math.floor(video.currentTime);
                                        }
                                        if(!isNaN(video.duration)){
                                            duration_time=Math.floor(video.duration);
                                        }
                                        video.currentTime=current_time-15;
                                    }

                                    function step_forward(){
                                        if(!isNaN(video.currentTime)){
                                            current_time=Math.floor(video.currentTime);
                                        }
                                        if(!isNaN(video.duration)){
                                            duration_time=Math.floor(video.duration);
                                        }
                                        video.currentTime=current_time+15;
                                    }

                                    function toggle_mute(){
                                        if(video.muted){
                                            video.muted=false;
                                            if(volume<0.7){
                                                mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-down"></i>';
                                            }else{
                                                mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-up"></i>';
                                            }
                                            var volume_handle_width=volume_handle.offsetWidth;
                                            var volume_current_pixels=volume*volume_handle_width;
                                            volume_before.style.width=volume_current_pixels+"px";
                                            volume_current.style.left=volume_current_pixels+"px";
                                        }else{
                                            var volume_handle_width=volume_handle.offsetWidth;
                                            var volume_current_width=volume_before.offsetWidth;
                                            volume=volume_current_width/volume_handle_width;
                                            video.muted=true;
                                            mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-off"></i>';
                                            volume_before.style.width="0px";
                                            volume_current.style.left="0px";
                                        }
                                    }

                                    function saveVolume(){
                                        var volume_handle_width=volume_handle.offsetWidth;
                                        var volume_current_pixels=volume*volume_handle_width;
                                        volume_before.style.width=volume_current_pixels+"px";
                                        volume_current.style.left=volume_current_pixels+"px";
                                    }

                                    function exitHandler(){
                                        if (isFullScreen())
                                            console.log("");
                                        // nothing
                                        else
                                            cFullScreen();
                                    }

                                    function toggle_full_screen(){
                                        if (isFullScreen())
                                            cFullScreen();
                                        else
                                            requestFullScreen(container || document.documentElement);

                                        jQuery.when(toggle_full_screen).done(function(){
                                            setTimeout(function(){
                                                video_width=video.offsetWidth;
                                                timeupdate();
                                                progressHandler();
                                                saveVolume();
                                            },800);
                                        });
                                    }

                                    function isFullScreen(){
                                        return (document.fullScreenElement && document.fullScreenElement !== null)
                                            || document.mozFullScreen
                                            || document.webkitIsFullScreen;
                                    }

                                    function vidSeeking(e){
                                        var x = e.clientX;
                                        var l=container.getBoundingClientRect().left;
                                        var pos = x-l;
                                        if(pos>=0 && pos<video_width){
                                            progress_played_bar.style.width = pos+"px";
                                            progress_thumb.style.left = pos+"px";
                                            var current_percent=(pos/video_width)*100;
                                            var seekto=duration_time*(current_percent/100);
                                            video.currentTime=seekto;
                                        }
                                    }

                                    function requestFullScreen(element){
                                        if (element.requestFullscreen)
                                            element.requestFullscreen();
                                        else if (element.msRequestFullscreen)
                                            element.msRequestFullscreen();
                                        else if (element.mozRequestFullScreen)
                                            element.mozRequestFullScreen();
                                        else if (element.webkitRequestFullscreen)
                                            element.webkitRequestFullscreen();
                                        addClass("fullscreen",container);

                                        fullscreen_btn.innerHTML='<i class="hugeicons hugeicons-compress"></i>';
                                        jQuery.when(requestFullScreen).done(function(){
                                            setTimeout(function(){
                                                video_width=video.offsetWidth;
                                                timeupdate();
                                                progressHandler();
                                                saveVolume();
                                            },500);

                                        });

                                    }

                                    function cFullScreen(){
                                        if (document.exitFullscreen)
                                            document.exitFullscreen();
                                        else if (document.msExitFullscreen)
                                            document.msExitFullscreen();
                                        else if (document.mozCancelFullScreen)
                                            document.mozCancelFullScreen();
                                        else if (document.webkitExitFullscreen)
                                            document.webkitExitFullscreen();
                                        removeClass("fullscreen",container);
                                        removeClass("hide_controls",container);
                                        fullscreen_btn.innerHTML='<i class="hugeicons hugeicons-expand"></i>';
                                        jQuery.when(cFullScreen).done(function(){
                                            setTimeout(function(){
                                                video_width=video.offsetWidth;
                                                timeupdate();
                                                progressHandler();
                                                saveVolume();
                                            },500);
                                        });
                                    }

                                    function setVolume(e){
                                        var x = e.clientX;
                                        var l=volume_handle.getBoundingClientRect().left;
                                        var pos = x-l;
                                        if(pos>0 && pos<=volume_handle.offsetWidth){
                                            volume_before.style.width=pos+"px";
                                            volume_current.style.left=pos+"px";
                                            var volume_handle_width=volume_handle.offsetWidth;
                                            var current_percent=(pos/volume_handle_width)*100;
                                            volume=current_percent/100;
                                            video.muted=false;
                                            video.volume=current_percent/100;
                                            if(current_percent<70){
                                                if(current_percent==0){
                                                    mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-off"></i>';
                                                }else{
                                                    mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-down"></i>';
                                                }
                                            }else{
                                                mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-up"></i>';
                                            }
                                        }else{
                                            if(pos<=0){
                                                video.muted=true;
                                                mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-off"></i>';
                                                volume_before.style.width="0px";
                                                volume_current.style.left="0px";
                                            }
                                        }
                                    }

                                    function waiting(){
                                        center_wait_icon.style.display="block";
                                        center_wait_icon.style.opacity="1";
                                    }

                                    function notWaiting(){
                                        center_wait_icon.style.display="none";
                                        center_wait_icon.style.opacity="0";
                                    }

                                    function videoEnd(){
                                        play_btn.innerHTML ='<i class="hugeicons hugeicons-refresh"></i>';
                                        paused=1;
                                        removeClass("playing",container);
                                    }

                                    function timerDisplay(e){
                                        if(video.buffered.length > 0){
                                            var x = e.clientX;
                                            var l=container.getBoundingClientRect().left;
                                            var w=hover_timer_box.offsetWidth;
                                            var pos = x-l;
                                            var mouse_pos_percent= (pos/video_width)*100;
                                            var mouse_pos_time=duration_time*(mouse_pos_percent/100);
                                            var curmins = Math.floor(mouse_pos_time / 60);
                                            var cursecs = Math.floor(mouse_pos_time - curmins * 60);
                                            if(cursecs < 10){ cursecs="0"+cursecs; }
                                            if(curmins < 10){ curmins="0"+curmins; }
                                            //hover_timer_box.style.webkitAnimationName="opacity";
                                            hover_timer_box.style.opacity="1";
                                            hover_timer_box.style.display = "block";
                                            hover_timer_box.style.left = x-l-w/2+"px";
                                            hover_timer.innerHTML = curmins+":"+cursecs;
                                        }
                                    }

                                    function timerNotDisplay(e){
                                        hover_timer_box.style.display = "none";
                                    }

                                    function keyFunctions(e){
                                        switch(e.keyCode){
                                            case 40:
                                                var curent_volume=video.volume;
                                                var curent_volume_percent=curent_volume*100;
                                                if(curent_volume_percent>0){
                                                    var new_volume_percent=curent_volume_percent-10;
                                                    if(new_volume_percent<=0){
                                                        mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-off"></i>';
                                                        volume_before.style.width="0px";
                                                        volume_current.style.left="0px";
                                                        video.muted=true;
                                                        volume=0;
                                                    }else{
                                                        if(new_volume_percent<70){
                                                            mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-down"></i>';
                                                        }else{
                                                            mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-up"></i>';
                                                        }
                                                        var new_volume=new_volume_percent/100;
                                                        var volume_handle_width=volume_handle.offsetWidth;
                                                        var new_position=new_volume*volume_handle_width;
                                                        volume_before.style.width=new_position+"px";
                                                        volume_current.style.left=new_position+"px";
                                                        video.muted=false;
                                                        video.volume=new_volume;
                                                        volume=new_volume;
                                                    }

                                                }
                                                e.preventDefault();
                                                break;
                                            case 38:
                                                var curent_volume=video.volume;
                                                var curent_volume_percent=curent_volume*100;
                                                if(curent_volume_percent<100){
                                                    var new_volume_percent=curent_volume_percent+10;
                                                    if(new_volume_percent>100){
                                                        new_volume_percent=100;
                                                    }
                                                    if(new_volume_percent<70){
                                                        mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-down"></i>';
                                                    }else{
                                                        mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-up"></i>';
                                                    }
                                                    var new_volume=new_volume_percent/100;
                                                    var volume_handle_width=volume_handle.offsetWidth;
                                                    var new_position=new_volume*volume_handle_width;
                                                    volume_before.style.width=new_position+"px";
                                                    volume_current.style.left=new_position+"px";
                                                    video.volume=new_volume;
                                                    video.muted=false;
                                                    volume=new_volume;
                                                }
                                                e.preventDefault();
                                                break;
                                            case 39:
                                                step_forward();
                                                e.preventDefault();
                                                break;
                                            case 37:
                                                step_back();
                                                e.preventDefault();
                                                break;
                                            case 32:
                                                VideoClickPlayPause();
                                                e.preventDefault();
                                                break;
                                        }
                                    }

                                    function huge_it_single_video_responsive(){
                                        var video_parent=container.parentNode;
                                        var computedStyle = getComputedStyle(video_parent);
                                        var video_parent_width=video_parent.clientWidth-parseFloat(computedStyle.paddingRight)-parseFloat(computedStyle.paddingLeft);
                                        if(!isFullScreen()){
                                            if(video_parent_width<=video_param_width){
                                                addClass("fullwidth",container);
                                                jQuery.when(huge_it_single_video_responsive).done(function(){
                                                    setTimeout(function(){
                                                        video_width=video.offsetWidth;
                                                        videoResize();
                                                    },500);
                                                });
                                            }else{
                                                removeClass("fullwidth",container);
                                                jQuery.when(huge_it_single_video_responsive).done(function(){
                                                    setTimeout(function(){
                                                        video_width=video.offsetWidth;
                                                        videoResize();
                                                    },500);
                                                });
                                            }

                                        }else{
                                            setTimeout(function(){
                                                video_width=video.offsetWidth;
                                            },500);
                                        }
                                    }

                                    function videoResize(){
                                        setTimeout(function(){
                                            video_width=video.offsetWidth;
                                            timeupdate();
                                            progressHandler();
                                            saveVolume();
                                            if(video_width<325){
                                                addClass("small",container);
                                            }else{
                                                removeClass("small",container);
                                            }
                                            if(video_width<225){
                                                addClass("very_small",container);
                                            }else{
                                                removeClass("very_small",container);
                                            }

                                        },200);
                                    }

                                    /* helping functions */
                                    /* *** */
                                    function addClass( classname, element ) {
                                        var cn = element.className;
                                        /*test for existance */
                                        if( cn.indexOf( classname ) != -1 ) {
                                            return;
                                        }
                                        /* add a space if the element already has class */
                                        if( cn != '' ) {
                                            classname = ' '+classname;
                                        }
                                        element.className = cn+classname;
                                    }

                                    function removeClass( classname, element ) {
                                        var cn = element.className;
                                        var rxp = new RegExp( "\\s?\\b"+classname+"\\b", "g" );
                                        cn = cn.replace( rxp, '' );
                                        element.className = cn;
                                    }

                                    function hasClass(cls, element) {
                                        return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
                                    }

                                    /* ADD EVENT LISTENERS */
                                    /* *** */
                                    video.addEventListener("timeupdate",timeupdate,false);
                                    video.addEventListener("playing",progressHandler,false);
                                    video.addEventListener("loadeddata",progressHandler,false);
                                    video.addEventListener("canplaythrough",progressHandler,false);
                                    video.addEventListener("progress",progressHandler,false);
                                    video.addEventListener("waiting",waiting,false);
                                    video.addEventListener("canplay",notWaiting,false);
                                    video.addEventListener("ended",videoEnd,false);
                                    video.addEventListener("resize",videoResize,false);
                                    /* *** */
                                    video.addEventListener("click",VideoClickPlayPause,false);
                                    video.addEventListener("dblclick",toggle_full_screen,false);
                                    center_play_icon.addEventListener("click",VideoClickPlayPause,false);
                                    center_pause_icon.addEventListener("click",VideoClickPlayPause,false);
                                    center_wait_icon.addEventListener("click",VideoClickPlayPause,false);
                                    thumb_box.addEventListener("click",PlayPause,false);
                                    play_btn.addEventListener("click",PlayPause,false);
                                    backward_btn.addEventListener("click",step_back,false);
                                    forward_btn.addEventListener("click",step_forward,false);
                                    mute_btn.addEventListener("click",toggle_mute,false);
                                    fullscreen_btn.addEventListener("click",toggle_full_screen,false);
                                    container.addEventListener('webkitfullscreenchange', exitHandler, false);
                                    container.addEventListener('mozfullscreenchange', exitHandler, false);
                                    container.addEventListener('fullscreenchange', exitHandler, false);
                                    container.addEventListener('MSFullscreenChange', exitHandler, false);
                                    progress_bar.addEventListener("mouseover",timerDisplay,false);
                                    progress_bar.addEventListener("mousemove",timerDisplay,false);
                                    progress_bar.addEventListener("mouseout",timerNotDisplay,false);

                                    /* KEYBOARD */
                                    /* *** */
                                    container.addEventListener("mouseover",function(){
                                        window.addEventListener("keydown",keyFunctions,false);
                                    },false)

                                    container.addEventListener("mouseout",function(){
                                        window.removeEventListener("keydown",keyFunctions,false);
                                    },false)
                                    /* *** */

                                    /* VIDEO SEEKING */
                                    progress_bar.addEventListener("mousedown",function(e){
                                        dragging=1;
                                        vidSeeking(e);
                                    },false);

                                    window.addEventListener("mousemove",function(e){
                                        if(dragging==1){
                                            if(paused!=1){
                                                video.pause();
                                            }
                                            removeClass("playing",container);
                                            vidSeeking(e);
                                        }
                                    },false);
                                    window.addEventListener("mouseup",function(){
                                        if(dragging==1){
                                            if(paused!==1){
                                                video.play();
                                            }
                                            addClass("playing",container);
                                            dragging="";
                                        }
                                    },false);
                                    /* VOLUME CHANGING */
                                    volume_handle.addEventListener("mousedown",function(e){
                                        volume_dragging=1;
                                        setVolume(e);
                                    },false);
                                    window.addEventListener("mousemove",function(e){
                                        if(volume_dragging==1){
                                            setVolume(e);
                                        }
                                    },false);
                                    window.addEventListener("mouseup",function(){
                                        if(volume_dragging==1){
                                            volume_dragging="";
                                        }
                                    },false);

                                    /* ONLOAD STUFF */
                                    /* *** */
                                    if(autoplay=="true"){
                                        video.play();
                                        video.autoplay = true;
                                        addClass("playing",container);
                                        removeClass("poster",container);
                                        thumb_box.style.display="none";
                                        play_btn.innerHTML='<i class="hugeicons hugeicons-pause"></i>';
                                    }else{
                                        video.autoplay = false;
                                        removeClass("playing",container);
                                        play_btn.innerHTML='<i class="hugeicons hugeicons-play"></i>';
                                        addClass("poster",container);
                                        thumb_box.style.display="block";
                                    }

                                    huge_it_single_video_responsive();
                                    jQuery(window).on("resize",function(){
                                        huge_it_single_video_responsive();
                                    });

                                    var timeout;
                                    container.onmousemove = function(){
                                        if(isFullScreen()){
                                            removeClass("hide_controls",container);
                                            clearTimeout(timeout);
                                            timeout = setTimeout(function(){
                                                addClass("hide_controls",container);
                                            },3000);
                                        }

                                    }
                                }
                            }
                        </script>
                        <style>
                            /*
                        parameters
                        */
                            #huge_it_sigle_video_player_<?php echo $i; ?> {
                                width:<?php echo $width; ?>px;
                            <?php
                            switch($paramssld['video_pl_position']){
                                case "left":
                                    ?>
                                float:left;
                                margin-left:<?php echo $margin_left; ?>px;
                                margin-right:<?php echo $margin_right; ?>px;
                            <?php
                            break;
                        case "right":
                            ?>
                                float:right;
                                margin-left:<?php echo $margin_left; ?>px;
                                margin-right:<?php echo $margin_right; ?>px;
                            <?php
                            break;
                        case "center":
                            ?>
                                margin:0px auto;
                            <?php
                            break;
                    }
                    ?>
                                margin-top:<?php echo $margin_top; ?>px;
                                margin-bottom:<?php echo $margin_bottom; ?>px;
                                border:<?php echo $border_size; ?>px solid #<?php echo $border_color; ?>;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .thumbnail_block {
                                background:#<?php echo esc_html($paramssld['video_pl_background_color']); ?>;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom {
                                background:rgba(<?php echo hugeit_vp_hex2RGB($paramssld['video_pl_controls_panel_color']); ?>,<?php echo $paramssld['video_pl_controls_panel_opacity']/100; ?>);
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_center {
                                color:#<?php echo esc_html($paramssld['video_pl_buttons_color']); ?> !important;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .control,
                            #huge_it_sigle_video_player_<?php echo $i; ?> .thumbnail_block .thumbnail_play {
                                color:#<?php echo esc_html($paramssld['video_pl_buttons_color']); ?> !important;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .control:hover,
                            #huge_it_sigle_video_player_<?php echo $i; ?> .thumbnail_block .thumbnail_play:hover {
                                color:#<?php echo esc_html($paramssld['video_pl_buttons_hover_color']); ?> !important;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_duration_slide {
                                background:transparent;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_duration_slide .duration {
                                background:rgba(<?php echo hugeit_vp_hex2RGB($paramssld['video_pl_timeline_background']); ?>,<?php echo $paramssld['video_pl_timeline_background_opacity']/100; ?>);
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_duration_slide .buffered {
                                background:rgba(<?php echo hugeit_vp_hex2RGB($paramssld['video_pl_timeline_buffering_color']); ?>,<?php echo $paramssld['video_pl_timeline_buffering_opacity']/100; ?>);
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle .volume_before,
                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_duration_slide .played{
                                background:#<?php echo esc_html($paramssld['video_pl_timeline_color']); ?>;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_duration_slide .thumb {
                                background:#<?php echo esc_html($paramssld['video_pl_timeline_slider_color']); ?>;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .timer .current_time {
                                color:#<?php echo esc_html($paramssld['video_pl_curtime_color']); ?>;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .timer .separator,
                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .timer .duration_time {
                                color:#<?php echo esc_html($paramssld['video_pl_durtime_color']); ?>;
                            }

                            /*
                            static
                            */
                            #huge_it_sigle_video_player_<?php echo $i; ?> {
                                position:relative;
                                display:table;
                                height:auto;
                                font-size:15px;
                                font-weight:normal;
                                font-family:Roboto, Arial, Helvetica, sans-serif;
                                font-style:normal;
                                line-height:1;
                                text-indent:0px;
                                user-select:none;
                                -webkit-user-select:none;
                                -moz-user-select:none;
                                -o-user-select:none;
                                -ms-user-select:none;
                                box-sizing: content-box;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.poster {
                                overflow:hidden;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullwidth {
                                width:100%;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen {
                                width:100%;
                                transition:none;
                                height:100%;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen video {
                                width:100%;
                                max-height:100%;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> div[data-title]:hover:after {
                                content: attr(data-title);
                                padding:8px 10px;
                                color: #fff;
                                position: absolute;
                                left:0;
                                bottom: calc(100% + 20px);
                                bottom: -webkit-calc(100% + 20px);
                                bottom: -moz-calc(100% + 20px);
                                bottom: -ms-calc(100% + 20px);
                                bottom: -o-calc(100% + 20px);
                                white-space: nowrap;
                                z-index:6;
                                font-size:12px;
                                background:#444;
                                border-radius:2px;
                                line-height:1;
                                -moz-transition: opacity .1s cubic-bezier(0.0,0.0,0.2,1);
                                -webkit-transition: opacity .1s cubic-bezier(0.0,0.0,0.2,1);
                                transition: opacity .1s cubic-bezier(0.0,0.0,0.2,1);
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_player {
                                position:relative;
                                float:left;
                                display:block;
                                width:100%;
                                height:100%;
                                margin:0px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_player video {
                                position:relative;
                                float:left;
                                display:block;
                                width:100%;
                                height:auto;
                                margin:0px;
                                z-index:5;
                                background:#000;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .thumbnail_block {
                                position:absolute;
                                display:none;
                                left:0px;
                                top:0px;
                                width:100%;
                                height:100%;
                                overflow:hidden;
                                z-index:10;
                                text-align:center;
                                vertical-align:middle;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .thumbnail_block:before {
                                content: "";
                                display: inline-block;
                                vertical-align: middle;
                                height: 100%;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.poster .thumbnail_block {
                                display:block;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .thumbnail_block img {
                                display:block;
                                position:absolute;
                                top:0px;
                                left:0px;
                                min-width:100%;
                                min-height:100%;
                                max-width:100%;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .thumbnail_block .thumbnail_play {
                                position:absolute;
                                display:block;
                                left:50%;
                                top:50%;
                                margin:-30px 0px 0px -30px;
                                width:60px;
                                height:60px;
                                font-size:58px;
                                line-height:60px;
                                text-align:center;
                                cursor:poiner;
                                transition:transform .5s cubic-bezier(0.0,0.0,0.2,1);
                                cursor:pointer;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .thumbnail_block .thumbnail_play:hover {
                                transform:scale(1.05,1.05);
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_top {
                                position: absolute;
                                top: 0px;
                                left: 0px;
                                width: calc(100% - 30px);
                                width: -webkit-calc(100% - 30px);
                                width: -moz-calc(100% - 30px);
                                width: -o-calc(100% - 30px);
                                width: -ms-calc(100% - 30px);
                                padding: 15px;
                                background:rgba(0,0,0,.1);
                                cursor: pointer;
                                overflow: hidden;
                                z-index:11;
                                webkit-transition: opacity .25s cubic-bezier(0.0,0.0,0.2,1);
                                transition: opacity .25s cubic-bezier(0.0,0.0,0.2,1);
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing .huge_it_video_player_top,
                            #huge_it_sigle_video_player_<?php echo $i; ?>.hide_controls.playing .huge_it_video_player_top {
                                opacity:0;
                            }



                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing:not(.hide_controls):hover .huge_it_video_player_top {
                                opacity:1;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_top .video_title {
                                color: #fff;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom {
                                position:absolute;
                                display:block;
                                bottom:0px;
                                left:0px;
                                height:50px;
                                width:100%;
                                z-index:6;
                                webkit-transition: opacity .25s cubic-bezier(0.0,0.0,0.2,1);
                                transition: opacity .25s cubic-bezier(0.0,0.0,0.2,1);
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen .huge_it_video_player_bottom {
                                height:80px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing .huge_it_video_player_bottom {
                                opacity:0;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.hide_controls.playing .huge_it_video_player_bottom {
                                opacity:0;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing:not(.hide_controls):hover .huge_it_video_player_bottom {
                                opacity:1;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_duration_slide {
                                position:absolute;
                                left:0px;
                                bottom:30px;
                                display:block;
                                width:100%;
                                height:20px;
                                margin:0px;
                                line-height:1;
                                cursor:pointer;
                                -moz-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
                                -webkit-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
                                -ms-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
                                transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
                                transform-origin:center center;
                                -webkit-transform-origin:center center;
                                -moz-transform-origin:center center;
                                -o-transform-origin:center center;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide {
                                height:30px;
                                bottom:50px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_duration_slide .duration {
                                position:absolute;
                                display:block;
                                bottom:7.5px;
                                left:0px;
                                width:100%;
                                height:5px;
                                z-index:6;
                                -moz-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
                                -webkit-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
                                -ms-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
                                transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
                                transform-origin:center center;
                                -webkit-transform-origin:center center;
                                -moz-transform-origin:center center;
                                -o-transform-origin:center center;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide .duration {
                                bottom:11px;
                                height:8px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_duration_slide .played {
                                position:absolute;
                                display:block;
                                bottom:7.5px;
                                left:0px;
                                width:0px;
                                height:5px;
                                z-index:8;
                                -moz-transition:height .1s cubic-bezier(0.0,0.0,0.2,1);
                                -webkit-transition:height .1s cubic-bezier(0.0,0.0,0.2,1);
                                -ms-transition:height .1s cubic-bezier(0.0,0.0,0.2,1);
                                transition:height .1s cubic-bezier(0.0,0.0,0.2,1);
                                transform-origin:center center;
                                -webkit-transform-origin:center center;
                                -moz-transform-origin:center center;
                                -o-transform-origin:center center;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide .played {
                                bottom:11px;
                                height:8px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_duration_slide .thumb {
                                position:absolute;
                                display:block;
                                bottom:3.5px;
                                left:0px;
                                width:13px;
                                height:13px;
                                margin-left:-6.5px;
                                background:#f12b24;
                                border-radius:6.5px;
                                z-index:9;
                                cursor:pointer;
                                -moz-transition:transform .1s cubic-bezier(0.0,0.0,0.2,1);
                                -webkit-transition:transform .1s cubic-bezier(0.0,0.0,0.2,1);
                                -ms-transition:transform .1s cubic-bezier(0.0,0.0,0.2,1);
                                transition:transform .1s cubic-bezier(0.0,0.0,0.2,1);
                                transform-origin:center center;
                                -webkit-transform-origin:center center;
                                -moz-transform-origin:center center;
                                -o-transform-origin:center center;

                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_duration_slide .thumb {
                                background:#<?php echo esc_html($paramssld['video_pl_timeline_slider_color']); ?>;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide .thumb {
                                bottom:5px;
                                width:20px;
                                height:20px;
                                border-radius:10px;
                                margin-left:-10px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide .thumb {
                                transform:scale(0,0);
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide:hover .thumb {
                                transform:scale(1,1);
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_duration_slide .buffered {
                                position:absolute;
                                display:block;
                                bottom:7.5px;
                                left:0px;
                                width:0px;
                                height:5px;
                                z-index:7;
                                -moz-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
                                -webkit-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
                                -ms-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
                                transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
                                transform-origin:center center;
                                -webkit-transform-origin:center center;
                                -moz-transform-origin:center center;
                                -o-transform-origin:center center;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide .buffered {
                                bottom:11px;
                                height:8px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide .duration,
                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide .played,
                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide .buffered {
                                bottom:8.5px;
                                height:3px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide .duration,
                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide .played,
                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide .buffered {
                                bottom:12.5px;
                                height:5px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide:hover .duration,
                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide:hover .played,
                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide:hover .buffered {
                                bottom:7.5px;
                                height:5px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide:hover .duration,
                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide:hover .played,
                            #huge_it_sigle_video_player_<?php echo $i; ?>.playing.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide:hover .buffered {
                                bottom:11px;
                                height:8px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_duration_slide .hover_timer {
                                position: absolute;
                                display:none;
                                left:0;
                                bottom:100%;
                                padding: 5px 9px;
                                max-width: 200px;
                                background:rgba(28,28,28,0.8);
                                border-radius:2px;
                                white-space: nowrap;
                                word-wrap: normal;
                                -o-text-overflow: ellipsis;
                                text-overflow: ellipsis;
                                font-size:11px;
                                line-height:1;
                                color: #fff;
                                z-index:6;
                                -moz-transform: none;
                                -ms-transform: none;
                                -webkit-transform: none;
                                transform: none;
                                -moz-transition:opacity .1s cubic-bezier(0.4,0.0,1,1);
                                -webkit-transition:opacity .1s cubic-bezier(0.4,0.0,1,1);
                                -ms-transition:opacity .1s cubic-bezier(0.4,0.0,1,1);
                                transition:opacity .1s cubic-bezier(0.4,0.0,1,1);
                                -webkit-animation-duration:.1s;
                                -webkit-animation-iteration-count:1;
                                -webkit-animation-timing-function: cubic-bezier(0.4,0.0,1,1);
                                opacity:0;
                            }

                            @-webkit-keyframes opacity {
                                0% {
                                    opacity:0;
                                }
                                100% {
                                    opacity:1;
                                }
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls {
                                position:absolute;
                                left:0px;
                                bottom:0px;
                                display:block;
                                width:100%;
                                height:30px;
                                margin:0px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.small .huge_it_video_player_bottom .huge_it_video_player_controls {
                                text-align:center;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen .huge_it_video_player_bottom .huge_it_video_player_controls {
                                height:50px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .control {
                                position:relative;
                                display:inline-block;
                                width:30px;
                                height:30px;
                                margin:0px 0px 0px 5px;
                                font-size:16px;
                                line-height:30px;
                                text-align:center;
                                vertical-align:top;
                                cursor:pointer;
                            }
                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .control i{
                                line-height: inherit;
                            }
                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen .huge_it_video_player_bottom .huge_it_video_player_controls .control {
                                width:50px;
                                height:50px;
                                margin:0px 0px 0px 5px;
                                font-size:30px;
                                line-height:50px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .fast_back {

                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.small .huge_it_video_player_bottom .huge_it_video_player_controls .fast_back {
                                display:none;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .play_pause {

                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.small .huge_it_video_player_bottom .huge_it_video_player_controls .play_pause {
                                float:left;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .fast_forward {

                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.small .huge_it_video_player_bottom .huge_it_video_player_controls .fast_forward {
                                display:none;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .mute_button {

                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.small .huge_it_video_player_bottom .huge_it_video_player_controls .mute_button {
                                float:left;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.very_small .huge_it_video_player_bottom .huge_it_video_player_controls .mute_button {
                                display:none;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle {
                                position:relative;
                                display:inline-block;
                                width:50px;
                                height:30px;
                                background: none;
                                cursor: pointer;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.small .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle {
                                float:left;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.very_small .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle {
                                display:none;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle {
                                position:relative;
                                display:inline-block;
                                width:100px;
                                height:50px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle .volume_before {
                                position: absolute;
                                top:13.5px;
                                left:0px;
                                height:3px;
                                width:50px;
                                z-index:7;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle .volume_before {
                                top:22.5px;
                                height:5px;
                                width:100px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle .volume_current {
                                position: absolute;
                                top:8.5px;
                                left:50px;
                                height:13px;
                                width: 4px;
                                margin-left:-2px;
                                background: #fff;
                                z-index:8;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle .volume_current {
                                top:15px;
                                height:20px;
                                width:6px;
                                margin-left:-3px;
                                left:100px;
                                width: 4px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle .volume_after {
                                position: absolute;
                                top:13.5px;
                                left:0px;
                                height:3px;
                                width:50px;
                                background:#fff;
                                z-index:6;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle .volume_after {
                                top:22.5px;
                                height:5px;
                                width:100px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .timer {
                                position:relative;
                                display:inline-block;
                                min-width:50px;
                                height:30px;
                                margin:0px 0px 0px 5px;
                                vertical-align:top;
                                font-size:11px;
                                line-height:30px;
                                font-style:normal;
                                font-weight:normal;
                                text-align:center;
                                cursor:pointer;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.fullscreen .huge_it_video_player_bottom .huge_it_video_player_controls .timer {
                                height:50px;
                                font-size:14px;
                                line-height:50px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .timer .current_time {
                                display:inline-block;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .timer .separator {
                                display:inline-block;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .timer .duration_time {
                                display:inline-block;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_bottom .huge_it_video_player_controls .full_screen {
                                float:right;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?>.small .huge_it_video_player_bottom .huge_it_video_player_controls .full_screen {
                                float:initial;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_center {
                                text-align:center;
                                font-size:15px;
                                line-height:40px;
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_center div {
                                position:absolute;
                                display:none;
                                left:50%;
                                top:50%;
                                margin-top:-20px;
                                height:40px;
                                width:40px;
                                margin-left:-20px;
                                background: rgba(0,0,0,.2);
                                border-radius: 100%;
                                text-align:center;
                                transition:all .5s linear;
                                -webkit-animation-duration:.5s;
                                -webkit-animation-iteration-count:1;
                                -webkit-animation-timing-function: linear;
                                z-index:6;
                                opacity:0;
                            }
                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_center i {
                                line-height: 40px;
                            }
                            #huge_it_sigle_video_player_<?php echo $i; ?> .huge_it_video_player_center .center_wait {
                                background:transparent;
                            }



                            @-webkit-keyframes popup {
                                0% {
                                    opacity:1;
                                    transform:scale(1,1);
                                }
                                100% {
                                    opacity:0;
                                    transform:scale(3,3);
                                }
                            }

                            #huge_it_sigle_video_player_<?php echo $i; ?> * {
                                box-sizing:content-box !important;
                                text-indent: 0px;
                            }
                        </style>
                        <div id="huge_it_sigle_video_player_<?php echo $i; ?>">
                            <div class="huge_it_video_player_player">
                                <video src="<?php echo esc_url($video->video_url_1);  ?>" data-current="--" data-duration="--">
                                    Your browser does not support HTML5 video.
                                </video>
                            </div>
                            <div class="thumbnail_block">
                                <?php
                                if(!empty($video->image_url)){
                                    ?>
                                    <img src="<?php echo esc_url($video->image_url); ?>" alt="poster" />
                                    <?php
                                }
                                ?>
                                <div class="thumbnail_play"><i class="hugeicons hugeicons-play-circle-o"></i></div>
                            </div>
                            <?php if ($video->name != '') { ?>
                                <div class="huge_it_video_player_top">
                                    <div class="video_title"><?php echo esc_html($video->name); ?></div>
                                </div>
                            <?php }?>
                            <div class="huge_it_video_player_bottom">
                                <div class="huge_it_video_player_duration_slide">
                                    <div class="duration"></div>
                                    <div class="played"></div>
                                    <div class="thumb"></div>
                                    <div class="buffered"></div>
                                    <div class="hover_timer">
                                        <span class="hover_timer_time">00:00</span>
                                    </div>
                                </div>
                                <div class="huge_it_video_player_controls">
                                    <div class="fast_back control" data-title="Fast backward"><i class="hugeicons hugeicons-step-backward"></i></div>
                                    <div class="play_pause control" data-title="Play"><i class="hugeicons hugeicons-play"></i></div>
                                    <div class="fast_forward control" data-title="Fast forward"><i class="hugeicons hugeicons-step-forward"></i></div>
                                    <div class="mute_button control" data-title="Mute"><i class="hugeicons hugeicons-volume-up"></i></div>
                                    <div class="volume_handle">
                                        <div class="volume_before"></div>
                                        <div class="volume_current"></div>
                                        <div class="volume_after"></div>
                                    </div>
                                    <div class="timer">
                                        <div class="current_time">--</div>
                                        <div class="separator"> / </div>
                                        <div class="duration_time">--</div>
                                    </div>
                                    <div class="full_screen control" data-title="Full Screen"><i class="hugeicons hugeicons-expand"></i></div>
                                </div>
                            </div>
                            <div class="huge_it_video_player_center">
                                <div class="center_play"><i class="hugeicons hugeicons-play"></i></div>
                                <div class="center_pause"><i class="hugeicons hugeicons-pause"></i></div>
                                <div class="center_wait"><i class="hugeicons hugeicons-spinner hugeicons-pulse"></i></div>
                            </div>
                        </div>
                        <?php
                        break;
                        case 'youtube':
                        $i=rand(1,100000);
							?>
							<style>
							#youtube_single_player_container_<?php echo $video_id; ?> {
								display:block;
								position:relative;
								<?php
								switch($paramssld['video_pl_position']){
									case "left":
										?>
										float:left;
										margin-left:<?php echo $margin_left; ?>px;
										margin-right:<?php echo $margin_right; ?>px;
										<?php
										break;
									case "right":
										?>
										float:right;
										margin-left:<?php echo $margin_left; ?>px;
										margin-right:<?php echo $margin_right; ?>px;
										<?php
										break;
									case "center":
										?>
										display:block;
										margin:0px auto;
										<?php
										break;
								}
								?> 
								margin-top:<?php echo $margin_top; ?>px;
								margin-bottom:<?php echo $margin_bottom ?>px;
								max-width:none;
								width:<?php echo $width; ?>px;
								height:<?php echo floor($video_player[0]->width*0.56); ?>px;
								box-sizing:content-box;
								border:<?php echo $border_size; ?>px solid #<?php echo $border_color; ?>;
							}
							
							#youtube_single_player_<?php echo $video_id; ?> {
								position:absolute;
								top:0px;
								left:0px;
								width:100%;
								height:100%;
								z-index:5;
							}
							
							#youtube_single_player_container_<?php echo $video_id; ?> .thumbnail_block {
								position:absolute;
								display:none;
								top:0px;
								left:0px;
								width:100%;
								height:100%;
								overflow:hidden;
								z-index:6;
							}
							
							#youtube_single_player_container_<?php echo $video_id; ?> .thumbnail_block .thumb {
								position:absolute;
								left:0px;
								top:0px;
								min-width:100%;
								min-height:100%;
								max-width:none;
								vertical-align:middle
							}
							
							#youtube_single_player_container_<?php echo $video_id; ?> .thumbnail_block .play {
								position:absolute;
								left:50%;
								top:50%;
								width:70px;
								height:49px;
								margin-left:-35px;
								margin-top:-24.5px;
								cursor:pointer;
							}
							</style>
							<div id="youtube_single_player_container_<?php echo $video_id; ?>">
								<div id="youtube_single_player_<?php echo $video_id; ?>"></div>
								<div class="thumbnail_block">
									<img class="thumb" src="<?php echo esc_url($video->image_url); ?>" alt="<?php echo esc_html($video->name); ?>" />
									<img class="play" src="<?php echo plugins_url("../images/play.youtube.png",__FILE__); ?>" alt="YouTube play" />
								</div>
							</div>
							
							<?php
							break;
						case "vimeo":
							$i=rand(1,100000);
							$vid = esc_url($video->video_url_1);
							$vid = explode("/",$vid);
							$vidid=  end($vid);
							if($j==0){
								$autoplay = absint($video_player[0]->autoplay);
							}else{
								$autoplay=0;
							}
							$vidurl="https://player.vimeo.com/video/".$vidid."?player_id=vimeo_single_player_".$video_id."&color=".$paramssld['video_pl_vimeo_color']."&autoplay=".$autoplay;
							?>
							<script>
								jQuery(document).ready(function(){
									function vimhw<?php echo $i; ?>(){
										var w=<?php echo $width; ?>;
										if(jQuery("#vimeo_single_player_container_<?php echo $video_id; ?>").parent().width()<=w){
											document.getElementById("vimeo_single_player_container_<?php echo $video_id; ?>").style.width="100%";
											var w=document.getElementById("vimeo_single_player_container_<?php echo $video_id; ?>").offsetWidth;
											document.getElementById("vimeo_single_player_container_<?php echo $video_id; ?>").style.height=w*0.56+"px";
										}else{
											document.getElementById("vimeo_single_player_container_<?php echo $video_id; ?>").style.width="<?php echo $width; ?>px";
											document.getElementById("vimeo_single_player_container_<?php echo $video_id; ?>").style.height="<?php echo $width*0.56; ?>px";
										}
									}
									
									if((<?php echo $j; ?>==0 && <?php echo absint($video_player[0]->autoplay); ?>==0) || <?php echo $j; ?>!=0){
										jQuery("#vimeo_single_player_container_<?php echo $video_id; ?> .thumbnail_block").css({display:'block'});
									}
									
									jQuery("#vimeo_single_player_container_<?php echo $video_id; ?> .thumbnail_block").on("click",function(){
										jQuery("#vimeo_single_player_container_<?php echo $video_id; ?> .thumbnail_block").css({display:'none'});
										jQuery("#vimeo_single_player_container_<?php echo $video_id; ?> #vimeo_single_player_<?php echo $video_id; ?>").attr("src","https://player.vimeo.com/video/<?php echo $vidid; ?>?player_id=vimeo_single_player_<?php echo $video_id; ?>&color=<?php echo $paramssld['video_pl_vimeo_color']; ?>&autoplay=1");
									});
									
									vimhw<?php echo $i; ?>();
									jQuery(window).on("resize",function(){
										vimhw<?php echo $i; ?>();
									});
								});
							</script>
							<style>
								
								
								#vimeo_single_player_container_<?php echo $video_id; ?> {
									display:block;
									position:relative;
									<?php
									switch($paramssld['video_pl_position']){
										case "left":
											?>
											float:left;
											margin-left:<?php echo $margin_left; ?>px;
											margin-right:<?php echo $margin_right; ?>px;
											<?php
											break;
										case "right":
											?>
											float:right;
											margin-left:<?php echo $margin_left; ?>px;
											margin-right:<?php echo $margin_right; ?>px;
											<?php
											break;
										case "center":
											?>
											display:block;
											margin:0px auto;
											<?php
											break;
									}
									?> 
									margin-top:<?php echo $margin_top; ?>px;
									margin-bottom:<?php echo $margin_bottom; ?>px;
									width:<?php echo $width; ?>px;
									max-width:none;
									height:<?php $ff=round($video_player[0]->width*0.56); echo $ff; ?>px;
									box-sizing:content-box;
									border:<?php echo $border_size; ?>px solid #<?php echo $border_color; ?>;
								}
								
								#vimeo_single_player_<?php echo $video_id; ?> {
									position:absolute;
									top:0px;
									left:0px;
									width:100%;
									height:100%;
									z-index:5;
								}
								
								#vimeo_single_player_container_<?php echo $video_id; ?> .thumbnail_block {
									position:absolute;
									display:none;
									top:0px;
									left:0px;
									width:100%;
									overflow:hidden;
									height:100%;
									z-index:6;
								}
								
								#vimeo_single_player_container_<?php echo $video_id; ?> .thumbnail_block .thumb {
									position:absolute;
									left:0px;
									top:0px;
									width:100%;
									max-height:none;
									vertical-align:middle
								}
								
								#vimeo_single_player_container_<?php echo $video_id; ?> .thumbnail_block .play {
									position:absolute;
									left:50%;
									top:50%;
									width:70px;
									height:49px;
									margin-left:-35px;
									margin-top:-24.5px;
									cursor:pointer;
								}
								
							</style>
							<div id="vimeo_single_player_container_<?php echo $video_id; ?>">
								<iframe id="vimeo_single_player_<?php echo $video_id; ?>" width="<?php echo $width; ?>" height="<?php echo $width*0.56; ?>" src="<?php echo esc_url($vidurl); ?>"  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
								<div class="thumbnail_block">
									<img class="thumb" src="<?php echo esc_url($video->image_url); ?>" alt="<?php echo esc_html($video->name); ?>" />
									<img class="play" src="<?php echo plugins_url("../images/play.vimeo.png",__FILE__); ?>" alt="vimeo play" />
								</div>
							</div>
							
							<?php
							break;
					}
					$j++;
			} ?>
			<?php

			break;
/////////////////////////////////////////////////////album//////////////////////////////////////////////////////////////
		case 'album':
			$i=rand(1,1000);
			?>
			<script>
			var YTdeferred =jQuery.Deferred();
			window.onYouTubeIframeAPIReady = function() {
				// resolve when youtube callback is called
				// passing YT as a parameter
				YTdeferred.resolve(window.YT);
			};

		/*init Album video player when document is ready*/
		jQuery(document).ready(function(){
			var tag = document.createElement('script');

			tag.src = "https://www.youtube.com/iframe_api";
			var firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
			
			
			var huge_it_album_player_interval_<?php echo $i; ?> = setInterval(function(){
				if(jQuery("#huge_it_album_video_player_<?php echo $i; ?>").is(":visible")){
					init_huge_it_playlist_vide_player_<?php echo $i; ?>("#huge_it_album_video_player_<?php echo $i; ?>");
					clearInterval(huge_it_album_player_interval_<?php echo $i; ?>);
				}
			},100);
	
	// Listen for the ready event for any vimeo video players on the page
	function init_huge_it_playlist_vide_player_<?php echo $i; ?>(video_container_id){
		var jQ_items,playlist_autoplay,player_width,video_width,video_param_width,global_container,container,load_icon,youtube,jQ_youtube,vimeo,jQ_vimeo,playlist_container,items;

		/* PLUGIN PARAMETERS */
		
		playlist_autoplay="false";
		player_width=<?php echo $width; ?>;
		<?php 
		if($video_player[0]->layout=="bottom"){
			?>
			video_width=<?php echo $width; ?>;
			video_param_width=<?php echo $width; ?>
			<?php
		}else{
			?>
			video_width=<?php echo $width*(3/5); ?>;
			video_param_width=<?php echo $width*(3/5); ?>;
			<?php
		}
		?>
		
		video_param_aprox_height=video_param_width*0.56;
		/* check if video player exists */
		global_container=document.querySelector(video_container_id);
		if(global_container){
			
			/* SET OBJECT LISTENERS */
			/* *** */
			/* custom video container */
			container=global_container.querySelector(".huge_it_player");
			load_icon=global_container.querySelector(".load_icon");
			/* youtube player container */
			youtube=global_container.querySelector("#youtube_player_<?php echo $i; ?>");
			jQ_youtube=jQuery("#youtube_player_<?php echo $i; ?>");
			/* vimeo player container */
			vimeo_js=global_container.querySelector("#vimeo_<?php echo $i; ?>");
			jQ_vimeo=jQuery("#vimeo_<?php echo $i; ?>");
			/* playlist container */
			playlist_container=global_container.querySelector(".playlist_wrapper");
			/* playlist items */
			items=playlist_container.querySelectorAll("li");
			jQ_items=jQuery("#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper li");
			if(items.length){
				
				
				/* create youtube player */
				var youtube_player;
				YTdeferred.done(function(YT) {
					setTimeout(function() {
						load_icon.style.display = "none";
						removeClass("loading", global_container);
						init_playlist_active_item();
						<?php if($videoAautoPlay == 1) : ?>
                            if (jQuery('#huge_it_album_video_player_<?php echo $i; ?> .thumbnail_play').parent().css('display') != 'none'){
                                jQuery('#huge_it_album_video_player_<?php echo $i; ?> .thumbnail_play').click();
                            }
                            else{
                                jQuery('#huge_it_album_video_player_<?php echo $i; ?> img.thumb').click();
                            }
						<?php
                        else : ?>
                        if(typeof youtube_player.stopVideo === 'function') {
                            youtube_player.stopVideo();
                        }
                            playlist_autoplay="true";
                        <?php
                        endif; ?>
					},2000);
					
					youtube_player = new YT.Player('youtube_player_<?php echo $i; ?>', {
						autoplay         : 0,
						enablejsapi      : 1,
						playerVars:{
							'autohide':			<?php echo absint($paramssld['video_pl_yt_autohide']); ?>,
							'autoplay':			0,
							'controls': 		1,
							'fs':				<?php echo absint($paramssld['video_pl_yt_fullscreen']); ?>,
							'disablekb':		0,
							'modestbranding':	1,
							'enablejsapi': 1,
							// 'cc_load_policy': 1, // forces closed captions on
							'iv_load_policy':	<?php echo absint($paramssld['video_pl_yt_annotation']); ?>, // annotations, 1=on, 3=off
							// 'playlist': videoID, videoID, videoID, etc,
                            'rel':				<?php echo absint($paramssld['video_pl_yt_related']); ?>,
							'showinfo':			<?php echo absint($paramssld['video_pl_yt_showinfo']); ?>,
							'theme':			'<?php echo esc_html($paramssld['video_pl_yt_theme']); ?>',	// dark, light
							'color':			'<?php echo esc_html($paramssld['video_pl_yt_color']); ?>'	// red, white
						},
						events           : {
							'onReady'    : onReady,
						}
					});
					function onReady() {
                        if(typeof youtube_player.pauseVideo === 'function') {
                            youtube_player.pauseVideo();
                        }
						youtube_player.addEventListener('onStateChange', function(e) {
							if(e.data==0){
								/* video ended. load next track in playlist */
								next_track();
							}
							if(e.data==1){
								playlist_autoplay="true";
							}
						});
					}
					
					var vimeo;
					
					var vimeo_player=jQuery('#vimeo_<?php echo $i; ?>')[0];
					vimeo = Froogaloop(vimeo_player);
					
					vimeo.addEvent('ready', function() {
						vimeo.addEvent('finish', function() {
							next_track();
						});
					});
					
					/* helping functions */
					/* *** */
					function addClass( classname, element ) {
						var cn = element.className;
						/*test for existance */
						if( cn.indexOf( classname ) != -1 ) {
							return;
						}
						/* add a space if the element already has class */
						if( cn != '' ) {
							classname = ' '+classname;
						}
						element.className = cn+classname;
					}

					function removeClass( classname, element ) {
						var cn = element.className;
						var rxp = new RegExp( "\\s?\\b"+classname+"\\b", "g" );
						cn = cn.replace( rxp, '' );
						element.className = cn;
					}
					
					function hasClass(cls, element) {
						return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
					}
				
					function init_playlist_custom_video(src){
						var video,thumb_box,thumb,paused,dragging,volume_dragging,volume,progress_duration_bar,progress_bar,progress_played_bar,progress_thumb,progress_buffered,current_time,duration_time,current_time_text,duration_time_text,autoplay,play_btn,center_play_icon,center_pause_icon,center_wait_icon,backward_btn,forward_btn,mute_btn,volume_handle,volume_before,volume_current,volume_after,fullscreen_btn,hover_timer_box,hover_timer;
						current_time="00";
						duration_time="00";
						volume=1;
						dragging="";
						paused="";
						volume_dragging="";
						/* PLUGIN PARAMETERS */
						
						autoplay=playlist_autoplay;
						
						/* check if video player exists */
						if(container && src!=""){
							/* SET OBJECT LISTENERS */
							/* *** */
							video=container.querySelector("video");
							play_btn=container.querySelector(".play_pause");
							center_play_icon=container.querySelector(".center_play");
							center_pause_icon=container.querySelector(".center_pause");
							center_wait_icon=container.querySelector(".center_wait");
							backward_btn=container.querySelector(".fast_back");
							forward_btn=container.querySelector(".fast_forward");
							mute_btn=container.querySelector(".mute_button");
							volume_handle=container.querySelector(".volume_handle");
							volume_before=container.querySelector(".volume_before");
							volume_current=container.querySelector(".volume_current");
							volume_after=container.querySelector(".volume_after");
							fullscreen_btn=container.querySelector(".full_screen");
							current_time_text=container.querySelector(".current_time");
							duration_time_text=container.querySelector(".duration_time");
							progress_bar=container.querySelector(".huge_it_video_player_duration_slide");
							progress_played_bar=container.querySelector(".played");
							progress_thumb=container.querySelector(".thumb");
							progress_buffered=container.querySelector(".buffered");
							hover_timer_box=container.querySelector(".hover_timer");
							hover_timer=container.querySelector(".hover_timer_time");
							thumb_box=container.querySelector(".thumbnail_block");
							thumb=container.querySelector(".thumbnail_block img");
							/* *** */
							/* FUNCTIONS */
							
							function timeupdate(){
								if(!isNaN(video.currentTime)){
									current_time=Math.floor(video.currentTime);
								}
								if(!isNaN(video.duration)){
									duration_time=Math.floor(video.duration);
								}
								var curmins = Math.floor(video.currentTime / 60);
								var cursecs = Math.floor(video.currentTime - curmins * 60);
								var durmins = Math.floor(video.duration / 60);
								var dursecs = Math.floor(video.duration - durmins * 60);
								if(cursecs < 10){ cursecs = "0"+cursecs; }
								if(dursecs < 10){ dursecs = "0"+dursecs; }
								if(curmins < 10){ curmins = "0"+curmins; }
								if(durmins < 10){ durmins = "0"+durmins; }
								current_time_text.innerHTML = curmins+":"+cursecs;
								duration_time_text.innerHTML = durmins+":"+dursecs;
								
								var current_time_percent = current_time*(100/duration_time);
								var progress_bar_pixels=(current_time_percent*video_width)/100;
								progress_played_bar.style.width=progress_bar_pixels+"px";
								if(progress_bar_pixels+progress_thumb.offsetWidth/2 > video_width){
									var last_px = video_width-progress_thumb.offsetWidth/2;
									progress_thumb.style.left=last_px+"px";
									
								}else if(progress_bar_pixels < progress_thumb.offsetWidth/2){
									var first_px = progress_thumb.offsetWidth/2;
									progress_thumb.style.left=first_px+"px";
								}else{
									progress_thumb.style.left=progress_bar_pixels+"px";
								}
								
							}
							
							function progressHandler(){
								if(!isNaN(video.currentTime)){
									current_time=Math.floor(video.currentTime);
								}
								if(!isNaN(video.duration)){
									duration_time=Math.floor(video.duration);
								}
								if(!video.paused){
									play_btn.innerHTML='<i class="hugeicons hugeicons-pause"></i>';
								}
								if(thumb_box.style.display=="block" && !video.paused){
									thumb_box.style.display="none";
								}
								if(video.buffered.length > 0){
									var i=video.buffered.length;
									var buffered_end=video.buffered.end(i-1);
									var loaded_percent=(buffered_end/duration_time)*100;
									var progress_bar_pixels=(loaded_percent*video_width)/100;
									progress_buffered.style.width=progress_bar_pixels+"px";
								}
								if(!isFullScreen() && hasClass("hide_controls",container)){
									removeClass("hide_controls",container);
								}
							}
							
							function VideoClickPlayPause(){
								if(video.paused){
									/* PLAY THE VIDEO */
									video.play();
									paused="";
									addClass("playing",container);
									play_btn.innerHTML='<i class="hugeicons hugeicons-pause"></i>';
									center_play_icon.style.webkitAnimationName="popup";
									center_play_icon.style.display="block";
									setTimeout(function(){
										center_play_icon.removeAttribute("style");
									},500);
									playlist_autoplay="true";
								}else{
									/* PAUSE THE VIDEO */
									video.pause();
									paused=1;
									removeClass("playing",container);
									play_btn.innerHTML='<i class="hugeicons hugeicons-play"></i>';
									center_pause_icon.style.webkitAnimationName="popup";
									center_pause_icon.style.display="block";
									setTimeout(function(){
										center_pause_icon.removeAttribute("style");
									},500);
								}
							}
							
							function PlayPause(){
								if(hasClass("poster",container)){
									video.play();
									paused="";
									thumb_box.style.display="none";
									removeClass("poster",container);
									addClass("playing",container);
									play_btn.innerHTML='<i class="hugeicons hugeicons-pause"></i>';
									playlist_autoplay="true";
								}else{
									if(video.paused){
										/* PLAY THE VIDEO */
										video.play();
										paused="";
										addClass("playing",container);
										play_btn.innerHTML='<i class="hugeicons hugeicons-pause"></i>';
									}else{
										/* PAUSE THE VIDEO */
										video.pause();
										paused=1;
										removeClass("playing",container);
										play_btn.innerHTML='<i class="hugeicons hugeicons-play"></i>';
									}
								}
								
							}
							
							function step_back(){
								if(!isNaN(video.currentTime)){
									current_time=Math.floor(video.currentTime);
								}
								if(!isNaN(video.duration)){
									duration_time=Math.floor(video.duration);
								}
								video.currentTime=current_time-15;
							}
							
							function step_forward(){
								if(!isNaN(video.currentTime)){
									current_time=Math.floor(video.currentTime);
								}
								if(!isNaN(video.duration)){
									duration_time=Math.floor(video.duration);
								}
								video.currentTime=current_time+15;
							}
							
							function toggle_mute(){
								if(video.muted){
									video.muted=false;
									if(volume<0.7){
										mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-down"></i>';
									}else{
										mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-up"></i>';
									}
									
									var volume_handle_width=volume_handle.offsetWidth;
									var volume_current_pixels=volume*volume_handle_width;
									volume_before.style.width=volume_current_pixels+"px";
									volume_current.style.left=volume_current_pixels+"px";
								}else{
									var volume_handle_width=volume_handle.offsetWidth;
									var volume_current_width=volume_before.offsetWidth;
									volume=volume_current_width/volume_handle_width;
									video.muted=true;
									mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-off"></i>';
									volume_before.style.width="0px";
									volume_current.style.left="0px";
								}
							}
							
							function exitHandler(){
								if (isFullScreen())
									console.log("");
								   // nothing
								else
									 cFullScreen();
							}
							
							function toggle_full_screen(){
								if (isFullScreen())
									cFullScreen();
								else
									requestFullScreen(container || document.documentElement);
							}
							
							function isFullScreen(){
								return (document.fullScreenElement && document.fullScreenElement !== null)
									 || document.mozFullScreen
									 || document.webkitIsFullScreen;
							}
							
							function vidSeeking(e){
								var x = e.clientX;
								var l=container.getBoundingClientRect().left;
								var pos = x-l;
								if(pos>=0 && pos<video_width){
									progress_played_bar.style.width = pos+"px";
									progress_thumb.style.left = pos+"px";
									var current_percent=(pos/video_width)*100;
									var seekto=duration_time*(current_percent/100);
									video.currentTime=seekto;
								}
							}
							
							function requestFullScreen(element){
								if (element.requestFullscreen)
									element.requestFullscreen();
								else if (element.msRequestFullscreen)
									element.msRequestFullscreen();
								else if (element.mozRequestFullScreen)
									element.mozRequestFullScreen();
								else if (element.webkitRequestFullscreen)
									element.webkitRequestFullscreen();
								addClass("fullscreen",container);
								
								fullscreen_btn.innerHTML='<i class="hugeicons hugeicons-compress"></i>';
								jQuery.when(requestFullScreen).done(function(){
									setTimeout(function(){
										video_width=video.offsetWidth;
										
									},500);
									
								});
								
							}		
							
							function cFullScreen(){
								if (document.exitFullscreen)
									document.exitFullscreen();
								else if (document.msExitFullscreen)
									document.msExitFullscreen();
								else if (document.mozCancelFullScreen)
									document.mozCancelFullScreen();
								else if (document.webkitExitFullscreen)
									document.webkitExitFullscreen();
								removeClass("fullscreen",container);
								fullscreen_btn.innerHTML='<i class="hugeicons hugeicons-expand"></i>';
								jQuery.when(cFullScreen).done(function(){
									setTimeout(function(){
										video_width=video.offsetWidth;
									},500);
								});
							}

							function setVolume(e){
								var x = e.clientX;
								var l=volume_handle.getBoundingClientRect().left;
								var pos = x-l;
								if(pos>0 && pos<=volume_handle.offsetWidth){
									volume_before.style.width=pos+"px";
									volume_current.style.left=pos+"px";
									var volume_handle_width=volume_handle.offsetWidth;
									var current_percent=(pos/volume_handle_width)*100;
									volume=current_percent/100;
									video.muted=false;
									video.volume=current_percent/100;
									if(current_percent<70){
										if(current_percent==0){
											mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-off"></i>';
										}else{
											mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-down"></i>';
										}
									}else{
										mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-up"></i>';
									}
								}else{
									if(pos<=0){
										video.muted=true;
										mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-off"></i>';
										volume_before.style.width="0px";
										volume_current.style.left="0px";
									}
								}
							}
							
							function waiting(){
								center_wait_icon.style.display="block";
								center_wait_icon.style.opacity="1";
							}
							
							function notWaiting(){
								center_wait_icon.style.display="none";
								center_wait_icon.style.opacity="0";
							}
							
							function videoEnd(){
								play_btn.innerHTML ='<i class="hugeicons hugeicons-refresh"></i>';
								if (isFullScreen()) cFullScreen();
								next_track();
							}
							
							function timerDisplay(e){
								if(video.buffered.length > 0){
									var x = e.clientX;
									var l=container.getBoundingClientRect().left;
									var w=hover_timer_box.offsetWidth;
									var pos = x-l;
									
									var mouse_pos_percent= (pos/video_width)*100;
									var mouse_pos_time=duration_time*(mouse_pos_percent/100);
									var curmins = Math.floor(mouse_pos_time / 60);
									var cursecs = Math.floor(mouse_pos_time - curmins * 60);
									if(cursecs < 10){ cursecs="0"+cursecs; }
									if(curmins < 10){ curmins="0"+curmins; }
									//hover_timer_box.style.webkitAnimationName="opacity";
									hover_timer_box.style.opacity="1";
									hover_timer_box.style.display = "block";
									hover_timer_box.style.left = x-l-w/2+"px";
									hover_timer.innerHTML = curmins+":"+cursecs;
								}
							}
							
							function timerNotDisplay(e){
								hover_timer_box.style.display = "none";
							}
							
							function keyFunctions(e){
								switch(e.keyCode){
									case 40:
										var curent_volume=video.volume;
										var curent_volume_percent=curent_volume*100;
										if(curent_volume_percent>0){
											var new_volume_percent=curent_volume_percent-10;
											if(new_volume_percent<=0){
												mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-off"></i>';
												volume_before.style.width="0px";
												volume_current.style.left="0px";
												video.muted=true;
												volume=0;
											}else{
												if(new_volume_percent<70){
													mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-down"></i>';
												}else{
													mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-up"></i>';
												}
												var new_volume=new_volume_percent/100;
												var volume_handle_width=volume_handle.offsetWidth;
												var new_position=new_volume*volume_handle_width;
												volume_before.style.width=new_position+"px";
												volume_current.style.left=new_position+"px";
												video.muted=false;
												video.volume=new_volume;
												volume=new_volume;
											}
											
										}
										e.preventDefault();
										break;
									case 38:
										var curent_volume=video.volume;
										var curent_volume_percent=curent_volume*100;
										if(curent_volume_percent<100){
											var new_volume_percent=curent_volume_percent+10;
											if(new_volume_percent>100){
												new_volume_percent=100;
											}
											if(new_volume_percent<70){
												mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-down"></i>';
											}else{
												mute_btn.innerHTML='<i class="hugeicons hugeicons-volume-up"></i>';
											}
											var new_volume=new_volume_percent/100;
											var volume_handle_width=volume_handle.offsetWidth;
											var new_position=new_volume*volume_handle_width;
											volume_before.style.width=new_position+"px";
											volume_current.style.left=new_position+"px";
											video.volume=new_volume;
											video.muted=false;
											volume=new_volume;
										}
										e.preventDefault();
										break;
									case 39:
										step_forward();
										e.preventDefault();
										break;
									case 37:
										step_back();
										e.preventDefault();
										break;
									case 32:
										VideoClickPlayPause();
										e.preventDefault();
										break;
								}
								
							}
							
							function huge_it_single_video_responsive(){
								var video_parent=container.parentNode;
								var video_parent_width=video_parent.offsetWidth;
								if(video_parent_width<=video_param_width && !isFullScreen()){
									/*container.style.width="100%";*/
									addClass("fullwidth",container);
									jQuery.when(huge_it_single_video_responsive).done(function(){
										setTimeout(function(){
											video_width=video.offsetWidth;
										},500);
									});
								}else{
									removeClass("fullwidth",container);
									/*container.style.width=video_param_width+"px";*/
									jQuery.when(huge_it_single_video_responsive).done(function(){
										setTimeout(function(){
											video_width=video.offsetWidth;
										},500);
									});
								}
							}
							
							function videoResized(){
								setTimeout(function(){
									video_width=video.offsetWidth;
								},500);
							}
							
							/* *** */

							/* ADD EVENT LISTENERS */
							/* *** */
							video.addEventListener("timeupdate",timeupdate,false);
							video.addEventListener("playing",progressHandler,false);
							video.addEventListener("loadeddata",progressHandler,false);
							video.addEventListener("canplaythrough",progressHandler,false);
							video.addEventListener("progress",progressHandler,false);
							video.addEventListener("waiting",waiting,false);
							video.addEventListener("canplay",notWaiting,false);
							video.addEventListener("ended",videoEnd,false);
							video.addEventListener("resize",videoResized,false);
							/* *** */
							video.addEventListener("click",VideoClickPlayPause,false);
							video.addEventListener("dblclick",toggle_full_screen,false);
							center_play_icon.addEventListener("click",VideoClickPlayPause,false);
							center_pause_icon.addEventListener("click",VideoClickPlayPause,false);
							center_wait_icon.addEventListener("click",VideoClickPlayPause,false);
							thumb_box.addEventListener("click",PlayPause,false);
							play_btn.addEventListener("click",PlayPause,false);
							backward_btn.addEventListener("click",step_back,false);
							forward_btn.addEventListener("click",step_forward,false);
							mute_btn.addEventListener("click",toggle_mute,false);
							fullscreen_btn.addEventListener("click",toggle_full_screen,false);
							container.addEventListener('webkitfullscreenchange', exitHandler, false);
							container.addEventListener('mozfullscreenchange', exitHandler, false);
							container.addEventListener('fullscreenchange', exitHandler, false);
							container.addEventListener('MSFullscreenChange', exitHandler, false);
							progress_bar.addEventListener("mouseover",timerDisplay,false);
							progress_bar.addEventListener("mousemove",timerDisplay,false);
							progress_bar.addEventListener("mouseout",timerNotDisplay,false);
							
							/* KEYBOARD */
							/* *** */
							container.addEventListener("mouseover",function(){
								window.addEventListener("keydown",keyFunctions,false);
							},false)
							
							container.addEventListener("mouseout",function(){
								window.removeEventListener("keydown",keyFunctions,false);
							},false)
							/* *** */
							
							/* VIDEO SEEKING */
							progress_bar.addEventListener("mousedown",function(e){
								dragging=1;
								vidSeeking(e);
							},false);
							
							window.addEventListener("mousemove",function(e){
								if(dragging==1){
									if(paused!=1){
										video.pause();
									}
									removeClass("playing",container);
									vidSeeking(e);
								}
							},false);
							window.addEventListener("mouseup",function(){
								if(dragging==1){
									if(paused!==1){
										video.play();
									}
									addClass("playing",container);
									dragging="";
								}
							},false);
							/* VOLUME CHANGING */
							volume_handle.addEventListener("mousedown", function (e) {
								volume_dragging = 1;
								setVolume(e);
							}, false);
							window.addEventListener("mousemove", function (e) {
								if (volume_dragging == 1) {
									setVolume(e);
								}
							}, false);
							window.addEventListener("mouseup", function () {
								if (volume_dragging == 1) {
									volume_dragging = "";
								}
							}, false);

							var timeout;
							container.onmousemove = function () {
								if (isFullScreen()) {
									removeClass("hide_controls", container);
									clearTimeout(timeout);
									timeout = setTimeout(function () {
										addClass("hide_controls", container);
									}, 3000);
								}

							}
						}
					}
					
					function init_playlist_active_item(){
						/* youtube player container */
						youtube=global_container.querySelector("#youtube_player_<?php echo $i; ?>");
						jQ_youtube=jQuery("#youtube_player_<?php echo $i; ?>");
						/* vimeo player container */
						vimeo_js=global_container.querySelector("#vimeo_<?php echo $i; ?>");
						jQ_vimeo=jQuery("#vimeo_<?php echo $i; ?>");
						
						var active=playlist_container.querySelector("li.active")
						var id=active.getAttribute("data-item-id");
						var type=active.getAttribute("data-type");
						
						switch(type){
							case "custom":
								/* HIDE YOUTUBE PLAYER */
								youtube.style.display="none";
								if(typeof youtube_player.loadVideoById === 'function'){
									youtube_player.loadVideoById("");
								}
								
								global_container.querySelector(".players_wrapper #youtube_<?php echo $i; ?>_thumb").style.display="none";
								/* HIDE VIMEO PLAYER */ 
								vimeo_player.setAttribute("src","");
								vimeo_js.style.display="none";
								global_container.querySelector(".players_wrapper #vimeo_<?php echo $i; ?>_thumb").style.display="none";
								/* DISPLAY CUSTOM VIDEO */
								container.style.display="block";
								var ContainerParent = document.getElementById("huge_it_album_video_player_<?php echo $i; ?>");
								var src=active.getAttribute("data-src");
								var thumb=active.getAttribute("data-poster");
								var title=ContainerParent.querySelector('li.active');
								title=title.getAttribute("data-title");
								container.querySelector("video").setAttribute("src",src);
								if(title == '')
									container.querySelector(".huge_it_video_player_top").style.display="none";
								else 
								{
									container.querySelector(".huge_it_video_player_top").style.display="block";
									container.querySelector(".video_title").innerHTML=title;
								}
								if(playlist_autoplay=="true"){
									container.querySelector("video").autoplay = true;
									addClass("playing",container);
									removeClass("poster",container);
									container.querySelector(".thumbnail_block").style.display="none";
									container.querySelector(".play_pause").innerHTML='<i class="hugeicons hugeicons-pause"></i>';
									playlist_autoplay="true";
									progressHandler();
								}else{
									container.querySelector("video").autoplay = false;
									removeClass("playing",container);
									container.querySelector(".play_pause").innerHTML='<i class="hugeicons hugeicons-play"></i>';
									addClass("poster",container);
									if(thumb != ""){
										container.querySelector(".thumbnail_block img").setAttribute("src",thumb);
									}
									container.querySelector(".thumbnail_block").style.display="block";
								}
								break;
							case "youtube":
								/* DISPLAY YOUTUBE PLAYER */
								var id=active.getAttribute("data-video-id");
								var thumb=active.getAttribute("data-poster");
                                if(typeof youtube_player.loadVideoById === 'function') {
                                    youtube_player.loadVideoById(id);
                                }
								youtube.style.display="block";
                                youtube.style.height=video_param_aprox_height+"px";

								if(playlist_autoplay=="true"){
									youtube_player.playVideo();
									global_container.querySelector(".players_wrapper #youtube_<?php echo $i; ?>_thumb").style.display="none";
								}else{
                                    if(typeof youtube_player.pauseVideo === 'function') {
                                        youtube_player.pauseVideo();
                                    }
									global_container.querySelector(".players_wrapper #youtube_<?php echo $i; ?>_thumb img.thumb").setAttribute("src",thumb);
									global_container.querySelector(".players_wrapper #youtube_<?php echo $i; ?>_thumb").style.display="block";
								}
								/* HIDE VIMEO PLAYER */ 
								vimeo_player.setAttribute("src","");
								vimeo_js.style.display="none";
								global_container.querySelector(".players_wrapper #vimeo_<?php echo $i; ?>_thumb").style.display="none";
								/* HIDE CUSTOM VIDEO */
								container.style.display="none";
								container.querySelector("video").setAttribute("src","");
								container.querySelector(".thumbnail_block").style.display="none";
								break;
							case "vimeo":
								/* HIDE YOUTUBE PLAYER */
								youtube.style.display="none";
                                if(typeof youtube_player.loadVideoById === 'function') {
                                    youtube_player.loadVideoById("");
                                }
								global_container.querySelector(".players_wrapper #youtube_<?php echo $i; ?>_thumb").style.display="none";
								/* DISPLAY VIMEO PLAYER */ 
								var id=active.getAttribute("data-video-id");
								var thumb=active.getAttribute("data-poster");
								var vimeo_autoplay;
								if(playlist_autoplay=="true"){
									vimeo_autoplay="&autoplay=1";
									global_container.querySelector(".players_wrapper #vimeo_<?php echo $i; ?>_thumb").style.display="none";
								}else{
									vimeo_autoplay="";
									global_container.querySelector(".players_wrapper #vimeo_<?php echo $i; ?>_thumb img.thumb").setAttribute("src",thumb);
									global_container.querySelector(".players_wrapper #vimeo_<?php echo $i; ?>_thumb").style.display="block";
								}
								
								vimeo_player.setAttribute("src","https://player.vimeo.com/video/"+id+"?api=1&color=<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_vimeo_color']); ?>&player_id=vimeo_<?php echo $i; ?>&fullscreen=0"+vimeo_autoplay);
								vimeo_js.style.display="block";
								/* HIDE CUSTOM VIDEO */
								container.style.display="none";
								container.querySelector("video").setAttribute("src","");
								container.querySelector(".thumbnail_block").style.display="none";
								break;
						}
					}
					
					function next_track(){
						var active=jQ_items.parent().find("li.active");
						var active_id=active.data("item-id");
						var max_id=jQ_items.length-1;
						var next;
						if(active_id!=max_id){
							next=active_id+1;
						}else{
							next=0;
						}
						active.removeClass("active");
						jQ_items.eq(next).addClass("active");
						init_playlist_active_item();
					}
					
					function change_track(el){
						var active=el.parent().find("li.active");
						var active_id=active.data("item-id");
						var this_id=el.data("item-id");
						var max_id=jQ_items.length-1;
						if(this_id!=active_id){
							active.removeClass("active");
							el.addClass("active");
							jQuery.when(change_track).done(function(){
								setTimeout(function(){
									init_playlist_active_item();
								},100);
							});
						}
					}
					
					function huge_it_playlist_responsive(){
						var computedStyle = getComputedStyle(global_container.parentNode);
						var a=global_container.parentNode.clientWidth-parseFloat(computedStyle.paddingRight)-parseFloat(computedStyle.paddingLeft);
						if(player_width>=a){
							addClass("fullwidth",global_container);
							jQuery.when(change_track).done(function(){
								
								setTimeout(function(){
									var player_width=global_container.offsetWidth;
									var player_aprox_height=player_width*0.56;
									youtube.style.height=player_aprox_height+"px";
									vimeo_js.style.height=player_aprox_height+"px";
								},100);
							});
						}else{
							removeClass("fullwidth",global_container);
							youtube.style.height=video_param_aprox_height+"px";
							vimeo_js.style.height=video_param_aprox_height+"px";
						}
					}
					
					function removeVimeoThumb(){
						var active=playlist_container.querySelector("li.active");
						var type=active.getAttribute("data-type");
						if(type=="vimeo"){
							var id=active.getAttribute("data-video-id");
							global_container.querySelector(".players_wrapper #vimeo_<?php echo $i; ?>_thumb").style.display="none";
							vimeo_player.setAttribute("src","https://player.vimeo.com/video/"+id+"?api=1&color=<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_vimeo_color']); ?>&portrait=<?php echo $paramssld['video_pl_vimeo_portrait']; ?>&player_id=vimeo_<?php echo $i; ?>&fullscreen=0&autoplay=1");
							playlist_autoplay="true";
						}
						
					}
					
					function removeYoutubeThumb(){
						var active=playlist_container.querySelector("li.active");
						var type=active.getAttribute("data-type");
						if(type=="youtube"){
							var id=active.getAttribute("data-video-id");
							global_container.querySelector(".players_wrapper #youtube_<?php echo $i; ?>_thumb").style.display="none";
                            if(typeof youtube_player.loadVideoById === 'function') {
                                youtube_player.loadVideoById(id);
                            }
							youtube_player.playVideo();
							playlist_autoplay="true";
						}
					}

					/* ADD EVENT LISTENERS */
					init_playlist_custom_video();
					
					huge_it_playlist_responsive();
					jQuery(window).on("resize",function(){
						huge_it_playlist_responsive();
					});
					jQ_items.on("click",function(){
						change_track(jQuery(this));
					});
					
					global_container.querySelector(".players_wrapper #youtube_<?php echo $i; ?>_thumb").addEventListener("click",removeYoutubeThumb,false);
					global_container.querySelector(".players_wrapper #vimeo_<?php echo $i; ?>_thumb").addEventListener("click",removeVimeoThumb,false);
					
				});
			}
			/* *** */
		}
	}
});
			</script>
			<style>
			/*
			parameters
			*/
			#huge_it_album_video_player_<?php echo $i; ?> {
				width:<?php echo $width; ?>px;
				height:auto;
				<?php
				switch($paramssld['video_pl_position']){
					case "left":
						?>
						float:left;
						margin-right:<?php echo absint($paramssld['video_pl_margin_right']); ?>px;
						margin-left:<?php echo absint($paramssld['video_pl_margin_left']); ?>px;
						<?php
						break;
					case "right":
						?>
						float:right;
						margin-right:<?php echo absint($paramssld['video_pl_margin_right']); ?>px;
						margin-left:<?php echo absint($paramssld['video_pl_margin_left']); ?>px;
						<?php
						break;
					case "center":
						?>
						margin:0px auto;
						<?php
						break;
				}
				?>
				margin-top:<?php echo absint($paramssld['video_pl_margin_top']); ?>px;
				margin-bottom:<?php echo absint($paramssld['video_pl_margin_bottom']); ?>px;
				background:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_background_color']); ?>;
				border:<?php echo absint($paramssld['video_pl_border_size']); ?>px solid #<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_border_color']); ?>;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper {
				<?php 
				switch($video_player[0]->layout){
					case "left":
						?>
						float:right;
						<?php
						break;
					case "right":
						?>
						float:left;
						<?php
						break;
					case "bottom":
						?>
						float:left;
						<?php
						break;
				}
				?>
			}

			#huge_it_album_video_player_<?php echo $i; ?>,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .custom_thumb,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .thumbnail_block {
				background:#f1f1f1;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player {
				position:relative;
				float:left;
				display:block;
				width:100%;
				height:100%;
				margin:0px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper > iframe {
				height:<?php echo $width*(3/5)*0.56; ?>px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper {
				width:<?php echo $width*(3/5); ?>px;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?>.align_bottom .players_wrapper {
				width:<?php echo $width; ?>px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .thumbnail_block {
				background:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_background_color']); ?>;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_video_player_bottom {
				background:rgba(<?php echo hugeit_vp_hex2RGB($paramssld['video_pl_controls_panel_color']); ?>,<?php echo $paramssld['video_pl_controls_panel_opacity']/100; ?>);
			}

			#huge_it_album_video_player_<?php echo $i; ?> .huge_it_player .thumbnail_block .thumbnail_play,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_video_player_center,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_video_player_bottom .huge_it_video_player_controls .control {
				color:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_buttons_color']); ?> !important;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_video_player_bottom .huge_it_video_player_controls .control:hover,
			#huge_it_album_video_player_<?php echo $i; ?> .huge_it_player .thumbnail_block .thumbnail_play:hover {
				color:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_buttons_hover_color']); ?> !important;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_video_player_bottom .huge_it_video_player_duration_slide {
				background:transparent;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_video_player_bottom .huge_it_video_player_duration_slide .duration {
				background:rgba(<?php echo hugeit_vp_hex2RGB($paramssld['video_pl_timeline_background']); ?>,<?php echo $paramssld['video_pl_timeline_background_opacity']/100; ?>);
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_video_player_bottom .huge_it_video_player_duration_slide .buffered {
				background:rgba(<?php echo hugeit_vp_hex2RGB($paramssld['video_pl_timeline_buffering_color']); ?>,<?php echo $paramssld['video_pl_timeline_buffering_opacity']/100; ?>);
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle .volume_before,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_video_player_bottom .huge_it_video_player_duration_slide .played{
				background:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_timeline_color']); ?>;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_video_player_bottom .huge_it_video_player_duration_slide .thumb {
				background:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_timeline_color']); ?> !important;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_video_player_bottom .huge_it_video_player_controls .timer .current_time {
				color:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_curtime_color']); ?>;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_video_player_bottom .huge_it_video_player_controls .timer .separator,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_video_player_bottom .huge_it_video_player_controls .timer .duration_time {
				color:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_durtime_color']); ?>;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper,
			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul {
				background:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_playlist_color']); ?>;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper h3 {
				color:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_playlist_head_color']); ?>;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul li.item:hover {
				background:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_playlist_hover_color']); ?>;
				
			}

			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul li.item.active {
				background:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_playlist_active_color']); ?>;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul li.item .playlist_button .item_title {
				color:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_playlist_text_color']); ?>;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul li.item:hover .playlist_button .item_title {
				color:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_playlist_hover_text_color']); ?>;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul li.item.active .playlist_button .item_title {
				color:#<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_playlist_active_text_color']); ?>;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul::-webkit-scrollbar-thumb {
				border-left-color: #<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_playlist_scroll_thumb']); ?>;
				background: #<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_playlist_scroll_thumb']); ?>;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul::-webkit-scrollbar-track {
				border-left-color: #<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_playlist_scroll_track']); ?>;
				background: #<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_playlist_scroll_track']); ?>;
				-webkit-box-shadow: inset 0 0 1px #<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_playlist_scroll_track']); ?>;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul::-webkit-scrollbar-thumb:hover {
				border-left-color: #<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_playlist_scroll_thumb_hover']); ?>;
				background: #<?php echo sanitize_hex_color_no_hash($paramssld['video_pl_playlist_scroll_thumb_hover']); ?>;
			}

			/*
			static
			*/

			#huge_it_album_video_player_<?php echo $i; ?> {
				position:relative;
				display:table;
				height:auto;
				min-width:250px;
				font-size:15px;
				font-weight:normal;
				font-family:Roboto, Arial, Helvetica, sans-serif;
				font-style:normal;
				line-height:1;
				user-select:none;
				-webkit-user-select:none;
				-moz-user-select:none;
				-o-user-select:none;
				-ms-user-select:none;
				box-sizing:content-box;
			}

			#huge_it_album_video_player_<?php echo $i; ?>.fullwidth {
				width:100%;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper {
				position:relative;
				display:table;
				height:auto;
			}

			#huge_it_album_video_player_<?php echo $i; ?>.fullwidth .players_wrapper {
				width:100%;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player {
				position:relative;
				display:table;
				height:auto;
				min-width:250px;
				font-size:15px;
				font-weight:normal;
				font-family:Roboto, Arial, Helvetica, sans-serif;
				font-style:normal;
				line-height:1;
				user-select:none;
				-webkit-user-select:none;
				-moz-user-select:none;
				-o-user-select:none;
				-ms-user-select:none;
			}

			#huge_it_album_video_player_<?php echo $i; ?>.loading {
				overflow:hidden;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .load_icon {
				position:absolute;
				display:block;
				width:100%;
				height:100%;
				background:#fff;
				z-index:15;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .load_icon img {
				width:50px;
				position:absolute;
				top:50%;
				left:50%;
				margin-left:-40px;
				margin-top:30px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .playlist_player,
			#huge_it_album_video_player_<?php echo $i; ?> .playlist_player iframe {
				position:relative;
				display:block;
				width:100%;
				height:auto;
				margin:0px;
				z-index:5;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.poster {
				overflow:hidden;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullwidth {
				width:100%;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen {
				width:100%;
				transition:none;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen video {
				width:100%;
				max-height:100%;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player div[data-title]:hover:after {
				content: attr(data-title);
				padding:8px 10px;
				color: #fff;
				position: absolute;
				left:0;
				bottom: calc(100% + 20px);
				bottom: -webkit-calc(100% + 20px);
				bottom: -moz-calc(100% + 20px);
				bottom: -ms-calc(100% + 20px);
				bottom: -o-calc(100% + 20px);
				white-space: nowrap;
				z-index:6;
				font-size:12px;
				background:#444;
				border-radius:2px;
				line-height:1;
				-moz-transition: opacity .1s cubic-bezier(0.0,0.0,0.2,1);
				-webkit-transition: opacity .1s cubic-bezier(0.0,0.0,0.2,1);
				transition: opacity .1s cubic-bezier(0.0,0.0,0.2,1);
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_player {
				position:relative;
				float:left;
				display:block;
				width:100%;
				height:100%;
				margin:0px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_player video {
				position:relative;
				float:left;
				display:block;
				width:100%;
				height:auto;
				margin:0px;
				z-index:5;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .thumbnail_block {
				position:absolute;
				display:none;
				left:0px;
				top:0px;
				width:100%;
				height:100%;
				overflow:hidden;
				z-index:10;
				text-align:center;
				vertical-align:middle;
				white-space:nowrap;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.poster .thumbnail_block {
				display:block;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .thumbnail_block img {
				position:absolute;
				left:0px;
				top:0px;
				display:block;
				width:100%;
				max-height:none;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .thumbnail_block .thumbnail_play {
				position:absolute;
				display:block;
				left:50%;
				top:50%;
				margin:-30px 0px 0px -30px;
				width:60px;
				height:60px;
				font-size:58px;
				line-height:60px;
				text-align:center;
				cursor:poiner;
				transition:transform .2s cubic-bezier(0.0,0.0,0.2,1);
				cursor:pointer;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .thumbnail_block .thumbnail_play:hover {
				transform:scale(1.05,1.05);
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_top {
				position: absolute;
				top: 0px;
				left: 0px;
				width: calc(100% - 30px);
				width: -webkit-calc(100% - 30px);
				width: -moz-calc(100% - 30px);
				width: -o-calc(100% - 30px);
				width: -ms-calc(100% - 30px);
				padding: 15px;
				background:rgba(0,0,0,.1);
				cursor: pointer;
				overflow: hidden;
				z-index:11;
				webkit-transition: opacity .25s cubic-bezier(0.0,0.0,0.2,1);
				transition: opacity .25s cubic-bezier(0.0,0.0,0.2,1);
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing .huge_it_video_player_top,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.hide_controls.playing .huge_it_video_player_top {
				opacity:0;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing:not(.hide_controls):hover .huge_it_video_player_top {
				opacity:1;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_top .video_title {
				color: #fff;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom {
				position:absolute;
				display:block;
				bottom:0px;
				left:0px;
				height:50px;
				width:100%;
				z-index:6;
				webkit-transition: opacity .25s cubic-bezier(0.0,0.0,0.2,1);
				transition: opacity .25s cubic-bezier(0.0,0.0,0.2,1);
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen .huge_it_video_player_bottom {
				height:80px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing .huge_it_video_player_bottom {
				opacity:0;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.hide_controls.playing .huge_it_video_player_bottom {
				opacity:0;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing:not(.hide_controls):hover .huge_it_video_player_bottom {
				opacity:1;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_duration_slide {
				position:absolute;
				left:0px;
				bottom:30px;
				display:block;
				width:100%;
				height:20px;
				margin:0px;
				line-height:1;
				cursor:pointer;
				-moz-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
				-webkit-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
				-ms-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
				transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
				transform-origin:center center;
				-webkit-transform-origin:center center;
				-moz-transform-origin:center center;
				-o-transform-origin:center center;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide {
				height:30px;
				bottom:50px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_duration_slide .duration {
				position:absolute;
				display:block;
				bottom:7.5px;
				left:0px;
				width:100%;
				height:5px;
				z-index:6;
				-moz-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
				-webkit-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
				-ms-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
				transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
				transform-origin:center center;
				-webkit-transform-origin:center center;
				-moz-transform-origin:center center;
				-o-transform-origin:center center;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide .duration {
				bottom:11px;
				height:8px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_duration_slide .played {
				position:absolute;
				display:block;
				bottom:7.5px;
				left:0px;
				width:0px;
				height:5px;
				z-index:8;
				-moz-transition:height .1s cubic-bezier(0.0,0.0,0.2,1);
				-webkit-transition:height .1s cubic-bezier(0.0,0.0,0.2,1);
				-ms-transition:height .1s cubic-bezier(0.0,0.0,0.2,1);
				transition:height .1s cubic-bezier(0.0,0.0,0.2,1);
				transform-origin:center center;
				-webkit-transform-origin:center center;
				-moz-transform-origin:center center;
				-o-transform-origin:center center;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide .played {
				bottom:11px;
				height:8px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_duration_slide .thumb {
				position:absolute;
				display:block;
				bottom:3.5px;
				left:0px;
				width:13px;
				height:13px;
				margin-left:-6.5px;
				background:#f12b24;
				border-radius:6.5px;
				z-index:9;
				cursor:pointer;
				-moz-transition:transform .1s cubic-bezier(0.0,0.0,0.2,1);
				-webkit-transition:transform .1s cubic-bezier(0.0,0.0,0.2,1);
				-ms-transition:transform .1s cubic-bezier(0.0,0.0,0.2,1);
				transition:transform .1s cubic-bezier(0.0,0.0,0.2,1);
				transform-origin:center center;
				-webkit-transform-origin:center center;
				-moz-transform-origin:center center;
				-o-transform-origin:center center;
				
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide .thumb {
				bottom:5px;
				width:20px;
				height:20px;
				border-radius:10px;
				margin-left:-10px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide .thumb {
				transform:scale(0,0);
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide:hover .thumb {
				transform:scale(1,1);
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_duration_slide .buffered {
				position:absolute;
				display:block;
				bottom:7.5px;
				left:0px;
				width:0px;
				height:5px;
				z-index:7;
				-moz-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
				-webkit-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
				-ms-transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
				transition:all .1s cubic-bezier(0.0,0.0,0.2,1);
				transform-origin:center center;
				-webkit-transform-origin:center center;
				-moz-transform-origin:center center;
				-o-transform-origin:center center;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide .buffered {
				bottom:11px;
				height:8px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide .duration,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide .played,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide .buffered {
				bottom:8.5px;
				height:3px;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide .duration,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide .played,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide .buffered {
				bottom:12.5px;
				height:5px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide:hover .duration,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide:hover .played,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing .huge_it_video_player_bottom .huge_it_video_player_duration_slide:hover .buffered {
				bottom:7.5px;
				height:5px;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide:hover .duration,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide:hover .played,
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.playing.fullscreen .huge_it_video_player_bottom .huge_it_video_player_duration_slide:hover .buffered {
				bottom:11px;
				height:8px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_duration_slide .hover_timer {
				position: absolute;
				display:none;
				left:0;
				bottom:100%;
				padding: 5px 9px;
				max-width: 200px;
				background:rgba(28,28,28,0.8);
				border-radius:2px;
				white-space: nowrap;
				word-wrap: normal;
				-o-text-overflow: ellipsis;
				text-overflow: ellipsis;
				font-size:11px;
				line-height:1;
				color: #fff;
				z-index:6;
				 -moz-transform: none;
				-ms-transform: none;
				-webkit-transform: none;
				transform: none;
				-moz-transition:opacity .1s cubic-bezier(0.4,0.0,1,1);
				-webkit-transition:opacity .1s cubic-bezier(0.4,0.0,1,1);
				-ms-transition:opacity .1s cubic-bezier(0.4,0.0,1,1);
				transition:opacity .1s cubic-bezier(0.4,0.0,1,1);
				 -webkit-animation-duration:.1s;
				-webkit-animation-iteration-count:1;
				-webkit-animation-timing-function: cubic-bezier(0.4,0.0,1,1);
				opacity:0;
			}

			@-webkit-keyframes opacity {
				0% {
					opacity:0;
				}
				100% {
					opacity:1;
				}
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls {
				position:absolute;
				left:0px;
				bottom:0px;
				display:block;
				width:100%;
				height:30px;
				margin:0px;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.small .huge_it_video_player_bottom .huge_it_video_player_controls {
				text-align:center;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen .huge_it_video_player_bottom .huge_it_video_player_controls {
				height:50px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .control {
				position:relative;
				display:inline-block;
				width:30px;
				height:30px;
				margin:0px 0px 0px 5px;
				font-size:16px;
				line-height:30px;
				text-align:center;
				vertical-align:top;
				cursor:pointer;
			}
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .control i{
                                line-height: inherit;
                        }
		
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen .huge_it_video_player_bottom .huge_it_video_player_controls .control {
				width:50px;
				height:50px;
				margin:0px 0px 0px 5px;
				font-size:30px;
				line-height:50px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .fast_back {
				
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.small .huge_it_video_player_bottom .huge_it_video_player_controls .fast_back {
				display:none;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .play_pause {

			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.small .huge_it_video_player_bottom .huge_it_video_player_controls .play_pause {
				float:initial;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .fast_forward {
				
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.small .huge_it_video_player_bottom .huge_it_video_player_controls .fast_forward {
				display:none;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .mute_button {
				
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.small .huge_it_video_player_bottom .huge_it_video_player_controls .mute_button {
				float:initial;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.very_small .huge_it_video_player_bottom .huge_it_video_player_controls .mute_button {
				display:none;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle {
				position:relative;
				display:inline-block;
				width:50px;
				height:30px;
				background: none;
				cursor: pointer;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.small .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle {
				float:initial;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.very_small .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle {
				display:none;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle {
				position:relative;
				display:inline-block;
				width:100px;
				height:50px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle .volume_before {
				position: absolute;
				top:13.5px;
				left:0px;
				height:3px;
				width:50px;
				z-index:7;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle .volume_before {
				top:22.5px;
				height:5px;
				width:100px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle .volume_current {
				position: absolute;
				top:8.5px;
				left:50px;
				height:13px;
				width: 4px;
				margin-left:-2px;
				background: #fff;
				z-index:8;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle .volume_current {
				top:15px;
				height:20px;
				width:6px;
				margin-left:-3px;
				left:100px;
				width: 4px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle .volume_after {
				position: absolute;
				top:13.5px;
				left:0px;
				height:3px;
				width:50px;
				background:#fff;
				z-index:6;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen .huge_it_video_player_bottom .huge_it_video_player_controls .volume_handle .volume_after {
				top:22.5px;
				height:5px;
				width:100px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .timer {
				position:relative;
				display:inline-block;
				min-width:50px;
				height:30px;
				margin:0px 0px 0px 5px;
				vertical-align:top;
				font-size:11px;
				line-height:30px;
				font-style:normal;
				font-weight:normal;
				text-align:center;
				cursor:pointer;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.fullscreen .huge_it_video_player_bottom .huge_it_video_player_controls .timer {
				height:50px;
				font-size:14px;
				line-height:50px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .timer .current_time {
				display:inline-block;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .timer .separator {
				display:inline-block;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .timer .duration_time {
				display:inline-block;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_bottom .huge_it_video_player_controls .full_screen {
				float:right;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player.small .huge_it_video_player_bottom .huge_it_video_player_controls .full_screen {
				float:initial;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_center {
				text-align:center;
				font-size:15px;
				line-height:40px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_center div {
				position:absolute;
				display:none;
				left:50%;
				top:50%;
				margin-top:-20px;
				height:40px;
				width:40px;
				margin-left:-20px;
				background: rgba(0,0,0,.2);
				border-radius: 100%;
				text-align:center;
				transition:all .2s linear;
				 -webkit-animation-duration:.5s;
				-webkit-animation-iteration-count:1;
				-webkit-animation-timing-function: linear;
				z-index:6;
				opacity:0;
			}
                        #huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_center i{
                                line-height: 40px;
                        }
			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .huge_it_player .huge_it_video_player_center .center_wait {
				background:transparent;
			}

			@-webkit-keyframes popup {
				0% {
					opacity:1;
					transform:scale(1,1);
				}
				100% {
					opacity:0;
					transform:scale(3,3);
				}
			}

			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper {
				float:left;
				display:block;
				width:<?php echo $width*(2/5); ?>px;
				height:100%;
				margin:0px;
				
			}

			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper h3 {
				position: relative;
				float: left;
				display: block;
				width: calc(100% - 20px);
				width: -webkit-calc(100% - 20px);
				width: -o-calc(100% - 20px);
				width: -moz-calc(100% - 20px);
				height: 30px;
				font-size: 18px;
				font-style: normal;
				text-shadow: none;
				margin: 0px;
				padding: 10px;
			}



			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul {
				position:absolute;
				display:block;
				width:<?php echo $width*(2/5); ?>px;
				top:50px;
				height:calc(100% - 50px);
				height:-webkit-calc(100% - 50px);
				height:-moz-calc(100% - 50px);
				height:-o-calc(100% - 50px);
				height:-ms-calc(100% - 50px);
				margin:0px;
				padding:0px;
				list-style-type:none;
				overflow:auto;
				
			}

			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul::-webkit-scrollbar-track {
				
			}

			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul::-webkit-scrollbar {
				width: 10px;
				height: 9px;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul::-webkit-scrollbar-thumb {
				
			}

			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul::-webkit-scrollbar-thumb:hover {
				
			}

			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul li.item {
				position:relative;
				float:left;
				display:block;
				width:100%;
				margin:0px;
				padding:0px;
				overflow:hidden;
				list-style-type:none;
			}
			
			
			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul li.item .playlist_button {
				position:relative;
				float:left;
				display:block;
				width:calc(100% - 20px);
				width:-webkit-calc(100% - 20px);
				width:-moz-calc(100% - 20px);
				width:-o-calc(100% - 20px);
				width:-ms-calc(100% - 20px);
				padding:10px;
				cursor:pointer;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul li.item .playlist_button .thumb {
				position:relative;
				float:left;
				display:block;
				width:72px;
				height:54px;
				overflow:hidden;
				text-align: center;
				vertical-align: middle;
				white-space: nowrap;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul li.item .playlist_button .thumb:before {
				content:'';
				display:inline-block;
				height:100%;
				vertical-align:middle;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul li.item .playlist_button img {
				display: inline-block;
				vertical-align: middle;
				width: auto;
				height: auto;
				max-width: 100%;
			}
			
			
			#huge_it_album_video_player_<?php echo $i; ?> .playlist_wrapper ul li.item .playlist_button .item_title {
				position: relative;
				display: block;
				float:right;
				width: calc(100% - 72px);
				height:54px;
				font-size:13px;
				vertical-align:middle;
				line-height:54px;
				white-space: nowrap;
				text-overflow: ellipsis;
				word-wrap: break-word;
				text-indent: 15px;
				cursor: pointer;
				overflow: hidden;
			}

			#huge_it_album_video_player_<?php echo $i; ?>.fullwidth .playlist_wrapper {
				width:100%;
			}

			#huge_it_album_video_player_<?php echo $i; ?>.fullwidth .playlist_wrapper ul {
				position:relative;
				top:0px;
				width:100%;
				height:300px;
				margin:0px;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?>.align_bottom .playlist_wrapper {
				width:100%;
			}
			
			#huge_it_album_video_player_<?php echo $i; ?>.align_bottom .playlist_wrapper ul {
				position:relative;
				top:0px;
				width:100%;
				height:300px;
				margin:0px;
			}


			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .custom_thumb {
				position: absolute;
				display: none;
				left: 0px;
				top: 0px;
				width: 100%;
				height: 100%;
				overflow: hidden;
				z-index: 10;
				text-align: center;
				vertical-align: middle;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .custom_thumb img.thumb {
				display:block;
				position:absolute;
				top:0px;
				left:0px;
				width:100%;
				max-height:none;
                height: 100%;
			}

			#huge_it_album_video_player_<?php echo $i; ?> .players_wrapper .custom_thumb img.play {
				position:absolute;
				top:calc(50% - 24.5px);
				top:-webkit-calc(50% - 24.5px);
				top:-moz-calc(50% - 24.5px);
				top:-o-calc(50% - 24.5px);
				top:-ms-calc(50% - 24.5px);
				left:calc(50% - 35px);
				left:-webkit-calc(50% - 35px);
				left:-moz-calc(50% - 35px);
				left:-o-calc(50% - 35px);
				left:-ms-calc(50% - 35px);
				width:70px;
				height:49px;
				cursor: pointer;
			}

			#huge_it_album_video_player_<?php echo $i; ?> * {
				box-sizing:content-box !important;
				text-indent: 0px;
			}

			</style>
			<div id="huge_it_album_video_player_<?php echo $i; ?>" class="loading <?php if($video_player[0]->layout=="bottom"){ echo "align_bottom"; } ?>">
				<div class="load_icon">
					<img src="<?php echo plugins_url('../images/loading.gif', __FILE__); ?>" alt="loading" />
				</div>
				<div class="players_wrapper">
					<div class="huge_it_player playlist_player">
						<div class="huge_it_video_player_player">
							<video  src="" data-current="--" data-duration="--">
								Your browser does not support HTML5 video.
							</video>
						</div>
						<div class="thumbnail_block">
							<img src="" alt="" />
							<div class="thumbnail_play"><i class="hugeicons hugeicons-play-circle-o"></i></div>
						</div>
						<div class="huge_it_video_player_top">
							<div class="video_title">Big Buck Bunny</div>
						</div>
						<div class="huge_it_video_player_bottom">
							<div class="huge_it_video_player_duration_slide">
								<div class="duration"></div>
								<div class="played"></div>
								<div class="thumb"></div>
								<div class="buffered"></div>
								<div class="hover_timer">
									<span class="hover_timer_time">00:00</span>
								</div>
							</div>
							<div class="huge_it_video_player_controls">
								<div class="fast_back control" data-title="Fast backward"><i class="hugeicons hugeicons-step-backward"></i></div>
								<div class="play_pause control" data-title="Play"><i class="hugeicons hugeicons-play"></i></div>
								<div class="fast_forward control" data-title="Fast forward"><i class="hugeicons hugeicons-step-forward"></i></div>
								<div class="mute_button control" data-title="Mute"><i class="hugeicons hugeicons-volume-up"></i></div>
								<div class="volume_handle">
									<div class="volume_before"></div>
									<div class="volume_current"></div>
									<div class="volume_after"></div>
								</div>
								<div class="timer">
									<div class="current_time">--</div>
									<div class="separator"> / </div>
									<div class="duration_time">--</div>
								</div>
								<div class="full_screen control" data-title="Full Screen"><i class="hugeicons hugeicons-expand"></i></div>
							</div>
						</div>
						<div class="huge_it_video_player_center">
							<div class="center_play"><i class="hugeicons hugeicons-play"></i></div>
							<div class="center_pause"><i class="hugeicons hugeicons-pause"></i></div>
							<div class="center_wait"><i class="hugeicons hugeicons-spinner hugeicons-pulse"></i></div>
						</div>
					</div>
					<div id="youtube_<?php echo $i; ?>_thumb" class="custom_thumb"><img class="thumb" src="<?php echo esc_url($videos[0]->image_url); ?>" alt="" /><img class="play" src="<?php echo plugins_url('../images/play.youtube.png', __FILE__); ?>" alt="youtube play" /></div>
					<div id="youtube_player_<?php echo $i; ?>" class="playlist_player"></div>
					<div id="vimeo_<?php echo $i; ?>_thumb" class="custom_thumb"><img class="thumb" src="<?php echo esc_url($videos[0]->image_url); ?>" alt="" /><img class="play" src="<?php echo plugins_url('../images/play.vimeo.png', __FILE__); ?>" alt="vimeo play" /></div>
					<iframe id="vimeo_<?php echo $i; ?>" class="playlist_player" src="" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				</div>
				<div class="playlist_wrapper">
					<h3><?php echo esc_html($video_player[0]->name); ?></h3>
					<ul>
						<?php
						$j=0;
						foreach($videos as $video){
							switch($video->sl_type){
								case "video":
									?>
									<li class="item <?php if($j==0){ echo "active"; } ?>" data-item-id="<?php echo $j; ?>" data-type="custom" data-src="<?php echo esc_url($video->video_url_1); ?>" data-poster="<?php echo esc_url($video->image_url); ?>" data-title="<?php echo esc_html($video->name); ?>">
										<div class="playlist_button">
											<div class="thumb">
												<?php if($video->image_url != ""){ ?>
													<img src="<?php echo esc_url($video->image_url); ?>" alt="<?php echo esc_html($video->name); ?>" />
												<?php } ?>
											</div>
											<span class="item_title"><?php echo esc_html(wp_unslash($video->name)); ?></span>
										</div>
									</li>
									<?php
									break;
								case "youtube":
									?>
									<li class="item <?php if($j==0){ echo "active"; } ?>" data-item-id="<?php echo $j; ?>" data-type="youtube" data-video-id="<?php echo hugeit_vp_get_youtube_thumb_id_from_url($video->video_url_1); ?>" data-poster="<?php echo esc_url($video->image_url); ?>" data-title="<?php echo esc_html($video->name); ?>">
										<div class="playlist_button">
											<div class="thumb">
												<img src="<?php echo esc_url($video->image_url); ?>" alt="<?php echo esc_html($video->name); ?>" />
											</div>
											<span class="item_title"><?php echo esc_html(wp_unslash($video->name)); ?></span>
										</div>
									</li>
									<?php
									break;
								case "vimeo":
									$vid = esc_url($video->video_url_1);
									$vid = explode("/",$vid);
									$vidid=  end($vid);
									if($j==0){
										$autoplay = absint($video_player[0]->autoplay);
									}else{
										$autoplay=0;
									}
									$vidurl="https://player.vimeo.com/video/".$vidid."?player_id=vimeo_player_".$video->id."&color=".$paramssld['video_pl_vimeo_color']."&autoplay=".$autoplay;
									?>
									<li class="item <?php if($j==0){ echo "active"; } ?>" data-item-id="<?php echo $j; ?>" data-type="vimeo" data-video-id="<?php echo $vidid; ?>" data-poster="<?php echo esc_url($video->image_url); ?>">
										<div class="playlist_button">
											<div class="thumb">
												<img src="<?php echo esc_url($video->image_url); ?>" alt="<?php echo esc_html($video->name); ?>" />
											</div>
											<span class="item_title"><?php echo esc_html(wp_unslash($video->name)); ?></span>
										</div>
									</li>
									<?php 
									break;
							}
							$j++;
						}
						?>
					</ul>
				</div>
			</div>
			<?php
			break;
	}
	return ob_get_clean();
    endif;
}
?>