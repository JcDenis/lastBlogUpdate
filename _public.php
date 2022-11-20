<?php
/**
 * @brief lastBlogUpdate, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugin
 *
 * @author Jean-Christian Denis, Pierre Van Glabeke
 *
 * @copyright Jean-Christian Denis
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('DC_RC_PATH')) {
    return null;
}

require __DIR__ . '/_widgets.php';

function lastBlogUpdateWidgetPublic($w)
{
    if ($w->offline) {
        return null;
    }

    # Nothing to display
    if ($w->homeonly == 1 && dcCore::app()->url->type != 'default'
    || $w->homeonly  == 2 && dcCore::app()->url->type == 'default'
    || !$w->blog_show     && !$w->post_show && !$w->comment_show && !$w->media_show
    || !$w->blog_text     && !$w->post_text && !$w->comment_text && !$w->media_text) {
        return null;
    }

    $blog = $post = $comment = $media = $addons = '';

    # Blog
    if ($w->blog_show && $w->blog_text) {
        $title = $w->blog_title ? sprintf('<strong>%s</strong>', html::escapeHTML($w->blog_title)) : '';
        $text  = dt::str($w->blog_text, dcCore::app()->blog->upddt, dcCore::app()->blog->settings->system->blog_timezone);
        $blog  = sprintf('<li>%s%s</li>', $title, $text);
    }

    # Post
    if ($w->post_show && $w->post_text) {
        $rs = dcCore::app()->blog->getPosts(['limit' => 1, 'no_content' => true]);
        if (!$rs->isEmpty()) {
            $title = $w->post_title ? sprintf('<strong>%s</strong>', html::escapeHTML($w->post_title)) : '';
            $text  = dt::str($w->post_text, strtotime($rs->post_upddt), dcCore::app()->blog->settings->system->blog_timezone);
            $link  = $rs->getURL();
            $over  = $rs->post_title;

            $post = sprintf('<li>%s<a href="%s" title="%s">%s</a></li>', $title, $link, $over, $text);
        }
    }

    # Comment
    if ($w->comment_show && $w->comment_text) {
        $rs = dcCore::app()->blog->getComments(['limit' => 1, 'no_content' => true]);
        if (!$rs->isEmpty()) {
            $title = $w->comment_title ? sprintf('<strong>%s</strong>', html::escapeHTML($w->comment_title)) : '';
            $text  = dt::str($w->comment_text, strtotime($rs->comment_upddt), dcCore::app()->blog->settings->system->blog_timezone);
            $link  = dcCore::app()->blog->url . dcCore::app()->getPostPublicURL($rs->post_type, html::sanitizeURL($rs->post_url)) . '#c' . $rs->comment_id;
            $over  = $rs->post_title;

            $comment = sprintf('<li>%s<a href="%s" title="%s">%s</a></li>', $title, $link, $over, $text);
        }
    }

    # Media
    if ($w->media_show && $w->media_text) {
        $rs = dcCore::app()->con->select(
            'SELECT media_upddt FROM ' . dcCore::app()->prefix . dcMedia::MEDIA_TABLE_NAME . ' ' .
            "WHERE media_path='" . dcCore::app()->con->escape(dcCore::app()->blog->settings->system->public_path) . "' " .
            'ORDER BY media_upddt DESC ' . dcCore::app()->con->limit(1)
        );

        if (!$rs->isEmpty()) {
            $title = $w->media_title ? sprintf('<strong>%s</strong>', html::escapeHTML($w->media_title)) : '';
            $text  = dt::str($w->media_text, strtotime($rs->f('media_upddt')), dcCore::app()->blog->settings->system->blog_timezone);

            $media = sprintf('<li>%s%s</li>', $title, $text);
        }
    }

    # --BEHAVIOR-- lastBlogUpdateWidgetParse
    $addons = dcCore::app()->callBehavior('lastBlogUpdateWidgetParse', $w);

    # Nothing to display
    if (!$blog && !$post && !$comment && !$media && !$addons) {
        return null;
    }

    # Display
    return $w->renderDiv(
        $w->content_only,
        'lastblogupdate ' . $w->class,
        '',
        ($w->title ? $w->renderTitle(html::escapeHTML($w->title)) : '') .
            sprintf('<ul>%s</ul>', $blog . $post . $comment . $media . $addons)
    );
}
