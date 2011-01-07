<?php 

/**
 * Security measure for Wolf 0.7.0+
 */
if (CMS_VERSION == '') {
	Flash::set('error', __('Fatal Error: CMS_VERSION not defined.'));
}
else {
	$ver_check = explode('.',CMS_VERSION);
	if (($ver_check[0] >= 1) || ($ver_check[0] < 1 && $ver_check[1] > 6))
	{
		if (!defined('IN_CMS')) 
		{
			Flash::set('error', __('Fatal Error:  Not In CMS'));
			exit();
		}
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

/**
 * class Tagger
 *
 * @author Andrew Smith <a.smith@silentworks.co.uk>
 * @author Tyler Beckett <tyler@tbeckett.net>
 */

class Tagger extends Tag
{   
    public $name;
    public $count;
    
    public static function find($args = null) {
        
        // Collect attributes...
        $where    = isset($args['where']) ? trim($args['where']) : '';
        $order_by = isset($args['order']) ? trim($args['order']) : '';
        $offset   = isset($args['offset']) ? (int) $args['offset'] : 0;
        $limit    = isset($args['limit']) ? (int) $args['limit'] : 0;

        // Prepare query parts
        $where_string = empty($where) ? '' : "WHERE $where";
        $order_by_string = empty($order_by) ? '' : "ORDER BY $order_by";
        $limit_string = $limit > 0 ? "LIMIT $offset, $limit" : '';

        $tablename = self::tableNameFromClassName('Tag');

        // Prepare SQL
        $sql = "SELECT * FROM $tablename"." $where_string $order_by_string $limit_string";

        $stmt = Record::getConnection()->prepare($sql);
        $stmt->execute();

        // Run!
        if ($limit == 1) {
            return $stmt->fetchObject('Tag');
        } else {
            $objects = array();
            while ($object = $stmt->fetchObject('Tag')) {
                $objects[] = $object;
            }
            return $objects;
        }
    
    } // find
    
    public static function findAll($args = null) {
        return self::find($args);
    }
    
    public static function findById($id) {
        return self::find(array(
            'where' => self::tableNameFromClassName('Tag').'.id='.(int)$id,
            'limit' => 1
        ));
    }

	/*
	 * Purges old Tags from the database and reconstructs the number of tags
	 *
	 * @since 1.2.4
	 */
	public function purge_old ()
	{
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'page_tag ORDER BY tag_id ASC';
		$pdo = Record::getConnection();
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		// Retrieve all Tag IDs into an array with tag_id as value
		while($tag = $stmt->fetchObject()) $tags[$tag->tag_id] = $tag->tag_id;

		// Retrieve actual count of Tags and add count to array
		foreach($tags as $id)
		{
			$sql = 'SELECT * FROM '.TABLE_PREFIX.'page_tag WHERE tag_id = "'.$id.'"';
			$pdo = Record::getConnection();
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			$count[$id] = $stmt->rowCount();
		}

		// Retrieve current tag counts and store to array with a zero value
		$sql = 'SELECT * FROM '.TABLE_PREFIX.'tag ORDER BY id ASC';
		$pdo = Record::getConnection();
		$stmt = $pdo->prepare($sql);
		$stmt->execute();

		while($old = $stmt->fetchObject()) $current[$old->id] = $old->id;

		foreach($current as $id)
		{
			$current2[$id] = 0;
		}

		$new = array_replace($current2, $count);

		// Update actual count of Tag table with actual count from page_tag table.
		foreach($current as $id)
		{
			if ($new[$id] == 0)
			{
				$sql = 'DELETE FROM '.TABLE_PREFIX.'tag WHERE id = "'.$id.'"';
			}
			else
			{
				$sql = 'UPDATE '.TABLE_PREFIX.'tag SET count = "'.$new[$id].'" WHERE id = "'.$id.'"';
			}

			$pdo = Record::getConnection();
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
		}

		Flash::set('success', __('Purge & Recount Complete!'));
	}
    
    /**
	 * Gives the name of the tag fields
	 *
	 * @since 1.1.0
	 * @param int $value
	 */
    public static function sortField($value = NULL) {
    	$fields = array('id', 'name', 'count');
    	return isset($value) ? $fields[$value] : $fields;
    }
}