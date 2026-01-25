<?php $__env->startComponent($typeForm, get_defined_vars()); ?>
    <div
        data-controller="upload"
        data-upload-storage="<?php echo e($storage ?? config('platform.attachment.disk', 'public')); ?>"
        data-upload-name="<?php echo e($name); ?>"
        data-upload-id="dropzone-<?php echo e($id); ?>"
        data-upload-data='<?php echo json_encode($value, 15, 512) ?>'
        data-upload-groups="<?php echo e($attributes['groups'] ?? ''); ?>"
        data-upload-multiple="<?php echo e($attributes['multiple']); ?>"
        data-upload-parallel-uploads="<?php echo e($parallelUploads); ?>"
        data-upload-max-file-size="<?php echo e($maxFileSize); ?>"
        data-upload-max-files="<?php echo e($maxFiles); ?>"
        data-upload-timeout="<?php echo e($timeOut); ?>"
        data-upload-accepted-files="<?php echo e($acceptedFiles); ?>"
        data-upload-resize-quality="<?php echo e($resizeQuality); ?>"
        data-upload-resize-width="<?php echo e($resizeWidth); ?>"
        data-upload-is-media-library="<?php echo e($media); ?>"
        data-upload-close-on-add="<?php echo e($closeOnAdd); ?>"
        data-upload-resize-height="<?php echo e($resizeHeight); ?>"
        data-upload-path="<?php echo e($attributes['path'] ?? ''); ?>"
    >
        <div id="dropzone-<?php echo e($id); ?>" class="dropzone-wrapper">
            <div class="fallback">
                <input type="file" value="" multiple/>
            </div>
            <div class="visual-dropzone sortable-dropzone dropzone-previews">
                <div class="dz-message dz-preview dz-processing dz-image-preview">
                    <div class="bg-light d-flex justify-content-center align-items-center border r-2x"
                         style="min-height: 112px;">
                        <div class="px-2 py-4">
                            <?php if (isset($component)) { $__componentOriginal385240e1db507cd70f0facab99c4d015 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal385240e1db507cd70f0facab99c4d015 = $attributes; } ?>
