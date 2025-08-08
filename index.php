<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 */

get_header(); ?>

<main id="primary" class="site-main">

    <?php if (have_posts()) : ?>
        
        <?php while (have_posts()) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <header class="entry-header">
                    <?php if (is_singular()) : ?>
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    <?php else : ?>
                        <h2 class="entry-title">
                            <a href="<?php the_permalink(); ?>" rel="bookmark">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                    <?php endif; ?>
                    
                    <div class="entry-meta">
                        <span class="posted-on">
                            Posted on <time datetime="<?php echo get_the_date('c'); ?>">
                                <?php echo get_the_date(); ?>
                            </time>
                        </span>
                        <span class="byline">
                            by <span class="author"><?php the_author(); ?></span>
                        </span>
                        <?php if (!is_single() && !post_password_required() && (comments_open() || get_comments_number())) : ?>
                            <span class="comments-link">
                                <?php comments_popup_link('Leave a comment', '1 Comment', '% Comments'); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </header>

                <div class="entry-content">
                    <?php if (is_single()) : ?>
                        <?php the_content(); ?>
                    <?php else : ?>
                        <?php the_excerpt(); ?>
                        <p><a href="<?php the_permalink(); ?>" class="read-more">Read More →</a></p>
                    <?php endif; ?>
                </div>

            </article>

        <?php endwhile; ?>

        <div class="pagination">
            <?php
            the_posts_pagination(array(
                'prev_text' => '← Previous',
                'next_text' => 'Next →',
            ));
            ?>
        </div>

    <?php else : ?>

        <section class="no-results not-found">
            <header class="page-header">
                <h1 class="page-title">Nothing here</h1>
            </header>

            <div class="page-content">
                <p>It looks like nothing was found at this location. Maybe try searching?</p>
                <?php get_search_form(); ?>
            </div>
        </section>

    <?php endif; ?>

</main>

<?php get_footer(); ?>
