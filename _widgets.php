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

dcCore::app()->addBehavior('initWidgets', 'lastBlogUpdateWidgetAdmin');

function lastBlogUpdateWidgetAdmin($w)
{
    $w
            ->create(
                'lastblogupdate',
                __('LastBlogUpdate: dates of lastest updates'),
                'lastBlogUpdateWidgetPublic',
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
