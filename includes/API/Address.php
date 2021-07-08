<?php
namespace Training\API;
use WP_REST_Controller;
use WP_REST_Server;


/**
 * Class Address
 * 
 * @package Training\API
 */
class Address  extends WP_REST_Controller {

    /**
     * Address constructor.
     */
    public function __construct() {
        $this->namespace = 'training/v1';
        $this->rest_base = 'contacts';
    }

    /**
     * Register contact route for rest api.
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [$this, 'get_items'],
                    'permission_callback' => [$this, 'get_items_permissions_check'],
                    'args'                => $this->get_collection_params(),
                ],
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'create_item' ],
                    'permission_callback' => [ $this, 'create_item_permissions_check' ],
                    'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::CREATABLE ),
                ],
                'schema' => [ $this, 'get_item_schema' ]
            ]
        );
        
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)',
            [
                'args'   => [
                    'id' => [
                        'description' => __( 'Unique identifier for the object.' ),
                        'type'        => 'integer',
                    ],
                ],
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_item' ],
                    'permission_callback' => [ $this, 'get_item_permissions_check' ],
                    'args'                => [
                        'context' => $this->get_context_param( [ 'default' => 'view' ] ),
                    ],
                ],
                [
                    'methods'             => WP_REST_Server::EDITABLE,
                    'callback'            => [ $this, 'update_item' ],
                    'permission_callback' => [ $this, 'update_item_permissions_check' ],
                    'args'                => $this->get_endpoint_args_for_item_schema( WP_REST_Server::EDITABLE ),
                ],
                [
                    'methods'             => WP_REST_Server::DELETABLE,
                    'callback'            => [ $this, 'delete_item' ],
                    'permission_callback' => [ $this, 'delete_item_permissions_check' ],
                ],
                
                'schema' => [ $this, 'get_item_schema' ],
            ]
        );        
    }

    /**
     * checks if a given request has access to read contacts
     *
     * @param \WP_REST_Request $request
     *
     * @return bool
     */
    public function get_items_permissions_check( $request ) {
        if ( ! current_user_can('manage_options')) {
            return true;
        }
        return false;
    }

    /**
     *  Retrieves the query params of contact
     *
     * @return array
     */
    public function get_collection_params() {

        $params = parent::get_collection_params();

        unset( $params['search'] );

        return $params;

    }

    /**
     * retrieves a list of address item
     *
     * @param \WP_REST_Request $request
     *
     * @return void|\WP_Error|\WP_REST_Response
     */
    public function get_items( $request ) {
        $args   = [];
        $params = $this->get_collection_params();

        foreach ( $params as $key => $value ) {
            if ( isset( $request[ $key ] ) ) {
                $args[ $key ] = $request[ $key ];
            }
        }
//        change 'per_page' to 'number'

        $args['number'] = $args['per_page'];
        $args['offset'] = $args['number'] * ($args['page'] - 1 );

//        unset Others
        unset( $args['per_page'] );
        unset( $args['page'] );

        $data     = [];
        $contacts = training_get_addreses($args );

        foreach ( $contacts as $contact ){
            $responces = $this->prepare_item_for_response( $contact, $request );
            $data[]    = $this->prepare_response_for_collection( $responces );
        }

        $total     = training_address_count();
        $max_pages = ceil( $total / $args['number'] );

        $responces = rest_ensure_response( $data );

        $responces->header( 'X-WP-Total', $total );
        $responces->header( 'X-WP-TotalPages', $max_pages );


        return $responces;
    }
    
    /**
     * Get the contact, if ID is valid.
     *
     * @param $id
     *
     * @return string|void|WP_Error
     */
    protected function get_contact( $id ) {
        $contact = training_get_address( $id );

        if ( ! $contact ) {
            return new WP_Error(
                'rest contact invalid id',
                __( "Invalid Contact ID", 'training' ),
                [ 'status' => 404 ]
            );
        }

        return  $contact;
    }

    /**
     * Checks if a given request has access to get a specific item.
     *
     * @param \WP_REST_Request $request
     *
     * @return bool|string|true|void|WP_Error
     */
    public function get_item_permissions_check( $request ) {
        if ( ! current_user_can('manage_options')) {
            return false;
        }

        $contact = $this->get_contact( $request['id'] );

        if ( is_wp_error( $contact ) ) {
            return $contact;
        }

        return true;
    }    

