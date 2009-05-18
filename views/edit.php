<h1><?php echo __(ucfirst($action).' tag'); ?></h1>

<form action="<?php echo $action=='edit' ? get_url('plugin/tagger/edit/'.$tag->id): get_url('plugin/tagger/add'); ; ?>" method="post">
  <div class="form-area">
    <p class="title">
      <label for="tag_name"><?php echo __('Name'); ?></label>
      <input class="textbox" id="tag_name" maxlength="100" name="tag[name]" size="100" type="text" value="<?php echo $tag->name; ?>" />
    </p>
    <!-- Hidden Fields -->
    <input class="textbox" id="tag_count" maxlength="100" name="tag[count]" size="100" type="hidden" value="<?php echo $tag->count; ?>" />
  </div>
  <p class="buttons">
    <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save'); ?>" />
    <input class="button" name="continue" type="submit" accesskey="e" value="<?php echo __('Save and Continue Editing'); ?>" />
    <?php echo __('or'); ?> <a href="<?php echo get_url('plugin/tagger'); ?>"><?php echo __('Cancel'); ?></a>
  </p>
</form>

<script type="text/javascript">
// <![CDATA[
  document.getElementById('tag_name').focus();
// ]]>
</script>