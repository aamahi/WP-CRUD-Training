<?php

/**
 * Insert a new address
 *
 * @param array $args
 *
 * @return int|WP_Error
 */
function training_insert_address( $args = [] ) {
    global $wpdb;

    if ( empty( $args['name'] ) ) {

        return New \WP_Error( 'no-name', __( "You must provide a Name", 'training' ) );
    }

    $table_name  = $wpdb->prefix."training_addresses";
    $defaults    = [
        'name'       => '',
        'email'      => '',
        'phone'      => '',
        'address'    => '',
        'created_by' => get_current_user_id(),
        'created_at' => current_time('mysql'),
    ];
    $data       = wp_parse_args( $args, $defaults );

    if ( $data['id'] ) {
        $id      = $data['id'];
        unset( $data['id'] );
        $updated = $wpdb->update(
            $table_name,
            $data,
            [ 'id' => $id ],
            [
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
                '%s',
            ],
            '%d'
        );
        return $updated;
    } else {
        $inserted = $wpdb->insert(
            $table_name,
            $data,
            [
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
                '%s',
            ]
        );

        if ( ! $inserted ){
            return  new \WP_Error( 'field to inster data', __( "Failed to Insert data", 'training' ) );
        }
        return $wpdb->insert_id;
    }
}

/**
 * Fetch Address
 *
 * @param array $args
 *
 * @return array|object|null
 */
// function training_get_addreses() {
//     global $wpdb;

//     $table_name  = $wpdb->prefix."training_addresses";
//     $defaults    = [
//         'orderby'=> 'id',
//         'order'  => 'ASC',
//     ];
//     $args['orderby'] = isset( $_GET['orderby'] ) ? isset( $_GET['orderby'] ) : 'id';
//     $args['order']   = isset( $_GET['order'] ) ? isset( $_GET['order'] ) : 'ASC';

//     $args  = wp_parse_args($args, $defaults );

// //    var_dump($args);

//     $items = $wpdb->get_results("SELECT * FROM {$table_name}
//             ORDER BY {$args['orderby']} {$args['order']}");

// //    var_dump($items);
//     return $items;
// }

function training_get_addreses( $args = [] ) {
    global $wpdb;
    $table_name  = $wpdb->prefix."training_addresses";

    $defaults = [
        'number'  => 10,
        'offset'  => 0,
        'orderby' => 'id',
        'order'   => 'ASC'
    ];

    $args = wp_parse_args( $args, $defaults );

    $sql = $wpdb->prepare(
        "SELECT * FROM {$table_name}
            ORDER BY {$args['orderby']} {$args['order']}
            LIMIT %d, %d",
        $args['offset'], $args['number']
    );

    $items = $wpdb->get_results( $sql );

    return $items;
}

/**
 * Get the count of Total Address
 *
 * @return int
 */
function training_address_count() {
    global $wpdb;

    $table_name  = $wpdb->prefix."training_addresses";

    return (int) $wpdb->get_var( "SELECT count(id) FROM {$table_name}" );
}

/**
 * Fetch a single contact from the DB
 *
 * @param $id
 *
 * @return string|void
 */
function training_get_address( $id ) {
    global $wpdb;

    $table_name  = $wpdb->prefix."training_addresses";

    return $wpdb->get_row(
        $wpdb->prepare( "SELECT * FROM {$table_name} WHERE id = %d", $id )
    );
}

/**
 * Delete an address
 *
 * @param $id
 *
 * @return bool|int
 */
function training_delete_address( $id ) {
    global $wpdb;

    $table_name  = $wpdb->prefix."training_addresses";

    return $wpdb->delete( $table_name, [ 'id'=>$id ], [ '%d' ] );
}
