<?php
/**
 * Tagger Plugin for Wolf CMS <http://thehub.silentworks.co.uk/plugins/frog-cms/tagger.html>
 * Alternate Mirror site <http://www.tbeckett.net/articles/plugins/tagger.xhtml>
 * Copyright (C) 2008 - 2010 Andrew Smith <a.smith@silentworks.co.uk>
 * Copyright (C) 2008 - 2010 Tyler Beckett <tyler@tbeckett.net>
 * 
 * Dual licensed under the MIT (license/mit-license.txt)
 * and GPL (license/gpl-license.txt) licenses.
 */

// Root location where Tagger plugin lives.
define('TAGGER_ROOT', URI_PUBLIC.'wolf/plugins/tagger');

// Tagger Version
define('TAGGER_VERSION', '1.4.0');
define('TAGGER_NAME',__('Tagger'));

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
		exit();
	}
}

Plugin::setInfos(array(
    'id'          => 'tagger',
    'title'       => TAGGER_NAME,
    'description' => __('Add tags to any page and organize your website.'),
    'version'     => TAGGER_VERSION,
    'license'     => 'MIT',
    'author'      => 'Andrew Smith ' . __('and') . ' Tyler Beckett',
    'website'     => 'http://www.tbeckett.net/articles/plugins/tagger.xhtml',
	'update_url'  => 'http://www.tbeckett.net/wpv.xhtml',
    'require_wolf_version' => '0.7.3')
);

Plugin::addController('tagger', TAGGER_NAME);
Behavior::add('tagger', 'tagger/tagger.php');

// Setting error display depending on debug mode or not
error_reporting((DEBUG ? (E_ALL | E_STRICT) : 0));

/**
 * Tagger Tag Cloud, Count and List new Object
 *
 * @since 1.4.0
*/
class Tags
{
	public function __construct($option = array())
	{
		return self::render($option);
	}
	
	/**
	 * Gets the url where pages are displayed based on tag selected
	 *
	 * @since 1.4.0
	*/
	public static function tag_url($page_id = NULL)
	{
		$page_option = ($page_id !== NULL) ? " AND id = {$page_id}" : "";

	    $sql = 'SELECT id FROM '.TABLE_PREFIX.'page WHERE behavior_id = "tagger"' . $page_option;
		
	    $stmt = Record::getConnection()->prepare($sql);
	    $stmt->execute();
		$id = $stmt->fetchColumn();
		
	    if (!is_null($id) && $id !== false) {
			$page = Page::findById($id);
			$url = $page->getUri();
			return BASE_URL . $url . '/';
		} else {
			return self::tag_url(NULL);
		}
	}
	
	/**
	 * Display tags as links.
	 *
	 * @since 1.4.0
	 * @param object $tags
	 */
	public static function tag_links($tags, $option = array())
	{
		$delimiter = array_key_exists('delimiter', $option) ? $option['delimiter'] : ', ';
		$tagger_page = array_key_exists('tagger_page', $option) ? $option['tagger_page'] : NULL;
		
		$i = 1;
		foreach($tags as $tag){
			$url = self::tag_url($tagger_page) . $tag . URL_SUFFIX;
			$end = $i == count($tags) ? '.' : $delimiter;
			
			echo sprintf('<a href="%s">%s</a>%s', 
				$url, 
				$tag,
				$end
				);
			$i++;
		}
	}
	
	/**
	 * Load Snippet that has template for Tagger.
	 *
	 * @since 1.4.0
	 * @param string $name
	 */
	public static function tpl($name)
	{
		$sql = 'SELECT content_html FROM '.TABLE_PREFIX.'snippet WHERE name LIKE ?';

	    $stmt = Record::getConnection()->prepare($sql);
	    $stmt->execute(array($name));

	    if ($snippet = $stmt->fetchObject()) {
	        return $snippet->content_html;
	    }
	}
	
