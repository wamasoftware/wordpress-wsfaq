<?php 
/**
 * @package cat-faq-plugin
 */

/**
 * Plugin Name: Category FAQ Plugin
 * Plugin URI: http://panda.com/plugin
 * Description: This is my first faq test plugin
 * Author: Wama Software
 * 
 */

defined( 'ABSPATH' ) or die( 'You cant access this plugin. Something went wrong...' );

class CatFaqPlugin {

    function __construct(){
        //Add custom post type and scripts
        add_action      (   'init',             array( $this, 'custom_post_type' ) );
        add_action      (   'init',             array( $this, 'custom_taxonomies_faq' ) );
        
        add_action      (   'add_meta_boxes',   array( $this, 'custom_post_metabox' ) );
        add_shortcode   (   'cat_faq_s',        array( $this, 'cat_faq_shortcode'   ) );
        add_shortcode   (   'wp_faq_s',         array( $this, 'cat_wp_faq_shortcode11' ) );
    }

    function custom_post_type(){
        $labels = array(
            'name'                  => 'CAT-FAQ',
            'singular_name'         => 'cat_faq',
            'add_new'               => 'Add FAQ',
            'all_items'             => 'All FAQs',
            'add_new_item'          => 'Add FAQ',
            'edit_item'             => 'Edit FAQ',
            'new_item'              => 'New FAQ',
            'view_item'             => 'View FAQ',
            'search_item'           => 'Serach FAQs',
            'not_found'             => 'No items found',
            'not_found_in_trash'    => 'No items found in trash',
            'parent_item_colon'     => 'Parent Item'
        );
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'menu_icon'             => 'dashicons-editor-help',
            'has_archive'           => true,
            'publicly_queryable'    => true,
            'query_var'             => true,
            'rewrite'               => true,
            'capability_type'       => 'post',
            'hierarchical'          => false,
            'supports'              => array(
                                        'title',
                                        'editor',
                                        'excerpt',
                                        'thumbnail',
                                        'revisions',
                                    ),
            'menu_position'         => 7,
            'exclude_from_search'   => false
        );
        register_post_type('cat_faq', $args);
    }

    function custom_taxonomies_faq(){
        //add new taxonomy hierarchical
    
        $labels = array(
            'name'                  => 'Category',
            'singular_name'         => 'Category',
            'search_items'          => 'Search Category',
            'all_items'             => 'All Categories',
            'parent_item'           => 'Parent Category',
            'parent_item_colon'     => 'Parent_Category:',
            'edit_item'             => 'Edit Category',
            'view_item'             => 'View Category',
            'update_item'           => 'Update Category',
            'add_new_item'          => 'Add New Category',
            'new_item_name'         => 'New Category Name',
            'menu_name'             => 'Category'
        );
    
        $args = array(
            'hierarchical'      => false,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array(
                                        'slug' => 'category'
                                )
        );
        register_taxonomy('category', 'cat_faq', $args);
    }

    //Add Custom Meta Box
    function custom_post_metabox(){
        add_meta_box('cat_faq_shortcode', 'FAQ Shortcode', array( $this, 'custom_shortcode'), 'cat_faq','side','core');
    }

    //Add Custom Shortcode field in Plugin
    function custom_shortcode(){
        $args = array(
            'orderby'   => 'name',
            'parent'    => 0
        );
        
        $categories = get_categories( $args );

        wp_nonce_field('action_shortcode_cat_faq', 'custom_cat_faq_shortcode');
    
        $value= get_post_meta($args, '_shortcode_cat_faq_key', true);

        foreach($categories as $category){
            if($category->term_id == get_the_category($post->ID)[0]->term_id){
                echo '<input type="text" id="cat_faq_shortcode_field" name="cat_faq_shortcode_field" value="[wp_faq_s id='. $category->term_id .']" size="25" readonly="readonly"/>';
            }
        }        
    }

    //Add Shortcode [cat_faq_s]
    function cat_faq_shortcode($atts){
        $args = array(
            'orderby'   => 'name',
            'parent'    => 0
        );
        
        $categories = get_categories( $args );
        
        ?>
        
        <select id="selectdrp">
            <option value="select">Select Category</option>
            <?php
                foreach ( $categories as $category ) {
            ?>
                <option value="<?php echo $category->term_id ?>"><?php echo $category->name; ?></option>
            <?php
                }
            ?>
        </select>
        
        <?php require_once 'cat-faq-searchform.php'; 

        if(isset($_GET['sf']) || strlen($_GET['sf'])){
	        echo '<span class="subtitle">';
	        printf( __('<b>Search Results for : '.$_GET['sf'].'</b><br/><br/>'));
	        // wp_html_excerpt( esc_html( wp_unslash( $_GET['sf'] ) ), 50 );
	        echo '</span>';

	        $_name = $_GET['sf'] != '' ? $_GET['sf'] : '';
        }

        ?>
        
        <br/><br/>
        
        <?php
        
        wp_enqueue_style( 'cat_faq_s', plugins_url('cat-faq-plugin/css/style.css') );
        wp_enqueue_script( 'cat_faq_s', plugins_url('cat-faq-plugin/js/jquery.js'), array('jquery') );
        
        $args = array(
            'post_type'         =>  'cat_faq',
            'posts_per_page'    =>  -1,
            's'                =>  $_GET['sf']
        );   

        $query = new WP_Query($args);
        ?>
        
        <?php
            foreach ( $categories as $category ) { 
            ?>
                <p class="cat_<?php echo $category->term_id ?> cat_all"><b><?php echo $category->name; ?> </b></p>
            <?php
            if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
            ?>
                <?php $abc = get_the_category($query->$post_id); ?>
                <?php if($category->term_id == get_the_category($post->ID)[0]->term_id){ ?>
                    <div class="cat_<?php echo $abc[0]->term_id; ?> cat_all" style="margin-bottom:15px;">
                        <div class="accordion"><?php echo get_the_title(); ?></div>
                        <div class="answer"><?php echo get_the_content(); ?></div>
                    </div>
            <?php 
                }
                endwhile; ?>
            
            <?php
                endif;
            }
        ?>
        <?php
    }

    //Add Custom Shortcode [wp_faq_s id=]
    function cat_wp_faq_shortcode11($attr){

        wp_enqueue_style( 'wp_faq_s', plugins_url('cat-faq-plugin/css/style.css') );
        wp_enqueue_script( 'wp_faq_s', plugins_url('cat-faq-plugin/js/jquery.js'), array('jquery') );
        
        shortcode_atts(
            array(
                'id'    =>  1
            ), $attr
        );

        $args = array(
            'post_type'         =>  'cat_faq',
            'posts_per_page'    =>  -1
        );

        $query = new WP_Query($args);

        $cat_args = array(
            'orderby'   => 'name',
            'parent'    => 0
        );
        
        $categories = get_categories( $cat_args );

        foreach($categories as $category){
            if($attr['id'] == $category->term_id){
            ?>
                <p><b><?php echo $category->name; ?></b></p>
            <?php
            }
        }

        if($query->have_posts()) : while($query->have_posts()) : $query->the_post();
            if($attr['id'] == get_the_category($post->ID)[0]->term_id){       
            ?>
                <div class="cat_<?php echo $abc[0]->term_id; ?> cat_all" style="margin-bottom:15px;">
                    <div class="accordion"><?php echo get_the_title(); ?></div>
                    <div class="answer"><?php echo get_the_content(); ?></div>
                </div>
            <?php
            }
        endwhile;
    endif;
    }
}

if( class_exists( 'CatFaqPlugin' ) ){
    $catfaqplugin = new CatFaqPlugin();
}



/**
 *  activation 
*/
require_once plugin_dir_path( __FILE__ ). 'include/cat-faq-plugin-activate.php';
register_activation_hook( __FILE__, array( 'Cat_Faq_Activate', 'activate' ) );


/**
 *  deactivation
 */
require_once plugin_dir_path( __FILE__ ). 'include/cat-faq-plugin-deactivate.php';
register_deactivation_hook( __FILE__, array( 'Cat_Faq_Deactivate', 'deactivate' ) );


/**
 *  uninstall
 */
register_uninstall_hook( __FILE__, array( $catfaqplugin, 'uninstall' ) );