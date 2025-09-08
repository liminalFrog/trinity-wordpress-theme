<?php
/**
 * The template for displaying all single posts
 *
 * @package Trinity
 */

get_header(); ?>

<main id="main" class="site-main single-post">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="featured-image mb-4">
                                <?php the_post_thumbnail('large', ['class' => 'img-fluid rounded']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <header class="entry-header mb-4">
                            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                            
                            <div class="entry-meta text-muted">
                                <span class="posted-on">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo get_the_date(); ?>
                                    </time>
                                </span>
                                
                                <span class="byline ms-3">
                                    <i class="far fa-user me-1"></i>
                                    <span class="author vcard">
                                        <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                            <?php echo get_the_author(); ?>
                                        </a>
                                    </span>
                                </span>
                                
                                <?php if (has_category()) : ?>
                                    <span class="cat-links ms-3">
                                        <i class="far fa-folder me-1"></i>
                                        <?php the_category(', '); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if (has_tag()) : ?>
                                    <span class="tags-links ms-3">
                                        <i class="far fa-tags me-1"></i>
                                        <?php the_tags('', ', '); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </header>
                        
                        <div class="entry-content">
                            <?php
                            the_content();
                            
                            wp_link_pages([
                                'before' => '<div class="page-links mt-4"><span class="page-links-title">' . esc_html__('Pages:', 'trinity') . '</span>',
                                'after'  => '</div>',
                                'link_before' => '<span class="page-number">',
                                'link_after'  => '</span>',
                            ]);
                            ?>
                        </div>
                        
                        <footer class="entry-footer mt-5 pt-4 border-top">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <?php if (get_edit_post_link()) : ?>
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
                                                '<span class="edit-link btn btn-outline-secondary btn-sm">',
                                                '</span>'
                                            );
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-md-6 text-md-end">
                                    <div class="social-share">
                                        <span class="share-label me-2"><?php esc_html_e('Share:', 'trinity'); ?></span>
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                                           class="btn btn-outline-primary btn-sm me-1" 
                                           target="_blank" 
                                           rel="noopener">
                                            <i class="fab fa-facebook-f"></i>
                                        </a>
                                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                                           class="btn btn-outline-info btn-sm me-1" 
                                           target="_blank" 
                                           rel="noopener">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" 
                                           class="btn btn-outline-primary btn-sm" 
                                           target="_blank" 
                                           rel="noopener">
                                            <i class="fab fa-linkedin-in"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </footer>
                    </div>
                </div>
            </div>
        </article>
        
        <!-- Author Bio -->
        <?php if (get_the_author_meta('description')) : ?>
            <div class="author-bio bg-light py-5 mt-5">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="author-bio-content d-flex">
                                <div class="author-avatar me-4">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 80, '', '', ['class' => 'rounded-circle']); ?>
                                </div>
                                <div class="author-info">
                                    <h4 class="author-name"><?php echo get_the_author(); ?></h4>
                                    <p class="author-description"><?php echo get_the_author_meta('description'); ?></p>
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" 
                                       class="btn btn-outline-primary btn-sm">
                                        <?php esc_html_e('View all posts', 'trinity'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Post Navigation -->
        <div class="post-navigation py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <nav class="navigation post-navigation" role="navigation">
                            <h2 class="screen-reader-text"><?php esc_html_e('Post navigation', 'trinity'); ?></h2>
                            <div class="nav-links d-flex justify-content-between">
                                <?php
                                $prev_post = get_previous_post();
                                $next_post = get_next_post();
                                
                                if ($prev_post) : ?>
                                    <div class="nav-previous">
                                        <a href="<?php echo get_permalink($prev_post); ?>" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            <?php echo esc_html($prev_post->post_title); ?>
                                        </a>
                                    </div>
                                <?php endif;
                                
                                if ($next_post) : ?>
                                    <div class="nav-next">
                                        <a href="<?php echo get_permalink($next_post); ?>" class="btn btn-outline-secondary">
                                            <?php echo esc_html($next_post->post_title); ?>
                                            <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        ?>
        
    <?php endwhile; ?>
</main>

<?php
get_footer();
?>
