<?php

define('ROOT_DIR', '/home/mgoetz/nb_private_dev/');
define('SHOW_ADS', false);
define('SHOW_ANALYTICS', false);
define('JS_PACKED', false);
define('DB_HOST', 'data.nutsybolts.com');
define('DB_USER', 'echoes_scout');
define('DB_PW', 'd34d&tr##');
define('DB_SCHEMA', 'nb_dev');

define('ENCRYPTION_KEY', 'This is an encryption key.');

define('LIB_DIR', ROOT_DIR . 'lib/');
define('MODEL_DIR', LIB_DIR . 'models/');
define('TEMPLATE_DIR', ROOT_DIR . 'templates/');
define('COMPILE_DIR', ROOT_DIR . 'templates_c/');
define('OP_DIR', LIB_DIR . 'operations/');
define('SMARTY_DIR', LIB_DIR . 'Smarty-2.6.18/libs/');
define('SEMAPHORE_DIR', ROOT_DIR . 'semaphore/');
define('LOG_DIR', ROOT_DIR . 'logs/');

define('STATUS_DOWN', 0);

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

ini_set('error_reporting', E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', '/home/mgoetz/nb_private_utils/dev_error.log');


define('PUSHER_APP_ID', '5443');
define('PUSHER_APP_KEY', 'cf78494784a12891f2d1');
define('PUSHER_APP_SECRET', 'bb0aef347a576bc0623a');
define('COOKIE_TIMEOUT', 60*60*4);
define('DEBUG_PIDPARAM', 1);

?>