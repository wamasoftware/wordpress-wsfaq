<?php
if (!defined('ABSPATH'))
    exit;

class Getallfaq {

    function getallfaqshortcode($attribute) {

        extract(shortcode_atts(array(
            "limit" => '',
            "category" => '',
                        ), $attribute));
        if ($limit) {
            $posts_per_page = $limit;
        } else {
            $posts_per_page = '-1';
        }

        if ($category) {
            $cat = $category;
        } else {
            $cat = '';
        }

        $singleopen = 'true';
        $transitionSpeed = '300';
        ob_start();
        $post_type = 'simfaq';
        $orderby = 'post_date';
        $order = 'DESC';
        $args = array(
            'post_type' => $post_type,
            'orderby' => $orderby,
            'order' => $order,
            'posts_per_page' => $posts_per_page,
        );
        if ($cat != "") {
            $args['tax_query'] = array(array('taxonomy' => 'simfaq_texonamy', 'field' => 'term_id', 'terms' => $cat));
        }

        $query = new WP_Query($args);
        $post_count = $query->post_count;
        $i = 1;
        if ($post_count > 0) :
            ?>
            <div class="simple-faq-accordion" data-accordion-group>	
                <?php while ($query->have_posts()) : $query->the_post();
                    ?>			  
                    <div data-accordion class="simple-faq-main">

                        <div data-control class="simple-faq-title"><h4> <?php the_title(); ?></h4></div>
                        <div data-content>
                            <?php
                            if (function_exists('has_post_thumbnail') && has_post_thumbnail()) {

                                the_post_thumbnail('thumbnail');
                            }
                            ?>
                            <div class="faq-content"><?php the_content(); ?></div>
                        </div>
                    </div>
                    <?php
                    $i++;
                endwhile;
                ?>
            </div>
            <?php
        endif;
        wp_reset_query();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('.simple-faq-accordion [data-accordion]').accordionfaq({
                    singleOpen: <?php echo $singleopen; ?>,
                    transitionEasing: 'ease',
                    transitionSpeed: <?php echo $transitionSpeed; ?>
                });
            });

        </script>
        <?php
        return ob_get_clean();
    }

}
