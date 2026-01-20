<?php if(Dashboard::getSearch()->isNotEmpty()): ?>
    <div class="p-3">
        <div class="position-relative overflow-hidden">
            <div class="input-icon">
                <input class="form-control bg-dark text-white"
                       type="text"
                       readonly
                       placeholder="<?php echo e(__('What to search...')); ?>">
                <div class="input-icon-addon">
                    <?php if (isset($component)) { $__componentOriginal385240e1db507cd70f0facab99c4d015 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal385240e1db507cd70f0facab99c4d015 = $attributes; } ?>
<?php $component = Orchid\Icons\IconComponent::resolve(['path' => 'bs.search'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
                </div>
            </div>
            <a href="#"
               data-bs-toggle="modal"
               data-bs-target="#search-modal"
               class="stretched-link"></a>
        </div>
    </div>
<?php else: ?>
    <div class="divider my-2"></div>
<?php endif; ?>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/partials/search.blade.php ENDPATH**/ ?>