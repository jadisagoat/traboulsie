<?php
// Language switch of the control panel: back_office/lang/set/ar or back_office/lang/set/en.
// Saved in a cookie for a year, then back to the page the person was on.
class Lang extends CI_Controller{

public function set($code = 'en')
{
$code = ($code === 'ar') ? 'ar' : 'en';
$path = parse_url(base_url(), PHP_URL_PATH);
$secure = ! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
setcookie('bo_lang', $code, time() + 365 * 24 * 3600, $path ? $path : '/', '', $secure, TRUE);

// back to the same page, only inside this website
$back = (string)$this->input->server('HTTP_REFERER');
$host = parse_url($back, PHP_URL_HOST);
$port = parse_url($back, PHP_URL_PORT);
if ($port) $host .= ':'.$port;
if ($back !== '' && $host && strtolower($host) === strtolower($_SERVER['HTTP_HOST']) && strpos($back, base_url().'back_office') === 0)
	redirect($back);
redirect(base_url().'back_office/home'.($this->session->userdata('user_id') ? '/dashboard' : ''));
}

}
