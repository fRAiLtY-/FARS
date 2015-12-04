<script src="//code.jquery.com/jquery-1.9.1.js"></script>

<?php echo $this->js_combine(array(
	'ls_core_jquery',
)); ?>

<?php echo $this->js_combine(array(
	'@js/bootstrap.min.js',
	'@js/owl.carousel.min.js',
	'@js/handlebars.js',
	'@js/typeahead.bundle.js',
	'@js/flexslider.min.js',
	'@js/filedrop.js',
	'@js/fars.js'
)); ?>