<?php

function EWD_FEUP_Return_Pointers() {
  $pointers = array();
  
  $pointers['tutorial-one'] = array(
    'title'     => "<h3>" . 'Front End Users Intro' . "</h3>",
    'content'   => "<div><p>Thanks for installing FEUP! These 6 slides will help get you started using the plugin.</p></div><div class='ewd-feup-pointer-count'><p>1 of 6 - <span class='feup-skip-all-tutorial-pop-ups'>Skip All</span></p></div>",
    'anchor_id' => '.Header',
    'edge'      => 'top',
    'align'     => 'left',
    'nextTab'   => 'Fields',
    'width'     => '320',
    'where'     => array( 'toplevel_page_EWD-FEUP-options') // <-- Please note this
  );
  
  $pointers['tutorial-two'] = array(
    'title'     => "<h3>" . 'Create Fields' . "</h3>",
    'content'   => "<div><p>In the 'Fields' tab, you can create fields for your users to fill in. There are many different types, and they can be dragged and dropped in the right-hand table to rearrange their order.</p></div><div class='ewd-feup-pointer-count'><p>2 of 6 - <span class='feup-skip-all-tutorial-pop-ups'>Skip All</span></p></div>",
    'anchor_id' => '#Fields_Menu',
    'edge'      => 'top',
    'align'     => 'left',
    'nextTab'   => 'Users',
    'width'     => '320',
    'where'     => array( 'toplevel_page_EWD-FEUP-options') // <-- Please note this
  );

  $pointers['tutorial-three'] = array(
    'title'     => "<h3>" . 'Manage Users' . "</h3>",
    'content'   => "<div><p>In the 'Users' tab, you can create new users. You can also view any current users in the right-hand table, and click on a user to see their details.</p></div><div class='ewd-feup-pointer-count'><p>3 of 6 - <span class='feup-skip-all-tutorial-pop-ups'>Skip All</span></p></div>",
    'anchor_id' => '#Users_Menu',
    'edge'      => 'top',
    'align'     => 'left',
    'nextTab'   => 'Options',
    'width'     => '320',
    'where'     => array( 'toplevel_page_EWD-FEUP-options') // <-- Please note this
  );

  $pointers['tutorial-four'] = array(
    'title'     => "<h3>" . 'Set Options' . "</h3>",
    'content'   => "<div><p>The 'Options' tab has options to help manage your site, including:<ul><li>Setting a minimum password length</li><li>Importing WordPress users</li><li>Payment options and more!</li></ul></p></div><div class='ewd-feup-pointer-count'><p>4 of 6 - <span class='feup-skip-all-tutorial-pop-ups'>Skip All</span></p></div>",
    'anchor_id' => '#Options_Menu',
    'edge'      => 'top',
    'align'     => 'left',
    'nextTab'   => 'Dashboard',
    'width'     => '320',
    'where'     => array( 'toplevel_page_EWD-FEUP-options') // <-- Please note this
  );
  
  $pointers['tutorial-five'] = array(
    'title'     => "<h3>" . 'Use Shortcodes' . "</h3>",
    'content'   => "<div><p>FEUP  has a dozen shortcodes that can be added to pages. You can get help using them by typing '[login help', '[register help', etc. in any WordPress page. For a complete shortcode list, <a href='https://wordpress.org/plugins/front-end-only-users/faq/'>visit the plugin FAQ page</a>.</p></div><div class='ewd-feup-pointer-count'><p>5 of 6 - <span class='feup-skip-all-tutorial-pop-ups'>Skip All</span></p></div>",
    'anchor_id' => '#menu-pages',
    'edge'      => 'top',
    'align'     => 'left',
    'nextTab'   => 'Dashboard',
    'width'     => '320',
    'where'     => array( 'toplevel_page_EWD-FEUP-options') // <-- Please note this
  );

  $pointers['tutorial-six'] = array(
    'title'     => "<h3>" . 'Need More Help?' . "</h3>",
    'content'   => "<div><p><a href='https://wordpress.org/support/view/plugin-reviews/front-end-only-users?filter=5'>Help us spread the word with a 5 star rating!</a><br><br>We've got a number of videos on how to use the plugin:<br /><iframe width='560' height='315' src='https://www.youtube.com/embed/amTX0VzOdco?list=PLEndQUuhlvSolfe-rIpI3eK_TmfeEDPeH' frameborder='0' allowfullscreen></iframe></p></div><div class='ewd-feup-pointer-count'><p>6 of 6</p></div>",
    'anchor_id' => '#wp-admin-bar-site-name',
    'edge'      => 'top',
    'align'     => 'left',
    'nextTab'   => 'Dashboard',
    'width'     => '600',
    'where'     => array( 'toplevel_page_EWD-FEUP-options') // <-- Please note this
  );
  
  return $pointers; 
}

?>