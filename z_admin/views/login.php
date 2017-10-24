<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>后台登陆入口</title>
<meta name="Copyright" content="Douco Design." />
<link href="{$HOME}admin/css/login.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$HOME}js/jquery.js"></script>
<script type="text/javascript" src="{$HOME}js/core/core.js"></script>
</head>
<body>
	<div id="login" style="margin-top: 231px;">
		<div class="dologo" style="height: 30px; text-align: center;">
			<div style="font-size: 28px; font-weight: 600; margin-top: 0px;">
				后台登陆入口</div>
		</div>
		<form action="login" method="post" id="form1">
			<ul>
				<li class="inpLi"><b>用户名：</b> <input name="phone" type="text"
					class="inpLogin" id="phone" /></li>
				<li class="inpLi"><b>密 码：</b> <input name="password" type="password"
					class="inpLogin" id="password" /></li>
				<li class="sub"><input type="button" name="button" id="sub"
					class="btn" value="登录" style="width: 120px;">
					&nbsp;&nbsp;&nbsp;&nbsp; <input type="reset" id="sub" class="btn"
					value="重置" style="width: 120px;"></li>
			</ul>
		</form>
		<script type="text/javascript">
			function s() {
				var phone = $("#phone").val();
				var password = $("#password").val();

				if (phone == '' || phone == null) {
					alert('用户名不能为空!');
					return false;
				}
				if (password == '' || password == null) {
					alert('密码不能为空!');
					return false;
				}
				$('#form1').submit();
				return;
			}
			$('#sub').click(function() {
				s();
			});
			$(document).ready(function() {
				$(document).keyup(function(evnet) {
					if (evnet.keyCode == '13') {
						s();
					}
				});

			});
		</script>
	</div>
</body>
</html>