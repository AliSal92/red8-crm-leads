<?php
/**
 * Project Name: Red8 CRM Leads
 * Project URI: https://github.com/AliSal92/red8-crm-leads
 * Description: PHP script to get the leads from the google sheet and send them to the CRM using CRM leads API
 * Version: 1.0
 * Author: AliSal
 * Author URI: https://github.com/AliSal92/
 * Red8 CRM Leads is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Red8 CRM Leads is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with MAM Properties. If not, see <http://www.gnu.org/licenses/>.
 */

use MAM\Init;
use Dotenv\Dotenv;

/**
 * Require once the Composer Autoload
 */
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
    require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

/**
 * Initialize .env
 */
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

/**
 * Initialize and run all the core classes of the plugin
 */
if ( class_exists( 'MAM\Init' ) ) {
    Init::register_services();
}