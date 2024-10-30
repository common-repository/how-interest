<?php


/*	
		
	Copyright 2011  howlin.ie  (email : hello@howlin.ie)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	
*/





/**
 * widget_init hook
 * 
 */
add_action('widgets_init', 'load_how_interest_widget');


//------------------------------------------------------------------------







/**
 * Ajax and such
 */
wp_enqueue_scripts('jquery');
wp_enqueue_script('how_ajax', plugin_dir_url( __FILE__ ) . 'script.js', array( 'jquery' ));
wp_localize_script('how_ajax', 'ajax_loc', array( 'ajaxurl' => admin_url( 'admin-ajax.php')));
add_action( 'wp_ajax_nopriv_how_interest_submit', 'how_interest_submit');
add_action( 'wp_ajax_how_interest_submit', 'how_interest_submit');

//------------------------------------------------------------------------








/**
 * register widget 
 * 
 */
function load_how_interest_widget(){
	register_widget('How_Interest_Widget');
}

//------------------------------------------------------------------------








/**
 * process data submitted from the front facing widget form
 * 
 */
function how_interest_submit(){
	
	global $wpdb;
	$table_name = $wpdb->prefix . "how_interested";
	
	// gather
	$name 	= $_POST['name'];
	$email 	= $_POST['email'];
	$phone 	= $_POST['phone'];
	$date 	= time();
	
	// save in the database
	$wpdb->insert( $table_name, array( 'name' => $name, 'email' => $email, 'phone' => $phone, 'date' => $date ) );
	
	// email
	$to 		= get_option('admin_email');
	$subject 	= __("A visiter to ".site_url()." used the 'How - Interest' widget to register their interest in your service");
	$headers	= "From: " .get_option('blogname'). " ". get_option('admin_email') . "\r\n";
	$message	= "
		".__("A visiter to ".site_url()." used the 'How - Interest' widget to register their interest in your service")."\r\n\r\n
		Name: ".$name."
		Email: ".$email."
		Phone: ".$phone."
		Date: ".date('d/m/y H:i:s', $date);
	wp_mail( $to, $subject, $message, $headers );
	
	echo "name = ".$name.", email = ".$email.", date = ".$date;
	
	exit;
}

//------------------------------------------------------------------------







/**
 * widget class
 *
 */
class How_Interest_Widget extends WP_Widget {
	
	
	/**
	 * construct
	 * 
	 */
	function How_Interest_Widget() {
		// Widget settings. 
		$widget_ops = array( 'classname' => 'How_Interest', 'description' => __('A simple widget to that allows visitors to your site register their interest in your service', 'how_interest') );

		// Widget control settings.
		$control_ops = array( 'id_base' => 'how_interest_widget' );

		// Create the widget.
		$this->WP_Widget( 'how_interest_widget', __('How Interest - Register Interest Widget', 'how_interest'), $widget_ops, $control_ops );
	}
	
	//-----------------------------------------------------------------------------
	
	
	
	
	
	
	/**
	 * widget
	 * @see WP_Widget::widget()
	 */
	function widget($args, $instance) {
		extract($args);
		
		// Our variables from the widget settings. 
		$title = apply_filters('widget_title', $instance['title'] );
		
		// Before widget 
		echo $before_widget;

		// Display the widget title if one was input
		if ( $title )
			echo $before_title . $title . $after_title;
		
		?>
		<script type="text/javascript">
			//setting vars that rely on PHP for their values.  
			var success_text 		= '<?php echo $instance['success_text']; ?>';
			var name_id 			= '<?php echo $this->get_field_id( 'name' ); ?>';
			var email_id 			= '<?php echo $this->get_field_id( 'email' ); ?>';
			var phone_id 			= '<?php echo $this->get_field_id( 'phone' ); ?>';
			var alert_name			= '<?php echo $instance['alert_name']; ?>';
			var alert_email			= '<?php echo $instance['alert_email']; ?>';
			var alert_phone			= '<?php echo $instance['alert_phone']; ?>';
			var alert_email_invalid	= '<?php echo $instance['alert_email_invalid']; ?>';
		</script>
		<div id="how_interest_form_container">
			<form name="how_interest_form" id="how_interest_form" action="#"  >
				<div>
					<label for="<?php echo $this->get_field_id( 'name' ); ?>"><?php _e('Name:', 'how_interest'); ?></label>
					<input type='text' id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" />
				</div>
				<div>
					<label for="<?php echo $this->get_field_id( 'email' ); ?>"><?php _e('Email:', 'how_interest'); ?></label>
					<input type='text' id="<?php echo $this->get_field_id( 'email' ); ?>" name="<?php echo $this->get_field_name( 'email' ); ?>" />
				</div>
				<div>
					<label for="<?php echo $this->get_field_id( 'phone' ); ?>"><?php _e('Phone:', 'how_interest'); ?></label>
					<input type='text' id="<?php echo $this->get_field_id( 'phone' ); ?>" name="<?php echo $this->get_field_name( 'phone' ); ?>" />
				</div>
				<input type='submit' value='Submit' />
			</form>
		</div>
		
		<?php 
		// After widget
		echo $after_widget;
	}

