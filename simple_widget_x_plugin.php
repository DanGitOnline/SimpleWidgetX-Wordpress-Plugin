<?php
/*
Plugin Name:  Simple Widget X
Plugin URI:   
Description:  
Version:      1.0
Author:       Dan Kirkwood
Author URI:   
License:      GPLv3
License URI:  https://www.gnu.org/licenses/gpl.html

*/

$simple_widget_x_id = 0;

if ( !is_admin() ) { add_action('wp_enqueue_scripts', 'simple_widget_x_scripts'); }

function simple_widget_x_scripts() {
    //wp_enqueue_style('simple_widget_x_style', plugins_url('/simple_widget_x.css', __FILE__));
    //wp_enqueue_script('simple_widget_x_script', plugins_url('/simple_widget_x.js', __FILE__)); 
}

function simple_widget_x($atts = [], $content = null, $tag = '') {
    $atts = array_change_key_case((array)$atts, CASE_LOWER);
    
    $wporg_atts = shortcode_atts([
        'caption' => '',
        'value' => '',
    ], $atts, $tag);

    global $simple_widget_x_id;
    $simple_widget_x_id++;
    $id = $simple_widget_x_id;

    $content = 
        '<div ' .
        'id="simpleWidgetX' . $id . '" ' .
        'style="' .
        'display: inline-block!important; ' .
        'padding: 8px; ' .
        'background-color: steelblue; ' .
        'color: white; ' .
        'border: outset dimgray 1px; ' .
        '" ' .
        '>' . 
        ((!empty($wporg_atts['caption'])) ? 'Caption: ' . $wporg_atts['caption'] : '(No Caption)') .
        '... ' . 
        ((!empty($wporg_atts['value'])) ? 'Value: ' . $wporg_atts['value'] : '(No Value)') .
        '...' .
        '</div>';

    return $content;
}

add_shortcode( 'simple_widget_x',  'simple_widget_x' );

function simple_widget_x_register_widget() { register_widget( 'simple_widget_x_widget' ); }

add_action(  'widgets_init',  'simple_widget_x_register_widget' );

class simple_widget_x_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'simple_widget_x_widget',
			__('simple widget x widget', 'simple_widget_x_widget_domain'),
            array( 'description' => __( 'simple widget x widget', 'simple_widget_x_widget_domain' ) )
		);
	}

	public function widget( $args, $instance ) {
		$caption = apply_filters( 'widget_caption', $instance['caption'] );
        $value = apply_filters( 'widget_value', $instance['value'] );
        
        if ( empty($caption) ) $caption = '';
        if ( empty($value) ) $value = '';
        
        $atts = [];
        $atts['caption'] = $caption;
        $atts['value'] = $value;

        echo $args['before_widget'];
        echo simple_widget_x( $atts );
		echo $args['after_widget'];
	}

	public function form( $instance ) {
    
        if ( isset( $instance[ 'caption' ] ) )
			$caption = $instance[ 'caption' ];
		else
            $caption = __( '', 'simple_widget_x_widget_domain' );

        if ( isset( $instance[ 'value' ] ) )
			$value = $instance[ 'value' ];
		else
            $value = __( '', 'simple_widget_x_widget_domain' );

        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'caption' ); ?>"><?php _e( 'Caption:' ); ?></label>
        <input 
            class="widefat" 
            id="<?php echo $this->get_field_id( 'caption' ); ?>" 
            name="<?php echo $this->get_field_name( 'caption' ); ?>" 
            type="text" 
            placeholder="(No Caption)"
            value="<?php echo esc_attr( $caption ); ?>" 
            />
        </p>
        <p>
        <label for="<?php echo $this->get_field_id( 'value' ); ?>"><?php _e( 'Value:' ); ?></label>
        <input 
            class="widefat" 
            id="<?php echo $this->get_field_id( 'value' ); ?>" 
            name="<?php echo $this->get_field_name( 'value' ); ?>" 
            type="text" 
            placeholder="(No Value)"
            value="<?php echo esc_attr( $value ); ?>" 
            />
        </p>
        <?php
	}

	public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['caption'] = 
            ( ! empty( $new_instance['caption'] ) ) ? strip_tags( $new_instance['caption'] ) : '';
        $instance['value'] = 
            ( ! empty( $new_instance['value'] ) ) ? strip_tags( $new_instance['value'] ) : '';
		return $instance;
	}
}
