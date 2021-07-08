<?php

namespace Training\Admin;

if( ! class_exists('WP_List_Table' ) ) {
    require_once ABSPATH.'wp-admin/includes/class-wp-list-table.php';
}

class Address_List extends \WP_List_Table {

    /**
     * Address_List constructor.
     * @param array $args
     */
     public function __construct() {
        parent::__construct( [
            'singular' => 'contact',
            'plural'   => 'contacts',
            'ajax'     => false
        ] );
    }

    /**
     * Get the Columns Name
     *
     * @return array
     */
    public function get_columns() {
        return [
            'cb'        => "<input type='checkbox'>",
            'name'      => __( 'Name', 'training' ),
            'address'   => __( 'Address', 'training' ),
            'email'     => __( 'E-Mail', 'training' ),
            'phone'     => __( 'Phone', 'training' ),
            'created_at'     => __( 'Date', 'training' ),
        ];
    }

    /**
     * Render the 'cb' column
     *
     * @param array|object $item
     *
     * @return string|void
     */
    public function column_cb( $item ) {
        return sprintf(
            "<input type='checkbox' value='%s' name='address_id[]'/>", $item->id
        );
    }

    /**
     * Render the 'name' column
     *
     * @param $item
     *
     * @return string
     */
    public function column_name( $item ){

         $actions = [];
         $actions[ 'edit' ]     = sprintf( "<a href='%s' title='%s'> %s </a>", admin_url( "admin.php?page=training&action=edit&id=" . $item->id ), __( "Edit", 'training' ), __( "Edit", 'training' ) );
         $actions[ 'delete' ]   = sprintf( "<a href='%s' title='%s' onclick='return confirm(\"Are you Sure? \")'> %s </a>", wp_nonce_url( admin_url( 'admin-post.php?action=training_delete_address&id=' . $item->id ), 'training_delete_address' ), __( "Delete", 'training' ), __( "Delete", 'training'  ) );

         return sprintf(
            " <a href='%s'> <strong> %s </strong> </a> %s", admin_url( "admin.php?page=training&action=view&id" . $item->id ), $item->name, $this->row_actions( $actions ),
        );
    }

    /**
     * Default column values
     *
     * @param array|object $item
     * @param string $column_name
     *
     * @return false|string|void
     */
    public function column_default($item, $column_name) {

        switch ( $column_name ) {

            case 'created_at':
                return wp_date( get_option( 'date_format' ), strtotime( $item->created_at ) );

            default:
                return isset( $item->$column_name ) ? $item->$column_name : '';
        }
    }

    /**
     * Get sortable column
     *
     * @return array[]
     */
    public function get_sortable_columns() {

            $sortable_column = [
                'name'     => [ 'name', true ],
                'address'  => [ 'address', true ],
                'email'    => [ 'email', true ],
                'phone'    => [ 'phone', true ],
                'created_at'     => [ 'created_at', true ],
            ];

            return $sortable_column;
    }

    /**
     * Pepare the items
     *
     * @return void
     */
//     public function prepare_items() {
//         $column                 = $this->get_columns();
//         $hidden                 = [];
//         $sortable               = $this->get_sortable_columns();

//         $this->_column_headers  = [ $column, $hidden, $sortable ];

//         $per_page               = 10;
//         $total_items            = training_address_count();
//         $paged                  = $_REQUEST['paged'] ?? 1;

// //        $data_chunks            = array_chunk( training_get_addreses(), $per_page);
// //        $this->items            = $data_chunks[$paged-1];
//         $this->items            = training_get_addreses();


//        $this->set_pagination_args( [
//            'total_items' => $total_items,
//            'per_pge'     => 10,
//            'total_pages' => ceil( $total_items / $per_page ),
//        ] );
//     }

    
    public function prepare_items() {
        $column   = $this->get_columns();
        $hidden   = [];
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [ $column, $hidden, $sortable ];

        $per_page     = 20;
        $current_page = $this->get_pagenum();
        $offset       = ( $current_page - 1 ) * $per_page;

        $args = [
            'number' => $per_page,
            'offset' => $offset,
        ];

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order']   = $_REQUEST['order'] ;
        }

        $this->items = training_get_addreses( $args );

        $this->set_pagination_args( [
            'total_items' => training_address_count(),
            'per_page'    => $per_page
        ] );
    }
}
