<?php

namespace Training\Admin;

/**
 * Address_Book Handler Class
 * @package Training\Admin
 */
class Address_Book {

    public $errors = [];
    /**
     * plugin page handeler
     *
     * @return void
     */

    public function plugin_page() {
        $action = isset( $_GET['action'] ) ? $_GET['action'] : 'list';
        $id     = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;

        switch ( $action ) {
            case 'new':
                $template = __DIR__ . '/views/address-new.php';
                break;

            case 'edit':
                $address  = training_get_address( $id );
                $template = __DIR__ . '/views/address-edit.php';
                break;

            case 'view':
                $template = __DIR__ . '/views/address-view.php';
                break;

            default:
                $template = __DIR__ . '/views/address-list.php';
                break;
        }

        if ( file_exists( $template ) ) {
            include $template;
        }
    }


    /**
     * Form handaler function
     *
     * @return void
     */
    public function form_handler() {

        if ( ! isset( $_POST[ 'submit_address' ] ) ) {
            return;
        }
        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'new_address' ) ) {
            wp_die( "Are you cheating ?" );
        }
        if ( ! current_user_can( 'manage_options' ) ){
            wp_die( "Are you cheating ?" );
        }

        $id         = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : 0 ;
        $name       = isset( $_POST['name'] ) ? wp_unslash( sanitize_text_field( $_POST['name'] ) ) : '';
        $email      = isset( $_POST['email'] ) ? wp_unslash( sanitize_email( $_POST['email'] ) ) : '';
        $phone      = isset( $_POST['phone'] ) ? wp_unslash( sanitize_text_field( $_POST['phone'] ) ) : '';
        $address    = isset( $_POST['address'] ) ? wp_unslash( sanitize_textarea_field( $_POST['address'] ) ) : '';

        if ( empty($name) ) {
            $this->errors['name'] = __( "Please Provide a Name", 'training' );
        }
        if ( empty($email) ) {
            $this->errors['email'] = __( "Please Provide a Mail", 'training' );
        }
        if ( empty($phone) ) {
            $this->errors['phone'] = __( "Please Provide a Phone Number", 'training' );
        }
        if ( empty($address) ) {
            $this->errors['address'] = __( "Please Provide a Address", 'training' );
        }

        if ( ! empty( $this->errors ) ) {
            return;
        }

        $args       = [
            'name'       => $name,
            'email'      => $email,
            'phone'      => $phone,
            'address'    => $address,
        ];
        if ( $id ){

            $args['id'] = $id;
        }

        $insert_id  = training_insert_address( $args );

        if( is_wp_error( $insert_id ) ) {
            wp_die( $insert_id->get_error_message() );
        }

        if ( $id ) {
            $redirect_to = admin_url( 'admin.php?page=training&updated=true&id='.$id );
        } else {
            $redirect_to = admin_url( 'admin.php?page=training&inserted=true' );
        }
        wp_redirect( $redirect_to );
        die();
    }

    public function delete_address() {
        if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'training_delete_address' ) ) {
            wp_die( "Are you cheating ?" );
        }
        if ( ! current_user_can( 'manage_options' ) ){
            wp_die( "Are you cheating ?" );
        }

        $id = isset( $_REQUEST['id'] ) ? intval( $_REQUEST['id'] ) : 0 ;

        if ( training_delete_address( $id ) ) {
            $redirect_to = admin_url( 'admin.php?page=training&deleted=true&id='.$id );
        } else {
            $redirect_to = admin_url( 'admin.php?page=training&deleted=false&id='.$id );
        }

        wp_redirect( $redirect_to );
        exit;
    }
}
