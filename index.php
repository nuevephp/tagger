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

Plugin::setInfos(array(
    'id'          => 'tagger',
    'title'       => 'Tagger',
    'description' => 'Add tags to any page and organize your website.',
    'version'     => '1.1.0',
    'license'     => 'AGPL',
    'author'      => 'Andrew Smith and Tyler Beckett',
    'website'     => 'http://thehub.silentworks.co.uk/plugins/frog-cms/tagger',
	'update_url'  => 'http://thehub.silentworks.co.uk/plugin-version.xml',
    'require_frog_version' => '0.9.5')
);

Plugin::addController('tagger', 'Tagger');
Behavior::add('tagger', 'tagger/tagger.php');

function cmpVals($val1, $val2)
{
	return strcasecmp($val1, $val2);
}

/**
 * Display tags on a page
 *
 * @since 0.0.8
 *
 * @param string booleon booleon
 */
function tagger($option = false, $case = false, $limit = false)
{
    global $__FROG_CONN__;

    $sql = 'SELECT DISTINCT(slug) FROM '.TABLE_PREFIX.'page WHERE behavior_id = "tagger"';

    $stmt = $__FROG_CONN__->prepare($sql);
    $stmt->execute();

    if (!is_null($slug = $stmt->fetchColumn())) { $tagger = BASE_URL.$slug.'/'; }
	// Setting Limit if selected
	if($limit){ $limit_set = " LIMIT 0, {$limit}"; } else { $limit_set = ""; }
    $sql = 'SELECT name, count FROM '.TABLE_PREFIX.'tag AS tag, '.TABLE_PREFIX.'page AS page, '.TABLE_PREFIX.'page_tag AS page_tag WHERE tag.id = page_tag.tag_id AND page_tag.page_id = page.id AND page.status_id != '.Page::STATUS_HIDDEN.' AND page.status_id != '.Page::STATUS_DRAFT . $limit_set;
    $stmt = $__FROG_CONN__->prepare($sql);
    $stmt->execute();

    // Putting Tags into a array
    while($tag = $stmt->fetchObject()) $tags[$tag->name] = $tag->count;

    if($tags)
    {
		// Sort array
		uksort($tags,'cmpVals');

		// Tag settings from database
		$tag_setting_type = Plugin::getSetting('tag_type', 'tagger');
		$tag_setting_case = Plugin::getSetting('case', 'tagger');

		// Tag display
		$tag_type = $option ? $option : $tag_setting_type;
		$tag_case = $case ? $case : $tag_setting_case;

		switch($tag_type){
			case "cloud":
				$max_size = 32; // max font size in pixels
				$min_size = 12; // min font size in pixels

				// largest and smallest array values
				$max_qty = max(array_values($tags));
				$min_qty = min(array_values($tags));

				// find the range of values
				$spread = $max_qty - $min_qty;
				if ($spread == 0) { // we don't want to divide by zero
					$spread = 1;
				}

				// set the font-size increment
				$step = ($max_size - $min_size) / ($spread);
				echo '<ul class="tagger">';
				// loop through the tag array
				foreach ($tags as $key => $value) {
					// calculate font-size
					// find the $value in excess of $min_qty
					// multiply by the font-size increment ($size)
					// and add the $min_size set above
					$size = round($min_size + (($value - $min_qty) * $step));
					$key_case = $tag_case == "1" ? ucfirst($key) : strtolower($key);
					echo '<li style="display: inline; border: none;"><a href="'. $tagger . slugify($key) . URL_SUFFIX .'" style="display: inline; border: none; font-size: ' . $size . 'px; padding: 2px" title="' . $value . ' things tagged with ' . $key . '">' . $key_case . "</a></li>\n";
				}
				echo '</ul>';
			break;
			case "count":
				echo '<ul class="tagger">';
				// loop through the tag array
				foreach ($tags as $key => $value) {
					$key_case = $tag_case == "1" ? ucfirst($key) : strtolower($key);
					echo '<li><a href="'. $tagger . slugify($key) . URL_SUFFIX .'" title="' . $value . ' things tagged with ' . $key . '">' . $key_case . ' ('. $value .')</a></li>';
				}
				echo '</ul>';
			break;
			default:
				echo '<ul class="tagger">';
				// loop through the tag array
				foreach ($tags as $key => $value) {
					$key_case = $tag_case == 1 ? ucfirst($key) : strtolower($key);
					echo '<li><a href="'. $tagger . slugify($key) . URL_SUFFIX .'" title="' . $value . ' things tagged with ' . $key . '">' . $key_case . '</a></li>';
				}
				echo '</ul>';
			break;
		}
    }
}

/**
 * Display tags as links.
 *
 * @since 1.1.0
 *
 * @param object $tags
 */
function tag_links($tags, $delimiter = ', ')
{
	global $__FROG_CONN__;

    $sql = 'SELECT DISTINCT(slug) FROM '.TABLE_PREFIX.'page WHERE behavior_id = "tagger"';

    $stmt = $__FROG_CONN__->prepare($sql);
    $stmt->execute();

    if (!is_null($slug = $stmt->fetchColumn())) { $tagger = BASE_URL.$slug.'/'; }

	$i = 1;
	foreach($tags as $tag){
		echo '<a href="'. $tagger . $tag . URL_SUFFIX .'">' . $tag . '</a>';
		echo $i == count($tags) ? '.' : $delimiter;
		$i++;
	}
}

/**
 * Internal Function to remove whitespace
 *
 * @since 1.0.1
 *
 * @param string $string
 */
function slugify($string){
	$slug = strtolower($string); // lower-case the string
	$slug = preg_replace('/[^a-z0-9- ]/', '', $slug); // remove all non-alphanumeric characters except for spaces and hyphens
	return str_replace(' ', '-', $slug);// substitute the spaces with hyphens
}
