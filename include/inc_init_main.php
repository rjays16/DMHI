<?php
# This is the database name
$dbname = 'dmhitestfeb5';

# Database user name, default is root or httpd for mysql, or postgres for postgresql
$dbusername = 'root';

# Database user password, default is empty char
$dbpassword = '';

# Database host name, default = localhost
$dbhost = 'localhost';

# Database session table, default = care_sessions
//$dbsessiontb='care_sessions';

# First key used for simple chaining protection of scripts
$key = '3.53020914643E+013';

# Second key used for accessing modules
$key_2level = '826165905490';

# 3rd key for encrypting cookie information
$key_login = '1.13664924241E+013';

# Main host address or domain
$main_domain = '127.0.0.1';

# Host address for images
$fotoserver_ip = '127.0.0.1';

# Transfer protocol. Use https if this runs on SSL server
$httprotocol = 'http';

# Set this to your database type. For details refer to ADODB manual or goto http://php.weblogs.com/ADODB/
$dbtype = 'mysql';
$dbtypeuse = 'mysqlt';

# Set this to the FTP's user id.
$ftp_userid = 'segworks';

# Set this to the FTP users' password.
$ftp_passwrd = 's3gw0rx';

#added by VAN 03-13-2012
#transfer method to be used in connecting LIS
#either NFS or SOCKET
#$transfer_method = 'SOCKET';

$debug_env = 1;
define('DEBUG', 1);

# new config variable
$config = array(
    'debug' => 1,
);
