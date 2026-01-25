<?php if(!\Orchid\Platform\Dashboard::assetsAreCurrent()): ?>
    <div class="alert alert-warning rounded shadow-sm mb-3 p-4" data-turbo-temporary>
        <div class="d-flex align-items-center mb-2 text-body-emphasis">
            <?php if (isset($component)) { $__componentOriginal385240e1db507cd70f0facab99c4d015 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal385240e1db507cd70f0facab99c4d015 = $attributes; } ?>
<?php $component = Orchid\Icons\IconComponent::resolve(['path' => 'bs.exclamation-triangle','width' => '2em','height' => '2em','class' => 'me-2'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('orchid-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Orchid\Icons\IconComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal385240e1db507cd70f0facab99c4d015)): ?>
<?php $attributes = $__attributesOriginal385240e1db507cd70f0facab99c4d015; ?>
<?php unset($__attributesOriginal385240e1db507cd70f0facab99c4d015); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal385240e1db507cd70f0facab99c4d015)): ?>
<?php $component = $__componentOriginal385240e1db507cd70f0facab99c4d015; ?>
<?php unset($__componentOriginal385240e1db507cd70f0facab99c4d015); ?>
<?php endif; ?>
            <h4 class="mb-0 fw-light"><?php echo e(__("Complete the update.")); ?></h4>
        </div>

        <p><?php echo e(__("The published Orchid assets are not up-to-date with the installed version. To update, run:")); ?></p>
        <code class="user-select-all">php artisan orchid:publish</code>
    </div>
<?php endif; ?>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/partials/update-assets.blade.php ENDPATH**/ ?>