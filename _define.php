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
    'lastBlogUpdate',
    'Show the dates of last updates of your blog in a widget',
    'Jean-Christian Denis, Pierre Van Glabeke',
    '2021.08.27.1',
    [
        'permissions' => 'usage,contentadmin',
        'type' => 'plugin',
        'dc_min' => '2.19',
        'support' => 'http://forum.dotclear.org/viewtopic.php?pid=332950#p332950',
        'details' => 'http://plugins.dotaddict.org/dc2/details/lastBlogUpdate',
        'repository' => 'https://raw.githubusercontent.com/JcDenis/lastBlogUpdate/master/dcstore.xml'
    ]
);