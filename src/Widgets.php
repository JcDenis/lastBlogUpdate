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
declare(strict_types=1);

namespace Dotclear\Plugin\lastBlogUpdate;

use dcCore;
use dcMedia;
use Dotclear\Database\Statement\SelectStatement;
use Dotclear\Helper\Date;
use Dotclear\Helper\Html\Html;
use Dotclear\Plugin\widgets\WidgetsStack;
use Dotclear\Plugin\widgets\WidgetsElement;

class Widgets
{
    public static function initWidgets(WidgetsStack $w): void
    {
        $w
            ->create(
                'lastblogupdate',
                __('Last blog update'),
                [self::class, 'parseWidget'],
                null,
                'Show the dates of last updates of your blog in a widget'
            )
            ->addTitle(__('Dates of lastest updates'))
            ->setting(
                'blog_show',
                __('Show blog update'),
                1,
                'check'
            )
            ->setting(
                'blog_title',
                __('Title for blog update:'),
                __('Blog:'),
                'text'
            )
            ->setting(
                'blog_text',
                __('Text for blog update:'),
                __('%Y-%m-%d %H:%M'),
                'text'
            )
            ->setting(
                'post_show',
                __('Show entry update'),
                1,
                'check'
            )
            ->setting(
                'post_title',
                __('Title for entries update:'),
                __('Entries:'),
                'text'
            )
            ->setting(
                'post_text',
                __('Text for entries update:'),
                __('%Y-%m-%d %H:%M'),
                'text'
            )
            ->setting(
                'comment_show',
                __('Show comment update'),
                1,
                'check'
            )
            ->setting(
                'comment_title',
                __('Title for comments update:'),
                __('Comments:'),
                'text'
            )
            ->setting(
                'comment_text',
                __('Text for comments update:'),
                __('%Y-%m-%d %H:%M'),
                'text'
            )
            ->setting(
                'media_show',
                __('Show media update'),
                1,
                'check'
            )
            ->setting(
                'media_title',
                __('Title for media update:'),
                __('Medias:'),
                'text'
            )
            ->setting(
                'media_text',
                __('Text for media update:'),
                __('%Y-%m-%d %H:%M'),
                'text'
            );

        # --BEHAVIOR-- lastBlogUpdateWidgetInit
        dcCore::app()->callBehavior('lastBlogUpdateWidgetInit', $w);

        $w->lastblogupdate
            ->addHomeOnly()
            ->addContentOnly()
            ->addClass()
            ->addOffline();
    }

    public static function parseWidget(WidgetsElement $w): string
    {
        if ($w->offline || is_null(dcCore::app()->blog)) {
            return '';
        }

        # Nothing to display
        if (!$w->checkHomeOnly(dcCore::app()->url->type)
        || !$w->blog_show && !$w->post_show && !$w->comment_show && !$w->media_show
        || !$w->blog_text && !$w->post_text && !$w->comment_text && !$w->media_text) {
            return '';
        }

        $blog = $post = $comment = $media = $addons = '';
        $tz   = is_string(dcCore::app()->blog->settings->get('system')->get('blog_timezone')) ? dcCore::app()->blog->settings->get('system')->get('blog_timezone') : 'UTC';

        # Blog
        if ($w->blog_show && $w->blog_text && is_numeric(dcCore::app()->blog->upddt)) {
            $title = $w->blog_title ? sprintf('<strong>%s</strong>', Html::escapeHTML($w->blog_title)) : '';
            $text  = Date::str($w->blog_text, (int) dcCore::app()->blog->upddt, $tz);
            $blog  = sprintf('<li>%s %s</li>', $title, $text);
        }

        # Post
        if ($w->post_show && $w->post_text) {
            $rs = dcCore::app()->blog->getPosts(['limit' => 1, 'no_content' => true]);
            if (!$rs->isEmpty()) {
                $title = $w->post_title ? sprintf('<strong>%s</strong>', Html::escapeHTML($w->post_title)) : '';
                $text  = Date::str($w->post_text, (int) strtotime(is_string($rs->f('post_upddt')) ? $rs->f('post_upddt') : ''), $tz);
                $link  = $rs->getURL();
                $over  = is_string($rs->f('post_title')) ? $rs->f('post_title') : '';

                $post = sprintf('<li>%s <a href="%s" title="%s">%s</a></li>', $title, $link, $over, $text);
            }
        }

        # Comment
        if ($w->comment_show && $w->comment_text) {
            $rs = dcCore::app()->blog->getComments(['limit' => 1, 'no_content' => true]);
            if (!$rs->isEmpty()) {
                $title = $w->comment_title ? sprintf('<strong>%s</strong>', Html::escapeHTML($w->comment_title)) : '';
                $text  = Date::str($w->comment_text, (int) strtotime(is_string($rs->f('comment_upddt')) ? $rs->f('comment_upddt') : ''), $tz);
                $link  = dcCore::app()->blog->url . dcCore::app()->getPostPublicURL(is_string($rs->f('post_type')) ? $rs->f('post_type') : '', Html::sanitizeURL(is_string($rs->f('post_url')) ? $rs->f('post_url') : '')) . '#c' . $rs->f('comment_id');
                $over  = is_string($rs->f('post_title')) ? $rs->f('post_title') : '';

                $comment = sprintf('<li>%s <a href="%s" title="%s">%s</a></li>', $title, $link, $over, $text);
            }
        }

        # Media
        if ($w->media_show && $w->media_text) {
            $path = dcCore::app()->blog->settings->get('system')->get('public_path');
            $sql  = new SelectStatement();
            $rs   = $sql->from(dcCore::app()->prefix . dcMedia::MEDIA_TABLE_NAME)
                ->column('media_upddt')
                ->where('media_path = ' . $sql->quote(is_string($path) ? $path : ''))
                ->order('media_upddt DESC')
                ->limit(1)
                ->select();

            if (!is_null($rs) && !$rs->isEmpty()) {
                $title = $w->media_title ? sprintf('<strong>%s</strong>', Html::escapeHTML($w->media_title)) : '';
                $text  = Date::str($w->media_text, (int) strtotime($rs->f('media_upddt')), $tz);

                $media = sprintf('<li>%s %s</li>', $title, $text);
            }
        }

        # --BEHAVIOR-- lastBlogUpdateWidgetParse
        $addons = dcCore::app()->callBehavior('lastBlogUpdateWidgetParse', $w);

        # Nothing to display
        if (!$blog && !$post && !$comment && !$media && !$addons) {
            return '';
        }

        # Display
        return $w->renderDiv(
            (bool) $w->content_only,
            'lastblogupdate ' . $w->class,
            '',
            ($w->title ? $w->renderTitle(Html::escapeHTML($w->title)) : '') .
                sprintf('<ul>%s</ul>', $blog . $post . $comment . $media . $addons)
        );
    }
}
