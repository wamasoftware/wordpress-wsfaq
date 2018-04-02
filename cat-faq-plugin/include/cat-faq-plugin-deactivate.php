<?php

/**
 * @package cat-faq-plugin
 */

class Cat_Faq_Deactivate{
    public static function deactivate(){
        flush_rewrite_rules();
    }
}