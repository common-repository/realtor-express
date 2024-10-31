<?php
/**
 * @file
 * HTML Helper class
 */

class RexHtml
{
  /**
   * Creates a simple HTML select list
   * 
   * @param array $items
   * @param array $attributes
   * 	Define html attributes such as class, id, etc... (e.g. array( 'class' => 'class-select' ))
   * @param boolean $is_assoc
   * 	If the array is not associative, the item value will be used as the option value.
   * @param string $default
   * 	Name of default option
   */
  public static function generateSelectList( $items, $default = NULL, $attributes = array(), $is_assoc = FALSE  )
  {
    $attrs = '';
    if ( ! empty( $attributes ) ) {
      foreach ( $attributes as $key => $value ) {
        $attrs .= $key . '="' . esc_attr( $value ) . '" '; 
      }
    }
    
    $output = "<select $attrs>";
    
    if ( ! is_array( $items ) || empty ( $items ) ) {
      $output .= '<option value="_none">- None -</option>';
    }
    else {
      foreach ( $items as $key => $value ) {
        if ( $is_assoc == FALSE ) {
          $key = $value;
        }
        $output .= "<option ";
        if ( $key == $default || $value == $default ) {
          $output .= "selected='selected' ";
        }
        $key = esc_attr( $key );
        $value = esc_html( $value );
        $output .= "<option value='$key'>$value</option>";
      }
    }
    
    $output .= '</select>';
    
    echo $output;
  }
  
  /**
  * Displays a formatted WordPress message
  *
  * @param string or array of strings $message
  * @param string $type
  *	Options include 'error' and 'updated'
  */
  public static function show_wp_message( $message, $type = 'error' ) {
    ?>
    	<div id="message" class="<?php echo $type;?>">
    			<?php 
    			  if ( is_array( $message ) ) {
    			    echo '<ul>';
    			    foreach ( $message as $string ) {
    			      echo '<li>' . $string . '</li>';
    			    }
    			    echo '</ul>';
    			  }
    			  else {
    			    echo '<p>' . $message . '</p>';
    			  }
    			?>
    	</div>
    <?php
    }
}