<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html>
	
	<head>
		
		<title><?= $title; ?></title>
		
		<?php foreach($meta as $name => $value) { ?>
			
			<meta name="<?= $name; ?>" content="<?= $value; ?>" />
			
		<?php } ?>

		<?= get_assets(); ?>
	
	<body>
	
		<div id="container">
		
			<div id="header">
			
				<h1><?= $title; ?></h1>
			
			</div>
			
			<div id="content">
			
				<?= render_content(); ?>
			
			</div>
		
		</div>
	
	</body>
	
</html>