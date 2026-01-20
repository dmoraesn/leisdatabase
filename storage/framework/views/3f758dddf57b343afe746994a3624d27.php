<div class="modal fade center-scale"
     tabindex="-1"
     data-controller="modal"
     role="dialog"
     id="search-modal"
>
    <div class="modal-dialog modal-fullscreen-md-down" role="document">
        <div class="modal-content">

            <div class="modal-header align-items-baseline gap-3">
                <h4 class="modal-title text-body-emphasis fw-light text-balance text-break"
                    data-modal-target="title">
                    <?php echo e(__('Search')); ?>

                </h4>
                <button type="button" class="btn-close" title="Close" data-bs-dismiss="modal"
                        aria-label="Close">
                </button>
            </div>

            <div class="modal-body p-4 py-4">
                <div data-controller="search-docs">
                    <div class="position-relative d-flex flex-column gap-3" data-controller="search">
                        <div class="input-icon">
                            <input
                                data-action="keyup->search#query blur->search#blur focus->search#focus"
                                data-search-target="query"
                                autocomplete="off"
                                autofocus
                                type="text"
                                value="<?php echo $__env->yieldContent('search'); ?>"
                                class="form-control"
                                placeholder="<?php echo e(__('What to search...')); ?>"
                            >
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
                        <div id="search-result"
                             class="d-flex flex-column gap-2 list-group">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/partials/search-modal.blade.php ENDPATH**/ ?>