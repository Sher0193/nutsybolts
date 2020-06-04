<?php

define('ROOT_DIR', '/home/mark/a2a_dev/');
define('SHOW_ADS', false);
define('SHOW_ANALYTICS', false);
define('JS_PACKED', true);
define('DB_USER', 'mark');
define('DB_PW', 'd34d&tr##');
define('DB_SCHEMA', 'mark_a2adev');

define('ENCRYPTION_KEY', 'This is an encryption key.');

define('LIB_DIR', ROOT_DIR . 'lib/');
define('MODEL_DIR', LIB_DIR . 'models/');
define('TEMPLATE_DIR', ROOT_DIR . 'templates/');
define('COMPILE_DIR', ROOT_DIR . 'templates_c/');
define('OP_DIR', LIB_DIR . 'operations/');
define('SMARTY_DIR', LIB_DIR . 'Smarty-2.6.18/libs/');
define('SEMAPHORE_DIR', ROOT_DIR . 'semaphore/');
define('LOG_DIR', ROOT_DIR . 'logs/');

// game constants
define('PLAYER_CARDS', 7);
define('MAX_ROOMS', 100);
define('SPAMBLOCK_INTERVAL', 0);
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