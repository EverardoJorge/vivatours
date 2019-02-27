<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function hugeit_vp_html_show_video_players($rows, $pageNav, $sort, $cat_row){
	?>
    <script language="javascript">
		function ordering(name,as_or_desc)
		{
			document.getElementById('asc_or_desc').value=as_or_desc;
			document.getElementById('order_by').value=name;
			document.getElementById('admin_form').submit();
		}
		function saveorder()
		{
			document.getElementById('saveorder').value="save";
			document.getElementById('admin_form').submit();

		}
		function listItemTask(this_id,replace_id)
		{
			document.getElementById('oreder_move').value=this_id+","+replace_id;
			document.getElementById('admin_form').submit();
		}
		function doNothing() {
			var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
			if( keyCode == 13 ) {

				if(!e) var e = window.event;

				e.cancelBubble = true;
				e.returnValue = false;

				if (e.stopPropagation) {
						e.stopPropagation();
						e.preventDefault();
				}
			}
		}
	</script>

<div class="wrap">
	<div id="poststuff">
		<div id="video_players-list-page">
			<form method="post"  onkeypress="doNothing()" action="admin.php?page=hugeit_vp_video_player" id="admin_form" name="admin_form">
				<?php $url_add_new = wp_nonce_url('admin.php?page=hugeit_vp_video_player&task=add_cat', 'hugeit_vp_add_new_', 'hugeit_vp_add_cat_data'); ?>
			<h2>Huge-IT Video Albums
				<a onclick="window.location.href='<?php echo $url_add_new; ?>'" class="add-new-h2" >Add New Video Album</a>
			</h2>
			<?php
			$serch_value='';
			if(isset($_POST['serch_or_not'])) {if($_POST['serch_or_not']=="search"){ $serch_value=esc_html(stripslashes($_POST['search_events_by_title'])); }else{$serch_value="";}}
			$serch_fields='<div class="alignleft actions"">
				<label for="search_events_by_title" style="font-size:14px">Filter: </label>
					<input type="text" name="search_events_by_title" value="'.$serch_value.'" id="search_events_by_title" onchange="clear_serch_texts()">
			</div>
			<div class="alignleft actions">
				<input type="button" value="Search" onclick="document.getElementById(\'page_number\').value=\'1\'; document.getElementById(\'serch_or_not\').value=\'search\';
				 document.getElementById(\'admin_form\').submit();" class="button-secondary action">
				 <input type="button" value="Reset" onclick="window.location.href=\'admin.php?page=hugeit_vp_video_player\'" class="button-secondary action">
			</div>';

			?>
			<table class="wp-list-table widefat fixed pages" style="width:95%">
				<thead>
				 <tr>
					<th scope="col" id="id" style="width:30px" ><span>ID</span><span class="sorting-indicator"></span></th>
					<th scope="col" id="name" style="width:85px" ><span>Name</span><span class="sorting-indicator"></span></th>
					<th scope="col" id="prod_count"  style="width:75px;" ><span>Videos</span><span class="sorting-indicator"></span></th>
					<th style="width:40px">Delete</th>
				 </tr>
				</thead>
				<tbody>
				 <?php
				 $trcount=1;
				  for($i=0; $i<count($rows);$i++){
					$trcount++;

					  $uncat=$rows[$i]->par_name;
					if(isset($rows[$i]->prod_count))
						$pr_count=$rows[$i]->prod_count;
					else
						$pr_count=0;
					?>
					<tr <?php if($trcount%2==0){ echo 'class="has-background"';}?>>
						<td><?php
							$id = absint($rows[$i]->id);
							echo $id; ?></td>
						<?php $url_edit =  wp_nonce_url('admin.php?page=hugeit_vp_video_player&task=edit_cat&id=' . $id, 'edit_cat_' . $id, 'hugeit_vp_edit_cat'); ?>
						<td><a href="<?php echo $url_edit; ?>"><?php echo esc_html(stripslashes($rows[$i]->name)); ?></a></td>
						<td>(<?php if(!($pr_count)){echo '0';} else{ echo $rows[$i]->prod_count;} ?>)</td>
						<?php $url_del = wp_nonce_url('admin.php?page=hugeit_vp_video_player&task=remove_cat&id=' . $id, 'remove_cat_' .$id, 'hugeit_vp_remove_cat'); ?>
						<td><a  href="<?php echo $url_del; ?>">Delete</a></td>
					</tr>
				 <?php } ?>
				</tbody>
			</table>
			 <input type="hidden" name="oreder_move" id="oreder_move" value="" />
			 <input type="hidden" name="asc_or_desc" id="asc_or_desc" value="<?php if(isset($_POST['asc_or_desc'])) echo esc_attr($_POST['asc_or_desc']);?>"  />
			 <input type="hidden" name="order_by" id="order_by" value="<?php if(isset($_POST['order_by'])) echo esc_attr($_POST['order_by']);?>"  />
			 <input type="hidden" name="saveorder" id="saveorder" value="" />
			</form>
		</div>
	</div>
</div>
    <?php
}
function hugeit_vp_html_edit_video_player($ord_elem, $row, $cat_row, $rowim, $rowsld, $paramssld, $rowsposts, $rowsposts8, $postsbycat) {
    $protocol = is_ssl() ? 'https:' : 'http:';
	$row_id = absint($row->id);
	if(isset($_GET["addslide"])){
		if($_GET["addslide"] == 1){
			$url = wp_nonce_url( 'admin.php?page=hugeit_vp_video_player&id='. $row_id .'&task=apply', 'save_data_' . $row_id, 'hugeit_vp_save_data' );
			$url = html_entity_decode($url);
			header('Location: ' . $url);
		}
	}
?>
<script type="text/javascript">
jQuery("#save-buttom").click(function(){
	hugeit_vp_submitbutton("apply");
})
function hugeit_vp_submitbutton(pressbutton, id)
{
	if(!document.getElementById('name').value){
	alert("Name is required.");
	return;
	}

	if (!jQuery('#' + id).data('delete-slide')) {
		document.getElementById("adminForm").action=document.getElementById("adminForm").action+"&task="+pressbutton;
	}
	document.getElementById("adminForm").submit();
}

jQuery(function() {
	jQuery( "#images-list" ).sortable({
	  stop: function() {
			jQuery("#images-list > li").removeClass('has-background');
			count=jQuery("#images-list > li").length;
			for(var i=0;i<=count;i+=2){
					jQuery("#images-list > li").eq(i).addClass("has-background");
			}
			jQuery("#images-list > li").each(function(){
				jQuery(this).find('.order_by').val(jQuery(this).index());
			});
	  },
	  revert: true
	});
	});
</script>
<!-- GENERAL PAGE, ADD IMAGES PAGE -->

<div class="wrap">
	<?php $url_save = wp_nonce_url('admin.php?page=hugeit_vp_video_player&id=' . $row_id, 'save_data_' . $row_id, 'hugeit_vp_save_data' ); ?>
<form action="<?php echo $url_save; ?>" method="post" name="adminForm" id="adminForm">

	<div id="poststuff" >
	<div id="video_player-header">
		<ul id="video_players-list">
			<?php
			foreach($rowsld as $rowsldires){
				$rowsldires_id = absint($rowsldires->id);
				if($rowsldires_id != $row_id){
					$url_edit = wp_nonce_url('admin.php?page=hugeit_vp_video_player&task=edit_cat&id=' . $rowsldires_id, 'edit_cat_' . $rowsldires_id , 'hugeit_vp_edit_cat');
				?>
					<li>
						<a href="#" onclick="window.location.href='<?php echo $url_edit; ?>'" ><?php echo sanitize_text_field($rowsldires->name); ?></a>
					</li>
				<?php
				}
				else{ ?>
					<li class="active" style="background-image:url(<?php echo plugins_url('../images/edit.png', __FILE__) ;?>)">
						<input class="text_area" onfocus="this.style.width = ((this.value.length + 1) * 8) + 'px'" type="text" name="name" id="name" maxlength="250" value="<?php echo esc_html(stripslashes($row->name));?>" />
					</li>
				<?php
				}
			}
		?>
			<li class="add-new">
				<?php $url_add_cat = wp_nonce_url('admin.php?page=hugeit_vp_video_player&amp;task=add_cat', 'hugeit_vp_add_cat_', 'hugeit_vp_add_cat_data'); ?>
				<a onclick="window.location.href='<?php echo $url_add_cat; ?>'">+</a>
			</li>
		</ul>
		</div>
		<div id="post-body" class="metabox-holder columns-2">
			<!-- Content -->
			<div id="post-body-content">

			<?php add_thickbox(); ?>
				<div id="post-body">
					<div id="post-body-heading">
						<h3>Videos</h3>
	<script>
		jQuery(document).ready(function($){
			jQuery(".wp-media-buttons-icon").click(function() {
				jQuery(".attachment-filters").css("display","none");
			});
		  var _custom_media = true,
			  _orig_send_attachment = wp.media.editor.send.attachment;


		  jQuery('.huge-it-newuploader .button').click(function(e) {
			var send_attachment_bkp = wp.media.editor.send.attachment;

			var button = jQuery(this);
			var id = button.attr('id').replace('_button', '');
			_custom_media = true;

			jQuery("#"+id).val('');
			wp.media.editor.send.attachment = function(props, attachment){
			  if ( _custom_media ) {
				 jQuery("#"+id).val(attachment.url+';;;'+jQuery("#"+id).val());
				 jQuery("#save-buttom").click();
			  } else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			  };
			}

			wp.media.editor.open(button);

			return false;
		  });

		  jQuery('.add_media').on('click', function(){
			_custom_media = false;

		  });
		});
	</script>
			<input type="hidden" name="imagess" id="_unique_name" />
			<span class="wp-media-buttons-icon"></span>
			<div class="huge-it-newuploader uploader add-new-image">
				<input type="button" class="button button-primary wp-media-buttons-icon" name="_unique_name_button" id="_unique_name_button" value="Upload Video" />
			</div>
			<a href="admin.php?page=hugeit_vp_video_player&task=video_player_video&id=<?php echo absint($_GET['id']); ?>&TB_iframe=1&width=783&height=610" class="button button-primary add-video-slide thickbox"  id="slideup3s" value="iframepop">
				<span class="wp-media-buttons-icon"></span>Add Video From Url
			</a>
			</div>
					<ul id="images-list">
					<?php

					function get_youtube_id_from_url($url){
						if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
							return $match[1];
						}
					}

					$i=2;
					foreach ($rowim as $key=>$rowimages){
						$rowimages_id = absint($rowimages->id);
						?>
					<?php if($rowimages->sl_type == ''){$rowimages->sl_type = 'video';} ?>
						<?php
						$url_remove = wp_nonce_url('admin.php?page=hugeit_vp_video_player&task=edit_cat&id=' . $row_id . '&removeslide=' . $rowimages_id, 'remove_slide_' . $row_id, 'hugeit_vp_edit_cat');
						$url_remove = html_entity_decode($url_remove);
						?>
						<?php switch ($rowimages->sl_type) {
						case 'video':	?>
						<li <?php if($i%2==0) {echo "class='has-background'";} $i++; ?>>
						<input class="order_by" type="hidden" name="order_by_<?php echo $rowimages_id; ?>" value="<?php echo esc_attr($rowimages->ordering); ?>" />
							<div class="image-container">
								<?php $path_site = plugins_url("../images", __FILE__); ?>
								<?php if($rowimages->image_url == ''){ ?>
								<img src="<?php echo $path_site; ?>/noimage.jpg" />
								<?php } else { ?>
								<img src="<?php echo esc_attr($rowimages->image_url); ?>" />
								<?php } ?>
								<div>
										<script>
										jQuery(document).ready(function($){
										  var _custom_media = true,
											  _orig_send_attachment = wp.media.editor.send.attachment;

										  jQuery('.huge-it-editnewuploader .button<?php echo $rowimages_id; ?>').click(function(e) {
											var send_attachment_bkp = wp.media.editor.send.attachment;
											var button = jQuery(this);
											var id = button.attr('id').replace('_button', '');
											_custom_media = true;
											wp.media.editor.send.attachment = function(props, attachment){
											  if ( _custom_media ) {
												jQuery("#"+id).val(attachment.url);
												jQuery("#save-buttom").click();
											  } else {
												return _orig_send_attachment.apply( this, [props, attachment] );
											  };
											}
											wp.media.editor.open(button);
											return false;
										  });

										  jQuery('.add_media').on('click', function(){
											_custom_media = false;
										  });
											jQuery(".huge-it-editnewuploader").click(function() {
											});
												jQuery(".wp-media-buttons-icon").click(function() {
												jQuery(".wp-media-buttons-icon").click(function() {
												jQuery(".media-menu .media-menu-item").css("display","none");
												jQuery(".media-menu-item:first").css("display","block");
												jQuery(".separator").next().css("display","none");
												jQuery('.attachment-filters').val('image').trigger('change');
												jQuery(".attachment-filters").css("display","none");

											});
										});

										});
								function deleteproject<?php echo $rowimages_id; ?>(){
								   jQuery('#adminForm').attr('action', '<?php echo $url_remove; ?>');
								}
										</script>
								<input type="hidden" name="imagess<?php echo $rowimages_id; ?>" id="_unique_name<?php echo $rowimages_id; ?>" value="<?php echo esc_attr($rowimages->image_url); ?>" />
								<span class="wp-media-buttons-icon"></span>
								<div class="editimgbutton_block huge-it-editnewuploader uploader button<?php echo $rowimages_id; ?> add-new-image">
									<span class="edit_image_info">Set Custom Thumbnail</span>
									<input type="button" class="editimgbutton button<?php echo $rowimages_id; ?> wp-media-buttons-icon" name="_unique_name_button<?php echo $rowimages_id; ?>" id="_unique_name_button<?php echo $rowimages_id; ?>" value="" />
								</div>
									</div>
							</div>
							<div data-video-type="video" class="image-options">
								<div class="description-block">
									<label for="titleimage<?php echo $rowimages_id; ?>">Title:</label>
									<input  class="text_area" type="text" id="titleimage<?php echo $rowimages_id; ?>" name="titleimage<?php echo $rowimages_id; ?>" id="titleimage<?php echo $rowimages_id; ?>"  value="<?php echo esc_html(stripslashes($rowimages->name)); ?>">
								</div>
								<div class="description-block">
									<label for="for_video_1<?php echo $rowimages_id; ?>">Url:</label>
									<input style="padding-right:20px;" type="text" name="for_video_1<?php echo $rowimages_id; ?>" id="for_video_1<?php echo $rowimages_id; ?>" value="<?php echo esc_html(stripslashes($rowimages->video_url_1)); ?>" />
									<div class="huge-it-editnewuploader uploader button<?php echo $rowimages_id; ?>">
										<input type="button" class="button<?php echo $rowimages_id; ?> wp-media-buttons-icon editimageicon" name="for_video_1_button<?php echo $rowimages_id; ?>" id="for_video_1_button<?php echo $rowimages_id; ?>" value="" />
									</div>
								</div>
								<div class="remove-image-container">
									<a onclick="deleteproject<?php echo $rowimages_id; ?>(); hugeit_vp_submitbutton('apply', 'remove_image<?php echo $rowimages_id; ?>');" id="remove_image<?php echo $rowimages_id; ?>" class="button remove-image" data-delete-slide="1">X</a>
								</div>
							</div>
						<div class="clear"></div>
						</li>
						<?php break;
						case 'youtube':
?>
						<li <?php if($i%2==0){echo "class='has-background'";}$i++; ?>>
						<input class="order_by" type="hidden" name="order_by_<?php echo $rowimages_id; ?>" value="<?php echo esc_attr($rowimages->ordering); ?>" />
							<div class="image-container">
								<?php $path_site = plugins_url("../images", __FILE__); ?>
								<?php if($rowimages->image_url == ''){ ?>
								<img src="<?php echo $path_site; ?>/noimage.jpg" />
								<?php }else{ ?>
								<img src="<?php echo esc_attr($rowimages->image_url); ?>" />
								<?php } ?>
								<div>
										<script>
										jQuery(document).ready(function($){
											var _custom_media = true,
											  _orig_send_attachment = wp.media.editor.send.attachment;

											jQuery('.huge-it-editnewuploader .button<?php echo $rowimages_id; ?>').click(function(e) {
												var send_attachment_bkp = wp.media.editor.send.attachment;
												var button = jQuery(this);
												var id = button.attr('id').replace('_button', '');
												_custom_media = true;
												wp.media.editor.send.attachment = function(props, attachment){
												  if ( _custom_media ) {
													jQuery("#"+id).val(attachment.url);
													jQuery("#save-buttom").click();
												  } else {
													return _orig_send_attachment.apply( this, [props, attachment] );
												  };
												}

												wp.media.editor.open(button);
												return false;
											});

											jQuery('.add_media').on('click', function(){
												_custom_media = false;
											});
											jQuery(".huge-it-editnewuploader").click(function() {
											});
											jQuery(".wp-media-buttons-icon").click(function() {
												jQuery(".wp-media-buttons-icon").click(function() {
													jQuery(".media-menu .media-menu-item").css("display","none");
													jQuery(".media-menu-item:first").css("display","block");
													jQuery(".separator").next().css("display","none");
													jQuery('.attachment-filters').val('image').trigger('change');
													jQuery(".attachment-filters").css("display","none");
												});
											});


										});
										function deleteproject<?php echo $rowimages_id; ?>() {
										   jQuery('#adminForm').attr('action', '<?php echo $url_remove; ?>');
										}
									</script>
									<input class="hidden_image_url" type="hidden" name="imagess<?php echo $rowimages_id; ?>" id="_unique_name<?php echo $rowimages_id; ?>" value="<?php echo esc_attr($rowimages->image_url); ?>" />
									<span class="wp-media-buttons-icon"></span>
									<div class="editimgbutton_block huge-it-editnewuploader uploader button<?php echo $rowimages_id; ?> add-new-image">
										<span class="edit_image_info">Set Custom Thumbnail</span>
										<input type="button" class="editimgbutton button<?php echo $rowimages_id; ?> wp-media-buttons-icon" name="_unique_name_button<?php echo $rowimages_id; ?>" id="_unique_name_button<?php echo $rowimages_id; ?>" value="" />
									</div>
								</div>
								<div class="default_thumbnail">
									<div class="button set_default_thumbnail" data-video-type="youtube" data-video-id="<?php echo hugeit_vp_get_youtube_thumb_id_from_url($rowimages->video_url_1); ?>">Set Default Thumbnail</div>
								</div>
							</div>

							<div data-video-type="youtube" class="image-options">
								<div class="description-block">
									<label for="titleimage<?php echo $rowimages_id; ?>">Title:</label>
									<input  class="text_area" type="text" id="titleimage<?php echo $rowimages_id; ?>" name="titleimage<?php echo $rowimages_id; ?>" id="titleimage<?php echo $rowimages_id; ?>"  value="<?php echo esc_html(stripslashes($rowimages->name)); ?>">
								</div>
								<div class="description-block">
									<label for="for_video_1<?php echo $rowimages_id; ?>">Url:</label>
									<input class="youtube_link video_link_change" type="text" name="for_video_1<?php echo $rowimages_id; ?>" id="for_video_1<?php echo $rowimages_id; ?>" value="<?php echo $rowimages->video_url_1; ?>" />
									<div class="button update_video_link">Update</div>
								</div>
								<div class="link-block">
									<input type="hidden" name="for_video_2<?php echo $rowimages_id; ?>" id="for_video_2<?php echo $rowimages_id; ?>" value="<?php echo esc_html(stripslashes($rowimages->video_url_2)); ?>" />
								</div>
								<div class="video_preview_container">
									<?php
										$video_thumb_url=hugeit_vp_get_youtube_thumb_id_from_url($rowimages->video_url_1); ?>
										<img src="<?php echo $protocol . "//img.youtube.com/vi/".$video_thumb_url."/mqdefault.jpg" ?>" alt="" />
										<div class="yt_play_center"></div>
								</div>
								<div class="remove-image-container">
									<a onclick="deleteproject<?php echo $rowimages_id; ?>(); hugeit_vp_submitbutton('apply', 'remove_image<?php echo $rowimages_id; ?>');" id="remove_image<?php echo $rowimages_id; ?>" class="button remove-image" data-delete-slide="1">X</a>
								</div>
							</div>

						<div class="clear"></div>
						</li>
						<?php
						break;
						case "vimeo":
							?>
						<li <?php if($i%2==0){echo "class='has-background'";}$i++; ?>>
						<input class="order_by" type="hidden" name="order_by_<?php echo $rowimages_id; ?>" value="<?php echo esc_attr($rowimages->ordering); ?>" />
							<div class="image-container">
								<?php $path_site = plugins_url("../images", __FILE__); ?>
								<?php if($rowimages->image_url == ''){ ?>
								<img src="<?php echo $path_site; ?>/noimage.jpg" />
								<?php } else { ?>
								<img src="<?php echo esc_attr($rowimages->image_url); ?>" />
								<?php } ?>
								<div>
										<script>
										jQuery(document).ready(function($){
											var _custom_media = true,
											  _orig_send_attachment = wp.media.editor.send.attachment;

											  jQuery('.huge-it-editnewuploader .button<?php echo $rowimages_id; ?>').click(function(e) {
												var send_attachment_bkp = wp.media.editor.send.attachment;
												var button = jQuery(this);
												var id = button.attr('id').replace('_button', '');
												_custom_media = true;
												wp.media.editor.send.attachment = function(props, attachment){
												  if ( _custom_media ) {
													jQuery("#"+id).val(attachment.url);
													jQuery("#save-buttom").click();
												  } else {
													return _orig_send_attachment.apply( this, [props, attachment] );
												  };
												}

												wp.media.editor.open(button);
												return false;
											  });

											  jQuery('.add_media').on('click', function(){
												_custom_media = false;
											  });
												jQuery(".huge-it-editnewuploader").click(function() {
												});
													jQuery(".wp-media-buttons-icon").click(function() {
													jQuery(".wp-media-buttons-icon").click(function() {
													jQuery(".media-menu .media-menu-item").css("display","none");
													jQuery(".media-menu-item:first").css("display","block");
													jQuery(".separator").next().css("display","none");
													jQuery('.attachment-filters').val('image').trigger('change');
													jQuery(".attachment-filters").css("display","none");

												});
											});
											jQuery("#album_name").on("keyup change",function(){
												jQuery("#name").val(jQuery(this).val());
											})
											jQuery("#name").on("keyup change",function(){
												jQuery("#album_name").val(jQuery(this).val());
											})

										});
										function deleteproject<?php echo $rowimages_id; ?>() {
										   jQuery('#adminForm').attr('action', '<?php echo $url_remove; ?>');
										}

									</script>
									<input class="hidden_image_url" type="hidden" name="imagess<?php echo $rowimages_id; ?>" id="_unique_name<?php echo $rowimages_id; ?>" value="<?php echo esc_attr($rowimages->image_url); ?>" />
									<span class="wp-media-buttons-icon"></span>
									<div class="editimgbutton_block huge-it-editnewuploader uploader button<?php echo $rowimages_id; ?> add-new-image">
										<span class="edit_image_info">Set Custom Thumbnail</span>
										<input type="button" class="editimgbutton button<?php echo $rowimages_id; ?> wp-media-buttons-icon" name="_unique_name_button<?php echo $rowimages_id; ?>" id="_unique_name_button<?php echo $rowimages_id; ?>" value="" />
									</div>
								</div>
								<div class="default_thumbnail">
									<?php
									$vid_id = explode( "/", $rowimages->video_url_1);
									$vidid=end($vid_id);
									?>
									<div class="button set_default_thumbnail" data-video-type="vimeo" data-video-id="<?php echo $vidid; ?>">Set Default Thumbnail</div>
								</div>
							</div>
							<div data-video-type="vimeo" class="image-options">
								<div class="description-block">
									<label for="titleimage<?php echo $rowimages_id; ?>">Title:</label>
									<input  class="text_area" type="text" id="titleimage<?php echo $rowimages_id; ?>" name="titleimage<?php echo $rowimages_id; ?>" id="titleimage<?php echo $rowimages_id; ?>"  value="<?php echo esc_html(stripslashes($rowimages->name)); ?>">
								</div>
								<div class="description-block">
									<label for="for_video_1<?php echo $rowimages_id; ?>">Url:</label>
									<input class="vimeo_link video_link_change" type="text" name="for_video_1<?php echo $rowimages_id; ?>" id="for_video_1<?php echo $rowimages_id; ?>" value="<?php echo esc_html(stripslashes($rowimages->video_url_1)); ?>" />
									<div class="button update_video_link">Update</div>
								</div>
								<div class="link-block">
									<input type="hidden" name="for_video_2<?php echo $rowimages_id; ?>" id="for_video_2<?php echo $rowimages_id; ?>" value="<?php echo esc_html(stripslashes($rowimages->video_url_2)); ?>" />
								</div>
								<div class="video_preview_container">
									<?php
											$vidid = explode( "/", $rowimages->video_url_1);
											$vidid=end($vidid);
											$hash=file_get_contents( $protocol . "//vimeo.com/api/v2/video/".$vidid.".php");
											$vidurl="https://player.vimeo.com/video/".$vidid;
											$hash = unserialize($hash);
											$video_thumb_url=$hash[0]['thumbnail_large'];
											?>
											<img src="<?php echo esc_url($video_thumb_url); ?>" alt="" />
											<div class="vimeo_play_center"></div>

								</div>
								<div class="remove-image-container">
									<a onclick="deleteproject<?php echo $rowimages_id; ?>(); hugeit_vp_submitbutton('apply', 'remove_image<?php echo $rowimages_id; ?>');" id="remove_image<?php echo $rowimages_id; ?>" class="button remove-image" data-delete-slide="1">X</a>
								</div>
							</div>
							<div class="clear"></div>
						</li>
<?php
						break;
						} ?>
					<?php } ?>
					</ul>
				</div>

			</div>

			<!-- SIDEBAR -->
			<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<div id="video_player-unique-options" class="postbox">
					<h3 class="hndle"><span>Select The Video Player View</span></h3>
					<ul id="video_player-unique-options-list">
					<div id="video_player-current-options-3" class="video_player-current-options">
					<ul id="slider-unique-options-list">
						<li>
							<label for="album_name">Player Name</label>
							<input type="text" id="album_name" name="album_name" value="<?php echo esc_html(stripslashes($row->name)); ?>" />
						</li>
						<li>
							<label for="album_single">Player Type</label>
							<select name="album_single" id="album_single">
									<option <?php if($row->album_single == 'album'){ echo 'selected'; } ?>  value="album">Album</option>
									<option <?php if($row->album_single == 'single'){ echo 'selected'; } ?>   value="single">Single</option>
							</select>
						</li>
						<li>
							<label for="album_playlist_layout">Playlist Layout</label>
							<select name="album_playlist_layout" id="album_playlist_layout">
								<option value="left" <?php if($row->layout=="left"){ echo 'selected="selected"'; } ?>>Left</option>
								<option value="right" <?php if($row->layout=="right"){ echo 'selected="selected"'; } ?>>Right</option>
								<option value="bottom" <?php if($row->layout=="bottom"){ echo 'selected="selected"'; } ?>>Bottom</option>
							</select>
						</li>
						<li>
							<label for="album_autoplay">Autoplay</label>
							<select name="album_autoplay" id="album_autoplay" >
								<option value="1" <?php if($row->autoplay=="1"){ echo 'selected="selected"'; } ?>>On</option>
								<option value="0" <?php if($row->autoplay=="0"){ echo 'selected="selected"'; } ?>>Off</option>
							</select>
						</li>
						<li>
							<label for="album_width">Video Width(px)</label>
							<input type="number" name="album_width" id="album_width" min="250" value="<?php echo esc_attr($row->width); ?>" />
						</li>
					</ul>
					</div>
					</ul>
						<div id="major-publishing-actions">
							<div id="publishing-action">
								<input type="button" onclick="hugeit_vp_submitbutton('apply')" value="Save Video Player" id="save-buttom" class="button button-primary button-large">
							</div>
							<div class="clear"></div>
						</div>
					</div>
					<div id="video_player-shortcode-box" class="postbox shortcode ms-toggle">
					<h3 class="hndle"><span>Usage</span></h3>
					<div class="inside">
						<ul>
							<li rel="tab-1" class="selected">
								<h4>Shortcode</h4>
								<p>Copy &amp; paste the shortcode directly into any WordPress post or page.</p>
								<textarea class="full" readonly="readonly">[huge_it_video_player id="<?php echo $row_id; ?>"]</textarea>
							</li>
							<li rel="tab-2">
								<h4>Template Include</h4>
								<p>Copy &amp; paste this code into a template file to include the slideshow within your theme.</p>
								<textarea class="full" readonly="readonly">&lt;?php echo do_shortcode("[huge_it_video_player id='<?php echo $row_id; ?>']"); ?&gt;</textarea>
							</li>
						</ul>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="task" value="" />
