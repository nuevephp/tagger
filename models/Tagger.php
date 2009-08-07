<?php 

/**
 * Tagger Plugin for Wolf CMS
 *
 * Copyright (C) 2008 Andrew Smith <a.smith@silentworks.co.uk>
 * Copyright (C) 2008 Tyler Beckett <tyler@tbeckett.net>

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.

 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * class PagePart
 *
 * @author Philippe Archambault <philippe.archambault@gmail.com>
 * @since  0.8.7
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
	 *
	 * @param int $value
	 */
    public function sortField($value) {
    	$fields = array('id', 'name', 'count');
    	return isset($value) ? $fields[$value] : $fields;
    }
}