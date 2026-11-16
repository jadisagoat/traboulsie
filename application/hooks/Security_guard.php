<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| Site-wide security checks, wired in config/hooks.php:
|  - the control panel only opens for a logged-in user
|  - every form sent to the site carries the token of the visitor's session (CSRF)
|  - delete links of the control panel only work from the control panel itself
|  - the contact form ignores robots (hidden field + minimum time to fill it)
*/
class Security_guard {

	// control panel pages that open without logging in
	var $open_admin_pages = array('home/index', 'home/login_validate', 'home/logout', 'lang/set');

	// control panel actions that change data through a simple link
	var $link_actions = '/(delete|remove|drop|sorted|complete|restore|empty|status|publish)/i';

	// seconds a person needs at least to fill the contact form
	var $min_form_seconds = 3;

	// pre_controller: the sessions are kept in the ci_sessions table, create it on first run
	function sessions_table()
	{
		// the flag only remembers that the table was once complete: it is still checked on every
		// request (one light query) because a restored database may not contain the table any more
		$flag = APPPATH.'cache/sessions_table.v2.ok';
		require_once(BASEPATH.'database/DB.php');
		$db =& DB();
		if (is_file($flag))
		{
			if ($db->table_exists('ci_sessions')) return;
			@unlink($flag);
		}
		$db->query("CREATE TABLE IF NOT EXISTS `ci_sessions` (
			`session_id` varchar(40) NOT NULL DEFAULT '0',
			`ip_address` varchar(45) NOT NULL DEFAULT '0',
			`user_agent` varchar(120) NOT NULL,
			`last_activity` int(10) unsigned NOT NULL DEFAULT 0,
			`user_data` text NOT NULL,
			PRIMARY KEY (`session_id`),
			KEY `last_activity` (`last_activity`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8");

		// the table is only trusted once it exists with every column the session library reads:
		// on a hosting where the database user may not CREATE tables, or where an old ci_sessions
		// table has other columns, the SELECT of the session fails and the whole site returned a 500
		$ok = $db->table_exists('ci_sessions');
		foreach (array('session_id', 'ip_address', 'user_agent', 'last_activity', 'user_data') as $field)
		{
			if ($ok && ! $db->field_exists($field, 'ci_sessions')) $ok = FALSE;
		}
		if ($ok)
		{
			@file_put_contents($flag, date('c'));
			return;
		}

		// no usable table: keep the sessions in the (signed) cookie instead of crashing,
		// and check again on the next request. To fix for good run docs/ci_sessions.sql in phpMyAdmin.
		$config =& load_class('Config', 'core');
		$config->set_item('sess_use_database', FALSE);
		log_message('error', 'ci_sessions table missing or incomplete, sessions kept in the cookie. Run docs/ci_sessions.sql');
	}

	// post_controller_constructor: runs before every page and every action
	function check()
	{
		$CI =& get_instance();
		$is_admin = trim($CI->router->fetch_directory(), '/') == 'back_office';
		$page = $CI->router->fetch_class().'/'.$CI->router->fetch_method();
		$is_post = strtoupper($_SERVER['REQUEST_METHOD']) == 'POST';

		$token = $this->token();

		// control panel: logged in only
		if ($is_admin && ! in_array($page, $this->open_admin_pages) && ! $CI->session->userdata('user_id'))
		{
			if ($this->_is_ajax())
			{
				set_status_header(401);
				exit;
			}
			redirect(base_url().'back_office/home');
		}

		// forms: the token must match the session
		if ($is_post)
		{
			$sent = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : (isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? (string) $_SERVER['HTTP_X_CSRF_TOKEN'] : '');
			unset($_POST['csrf_token']);
			if ( ! function_exists('hash_equals') ? $sent !== $token : ! hash_equals($token, $sent))
			{
				$this->_refuse('This page has expired. Please go back, refresh the page and try again.');
			}
		}

		// control panel links that change data: only when clicked inside the control panel
		if ($is_admin && ! $is_post && preg_match($this->link_actions, $CI->router->fetch_method()) && ! $this->_same_site())
		{
			$this->_refuse('This action can only be done from the control panel.');
		}

		// contact form: robots fill the hidden field or send the form too fast
		if ( ! $is_admin)
		{
			if ($is_post && isset($_POST['message']))
			{
				$shown = (int) $CI->session->userdata('form_shown_at');
				$robot = ( ! empty($_POST['website'])) || ($shown && time() - $shown < $this->min_form_seconds);
				unset($_POST['website']);
				if ($robot)
				{
					redirect(base_url().trim($CI->uri->uri_string(), '/').'#contact');
				}
			}
			else
			{
				$CI->session->set_userdata('form_shown_at', time());
			}
		}
	}

	// display_override: adds the token to every form, and the hidden robot field to the contact form
	function add_tokens()
	{
		$CI =& get_instance();
		$out = $CI->output->get_output();

		// the 404 page is built outside the normal flow and may not have the session loaded yet
		if ( ! isset($CI->session) && isset($CI->load))
		{
			$CI->load->library('session');
		}
		if ( ! isset($CI->session))
		{
			$CI->output->_display($out);
			return;
		}

		if (stripos($out, '<form') !== FALSE || stripos($out, '</body>') !== FALSE)
		{
			$token = htmlspecialchars($this->token(), ENT_QUOTES);
			$out = preg_replace_callback('#<form\b[^>]*>#i', function ($m) use ($token) {
				if ( ! preg_match('#method\s*=\s*["\']?post#i', $m[0])) return $m[0];
				$add = '<input type="hidden" name="csrf_token" value="'.$token.'">';
				if (stripos($m[0], 'main-contact-form') !== FALSE)
				{
					$add .= '<div style="position:absolute;width:1px;height:1px;margin:-1px;padding:0;border:0;overflow:hidden;clip:rect(0 0 0 0);clip-path:inset(50%);white-space:nowrap" aria-hidden="true"><label>Website<input type="text" name="website" value="" tabindex="-1" autocomplete="off"></label></div>';
				}
				return $m[0].$add;
			}, $out);

			// requests sent by jQuery carry the token in a header
			$script = '<script>(function(t){function s(){if(window.jQuery){jQuery.ajaxSetup({headers:{"X-CSRF-Token":t}});}}s();document.addEventListener("DOMContentLoaded",s);})("'.$token.'");</script>';
			$out = preg_replace('#</body>#i', $script.'</body>', $out, 1);
		}

		// control panel in Arabic: swap the English texts (helpers/bo_lang_helper.php)
		if (isset($CI->router) && trim($CI->router->fetch_directory(), '/') == 'back_office' && function_exists('bo_is_ar') && bo_is_ar() && stripos($out, '<html') !== FALSE)
		{
			$out = bo_translate_html($out);
		}

		$CI->output->_display($out);
	}

	// the token of this visitor, created once per session
	function token()
	{
		$CI =& get_instance();
		$token = $CI->session->userdata('csrf_token');
		if ( ! $token)
		{
			$token = function_exists('random_bytes') ? bin2hex(random_bytes(32)) : sha1(uniqid(mt_rand(), TRUE));
			$CI->session->set_userdata('csrf_token', $token);
		}
		return $token;
	}

	function _same_site()
	{
		$from = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
		$host = parse_url($from, PHP_URL_HOST);
		$port = parse_url($from, PHP_URL_PORT);
		if ($port) $host .= ':'.$port;
		return $host && isset($_SERVER['HTTP_HOST']) && strtolower($host) == strtolower($_SERVER['HTTP_HOST']);
	}

	function _is_ajax()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

	function _refuse($message)
	{
		if ($this->_is_ajax())
		{
			set_status_header(403);
			echo $message;
			exit;
		}
		show_error($message, 403);
	}
}
