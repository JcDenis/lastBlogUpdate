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
 * @copyright   Jean-Christian Denis
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
$this->registerModule(
    'Last blog update',
    'Show the dates of last updates of your blog in a widget',
    'Jean-Christian Denis, Pierre Van Glabeke',
    '2023.10.11',
    [
        'requires'    => [['core', '2.28']],
        'permissions' => 'My',
        'type'        => 'plugin',
        'support'     => 'https://git.dotclear.watch/JcDenis/' . basename(__DIR__) . '/issues',
        'details'     => 'https://git.dotclear.watch/JcDenis/' . basename(__DIR__) . '/src/branch/master/README.md',
        'repository'  => 'https://git.dotclear.watch/JcDenis/' . basename(__DIR__) . '/raw/branch/master/dcstore.xml',
    ]
);
