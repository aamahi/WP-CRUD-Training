<?php
namespace Training\Admin;

/**
 * Class Menu page
 * @package Training\Admin
 */
class Menu {

    public $addressbook;
    /**
     * Menu constructor.
     */
    public function __construct($addressbook) {
        $this->addressbook = $addressbook;
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    /**
     * Admin Manu method
     */
    public function admin_menu() {
        $parent_slug   = "training";
        $capability    = 'manage_options';
        add_menu_page( __( 'Training' , 'training' ) ,__( 'Training' ,'training' ) ,$capability , $parent_slug ,[ $this->addressbook, 'plugin_page' ] , 'dashicons-welcome-learn-more' , '30');
        add_submenu_page( $parent_slug, __('Address Book', 'training'), __('Address Book', 'training'), $capability, $parent_slug,  [ $this->addressbook, 'plugin_page' ]);
        add_submenu_page( $parent_slug, __('Settings', 'training'), __('Settings', 'training'), $capability, 'training-settings',  [ $this, 'training_settings' ]);

    }

    /**
     * render trainging seetings
     */
    public function training_settings(){
        echo "Hello form settings page";
    }


}