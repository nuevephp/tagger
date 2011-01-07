<?php

/**
 * Security measure for Wolf 0.7.0+
 */
if (!defined('CMS_VERSION'))
{
	Flash::set('error', __('Fatal Error: CMS_VERSION not defined.'));
}
else 
{
	$ver_check = explode('.',CMS_VERSION);
	if (($ver_check[0] >= 1) || ($ver_check[0] < 1 && $ver_check[1] > 6))
	{
		if (!defined('IN_CMS')) 
		{
			Flash::set('error', __('Fatal Error:  Not In CMS'));
			exit();
		}
	}
	else if ($ver_check[0] < 1 && $ver_check[1] < 7)
	{
		Flash::set('error', __('Tagger ' . TAGGER_VERSION . ' is not supported by this version of Wolf CMS.  0.7.0 and higher required.'));
	}
}

/**
 * Tagger Plugin for Wolf CMS <http://thehub.silentworks.co.uk/plugins/frog-cms/tagger.html>
 * Alternate Mirror site <http://www.tbeckett.net/articles/plugins/tagger.xhtml>
 * Copyright (C) 2008 - 2010 Andrew Smith <a.smith@silentworks.co.uk>
 * Copyright (C) 2008 - 2010 Tyler Beckett <tyler@tbeckett.net>
 * 
 * Dual licensed under the MIT (license/mit-license.txt)
 * and GPL (license/gpl-license.txt) licenses.
 */

$PDO = Record::getConnection();
$driver = strtolower($PDO->getAttribute(Record::ATTR_DRIVER_NAME));

if ($driver == 'mysql') {
	
	$query = $PDO->query("SELECT * FROM ".TABLE_PREFIX."page WHERE behavior_id = 'tagger'");

	if(!$query->rowCount()){
		// Create Pages
		// Temporary variable, used to store current query
		$sql = '';
		// Read in entire file
		$lines = file(dirname(__file__) . '/sql/install.sql');
		// Loop through each line
		foreach ($lines as $line)
		{
			// Skip it if it's a comment
			if (substr($line, 0, 2) == '--' || $line == '')
				continue;

			// Add this line to the current segment
			$sql .= $line;
			// If it has a semicolon at the end, it's the end of the query
			if (substr(trim($line), -1, 1) == ';')
			{
				// Perform the query
				$PDO->exec(str_replace('{prefix}', TABLE_PREFIX, $sql)) or die('Error performing query \'<strong>' . $sql . '\': ' . mysql_error() . '<br /><br />');
				// Reset temp variable to empty
				$sql = '';
			}
		}
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