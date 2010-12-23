<?php

/**
 * Tagger Plugin for Wolf CMS <http://thehub.silentworks.co.uk/plugins/frog-cms/tagger.html>
 * Alternate Mirror site <http://www.tbeckett.net/articles/plugins/tagger.xhtml>
 * Copyright (C) 2008 - 2010 Andrew Smith <a.smith@silentworks.co.uk>
 * Copyright (C) 2008 - 2010 Tyler Beckett <tyler@tbeckett.net>
 * 
 * Dual licensed under the MIT (license/mit-license.txt)
 * and GPL (license/gpl-license.txt) licenses.
 */

if (Plugin::deleteAllSettings('tagger') === false) {
    Flash::set('error', __('Unable to remove plugin settings.'));
    redirect(get_url('setting'));
}
else {
    Flash::set('success', __('Successfully uninstalled plugin.'));
    redirect(get_url('setting'));
}