<?php

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once dirname(__FILE__).'/inc/store.php';

$trash = new \GridElementTrash\Store();
$result = $trash->clear();