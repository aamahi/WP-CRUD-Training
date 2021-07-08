<?php


namespace Training;


class API {

    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_api' ] );
    }

    public function register_api() {
        $address = new API\Address();
        $address->register_routes();

    }

}