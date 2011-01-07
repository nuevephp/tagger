	<h3>Table of Contents</h3>
	<ul>
		<li><a href="#how_to"><?php echo __('How to use Tagger?'); ?></a></li>
		<li><a href="#override_tagger"><?php echo __('Override Tagger Settings'); ?></a></li>
		<li><a href="#tag_links"><?php echo __('Tag Links'); ?></a></li>
	</ul>
	<h2 id="how_to" class="subtitle"><?php echo __('How to use Tagger?'); ?></h2>
	<p>You can add Tags to your pages by including this snippet into the page which you want the tags to appear.</p>
	<code>&lt;?php $this->includeSnippet('<var>tags</var>'); ?&gt;</code>
	<p><?php echo __('or you can use:'); ?></p>
	<code>&lt;?php Tags::render(); ?&gt;</code>
	<h2 id="override_tagger" class="subtitle"><?php echo __('Override Tagger Settings'); ?></h2>
	<p>Tagger is configurable. Set parameters by editing Snippets->tags and changing the following valid parameters.  All of the options below are optional.</p>
	<ul>
		<li>
			<strong>Tag Type: </strong>Set this to cloud, count or just leave it blank.
			<p>Cloud creates a "cloud" of tags with some larger than others. This creates a neat visual effect.</p>
			<p>Count will create a list of tags with the number of times that tag is used. For example <i>Tag (1)</i>.</p>
			<p>Leaving this field blank will default to a list of tags, but without the number afer it. For example<i>Tag</i>.</p>
		</li>
		<li>
			<strong>Typecase: </strong> Set this to either true or false.
			<p>True will simply capitalize the first letter of the tag.  For example <i>tag</i> will become <i>Tag</i>.</p>
			<p>False, which is the default (blank), will leave the tag however it was entered into the database.</p>
		</li>
		<li>
			<strong>Limit:</strong> By default it is unlimited (blank).
			<p>You can enter a number here (without quotes) to limit the number of tags that will be displayed.</p>
		</li>
		<li>
			<strong>Parent:</strong> By default it is null (blank). <em class="new">New 1.2.0</em>
			<p>You can enter a number (without quotes) of the parent page, this will then display all the tags in the children pages only.</p>
		</li>
		<li>
			<strong>Tagger Page:</strong> By default it is null (blank). <em class="new">New 1.3.0</em>
			<p>You can enter a number (without quotes) of the tagger page, this will then point all the tags urls to this page.</p>
		</li>
		<li>
			<strong>Tagger Template:</strong> This is set by default. <em class="new">New 1.4.0</em>
			<p>You can enter a snippet name which you created as template, this will then override the default hardcoded template.</p>
		</li>
	</ul>
	<code>
		&lt;?php <br/>
			&nbsp;&nbsp;&nbsp;$params = <samp>array</samp>('type' => 'count', 'case' => true, 'limit' => 5, 'parent' => 4, 'tagger_page' => 10, 'tagger_tpl' => 'tagger_count_tpl');<br/>
			&nbsp;&nbsp;&nbsp;Tags::render(<var>$params</var>); <br/>
		?&gt;
	</code>
	<h2 id="tag_links" class="subtitle"><?php echo __('Tag Links'); ?></h2>
	<p>If you would like to have the default tags, that appear in the archives just before clicking a page link, show up as links, please edit your archive and change the line that says:</p>
	<code>&lt;?php echo join(', ', $article->tags()); ?&gt;</code>
	<p><?php echo __('to be:'); ?></p>
	<code>&lt;?php echo Tags::tag_links($article->tags()); ?&gt;</code>
	<p>The new Tag Links function give you the flexibility to be able to change a second parameter to the delimites you wish, so you could have'</p>
	<code>&lt;?php echo Tags::tag_links($article->tags(), <samp>array</samp>('delimiter' => ' - ', 'tagger_page' => 10)); ?&gt;</code>
	<p>The second parameter is not required as this will default to a comma and NULL if you choose not to set it.</p>