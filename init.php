<?php

/*
  Plugin Name:Simple FAQ
  Description:In This You Can add FAQ Category wise.
  Version: 1
  Author: wamasoftware
  Author URI: http://wamasoftware.com
 */
if (!defined('ABSPATH'))
    exit;
define('INCLUDEDIRPATH', plugin_dir_path(__FILE__));
define( 'SIMPLEFAQ_POST_TYPE', 'simfaq' );
Class Simplefaqinit {
    
     function simplefaqmenu() {
        $settings= new  SimpleFaqsettings();
        add_submenu_page( 'edit.php?post_type='.SIMPLEFAQ_POST_TYPE, __('Settings'), __('Settings'), 'manage_options', 'simfaq-designs', array($settings, 'settings'));
    }
    function getlistoffaq() {
        $getallfaq = new Getallfaq();
        add_shortcode('simplefaq', array($getallfaq, 'getallfaqshortcode'));
    }
    function simplefaq_custom_post_type() {
        $labels = array(
            'name' => _x('Simple FAQ', 'Post Type General Name', 'simple-faq'),
            'singular_name' => _x('Simple FAQ', 'Post Type Singular Name', 'simple-faq'),
            'menu_name' => __('Simple FAQ', 'simple-faq'),
            'parent_item_colon' => __('Parent FAQ', 'simple-faq'),
            'all_items' => __('All FAQ', 'simple-faq'),
            'view_item' => __('View FAQ', 'simple-faq'),
            'add_new_item' => __('Add New FAQ', 'simple-faq'),
            'add_new' => __('Add New', 'simple-faq'),
            'edit_item' => __('Edit FAQ', 'simple-faq'),
            'update_item' => __('Update FAQ', 'simple-faq'),
            'search_items' => __('Search FAQ', 'simple-faq'),
            'not_found' => __('Not Found', 'simple-faq'),
            'not_found_in_trash' => __('Not found in Trash', 'simple-faq'),);

        $args = array(
            'label' => __('FAQ', 'simple-faq'),
            'description' => __('Frequently asked questions', 'simple-faq'),
            'labels' => $labels,
            'supports' => array('title', 'editor', 'thumbnail', 'revisions',),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 25,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'menu_icon'   => 'dashicons-warning',
            'capability_type' => 'page',
        );
        register_post_type('simfaq', $args);
    }

    function simplefaq_post_taxomomy() {
        $labels = array(
            'name' => _x('Category', 'taxonomy general name'),
            'singular_name' => _x('Category', 'taxonomy singular name'),
            'search_items' => __('Search FAQ Category'),
            'all_items' => __('All FAQ Category'),
            'parent_item' => __('Parent FAQ Category'),
            'parent_item_colon' => __('Parent FAQ Category:'),
            'edit_item' => __('Edit FAQ Category'),
            'update_item' => __('Update FAQ Category'),
            'add_new_item' => __('Add New FAQ Category'),
            'new_item_name' => __('New FAQ Category'),
            'menu_name' => __('Category'),
        );
        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'simfaq'),
        );
        register_taxonomy('simfaq_texonamy', 'simfaq', $args);
        
    }
    function simfaq_cat_manage_columns($theme_columns) {
        $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __('Name'),
            'simfaq_category_shortcode' => __( 'FAQ Category Shortcode', 'simfaq' ),
            'slug' => __('Slug'),
            'posts' => __('Posts')
        );
        return $new_columns;
    }

    function simfaq_cat_columns($output, $column_name, $term_id) {
    $theme = get_term($term_id, 'simfaq_texonamy');
    switch ($column_name) {
        case 'title':
            echo get_the_title();
        break;

        case 'simfaq_category_shortcode':             
             echo '[simplefaq category="' . $term_id. '"]';
        break;
 
        default:
            break;
    }
    return $output;    
}   

function simfaq_custom_columns($output, $column_name, $term_id){
    $theme = get_term($term_id, 'simfaq_texonamy');
    switch ($column_name) {
        case 'title':
            echo get_the_title();
        break;

        case 'Views':             
             echo '[simplefaq category="' . $term_id. '"]';
        break;
 
        default:
            break;
    }
    return $output; 
    
}
    function simplefaq_add_js_head() {
        wp_enqueue_script('jqueryuijs', plugins_url("/js/jquery-ui-accordion.js", __FILE__));
        wp_enqueue_style('jqueryuicss', plugins_url("/css/jquery-ui-accordion.css", __FILE__));
    }
    function simplefaq_add_js_adminhead(){
         wp_enqueue_style('admincss', plugins_url("/css/style.css", __FILE__));
    }
    
    function getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}
 
// function to count views.
        function setPostViews($postID) {
            $count_key = 'post_views_count';
            $count_posts = wp_count_posts( 'simfaq' )->publish;
            echo $count_posts;
            $count = get_post_meta($postID, $count_key, true);
            if($count==''){
                $count = 0;
                delete_post_meta($postID, $count_key);
                add_post_meta($postID, $count_key, '0');
            }else{
                $count++;
                update_post_meta($postID, $count_key, $count);
            }
        }
    function posts_column_views($defaults){
    $defaults['post_views'] = __('Views');
    return $defaults;
    }
    function posts_custom_column_views($column_name, $id){
    if($column_name === 'post_views'){
        echo  $this->getPostViews(get_the_ID());
    }
    }
}

$simplefaq = new Simplefaqinit();
add_action('init', array($simplefaq, 'simplefaq_custom_post_type'), 0);
add_action('init', array($simplefaq, 'simplefaq_post_taxomomy'), 0);
add_action('admin_menu', array($simplefaq, 'simplefaqmenu'), 0);
add_filter('manage_simfaq_texonamy_custom_column', array($simplefaq,'simfaq_cat_columns'),10,3);
add_filter('manage_posts_columns', array($simplefaq,'posts_column_views'));
add_action('manage_posts_custom_column', array($simplefaq,'posts_custom_column_views'),5,2);
add_filter('manage_edit-simfaq_texonamy_columns', array($simplefaq,'simfaq_cat_manage_columns'));
add_action('init', array($simplefaq, 'getlistoffaq'), 0);
add_action('wp_head', array($simplefaq, 'simplefaq_add_js_head'));
add_action('admin_enqueue_scripts', array($simplefaq, 'simplefaq_add_js_adminhead'));
require_once(INCLUDEDIRPATH . '/includes/getallfaq.php');
require_once(INCLUDEDIRPATH . '/includes/settings.php');