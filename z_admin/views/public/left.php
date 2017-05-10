<div class="layui-header header header-demo">
	<div class="layui-main">
		<div
			style="color: white; font-size: 28px; padding-left: 5px; padding-top: 13px;">
			后台管理</div>
		<ul class="layui-nav" pc>
			<!--
			<li class="layui-nav-item layui-this"><a href="#">预留选项</a></li>
			-->
			<li class="layui-nav-item" pc><a href="javascript:;">
					欢迎:<?=session("admin")['name'];?>
				</a>
				<dl class="layui-nav-child">
					<dd>
						<a
							href="javascript:go_self('{$MODULE_URL}index/password','600px','600px');">
							修改密码 </a>
					</dd>
					<dd>
						<a href="javascript:logout();"> 退出 </a>
					</dd>
				</dl></li>
		</ul>
	</div>
</div>
<div class="layui-side layui-bg-black">
	<div class="layui-side-scroll">

		<ul class="layui-nav layui-nav-tree site-demo-nav">
			<li class="layui-nav-item layui-nav-itemed"><a class="javascript:;"
				href="javascript:;"> 功能 </a>
				<dl class="layui-nav-child">
					<dd>
						<a href="{$MODULE_URL}agent_commodity/all"> 商品列表 </a>
					</dd>
				</dl></li>
			<li class="layui-nav-item" style="height: 30px; text-align: center"></li>
		</ul>

	</div>
</div>