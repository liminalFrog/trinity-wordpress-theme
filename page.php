<?php
/**
 * The template for displaying all pages
 *
 * @package Trinity
 */

get_header(); ?>

<main id="main" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <?php if (has_post_thumbnail() && !get_post_meta(get_the_ID(), '_trinity_hide_title', true)) : ?>
                            <div class="featured-image mb-4">
                                <?php the_post_thumbnail('large', ['class' => 'img-fluid rounded']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="entry-content">
                            <?php
                            the_content();
                            
                            wp_link_pages([
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'trinity'),
                                'after'  => '</div>',
                            ]);
                            ?>
                        </div>
                        
                        <?php if (get_edit_post_link()) : ?>
                            <footer class="entry-footer mt-4">
                                <div class="edit-link">
                                    <?php
                                    edit_post_link(
                                        sprintf(
                                            wp_kses(
                                                __('Edit <span class="screen-reader-text">%s</span>', 'trinity'),
                                                ['span' => ['class' => []]]
                                            ),
                                            get_the_title()
                                        ),
                                        '<span class="edit-link">',
                                        '</span>'
                                    );
                                    ?>
                                </div>
                            </footer>
                        <?php endif; ?>
                    </article>
                    
                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</main>

<?php
get_footer();
?>
