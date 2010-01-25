<?php

/**
 * Tagger Plugin for Frog CMS <http://thehub.silentworks.co.uk/plugins/frog-cms/tagger.html>
 * Alternate Mirror site <http://www.tbeckett.net/articles/plugins/tagger.xhtml>
 * Copyright (C) 2008 - 2010 Andrew Smith <a.smith@silentworks.co.uk>
 * Copyright (C) 2008 Tyler Beckett <tyler@tbeckett.net>
 * 
 * Dual licensed under the MIT (mit-license.txt)
 * and GPL (gpl-license.txt) licenses.
 */

?>
<h1><?php echo __('Tagger Plugin'); ?></h1>

<form action="<?php echo get_url('plugin/tagger/save'); ?>" method="post">
    <fieldset style="padding: 0.5em;">
        <legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('Tagger Frontend settings'); ?></legend>
        <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="label"><label for="case"><?php echo __('Type Case'); ?>: </label></td>
                <td class="field">
					<select name="case" id="case">
						<option value="1" <?php if($case == "1") echo 'selected ="";' ?>><?php echo __('Uppercase'); ?></option>
						<option value="0" <?php if($case == "0") echo 'selected ="";' ?>><?php echo __('Lowercase'); ?></option>
					</select>
				</td>
                <td class="help"><?php echo __('Choose if you want your tags to be uppercase. Otherwise, they will be lowercase.'); ?></td>
            </tr>
			<tr>
                <td class="label"><label for="tag_type"><?php echo __('Tag Type'); ?>: </label></td>
                <td class="field">
					<select name="tag_type" id="tag_type">
						<option value="count" <?php if($tag_type == "count") echo 'selected ="";' ?>><?php echo __('Count'); ?></option>
						<option value="cloud" <?php if($tag_type == "cloud") echo 'selected ="";' ?>><?php echo __('Cloud'); ?></option>
						<option value="default" <?php if($tag_type == "default") echo 'selected ="";' ?>><?php echo __('List'); ?></option>
					</select>
				</td>
                <td class="help"><?php echo __("Select how you would like the tags to be displayed, you can also overide this within the tag snippet."); ?></td>
            </tr>
        </table>
    </fieldset>
    <fieldset style="padding: 0.5em;">
        <legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('Tagger Backend settings'); ?></legend>
        <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
        	<tr>
                <td class="label"><label for="rowspage"><?php echo __('Tags per page'); ?>: </label></td>
                <td class="field">
					<input type="text" class="textinput" value="<?php echo $rowspage; ?>" name="rowspage" />
				</td>
                <td class="help"><?php echo __('Sets the number of tags to be displayed per page in the backend.'); ?></td>
        	</tr>
	        <tr>
	            <td class="label"><label for="sort_field"><?php echo __('Sort Field'); ?>: </label></td>
	            <td class="field">
					<select name="sort_field" id="sort_field">
						<?php foreach (Tagger::sortField() as $key => $field): ?>
							<option value="<?php echo $key; ?>" <?php if($sort_field == $key) echo 'selected ="";' ?>><?php echo $field; ?></option>
						<?php endforeach ?>
					</select>
				</td>
	            <td class="help"><?php echo __('Choose the field your would like your tags to be sorted by in the backend.'); ?></td>
	        </tr>
			<tr>
	            <td class="label"><label for="sort_order"><?php echo __('Sort Order'); ?>: </label></td>
	            <td class="field">
					<select name="sort_order" id="sort_order">
						<option value="ASC" <?php if($sort_order == "ASC") echo 'selected ="";' ?>><?php echo __('ASC'); ?></option>
						<option value="DESC" <?php if($sort_order == "DESC") echo 'selected ="";' ?>><?php echo __('DESC'); ?></option>
					</select>
				</td>
	            <td class="help"><?php echo __("Choose the order your would like your tags to be sorted by in the backend."); ?></td>
	        </tr>
	    </table>
    </fieldset>
    <br/>
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="s" value="<?php echo __('Save'); ?>" />
    </p>
</form>
