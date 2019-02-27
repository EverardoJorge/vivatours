<?php

/**
 * Class Hustle_Module_Widget
 */
class Hustle_Module_Widget extends WP_Widget {

	/**
	 * @var string Widget Id
	 */
	const WIDGET_ID = "hustle_module_widget";


	/**
	 * Registers the widget
	 */
	public function __construct() {
		parent::__construct(
			self::WIDGET_ID,
			__( 'Hustle', Opt_In::TEXT_DOMAIN ),
			array( 'description' => __( 'A widget to add Hustle Embeds and Social Sharing.', Opt_In::TEXT_DOMAIN ) )
		);
	}



	/**
	 *
	 * Front-end display of widget.
	 *
	 * @param array $args
	 * @param array $instance Previously saved values from database.
	 * @return string
	 */
	public function widget( $args, $instance ) {
		// phpcs:disable
		if( empty( $instance['module_id'] ) ){

			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			}
			echo esc_attr__("Select Module", Opt_In::TEXT_DOMAIN);

			echo $args['after_widget'];

			return;
		}



		$module = Hustle_Module_Model::instance()->get( $instance['module_id'] );

		// if( !$module->settings->widget->show_in_front() ){
			// echo $args['before_widget'];
			// echo $args['after_widget'];
			// return;
		// }

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}

		$widget_css_class = ( 'social_sharing' === $module->module_type )
			? Hustle_Module_Front::SSHARE_WIDGET_CSS_CLASS
			: Hustle_Module_Front::WIDGET_CSS_CLASS;

		//unique id for the same optins on one page
		$unique_id = wp_rand();
		?>

		<div class="<?php echo esc_attr( $widget_css_class ); ?> module_id_<?php echo esc_attr( $instance['module_id'] ); ?>" data-type="widget" data-id="<?php echo esc_attr( $instance['module_id'] ); ?>" data-unique_id="<?php echo esc_attr( $unique_id ); ?>"></div>
		<?php

		echo $args['after_widget'];
		// phpcs:enable
	}


	/**
	 *
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 * @param array $instance Previously saved values from database.
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
		if( empty( $instance['module_id'] ) )
			$instance['module_id'] = -1;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_attr__( 'Title:', Opt_In::TEXT_DOMAIN ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'module_id' ) ); ?>"><?php echo esc_attr__( 'Select Module:', Opt_In::TEXT_DOMAIN ); ?></label>
			<select name="<?php echo esc_attr( $this->get_field_name( 'module_id' ) ); ?>" id="hustle_module_id">
				<option value=""><?php echo esc_attr__("Select Module", Opt_In::TEXT_DOMAIN); ?></option>
				<?php
					$types = array( 'embedded', 'social_sharing' );
					foreach( Hustle_Module_Collection::instance()->get_embed_id_names($types) as $mod ) :
					$module = Hustle_Module_Model::instance()->get( $mod->module_id );
						//if( $module->settings->widget->show_in_front() ):
					?>
					<option <?php selected( $instance['module_id'],  $mod->module_id); ?> value="<?php echo esc_attr( $mod->module_id ); ?>"><?php echo esc_attr( $mod->module_name ); ?></option>

					<?php
						//endif;
						endforeach;
					?>
			</select>
		</p>
		<?php
	}


	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] =  ! empty( $new_instance['title'] )  ? wp_strip_all_tags( $new_instance['title'] ) : '';
		$instance['module_id'] =  ! empty( $new_instance['module_id'] )  ? wp_strip_all_tags( $new_instance['module_id'] ) : '';

		return $instance;
	}

}
