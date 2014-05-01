<?php
App::uses('ModelBehavior','Model');

class MenuLinksBehavior extends ModelBehavior {

	public function afterSave(Model $model,$created,$options=array()){
		$this->Link = ClassRegistry::init('Link');
        	if($model->name == 'Node'){
			if(!empty($model->data['Link'])){
				$model->data['Link']['link'] =  sprintf(
				    'plugin:%s/controller:%s/action:%s/type:%s/slug:%s',
				    'nodes',
				    'nodes',
				    'view',
				    $model->data['Node']['type'],
				    $model->data['Node']['slug'] 
				);

				if(isset($model->data['Link']['addlink']) && $model->data['Link']['addlink'] == 'true'){ 
					$this->Link->create();
					$this->Link->setTreeScope($link['Link']['menu_id']);

						if($this->Link->save($model->data)){
					}
				}
			}
		}
	}

	public function beforeDelete(Model $model,$cascade=true){
		$this->Link = ClassRegistry::init('Link');
		$this->Node = ClassRegistry::init('Node');
		$node = $this->Node->findById($model->id);
		if(isset($node['Node'])){
			$link_url = sprintf(
			    'plugin:%s/controller:%s/action:%s/type:%s/slug:%s',
			    'nodes',
			    'nodes',
			    'view',
			    $node['Node']['type'],
			    $node['Node']['slug']
			);
			$link = $this->Link->findByLink($link_url);
			if (isset($link['Link']['id'])) {
				$this->Link->setTreeScope($link['Link']['menu_id']);
				$this->Link->delete($id);
				Cache::clearGroup('menus','croogo_menus');
			}
		}
	}
}
