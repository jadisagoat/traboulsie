<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| How the website sends the contact form emails.
|
| Fill smtp_host, smtp_user and smtp_pass with the company mailbox created on the
| hosting (cPanel > Email Accounts), for example:
|   smtp_host = 'ssl://mail.traboulsiest.com'   smtp_port = 465
|   smtp_user = 'info@traboulsiest.com'          smtp_pass = 'the mailbox password'
| The emails then leave from that mailbox and stop landing in spam.
| Left empty, the website sends with the server's mail() as before.
*/
$config['smtp_host'] = '';
$config['smtp_user'] = '';
$config['smtp_pass'] = '';
$config['smtp_port'] = 465;
$config['smtp_timeout'] = 10;

$config['protocol'] = $config['smtp_host'] != '' ? 'smtp' : 'mail';
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['newline'] = "\r\n";
$config['crlf'] = "\r\n";

/* End of file email.php */
/* Location: ./application/config/email.php */
