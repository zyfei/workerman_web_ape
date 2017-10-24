var curWwwPath = window.document.location.href;// 获取当前网址，如：
// http://localhost:8083/uimcardprj/share/meun.jsp
var newCurWwwPath = "";
for (var i = 0; i < curWwwPath.length; i++) {// 去掉#后面的东西
	if (curWwwPath.charAt(i) == "#") {
		curWwwPath = newCurWwwPath;
		break;
	}
	newCurWwwPath = newCurWwwPath + curWwwPath.charAt(i);
}
var pathName = window.document.location.pathname;// 获取主机地址之后的目录，如：
// uimcardprj/share/meun.jsp
var pos = curWwwPath.indexOf(pathName);
var localhostPaht = curWwwPath.substring(0, pos);// 获取主机地址，如：
// http://localhost:8083s
var localhostWithProject = localhostPaht + '/';

/**
 * 返回json数据
 */
function ajax(url, datas) {
	// var str =
	// $.ajax({url:url,type:'GET',async:false,cache:false}).responseText;
	return $.ajax({
		cache : true,
		async : false,
		url : url,
		type : "POST",
		data : datas
	}).responseText;
}

/**
 * 带提示的跳转
 * 
 * @param url
 * @param str
 * @param murl
 */
var D_en = 0;// 防止多次点击
function D(str, url) {
	if (D_en == 1) {
		// 请不要重复点击
		return false;
	}
	D_en = 1;
	if (!confirm(str)) {
		D_en = 0;
		return false;
	}
	go(url);
	D_en = 0;
	return;
}

/**
 * 上传图片
 * 
 * @param {Object}
 *            url
 * @param {Object}
 *            imageId
 * @param {Object}
 *            fun
 * @return {TypeName}
 */
function ajaxImage(url, imageId, fun) {
	$.ajaxFileUpload({
		url : url,// 用于文件上传的服务器端请求地址
		secureuri : false,// 一般设置为false
		fileElementId : imageId,// 文件上传空间的id属性
		dataType : 'text',// 返回值类型 一般设置为json
		success : function(data) // 服务器成功响应处理函数
		{
			fun(data);
		},
		error : function()// 服务器响应失败处理函数
		{
			alert("访问服务器异常，请重试");
		}
	});
}

/**
 * JS 获取页面传过来的参数值
 * 
 * @param {Object}
 *            sProp
 * @return {TypeName}
 */
function getParameter(sProp) {
	var re = new RegExp("[&,?]" + sProp + "=([^\\&]*)", "i");
	var a = re.exec(document.location.search);
	if (a == null)
		return "";
	return a[1];
}

/**
 * 跳转url路径
 * 
 * @param {Object}
 *            url
 */
function go(url) {
	window.location.href = url;
}

/**
 * 一秒后刷新页面
 */
function refresh() {
	window.setTimeout('reFun()', 1000)
}

function reFun() {
	location.reload();
}

function go_self(content , w , h) {
	if(w==null || w=="defined" || w==""){
		w = "80%";
	}
	if(h==null || h=="defined" || h==""){
		h = "80%";
	}
	// iframe层
	layer.open({
		type : 2,
		title : false,
		shadeClose : true,
		shade : 0.6,
		scrollbar : false,
		area : [ w, h ],
		content : content
	// iframe的url,
	});
}

function go_self2(content, title) {
	// iframe层
	layer.open({
		type : 2,
		title : false,
		shadeClose : true,
		shade : 0.6,
		offset : "44px",
		scrollbar : false,
		area : [ '68%', '90%' ],
		content : content
	// iframe的url,
	});
}

function go_help(content, title) {
	// iframe层
	layer.open({
		type : 2,
		title : false,
		shadeClose : true,
		shade : 0.6,
		offset : "14px",
		scrollbar : false,
		area : [ '60%', '95%' ],
		content : content
	// iframe的url,
	});
}

function close_layer(url) {
	var index = parent.layer.getFrameIndex(window.name);
	if (url == null || url == "") {
		parent.layer.close(index);
		return;
	}
	if (url = "reload") {
		parent.location.reload();
		parent.layer.close(index);
		return;
	}
	parent.location.href = url;
	parent.layer.close(index);
	return;
}

function msg(str) {
	layer.msg(str, {
		offset : 4
	});
}

$(".yanzheng_img").change(function() {
	file_size = this.files[0].size;
	var size = file_size / 1024;
	if (size > 10240 / 10 / 2) {
		alert("上传的图片大小不能超过500K！");
		var file = $(this);
		file.val("");
	}
});
