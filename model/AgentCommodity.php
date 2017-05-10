<?php

namespace model;

use lib\base\DBBase;

/**
 * 测试商品类
 */
class AgentCommodity extends DBBase {
	public static $table = "a_agent_commodity";
	public static $softDelete = true;
	
}