    /**
     * Retrieves one item from the collection
     *
     * @param \WP_REST_Request $request
     *
     * @return WP_Error|\WP_HTTP_Response|\WP_REST_Response
     */
    public function get_item($request) {


        $contact = $this->get_contact( $request['id'] );

        $responces = $this->prepare_item_for_response( $contact, $request );
        $responces = rest_ensure_response( $responces );

        return $responces;

    }

    /**
     * Checks if a given request has access to get a specific item.
     * 
     * @param $request
     *
     * @return bool|string|true|void|WP_Error
     */
    public function delete_item_permissions_check( $request ) {

        return $this->get_item_permissions_check( $request );
    }

    /**
     * Deleted one item form the collection
     *
     * @param \WP_REST_Request $request
     *
     * @return WP_Error|\WP_HTTP_Response|\WP_REST_Response
     */
    public function delete_item( $request ) {
        $contact   = $this->get_contact( $request['id'] );
        $previous  = $this->prepare_item_for_response( $contact, $request );
        $deleted   = training_delete_address( $request['id'] );

        if ( ! $deleted ) {
            new WP_Error(
                'rest_not_deleted',
                __( "Sorry, The Address could not be deleted !", 'training' ),
                [ 'status' => 400 ]
            );
        }

        $data = [
            'deleted'  => true,
            'previous' =>  $previous->get_data(),
        ];

        $responces = rest_ensure_response( $data );

        return $responces;
    }

    /**
     * Checks if a given request has access to create items.
     *
     * @param $request
     *
     * @return bool
     */
    public function create_item_permissions_check( $request ) {
        return $this->get_items_permissions_check( $request );
    }

    /**
     * Creates one item from the collection.
     *
     * @param \WP_REST_Request $request
     *
     * @return int|object|WP_Error|\WP_HTTP_Response|\WP_REST_Response
     *
     */
    public function create_item( $request ) {

        $contact = $this->prepare_item_for_database( $request );

        if ( is_wp_error( $contact ) ) {
            return $contact;
        }

        $contact_id = training_insert_address( $contact );

        if ( is_wp_error( $contact_id ) ) {
            $contact_id->add_data( [ 'status' => 400 ] );

            return $contact_id;
        }

        $contact  = $this->get_contact( $contact_id );
        $response = $this->prepare_item_for_response( $contact, $request );

        $response->set_status( 201 );
        $response->header( 'Location', rest_url( sprintf( '%s/%s/%d', $this->namespace, $this->rest_base, $contact_id ) ) );

        return rest_ensure_response( $response );
    }

    /**
     * Checks if a given request has access to get a specific item.
     *
     * @param \WP_REST_Request $request
     *
     * @return bool|string|true|void|WP_Error
     */
    public function update_item_permissions_check( $request ) {
        return $this->get_item_permissions_check( $request );
    }

    /**
     * Updates one item from the collection.
     *
     * @param \WP_REST_Request $request
     *
     * @return WP_Error|\WP_HTTP_Response|\WP_REST_Response
     */
    public function update_item( $request) {
        $contact  = $this->get_contact( $request['id'] );
        $prepared = $this->prepare_item_for_database( $request );

        $prepared = array_merge( (array) $contact, $prepared );

        $update   = training_insert_address($prepared );

        if ( ! $update ) {
            new WP_Error(
                'rest not updated',
                __( "Sorry, Address could not be updated !" ),
                [ 'status' => 400 ]
            );
        }

        $contact = $this->get_contact( $request['id'] );
        $response = $this->prepare_item_for_response( $contact, $request );

        return rest_ensure_response( $response );

    }

    /**
     * Prepares one item for create or update operation.
     *
     * @param \WP_REST_Request $request
     *
     * @return array
     */
    protected function prepare_item_for_database( $request ) {
        $prepared = [];

        if ( isset( $request['name'] ) ) {
            $prepared['name'] = $request['name'];
        }

        if ( isset( $request['address'] ) ) {
            $prepared['address'] = $request['address'];
        }

        if ( isset( $request['email'] ) ) {
            $prepared['email'] = $request['email'];
        }


        if ( isset( $request['phone'] ) ) {
            $prepared['phone'] = $request['phone'];
        }

        return $prepared;
    }

