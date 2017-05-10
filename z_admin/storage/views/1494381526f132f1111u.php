<!DOCTYPE html>
<html>
<head><?php echo $this->display('public.head')?>
</head>
<body>
	<div class="layui-layout layui-layout-admin">
		<?php echo $this->display('public.left')?>
		<div class="layui-tab layui-tab-brief" lay-filter="demoTitle">
			<ul class="layui-tab-title site-demo-title">
				<li class="layui-this">积分商品列表</li>
				<li>添加积分商品</li>
			</ul>
			<div class="layui-body layui-tab-content site-demo site-demo-body">
				<div class="layui-tab-item layui-show">
					<div class="layui-main">
						<table class="layui-table">
							<colip>
							<col>
							<col width="430">
							<col>
							<col width="230">
							</colip>
							<thead>
								<tr>
									<th>名字</th>
									<th>短介绍</th>
									<th>最后操作人</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								<?php if (count((array)$this->vars['list'])) foreach((array)$this->vars['list'] as $this->vars['k']=>$this->vars['n']) {?>
								<tr>
									<td><?php echo $this->vars['n']['name']?></td>
									<td><?php echo $this->vars['n']['short_str']?></td>
									<td><?=find("Admin",$this->vars['n']['admin_id'])["name"]?></td>
									<td>
										<div class="layui-btn-group">
											<a class="layui-btn layui-btn-small"
												href="javascript:_update('<?php echo $this->vars['n']['id']?>');" title="修改"> <i
												class="layui-icon"></i>
											</a> <a href="javascript:_delete('<?php echo $this->vars['n']['id']?>');"
												class="layui-btn layui-btn-small" title="删除"> <i
												class="layui-icon"></i>
											</a>
										</div>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<div id="page"></div>

					</div>

				</div>

				<div class="layui-tab-item">
					<div class="layui-main">
						<fieldset class="layui-elem-field layui-field-title">
							<legend> 添加公告</legend>
						</fieldset>
						<form class="layui-form layui-form-pane"
							enctype="multipart/form-data" action="add" method="post">
							<div class="layui-form-item">
								<label class="layui-form-label">名字</label>
								<div class="layui-input-inline">
									<input type="text" name="name"  lay-verify="required"
										placeholder="请输入" autocomplete="off" class="layui-input">
								</div>
							</div>
							<div class="layui-form-item">
								<label class="layui-form-label">积分</label>
								<div class="layui-input-inline">
									<input type="text" name="point" lay-verify="required|number"
										value="" placeholder="多少积分才可以兑换" autocomplete="off"
										class="layui-input">
								</div>
							</div>
							<div class="layui-form-item">
								<label class="layui-form-label">是否上架</label>
								<div class="layui-input-inline">
									<select name="is_show">
										<option value="1">是</option>
										<option value="0">否</option>
									</select>
								</div>
							</div>
							<div class="layui-form-item">
								<label class="layui-form-label">图片</label>
								<div class="layui-input-inline">
									<input type="file" name="image" lay-verify="required" class="layui-upload-file" style="opacity: 1;height: 38px;font-size: 22px;">
								</div>
							</div>
							<div class="layui-form-item">
								<label class="layui-form-label">短介绍</label>
								<div class="layui-input-block">
									<input type="text" name="short_str" lay-verify="required"
										autocomplete="off" placeholder="短介绍" class="layui-input">
								</div>
							</div>
							<div class="layui-form-item" style="margin-bottom: 0px;">
								<label class="layui-form-label" style="width: 573px;">内容</label>
							</div>
							<div class="layui-form-item">
								<script id="editor" type="text/plain"
									style="width: 573px; height: 506px;"></script>
							</div>
							<div class="layui-form-item">
								<button class="layui-btn" lay-submit>添加</button>
							</div>
						</form>
					</div>

				</div>

			</div>

		</div>
	</div>
</body>
<?php echo $this->display('public.res')?>
<script>
	var laypage = layui.laypage;
	laypage({
	     cont: 'page',
	     pages: '<?php echo $this->vars['all_page_num']?>',
	     skin: '#1E9FFF',
	     groups: '<?php echo $this->vars['page_size']?>',
	     curr:(Number('<?php echo $this->vars['page_num']?>')+1),
	     jump: function(obj, first){
	      if(!first){
	      	layer.msg('第 '+ obj.curr +' 页');
	        go("all?page_num="+(obj.curr-1));
	        return;
	      }
	    }
	  });
</script>
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

