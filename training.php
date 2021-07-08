<?php
namespace Training;
use Training\Frontend;
use Training\Admin\Menu;

/**
 * Plugin Name:       Training
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.10.3
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Abdullah Mahi
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       training
 * Domain Path:       /languages
 */
if ( ! defined( "ABSPATH" ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Main Plugin Class
 * @package Training
 */
final Class Training {

    /**
     * Version of plugin
     *
     * @var String
     */
    const VERSION = 1.0;

    /**
     * Class constructor.
     */
    public function __construct() {
        $this->defined_constaned();
        register_activation_hook(__FILE__, [ $this, 'activate'] );
        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Initialize a Singleton instance.
     *
     * @return false|Training
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function defined_constaned() {
        define( 'TRAINING_VERSION', self::VERSION );
        define( 'TRAINING_FILE', __FILE__ );
        define( 'TRAINING_PATH', __DIR__ );
        define( 'TRAINING_URL', plugins_url('', TRAINING_FILE ) );
        define( 'TRAINING_ASSETS', TRAINING_URL . '/assets' );
    }

    /**
     * initialize the  plugin
     *
     * @return void
     */
    public function init_plugin() {
        new Assets();
        if ( is_admin() ) {
            new Admin();
        }
        new Frontend();
        new API();
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function activate() {
        $installer = New Installer();
        $installer->run();
    }
}

/**
 * Initializes the main plugin
 *
 * @return false|Training
 */
function training() {
    return Training::init();
}

/**
 * kick-off the plugin
 */
training();