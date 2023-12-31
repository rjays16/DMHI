<?php
/*
 * $Header: /cvsroot/care2002/Care2002/modules/nocc/conf.php,v 1.2 2005/10/29 20:08:10 kaloyan_raev Exp $
 *
 * Copyright 2001 Nicolas Chalanset <nicocha@free.fr>
 * Copyright 2001 Olivier Cahagne <cahagn_o@epita.fr>
 *
 * See the enclosed file COPYING for license information (GPL).  If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 */

// ################### This is the main configuration for NOCC ########## //

// ==> Required parameters

// Default smtp server and smtp_port (default is 25)
// If a domain has no smtp server, this one will be used
// If no smtp server is provided, Nocc will default to the mail() function,
// and try to use Sendmail or any other MTA (Postfix)
$default_smtp_server = '192.168.0.2';
$default_smtp_port = 25;

// List of domains people can log in
// You can have as many domaind as you need

// $domains[0]->domain = 'sourceforge.net';
//  domain name e.g 'sourceforge.net'. This field is used when sending message
//
// $domains[0]->in = 'mail.sourceforge.net/pop3:110';
//  imap or pop3 server name + protocol (only if not imap) + port
//  [server_name]/[protocol]:[port number]
//  ex for an imap server : mail.sourceforge.net:143
//  ex for a pop3 server  : mail.sourceforge.net/pop3:110
//  protocol can only be pop3
//
// $domains[0]->smtp = 'smtp.isp.com';
//  Optional: smtp server name or IP address
//  Leave empty to send mail via sendmail
//
// $domains[0]->smtp_port = 25;
//  Port number to connect to smtp server (usually 25)
$domains[0]->domain = 'maryhospital.intranet';
$domains[0]->in = 'pop3.maryhospital.intranet/pop3:110';
$domains[0]->smtp = 'smtp.maryhospital.intranet';
$domains[0]->smtp_port = 25;


// If you want to add more domainis, uncomment the following
// lines and fill them in

$domains[1]->domain = 'bong.intranet';
$domains[1]->in = 'pop3.bong.intranet/pop3:110';
$domains[1]->smtp = 'smtp.bong.intranet';
$domains[1]->smtp_port = 25;

//$domains[2]->domain = '';
//$domains[2]->in = '';
//$domains[2]->smtp = '';
//$domains[2]->smtp_port = 25;

// Default tmp directory (where to store temporary uploaded files)
// Can be empty if 'upload_tmp_dir' is set in php.ini
// This should be something like '/tmp' on Unix System
// And 'c:\\temp' on Win32 (note that we must escape "\")
$tmpdir = 'c:\\maryhospital\\nocctemp';

// Default folder to go first
$default_folder = 'INBOX';

// ===> End of required parameters
// The following parameters can be changed but it's not necessary to
// get a working version of nocc

// if browser has no preferred language, we use the default language
$default_lang = 'en';

// How many messages to display in the inbox (devel only)
$max_msg_num = 1;

// let user see the header of a message
$use_verbose = true;

// the user can logout or not (if nocc is used within your website
// enter 'false' here else leave 'true')
$enable_logout = true;

// Whether or not to display attachment part number
$display_part_no = true;

// Whether or not to display the Message/RFC822 into the attachments
// (the attachments of that part are still available even if false is set
$display_rfc822 = true;

// If you don't want to display images (GIF, JPEG and PNG) sent as attachements
// set it to 'false'
$display_img_attach = true;

// If you don't want to display text/plain attachments set it to 'false'
$display_text_attach = true;

// By default the messages are sorted by date 
$default_sort = '1';

// By default the most recent is in top ('1' --> sorting top to bottom,
// '0' --> bottom to top)
$default_sortdir = '1';

// For old UCB POP server, change this setting to 1 to enable
// new mail detection. Recommended: leave it to 0 for any other POP or
// IMAP server.
// See FAQ for more details.
$have_ucb_pop_server = '0';

// If you wanna make your own theme and force people to use that one, 
// set $use_theme to false and fill in the $default_theme to the theme name
// you want to use
// Theme handling: allows users to choose a theme on the login page
$use_theme = true;

// Default theme
$default_theme = 'standard';

// Error reporting
// Display error but no notice
$debug_level = E_ALL & ~E_NOTICE;


// ################### Messages Signature  ################### //

// This message is added to every message, the user cannot delete it
// Be careful if you modify this, do not forget to write '\r\n' to switch
// to the next line !
//$ad = "___________________________________\r\nNOCC, http://maryhospital";
$ad = "";


/*
###################     End of Configuration     ####################
*********************************************************************
################### Do not modify below this line ###################
*/

$nocc_version = '0.9.4';
$nocc_name = 'NOCC';

session_register('domainnum', 'user', 'passwd', 'server', 'servtype', 'port', 'theme');

if (isset($domainnum))
{
    $domain	= $domains[$domainnum]->domain;
    $servr = $domains[$domainnum]->in;
    $smtp_server = $domains[$domainnum]->smtp;
	$smtp_port = $domains[$domainnum]->smtp_port;
}

if (empty($smtp_server))
	$smtp_server = $default_smtp_server;
if (empty($smtp_port))
	$smtp_port = $default_smtp_port;
if (empty($servr) && !empty($server) && !empty($servtype) && !empty($port))
{
		$servtype = strtolower($servtype);
		if ($servtype != 'imap')
			$servr = $server.'/'.$servtype.':'.$port;
		else
			$servr = $server.':'.$port;
		$domain = $server;
}

if (!isset($folder))
	$folder = $default_folder;
if (!isset($sort))
	$sort = $default_sort;
if (!isset($sortdir))
	$sortdir = $default_sortdir;
if ($use_theme == true)
{
	if (!isset($theme))
		$theme = $default_theme;
}
else
	$theme = $default_theme;
$php_session = ini_get('session.name');
$upload_tmp_dir = ini_get('upload_tmp_dir');
$tmpdir = (!empty($upload_tmp_dir)) ? $upload_tmp_dir : $tmpdir;
error_reporting($debug_level);
require('conf_lang.php');
require('themes/'.$theme.'/colors.php');
?>
