<div class="profile-container d-flex align-items-stretch p-3 rounded lh-sm position-relative overflow-hidden">

    <a href="<?php echo e(route(config('platform.profile', 'platform.profile'))); ?>" class="col-10 d-flex align-items-center gap-3">
        <?php if($image = Auth::user()->presenter()->image()): ?>
            <img src="<?php echo e($image); ?>"  alt="<?php echo e(Auth::user()->presenter()->title()); ?>" class="thumb-sm avatar b" type="image/*">
        <?php endif; ?>

        <small class="d-flex flex-column lh-1 col-9">
            <span class="text-ellipsis text-white"><?php echo e(Auth::user()->presenter()->title()); ?></span>
            <span class="text-ellipsis text-muted"><?php echo e(Auth::user()->presenter()->subTitle()); ?></span>
        </small>
    </a>

    <?php if (isset($component)) { $__componentOriginalfa481b5ce2c06eca6d8a59f198f623c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfa481b5ce2c06eca6d8a59f198f623c3 = $attributes; } ?>
<?php $component = Orchid\Platform\Components\Notification::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('orchid-notification'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Orchid\Platform\Components\Notification::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfa481b5ce2c06eca6d8a59f198f623c3)): ?>
<?php $attributes = $__attributesOriginalfa481b5ce2c06eca6d8a59f198f623c3; ?>
<?php unset($__attributesOriginalfa481b5ce2c06eca6d8a59f198f623c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfa481b5ce2c06eca6d8a59f198f623c3)): ?>
<?php $component = $__componentOriginalfa481b5ce2c06eca6d8a59f198f623c3; ?>
<?php unset($__componentOriginalfa481b5ce2c06eca6d8a59f198f623c3); ?>
<?php endif; ?>

</div>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/partials/profile.blade.php ENDPATH**/ ?>