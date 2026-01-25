<?php if (isset($component)) { $__componentOriginala871b0937f833a73f8d6540e05f15b48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala871b0937f833a73f8d6540e05f15b48 = $attributes; } ?>
<?php $component = Orchid\Platform\Components\Stream::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('orchid-stream'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Orchid\Platform\Components\Stream::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['target' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($templateSlug),'rule' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(\request()->routeIs('platform.async.listener'))]); ?>
    <div data-controller="listener"
         data-listener-watched-value="<?php echo e($targets); ?>"
         data-listener-url-value="<?php echo e($asyncRoute); ?>"
         data-listener-loading-class="pe-none cursor-wait"
         id="<?php echo e($templateSlug); ?>"
    >
        <?php $__currentLoopData = $manyForms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layouts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $__currentLoopData = $layouts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $layout): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $layout ?? ''; ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala871b0937f833a73f8d6540e05f15b48)): ?>
<?php $attributes = $__attributesOriginala871b0937f833a73f8d6540e05f15b48; ?>
<?php unset($__attributesOriginala871b0937f833a73f8d6540e05f15b48); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala871b0937f833a73f8d6540e05f15b48)): ?>
<?php $component = $__componentOriginala871b0937f833a73f8d6540e05f15b48; ?>
<?php unset($__componentOriginala871b0937f833a73f8d6540e05f15b48); ?>
<?php endif; ?>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/layouts/listener.blade.php ENDPATH**/ ?>