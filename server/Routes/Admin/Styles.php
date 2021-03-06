<?php

namespace Routes\Admin;

class Styles extends \Core\ControllerAdmin
{
	public function index($groupId = NULL)
	{
		$where = array();
		$groupId && $where['style_group_id'] = intval($groupId);
		$mdlStyle = new \Mdl\Style();
		$mdlStyleGroup = new \Mdl\StyleGroup();
		$list = $mdlStyle->all($where);
		$page = new \Lib\Page();
		$page->header(array('title' => 'Styles', 'menuItem' => 'Styles'))->body('Admin/Styles/index', array(
			'list' => $list ?: array(),
			'types' => $mdlStyle->types() ?: array(),
			'styleGroups' => $mdlStyleGroup->all() ?: array(),
			'groupId' => $groupId,
			'base_url_segment_add' => \Helpers\Url::getBaseUrl() . '/admin/styles/add/',
			'base_url_segment_change' => \Helpers\Url::getBaseUrl() . '/admin/styles/change/',
			'base_url_segment_delete' => \Helpers\Url::getBaseUrl() . '/admin/styles/delete/',
		))->footer()->render();
	}
	
	public function add()
	{
		$mdlStyle = new \Mdl\Style();
		$data = array();
		$data['title'] = array_key_exists('title', $_POST) && preg_match("/^[a-zA-Z\s0-9\-]+$/", $_POST['title']) ? $_POST['title'] : NULL;
		$data['type'] = array_key_exists('type', $_POST) && is_array($_POST['type']) && $mdlStyle->hasTypesMatch($_POST['type']) ? $_POST['type'] : NULL;
		$data['name'] = array_key_exists('title', $_POST) && preg_match("/^[a-zA-Z\s0-9\-]+$/", $_POST['name']) ? $_POST['name'] : NULL;
		$data['style_group_id'] = array_key_exists('style_group_id', $_POST) ? (int)$_POST['style_group_id'] : 0;
		
		$page = new \Lib\Page();
		
		$errors = FALSE;
		if (!$data['title']) {
			$errors = TRUE;
			$page->addError('Field is invalid or not properly filled: <b>' . 'title' . '</b>(a-z, spaces, dash, numbers)');
		}
		if (!$data['type']) {
			$errors = TRUE;
			$page->addError('Invalid types passed');
		}
		if (!$data['title']) {
			$errors = TRUE;
			$page->addError('Field is invalid or not properly filled: <b>' . 'name' . '</b>(a-z, spaces, dash, numbers)');
		}
		
		if ($errors) {
			\Helpers\Url::redirectBack();
			return;
		}
		
		$mdlStyle->insert($data);
		$page->addSuccess('Record added');
		\Helpers\Url::redirectBack();
		return;
	}
	
	public function change($id)
	{
		$id = intval($id);
		$page = new \Lib\Page();
		$mdlStyle = new \Mdl\Style();
		$record = $mdlStyle->one($id);
		if (!$record){
			$page->addError('Invalid record - no such exists');
			\Helpers\Url::redirectBack();
			return;
		}
		
		$data = array();
		$data['title'] = array_key_exists('title', $_POST) && preg_match("/^[a-zA-Z\s0-9\-]+$/", $_POST['title']) ? $_POST['title'] : NULL;
		$data['type'] = array_key_exists('type', $_POST) && is_array($_POST['type']) && $mdlStyle->hasTypesMatch($_POST['type']) ? $_POST['type'] : NULL;
		$data['name'] = array_key_exists('name', $_POST) && preg_match("/^[a-zA-Z\s0-9\-]+$/", $_POST['name']) ? $_POST['name'] : NULL;
		$data['style_group_id'] = array_key_exists('style_group_id', $_POST) ? (int)$_POST['style_group_id'] : 0;
		
		$page = new \Lib\Page();
		
		$errors = FALSE;
		if (!$data['title']) {
			$errors = TRUE;
			$page->addError('Field is invalid or not properly filled: <b>' . 'title' . '</b>(a-z, spaces, dash, numbers)');
		}
		if (!$data['type']) {
			$errors = TRUE;
			$page->addError('Invalid type passed');
		}
		if (!$data['name']) {
			$errors = TRUE;
			$page->addError('Field is invalid or not properly filled: <b>' . 'name' . '</b>(a-z, spaces, dash, numbers)');
		}
		
		if ($errors) {
			\Helpers\Url::redirectBack();
			return;
		}
		
		$mdlStyle->update($id, $data);
		$page->addSuccess('Record changed');
		\Helpers\Url::redirectBack();
		return;
	}
	
	public function delete($id)
	{
		$id = intval($id);
		$model = new \Mdl\Style();
		$model->delete($id);
		
		$page = new \Lib\Page();
		$page->addSuccess('Record successfully deleted');
		\Helpers\Url::redirectBack();
		return;
	}
}