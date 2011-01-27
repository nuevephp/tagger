<?php
/**
 * Tagger Plugin for Wolf CMS <http://thehub.silentworks.co.uk/plugins/frog-cms/tagger.html>
 * Alternate Mirror site <http://www.tbeckett.net/articles/plugins/tagger.xhtml>
 * Copyright (C) 2008 - 2011 Andrew Smith <a.smith@silentworks.co.uk>
 * Copyright (C) 2008 - 2011 Tyler Beckett <tyler@tbeckett.net>
 * 
 * Dual licensed under the MIT (license/mit-license.txt)
 * and GPL (license/gpl-license.txt) licenses.
 */

/**
 * Security measure for Wolf 0.7.0+
 */
include_once TAGGER_ROOT . "security.php";

/**
 * The Tagger plugin provides an interface to add, edit and delete tags.
 *
 * @package wolf
 * @subpackage plugin.tagger
 *
 * @author Andrew Smith <a.smith@silentworks.co.uk>
 * @author Tyler Beckett <tyler@tbeckett.net>
 * @version 1.1.0
 * @since Frog version 0.9.3
 * @copyright Andrew Smith, Tyler Beckett, 2008 - 2011
 */

// Include Tagger Models
include_once 'models/Tagger.php';
include_once 'models/TaggerTag.php';

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
		
		$CurPage = isset($page) ? $page : 0;
		
		$rowspage = Plugin::getSetting('rowspage', 'tagger');
		
		// New functions added in to make sorting tags easier on the backend.
		$sort_field = Plugin::getSetting('sort_field', 'tagger');
		$sort_order = Plugin::getSetting('sort_order', 'tagger');
		$order_by = Tagger::sortField($sort_field). ' ' .$sort_order;

		$start = $CurPage * $rowspage;

		$totalrecords = count($totalTags);

		$lastpage = ceil($totalrecords / $rowspage);
		
		$lastpage = $totalrecords <= $rowspage ? 0 : abs($lastpage - 1);

		/* Get data. */
		$tags = Tagger::findAll(array('offset' =>  $start,'limit' => $rowspage, 'order' => $order_by));

        $this->display('tagger/views/index', array(
            'tags' => $tags,
            'currentpage' => $CurPage,
            'lastpage' => $lastpage
        ));
    }

    public function add()
    {
        // check if trying to save
        if (get_request_method() == 'POST') return $this->_add();

        // check if user have already enter something
        $tag = Flash::get('post_data');

        if (empty($tag)) $tag = new Tagger;

        $this->display('tagger/views/edit', array(
            'action'  => 'add',
            'tag' => $tag
        ));
    }

    private function _add()
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
        if (isset($_POST['commit'])) redirect(get_url('plugin/tagger'));
        else redirect(get_url('plugin/tagger/edit/'.$tag->id));
    }

    public function edit($id)
    {
        if ( ! $tag = Tagger::findById($id))
        {
            Flash::set('error', __('Tag not found!'));
            redirect(get_url('plugin/tagger'));
        }

        // check if trying to save
        if (get_request_method() == 'POST') return $this->_edit($id);

        $this->display('tagger/views/edit', array(
            'action'  => 'edit',
            'tag' => $tag
        ));
    }

    private function _edit($id)
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
        if (isset($_POST['commit'])) redirect(get_url('plugin/tagger'));
        else redirect(get_url('plugin/tagger/edit/'.$id));
    }

    public function delete($id) {
        // find the user to delete
        if ($tag = Record::findByIdFrom('Tag', $id))
        {
            if ($tag->delete()){
                if(TaggerTag::deleteByTagId($id)) Flash::set('success', __('Tag :name has been deleted!', array(':name'=>$tag->name)));
            }
            else Flash::set('error', __('Tag :name has not been deleted!', array(':name'=>$tag->name)));
        }
        else Flash::set('error', __('Tag not found!'));

        redirect(get_url('plugin/tagger'));
    }

	public function save() {
		$db_dsn = explode(';', DB_DSN);
		$db_host = str_replace('host=','',$db_dsn[1]);
		
		if (mysql_connect($db_host, DB_USER, DB_PASS)) {
			$tag_type = mysql_real_escape_string($_POST['tag_type']);
			$case = mysql_real_escape_string($_POST['case']);
			$rowspage = mysql_real_escape_string($_POST['rowspage']);
			$sort_field = mysql_real_escape_string($_POST['sort_field']);
			$sort_order = mysql_real_escape_string($_POST['sort_order']);

			$settings = array('tag_type' => $tag_type,
							  'case' => $case,
							  'rowspage' => $rowspage,
							  'sort_field' => $sort_field,
							  'sort_order' => $sort_order
							 );

			$ret = Plugin::setAllSettings($settings, 'tagger');
		}
        if ($ret) Flash::set('success', __('The settings have been updated.'));
        else Flash::set('error', __('An error has occured.'));

        redirect(get_url('plugin/tagger/settings'));
	}

    /**
	 * Ends relationship between page and tag
	 *
	 * @since 1.1.0
	 *
	 * @param string $ids
	 */
    public function endrelationship($ids) {
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
	public function settings() {
        $tmp = Plugin::getAllSettings('tagger');
        $settings = array('tag_type' => $tmp['tag_type'],
                          'case' => $tmp['case'],
                          'rowspage' => $tmp['rowspage'],
                          'sort_field' => $tmp['sort_field'],
                          'sort_order' => $tmp['sort_order']
                         );
        $this->display('tagger/views/settings', $settings);
    }

	/**
	 * Purge & Recount for Tagger to update and purge unused tags
	 *
	 * @since 1.2.4
	 *
	 */
	public function purge() {
		$this->display('tagger/views/purge');
    }

	public function purged() {
		Tagger::purge_old();
		redirect(get_url('plugin/tagger'));
    }

    /**
	 * Documentation for Tagger
	 *
	 * @since 1.0.0
	 */
	public function documentation($page = 'index') {
		try {
			$var['content_for_documentation'] = new View('../../plugins/tagger/views/documentation/'. $page);
		} catch (Exception $e) {
			$var['content_for_documentation'] = new View('../../plugins/tagger/views/documentation/index');
		}
        $this->display('tagger/views/documentation/main', $var);
    }
} // end TaggerController class