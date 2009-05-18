<h1><?php echo __('Tagger: Documentation'); ?></h1>

<h2 class="subtitle"><?php echo __('How to use Tagger?'); ?></h2>
<p><?php echo __('You can add Tags to your pages by including this snippet into the page which you want the tags to appear.'); ?></p>
<p class="code bottom-5"><code>&lt;?php $this->includeSnippet('tags'); ?&gt;</code></p>
<h2 class="subtitle"><?php echo __('Overide Tagger Settings'); ?></h2>
<p><?php echo __('Tagger is configurable. Set parameters by editing Snippets->tags and changing the following valid parameters.  All of the options below are optional.'); ?></p>
<p>
	<ul>
		<li><?php echo __('<strong>Tag Type: </strong>Set this to cloud, count or just leave it blank.
		<ul style="text-indent: 15px;"><li>Cloud creates a "cloud" of tags with some larger than others.
		This creates a neat visual effect.</li><li>Count will create a list of tags with the number of times that tag is used.
		For example <i>Tag (1)</i>.</li><li>Leaving this field blank will default to a list of tags, but without the number afer it.
		For example<i>Tag</i>.</ul>'); ?>
		</li>
		<li><?php echo __('<strong>Typecase: </strong> Set this to either true or false.<ul style="text-indent: 15px;"><li>True will
		simply capitalize the first letter of the tag.  For example <i>tag</i> will become <i>Tag</i>.</li><li>False, which is the
		default (blank), will leave the tag however it was entered into the database.</li></ul>'); ?>
		</li>
		<li><?php echo __('<strong>Limit:</strong> By default it is unlimited (blank).<ul style="text-indent: 15px;"><li>You can enter
		a number here (without quotes) to limit the number of tags that will be displayed.</li></ul>'); ?></li>
	</ul>
</p>
<p class="code"><code>&lt;?php tagger('count', true, 5); ?&gt;</code></p>
<h2 class="subtitle"><?php echo __('Tag Links'); ?></h2>
<p><?php echo __('If you would like to have the default tags, that appear in the archives just before clicking a page link, show up as links, please edit your archive and change the line that says:'); ?></p>
<p class="code bottom-5"><code>&lt;?php echo join(', ', $article->tags()); ?&gt;</code></p>
<p><?php echo __('to be:'); ?></p>
<p class="code bottom-20">
	<code>&lt;?php echo tag_links($article->tags()); ?&gt;</code>
</p>
<p><?php echo __('The new Tag Links function give you the flexibility to be able to change a second parameter to the delimites you wish, so you could have'); ?></p>
<p class="code bottom-20">
	<code>&lt;?php echo tag_links($article->tags(), ' - '); ?&gt;</code>
</p>
<p><?php echo __('The second parameter is not required as this will default to a comma if you choose not to set it.'); ?></p>
