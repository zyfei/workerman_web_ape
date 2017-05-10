<script type="text/javascript" src="<?php echo $this->vars['HOME']?>js/jquery.js"></script>
<!-- 核心 -->
<script src="<?php echo $this->vars['HOME']?>layui/layui.js"></script>
<script src="<?php echo $this->vars['HOME']?>layui/lay/dest/layui.all.js"></script>
<script src="<?php echo $this->vars['HOME']?>js/core/core.js"></script>
<script type="text/javascript">
	function logout() {
		go("<?php echo $this->vars['MODULE_URL']?>index/logout");
	}
</script>
<!-- 引入ue -->
<script type="text/javascript" charset="utf-8"
	src="<?php echo $this->vars['HOME']?>ue/ueditor.config.js">
	
</script>
<script type="text/javascript" charset="utf-8"
	src="<?php echo $this->vars['HOME']?>ue/ueditor.all.min.js">
	
</script>
<script type="text/javascript" charset="utf-8"
	src="<?php echo $this->vars['HOME']?>ue/lang/zh-cn/zh-cn.js"></script>
<script>
	var form = layui.form();
	var layer = layui.layer;
	var laytpl = layui.laytpl;
	//自定义验证规则  
	form.verify();
	function _update(id) {
		go_self("updateInfo?id=" + id, "666px");
	}
	function _delete(id) {
		//询问框
		layer.confirm('确定删除？', {
			btn : [ '是', '否' ]
		//按钮
		}, function() {
			ajax("delete?id=" + id);
			location.reload();
		}, function() {
		});
	}
</script>