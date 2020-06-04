<?php

define('ROOT_DIR', '/home/mark/a2a_prod/');
define('SHOW_ADS', false);
define('SHOW_ANALYTICS', true);
define('JS_PACKED', true);
define('DB_USER', 'mark');
define('DB_PW', 'd34d&tr##');
define('DB_SCHEMA', 'mark_a2a');

define('LIB_DIR', ROOT_DIR . 'lib/');
define('MODEL_DIR', LIB_DIR . 'models/');
define('TEMPLATE_DIR', ROOT_DIR . 'templates/');
define('COMPILE_DIR', ROOT_DIR . 'templates_c/');
define('OP_DIR', LIB_DIR . 'operations/');
define('SMARTY_DIR', LIB_DIR . 'Smarty-2.6.18/libs/');
define('SEMAPHORE_DIR', ROOT_DIR . 'semaphore/');

// game constants
define('PLAYER_CARDS', 7);
define('MAX_ROOMS', 100);
define('SPAMBLOCK_INTERVAL', 90);
define('ENCRYPTION_KEY', 'i miss ann arbor');
define('HP_MAX_ROOM_AGE', 600);

// log codes
define('LOG_GAME_START', 'start');
define('LOG_NEW_CREATOR', 'crtr');
define('LOG_NEW_MESSAGE', 'msg');
define('LOG_NEW_PLAYER', 'newpl');
define('LOG_REMOVED_PLAYER', 'rmpl');
define('LOG_PLAYER_IDLE', 'idle');
define('LOG_PLAYER_UNIDLE', 'unidle');
define('LOG_PLAYER_SKIP', 'skip');
define('LOG_PLAYER_UNSKIP', 'unskip');
define('LOG_CARD_PLAYED', 'played');
define('LOG_BEGIN_VOTE', 'p2');
define('LOG_CARD_VOTED', 'voted');
define('LOG_NEW_JUDGE', 'judge');

?>