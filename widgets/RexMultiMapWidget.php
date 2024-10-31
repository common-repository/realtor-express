<?php
/**
 * @file
 * Defines the Google Map widget.
 */
class RexMultiMapWidget extends WP_Widget {

  public function __construct() {
    parent::__construct(
	 		'rex_multi_map_widget', // Base ID
			'REX Multi Map', // Name
			array( 'description' => __( 'Multi Map Widget', 'realtor-express' ), ) // Args
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
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;

		$locations_json_array = RexListing::getListingsLocationsJSON( array("LIMIT" => $instance['limit'], 'ORDER' => $instance['order'] ) );
		?>
		<script>
    	window.onload = initMaps;
    	function initMapWidget() {
    		updateMultiMap(<?php echo $locations_json_array; ?>, 'rex-multi-map-widget');
    	}
		</script>
		<div id="rex-multi-map-widget"><img src="<?php echo REX_PLUGIN_URL . '/images/ajax-loader-white-bg.gif'; ?>" alt='Ajax Loader' /> <?php _e('Loading Map', 'realtor-express'); ?></div>
		<?php
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
		$instance['limit'] = strip_tags( $new_instance['limit'] );
		$instance['order'] = strip_tags( $new_instance['order'] );

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
	  require_once REX_PLUGIN_DIR . '/includes/RexHtml.php';

	  if ( isset( $instance[ 'title' ] ) ) {
	    $title = $instance[ 'title' ];
	  }
	  else {
	    $title = __( 'All Listings', 'realtor-express' );
	  }
	  if ( isset( $instance[ 'limit' ] ) ) {
	    $limit = $instance[ 'limit' ];
	  }
	  else {
	    $limit = 50;
	  }
	  if ( isset( $instance[ 'order' ] ) ) {
	    $order = $instance[ 'order' ];
	  }
	  else {
	    $order = 'price_desc';
	  }
	  
	  $order_array = array(
	    'price_desc' => 'Price (high to low)',
	    'price_asc' => 'Price (low to high)',
	  );

	  echo '<p>' . __('This widget displays a Google Map of multiple listings.', 'realtor-express') . '</p>';
	  ?>
			<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'realtor-express' ); ?></label>
			<input class="" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit Listings:', 'realtor-express' ); ?></label>
		<?php
		  RexHtml::generateSelectList( range(10, 500, 10), $limit, array( 'name' => $this->get_field_name( 'limit' ) ) );
		?>
			</p>
			<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Listings Order:', 'realtor-express' ); ?></label>
		<?php
		  RexHtml::generateSelectList( $order_array, $order, array( 'name' => $this->get_field_name( 'order' ) ), true );
		?>
			</p>
		<?php

  }
}