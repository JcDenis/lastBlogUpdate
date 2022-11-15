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

$this->registerModule(
    'Last blog update',
    'Show the dates of last updates of your blog in a widget',
    'Jean-Christian Denis, Pierre Van Glabeke',
    '2022.11.12',
    [
        'requires'    => [['core', '2.24']],
        'permissions' => dcCore::app()->auth->makePermissions([
            dcAuth::PERMISSION_USAGE,
            dcAuth::PERMISSION_CONTENT_ADMIN,
        ]),
        'type'       => 'plugin',
        'support'    => 'http://forum.dotclear.org/viewtopic.php?pid=332950#p332950',
        'details'    => 'http://plugins.dotaddict.org/dc2/details/lastBlogUpdate',
        'repository' => 'https://raw.githubusercontent.com/JcDenis/lastBlogUpdate/master/dcstore.xml',
    ]
);
