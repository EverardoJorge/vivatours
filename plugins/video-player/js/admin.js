jQuery(document).ready(function () {
	
	jQuery(".update_video_link").on("click tap",function(){
		var button=jQuery(this);
		var data_type=button.parent().parent().data("video-type");
		var link=button.parent().find(".video_link_change").val();
		link.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/)
		if (RegExp.$3.indexOf('youtu') > -1) {
			var type='youtube';
		} else if (RegExp.$3.indexOf('vimeo') > -1) {
			var type='vimeo';
		}
		var data={
			action:"video_player_ajax",
			task:"change_video_link",
			type:type,
			link:link,
		}
		jQuery.post(ajax_object.ajax_url,data,function(response){
			if(response.success){
				button.parent().parent().parent().find(".set_default_thumbnail").data("video-id",response.video_id);
				button.parent().parent().parent().find(".video_preview_container").find("img").attr("src",response.video_image);
				if(data_type!=type){
					switch(type){
						case "youtube":
							button.parent().parent().parent().find(".vimeo_play_center").addClass("yt_play_center").removeClass("vimeo_play_center");
							
							break;
						case "vimeo":
							button.parent().parent().parent().find(".yt_play_center").addClass("vimeo_play_center").removeClass("yt_play_center");
							break;
					}
					button.parent().parent().parent().find(".set_default_thumbnail").data("video-type",type);
					button.parent().parent().parent().find(".image-options").data("video-type",type);
				}
			}else{
				if(response.error){
					console.log("Wrong Video Url!");
					/* Do Nothing :) */
				}
			}
		},"json");
	});
	
	jQuery('#arrows-type input[name="params[slider_navigation_type]"]').change(function(){
		jQuery(this).parents('ul').find('li.active').removeClass('active');
		jQuery(this).parents('li').addClass('active');
	});
	jQuery('input[data-gallery="true"]').bind("gallery:changed", function (event, data) {
		jQuery(this).parent().find('span').html(parseInt(data.value)+"%");
		jQuery(this).val(parseInt(data.value));
	});
	
	jQuery('#gallery-view-tabs li a').click(function(){
		jQuery('#gallery-view-tabs > li').removeClass('active');
		jQuery(this).parent().addClass('active');
		jQuery('#gallery-view-tabs-contents > li').removeClass('active');
		var liID=jQuery(this).attr('href').replace('#','');
		jQuery('#gallery-view-tabs-contents > li[data-id="'+liID+'"').addClass('active');
		jQuery('#adminForm').attr('action',"admin.php?page=Options_gallery_styles&task=save#"+liID);
	});
	
	jQuery('#huge_it_sl_effects').change(function(){
		jQuery('.gallery-current-options').removeClass('active');
		jQuery('#gallery-current-options-'+jQuery(this).val()).addClass('active');
	});
	
	jQuery("#images-list .set_default_thumbnail").on("click",function(){
		var button=jQuery(this);
		var type=button.data("video-type");
		var video_id=button.data("video-id");
		var data={
			action:"video_player_ajax",
			task:"get_video_thumb_from_id",
			type:type,
			video_id: video_id
		}
		jQuery.post(ajax_object.ajax_url,data,function(response){
			if(response.success){
				button.parent().parent().find("img").attr("src",response.image_url);
				button.parent().parent().find(".hidden_image_url").val(response.image_url);
			}
		},"json");
	});
});