<?php

/**
 * Tagger Plugin for Frog CMS <http://thehub.silentworks.co.uk/plugins/frog-cms/tagger.html>
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

$PDO = Record::getConnection();
$driver = strtolower($PDO->getAttribute(Record::ATTR_DRIVER_NAME));

if ($driver == 'mysql')
{
	$query = $PDO->query("SELECT * FROM ".TABLE_PREFIX."page WHERE behavior_id = 'tagger'");

	if(!$query->rowCount()){
		// Create Pages
		$PDO->exec("INSERT INTO ".TABLE_PREFIX."page (id, title, slug, breadcrumb, keywords, description, parent_id, layout_id, behavior_id, status_id, created_on, published_on, updated_on, created_by_id, updated_by_id, position, is_protected) VALUES('', 'Tag', 'tag', 'Tag', '', '', '1', '0', 'tagger', '101', '".date('Y-m-d H:i:s')."', NULL, NULL, '1', '1', '2', '0')");
		$PDO->exec("INSERT INTO ".TABLE_PREFIX."page_part (id, name, filter_id, content, content_html, page_id) VALUES('', 'body', '', '<?php\r\n\$pages = \$this->tagger->pagesByTag();\r\nif(\$pages){\r\necho \"<h3>Pages tagged with ''\".\$this->tagger->tag().\"''</h3>\";\r\n      foreach(\$pages as \$slug => \$page)\r\n{\r\n		echo ''<h3><a href=\"''.\$slug.''\">''.\$page.''</a></h3>'';\r\n	}\r\n} else {\r\n	echo \"There are no items with this tag.\";\r\n}\r\n?>', '<?php\r\n\$pages = \$this->tagger->pagesByTag();\r\nif(\$pages){\r\necho \"<h3>Pages tagged with ''\".\$this->tagger->tag().\"''</h3>\";\r\n      foreach(\$pages as \$slug => \$page)\r\n{\r\n		echo ''<h3><a href=\"''.\$slug.''\">''.\$page.''</a></h3>'';\r\n	}\r\n} else {\r\n	echo \"There are no items with this tag.\";\r\n}\r\n?>', '".$PDO->lastInsertId()."')");
	}
}
// Create Snippet
$PDO->exec("INSERT INTO ".TABLE_PREFIX."snippet (name, filter_id, content, content_html, created_on, created_by_id) VALUES ('tags', '', '<h3>Tags</h3>\r\n<?php tagger(); ?>', '<h3>Tags</h3>\r\n<?php tagger(); ?>', '".date('Y-m-d H:i:s')."', 1)");

// Store settings new style
$settings = array('tag_type' => 'count',
                  'case' => '0',
                  'rowspage' => '15'
                 );

Plugin::setAllSettings($settings, 'tagger');
