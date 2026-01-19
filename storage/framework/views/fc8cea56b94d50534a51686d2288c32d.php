<div class="d-flex flex-column grid d-md-grid form-group <?php echo e($align); ?>"
    style="<?php echo \Illuminate\Support\Arr::toCssStyles([
        '--bs-columns: '.count($group),
        'grid-template-columns: '. $widthColumns => $widthColumns !== null,
    ]) ?>">
    <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="<?php echo e($class); ?>

                    <?php echo e($loop->first && $itemToEnd ? 'ms-auto': ''); ?>

            ">
            <?php echo $field; ?>

        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/fields/group.blade.php ENDPATH**/ ?>