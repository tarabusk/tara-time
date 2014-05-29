<?php
/**
Plugin Name: Tara Time 1
Description: Display current time of a specified city. Using Time Zone API https://www.worldweatheronline.com/time-zone-api.aspx 
Version: 1.0.0
Author: tarabusk.net
Author URI: http://tarabusk.net
License: This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

 */

function TaraTimeLoad() {
    register_widget( 'TaraTimeWidget' );
}
add_action( 'widgets_init', 'TaraTimeLoad' );


class TaraTimeWidget extends WP_Widget {
    function __construct() {
		parent::__construct(
			'tara_time1', // Base ID
			__( 'Specified Coutry Time', 'tara-time' ), // Name
			array( 'description' => __( ' !! Display current time in a specified city', 'tara-time' ), )
		); 
		
		wp_register_style( 'tara-time-css', plugins_url( 'css/tara-time.css', __FILE__ ) );
		wp_enqueue_style( 'tara-time-style' );
		wp_enqueue_script( 'tara-time-js', plugins_url( '/js/tara-time.js', __FILE__ ) );
    } 
	


    /* Show the widget. */
    function widget( $args, $instance ) {
		extract( $args );
		
		$widget_id = $args['widget_id'];

		//Widget settings
		$title = apply_filters( 'widget_title', $instance['title'] );
		$city = $instance['city'];
		$time_format = $instance[ 'time_format' ];
		$date_format = $instance[ 'date_format' ];
		

		echo $before_widget;
		if ( ! empty( $title ) )
		echo $args['before_title'] . $title . $args['after_title'];
		function TaraTimeDisplay($city){	
			if(isset($city) ){
				$city = $city;
			}else{
				$city = 'San Francisco';
			}
			$url_post = "http://api.worldweatheronline.com/free/v1/tz.ashx?key=encsf52dch9hs9s2ugytcsch&q=".urlencode($city)."&format=json";
			
			$time_feed = file_get_contents($url_post); 
			$time_feed = json_decode($time_feed);
			foreach ($time_feed->data->time_zone as $item) {
			  $coutrytime = $item->localtime;				  
			}
			return $coutrytime;			
		}
		$LaDate =  TaraTimeDisplay($city);
		
		$date = new DateTime($LaDate);
		$heure = $date->format("H");
		$minute= $date->format("i"); 
		?>
		<div class="tara-datetime">
			<div class="tara-date"></div>
			<div class="tara-time"></div>
		</div>
		<script type="text/javascript">
			TaraTime_UpdateTime('<?php echo $heure; ?>','<?php echo $minute; ?>','<?php echo $widget_id; ?>', '<?php echo $time_format; ?>', '<?php echo $date_format; ?>');
		</script>

		<?php
		echo $after_widget;
    }
    
    /* Show the widget's settings. */
    function form( $instance ) { ?>
		<?php //Set up some default widget settings.
		$defaults = array(
		'title' => '',
		'city' => 'San Francisco',
		'time_format' => '12-hour-seconds',
		'date_format' => 'long',
		
		);
		$time_formats = array(
		"none" => "None",
		"12-hour" => date("g:i A", current_time( 'timestamp', 0 ) ),
		"12-hour-seconds" => date("g:i:s A", current_time( 'timestamp', 0 ) ),
		"24-hour" => date("G:i", current_time( 'timestamp', 0 ) ),
		"24-hour-seconds" => date("G:i:s", current_time( 'timestamp', 0 ) ),
		);
		$date_formats = array(
		"none" => "None",
		"short" => date( "n/j/Y", current_time( 'timestamp', 0 ) ),
		"european" => date( "j/n/Y", current_time( 'timestamp', 0 ) ),
		"medium" => date( "M j Y", current_time( 'timestamp', 0 ) ),
		"long" => date( "F j, Y", current_time( 'timestamp', 0 ) ),
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	    if ( isset( $instance[ 'title' ] ) ) {$title = $instance[ 'title' ];}
		else {$title = __( 'San Francisco', 'tara_time1' );} ?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p> <?php
		if ( isset( $instance[ 'city' ] ) ) {$city = $instance[ 'city' ];}
		else {$city = __( 'San Francisco', 'tara_time1' );} ?>
		<p>
		<label for="<?php echo $this->get_field_id( 'city' ); ?>"><?php _e( 'City:' ); ?></label> 
		<input class="" id="<?php echo $this->get_field_id( 'city' ); ?>" name="<?php echo $this->get_field_name( 'city' ); ?>" type="text" value="<?php echo esc_attr( $city ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'time_format' ); ?>"><?php _e( 'Time Format:', 'tara_time1' ); ?></label>
		<select id="<?php echo $this->get_field_id( 'time_format' ); ?>" name="<?php echo $this->get_field_name( 'time_format' ); ?>" class="widefat">
		
		<?php foreach( $time_formats as $key => $value ) {
		$selected = ( $instance[ 'time_format' ] == $key ) ? 'selected="selected"' : '';
		echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
		} ?>
		</select>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'date_format' ); ?>"><?php _e( 'Date Format:', 'tara_time1' ); ?></label>
		<select id="<?php echo $this->get_field_id( 'date_format' ); ?>" name="<?php echo $this->get_field_name( 'date_format' ); ?>" class="widefat">
		
		<?php foreach( $date_formats as $key => $value ) {
		$selected = ( $instance[ 'date_format' ] == $key ) ? 'selected="selected"' : '';
		echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
		} ?>
		</select>
		</p>

		
		<?php
    }
    
    //Save the widget's settings.
    function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance[ 'title' ] = $new_instance[ 'title' ];
		$instance[ 'city' ] = $new_instance[ 'city' ];
		$instance[ 'time_format' ] = $new_instance[ 'time_format' ];
		$instance[ 'date_format' ] = $new_instance[ 'date_format' ];
		
		return $instance;
    }
}
?>