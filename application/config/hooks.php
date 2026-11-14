<?php     if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/



// security checks of the whole site: see hooks/Security_guard.php
$hook['pre_controller'][] = array('class' => 'Security_guard', 'function' => 'sessions_table', 'filename' => 'Security_guard.php', 'filepath' => 'hooks');
$hook['post_controller_constructor'][] = array('class' => 'Security_guard', 'function' => 'check', 'filename' => 'Security_guard.php', 'filepath' => 'hooks');
$hook['display_override'][] = array('class' => 'Security_guard', 'function' => 'add_tokens', 'filename' => 'Security_guard.php', 'filepath' => 'hooks');

/* End of file hooks.php */
/* Location: ./application/config/hooks.php */