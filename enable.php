<?php
/**
 * Tagger Plugin for Wolf CMS <http://thehub.silentworks.co.uk/plugins/frog-cms/tagger.html>
 * Alternate Mirror site <http://www.tbeckett.net/articles/plugins/tagger.xhtml>
 * Copyright (C) 2008 - 2011 Andrew Smith <a.smith@silentworks.co.uk>
 * Copyright (C) 2008 - 2011 Tyler Beckett <tyler@tbeckett.net>
 * 
 * Dual licensed under the MIT (license/mit-license.txt)
 * and GPL (license/gpl-license.txt) licenses.
 */

/**
 * Tagger Utils
 */
$tagger_dir = dirname(__FILE__) . '/';
include_once $tagger_dir . "utils.php";

$PDO = Record::getConnection();
$driver = strtolower($PDO->getAttribute(Record::ATTR_DRIVER_NAME));

if ($driver == 'mysql') {
	
	$query = $PDO->query("SELECT * FROM ".TABLE_PREFIX."page WHERE behavior_id = 'tagger'");
	if(!$query->rowCount()){
		// Create Pages
		executioner(
			file($tagger_dir . 'sql/install.sql'),
			array(
				'{prefix}' => TABLE_PREFIX
			)
		);
	}
}

// Check if the plugin's settings already exist and create them if not.
if (Plugin::getSetting('tag_type', 'tagger') === false) {
	// Store settings new style
	$settings = array('tag_type' => 'count',
	                  'case' => '0',
	                  'rowspage' => '15',
	                  'sort_field' => '0',
	                  'sort_order' => 'ASC',
					  'font_min' => '12',
					  'font_max' => '32'
	                 );

	Plugin::setAllSettings($settings, 'tagger');
}

Flash::set('success', __('Tagger: Plugin was successfully enabled!'));