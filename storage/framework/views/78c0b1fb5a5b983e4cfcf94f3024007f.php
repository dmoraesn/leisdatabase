<?php $__env->startSection('title', '404'); ?>
<?php $__env->startSection('description', __("You requested a page that doesn't exist.")); ?>

<?php $__env->startSection('content'); ?>

    <div class="container py-md-4">
            <h1 class="h2 mb-3">
                <?php echo e(__("Sorry, we don't have anything to show you on this page")); ?>

            </h1>


            <p><?php echo e(__("This could be because:")); ?></p>
            <ul>
                <li><?php echo e(__("The item you're looking for has been deleted")); ?></li>
                <li><?php echo e(__("You don't have access to it")); ?></li>
                <li><?php echo e(__("You clicked a broken link")); ?></li>
            </ul>

            <p class="mb-0"><?php echo e(__("If you think you should have access to this page, ask the person who manages the project (or the account) to add you to it.")); ?></p>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('platform::dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/errors/404.blade.php ENDPATH**/ ?>