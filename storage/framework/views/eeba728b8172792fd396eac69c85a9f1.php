<th <?php if(empty(!$width)): ?> width="<?php echo e($width); ?>" <?php endif; ?> class="text-<?php echo e($align); ?>" data-column="<?php echo e($slug); ?>">
    <div class="d-inline-flex align-items-center">

        <?php echo $__env->renderWhen($filter !== null, "platform::partials.layouts.filter", ['filter' => $filter], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1])); ?>

        <?php if($sort): ?>
            <a href="<?php echo e($sortUrl); ?>"
               class="<?php if(!is_sort($column)): ?> text-muted <?php endif; ?>">
                <?php echo $title; ?>


                <?php if (isset($component)) { $__componentOriginal1d1976506f33d5d23fa37b3ec2628c63 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1d1976506f33d5d23fa37b3ec2628c63 = $attributes; } ?>
<?php $component = Orchid\Screen\Components\Popover::resolve(['content' => $popover] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('orchid-popover'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Orchid\Screen\Components\Popover::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1d1976506f33d5d23fa37b3ec2628c63)): ?>
<?php $attributes = $__attributesOriginal1d1976506f33d5d23fa37b3ec2628c63; ?>
<?php unset($__attributesOriginal1d1976506f33d5d23fa37b3ec2628c63); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1d1976506f33d5d23fa37b3ec2628c63)): ?>
<?php $component = $__componentOriginal1d1976506f33d5d23fa37b3ec2628c63; ?>
<?php unset($__componentOriginal1d1976506f33d5d23fa37b3ec2628c63); ?>
<?php endif; ?>

                <?php if(is_sort($column)): ?>
                    <?php $sortIcon = get_sort($column) === 'desc' ? 'bs.sort-down' : 'bs.sort-up' ?>
                    <?php if (isset($component)) { $__componentOriginal385240e1db507cd70f0facab99c4d015 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal385240e1db507cd70f0facab99c4d015 = $attributes; } ?>
<?php $component = Orchid\Icons\IconComponent::resolve(['path' => $sortIcon] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
            </a>
        <?php else: ?>
            <?php echo $title; ?>


            <?php if (isset($component)) { $__componentOriginal1d1976506f33d5d23fa37b3ec2628c63 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1d1976506f33d5d23fa37b3ec2628c63 = $attributes; } ?>
<?php $component = Orchid\Screen\Components\Popover::resolve(['content' => $popover] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('orchid-popover'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Orchid\Screen\Components\Popover::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1d1976506f33d5d23fa37b3ec2628c63)): ?>
<?php $attributes = $__attributesOriginal1d1976506f33d5d23fa37b3ec2628c63; ?>
<?php unset($__attributesOriginal1d1976506f33d5d23fa37b3ec2628c63); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1d1976506f33d5d23fa37b3ec2628c63)): ?>
<?php $component = $__componentOriginal1d1976506f33d5d23fa37b3ec2628c63; ?>
<?php unset($__componentOriginal1d1976506f33d5d23fa37b3ec2628c63); ?>
<?php endif; ?>
        <?php endif; ?>
    </div>

    <?php if($filterString): ?>
        <div data-controller="filter" class="mt-2">
            <a href="#"
               data-action="filter#clearFilter"
               data-filter="<?php echo e($column); ?>"
               class="badge bg-light border d-inline-flex align-items-center link-body-emphasis gap-1">
                <span><?php echo e($filterString); ?></span>
                <?php if (isset($component)) { $__componentOriginal385240e1db507cd70f0facab99c4d015 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal385240e1db507cd70f0facab99c4d015 = $attributes; } ?>
<?php $component = Orchid\Icons\IconComponent::resolve(['path' => 'bs.x-lg'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
        </div>
    <?php endif; ?>
</th>


<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/partials/layouts/th.blade.php ENDPATH**/ ?>