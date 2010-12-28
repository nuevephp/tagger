<?php if (Dispatcher::getAction() != 'view'): ?>

<p class="button"><a href="<?php echo get_url('plugin/tagger/add'); ?>"><img src="<?php echo TAGGER_ROOT; ?>/images/tag.png" align="middle" alt="snippet icon" /> <?php echo __('New Tag'); ?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/tagger/settings'); ?>"><img src="<?php echo TAGGER_ROOT; ?>/images/settings.png" align="middle" alt="page icon" /> <?php echo __('Settings'); ?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/tagger/purge'); ?>"><img src="/wolf/plugins/tagger/images/purge.png" align="middle" alt="snippet icon" /> <?php echo __('Purge & Recount'); ?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/tagger/documentation'); ?>"><img src="<?php echo TAGGER_ROOT; ?>/images/documentation.png" align="middle" alt="snippet icon" /> <?php echo __('Documentation'); ?></a></p>
<p class="button"><a href="<?php echo get_url('plugin/tagger/documentation/tutorials'); ?>"><img src="<?php echo TAGGER_ROOT; ?>/images/documentation.png" align="middle" alt="snippet icon" /> <?php echo __('Tutorials'); ?></a></p>

<div class="box">
    <h2><?php echo __('What is a Tag?'); ?></h2>
    <p><?php echo __('Tags are generally used to organise content.'); ?></p>
</div>

<div class="box">
    <h2><?php echo __('How to use Tagger?'); ?></h2>
    <p><?php echo __('You can add Tags to your pages by including this snippet into the page which you want the tags to appear.'); ?></p>
    <p><code>&lt;?php $this->includeSnippet('tags'); ?&gt;</code></p>
</div>

<?php endif; ?>