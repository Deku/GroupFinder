<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * Email config (global)
 */

$config['useragent']            = 'CodeIgniter';
$config['protocol']             = 'smtp';
$config['mailpath']             = 'D:\xampp\sendmail';
$config['smtp_host']            = 'smtp.googlemail.com';
$config['smtp_user']            = 'contact.groupfinder@gmail.com';
$config['smtp_pass']            = '';
$config['smtp_port']            = 465;
$config['smtp_timeout']         = 5; // (in seconds)
$config['smtp_crypto']          = 'ssl'; // '' or 'tls' or 'ssl'
$config['wordwrap']             = true;
$config['wrapchars']            = 76;
$config['mailtype']             = 'html'; // 'text' or 'html'
$config['charset']              = 'utf-8';
$config['validate']             = true;
$config['priority']             = 3; // 1, 2, 3, 4, 5
$config['crlf']                 = "\r\n"; // "\r\n" or "\n" or "\r"
$config['newline']              = "\r\n"; // "\r\n" or "\n" or "\r"
