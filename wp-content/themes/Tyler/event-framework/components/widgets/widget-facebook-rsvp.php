<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Register the Facebook Rsvp Widget
 * 
 * @package Event Framework
 * @since 1.0.0
 */

/**
 * Ef_Facebook_Rsvp_Widget Widget Class.
 * 
 * 
 * @package Event Framework
 * @since 1.0.0
 */
class Ef_Facebook_Rsvp_Widget extends WP_Widget {
	
	/**
	 * Contact Widget setup.
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function Ef_Facebook_Rsvp_Widget() {
		
		$widget_name = EF_Framework_Helper::get_widget_name();
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'ef_facebook_rsvp', 'description' => __( 'Shows a section displaying users who\'ve RSVP\'d via Facebook', 'dxef' ) );
		
		/* Create the widget. */
		$this->WP_Widget( 'ef_facebook_rsvp', $widget_name . __( ' Facebook RSVP Stats', 'dxef' ), $widget_ops );
		
	}
	
	/**
	 * Output of Widget Content
	 * 
	 * Handle to outputs the
	 * content of the widget
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function widget( $args, $instance ) {
		
		global $facebook;
   		
		$eventlink		= isset( $instance['eventlink'] ) ? $instance['eventlink'] : '';
	    $eventlinktitle	= isset( $instance['eventlinktitle'] ) ? $instance['eventlinktitle'] : '';
	    $rsvpeventid	= isset( $instance['rsvpeventid'] ) ? $instance['rsvpeventid'] : '';
	    $rsvpappid		= isset( $instance['rsvpappid'] ) ? $instance['rsvpappid'] : '';
	    $rsvpsecret 	= isset( $instance['rsvpsecret'] ) ? $instance['rsvpsecret'] : '';
		
	    $invited = array(
						'summary' => array(
						    'attending_count'	=> 0,
						    'maybe_count'		=> 0,
						    'declined_count'	=> 0
						)
	    			);
    	
	    if ( isset( $facebook ) && ! empty( $rsvpeventid ) ) {
	    	
	        try {
	        	
	        	$invited = $facebook->api("/$rsvpeventid/invited?limit=1&summary=1");
	        } catch( Exception $ex ) {
	        	
	        	error_log( "[{$ex->getType()}]: {$ex->getMessage()}" );
	        }
	    }
    	
	    echo stripslashes( $args['before_widget'] );?>
	    
	    <!-- FACEBOOK -->
	    <div id="tile_facebook" class="container widget">
	        <div class="row facebook">
	            <div class="col-md-8">
	                <div class="fb-event bg-fb-light">
	                    <div class="col">
	                        <i class="icon-thumbs-up"></i>
	                        <span class="num"><?php echo $invited['summary']['attending_count']; ?></span>
	                        <?php _e('YES', 'dxef'); ?>
	                    </div>
	                    <div class="col">
	                        <i class="icon-pause"></i>
	                        <span class="num"><?php echo $invited['summary']['maybe_count']; ?></span>
	                        <?php _e('MAYBE', 'dxef'); ?>
	                    </div>
	                    <div class="col">
	                        <i class="icon-thumbs-down"></i>
	                        <span class="num"><?php echo $invited['summary']['declined_count']; ?></span>
	                        <?php _e('NO', 'dxef'); ?>
	                    </div>
	                </div>
	            </div>
	            <div class="col-md-4">
	                    <a href="<?php echo stripslashes($eventlink); ?>" target="_blank" class="bg-fb-dark fb-view">
	                        <i class="icon-facebook-alt"></i>
	                        <?php echo stripslashes($eventlinktitle); ?> <i class="icon-angle-right"></i>
	                    </a>
	            </div>
	        </div>
	    </div><?php
	    
	    echo stripslashes($args['after_widget']);
	}
	
	/**
	 * Update Widget Setting
	 * 
	 * Handle to updates the widget control options
	 * for the particular instance of the widget
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function update( $new_instance, $old_instance ) {

		if (isset($_POST['submitted'])) {
			update_option('ef_facebook_rsvp_widget_eventlink', isset( $new_instance['eventlink'] ) ? $new_instance['eventlink'] : '' );
			update_option('ef_facebook_rsvp_widget_eventlinktitle', isset( $new_instance['eventlinktitle'] ) ? $new_instance['eventlinktitle'] : '' );
			update_option('ef_facebook_rsvp_widget_appid', isset( $new_instance['rsvpappid'] ) ? $new_instance['rsvpappid'] : '' );
			update_option('ef_facebook_rsvp_widget_secret', isset( $new_instance['rsvpsecret'] ) ? $new_instance['rsvpsecret'] : '' );
			update_option('ef_facebook_rsvp_widget_eventid', isset( $new_instance['rsvpeventid'] ) ? $new_instance['rsvpeventid'] : '' );
		}
		
		$instance = $old_instance;
		
		/* Set the instance to the new instance. */
		$instance = $new_instance;
		
		/* Input fields */
		$instance['eventlink']	= strip_tags( $new_instance['eventlink'] );
		$instance['eventlinktitle']	= strip_tags( $new_instance['eventlinktitle'] );
		$instance['rsvpeventid']	= strip_tags( $new_instance['rsvpeventid'] );
		$instance['rsvpappid']	= strip_tags( $new_instance['rsvpappid'] );
		$instance['rsvpsecret']	= strip_tags( $new_instance['rsvpsecret'] );
		
		return $instance;
	}
	
	/**
	 * Display Widget Form
	 * 
	 * Displays the widget
	 * form in the admin panel
	 * 
	 * @package Event Framework
	 * @since 1.0.0
	 */
	function form( $instance ) {
		
		$eventlink		= isset( $instance['eventlink'] ) ? $instance['eventlink'] : '';
	    $eventlinktitle	= isset( $instance['eventlinktitle'] ) ? $instance['eventlinktitle'] : '';
	    $rsvpeventid	= isset( $instance['rsvpeventid'] ) ? $instance['rsvpeventid'] : '';
	    $rsvpappid		= isset( $instance['rsvpappid'] ) ? $instance['rsvpappid'] : '';
	    $rsvpsecret 	= isset( $instance['rsvpsecret'] ) ? $instance['rsvpsecret'] : '';?>
	    
	    <em><?php _e('Link to Event on Facebook:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'eventlink' ); ?>" value="<?php echo stripslashes($eventlink); ?>"/>
	    <br /><br />
	    <em><?php _e('Link Text:', 'dxef'); ?></em><br />
	    <input type="text" class="widefat" name="<?php echo $this->get_field_name( 'eventlinktitle' ); ?>" value="<?php echo stripslashes($eventlinktitle); ?>"/>
	    <br /><br />
	    <em><?php _e('Event ID:', 'dxef'); ?></em><br />
	    <input type="text" class="rsvpeventid" name="<?php echo $this->get_field_name( 'rsvpeventid' ); ?>" value="<?php echo stripslashes($rsvpeventid); ?>"/>
	    <br /><br />
	    <em><?php _e('App ID:', 'dxef'); ?></em><br />
	    <input type="text" class="rsvpappid" name="<?php echo $this->get_field_name( 'rsvpappid' ); ?>" value="<?php echo stripslashes($rsvpappid); ?>"/>
	    <br /><br />
	    <em><?php _e('Secret Key:', 'dxef'); ?></em><br />
	    <input type="text" class="rsvpsecret" name="<?php echo $this->get_field_name( 'rsvpsecret' ); ?>" value="<?php echo stripslashes($rsvpsecret); ?>"/>
	    <br /><br />
	    <input type="hidden" name="submitted" value="1" /><?php
	}
}

//Register Widget
register_widget( 'Ef_Facebook_Rsvp_Widget' );