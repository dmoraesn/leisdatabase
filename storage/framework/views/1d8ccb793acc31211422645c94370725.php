<?php $__currentLoopData = $generate(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crumbs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($crumbs->url() && !$loop->last): ?>
        <li <?php echo e($attributes->merge(['class' => $class])); ?>>
            <a href="<?php echo e($crumbs->url()); ?>">
                <?php echo e($crumbs->title()); ?>

            </a>
        </li>
    <?php else: ?>
        <li <?php echo e($attributes->merge(['class' => $class. ' '. $active])); ?>>
            <?php echo e($crumbs->title()); ?>

        </li>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH D:\leisdatabase.com\vendor\tabuna\breadcrumbs\src/../views/breadcrumbs.blade.php ENDPATH**/ ?>