<fieldset class="row g-0 mb-3">
    <?php if(!empty($title) || !empty($description)): ?>
    <div class="col p-0 px-3">
        <legend class="text-body-emphasis px-2 mt-2">
            <?php echo e(__($title ?? '')); ?>


            <?php if(!empty($description)): ?>
            <p class="small text-muted mt-2 mb-0 text-balance">
                <?php echo __($description  ?? ''); ?>

            </p>
            <?php endif; ?>
        </legend>
    </div>
    <?php endif; ?>
    <div class="col-12 <?php echo e(!$vertical ? 'col-md-7' : ''); ?> shadow-sm h-100">

        <div class="bg-white d-flex flex-column layout-wrapper <?php echo e(empty($commandBar) ? 'rounded' : 'rounded-top'); ?>">
            <?php $__currentLoopData = $manyForms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $layouts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $__currentLoopData = $layouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $layout ?? ''; ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php if(empty(!$commandBar)): ?>
            <div class="bg-light px-4 py-3 d-flex justify-content-end rounded-bottom gap-2">
                <?php $__currentLoopData = $commandBar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $command): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <?php echo $command; ?>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</fieldset>

<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/layouts/block.blade.php ENDPATH**/ ?>