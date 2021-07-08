<?php

namespace Training;

/**
 * Class Assets
 *
 * @package Training
 */
class Assets {

    /**
     * Assets constructor.
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
    }

    /**
     * Get Scripts
     *
     * @return array[]
     */
    public function get_scripts() {
        return [
            'training_script' => [
                'src'     => TRAINING_ASSETS . '/js/frontend.js',
                'version' => filemtime( TRAINING_PATH . '/assets/js/frontend.js' ),
                'deps'    => [ 'jquery' ],
            ]
        ];
    }

    /**
     * Get all Style file
     *
     * @return array[]
     */
    public function get_styles() {
        return [
            'training_style' => [
                'src'     => TRAINING_ASSETS . '/css/frontend.css',
                'version' => filemtime( TRAINING_PATH . '/assets/css/frontend.css' ),
                'deps'    => [],
            ]
        ];
    }

    /**
     * Register Enqueue Assets
     *
     * @return void
     */
    public function enqueue_assets() {
        $scripts    = $this->get_scripts();

        foreach ($scripts as $handle => $script) {

            $deps = isset( $script[ 'deps' ] ) ? isset( $script[ 'deps' ] ) : false;

            wp_register_script( $handle, $script[ 'src' ], $deps, $script[ 'version' ], true );
        }

        $styles    = $this->get_styles();

        foreach ($styles as $handle => $style) {

            $deps = isset( $style[ 'deps' ] ) ? isset( $style[ 'deps' ] ) : false;

            wp_register_style( $handle, $style[ 'src' ], $deps, $style[ 'version' ] );
        }
    }
}