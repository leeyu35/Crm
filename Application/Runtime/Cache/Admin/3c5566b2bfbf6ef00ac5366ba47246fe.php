<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo U('loginrn');?>">
  <p>
    <label for="textfield">用户名:</label>
    <input type="text" name="users" id="textfield">
    <label for="password"><br>
      密码:</label>
    <input type="password" name="password" id="password">
  </p>
  <p>
    <input type="submit" name="submit" id="submit" value="提交">
  </p>
</form>
</body>
</html>