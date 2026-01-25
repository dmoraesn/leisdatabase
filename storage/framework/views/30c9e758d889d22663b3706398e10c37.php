<div
     data-controller="chart"
     data-chart-parent="#<?php echo e($slug); ?>"
     data-chart-labels="<?php echo e($labels); ?>"
     data-chart-datasets="<?php echo e($data); ?>"
     data-chart-type="<?php echo e($type); ?>"
     data-chart-height="<?php echo e($height); ?>"
     data-chart-colors="<?php echo e($colors); ?>"
     data-chart-max-slices="<?php echo e($maxSlices); ?>"
     data-chart-values-over-points="<?php echo e($valuesOverPoints); ?>"
     data-chart-axis-options="<?php echo e($axisOptions); ?>"
     data-chart-bar-options="<?php echo e($barOptions); ?>"
     data-chart-line-options="<?php echo e($lineOptions); ?>"
     data-chart-markers="<?php echo e($markers); ?>"
>
    <div class="bg-white rounded shadow-sm mb-3 pt-3">

        <div class="d-flex px-3 align-items-center">
            <legend class="text-body-emphasis px-2 mt-2 mb-0">
                <div class="d-flex align-items-center">
                    <small class="d-block"><?php echo e(__($title ?? '')); ?></small>

                    <?php if($export): ?>
                        <a href="#" class="ms-auto px-2 text-muted" data-action="chart#export" title="<?php echo e(__('Export')); ?>">
                            <?php if (isset($component)) { $__componentOriginal385240e1db507cd70f0facab99c4d015 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal385240e1db507cd70f0facab99c4d015 = $attributes; } ?>
<?php $component = Orchid\Icons\IconComponent::resolve(['path' => 'bs.cloud-arrow-down'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
                        </a>
                    <?php endif; ?>
                </div>

                <?php if(empty(!$description)): ?>
                    <p class="small text-muted mb-0 content-read text-balance">
                        <?php echo __($description  ?? ''); ?>

                    </p>
                <?php endif; ?>
            </legend>

        </div>

        <div class="position-relative w-100">
            <figure id="<?php echo e($slug); ?>" class="w-100 h-full m-0 p-0 d-flex"></figure>
        </div>
    </div>
</div>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/layouts/chart.blade.php ENDPATH**/ ?>