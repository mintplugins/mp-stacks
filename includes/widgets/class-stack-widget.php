<?php
/**
 * Extends MP_CORE_Widget to create custom widget class.
 */
class MP_STACKS_Widget extends MP_CORE_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'mp_stacks_widget', // Base ID
			'MP Stacks', // Name
			array( 'description' => __( 'Display A Stack from MP Stacks.', 'mp_stacks' ), ) // Args
		);

		//enqueue scripts defined in MP_CORE_Widget
		add_action( 'admin_enqueue_scripts', array( $this, 'mp_widget_enqueue_scripts' ) );

		$this->_form = array (
			"field1" => array(
				'field_id' 			=> 'title',
				'field_title' 	=> __('Title:', 'mp_stacks'),
				'field_description' 	=> NULL,
				'field_type' 	=> 'textbox',
			),
			"field1" => array(
				'field_id' 			=> 'stack_id',
				'field_title' 	=> __('Select a stack:', 'mp_stacks'),
				'field_description' 	=> NULL,
				'field_type' 	=> 'select',
				'field_select_values' 	=> mp_core_get_all_terms_by_tax('mp_stacks'),
			),
		);

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		global $mp_stacks_widget_stacks;

		extract( $args );
		$title = apply_filters( 'mp_stacks_widget_title', isset($instance['title']) ? $instance['title'] : '' );

		/**
		 * Widget Start and Title
		 */
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		/**
		 * Before Hook
		 */
		do_action('mp_stacks_widget_start');

		/**
		 * Widget Body
		 */
		echo mp_stack( $instance['stack_id'] );

		/**
		 * After Hook
		 */
		 do_action('mp_stacks_widget_end');

		/**
		 * Widget End
		 */
		echo $after_widget;

	}
}

function mp_stacks_register_widget() {
	register_widget( "MP_STACKS_Widget" );
}
add_action( 'register_sidebar', 'mp_stacks_register_widget' );
