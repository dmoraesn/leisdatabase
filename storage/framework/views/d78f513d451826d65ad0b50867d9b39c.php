<?php $__env->startSection('body'); ?>

    <div class="container-xl p-0 h-100">
        <div class="workspace workspace-limit pt-3 pt-md-4 mb-4 mb-md-0 d-flex flex-column h-100">
            <?php echo $__env->yieldContent('workspace'); ?>

            <?php echo $__env->first([config('platform.template.footer'), 'platform::footer'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('platform::app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/workspace/compact.blade.php ENDPATH**/ ?>