    /**
     * Prepare item for REST responce
     *
     * @param mixed $item
     * @param \WP_REST_Request $request
     *
     * @return \WP_Error|\WP_REST_Response
     */
    public function prepare_item_for_response( $item, $request )  {
        $data   = [];
        $fields = $this->get_fields_for_response( $request );

        if ( in_array( 'id' , $fields, true ) ) {
            $data['id'] = (int) $item->id;
        }

        if ( in_array( 'name' , $fields, true ) ) {
            $data['name'] =  $item->name;
        }

        if ( in_array( 'email' , $fields, true ) ) {
            $data['email'] = $item->email;
        }

        if ( in_array( 'phone' , $fields, true ) ) {
            $data['phone'] = $item->phone;
        }

        if ( in_array( 'address' , $fields, true ) ) {
            $data['address'] = $item->address;
        }

        if ( in_array( 'date' , $fields, true ) ) {
            $data['date'] = mysql_to_rfc3339( $item->created_at );
        }

        $context = ! empty( $request['context'] ) ? $request['context'] : 'view';
        $data    = $this->filter_response_by_context( $data, $context );

        $responces =  rest_ensure_response( $data );
        $responces->add_links( $this->prepare_links( $item ) );

        return $responces;
    }

    /**
     * prepare links for the request.
     *
     * @param $item
     *
     * @return array[]
     */
    protected function prepare_links( $item ) {
        $base = sprintf( '%s/%s', $this->namespace, $this->rest_base );

        $links = [
            'self' => [
                'href' => rest_url( trailingslashit( $base ) . $item->id )
            ],
            'collection' => [
                'href' => rest_url( $base )
            ]
        ];

        return $links;
    }

    /**
     *  Retrieves the contact schema, Confirming the JSON Schema.
     *
     * @return array
     */
    public function get_item_schema()  {
        if ( $this->schema ) {
            return $this->add_additional_fields_schema( $this->schema );
        }

        $schema = [
            'schema' => 'http://json-schema.org/draft-04/schema',
            'title' => 'Contact',
            'type'  => 'object',
            'properties' => [
                'id' => [
                    'descriptions' =>__( 'Unique Identifier for the object', 'training' ),
                    'type'         => 'integer',
                    'context'      => [ 'view', 'edit' ],
                    'readonly'     => true
                ],
                'name' => [
                    'descriptions' => __( 'Name of Contact', 'training' ),
                    'type'         => 'string',
                    'context'      => [ 'view', 'edit' ],
                    'required'     => true,
                    'arg_options'  => [
                        'sanitize_callback' => 'sanitize_text_field'
                    ]
                ],
                'address' => [
                    'descriptions' => __( 'Address of Contact', 'training' ),
                    'type'         => 'string',
                    'context'      => [ 'view', 'edit' ],
                    'required'     => true,
                    'arg_options'  => [
                        'sanitize_callback' => 'sanitize_textarea_field'
                    ]
                ],
                'email' => [
                    'descriptions' => __( 'Email of Contact', 'training' ),
                    'type'         => 'string',
                    'context'      => [ 'view', 'edit' ],
                    'required'     => true,
                    'arg_options'  => [
                        'sanitize_callback' => 'sanitize_email',
                    ]
                ],
                'phone' => [
                    'descriptions' => __( 'Phone of Contact', 'training' ),
                    'type'         => 'integer',
                    'context'      => [ 'view', 'edit' ],
                    'required'     => true,
                    'arg_options'  => [
                        'sanitize_callback' => 'sanitize_text_field',
                    ],
                ],
                'date' => [
                    'descriptions' => __( 'Create date of Contact', 'training' ),
                    'type'         => 'string',
                    'context'      => [ 'view' ],
                    'readonly'     => true
                ]
            ]
        ];

        $this->schema = $schema;

        return $this->add_additional_fields_schema( $this->schema );
    }

}
