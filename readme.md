# [Tagger Plugin for Wolf CMS](http://www.tbeckett.net/articles/plugins/tagger.xhtml)
#### [Alternate Link](http://thehub.silentworks.co.uk/plugins/frog-cms/tagger.html)

## License

This plugin is dually licensed under the MIT and GPL license models.  For more information view the license_overview.txt file in the license folder.

## Introduction and Brief History:

The tags idea was brought to light back in February of 2008, before the functionality was even fully implemented within Frog CMS.  The idea was put to the side due to this lack of functionality, but was brought back to centre stage at the end of June, 2008 and blossomed from there.  It was quickly picked up as a great idea by _BDesign_, _easylancer_, and _mvdkleijn_ (in alphabetical order) on the [Frog CMS forum](http://forum.madebyfrog.com/topic/180) and with much discussion between them and help from many others, Tagger was born in its early state at the beginning of July, 2008.

In mid-October, 2008, the plugin was brought back into the spotlight and needed updates to bring it up to speed with the quickly developing Frog.  _mtylerb_ and _easylancer_ began working together and fixed issues that had surfaced in the few months it had been around.  In mid-November, 2008, the plugin was finally ready for re-release!

After the fork of Wolf CMS from Frog in July 2009, this plugin was first ported to the new system in August 2009.

## Credits:

* BDesign
* David
* easylancer
* Jonas
* mtylerb
* mvdkleijn
* phillipe

## Installation:

1. Place this plugin (as a directory named 'tagger' with all contents) in the Wolf /wolf/plugins directory.
2. Activate the plugin through the administration screen.
3. The plugin should automatically be ready to use, go to the next steps only if you can't find the Tags page or Tag snippet.

_Use Below for Manual Install Only_

4. Create a snippet with the information in the step 6
5. Use the code(s) below in a page/snippet/layout to produce the desired effect.

    <?php $this->includeSnippet('snippetname'); ?>

__Note:__ snippetname will be the name you give your snippet.

6. __Snippet Code__

        <h3>Tag Cloud</h3>
        <ul id="tagger">
        <?php Tags::render(array('type' => 'cloud')); ?>
        </ul>

   * Snippet options are cloud, count and you can also leave it empty.
   * Count is just a list with the number of items tagged with the tag next to it eg. news(1)
   * Leaving it blank is the same as count without the number eg. news

7. __Article Code__
Create a new article and add this code below inside it:

        <?php $pages = $this->tagger->pagesByTag(); ?>
		<?php if($pages): ?>
			<h3>You are viewing pages in "<?php echo $this->tagger->tag(); ?>"</h3>
			<?php foreach ($pages as $page): ?>
			<div class="entry">
			  <h3><?php echo $page->link(); ?></h3>
		<?php echo $page->content(); ?>
		  <p class="info">Posted by <?php echo $page->author(); ?> on <?php echo $page->date(); ?>  
		     <br />tags: <?php echo Tags::tag_links($page->tags(), array('delimiter' => ', ')); ?>
		  </p>
			</div>
			<?php endforeach ?>
		<?php else: ?>
		<h3>There are no pages tagged with "<?php echo $this->tagger->tag(); ?>"</h3>
		<?php endif ?>

8. Ensure you set this Page Type to Tagger.
