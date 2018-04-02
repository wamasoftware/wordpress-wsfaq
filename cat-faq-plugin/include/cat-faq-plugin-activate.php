<?php

/**
 * @package cat-faq-plugin
 */

class Cat_Faq_Activate{
    public static function activate(){
        flush_rewrite_rules();
    }
}