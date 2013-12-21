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
						if($this->Link->save($model->data)){
					}
				}
			}
		}
	}
}
