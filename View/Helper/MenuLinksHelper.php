<?php

App::uses('AppHelper','View/Helper');

class MenuLinksHelper extends AppHelper {

	var $helpers = array('Html','Menus');

/**
 * Show Menu by Alias
 *
 * @param string $menuAlias Menu alias
 * @param array $options (optional)
 * @return string
 */
	public function submenu($menuAlias, $options = array()) {
		$_options = array(
			'tag' => 'ul',
			'tagAttributes' => array(),
			'selected' => 'selected',
			'dropdown' => true,
			'dropdownClass' => 'sf-menu',
			'element' => 'MenuLinks.submenu',
		);
		$options = array_merge($_options, $options);

		if (!isset($this->_View->viewVars['menus_for_layout'][$menuAlias])) {
			return false;
		}
		$menu = $this->_View->viewVars['menus_for_layout'][$menuAlias];
		$output = $this->_View->element($options['element'], array(
			'menu' => $menu,
			'options' => $options,
		));
		return $output;
	}

/**
 * Nested Links
 *
 * @param array $links model output (threaded)
 * @param array $options (optional)
 * @param integer $depth depth level
 * @return string
 */
	public function submenuNestedLinks($links, $options = array(), $depth = 1) {
		$_options = array();
		$options = array_merge($_options, $options);

		$output = '';
		foreach ($links as $link) {
			$linkAttr = array(
				'id' => 'link-' . $link['Link']['id'],
				'rel' => $link['Link']['rel'],
				'target' => $link['Link']['target'],
				'title' => $link['Link']['description'],
				'class' => $link['Link']['class'],
			);

			if (isset($link['Params']['linkAttr'])) {
				$linkAttr = array_merge($linkAttr, $link['Params']['linkAttr']);
			}

			foreach ($linkAttr as $attrKey => $attrValue) {
				if ($attrValue == null) {
					unset($linkAttr[$attrKey]);
				}
			}

			// if link is in the format: controller:contacts/action:view
			if (strstr($link['Link']['link'], 'controller:')) {
				$link['Link']['link'] = $this->Menus->linkStringToArray($link['Link']['link']);
			}

			// Remove locale part before comparing links
			if (!empty($this->_View->request->params['locale'])) {
				$currentUrl = substr($this->_View->request->url, strlen($this->_View->request->params['locale'] . '/'));
			} else {
				$currentUrl = $this->_View->request->url;
			}

			$child_options = $options;
			if (Router::url($link['Link']['link']) == Router::url('/' . $currentUrl)) {
				if (!isset($linkAttr['class'])) {
					$linkAttr['class'] = '';
				}
				$linkAttr['class'] .= ' ' . $options['selected'];
				$child_options['parent_selected'] = true;
			}

			$linkOutput = '';

			if($options['parent_selected'] == true){
				$linkOutput = $this->Html->link($link['Link']['title'], $link['Link']['link'], $linkAttr);
			}

			if (isset($link['children']) && count($link['children']) > 0) {
				$linkOutput .= $this->submenuNestedLinks($link['children'], $child_options, $depth + 1);
			}

			if($options['parent_selected'] == true){
				$linkOutput = $this->Html->tag('li', $linkOutput);
			}

			$output .= $linkOutput;
		}

		if ($output != null) {
			$tagAttr = $options['tagAttributes'];

			$tagAttr['class'] = $options['dropdownClass'];

			if($options['parent_selected'] == true){
				$output = $this->Html->tag($options['tag'], $output, $tagAttr);
			}
		}

		return $output;
	}

}
