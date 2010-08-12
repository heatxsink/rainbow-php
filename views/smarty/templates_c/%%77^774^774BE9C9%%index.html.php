<?php /* Smarty version 2.6.26, created on 2010-08-12 00:47:34
         compiled from index.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Language" content="EN" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'blueprint_css.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<link rel="stylesheet" href="/static/css/rainbow.css" media="all" type="text/css" />
<script type="text/javascript" src="/static/js/combined.js"></script>

<title><?php echo $this->_tpl_vars['page_title']; ?>
</title>
</head>
<body>
<div id="content">
	<div id="nav">
		<a href="/">Home</a>
	</div>

	<h1><?php echo $this->_tpl_vars['page_title']; ?>
</h1>

	<div style="padding-top: 20px;">
		<img src="/static/images/rainbow-php.png" alt="rainbow-php" width="300" />
		<p><?php echo $this->_tpl_vars['content']; ?>
</p>
	</div>
</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'javascript_includes.html', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</body>
</html>