	//-----------------------------------------------------------------------------
	
	
	
	
	
	
	/**
	 * update
	 * @see WP_Widget::update()
	 */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		// Strip tags for title and name to remove HTML (important for text inputs)
		$instance['title'] 					= strip_tags( $new_instance['title'] );
		$instance['success_text'] 			= strip_tags( $new_instance['success_text'] );
		$instance['alert_name'] 			= strip_tags( $new_instance['alert_name'] );
		$instance['alert_email'] 			= strip_tags( $new_instance['alert_email'] );
		$instance['alert_phone'] 			= strip_tags( $new_instance['alert_phone'] );
		$instance['alert_email_invalid']	= strip_tags( $new_instance['alert_email_invalid'] );

		return $instance;
	}

	//-----------------------------------------------------------------------------
	
	
	
	
	
	
	/**
	 * form
	 * @see WP_Widget::form()
	 */
	function form($instance) {
		// Set up some default widget settings.
		$defaults = array( 
			'title' 						=> __('Register Your Interest', 'how_interest'), 
			'success_text' 			=> __('Thank you for registering your interest.  We will be in touch soon.', 'how_interest'),
			'alert_name' 				=> __('Name is a required field'),
			'alert_phone' 				=> __('Phone is a required field'),
			'alert_email'				=> __('Email is a required field'),
			'alert_email_invalid'	=> __('Please enter a valid email address')
		 );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><b><?php _e('Title:', 'hybrid'); ?></b></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'success_text' ); ?>"><b><?php _e('Success Text:', 'how_interest'); ?></b></label>
			<textarea id="<?php echo $this->get_field_id( 'success_text' ); ?>" name="<?php echo $this->get_field_name( 'success_text' ); ?>" class="widefat" style="height: 90px;"><?php echo $instance['success_text']; ?></textarea>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'alert_name' ); ?>"><b><?php _e('Alert text - Name field is empty:', 'how_interest'); ?></b></label>
			<input id="<?php echo $this->get_field_id( 'alert_name' ); ?>" name="<?php echo $this->get_field_name( 'alert_name' ); ?>" value="<?php echo $instance['alert_name']; ?>" class="widefat" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'alert_phone' ); ?>"><b><?php _e('Alert text - Phone field is empty:', 'how_interest'); ?></b></label>
			<input id="<?php echo $this->get_field_id( 'alert_phone' ); ?>" name="<?php echo $this->get_field_name( 'alert_phone' ); ?>" value="<?php echo $instance['alert_phone']; ?>" class="widefat" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'alert_email' ); ?>"><b><?php _e('Alert text -  Email field is empty:', 'how_interest'); ?></b></label>
			<input id="<?php echo $this->get_field_id( 'alert_email' ); ?>" name="<?php echo $this->get_field_name( 'alert_email' ); ?>" value="<?php echo $instance['alert_email']; ?>" class="widefat" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'alert_email_invalid' ); ?>"><b><?php _e('Alert text - Invalid email:', 'how_interest'); ?></b></label>
			<input id="<?php echo $this->get_field_id( 'alert_email_invalid' ); ?>" name="<?php echo $this->get_field_name( 'alert_email_invalid' ); ?>" value="<?php echo $instance['alert_email_invalid']; ?>" class="widefat" />
		</p>
		
		
		<?php 
	}
	
	//-----------------------------------------------------------------------------
	
	
	
	

} 
//-- end class ---------------------------------------------------





/* End of file widget.php */