	<title><?php echo h($this->page->title); ?> | FARS</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php echo $this->page->description; ?>" />
	<meta name="keywords" content="<?php echo $this->page->keywords; ?>" />
	<?php echo $this->css_combine(array(
		'@css/bootstrap.min.css',
		'@css/font-awesome.min.css',
		'@css/fars.css'
	)); ?>

	<link rel="shortcut icon" href="<?php echo site_url('favicon.ico'); ?>"> 