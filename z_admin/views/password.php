<!DOCTYPE html>
<html>
<head>{include file="public.head"}
</head>
<body>
	<div class="layui-layout layui-layout-admin" style="margin-top: 30px;">
		<div class="layui-main">
			<fieldset class="layui-elem-field layui-field-title">
				<legend>修改密码</legend>
			</fieldset>
			<form class="layui-form layui-form-pane"
				action="password_update" method="post">
				<div class="layui-form-item">
					<label class="layui-form-label">管理员</label>
					<div class="layui-input-inline">
						<input type="text"  lay-verify="required"
							readonly="readonly" value="<?=session('admin')['name']?>" autocomplete="off"
							class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">原密码</label>
					<div class="layui-input-inline">
						<input type="password" id="password_old" name="password_old" placeholder="请输入原密码"
							lay-verify="required"
							autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">新密码</label>
					<div class="layui-input-inline">
						<input type="password" name="password" id="password" placeholder="请输入新密码"
							lay-verify="required"
							autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">确认新密码</label>
					<div class="layui-input-inline">
						<input type="password" id="password2" placeholder="请输入原密码"
							lay-verify="required"
							autocomplete="off" class="layui-input">
					</div>
				</div>
				<div class="layui-form-item">
					<button class="layui-btn"  onclick="upd();" >提交</button>
				</div>
			</form>
		</div>
	</div>
</body>
{include file="public.res"}
<script type="text/javascript">
function upd(){
	var password_old = $("#password_old").val();
	if(password_old == "" || password_old == null){
		alert("原密码不能为空！");
		return;
	}
	var password = $("#password").val();
	var password2 = $("#password2").val();
	if(password == "" || password == null){
		alert("新密码不能为空！");
		return;
	}else{
		if(password!=password2){
			alert("两次密码不同！");
			return;
		}else{
			$.ajax({
			   type: "POST",
			   url: "password_update",
			   data: {password_old:password_old,password:password},
			   success: function(data){
				   if(data == 1){
						alert("修改成功");
						close_layer();
						return;
					}else if(data == 0){
						alert("原密码不正确！");
						close_layer();
						return;
					}
			   }
			});
		}
	}
	
	
}
</script>
</html>
