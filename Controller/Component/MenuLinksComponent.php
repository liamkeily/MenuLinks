<?php
App::uses('Component', 'Controller');

/**
 * MenuLinks Component
 *
 * @author Liam Keily
 * @package Croogo.MenuLinks.Controller.Component
 */

class MenuLinksComponent extends Component {
	public function initialize(Controller $controller){
		$controller->uses[] = 'Menus.Link';
		$controller->uses[] = 'Menus.Menu';
	
		$controller->Security->unlockedFields[] = 'addlink';	
		$controller->Security->unlockedFields[] = 'link';	

	}
	
	public function startup(Controller $controller){
	

		if($controller->params['controller'] == 'nodes' && ($controller->action == 'admin_edit' || $controller->action == 'admin_add')){

			// Saving Link
			if(!empty($controller->request->data['Link'])){
				$controller->Link->create();
				if($controller->Link->save($controller->request->data)){
				}
			}

		}

	}

	public function beforeRender(Controller $controller){
	
		if($controller->params['controller'] == 'nodes' && ($controller->action == 'admin_edit' || $controller->action == 'admin_add')){
			// The Link URL for current page
			$link_url = sprintf(
			    'plugin:%s/controller:%s/action:%s/type:%s/slug:%s',
			    'nodes',
			    'nodes',
			    'view',
			    $controller->Node->field('type'),
			    $controller->Node->field('slug')
			);

			// Find the corresponding link
			$currentlink = $controller->Link->find('first',array(
				'conditions'=>array(
					'Link.link'=>$link_url,	
				)
			));				
			if(!isset($currentlink['Link']['id'])) $currentlink['Link']['id'] = 0;
			if(!isset($currentlink['Link']['link'])) $currentlink['Link']['link'] = $link_url;
			if(!isset($currentlink['Link']['title'])) $currentlink['Link']['title'] = null;
			if(!isset($currentlink['Link']['visibility_roles'])) $currentlink['Link']['visibility_roles'] = null;
			if(!isset($currentlink['Link']['params'])) $currentlink['Link']['params'] = null;
			if(!isset($currentlink['Link']['description'])) $currentlink['Link']['description'] = null;
			if(!isset($currentlink['Link']['parent_id'])) $currentlink['Link']['parent_id'] = null;
			if(!isset($currentlink['Link']['status'])) $currentlink['Link']['status'] = true;

			// Link List Conditions
			$conditions = array();
			if(isset($currentlink['Link']['lft']) && isset($currentlink['Link']['lft'])){
				$conditions = array('OR'=>array(
					'Link.lft <'=>$currentlink['Link']['lft'],
					'Link.rght >'=>$currentlink['Link']['rght']
					));
			}

			// Generate Menu List and Link List
			$menus = $controller->Menu->find('all');
			$menulist = array();
			$linklist = array();
			foreach($menus as $menu){
				$menulist[$menu['Menu']['id']] = $menu['Menu']['title'];					
				if(!isset($currentlink['Link']['menu_id'])) $currentlink['Link']['menu_id'] = $menu['Menu']['id'];

				$linklist[$menu['Menu']['id']] = $controller->Link->generateTreeList(array_merge(array('Link.menu_id'=>$menu['Menu']['id']),$conditions));
			}
			
			Croogo::hookAdminTab('Nodes/admin_add','Menu Link','MenuLinks.MenuLinks');
			Croogo::hookAdminTab('Nodes/admin_edit','Menu Link','MenuLinks.MenuLinks');
		
			$controller->set(compact('menulist','linklist','currentlink'));
		}
	}

	public function shutdown(Controller $controller){
	}
	

}
