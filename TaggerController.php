<?php

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

/**
 * The Tagger plugin provides an interface to add, edit and delete tags.
 *
 * @package frog
 * @subpackage plugin.tagger
 *
 * @author Andrew Smith <a.smith@silentworks.co.uk>
 * @author Tyler Beckett <tyler@tbeckett.net>
 * @version 1.1.0
 * @since Frog version 0.9.3
 * @license http://www.gnu.org/licenses/agpl.html AGPL License
 * @copyright Andrew Smith, Tyler Beckett, 2008
 */

// Include Tagger Models
include_once 'models/Tagger.php';
include_once 'models/TaggerTag.php';

/**
 * class TaggerController
 *
 * @package frog
 * @subpackage plugin.tagger
 * @author Andrew Smith <a.smith@silentworks.co.uk>
 * @author Tyler Beckett <tyler@tbeckett.net>
 * @since Frog version 0.9.3
 */
class TaggerController extends PluginController
{
    public function __construct()
    {
        $this->setLayout('backend');
        $this->assignToLayout('sidebar', new View('../../plugins/tagger/views/sidebar'));
    }

    public function index($page = 0)
    {
		$totalTags = Tagger::findAll();

		if (isset($page)) {
			$CurPage = $page;
		} else {
			$CurPage = 0;
		}
		$rowspage = Plugin::getSetting('rowspage', 'tagger');

		$start = $CurPage * $rowspage;

		$totalrecords = count($totalTags);

		$lastpage = ceil($totalrecords / $rowspage);
		if($totalrecords <= $rowspage) { $lastpage = 0; } else { $lastpage = abs($lastpage - 1); }

		/* Get data. */
		$tags = Tagger::findAll(array('offset' =>  $start,'limit' => $rowspage));

        $this->display('tagger/views/index', array(
            'tags' => $tags,
            'currentpage' => $CurPage,
            'lastpage' => $lastpage
        ));
    }

    public function add()
    {
        // check if trying to save
        if (get_request_method() == 'POST')
            return $this->_add();

        // check if user have already enter something
        $tag = Flash::get('post_data');

        if (empty($tag))
            $tag = new Tagger;

        $this->display('tagger/views/edit', array(
            'action'  => 'add',
            'tag' => $tag
        ));
    }

    public function _add()
    {
        $data = $_POST['tag'];
        Flash::set('post_data', (object) $data);

        $tag = new Tag($data);

        if ( ! $tag->save())
        {
            Flash::set('error', __('Tag has not been added. Name must be unique!'));
            redirect(get_url('plugin/tagger', 'add'));
        }
        else Flash::set('success', __('Tag has been added!'));

        // save and quit or save and continue editing?
        if (isset($_POST['commit']))
            redirect(get_url('plugin/tagger'));
        else
            redirect(get_url('plugin/tagger/edit/'.$tag->id));
    }

    function edit($id)
    {
        if ( ! $tag = Tagger::findById($id))
        {
            Flash::set('error', __('Tag not found!'));
            redirect(get_url('plugin/tagger'));
        }

        // check if trying to save
        if (get_request_method() == 'POST')
            return $this->_edit($id);

        $this->display('tagger/views/edit', array(
            'action'  => 'edit',
            'tag' => $tag
        ));
    }

    function _edit($id)
    {
        $data = $_POST['tag'];

        $data['id'] = $id;

        $tag = new Tagger($data);

        if ( ! $tag->save())
        {
            Flash::set('error', __('Tag :name has not been saved. Name must be unique!', array(':name'=>$tag->name)));
            redirect(get_url('plugin/tagger/edit/'.$id));
        }
        else Flash::set('success', __('Tag :name has been saved!', array(':name'=>$tag->name)));

        // save and quit or save and continue editing?
        if (isset($_POST['commit']))
            redirect(get_url('plugin/tagger'));
        else
            redirect(get_url('plugin/tagger/edit/'.$id));
    }

    function delete($id)
    {
        // find the user to delete
        if ($tag = Record::findByIdFrom('Tag', $id))
        {
            if ($tag->delete()){
                if(TaggerTag::deleteByTagId($id))
                    Flash::set('success', __('Tag :name has been deleted!', array(':name'=>$tag->name)));
            }
            else
                Flash::set('error', __('Tag :name has not been deleted!', array(':name'=>$tag->name)));
        }
        else Flash::set('error', __('Tag not found!'));

        redirect(get_url('plugin/tagger'));
    }

	function save() {
		$tag_type = mysql_escape_string($_POST['tag_type']);
        $case = mysql_escape_string($_POST['case']);
        $rowspage = mysql_escape_string($_POST['rowspage']);

        $settings = array('tag_type' => $tag_type,
                          'case' => $case,
                          'rowspage' => $rowspage
                         );

        $ret = Plugin::setAllSettings($settings, 'tagger');

        if ($ret)
            Flash::set('success', __('The settings have been updated.'));
        else
            Flash::set('error', 'An error has occured.');

        redirect(get_url('plugin/tagger/settings'));
	}

    /**
	 * Ends relationship between page and tag
	 *
	 * @since 1.1.0
	 *
	 * @param string $ids
	 */
    function endrelationship($ids)
    {
    	$id = explode('-', $ids);

    	$page_id = $id[0];
    	$tag_id = $id[1];

    	if ($page = Record::findByIdFrom('Page', $page_id))
        {
        	if($tag = Record::findByIdFrom('Tag', $tag_id)) {
		    	if(TaggerTag::deletePageTagRelationship($page_id, $tag_id)) {
		    		Flash::set('success', __('Page :page_name has been deleted from association with Tag :tag_name!', array(':page_name'=>$page->title, ':tag_name'=>$tag->name)));
		    	}
		    	else Flash::set('error', __('Nothing was deleted!'));
			}
		}
		else Flash::set('error', __('Page not found!'));

		redirect(get_url('plugin/tagger'));
    }

	/**
	 * Settings for Tagger to change specific features
	 *
	 * @since 1.1.0
	 *
	 */
	function settings() {
        $tmp = Plugin::getAllSettings('tagger');
        $settings = array('tag_type' => $tmp['tag_type'],
                          'case' => $tmp['case'],
                          'rowspage' => $tmp['rowspage']
                         );
        $this->display('tagger/views/settings', $settings);
    }

    /**
	 * Documentation for Tagger
	 *
	 * @since 1.0.0
	 */
	public function documentation()
    {
        $this->display('tagger/views/documentation');
    }
} // end TaggerController class
