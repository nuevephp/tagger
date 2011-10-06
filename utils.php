<?php
/**
 * Tagger Utilities
 */

function executioner($file_path, $params = array())
{
	$PDO = Record::getConnection();

	// Temporary variable, used to store current query
	$sql = '';

	// Read in entire file
	$lines = $file_path;
	
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
			if (!empty($params))
			{
				$sql = str_replace(array_keys($params), $params, $sql);
			}
			
			$PDO->exec($sql) or die('Error performing query \'<strong>' . $sql . '\': ' . mysql_error() . '<br /><br />');
			// Reset temp variable to empty
			$sql = '';
		}
	}
}