<?php $component = Orchid\Icons\IconComponent::resolve(['path' => 'bs.cloud-arrow-up','class' => 'h3'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
                            <small class="text-muted d-block mt-1"><?php echo e(__('Upload file')); ?></small>
                        </div>
                    </div>
                </div>

                <?php if($media): ?>
                    <div class="dz-message dz-preview dz-processing dz-image-preview"
                         data-action="click->upload#openMedia">
                        <div class="bg-light d-flex justify-content-center align-items-center border r-2x"
                             style="min-height: 112px;">
                            <div class="px-2 py-4">
                                <?php if (isset($component)) { $__componentOriginal385240e1db507cd70f0facab99c4d015 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal385240e1db507cd70f0facab99c4d015 = $attributes; } ?>
<?php $component = Orchid\Icons\IconComponent::resolve(['path' => 'bs.collection','class' => 'h3'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

                                <small class="text-muted d-block mt-1"><?php echo e(__('Media catalog')); ?></small>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="attachment modal fade center-scale" tabindex="-1" role="dialog" aria-hidden="false">
                <div class="modal-dialog modal-fullscreen-md-down">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title text-body-emphasis fw-light">
                                <?php echo e(__('File Information')); ?>

                                <small class="text-muted d-block"><?php echo e(__('Information to display')); ?></small>
                            </h4>

                            <button type="button" class="btn-close" title="Close" data-bs-dismiss="modal"
                                    aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body p-4">
                            <div class="form-group">
                                <label><?php echo e(__('System name')); ?></label>
                                <input type="text" class="form-control" data-upload-target="name" readonly
                                       maxlength="255">
                            </div>
                            <div class="form-group">
                                <label><?php echo e(__('Display name')); ?></label>
                                <input type="text" class="form-control" data-upload-target="original"
                                       maxlength="255" placeholder="<?php echo e(__('Display name')); ?>">
                            </div>
                            <div class="form-group">
                                <label><?php echo e(__('Alternative text')); ?></label>
                                <input type="text" class="form-control" data-upload-target="alt"
                                       maxlength="255" placeholder="<?php echo e(__('Alternative text')); ?>">
                            </div>
                            <div class="form-group">
                                <label><?php echo e(__('Description')); ?></label>
                                <textarea class="form-control no-resize"
                                          data-upload-target="description"
                                          placeholder="<?php echo e(__('Description')); ?>"
                                          maxlength="255"
                                          rows="3"></textarea>
                            </div>


                            <?php if($visibility === 'public'): ?>
                                <div class="form-group">
                                    <a href="#" data-action="click->upload#openLink">
                                        <small>
                                            <?php if (isset($component)) { $__componentOriginal385240e1db507cd70f0facab99c4d015 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal385240e1db507cd70f0facab99c4d015 = $attributes; } ?>
<?php $component = Orchid\Icons\IconComponent::resolve(['path' => 'bs.share','class' => 'me-2'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

                                            <?php echo e(__('Link to file')); ?>

                                        </small>
                                    </a>
                                </div>
                            <?php endif; ?>


                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    data-bs-dismiss="modal"
                                    class="btn btn-link">
                                    <span>
                                        <?php echo e(__('Close')); ?>

                                    </span>
                            </button>
                            <button type="button" data-action="click->upload#save" class="btn btn-default">
                                <?php echo e(__('Apply')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <?php if($media): ?>
                <div class="media modal fade enter-scale disable-scroll" tabindex="-1" role="dialog"
                     aria-hidden="false">
                    <div class="modal-dialog modal-dialog-scrollable modal-fullscreen-md-down slide-up">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title text-body-emphasis fw-light">
                                    <?php echo e(__('Media Library')); ?>

                                    <small class="text-muted d-block"><?php echo e(__('Previously uploaded files')); ?></small>
                                </h4>
                                <button type="button" class="btn-close" title="Close" data-bs-dismiss="modal"
                                        aria-label="Close">
                                </button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row justify-content-center">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><?php echo e(__('Search file')); ?></label>
                                            <input type="search"
                                                   data-upload-target="search"
                                                   data-action="keydown->upload#resetPage keydown->upload#loadMedia"
                                                   class="form-control"
                                                   placeholder="<?php echo e(__('Search...')); ?>"
                                            >
                                        </div>

                                        <div class="media-loader spinner-border" role="status">
                                            <span class="visually-hidden"><?php echo e(__('Loading...')); ?></span>
                                        </div>

                                        <div class="row media-results m-0"></div>

                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-link d-block w-100"
                                                    data-upload-target="loadmore"
                                                    data-action="click->upload#loadMore"><?php echo e(__('Load more')); ?></button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <template id="dropzone-<?php echo e($id); ?>-media">
                    <div class="col-4 col-sm-3 my-3 position-relative media-item">
                      <div data-action="click->upload#addFile" data-key="{index}">
                         <img src="{element.url}" class="rounded mw-100" style="height: 50px;width: 100%;object-fit: cover;">
                          <p class="text-ellipsis small text-muted mt-1 mb-0" title="{element.original_name}">{element.original_name}</p>
                        </div>
                      </div>
                </template>
            <?php endif; ?>


            <template id="dropzone-<?php echo e($id); ?>-remove-button">
                <a href="javascript:;" class="btn-remove">&times;</a>
            </template>

            <template id="dropzone-<?php echo e($id); ?>-edit-button">
                <a href="javascript:;" class="btn-edit">
                    <?php if (isset($component)) { $__componentOriginal385240e1db507cd70f0facab99c4d015 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal385240e1db507cd70f0facab99c4d015 = $attributes; } ?>
<?php $component = Orchid\Icons\IconComponent::resolve(['path' => 'bs.card-text','class' => 'mb-1'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
            </template>


        </div>
    </div>
<?php echo $__env->renderComponent(); ?>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/fields/upload.blade.php ENDPATH**/ ?>