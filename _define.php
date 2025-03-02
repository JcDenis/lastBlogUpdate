<?php
/**
 * @file
 * @brief       The plugin lastBlogUpdate definition
 * @ingroup     lastBlogUpdate
 *
 * @defgroup    lastBlogUpdate Plugin lastBlogUpdate.
 *
 * Show the dates of last updates of your blog in a widget.
 *
 * @author      Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
$this->registerModule(
    'Last blog update',
    'Show the dates of last updates of your blog in a widget',
    'Jean-Christian Denis, Pierre Van Glabeke',
    '2025.03.02',
    [
        'requires'    => [['core', '2.28']],
        'permissions' => 'My',
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $this->id . '/master/dcstore.xml',
        'date'        => '2025-03-02T17:02:37+00:00',
    ]
);
