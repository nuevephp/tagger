<?php

/**
 * Security measure for Wolf 0.7.0+
 */
if (!defined('CMS_VERSION')) {
	Flash::set('error', __('Fatal Error: CMS_VERSION not defined, Tagger ' . TAGGER_VERSION . ' is not supported by this version of Wolf CMS'));
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

class Tagger
{
    public function __construct(&$page, $params)
    {
        $this->page =& $page;
        $this->params = $params;

        switch(count($params))
        {
            case 0: break;
            case 1:
                $this->pagesByTag($params);
            break;
            default:
                page_not_found();
        }
    }

    public function tag($params = false)
    {
        if(!$params) $params = $this->params;

        return $params[0];
    }

    public function pagesByTag($params = false)
	{
		$pdoConn = Record::getConnection();

		if(!$params) $params = $this->params;
		
		$pages = array();

		$tag_unslugified = unslugify(isset($params[0]) ? $params[0] : NULL);
		$tag = isset($params[0]) ? $params[0] : NULL;
		
		$where = " WHERE page.id = page_tag.page_id AND page_tag.tag_id = tag.id AND ((tag.name = '$tag') OR (tag.name = '$tag_unslugified'))"
		 		." AND page.status_id != ".Page::STATUS_HIDDEN." AND page.status_id != ".Page::STATUS_DRAFT." ORDER BY page.created_on DESC";
		
		// Count rows in table
		$sql_count = "SELECT count(*) FROM ".TABLE_PREFIX."page AS page, ".TABLE_PREFIX."page_tag AS page_tag, ".TABLE_PREFIX."tag AS tag" . $where;
		
		$query = $pdoConn->query($sql_count);

		if($query->fetchColumn() > 0) {
			
			$sql = "SELECT page.* FROM ".TABLE_PREFIX."page AS page, ".TABLE_PREFIX."page_tag AS page_tag, ".TABLE_PREFIX."tag AS tag" . $where;
			
			$stmt = $pdoConn->prepare($sql);
			$stmt->execute();
			
			while ($object = $stmt->fetchObject()) {
				$page = new PageTagger($object, $this);
				
				// assignParts
                $page->part = get_parts($page->id);
                $pages[] = $page;
			}
		} else return false;

		return $pages;
	}
}

class PageTagger extends Page
{
	protected function setUrl()
    {
		$page = Page::findById($this->id);
        $this->url = trim($page->getUri(), '/');
    }
}

/**
 * Internal Function
 * Unslugify the tag to make it human readable.
 *
 * @since 1.0.1
 * @param string $string
 */
function unslugify($string) {
	return str_replace('-', ' ', $string);
}