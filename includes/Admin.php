<?php
namespace Training;

use Training\Admin\Address_Book;
use Training\Admin\Menu;

/**
 * Class Admin
 *
 * @package Training
 */
class Admin {
    /**
     * Admin constructor.
     */
    public function __construct() {
        $addressbook = New Address_Book();

        new Menu( $addressbook );

        $this->dispatch_actions( $addressbook );
    }

    /**
     * Dispatch and bind actions
     *
     * @return void
     */
    public function dispatch_actions( $addressbook ) {
        add_action( 'admin_init', [ $addressbook, 'form_handler' ] );
        add_action( 'admin_post_training_delete_address', [ $addressbook, 'delete_address' ] );
    }
}