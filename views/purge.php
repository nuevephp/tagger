<?php
/**
 * Tagger Plugin for Wolf CMS <http://www.tbeckett.net/articles/plugins/tagger.xhtml>
 * Copyright (C) 2010 Tyler Beckett <tyler@tbeckett.net>

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

?>
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
