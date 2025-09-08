<?php
/**
 * The template for displaying comments
 *
 * @package Trinity
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                
                <?php if (have_comments()) : ?>
                    <h2 class="comments-title h4 mb-4">
                        <?php
                        $comments_number = get_comments_number();
                        if ('1' === $comments_number) {
                            printf(
                                esc_html__('One comment on &ldquo;%s&rdquo;', 'trinity'),
                                '<span>' . get_the_title() . '</span>'
                            );
                        } else {
                            printf(
                                esc_html(_nx(
                                    '%1$s comment on &ldquo;%2$s&rdquo;',
                                    '%1$s comments on &ldquo;%2$s&rdquo;',
                                    $comments_number,
                                    'comments title',
                                    'trinity'
                                )),
                                number_format_i18n($comments_number),
                                '<span>' . get_the_title() . '</span>'
                            );
                        }
                        ?>
                    </h2>

                    <ol class="comment-list list-unstyled">
                        <?php
                        wp_list_comments([
                            'style'       => 'ol',
                            'short_ping'  => true,
                            'avatar_size' => 50,
                            'callback'    => 'trinity_comment_callback',
                        ]);
                        ?>
                    </ol>

                    <?php
                    the_comments_pagination([
                        'prev_text' => '<i class="fas fa-arrow-left"></i> ' . esc_html__('Previous', 'trinity'),
                        'next_text' => esc_html__('Next', 'trinity') . ' <i class="fas fa-arrow-right"></i>',
                        'class'     => 'comments-pagination',
                    ]);
                    ?>

                <?php endif; ?>

                <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
                    <p class="no-comments alert alert-info">
                        <?php esc_html_e('Comments are closed.', 'trinity'); ?>
                    </p>
                <?php endif; ?>

                <?php
                comment_form([
                    'title_reply'         => esc_html__('Leave a Comment', 'trinity'),
                    'title_reply_to'      => esc_html__('Leave a Reply to %s', 'trinity'),
                    'cancel_reply_link'   => esc_html__('Cancel Reply', 'trinity'),
                    'label_submit'        => esc_html__('Post Comment', 'trinity'),
                    'comment_field'       => '<div class="form-floating mb-3"><textarea id="comment" name="comment" class="form-control" placeholder="' . esc_attr__('Your comment...', 'trinity') . '" style="height: 120px" required></textarea><label for="comment">' . esc_html__('Your comment...', 'trinity') . '</label></div>',
                    'fields'              => [
                        'author' => '<div class="row"><div class="col-md-6"><div class="form-floating mb-3"><input id="author" name="author" type="text" class="form-control" placeholder="' . esc_attr__('Your name', 'trinity') . '" required><label for="author">' . esc_html__('Your name', 'trinity') . '</label></div></div>',
                        'email'  => '<div class="col-md-6"><div class="form-floating mb-3"><input id="email" name="email" type="email" class="form-control" placeholder="' . esc_attr__('Your email', 'trinity') . '" required><label for="email">' . esc_html__('Your email', 'trinity') . '</label></div></div></div>',
                        'url'    => '<div class="form-floating mb-3"><input id="url" name="url" type="url" class="form-control" placeholder="' . esc_attr__('Your website (optional)', 'trinity') . '"><label for="url">' . esc_html__('Your website (optional)', 'trinity') . '</label></div>',
                    ],
                    'submit_button'       => '<button name="%1$s" type="submit" id="%2$s" class="%3$s btn btn-primary">%4$s</button>',
                    'class_submit'        => 'submit',
                ]);
                ?>
                
            </div>
        </div>
    </div>
</div>
