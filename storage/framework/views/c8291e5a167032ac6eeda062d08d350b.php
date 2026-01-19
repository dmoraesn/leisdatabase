<a href="<?php echo e($url); ?>" class="d-flex align-items-center gap-md-3">
    <?php if(empty(!$image)): ?>
    <span class="thumb-sm avatar d-none d-md-inline-block">
      <img src="<?php echo e($image); ?>" class="bg-light">
    </span>
    <?php endif; ?>
    <div class="text-balance">
        <p class="mb-0"><?php echo e($title); ?></p>
        <small class="text-muted"><?php echo e($subTitle); ?></small>
    </div>
</a>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/layouts/persona.blade.php ENDPATH**/ ?>