</form>
</div>
<?php
}

function hugeit_vp_html_video_player_video(){
?>
	<style>
		html.wp-toolbar {
			padding:0px !important;
		}
		#wpadminbar,#adminmenuback,#screen-meta, .update-nag,#dolly {
			display:none;
		}
		#wpbody-content {
			padding-bottom:30px;
		}
		#adminmenuwrap {display:none !important;}
		.auto-fold #wpcontent, .auto-fold #wpfooter {
			margin-left: 0px;
		}
		#wpfooter {display:none;}
		iframe {height:250px !important;}
		#TB_window {height:250px !important;}
	</style>
	<script type="text/javascript">
		jQuery(document).ready(function() {

		jQuery('.huge-it-insert-video-button').click(function(e){
		    e.preventDefault();
        });
        <?php
			if(isset($_GET["closepop"])){
			if($_GET["closepop"] == 1){ ?>
					jQuery("#closepopup").click();
					self.parent.location.reload();
			<?php	}	} ?>
			jQuery('.updated').css({"display":"none"});

            jQuery('#huge_it_add_video_input').change(function(){

                if (jQuery(this).val().indexOf("youtube") >= 0){
                    jQuery('#add-video-popup-options > div').removeClass('active');
                    jQuery('#add-video-popup-options  .youtube').addClass('active');
                }else if (jQuery(this).val().indexOf("vimeo") >= 0){
                    jQuery('#add-video-popup-options > div').removeClass('active');
                    jQuery('#add-video-popup-options  .vimeo').addClass('active');
                }else {
                    jQuery('#add-video-popup-options > div').removeClass('active');
                    jQuery('#add-video-popup-options  .error-message').addClass('active');
                }
            })

			jQuery("#huge_it_add_video_input").on("change keyup",function(){
				var url=jQuery(this).val();
				var addVideoSaveNonce = jQuery(this).parents('#huge_it_slider_add_videos_wrap').data('add-video-save-nonce');
				var data={
					action:"video_player_ajax",
					nonce: addVideoSaveNonce,
					task: "get_video_meta_from_url",
					url: url,
				};

                jQuery.post('<?php echo admin_url( 'admin-ajax.php' ); ?>',data,function(response){
					if(response.success){
						jQuery("#show_title").val(response.title);
						jQuery("#show_description").val(response.image_url);

                        if(jQuery("#add-video-popup-options .thumb_block").length){
							jQuery("#add-video-popup-options .thumb_block").remove();
							jQuery("#add-video-popup-options").append("<div class='thumb_block'><img class='"+response.type+"' src='"+response.image_url+"' alt='"+response.title+"' /><div class='"+response.type+"_play'></div></div>");
						}else{
							jQuery("#add-video-popup-options").append("<div class='thumb_block'><img class='"+response.type+"' src='"+response.image_url+"' alt='"+response.title+"' /><div class='"+response.type+"_play'></div></div>");
						}
                        jQuery("#insert_video").submit();
					}else{
						if(response.fail){
							//do nothing
						}
					}
				},"json");
			});
		});
	</script>
	<a id="closepopup"  onclick=" parent.eval('tb_remove()')" style="display:none;"> [X] </a>
	<div id="huge_it_slider_add_videos">
		<?php $hugeit_vp_add_video_save_nonce = wp_create_nonce('hugeit_vp_add_video_save'); ?>
		<div id="huge_it_slider_add_videos_wrap" data-add-video-save-nonce="<?php echo $hugeit_vp_add_video_save_nonce; ?>">
			<h2>Add Video From Url (Youtube/Vimeo Or Custom Video)</h2>
			<div class="control-panel">
				<?php
					$id = absint($_GET['id']);
					$url = wp_nonce_url(
					'admin.php?page=hugeit_vp_video_player&task=video_player_video&id=' . $id . '&closepop=1',
					'add_video_' . $id,
					'hugeit_vp_add_video'
				); ?>
				<form id="insert_video" method="post" action="<?php echo $url; ?>" >                    
                        <input type="text" id="huge_it_add_video_input" name="show_video_url_1" placeholder="http://" />
                        <button class='save-slider-options button-primary huge-it-insert-video-button' id='huge-it-insert-video-button'>Insert Video Url</button>                    
                    <div id="add-video-popup-options">
                        <div>
                            <div>
                                <label for="show_title">Title:</label>
                                <div>
                                    <input id="show_title" name="show_title" value="" type="text" />
                                </div>
                            </div>
                            <div>
                                <label for="show_video_url_2">Image Url:</label>
                                <input type="text" id="show_description" name="show_video_image_url" />
                            </div>
                        </div>
                    </div>
				</form>
			</div>
		</div>
	</div>
<?php
}
