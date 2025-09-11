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
                <?php 
                $post_count = 0;
                $posts_per_row = 2;
                
                while (have_posts()) : the_post(); 
                    $post_count++;
                    $post_categories = get_the_category();
                    $primary_category = !empty($post_categories) ? $post_categories[0] : null;
                    $is_featured = $post_count === 1; // First post is featured
                    
                    // Featured post gets full width
                    if ($is_featured) : ?>
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card featured-post h-100 position-relative">
                                    <a href="<?php the_permalink(); ?>" class="stretched-link"></a>
                                    
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('large', [
                                            'class' => 'card-img-top',
                                            'style' => 'height: 300px; object-fit: cover;'
                                        ]); ?>
                                    <?php endif; ?>
                                    
                                    <div class="card-body">
                                        <?php if ($primary_category) : ?>
                                            <strong class="d-inline-block mb-2 text-primary-emphasis">
                                                <?php echo esc_html($primary_category->name); ?>
                                            </strong>
                                        <?php endif; ?>
                                        
                                        <h1 class="card-title display-4 fst-italic mb-3">
                                            <?php the_title(); ?>
                                        </h1>
                                        
                                        <p class="card-text lead mb-3">
                                            <?php echo wp_trim_words(get_the_excerpt(), 30, '...'); ?>
                                        </p>
                                        
                                        <span class="btn btn-primary">
                                            <?php esc_html_e('Continue reading...', 'trinity'); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else :
                        // Regular posts in card layout
                        // Start new row every 2 posts (excluding featured post)
                        $regular_post_count = $post_count - 1;
                        if ($regular_post_count % $posts_per_row === 1) : ?>
                            <div class="row mb-4">
                        <?php endif; ?>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 position-relative">
                                <a href="<?php the_permalink(); ?>" class="stretched-link"></a>
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="row g-0 h-100">
                                        <!-- Image on left for desktop, top for mobile -->
                                        <div class="col-12 col-md-4">
                                            <?php the_post_thumbnail('medium', [
                                                'class' => 'card-img img-fluid h-100',
                                                'style' => 'object-fit: cover;'
                                            ]); ?>
                                        </div>
                                        
                                        <!-- Content on right for desktop, bottom for mobile -->
                                        <div class="col-12 col-md-8">
                                            <div class="card-body d-flex flex-column h-100">
                                                <?php if ($primary_category) : ?>
                                                    <strong class="d-inline-block mb-2 text-primary-emphasis">
                                                        <?php echo esc_html($primary_category->name); ?>
                                                    </strong>
                                                <?php endif; ?>
                                                
                                                <h5 class="card-title">
                                                    <?php the_title(); ?>
                                                </h5>
                                                
                                                <div class="mb-2 text-body-secondary">
                                                    <small><?php echo get_the_date('M j, Y'); ?></small>
                                                </div>
                                                
                                                <p class="card-text flex-grow-1">
                                                    <?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?>
                                                </p>
                                                
                                                <div class="mt-auto">
                                                    <span class="btn btn-outline-primary btn-sm">
                                                        <?php esc_html_e('Read More', 'trinity'); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <!-- No image version -->
                                    <div class="card-body d-flex flex-column h-100">
                                        <?php if ($primary_category) : ?>
                                            <strong class="d-inline-block mb-2 text-primary-emphasis">
                                                <?php echo esc_html($primary_category->name); ?>
                                            </strong>
                                        <?php endif; ?>
                                        
                                        <h5 class="card-title">
                                            <?php the_title(); ?>
                                        </h5>
                                        
                                        <div class="mb-2 text-body-secondary">
                                            <small><?php echo get_the_date('M j, Y'); ?></small>
                                        </div>
                                        
                                        <p class="card-text flex-grow-1">
                                            <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
                                        </p>
                                        
                                        <div class="mt-auto">
                                            <span class="btn btn-outline-primary btn-sm">
                                                <?php esc_html_e('Read More', 'trinity'); ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php 
                        // Close row every 2 posts or at the end
                        if ($regular_post_count % $posts_per_row === 0 || !have_posts()) : ?>
                            </div>
                        <?php endif;
                    endif;
                    
                endwhile; ?>
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
