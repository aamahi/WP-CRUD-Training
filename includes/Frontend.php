<?php


namespace Training;
use Training\Frontend\Shortcode;

/**
 * Frontend handler class
 */
class Frontend {

    /**
     * Frontend constructor.
     */
    public function __construct() {
        new Shortcode();
    }

}