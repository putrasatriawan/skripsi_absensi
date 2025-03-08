<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->data = array();
		$this->data['users'] = array();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth/login', 'refresh');
		} else {
			$this->data['users'] = $this->ion_auth->user()->row();
			$this->data['users_groups'] = $this->ion_auth->get_users_groups($this->data['users']->id)->row();
			// $this->data['roles'] = $this->ion_auth->roles()->row();

			// echo "<pre>";
			// print_r($this->data['users_groups']);
			// die;
			// foreach ($this->data['users_groups'] as $value) {
			// 	echo "<pre>";
			// 	print_r($value);
			// }
			// die;
		}

		$this->data['page'] = "";
		$this->data['parent_page_name'] = "";
		$this->data['page_id'] = "";
		$this->data['is_superadmin'] = false;
		$this->data['is_can_read'] = false;
		$this->data['is_can_approval'] = false;
		$this->data['is_can_create'] = false;
		$this->data['is_can_edit'] = false;
		$this->data['is_can_delete'] = false;
		$this->data['is_can_download'] = false;
		$this->data['is_can_disetujui'] = false;
		$this->data['is_can_diverifikasi'] = false;
		$this->data['is_can_ditinjau'] = false;
		$this->data['is_can_dievaluasi'] = false;
		$this->data['is_can_exportexcel'] = false;
		$this->data['is_can_review'] = false;
		$this->data['is_can_diajukan'] = false;
		$this->data['is_can_diperiksa'] = false;
		$this->data['is_can_diketahui'] = false;
		$this->data['is_can_disusun'] = false;
		$this->data['is_can_disiapkan'] = false;
		$this->data['is_can_disahkan'] = false;
		$this->load->model("menu_model");

		if ($this->ion_auth->in_group(1)) {
			$this->data['is_superadmin'] = true;
		}
		if (!$this->input->is_ajax_request()) {
			$this->data['menu'] = $this->loadMenu();
		} else {
			$this->data['page_id'] = $this->session->userdata('page_id');

			// var_dump($this->data['page_id']);die;
		}

		if ($this->data['is_superadmin']) {

			$this->data['is_can_read'] = true;
			$this->data['is_can_edit'] = true;
			$this->data['is_can_create'] = true;
			$this->data['is_can_delete'] = true;
			$this->data['is_can_approval'] = true;
			$this->data['is_can_download'] = true;
			$this->data['is_can_disetujui'] = true;
			$this->data['is_can_diverifikasi'] = true;
			$this->data['is_can_ditinjau'] = true;
			$this->data['is_can_dievaluasi'] = true;
			$this->data['is_can_exportexcel'] = true;
			$this->data['is_can_review'] = true;
			$this->data['is_can_diajukan'] = true;
			$this->data['is_can_diperiksa'] = true;
			$this->data['is_can_diketahui'] = true;
			$this->data['is_can_disusun'] = true;
			$this->data['is_can_disiapkan'] = true;
			$this->data['is_can_disahkan'] = true;
		} else {
			$this->load->model("privilleges_model");
			$dataPrivilleges = $this->privilleges_model->getOneBy(
				array(
					"menu_id" => $this->data['page_id'],
					"role_id" => $this->data['users_groups']->id
				)
			);
			$this->data['is_can_create'] = ($this->isInPrivilleges($dataPrivilleges, 1));
			$this->data['is_can_read'] = ($this->isInPrivilleges($dataPrivilleges, 2));
			$this->data['is_can_edit'] = ($this->isInPrivilleges($dataPrivilleges, 3));
			$this->data['is_can_delete'] = ($this->isInPrivilleges($dataPrivilleges, 4));
			$this->data['is_can_active'] = ($this->isInPrivilleges($dataPrivilleges, 5));
			$this->data['is_can_access'] = ($this->isInPrivilleges($dataPrivilleges, 6));
			$this->data['is_can_download'] = ($this->isInPrivilleges($dataPrivilleges, 7));
			$this->data['is_can_upload'] = ($this->isInPrivilleges($dataPrivilleges, 8));
			$this->data['is_can_approval'] = ($this->isInPrivilleges($dataPrivilleges, 9));
			$this->data['is_can_disetujui'] = ($this->isInPrivilleges($dataPrivilleges, 10));
			$this->data['is_can_diverifikasi'] = ($this->isInPrivilleges($dataPrivilleges, 11));
			$this->data['is_can_ditinjau'] = ($this->isInPrivilleges($dataPrivilleges, 12));
			$this->data['is_can_dievaluasi'] = ($this->isInPrivilleges($dataPrivilleges, 13));
			$this->data['is_can_exportexcel'] = ($this->isInPrivilleges($dataPrivilleges, 14));
			$this->data['is_can_review'] = ($this->isInPrivilleges($dataPrivilleges, 15));
			$this->data['is_can_diajukan'] = ($this->isInPrivilleges($dataPrivilleges, 16));
			$this->data['is_can_diperiksa'] = ($this->isInPrivilleges($dataPrivilleges, 17));
			$this->data['is_can_diketahui'] = ($this->isInPrivilleges($dataPrivilleges, 18));
			$this->data['is_can_disusun'] = ($this->isInPrivilleges($dataPrivilleges, 19));
			$this->data['is_can_disiapkan'] = ($this->isInPrivilleges($dataPrivilleges, 20));
			$this->data['is_can_disahkan'] = ($this->isInPrivilleges($dataPrivilleges, 21));

		}
	}
	private function isInPrivilleges($data, $id)
	{
		if (!empty($data)) {
			for ($i = 0; $i < count($data); $i++) {
				if (isset($data[$i]) && $data[$i]->function_id == $id) {
					return true;
				}
			}
		}

		return false;
	}
	private function createTree($parent, $menu, $parent_id, $path_active_name)
	{
		$html = "";
		if (isset($menu['parents'][$parent])) {
			if ($parent == 1) {
				$html .= '<ul class="vertical-nav-menu metismenu">';
			} else {
				$class = ($parent == $parent_id) ? '' : '';
				// $html .= '<ul class=' . $class . '>';
			}
			foreach ($menu['parents'][$parent] as $itemId) {
				$id = $menu['items'][$itemId]['id'];
				$url = $menu['items'][$itemId]['url'];
				$urlText = $menu['items'][$itemId]['url-text'];
				$icon = $menu['items'][$itemId]['icon'];
				$name = $menu['items'][$itemId]['name'];
				$description = $menu['items'][$itemId]['description'];

				if (!isset($menu['parents'][$itemId])) {
					$class = ($path_active_name == strtolower($urlText)) ? 'class="mm-active"' : 'class="mm"';
					$active = ($path_active_name == strtolower($urlText)) ? 'mm-active' : '';

					// Hapus atribut data-description
					$html .= '<li ' . $class . '>
						<a href="' . $url . '" class="' . $active . '">
							<i class="metismenu-icon ' . $icon . '"></i>
							' . $name . '
						</a>';
				} else {
					$class = ($id == $parent_id) ? 'show active' : '';
					$active = ($path_active_name == strtolower($urlText)) ? 'active' : '';
					$expanded = ($class == 'show active' || $active == 'active') ? 'true' : 'false';
					$is_expand = ($class == 'show active' || $active == 'active') ? "mm-active" : '';
					$submenuId = 'submenu-' . $id;

					// Pengecekan apakah deskripsi ada sebelum menampilkan elemen deskripsi
					$descriptionHtml = '';

					// Check if the current item is not expanded and description is not empty
					if ($expanded !== 'true' && !empty($description)) {
						$descriptionHtml = '<div class="menu-description" data-toggle="tooltip" data-placement="right" title="' . $description . '"></div>';
					}

					// Tambahkan deskripsi dengan class "menu-description" jika deskripsi ada
					$html .= '<li class="' . $is_expand . '" data-toggle="tooltip" data-placement="right" title="' . htmlspecialchars($description) . '">
					<a href="#" class="' . $active . '">
						<i class="metismenu-icon ' . $icon . '"></i> 
						' . $name . '
						<i class="metismenu-state-icon pe-7s-angle-down caret-left"></i> 
					</a>';


					// Include the description inside the app-main
					// $html .= '<div class="app-main">' . $descriptionHtml . '</div>';

					$html .= '<ul class="' . $class . '" id="' . $submenuId . '">';
					$html .= $this->createTree($itemId, $menu, $parent_id, $path_active_name);
					$html .= '</ul>';
					$html .= "</li>";

				}

			}
			// $html .= "</ul>";
		}
		return $html;
	}






	private function loadMenu()
	{
		if ($this->data['is_superadmin']) {
			$menus = $this->menu_model->getMenuSuperadmin();
		} else {
			$menus = $this->menu_model->getMenuPrivilleges(array("role_id" => $this->data['users_groups']->id));
			// echo"<pre>"; print_r($menus	);die;
			// 	foreach($menus as $value){
			// 	echo"<pre>"; print_r($value);

			// 	}
			// 	die;
		}
		if (empty($menus))
			return "";

		$new_menus = array();

		foreach ($menus as $key => $value) {
			$new_menus[$value->id] = array();
			$new_menus[$value->id]['id'] = $value->id;
			$new_menus[$value->id]['name'] = $value->name;
			$new_menus[$value->id]['url'] = base_url() . $value->url;
			$new_menus[$value->id]['url-text'] = $value->url;
			$new_menus[$value->id]['parent_id'] = $value->parent_id;
			$new_menus[$value->id]['icon'] = $value->icon;
			$new_menus[$value->id]['description'] = $value->description;
		}

		$tree_menu = array(
			'items' => array(),
			'parents' => array()
		);
		foreach ($new_menus as $a) {
			$tree_menu['items'][$a['id']] = $a;
			// Creates list of all items with children
			$tree_menu['parents'][$a['parent_id']][] = $a['id'];
		}
		$path_active_name = $this->uri->segment(1);
		if (!empty($this->uri->segment(2))) {
			if ($this->uri->segment(2) !== "create" && $this->uri->segment(2) !== "edit" && $this->uri->segment(2) !== "detail" && $this->uri->segment(2) !== "importExcel" && $this->uri->segment(2) !== "exportJson") {
				$path_active_name = $this->uri->segment(1) . "/" . $this->uri->segment(2);
			}
		}

		$data_parent = $this->menu_model->getParentIdBy(array("URL" => $path_active_name));
		$data_menu = $this->menu_model->getDetailMenuBy(array("URL" => $path_active_name));

		$parent_id = (!empty($data_parent)) ? $data_parent->parent_id : 0;
		if ($data_parent) {
			$parent = $this->menu_model->getParentIdBy(array("id" => $data_parent->parent_id));
		}

		$this->data['page_description'] = (!empty($data_menu)) ? $data_menu->description : "";
		$this->data['parent_page_name'] = (!empty($parent)) ? $parent->name : "";
		$this->data['page'] = (!empty($data_menu)) ? $data_menu->name : "";
		$this->data['page_id'] = (!empty($data_menu)) ? $data_menu->id : "";
		$this->session->set_userdata(array("page_id" => $this->data['page_id']));
		return $this->createTree(1, $tree_menu, $parent_id, $path_active_name);
	}
}

