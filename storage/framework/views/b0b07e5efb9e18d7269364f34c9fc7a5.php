<div class="form-group mb-0">

    <?php if(isset($title)): ?>
        <label for="<?php echo e($id); ?>" class="form-label mb-0">
            <?php echo e($title); ?>

        </label>
    <?php endif; ?>

    <?php echo e($slot); ?>


    <?php
            // Backport for consistent error handling behavior between Laravel 10 and 11.
            // This implementation will be modified in a future major version.

            // Retrieve all errors from the $errors object and convert them into a collection
            $allErrors = collect($errors->all());

            // Check if there is a 'default' error key in the collection of errors
            if ($allErrors->has('default')) {
                // If a 'default' error exists, assign it to the $errors variable
                $errors = $allErrors->get('default');
            }
    ?>

    <?php if($errors->has($oldName)): ?>
        <div class="invalid-feedback d-block">
            <small><?php echo e($errors->first($oldName)); ?></small>
        </div>
    <?php elseif(isset($help)): ?>
        <small class="form-text text-muted"><?php echo $help; ?></small>
    <?php endif; ?>
</div>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/partials/fields/clear.blade.php ENDPATH**/ ?>