<!DOCTYPE html>
<html>
<head>
	<title>
		<?php echo SITE_TITLE; ?>:
		<?php echo $title; ?>
	</title>
	
</head>
<body>
	<div id="header" style='text-align:center;text-shadow:1px 1px 2px rgba(0,0,0,0.5);'>
	<?php echo SITE_TITLE; ?>
	</div>
	<div id="content" style="border:1px solid #ccc;padding:5px;margin:1%;">
		<?php
			echo $content;
		?>
	</div>
	<div id="footer" style="text-align:right;font-size:12px;">
		<br/>
		Proudly presented by Rapna
	</div>
	<!------------------- temp -->
	<?php 
		echo $this->element('sql_dump'); 
	?>
</body>
</html>
