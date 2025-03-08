<?php

defined('BASEPATH') OR exit('No direct script access allowed');





// $config['email_config'] = array(

//          'mail_protocol' => 'smtp',

//          'mail_host' => 'ssl://mail.hypos.id',

//          'mail_port' => '465',

//          'mail_user' => 'noreply@hypos.id',

//          'mail_pass' => 'p0h0d3u1??',

//          'mailtype' => 'html',

//          'mail_charset' => 'iso-8859-1',

//          'newline' => "\r\n",

//          'mail_timeout' => '4',

//          'wordwrap'=>TRUE

//      );

$config['email_config'] = array(

        'protocol' => 'smtp',

        'smtp_host' => 'ssl://mail.hypos.id',

        'smtp_port' => '465',

        'smtp_user' => 'noreply@hypos.id',

        'smtp_pass' => 'p0h0d3u1??',

        'mailtype' => 'html',

        'mail_charset' => 'iso-8859-1',

        'newline' => "\r\n",

        'mail_timeout' => '4',

        'wordwrap'=>TRUE

);

$config['email_sender'] = 'noreply@hypos.id';

$config['email_config_reminder'] = array(

        'protocol' => 'smtp',

        'smtp_host' => 'ssl://mail.hypos.id',

        'smtp_port' => '465',

        'smtp_user' => 'reminder@hypos.id',

        'smtp_pass' => 'Sh1r012222903!@#',

        'mailtype' => 'html',

        'mail_charset' => 'iso-8859-1',

        'newline' => "\r\n",

        'mail_timeout' => '4',

        'wordwrap'=>TRUE

);

$config['email_sender_reminder'] = 'reminder@hypos.id';