<?php
/**
 * @file
 * Defines the Listing Filter widget.
 */
class RexListingFilterWidget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'rex_listing_filter_widget', // Base ID
			'REX Listing Filter', // Name
			array( 'description' => __( 'Listing Filter Widget', 'realtor-express' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget. (controller)
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
	  extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		
		$min_price = !empty( $_GET['rex_filter_min_price'] ) ? (int) $_GET['rex_filter_min_price'] : null;
		$min_price = is_int( $min_price ) ? $min_price : null;
		$max_price = !empty( $_GET['rex_filter_max_price'] )  ? (int) $_GET['rex_filter_max_price'] : null;
		$max_price = is_int( $max_price ) ? $max_price : null;
		
		// Bedrooms and baths.
		$select_plus_array = RexListing::generatePlusArray( 10 );
		$bedrooms = array_key_exists( $_GET['rex_filter_bedrooms'], $select_plus_array ) ? $_GET['rex_filter_bedrooms'] : null;
		$baths = array_key_exists( $_GET['rex_filter_baths'], $select_plus_array ) ? $_GET['rex_filter_baths'] : null;
		
		// Types and styles.
		$types_array = RexListing::generateTaxonomySelectArray( 'rex_listing_type' );
		$type = array_key_exists( $_GET['rex_filter_type'], $types_array ) ? $_GET['rex_filter_type'] : null;
		$style_array = RexListing::generateTaxonomySelectArray( 'rex_listing_style' );
		$style = array_key_exists( $_GET['rex_filter_style'], $style_array ) ? $_GET['rex_filter_style'] : null;
		
		// Status
		$status_array = RexListing::getStatusArray( true );
		$status = array_key_exists( $_GET['rex_filter_status'], $status_array ) ? $_GET['rex_filter_status'] : null;
		
		// Load listing filter form.
		require_once REX_PLUGIN_DIR . '/views/RexWidgetListingFilterView.php';
		
		echo $after_widget;
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
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Filter Listings', 'realtor-express' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

} // class Foo_Widget