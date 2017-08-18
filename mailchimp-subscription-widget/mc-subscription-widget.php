<?php
/*
Plugin Name: MailChimp Subscription Widget
Plugin URI: https://github.com/praliedutzel/mailchimp-api-wordpress-widget
Description: Allows you to add a MailChimp subscription signup form as a widget to dynamic sidebars.
Version: 1.0
Author: Pralie Dutzel
Author URI: https://praliedutzel.com
*/

if( !defined( 'ABSPATH' ) ) {
    die;
}


/**
 * Create the settings page and load necessary assets
 *
 */

require_once( 'mcsw-settings.php' );

function mcsw_assets() {
    // Widget specific assets
    if ( !is_admin() ) {
        wp_enqueue_script( 'mcsw-widget-script', plugins_url( 'js/widget-form-handler.js', __FILE__ ), array('jquery'), '', true );
        wp_localize_script( 'mcsw-widget-script', 'mcswData', array(
            'ajaxPath'          => get_bloginfo( 'wpurl' ).'/wp-admin/admin-ajax.php',
            'successMessage'    => get_option( 'success_message' ),
            'subscribedMessage' => get_option( 'subscribed_message' ),
            'errorMessage'      => get_option( 'error_message' )
        ) );
    }
}

add_action( 'wp_enqueue_scripts', 'mcsw_assets' );


/**
 * Setup subscription widget for the dynamic sidebar
 *
 */

class mcsw_sidebar_widget extends WP_Widget {
    public function __construct() {
        $widget_options = array(
            'classname'   => 'mcsw-widget',
            'description' => 'Add a MailChimp subscription form to your sidebar.'
        );

        parent::__construct( 'mcsw-widget', 'MailChimp Signup Widget', $widget_options );
    }

    public function widget( $args, $instance ) {
        $title       = apply_filters( 'widget_title', $instance['title'] );
        $description = $instance['description'];

        echo $args['before_widget'];

        if ( $title != '' ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        if ( $description != '' ) {
            echo '<p class="widgetdescription">'.$description.'</p>';
        }

        require( 'mcsw-markup.php' );

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = !empty( $instance['title'] ) ? $instance['title'] : get_option( 'default_title' );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                Title:
                <input type="text" id="<?php echo $this->get_field_id('title'); ?>" class="widefat" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>">
            </label>
        </p>
        <?php
        $description = !empty( $instance['description'] ) ? $instance['description'] : get_option( 'default_description' );
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('description'); ?>">
                Description:
                <input type="text" id="<?php echo $this->get_field_id('description'); ?>" class="widefat" name="<?php echo $this->get_field_name('description'); ?>" value="<?php echo esc_attr($description); ?>">
            </label>
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance                = $old_instance;
        $instance['title']       = strip_tags( stripslashes( $new_instance['title'] ) );
        $instance['description'] = strip_tags( stripslashes( $new_instance['description'] ) );
        return $instance;
    }
}

function mcsw_register_widget() {
    register_widget( 'mcsw_sidebar_widget' );
}

add_action( 'widgets_init', 'mcsw_register_widget' );


/**
 * Setup the processor for MailChimp's API
 *
 */

function mcsw_process_subscriber() {
    $options = array(
        'api_key'    => get_option( 'mc_api_key' ),
        'datacenter' => get_option( 'mc_api_datacenter' ),
        'list_id'    => get_option( 'mc_api_list_id' )
    );

    $data   = array();

    $first_name = $_POST['firstName'];
    $last_name  = $_POST['lastName'];
    $email      = $_POST['email'];
    $status     = 'pending';

    if ( !empty($_POST['status']) ) {
        $status = $_POST['status'];
    }

    $url = 'http://'.$options['datacenter'].'.api.mailchimp.com/3.0/lists/'.$options['list_id'].'/members/';

    $auth = base64_encode( 'user:'.$options['api_key'] );

    $data = array(
        'email_address' => $email,
        'status'        => $status,
        'merge_fields'  => array(
            'FNAME'     => $first_name,
            'LNAME'     => $last_name
        )
    );
    
    $data_string = json_encode($data);

    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_POST, 1 );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: '.strlen($data_string),
        'Authorization: Basic '.$auth
    ) );

    $result = curl_exec($ch);

    if ( !curl_exec($ch) ) {
        echo 'Error: '.curl_error($ch);
    }

    curl_close($ch);

    echo $result;

    die();
}

add_action( 'wp_ajax_mcsw_process', 'mcsw_process_subscriber' );
add_action( 'wp_ajax_nopriv_mcsw_process', 'mcsw_process_subscriber' );


/**
 * Uninstall options
 *
 * Removes the widget on uninstall of the plugin
 */

function mcsw_uninstall() {
    function mcsw_remove_widget() {
        unregister_widget( 'mcsw_sidebar_widget' );
    }

    add_action( 'widgets_init', 'mcsw_remove_widget' );
}

register_uninstall_hook( __FILE__, 'mcsw_uninstall' );