	/**
	 * Display tags on a page
	 *
	 * @since 1.4.0
	 * @param string booleon booleon
	 */
	public static function render($option = array())
	{
		// Tag settings from database
		$tag_setting_type = Plugin::getSetting('tag_type', 'tagger');
		$tag_setting_case = Plugin::getSetting('case', 'tagger');

		// Tag display
		$tag_type = array_key_exists('type', $option) ? $option['type'] : $tag_setting_type;
		$tag_case = array_key_exists('case', $option) ? $option['case'] : $tag_setting_case;
		
		// Setting Limit, Parent and Tagger page if selected
		$limit_set = array_key_exists('limit', $option) ? " LIMIT 0, {$option['limit']}" : NULL;
		$parent = array_key_exists('parent', $option) ? " AND page.parent_id = {$option['parent']}" : NULL;
		$tagger_page = array_key_exists('tagger_page', $option) ? $option['tagger_page'] : NULL;
		$tpl = array_key_exists('tagger_tpl', $option) ? $option['tagger_tpl'] : NULL;

	    $sql = 'SELECT name, count FROM '.TABLE_PREFIX.'tag AS tag, '.TABLE_PREFIX.'page AS page, '.TABLE_PREFIX.'page_tag AS page_tag'
			   .' WHERE tag.id = page_tag.tag_id AND page_tag.page_id = page.id AND page.status_id != '.Page::STATUS_HIDDEN.' AND'
			   .' page.status_id != '.Page::STATUS_DRAFT . $parent . $limit_set;

	    $stmt = Record::getConnection()->prepare($sql);
	    $stmt->execute();

	    // Putting Tags into a array
	    while($tag = $stmt->fetchObject()) {
			$tags[$tag->name] = $tag->count;
		}

	    if(isset($tags)) {
			// Sort array
			uksort($tags, 'cmpVals');

			switch($tag_type) {
				case "cloud":
					$max_size = 32; // max font size in pixels
					$min_size = 12; // min font size in pixels

					// largest and smallest array values
					$max_qty = max(array_values($tags));
					$min_qty = min(array_values($tags));

					// find the range of values
					$spread = $max_qty - $min_qty;
					if ($spread == 0) { $spread = 1; }

					// set the font-size increment
					$step = ($max_size - $min_size) / ($spread);
					if($tpl) {
						eval('?>'.self::tpl($tpl));
					} else {
						echo '<ul class="tagger">';
						foreach ($tags as $key => $value) {
							// calculate font-size, find the $value in excess of $min_qty, multiply by the font-size increment ($size) and add the $min_size set above
							$size = round($min_size + (($value - $min_qty) * $step));
							$key_case = ($tag_case == "1") ? ucfirst($key) : strtolower($key);
							$url = self::tag_url($tagger_page) . slugify($key) . URL_SUFFIX;
							
							echo sprintf('<li style="display: inline; border: none;"><a href="%s"  style="display: inline; border: none; font-size: %spx; padding: 2px" title="%s things tagged with %s">%s</a></li>'."\r\n",
								$url,
								$size,
								$value,
								$key,
								htmlspecialchars_decode($key_case)
								);
						}
						echo '</ul>';
					}
				break;
				case "count":
					if($tpl) {
						eval('?>'.self::tpl($tpl));
					} else {
						echo '<ul class="tagger">';
						foreach ($tags as $key => $value) {
							$key_case = ($tag_case == "1") ? ucfirst($key) : strtolower($key);
							$url = self::tag_url($tagger_page) . slugify($key) . URL_SUFFIX;
							
							echo sprintf('<li><a href="%s" title="%s things tagged with %s">%s (%s)</a></li>',
								$url,
								$value,
								$key,
								htmlspecialchars_decode($key_case),
								$value
								);
						}
						echo '</ul>';
					}
				break;
				default:
					if($tpl) {
						eval('?>'.self::tpl($tpl));
					} else {
						echo '<ul class="tagger">';
						foreach ($tags as $key => $value) {
							$key_case = ($tag_case == 1) ? ucfirst($key) : strtolower($key);
							$url = self::tag_url($tagger_page) . slugify($key) . URL_SUFFIX;
							
							echo sprintf('<li><a href="%s" title="%s things tagged with %s">%s</a></li>',
								$url,
								$value,
								$key,
								htmlspecialchars_decode($key_case)
								);
						}
						echo '</ul>';
					}
				break;
			}
	    }
	}
}

function cmpVals($val1, $val2)
{
	return strcasecmp($val1, $val2);
}

/**
 * Internal Function to remove whitespace
 *
 * @since 1.0.1
 * @param string $string
 */
function slugify($string){
	$search = array(' ','å','ä','á','à','â','ã','ª','Á','À','Â','Ã','é','ë','è','ê','Ë','É','È','Ê','ï','í','ì','î','Í','Ì','Î','ø','ö','ò','ó','ô','õ','º','Ó','Ò','Ô','Õ','ü','ú','ù','û','Ú','Ù','Û','ç','Ç','Ñ','ñ');
	$replace = array('-','a','a','a','a','a','a','a','A','A','A','A','e','e','e','e','E','E','E','E','i','i','i','i','I','I','I','o','o','o','o','o','o','o','O','O','O','O','u','u','u','u','U','U','U','c','C','N','n');
    $slug = trim(str_replace($search, $replace, $string)); // substitute the spaces with hyphens
    $slug = strtolower($slug); // lower-case the string
	return preg_replace('[^A-Za-z0-9\_\.\-]', '', $slug); // remove all non-alphanumeric characters except for spaces and hyphens
}

// Backward Compatability functions
/**
 * Gets the url where pages are displayed based on tag selected
 *
 * @since 1.2.0
 * @deprecated 1.4.0
 */
function tag_url($page_id = NULL)
{
	return Tags::tag_url($page_id);
}

/**
 * Display tags on a page
 *
 * @since 0.0.8
 * @param string booleon booleon
 * @deprecated 1.4.0
 */
function tagger($option = array())
{
	return new Tags($option);
}

/**
 * Display tags as links.
 *
 * @since 1.1.0
 * @param object $tags
 * @deprecated 1.4.0
 */
function tag_links($tags, $option = array())
{
	return Tags::tag_links($tags, $option);
}