<?php
/*
 * Plugin Name:       Related Posts
 * Plugin URI:        
 * Description:       Related Posts is an essential plugin for website users; it displays related posts on the post details page.
 * Version:           1.0.0
 * Requires at least: 6.2
 * Requires PHP:      7.2
 * Author:            Mehedi Hasan
 * Author URI:        
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        
 * Text Domain:       related-posts
 * Domain Path:       /languages
 */

if (!defined('ABSPATH')) {
    die;
}

define('WPH_RELATED_POSTS_PATH', plugin_dir_path(__FILE__));
define('WPH_RELATED_POSTS_URL', plugin_dir_url(__FILE__));
define('WPH_RELATED_POSTS_VERSION', '1.0.0');

if (!class_exists('WPH_Related_Posts')) {
    class WPH_Related_Posts
    {
        public function __construct()
        {
            add_action( 'init', array( $this, 'init' ) );          
        }

        public function init(){
          $this->define_constants();
          $this->load_textdomain();

          add_filter('the_content', array($this, 'display_related_posts'));
        }

       

        public function define_constants()
        {
            
        }

        public function display_related_posts($p_content){


            ob_start();
            if ( is_single() ) {
               
                global $post;
                $categories = get_the_category($post->ID);
               
                if ( ! empty( $categories ) ) {
                    $related_posts_content = '';
                    foreach ( $categories as $category ) {
                        $related_posts = new WP_Query(array(
                            'category__in' => array($category->term_id),
                            'post__not_in' => array($post->ID),
                            'posts_per_page' => 5,
                            'ignore_sticky_posts' => 1
                        ));
                        if ( $related_posts->have_posts() ) {
                            echo '<h2>' . __('Related Posts', 'category_related_posts') . '</h2>';
                            echo '<ul>';
                            while ( $related_posts->have_posts() ) {
                                $related_posts->the_post();
                                require_once( WPH_RELATED_POSTS_PATH . 'views/wph-category-related-posts-show.php' );
                            }
                            echo '</ul>';
                        }
                        wp_reset_postdata();
                    }
                
                }
            }

            $content = ob_get_clean();
            $p_content .= $content;
            
            return $p_content;
        }

        public static function activate()
        {
            // Activation tasks, if any
        }

        public static function deactivate()
        {
            flush_rewrite_rules();
        }

        public static function uninstall()
        {
            // Uninstall tasks, if any
        }

        public function load_textdomain()
        {
            load_plugin_textdomain(
                'related-posts',
                false,
                dirname(plugin_basename(__FILE__)) . '/languages/'
            );
        }
    }
}

if (class_exists('WPH_Related_Posts')) {
    register_activation_hook(__FILE__, array('WPH_Related_Posts', 'activate'));
    register_deactivation_hook(__FILE__, array('WPH_Related_Posts', 'deactivate'));
    register_uninstall_hook(__FILE__, array('WPH_Related_Posts', 'uninstall'));

    $wph_related_posts = new WPH_Related_Posts();
}
