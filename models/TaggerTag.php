<?php

/**
 * Tagger Plugin for Frog CMS <http://thehub.silentworks.co.uk/plugins/frog-cms/tagger>
 * Alternate Mirror site <http://www.tbeckett.net/articles/plugins/tagger.xhtml>
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
 * class TaggerTag
 *
 * @author Andrew Smith <a.smith@silentworks.co.uk>
 * @since  0.9
 */

class TaggerTag extends PageTag
{
    public function beforeSave()
    {
        // apply filter to save is generated result in the database
        if ( ! empty($this->filter_id))
            $this->content_html = Filter::get($this->filter_id)->apply($this->content);
        else
            $this->content_html = $this->content;

        return true;
    }

	public function findTagsAndPageAssigned($id)
	{
		// Prepare SQL
        $sql = 'SELECT page.*, tag.id AS tagid FROM '.TABLE_PREFIX.'page AS page, '.TABLE_PREFIX.'page_tag AS page_tag, '.TABLE_PREFIX.'tag AS tag WHERE page.id = page_tag.page_id AND page_tag.tag_id ='. $id;

        $stmt = self::$__CONN__->prepare($sql);
        $stmt->execute();

		while($page = $stmt->fetchObject()) $pages[$page->id] = $page->title;

		return $pages;
	}
	
	public static function deletePageTagRelationship($page_id, $tag_id)
	{	
		// get the id of the tag
        $tag = Record::findOneFrom('Tag', 'id=?', array($tag_id));
        Record::deleteWhere('PageTag', 'page_id=? AND tag_id=?', array($page_id, $tag->id));
        $tag->count--;
        $tag->save();
        return true;
	}

    public static function findByPageId($id)
    {
        return self::findAllFrom('TaggerTag', 'page_id='.(int)$id.' ORDER BY id');
    }

    public static function deleteByTagId($id)
    {
        return self::$__CONN__->exec('DELETE FROM '.self::tableNameFromClassName('TaggerTag').' WHERE tag_id='.(int)$id) === false ? false: true;
    }

} // end PagePart class
