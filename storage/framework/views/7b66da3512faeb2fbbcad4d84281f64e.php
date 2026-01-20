<?php if (isset($component)) { $__componentOriginala871b0937f833a73f8d6540e05f15b48 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala871b0937f833a73f8d6540e05f15b48 = $attributes; } ?>
<?php $component = Orchid\Platform\Components\Stream::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('orchid-stream'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Orchid\Platform\Components\Stream::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['target' => 'screen-state']); ?>
    <input type="hidden" name="_state" id="screen-state" value="<?php echo e($state); ?>">
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
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/partials/state.blade.php ENDPATH**/ ?>