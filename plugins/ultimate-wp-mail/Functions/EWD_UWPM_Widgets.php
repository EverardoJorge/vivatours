<?php
class EWD_UWPM_Interests_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'ewd_uwpm_interests_widget', // Base ID
			__('Email Interests Widget', 'ultimate-wp-mail'), // Name
			array( 'description' => __( 'Insert a set of checkboxes with interests for emails', 'ultimate-wp-mail' ), ) // Args
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
		/*if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		echo __( 'Hello, World!', 'ultimate-wp-mail' );*/
		echo do_shortcode("[subscription-interests display_interests='". implode(",", $instance['display_interests']) . "']");
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		$display_interests = ! empty( $instance['display_interests'] ) ? $instance['display_interests'] : array();
		?>
		<p>
		<label for="ewd_uwpm_display_interests_post_categories"><?php _e( 'Post Categories:', 'ultimate-wp-mail' ); ?></label> 
		<input class="widefat" id="ewd_uwpm_display_interests_post_categories" name="<?php echo $this->get_field_name( 'display_interests' ); ?>[]" type="checkbox" value="post_categories" <?php echo (in_array("post_categories", $display_interests) ? 'checked' : ''); ?> >
		</p>
		<p>
		<label for="ewd_uwpm_display_interests_uwpm_categories"><?php _e( 'Email Categories:', 'ultimate-wp-mail' ); ?></label> 
		<input class="widefat" id="ewd_uwpm_display_interests_uwpm_categories" name="<?php echo $this->get_field_name( 'display_interests' ); ?>[]" type="checkbox" value="uwpm_categories" <?php echo (in_array("uwpm_categories", $display_interests) ? 'checked' : ''); ?>>
		</p>
		<p>
		<label for="ewd_uwpm_display_interests_wc_categories"><?php _e( 'WooCommerce Categories:', 'ultimate-wp-mail' ); ?></label> 
		<input class="widefat" id="ewd_uwpm_display_interests_wc_categories" name="<?php echo $this->get_field_name( 'display_interests' ); ?>[]" type="checkbox" value="woocommerce_categories" <?php echo (in_array("woocommerce_categories", $display_interests) ? 'checked' : ''); ?>>
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
		$instance['display_interests'] = ( ! empty( $new_instance['display_interests'] ) ) ? $new_instance['display_interests'] : array();

		return $instance;
	}
}
add_action('widgets_init', create_function('', 'return register_widget("EWD_UWPM_Interests_Widget");'));
 ?>