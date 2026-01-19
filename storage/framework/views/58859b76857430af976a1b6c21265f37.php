<?php $__env->startSection('title', (string) __($name)); ?>
<?php $__env->startSection('description', (string) __($description)); ?>
<?php $__env->startSection('controller', $controller); ?>

<?php $__env->startSection('navbar'); ?>
    <?php $__currentLoopData = $commandBar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $command): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li>
            <?php echo $command; ?>

        </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div id="modals-container">
        <?php echo $__env->yieldPushContent('modals-container'); ?>
    </div>

    <form id="post-form"
          class="mb-md-4 h-100"
          method="post"
          enctype="multipart/form-data"
          data-controller="form"
          data-form-need-prevents-form-abandonment-value="<?php echo e(var_export($needPreventsAbandonment)); ?>"
          data-form-failed-validation-message-value="<?php echo e($formValidateMessage); ?>"
          data-action="keypress->form#disableKey
                      turbo:before-fetch-request@document->form#confirmCancel
                      beforeunload@window->form#confirmCancel
                      change->form#changed
                      form#submit"
          novalidate
    >
        <?php echo $layouts; ?>

        <?php echo csrf_field(); ?>
        <?php echo $__env->make('platform::partials.confirm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </form>

    <div data-controller="filter">
        <form id="filters" autocomplete="off"
              data-action="filter#submit"
              data-form-need-prevents-form-abandonment-value="false"
        ></form>
    </div>

    <?php echo $__env->renderWhen(isset($state), 'platform::partials.state', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('platform::dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/layouts/base.blade.php ENDPATH**/ ?>