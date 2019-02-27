<?php
class EWD_FEUP_Login_Logout_Toggle_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'ewd_feup_login_logout_toggle_widget', // Base ID
			__('FEUP Login/Logout Toggle', 'front-end-only-users'), // Name
			array( 'description' => __( 'Insert a login form or logout button, depending on login status', 'EWD_FEUP' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ($instance['title'] != "") {
			echo "<div class='ewd-feup-widget-title'>" . $instance['title'] . "</div>";
		}
		echo do_shortcode("[login-logout-toggle login_redirect_page='". $instance['login_redirect_page'] . "' logout_redirect_page='" . $instance['logout_redirect_page'] . "']");
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Form Heading', 'EWD_FEUP' );
		$login_redirect_page = ! empty( $instance['login_redirect_page'] ) ? $instance['login_redirect_page'] : __( 'Login Redirect Page', 'EWD_FEUP' );
		$logout_redirect_page = ! empty( $instance['logout_redirect_page'] ) ? $instance['logout_redirect_page'] : __( 'Logout Redirect Page', 'EWD_FEUP' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Form Heading:', 'EWD_FEUP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'login_redirect_page' ); ?>"><?php _e( 'Login Redirect Page:', 'EWD_FEUP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'login_redirect_page' ); ?>" name="<?php echo $this->get_field_name( 'login_redirect_page' ); ?>" type="text" value="<?php echo esc_attr( $login_redirect_page ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'logout_redirect_page' ); ?>"><?php _e( 'Logout Redirect Page:', 'EWD_FEUP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'logout_redirect_page' ); ?>" name="<?php echo $this->get_field_name( 'logout_redirect_page' ); ?>" type="text" value="<?php echo esc_attr( $logout_redirect_page ); ?>">
		</p>
		<?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['login_redirect_page'] = ( ! empty( $new_instance['login_redirect_page'] ) ) ? strip_tags( $new_instance['login_redirect_page'] ) : '';
		$instance['logout_redirect_page'] = ( ! empty( $new_instance['logout_redirect_page'] ) ) ? strip_tags( $new_instance['logout_redirect_page'] ) : '';

		return $instance;
	}
}
add_action('widgets_init', create_function('', 'return register_widget("EWD_FEUP_Login_Logout_Toggle_Widget");'));

class EWD_FEUP_User_Data_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'ewd_feup_user_data_widget', // Base ID
			__('FEUP User Data', 'front-end-only-users'), // Name
			array( 'description' => __( 'Insert a piece of user data, along with accompanying text.', 'EWD_FEUP' ), ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		echo do_shortcode("[user-data field_name='". $instance['field_name'] . "' plain_text='" . $instance['plain_text'] . "' before_text='" . $instance['before_text'] . "' after_text='" . $instance['after_text'] . "']");
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		global $wpdb;
		global $ewd_feup_fields_table_name;
		$Fields = $wpdb->get_results("SELECT Field_Name FROM $ewd_feup_fields_table_name");

		$field_name = ! empty( $instance['field_name'] ) ? $instance['field_name'] : __( 'Username', 'EWD_FEUP' );
		$plain_text = ! empty( $instance['plain_text'] ) ? $instance['plain_text'] : __( 'Yes', 'EWD_FEUP' );
		$before_text = ! empty( $instance['before_text'] ) ? $instance['before_text'] : __( '', 'EWD_FEUP' );
		$after_text = ! empty( $instance['after_text'] ) ? $instance['after_text'] : __( '', 'EWD_FEUP' );
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'field_name' ); ?>"><?php _e( 'Field to Display:', 'EWD_FEUP' ); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id( 'field_name' ); ?>" name="<?php echo $this->get_field_name( 'field_name' ); ?>">
			<option value='Account_Expiry' <?php if ($instance['field_name'] == "Account_Expiry") {echo "selected=selected";} ?> >Account Expiry</option>
			<?php 
				foreach ($Fields as $Field) {
					echo "<option value='" . $Field->Field_Name ."' ";
					if ($Field->Field_Name == $instance['field_name']) {echo "selected=selected";}
					echo ">" . $Field->Field_Name . "</option>";
				} 
			?>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'plain_text' ); ?>"><?php _e( 'Plain Text:', 'EWD_FEUP' ); ?></label> 
		<select class="widefat" id="<?php echo $this->get_field_id( 'plain_text' ); ?>" name="<?php echo $this->get_field_name( 'plain_text' ); ?>">
			<option value='Yes' <?php if ($instance['plain_text'] == "Yes") {echo "selected=selected";} ?> >Yes</option>
			<option value='No' <?php if ($instance['plain_text'] == "No") {echo "selected=selected";} ?> >No</option>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'before_text' ); ?>"><?php _e( 'Before Text:', 'EWD_FEUP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'before_text' ); ?>" name="<?php echo $this->get_field_name( 'before_text' ); ?>" type="text" value="<?php echo esc_attr( $before_text ); ?>">
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'after_text' ); ?>"><?php _e( 'After Text:', 'EWD_FEUP' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'after_text' ); ?>" name="<?php echo $this->get_field_name( 'after_text' ); ?>" type="text" value="<?php echo esc_attr( $after_text ); ?>">
		</p>
		<?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['field_name'] = ( ! empty( $new_instance['field_name'] ) ) ? strip_tags( $new_instance['field_name'] ) : '';
		$instance['plain_text'] = ( ! empty( $new_instance['plain_text'] ) ) ? strip_tags( $new_instance['plain_text'] ) : '';
		$instance['before_text'] = ( ! empty( $new_instance['before_text'] ) ) ? strip_tags( $new_instance['before_text'] ) : '';
		$instance['after_text'] = ( ! empty( $new_instance['after_text'] ) ) ? strip_tags( $new_instance['after_text'] ) : '';

		return $instance;
	}
}
add_action('widgets_init', create_function('', 'return register_widget("EWD_FEUP_User_Data_Widget");'));

?>