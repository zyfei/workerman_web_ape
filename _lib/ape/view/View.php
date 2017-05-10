<?php

namespace ape\view;

/**
 *
 * @author Administrator
 *        
 */
class View {
	/*
	 * The name of the directory where templates are located.
	 * @var string
	 */
	var $templatedir = "";
	
	/*
	 * The directory where compiled templates are located.
	 * @var string
	 */
	var $compiledir = "";
	
	/*
	 * where assigned template vars are kept
	 * @var array
	 */
	var $vars = array ();
	
	/**
	 * 存放视图，视图缓存对应表
	 *
	 * @var array
	 */
	var $tpl_storage = array ();
	/*
	 * compile a resource
	 * sets PHP tag to the compiled source
	 * @param string $tpl (template file)
	 */
	function parse($tpl) {
		global $MODULE_NAME; // 当前模块
		                     // load template file //
		$fp = @fopen ( $this->templatedir . $tpl, 'r' );
		$text = fread ( $fp, filesize ( $this->templatedir . $tpl ) );
		fclose ( $fp );
		// repalce template tag to PHP tag //
		$text = str_replace ( '{/if}', '<?php } ?>', $text );
		$text = str_replace ( '{/loop}', '<?php } ?>', $text );
		$text = str_replace ( '{foreachelse}', '<?php } else {?>', $text );
		$text = str_replace ( '{/foreach}', '<?php } ?>', $text );
		$text = str_replace ( '{else}', '<?php } else {?>', $text );
		$text = str_replace ( '{loopelse}', '<?php } else {?>', $text );
		// template pattern tags //
		$pattern = array (
				'/\$(\w*[a-zA-Z0-9_])/',
				'/\$this\-\>vars\[\'(\w*[a-zA-Z0-9_])\'\]+\.(\w*[a-zA-Z0-9])/',
				'/\{include file=(\"|\'|)(\w*[a-zA-Z0-9_\.][a-zA-Z]\w*)(\"|\'|)\}/',
				'/\{\$this\-\>vars(\[\'(\w*[a-zA-Z0-9_])\'\])(\[\'(\w*[a-zA-Z0-9_])\'\])?\}/',
				'/\{if (.*?)\}/',
				'/\{elseif (.*?)\}/',
				'/\{loop \$(.*) as (\w*[a-zA-Z0-9_])\}/',
				'/\{foreach \$(.*) (\w*[a-zA-Z0-9_])\=\>(\w*[a-zA-Z0-9_])\}/' 
		);
		// replacement PHP tags //
		$replacement = array (
				'$this->vars[\'\1\']',
				'$this->vars[\'\1\'][\'\2\']',
				'<?php echo $this->display(\'\2\')?>',
				'<?php echo \$this->vars\1\3?>',
				'<?php if(\1) {?>',
				'<?php } elseif(\1) {?>',
				'<?php if (count((array)\$\1)) foreach((array)\$\1 as \$this->vars[\'\2\']) {?>',
				'<?php if (count((array)\$\1)) foreach((array)\$\1 as \$this->vars[\'\2\']=>$this->vars[\'\3\']) {?>' 
		);
		// repalce template tags to PHP tags //
		$text = preg_replace ( $pattern, $replacement, $text );
		
		// create compile file //
		$compliefile = time () . random ( 10 ) . ".php";
		if ($fp = @fopen ( $this->compiledir . $compliefile, 'w' )) {
			fputs ( $fp, $text );
			fclose ( $fp );
		}
		// 删除旧的模板
		@unlink ( $this->compiledir . $this->tpl_storage [$MODULE_NAME . "/" . $tpl] );
		$this->tpl_storage [$MODULE_NAME . "/" . $tpl] = $compliefile;
	}
	
	/*
	 * assigns values to template variables
	 * @param array|string $k the template variable name(s)
	 * @param mixed $v the value to assign
	 */
	function assign($k, $v = null) {
		$this->vars [$k] = $v;
	}
	
	/*
	 * ste directory where templates are located
	 * @param string $str (path)
	 */
	function templateDir($path) {
		$this->templatedir = $this->pathCheck ( $path );
	}
	
	/*
	 * set where compiled templates are located
	 * @param string $str (path)
	 */
	function compileDir($path) {
		$this->compiledir = $this->pathCheck ( $path );
	}
	
	/*
	 * check the path last character
	 * @param string $str (path)
	 * @return string
	 */
	function pathCheck($str) {
		return (preg_match ( '/\/$/', $str )) ? $str : $str . '/';
	}
	
	/*
	 * executes & displays the template results
	 * @param string $tpl (template file)
	 */
	function display($tpl) {
		// 将.替换成/
		$tpl = str_replace ( '.', '/', $tpl );
		$tpl = $tpl . ".php";
		
		global $MODULE_NAME; // 当前模块
		if (! file_exists ( $this->templatedir . $tpl )) {
			return ('can not load template file : ' . $this->templatedir . $tpl);
		}
		// 判断是否存在这个模板缓存
		if (array_key_exists ( $MODULE_NAME . "/" . $tpl, $this->tpl_storage )) {
			// 获取模板模板缓存位置
			$compliefile = $this->compiledir . $this->tpl_storage [$MODULE_NAME . "/" . $tpl];
			if (! file_exists ( $compliefile ) || filemtime ( $this->templatedir . $tpl ) > filemtime ( $compliefile )) {
				$this->parse ( $tpl );
			}
		} else {
			// 如果不存在这个模板缓存
			$this->parse ( $tpl );
		}
		$compliefile = $this->compiledir . $this->tpl_storage [$MODULE_NAME . "/" . $tpl];
		foreach ($this->vars as $k=>$n){
			$$k = $n;
		}
		// 打开缓存区
		ob_start ();
		include $compliefile;
		$contents = ob_get_contents ();
		ob_end_clean ();
		
		return $contents;
	}
	
	/**
	 * 渲染
	 */
	public function view($tpl, &$vars = array()) {
		global $MODULE_NAME; // 当前模块
		global $MODULE_URL;
		$this->templateDir ( RUN_DIR . $MODULE_NAME . "/" . "views/" );
		$this->compileDir ( RUN_DIR . $MODULE_NAME . "/" . 'storage/views/' );
		
		$this->vars = $vars;
		$this->vars ["HOME"] = HOME;
		$this->vars ["MODULE_URL"] = $MODULE_URL;
		
		$ret = $this->display ( $tpl );
		$this->vars = null;
		return $ret;
	}
}
