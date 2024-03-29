<?php defined('BASEPATH') OR exit('No direct script access allowed');

// $config["menu_id"]               = 'id';
 $config["menu_label"]            = 'name';
 $config["menu_parent"]           = 'parent';
$config["menu_icon"] 			 = 'icon';
$config["menu_key"]              = 'slug';
$config["menu_order"]            = 'number';

$config["parent_tag_open"]       = '<li>';
$config["parent_tag_close"]      = '</li>';
$config["parent_anchor_tag"]     = '<a href="%s" class="menu-toggle">%s</a>';
$config["children_tag_open"]     = '<ul class="ml-menu">';
$config["children_tag_close"]    = '</ul>';	
$config['icon_position']		 = 'left'; // 'left' or 'right'
$config['menu_icons_list']		 = array();
// these for the future version
$config['icon_img_base_url']	 = ''; 