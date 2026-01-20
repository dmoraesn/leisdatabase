<fieldset class="mb-3">

    <?php if(empty(!$title)): ?>
        <div class="col p-0 px-3">
            <legend class="text-body-emphasis">
                <?php echo e($title); ?>

            </legend>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded shadow-sm p-4 py-4 d-flex flex-column gap-3">
        <?php echo $form ?? ''; ?>

    </div>
</fieldset>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/layouts/row.blade.php ENDPATH**/ ?>