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

Introduction and Brief History:

The tags idea was brought to light back in February of 2008, before the functionality was even fully implemented within Frog CMS.  The idea was put to the side due to this lack of functionality, but was brought back to centre stage at the end of June, 2008 and blossomed from there.  It was quickly picked up as a great idea by BDesign, easylancer, and mvdkleijn (in alphabetical order) on the Frog CMS forum (http://forum.madebyfrog.com/topic/180) and with much discussion between them and help from many others, Tagger was born in its early state at the beginning of July, 2008.

In mid-October, 2008, the plugin was brought back into the spotlight and needed updates to bring it up to speed with the quickly developing Frog.  mtylerb and easylancer began working together and fixed issues that had surfaced in the few months it had been around.  In mid-November, 2008, the plugin was finally ready for re-release!

Credits (in alphabetical order):

BDesign
David
easylancer
Jonas
mtylerb
mvdkleijn
phillipe

Installation:

1) Place this plugin in the Frog plugins directory.
2) Activate the plugin through the administration screen.
3) The plugin should automatically be ready to use, go to step 4 only if you can't find the Tags page or tag snippet.

Use Below for Manual Install Only
4) Create a snippet with the information in the snippet.txt file
5) Use the code(s) below in a page/snippet/layout to produce the desired effect.

<?php $this->includeSnippet('snippetname'); ?>

note: snippetname will be the name you give your snippet.

//Snippet Code

<h3>Tag Cloud</h3>
<ul id="tagger">
<?php tagger('cloud'); ?>
</ul>

snippet options are cloud, count and you can also leave it empty.
 - count is just a list with the number of items tagged with the tag next to it eg. news(1)
 - leaving it blank is the same as count without the number eg. news

// Page Code
Create a new page and add this code below inside it:

<?php
$pages = $this->tagger->pagesByTag();
if($pages){
echo "<h3>Pages tagged with '".$this->tagger->tag()."'</h3>";
      foreach($pages as $slug => $page)
{
		echo '<h3><a href="'.$slug.'">'.$page.'</a></h3>';
	}
} else {
	echo "There is no items with this tag.";
}
?>

Ensure you set this Page Type to Tagger.
