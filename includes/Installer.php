<?php

namespace Training;

/**
 * Class Installer
 *
 * @package Training
 */
class Installer {
    /**
     * run the installer
     *
     * @return void
     */
    public function run() {
        $this->add_version();
        $this->create_datebase();
    }

    /**
     * Add Time and Version On DB
     */
    public function add_version() {
        $installed = get_option( 'training_installed' );

        if ( ! $installed ) {
            update_option( 'training_installed', time() );
        }
        update_option('training_version', TRAINING_VERSION );
    }

    /**
     * Create necessary database;
     *
     * @return void
     */
    public function create_datebase() {
        global $wpdb;

        $table_name      = $wpdb->prefix."training_addresses";
        $charset_collate = $wpdb->get_charset_collate();

        $sql             = "CREATE TABLE `{$table_name}` (
                          `id`      int(9) unsigned NOT NULL AUTO_INCREMENT,
                          `name`    varchar(100) NOT NULL,
                          `email`   varchar(200) NOT NULL,
                          `address` varchar(255) NOT NULL,
                          `phone`   varchar(255) NOT NULL,
                          `created_by`    bigint(20) unsigned NOT NULL,
                          `created_at` datetime NOT NULL,
                          PRIMARY KEY (`id`),
                          KEY `email` (`email`),
                          FOREIGN KEY (created_by) 
                                REFERENCES wp_users(ID)
                        ) {$charset_collate}; ";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH.'wp-admin/includes/upgrade.php';
        }

        dbDelta( $sql );
    }
}
