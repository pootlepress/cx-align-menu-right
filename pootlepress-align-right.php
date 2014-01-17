<?php
/*
Plugin Name: Pootlepress Align Right
Plugin URI: http://pootlepress.co.uk/
Description: If you want to align your menu to the right, then install me!
Version: 1.0.0
Author: Pootlepress
Author URI: http://pootlepress.co.uk/
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
/*  Copyright 2014  Pootlepress  (email : jamie@pootlepress.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
	
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	require_once( 'pootlepress-align-right-functions.php' );
	require_once( 'classes/class-pootlepress-align-right.php' );

    $GLOBALS['pootlepress_align_right'] = new Pootlepress_Align_Right( __FILE__ );
    $GLOBALS['pootlepress_align_right']->version = '1.0.0';
	
?>