<?php

namespace z_admin\controller;

use lib\base\BaseController;
use model\AgentCommodity;
use util\Upload;

/**
 * 代理人积分商城商品
 */
class AgentCommodityController extends BaseController {
	
	/**
	 * 查询全部
	 * 
	 * @return unknown
	 */
	public static function all($app, $data) {
		$page_num = input ( "page_num", 0 );
		$page_size = 30;
		$arr = AgentCommodity::page ( $page_num * $page_size, $page_size, "1=1", "id desc" );
		$arr ["page_num"] = $page_num;
		$arr ["page_size"] = $page_size;
		return view ( 'agent_commodity/all', $arr );
	}
	
	/**
	 * 添加
	 */
	public static function add($app, $data) {
		$gp = array ();
		$image = Upload::upload_file ( @$_FILES [0]);
		$gp ["name"] = input ( "name" );
		$gp ["point"] = input ( "point" );
		$gp ["image"] = $image;
		$gp ["des"] = input ( "des" );
		$gp ["buy_num"] = 0;
		$gp ["click"] = 0;
		$gp ["is_show"] = 1;
		$gp ["short_str"] = input ( "short_str" );
		$gp ["admin_id"] = session ( "admin" ) ["id"];
		AgentCommodity::add ( $gp );
		alert ( "添加成功!" );
		return R ( "agent_commodity/all" );
	}
	
	/**
	 * 修改之前
	 */
	public static function updateInfo($app, $data) {
		$id = input ( "id" );
		$arr ["agent_commodity"] = AgentCommodity::find ( $id );
		return view ( 'agent_commodity/update', $arr );
	}
	
	/**
	 * 修改
	 */
	public static function update($app, $data) {
		$id = input ( "id" );
		
		$gp = AgentCommodity::find ( $id );
		if(count($_FILES)>0 && $_FILES[0]["file_name"]!=""){
			$image = Upload::upload_file ( @$_FILES [0]);
			$gp ["image"] = $image;
		}
		
		$gp ["name"] = input ( "name" );
		$gp ["point"] = input ( "point" );
		$gp ["des"] = input ( "des" );
		$gp ["is_show"] = 1;
		$gp ["short_str"] = input ( "short_str" );
		$gp ["admin_id"] = session ( "admin" ) ["id"];
		AgentCommodity::update ( $gp, $id );
		
		// 修改成功
		alert ( "修改成功！" );
		close_layer ( "reload" );
	}
	
	/**
	 * 删除
	 */
	public static function delete($app, $data) {
		$id = input ( "id" );
		// 删除操作
		AgentCommodity::delete ( $id );
	}
}