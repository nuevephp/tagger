<?php 

/**
 * Tagger Plugin for Frog CMS <http://thehub.silentworks.co.uk/plugins/frog-cms/tagger.html>
 * Alternate Mirror site <http://www.tbeckett.net/articles/plugins/tagger.xhtml>
 * Copyright (C) 2008 - 2010 Andrew Smith <a.smith@silentworks.co.uk>
 * Copyright (C) 2008 Tyler Beckett <tyler@tbeckett.net>
 * 
 * Dual licensed under the MIT (mit-license.txt)
 * and GPL (gpl-license.txt) licenses.
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

        $stmt = self::$__CONN__->prepare($sql);
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
    
    /**
	 * Gives the name of the tag fields
	 *
	 * @since 1.1.0
	 * @param int $value
	 */
    public function sortField($value) {
    	$fields = array('id', 'name', 'count');
    	return isset($value) ? $fields[$value] : $fields;
    }
}