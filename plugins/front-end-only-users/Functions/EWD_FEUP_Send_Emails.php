<?php
function EWD_FEUP_Send_Email($Options = array()) {
	global $wpdb;
	global $ewd_feup_user_table_name;
	global $ewd_feup_fields_table_name;
	global $ewd_feup_user_fields_table_name;

	$Admin_Email = get_option("EWD_FEUP_Admin_Email");
	$Email_Field = get_option("EWD_FEUP_Email_Field");

	$Username_Is_Email = get_option("EWD_FEUP_Username_Is_Email");

	$Email_Messages_Array = get_option("EWD_FEUP_Email_Messages_Array");
	if (!is_array($Email_Messages_Array)) {$Email_Messages_Array = array();}

	if (!isset($Options['Email_ID']) or !isset($Options['User_ID'])) {return;}

	if (isset($Options['To_Email_Address'])) {$Options['User_Email'] = $Options['To_Email_Address'];}
	else {
		if ($Username_Is_Email == "Yes") {$Options['User_Email'] = $wpdb->get_var($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_ID=%d", $Options['User_ID']));} 
		else {
			$Field_ID = $wpdb->get_var($wpdb->prepare("SELECT Field_ID FROM $ewd_feup_fields_table_name WHERE Field_Name=%s", $Email_Field));
			$Options['User_Email'] = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE User_ID=%d and Field_ID=%d", $Options['User_ID'], $Field_ID));
		}
	}

	if ($Options['Email_ID'] < 0) {
		$Params = array(
			'Email_ID' => $Options['Email_ID'] * -1,
			'feup_user_id' => $Options['User_ID'],
			'Email_Address' => $Options['User_Email']
		);

		if (isset($Options['Reset_Code'])) {$Params['Reset_Code'] = $Options['Reset_Code'];}
		if (isset($Options['Confirmation_Link'])) {$Params['Confirmation_Link'] = $Options['Confirmation_Link'];}
		if (isset($Options['Reset_Email_URL'])) {$Params['Reset_Email_URL'] = $Options['Reset_Email_URL'];}

		$User_WP_ID = $wpdb->get_var($wpdb->prepare("SELECT User_WP_ID FROM $ewd_feup_fields_table_name WHERE User_ID=%d", $Options['User_ID']));
		if($User_WP_ID == '') {
			EWD_URP_Send_Email_To_Non_User($Params);
		}
		else {
			$Params['User_ID'] = $User_WP_ID;
			EWD_UWPM_Email_User($Params);
		}
	}

	if ($Options['User_ID'] == 0) {$Options['User_Email'] = $Options['Test_Email'];}

	if (isset($Options['Reset_Code'])) {
		$Options['Reset_Link_Text'] = "[button link='" . site_url() . "/" . $Options['Reset_Email_URL'] . "?add=" . urlencode($Options['User_Email']) . "&rc=" . $Options['Reset_Code'] . "']" . __("Reset Password", 'front-end-only-users') . "[/button]";
	}

	if (isset($Options['Confirmation_Link'])) {
		$Options['Confirmation_Link_Text'] = "[button link='" . $Options['Confirmation_Link'] . "']" . __("Verify Email", 'front-end-only-users') . "[/button]";
	}

	foreach ($Email_Messages_Array as $Email_Message_Item) {
		if ($Email_Message_Item['ID'] == $Options['Email_ID']) {
			$Message = EWD_FEUP_Substitute_Email_Text(EWD_FEUP_Return_Email_Template($Email_Message_Item), $Options);
			$Subject = EWD_FEUP_Substitute_Email_Text($Email_Message_Item['Subject'], $Options, true);
		}
	}

	$Headers = array('From: ' . $Admin_Email, 'Reply-To: ' . $Admin_Email, 'X-Mailer: PHP/' . phpversion(), 'Content-Type: text/html; charset=UTF-8');
	
	return wp_mail($Options['User_Email'], $Subject, $Message, $Headers); 
}

function EWD_FEUP_Substitute_Email_Text($Text, $Options, $Subject = false) {
	global $wpdb;
	global $ewd_feup_user_table_name;
	global $ewd_feup_fields_table_name;
	global $ewd_feup_user_fields_table_name;

	$User = $wpdb->get_row($wpdb->prepare("SELECT * FROM $ewd_feup_user_table_name WHERE User_ID=%d", $Options['User_ID']));
	$Fields = $wpdb->get_results("SELECT Field_ID, Field_Slug FROM $ewd_feup_fields_table_name");

	$Search_Terms = array(
		"[username]",
		"[user-id]",
		"[join-date]",
		"[reset-password-link]",
		"[reset-code]",
		"[confirmation-link]"
	);
	foreach ($Fields as $Field) {$Search_Terms[] = "[" . $Field->Field_Slug . "]";}
	if (!isset($Options['Reset_Link_Text'])){ $Options['Reset_Link_Text'] = ""; }
	if (!isset($Options['Reset_Code'])){ $Options['Reset_Code'] = ""; }
	if (!isset($Options['Confirmation_Link_Text'])) { $Options['Confirmation_Link_Text'] = "";}
	$Replace_Terms = array(
		$User->Username,
		$User->User_ID,
		$User->User_Date_Created,
		$Options['Reset_Link_Text'],
		$Options['Reset_Code'],
		$Options['Confirmation_Link_Text']
	);
	foreach ($Fields as $Field) {
		$Field_Value = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_ID=%d AND User_ID=%d", $Field->Field_ID, $Options['User_ID']));
		$Replace_Terms[] = $Field_Value;
	}

	$Email_Text = str_replace($Search_Terms, $Replace_Terms, $Text);

	return EWD_FEUP_Replace_Email_Content($Email_Text, $Subject);
}

function EWD_FEUP_Return_Email_Template($Email_Message_Item) {
  	$Message_Title = $Email_Message_Item['Subject'];
  	$Message_Content = EWD_FEUP_Replace_Email_Content(stripslashes($Email_Message_Item['Message']));

	$Email_Reminder_Background_Color = get_option("EWD_FEUP_Email_Reminder_Background_Color");
	$Email_Reminder_Inner_Color = get_option("EWD_FEUP_Email_Reminder_Inner_Color");
	$Email_Reminder_Text_Color = get_option("EWD_FEUP_Email_Reminder_Text_Color");
	$Email_Reminder_Button_Background_Color = get_option("EWD_FEUP_Email_Reminder_Button_Background_Color");
	$Email_Reminder_Button_Text_Color = get_option("EWD_FEUP_Email_Reminder_Button_Text_Color");
	$Email_Reminder_Button_Background_Hover_Color = get_option("EWD_FEUP_Email_Reminder_Button_Background_Hover_Color");
	$Email_Reminder_Button_Text_Hover_Color = get_option("EWD_FEUP_Email_Reminder_Button_Text_Hover_Color");

  $Message =   <<< EOT
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml" style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
  <head>
  <meta name="viewport" content="width=device-width" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>$Message_Title</title>


  <style type="text/css">

	.body-wrap {
		background-color: {$Email_Reminder_Background_Color} !important;
	}
	.btn-primary {
		background-color: {$Email_Reminder_Button_Background_Color} !important;
		border-color: $Email_Reminder_Button_Background_Color !important;
		color: {$Email_Reminder_Button_Text_Color} !important;
	}
	.btn-primary:hover {
		background-color: {$Email_Reminder_Button_Background_Hover_Color} !important;
		border-color: $Email_Reminder_Button_Background_Hover_Color !important;
		color: {$Email_Reminder_Button_Text_Hover_Color} !important;
	}
	.main {
		background: $Email_Reminder_Inner_Color !important;
		color: $Email_Reminder_Text_Color;
	}

  img {
  max-width: 100%;
  }
  body {
  -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em;
  }
  body {
  background-color: #f6f6f6;
  }
  @media only screen and (max-width: 640px) {
    body {
      padding: 0 !important;
    }
    h1 {
      font-weight: 800 !important; margin: 20px 0 5px !important;
    }
    h2 {
      font-weight: 800 !important; margin: 20px 0 5px !important;
    }
    h3 {
      font-weight: 800 !important; margin: 20px 0 5px !important;
    }
    h4 {
      font-weight: 800 !important; margin: 20px 0 5px !important;
    }
    h1 {
      font-size: 22px !important;
    }
    h2 {
      font-size: 18px !important;
    }
    h3 {
      font-size: 16px !important;
    }
    .container {
      padding: 0 !important; width: 100% !important;
    }
    .content {
      padding: 0 !important;
    }
    .content-wrap {
      padding: 10px !important;
    }
    .invoice {
      width: 100% !important;
    }
  }
  </style>
  </head>

  <body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; width: 100% !important; height: 100%; line-height: 1.6em; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6">

  <table class="body-wrap" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; background-color: #f6f6f6; margin: 0;" bgcolor="#f6f6f6"><tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
  		<td class="container" width="600" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; display: block !important; max-width: 600px !important; clear: both !important; margin: 0 auto;" valign="top">
  			<div class="content" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; max-width: 600px; display: block; margin: 0 auto; padding: 20px;">
  				<table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; border-radius: 3px; background-color: #fff; margin: 0; border: 1px solid #e9e9e9;" bgcolor="#fff"><tr style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-wrap" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 20px;" valign="top">
  					<meta itemprop="name" content="Please Review" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;" /><table width="100%" cellpadding="0" cellspacing="0" style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;">
              $Message_Content
        </div>
  		</td>
  		<td style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0;" valign="top"></td>
  	</tr></table></body>
  </html>

EOT;

  return $Message;
}

function EWD_FEUP_Replace_Email_Content($Message_Start, $Subject = false) {
  if (strpos($Message_Start, '[footer]') === false and !$Subject) {$Message_Start .= '</table></td></tr></table>';}

  $Replace = array('[section]', '[/section]', '[footer]', '[/footer]', '[/button]');
  $ReplaceWith = array(
    '<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top">',
    '</td></tr>',
    '</table></td></tr></table><div class="footer" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; width: 100%; clear: both; color: #999; margin: 0; padding: 20px;"><table width="100%" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="aligncenter content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 12px; vertical-align: top; color: #999; text-align: center; margin: 0; padding: 0 0 20px;" align="center" valign="top">',
    '</td></tr></table></div>',
    '</a></td></tr>'
  );
  $Message = str_replace($Replace, $ReplaceWith, $Message_Start);
  $Final_Message = EWD_FEUP_Replace_Email_Links($Message);

  return $Final_Message;
}


function EWD_FEUP_Replace_Email_Links($Message) {
	$Pattern = "/\[button link=\'(.*?)\'\]/";

	preg_match_all($Pattern, $Message, $Matches);

	$Replace = '<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; margin: 0;"><td class="content-block" itemprop="handler" itemscope itemtype="http://schema.org/HttpActionHandler" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; vertical-align: top; margin: 0; padding: 0 0 20px;" valign="top"><a href="INSERTED_LINK" class="btn-primary" itemprop="url" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #348eda; margin: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;">';
	$Result = preg_replace($Pattern, $Replace, $Message);

	if (is_array($Matches[1])) {
		foreach ($Matches[1] as $Link) {
			$Pos = strpos($Result, "INSERTED_LINK");
			if ($Pos !== false) {
			    $NewString = substr_replace($Result, $Link, $Pos, 13);
			    $Result = $NewString;
			}
		}
	}

	return $Result;
}
?>