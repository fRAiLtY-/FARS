<?php $category = isset($product) ? $product->categories[0] : null; ?>

<?php if($category): ?>
	<?php foreach ($category->get_parents() as $parent): ?>
		<li><a href="<?php echo $parent->page_url('/category') ?>"><?php echo h($parent->name); ?></a></li>
	<?php endforeach; ?>
	<li><a href="<?php echo $category->page_url('/category'); ?>"><?php echo h($category->name); ?></a></li>
<?php endif; ?>
  
<?php if($product): ?>
	<li class="active"><a href="<?php echo $product->page_url('/product'); ?>"><?php echo h($product->name); ?></a></li>
<?php endif; ?>