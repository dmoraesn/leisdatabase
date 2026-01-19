<?php $__env->startComponent($typeForm, get_defined_vars()); ?>
    <button type="button"
            <?php echo e($attributes); ?>

            data-controller="modal-toggle"
            data-action="click->modal-toggle#targetModal"
            data-modal-toggle-open-value="<?php echo e(var_export($open)); ?>"
            data-modal-toggle-title-value="<?php echo e($modalTitle ?? $title ??  ''); ?>"
            data-modal-toggle-key-value="<?php echo e($modal ?? ''); ?>"
            data-modal-toggle-action-value="<?php echo e($action); ?>"

            <?php if(!empty($parameters)): ?>
                data-modal-toggle-parameters-value='<?php echo json_encode($parameters, 15, 512) ?>'
            <?php endif; ?>
    >

        <?php if(isset($icon)): ?>
            <?php if (isset($component)) { $__componentOriginal385240e1db507cd70f0facab99c4d015 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal385240e1db507cd70f0facab99c4d015 = $attributes; } ?>
<?php $component = Orchid\Icons\IconComponent::resolve(['path' => $icon,'class' => 'overflow-visible'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
        <?php endif; ?>

        <?php echo e($name ?? ''); ?>

    </button>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/actions/modal.blade.php ENDPATH**/ ?>