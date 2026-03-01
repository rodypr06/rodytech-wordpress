<?php
/**
 * Comments Template
 */

if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">
    <h3 class="comments-title">
        <?php
            $comment_count = get_comments_number();
            if ($comment_count === '0') {
                echo 'No comments yet';
            } elseif ($comment_count === '1') {
                echo '1 comment';
            } else {
                echo $comment_count . ' comments';
            }
        ?>
    </h3>

    <?php if (have_comments()) : ?>
        <ol class="comment-list">
            <?php
                wp_list_comments(array(
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 56,
                    'callback'    => 'rodytech_comment_callback',
                ));
            ?>
        </ol>

        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
            <nav class="comment-navigation">
                <?php
                    paginate_comments_links(array(
                        'prev_text' => '← Older',
                        'next_text' => 'Newer →',
                    ));
                ?>
            </nav>
        <?php endif; ?>

    <?php endif; ?>

    <?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
        <p class="no-comments">Comments are closed.</p>
    <?php endif; ?>

    <?php
        $comment_args = array(
            'title_reply'          => 'Leave a comment',
            'title_reply_to'       => 'Reply to %s',
            'cancel_reply_link'    => 'Cancel reply',
            'label_submit'         => 'Post comment',
            'comment_field'        => '<p class="comment-form-comment"><label for="comment">Comment</label><textarea id="comment" name="comment" cols="45" rows="6" required></textarea></p>',
            'fields'               => array(
                'author' => '<p class="comment-form-author"><label for="author">Name</label><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" required></p>',
                'email'  => '<p class="comment-form-email"><label for="email">Email</label><input id="email" name="email" type="email" value="' . esc_attr($commenter['comment_author_email']) . '" required></p>',
            ),
            'class_submit'         => 'submit-btn',
        );
        comment_form($comment_args);
    ?>
</div>

<?php
// Custom comment callback
function rodytech_comment_callback($comment, $args, $depth) {
    $tag = ($args['style'] === 'div') ? 'div' : 'li';
?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class('comment'); ?>>
        <article class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author">
                    <?php echo get_avatar($comment, 56, '', '', array('class' => 'comment-avatar')); ?>
                    <div class="comment-author-info">
                        <span class="comment-author-name"><?php comment_author_link(); ?></span>
                        <time class="comment-date"><?php echo get_comment_date('M j, Y'); ?></time>
                    </div>
                </div>
            </footer>
            
            <div class="comment-content">
                <?php comment_text(); ?>
            </div>
            
            <div class="comment-actions">
                <?php
                    comment_reply_link(array_merge($args, array(
                        'depth'     => $depth,
                        'max_depth' => $args['max_depth'],
                        'reply_text' => 'Reply',
                    )));
                ?>
            </div>
        </article>
<?php
}
