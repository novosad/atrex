<?php
/**
 * @package Configuration Settings circa 1.5.4
 * @copyright Copyright 2003-2014 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * File Built by zc_install on 2016-02-10 05:51:00
 */


/*************** NOTE: This file is similar, but DIFFERENT from the "admin" version of configure.php. ***********/
/***************       The 2 files should be kept separate and not used to overwrite each other.      ***********/

// Define the webserver and path parameters
// HTTP_SERVER is your Main webserver: eg-http://www.your_domain.com
// HTTPS_SERVER is your Secure webserver: eg-https://www.your_domain.com
define('HTTP_SERVER', 'http://opencart.web');
define('HTTPS_SERVER', 'https://opencart.web');

// define our database connection
define('DB_TYPE', 'mysql');
define('DB_PREFIX', 'oc_');
define('DB_CHARSET', 'utf8');
define('DB_SERVER', 'localhost');
define('DB_SERVER_USERNAME', 'root');
define('DB_SERVER_PASSWORD', '123');
define('DB_DATABASE', 'opencart');