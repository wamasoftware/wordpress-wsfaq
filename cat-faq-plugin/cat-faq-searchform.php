<?php
/**
 * Template for displaying search forms in Twenty Seventeen
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>

<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); 
	wp_enqueue_script( 'cat_faq_s', plugins_url('cat-faq-plugin/js/jquery.js'), array('jquery') );
?>

<form role="search" method="get" class="search-form" action="<?php echo the_permalink(); ?>" id="formid">	
	<label for="<?php echo $unique_id; ?>">		
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label'); ?></span>
	</label>
	<input type="text" id="searchid" class="search-field" style="width:100%" placeholder="Search Here" value="<?php echo get_search_query(); ?>" name="sf" />
</form>