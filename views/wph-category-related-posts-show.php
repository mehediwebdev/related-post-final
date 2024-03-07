<?php

if (!defined('ABSPATH')) {
    die;
}

?>

<li>
            <h2>
            <a href="<?php the_permalink() ?>">
            <?php the_title(); ?>
            </a>
            </h2>
    <div>
    <?php 
    if (has_post_thumbnail()) {
        the_post_thumbnail($post->ID, 'thumbnail');
    } else {   
        ?>                         
        <img src="' . WPH_RELATED_POSTS_URL . 'assets/public/images/wph-related-post-thumnail.jpg' . '" alt="' . __('Default Thumbnail', 'category_related_posts') . '" />
        <?php
    }
    ?>
    </div>

</li>
<?php 