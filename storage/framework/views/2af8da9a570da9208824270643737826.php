<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['target', 'action', 'push', 'rule' => \Orchid\Support\Facades\Dashboard::isPartialRequest()]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['target', 'action', 'push', 'rule' => \Orchid\Support\Facades\Dashboard::isPartialRequest()]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>


<?php if(filter_var($rule, FILTER_VALIDATE_BOOLEAN)): ?>
    <?php $__env->startFragment($target); ?>
        <turbo-stream target="<?php echo e($target); ?>" action="<?php echo e($action ?? 'replace'); ?>">
            <template>
                <?php echo $slot; ?>

            </template>
        </turbo-stream>
    <?php echo $__env->stopFragment(); ?>
<?php elseif(!empty($push)): ?>
    <?php $__env->startPush($push); ?>
        <?php echo $slot; ?>

    <?php $__env->stopPush(); ?>
<?php else: ?>
    <?php echo $slot; ?>

<?php endif; ?>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/components/stream.blade.php ENDPATH**/ ?>