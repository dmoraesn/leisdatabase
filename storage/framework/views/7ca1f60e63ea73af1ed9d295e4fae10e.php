<div class="dropdown d-inline-block" data-controller="filter" data-action="click->filter#onMenuClick">
    <button class="btn btn-sm btn-link dropdown-toggle p-0 me-1"
            type="button"
            data-bs-toggle="dropdown"
            aria-haspopup="true"
            data-bs-boundary="viewport"
            aria-expanded="false">
        <?php if (isset($component)) { $__componentOriginal385240e1db507cd70f0facab99c4d015 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal385240e1db507cd70f0facab99c4d015 = $attributes; } ?>
<?php $component = Orchid\Icons\IconComponent::resolve(['path' => 'bs.funnel'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
    </button>
    <div class="dropdown-menu dropdown-menu-left dropdown-menu-arrow py-0" x-placement="bottom-end">
        <div class="p-3">
            <?php echo $filter; ?>

        </div>

        <div class="bg-light p-3">
            <button type="submit" form="filters" class="btn btn-link btn-sm w-100 border">
                <span class="w-100 text-center"><?php echo e(__('Apply')); ?></span>
            </button>
        </div>
    </div>
</div>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/partials/layouts/filter.blade.php ENDPATH**/ ?>