<?php

/**
 * Creates a settings page with options for MailChimp's required data and plugin defaults
 *
 */

class mcsw_settings {
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
        add_action( 'admin_init', array( $this, 'setup_sections' ) );
        add_action( 'admin_init', array( $this, 'setup_fields' ) );
    }

    public function create_plugin_settings_page() {
        $page_title = 'MailChimp Subscription Settings';
        $menu_title = 'MailChimp Subscription';
        $capability = 'manage_options';
        $slug       = 'mcsw_fields';
        $callback   = array( $this, 'plugin_settings_page_content' );
        $icon       = 'dashicons-email-alt';
        $position   = 100;

        add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
    }

    public function plugin_settings_page_content() { ?>
        <div class="wrap">
            <h2>MailChimp Subscription Settings</h2>
            
            <?php if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ) {
                $this->admin_notice();
            } ?>

            <form method="post" action="options.php">
                <?php
                    settings_fields( 'mcsw_fields' );
                    do_settings_sections( 'mcsw_fields' );
                    submit_button();
                ?>
            </form>
        </div> <?php
    }

    public function admin_notice() { ?>
        <div class="notice notice-success is-dismissable">
            <p>Your changes have been saved.</p>
        </div> <?php
    }

    public function setup_sections() {
        $sections = array(
            array(
                'uid'   => 'mailchimp_api_section',
                'title' => 'MailChimp API Settings'
            ),
            array(
                'uid'   => 'default_text_section',
                'title' => 'Default Text Settings'
            ),
            array(
                'uid'   => 'submission_messaging_section',
                'title' => 'Form Submission Messaging Settings'
            )
        );

        foreach ( $sections as $section ) {
            add_settings_section( $section['uid'], $section['title'], array( $this, 'section_callback' ), 'mcsw_fields' );
        }
    }

    public function section_callback( $arguments ) {
        switch( $arguments['id'] ) {
            case 'mailchimp_api_section' :
                echo '<p>These fields are required in order to connect to your MailChimp account. The subscription form will not work without these settings.</p>';
                break;
            case 'default_text_section' :
                echo '<p>These fields are the default text options for widgets. They can still be changed individually per widget.</p>';
                break;
            case 'submission_messaging_section' :
                echo '<p>These fields control the messaging that appears after a user submits a subscription form.</p>';
                break;
        }
    }

    public function setup_fields() {
        $fields = array(
            array(
                'uid'          => 'mc_api_key',
                'label'        => 'API Key',
                'section'      => 'mailchimp_api_section',
                'type'         => 'text',
                'options'      => false,
                'supplemental' => 'This is generated through your account on <a href="http://kb.mailchimp.com/integrations/api-integrations/about-api-keys#Find-or-Generate-Your-API-Key" target="_blank" rel="noopener noreferrer">MailChimp</a>.'
            ),
            array(
                'uid'          => 'mc_api_datacenter',
                'label'        => 'Datacenter',
                'section'      => 'mailchimp_api_section',
                'type'         => 'text',
                'placeholder'  => 'usX',
                'options'      => false,
                'supplemental' => 'This can be found at the beginning of the url when logged into MailChimp, and should begin with "us" followed by a number.'
            ),
            array(
                'uid'          => 'mc_api_list_id',
                'label'        => 'List ID',
                'section'      => 'mailchimp_api_section',
                'type'         => 'text',
                'options'      => false,
                'supplemental' => 'This can be found in the lists section of the <a href="https://us1.api.mailchimp.com/playground/" target="_blank" rel="noopener noreferrer">API Playground</a>.'
            ),
            array(
                'uid'          => 'default_title',
                'label'        => 'Title',
                'section'      => 'default_text_section',
                'type'         => 'text',
                'options'      => false,
                'supplemental' => ''
            ),
            array(
                'uid'          => 'default_description',
                'label'        => 'Description',
                'section'      => 'default_text_section',
                'type'         => 'text',
                'options'      => false,
                'supplemental' => ''
            ),
            array(
                'uid'          => 'success_message',
                'label'        => 'Success Message',
                'section'      => 'submission_messaging_section',
                'type'         => 'text',
                'options'      => false,
                'supplemental' => ''
            ),
            array(
                'uid'          => 'subscribed_message',
                'label'        => 'Already Subscribed Message',
                'section'      => 'submission_messaging_section',
                'type'         => 'text',
                'options'      => false,
                'supplemental' => ''
            ),
            array(
                'uid'          => 'error_message',
                'label'        => 'Error Message',
                'section'      => 'submission_messaging_section',
                'type'         => 'text',
                'options'      => false,
                'supplemental' => ''
            )
        );

        foreach ( $fields as $field ) {
            add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'mcsw_fields', $field['section'], $field );
            register_setting( 'mcsw_fields', $field['uid'] );
        }
    }

    public function field_callback( $arguments ) {
        $value = get_option( $arguments['uid'] );
        printf( '<input name="%1$s" id="%1$s" class="widefat" type="%2$s" placeholder="%3$s" value="%4$s" style="max-width: 300px;">', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );

        if ( $supplemental = $arguments['supplemental'] ) {
            printf( '<p class="description">%s</p>', $supplemental );
        }
    }

}

new mcsw_settings();