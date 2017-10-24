<!DOCTYPE html>
<html>
<head>{include file="public.head"}
</head>
<body>
	<div class="layui-layout layui-layout-admin" style="margin-top: 30px;">
		<div class="layui-main">
			<fieldset class="layui-elem-field layui-field-title">
				<legend> 修改积分商品列表 </legend>
			</fieldset>
			<form class="layui-form layui-form-pane"
				enctype="multipart/form-data"
				action="update?id={$agent_commodity['id']}" method="post">
				<div class="layui-form-item">
					<label class="layui-form-label">名字</label>
					<div class="layui-input-inline">
						<input type="text" name="name" lay-verify="required"
							value="{$agent_commodity['name']}" placeholder="请输入"
							autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">积分</label>
					<div class="layui-input-inline">
						<input type="text" name="point" lay-verify="required|number"
							value="{$agent_commodity['point']}" value=""
							placeholder="多少积分才可以兑换" autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">是否显示</label>
					<div class="layui-input-inline">
						<select name="is_show"> {if $agent_commodity['is_show']==1}
							<option value="1" selected="selected">是</option>
							<option value="0">否</option> {else}
							<option value="1">是</option>
							<option value="0" selected="selected">否</option> {/if}
						</select>
					</div>
				</div>
				<div class="layui-form-item" style="margin-bottom: 0px;">
					<label class="layui-form-label" style="width: 305px;">原图</label>
				</div>
				<div class="layui-form-item">
					<img alt="" src="{$HOME}{$agent_commodity['image']}"
						style="width: 305px;">
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">图片</label>
					<div class="layui-input-inline">
						<input type="file" name="image" class="layui-upload-file"
							style="opacity: 1; height: 38px; font-size: 22px;">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">短介绍</label>
					<div class="layui-input-block">
						<input type="text" name="short_str" lay-verify="required"
							value="{$agent_commodity['short_str']}" autocomplete="off"
							placeholder="短介绍" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item" style="margin-bottom: 0px;">
					<label class="layui-form-label" style="width: 573px;">内容</label>
				</div>
				<div class="layui-form-item">
					<script id="editor" type="text/plain"
						style="width: 573px; height: 506px;">{$agent_commodity['des']}</script>
				</div>
				<div class="layui-form-item">
					<button class="layui-btn" lay-submit>修改</button>
				</div>
			</form>
		</div>
	</div>
</body>
{include file="public.res"}
<script type="text/javascript">
var ue = UE.getEditor('editor', {
	//这里可以选择自己需要的工具按钮名称,此处仅选择如下七个
	//toolbars : [ [ 'autotypeset', 'forecolor', 'backcolor', 'customstyle',
	//		'paragraph', 'fontfamily', 'fontsize', '|', 'link', '|',
	//		'insertimage', 'emotion', 'scrawl' ] ],
	autoSyncData : true,//自动同步编辑器要提交的数据
	enableAutoSave : true,//启用自动保存
	saveInterval : 6000,
	textarea : 'des'
//自动保存间隔时间， 单位ms
});
//UE.getEditor('editor').getContent()
</script>
</html>
