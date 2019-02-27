<?php
if (!class_exists('FEUP_User')){
    class FEUP_User {
    	private $Username;
		private $User_ID;

		function __construct($params = array()) {
			global $wpdb, $ewd_feup_user_table_name;

			if (isset($params['ID'])) {
				$this->Username = $wpdb->get_var($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_ID=%d", $params['ID']));
				$this->User_ID = $wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE User_ID=%d", $params['ID']));
			}
			elseif (isset($params['Username'])) {
				$this->Username = $wpdb->get_var($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE Username=%s", $params['Username']));
				$this->User_ID = $wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username=%s", $params['Username']));
			}
			else {
				$CheckCookie = CheckLoginCookie();
				if ($CheckCookie['Username'] != "") {
					$this->Username = $CheckCookie['Username'];
					$this->User_ID = $wpdb->get_var($wpdb->prepare("SELECT User_ID FROM $ewd_feup_user_table_name WHERE Username=%s", $this->Username));
				}
			}
    	}

		function Get_User_Name_For_ID($id = null) {
			global $wpdb, $ewd_feup_user_table_name;

			if(!$id) {
				return null;
			}

			return $wpdb->get_var($wpdb->prepare("SELECT Username FROM $ewd_feup_user_table_name WHERE User_ID=%d", $id));
		}

		function Get_Field_Value_For_ID($Field, $id) {
			global $wpdb, $ewd_feup_user_fields_table_name;

			if(!$Field || !$id) {
				return null;
			}
			$Value = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_Name=%s AND User_ID=%d", $Field, $id));

			return $Value;
		}

		function Get_User_ID() {
			return $this->User_ID;
		}

		function Get_Username() {
			return $this->Username;
		}

		function Get_User_Level_Name() {
			global $wpdb, $ewd_feup_user_table_name, $ewd_feup_levels_table_name;

			$Level_ID = $wpdb->get_var($wpdb->prepare("SELECT Level_ID FROM $ewd_feup_user_table_name WHERE User_ID=%d", $this->User_ID));
			$Level_Name = $wpdb->get_var($wpdb->prepare("SELECT Level_Name FROM $ewd_feup_levels_table_name WHERE Level_ID=%d", $Level_ID));

			return $Level_Name;
		}

		function Get_Field_Value($Field) {
			global $wpdb, $ewd_feup_user_fields_table_name;

			$Value = $wpdb->get_var($wpdb->prepare("SELECT Field_Value FROM $ewd_feup_user_fields_table_name WHERE Field_Name=%s AND User_ID=%d", $Field, $this->User_ID));

			return $Value;
		}

    	function Is_Logged_In() {
			$CheckCookie = CheckLoginCookie();
			if ($this->Username == $CheckCookie['Username'] and isset($this->Username)) {return true;}
			else {return false;}
    	}
	}
}

function EWD_FEUP_Get_All_Users() {
	global $wpdb, $ewd_feup_user_table_name;

	$WP_User_Objects = $wpdb->get_results("SELECT User_ID FROM $ewd_feup_user_table_name");

	foreach ($WP_User_Objects as $User_Object) {
		$User_Array[] = new FEUP_User(array('ID' => $User_Object->User_ID));
	}

	return $User_Array;
}

?>
