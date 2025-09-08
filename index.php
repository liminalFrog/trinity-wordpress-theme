<?php
/**
 * Trinity Theme Index
 * 
 * @package Trinity
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <header class="page-header mb-5 text-center">
                    <h1 class="page-title">
                        <?php
                        if (is_home() && get_option('page_for_posts')) {
                            echo get_the_title(get_option('page_for_posts'));
                        } else {
                            esc_html_e('Blog', 'trinity');
                        }
                        ?>
                    </h1>
                    <?php if (is_category()) : ?>
                        <p class="lead text-muted"><?php single_cat_title(); ?></p>
                    <?php elseif (is_tag()) : ?>
                        <p class="lead text-muted"><?php single_tag_title(); ?></p>
                    <?php elseif (is_author()) : ?>
                        <p class="lead text-muted"><?php esc_html_e('Posts by', 'trinity'); ?> <?php the_author(); ?></p>
                    <?php endif; ?>
                </header>
            </div>
        </div>
        
        <?php if (have_posts()) : ?>
            <div class="blog-posts">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('blog-post-item' . (has_post_thumbnail() ? ' has-post-thumbnail' : '')); ?>>
                        <div class="blog-post-content">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="blog-post-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', ['class' => 'blog-post-img']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="blog-post-info">
                                <h2 class="blog-post-title">
                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                                
                                <div class="blog-post-meta">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        <?php echo get_the_date(); ?>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-user me-1"></i>
                                        <?php the_author(); ?>
                                        <?php if (get_comments_number() > 0) : ?>
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-comments me-1"></i>
                                            <?php comments_number('0', '1', '%'); ?>
                                        <?php endif; ?>
                                    </small>
                                </div>
                                
                                <div class="blog-post-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                                    <?php esc_html_e('Read More', 'trinity'); ?>
                                </a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <?php trinity_pagination(); ?>
            
        <?php else : ?>
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        <h4 class="alert-heading"><?php esc_html_e('Nothing found', 'trinity'); ?></h4>
                        <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'trinity'); ?></p>
                        <?php get_search_form(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_sidebar();
get_footer();
?>
