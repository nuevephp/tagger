<h1><?php echo __('Tagger Plugin'); ?></h1>

<form action="<?php echo get_url('plugin/tagger/purged'); ?>" method="post">
    <fieldset style="padding: 0.5em;">
        <legend style="padding: 0em 0.5em 0em 0.5em; font-weight: bold;"><?php echo __('Tagger Purge & Recount Function'); ?></legend>
        <table class="fieldset" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="label"><label for="case"><?php echo __('Purge & Recount'); ?>: </label></td>
                <td class="help">
					<?php echo __('This action will purge all tags with a zero count from the database.  It will also recount all non-zero tags and input a fresh count.  If you wish to continue, please click the button below.  Otherwise leave this page to cancel.'); ?>
				</td>
             </tr>
		</table>
    </fieldset>
    <br/>
    <p class="buttons">
        <input class="button" name="commit" type="submit" accesskey="p" value="<?php echo __('Purge & Recount'); ?>" />
    </p>
</form>
