<?php $__env->startComponent($typeForm, get_defined_vars()); ?>
    <div data-controller="input"
         data-input-mask="<?php echo e($mask ?? ''); ?>"
    >
        <input <?php echo e($attributes); ?>>
    </div>

    <?php if(empty(!$datalist)): ?>
        <datalist id="datalist-<?php echo e($name); ?>">
            <?php $__currentLoopData = $datalist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($item); ?>">
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </datalist>
    <?php endif; ?>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/fields/input.blade.php ENDPATH**/ ?>