<?php
namespace Training\Frontend;

/**
 * Class Shortcode
 *
 * @package Training\Frontend
 */
class Shortcode
{
    

    public function __construct() {
        add_shortcode('training' , [ $this , 'render_shortcode']);
    }

    /**
     * Render Shortcode
     *
     * @param $args
     *
     * @param string $content
     *
     * @return string
     */
    public function render_shortcode($args , $content ='') {
        wp_enqueue_script( 'training_script' );
        wp_enqueue_style( 'training_style' );
        return "<p class='training_shortcode'>Hello This is Trainging Shortcode</p>";
    }

}