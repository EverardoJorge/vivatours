<?php

$FEUP = new FEUP_User;

/******************************************
* DIFFERENT MENU FOR LOGGED IN USERS
******************************************/

// Test if a user is logged in
if ($FEUP->Is_Logged_In()) {
	// Display the menu named "Logged In Menu" if they are (created under "Appearances" -> "Menus" of the WordPress Admin)
	wp_nav_menu(array('menu' => 'Logged In Menu'));
}
else {
	// Display the menu named "Logged Out Menu" if they aren't
	wp_nav_menu(array('menu' => 'Logged Out Menu'));
}


/******************************************
* DISPLAY POST BASED ON A USER'S INTERESTS
******************************************/

// Get the field value for the "Interests" field
$Interests_String = $FEUP->Get_Field_Value("Interests");

// Turn the string containing a user's interests into an array
$Interests = explode(",", $Interests_String);

// Check if lighting is in the array, and display posts in the "Lighting" category if it is
if (in_array("Lighting", $Interests)) {
  // this user is interested in lighting, display the lighting posts category here
}


/*****************************************
* THE FOUR HELPER FUNCTIONS
*****************************************/

// Retrieve the user's ID
$User_ID = $FEUP->Get_User_ID();

// Retrieve the user's username
$Username = $FEUP->Get_Username();

// Retrieve the user's username in a different way
$Same_Username = $FEUP->Get_User_Name_For_ID($User_ID);

// Retrieve the value for the field named "Interests", for the user with ID equal to $User_ID
$Field_Value = $FEUP->Get_Field_Value_For_ID("Interests", $User_ID);


?>