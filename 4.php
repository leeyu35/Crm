<?php
	$a="Hello World";
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?=$a?></title>
<style type="text/css">
.test_list{ color:#575757;}
.test_list span{ color:#F00;}
</style>
</head>

<body>
<h1><?php echo $a;?></h1>

<ul>
<?php 
	for($i=0;$i<=10;$i++)
	{
?>
		<li class="test_list">This is <span><?php echo $i;?></span></li>
<?php 
	}
?>
</ul>
</body>
</html>