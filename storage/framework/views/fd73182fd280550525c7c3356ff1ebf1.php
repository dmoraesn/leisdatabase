<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>" data-controller="html-load" dir="<?php echo e(\Orchid\Support\Locale::currentDir()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
    <title>
        <?php echo $__env->yieldContent('title', config('app.name')); ?>
        <?php if (! empty(trim($__env->yieldContent('title')))): ?>
            - <?php echo e(config('app.name')); ?>

        <?php endif; ?>
    </title>
    <meta name="csrf_token" content="<?php echo e(csrf_token()); ?>" id="csrf_token">
    <meta name="auth" content="<?php echo e(Auth::check()); ?>" id="auth">
    <?php if(\Orchid\Support\Locale::isRtl()): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo e(mix('/css/orchid.rtl.css','vendor/orchid')); ?>"  data-turbo-track="reload" >
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href="<?php echo e(mix('/css/orchid.css','vendor/orchid')); ?>"  data-turbo-track="reload" >
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('head'); ?>

    <meta name="view-transition" content="same-origin">
    <meta name="turbo-root" content="<?php echo e(Dashboard::prefix()); ?>">
    <meta name="turbo-refresh-method" content="<?php echo e(config('platform.turbo.refresh-method', 'replace')); ?>">
    <meta name="turbo-refresh-scroll" content="<?php echo e(config('platform.turbo.refresh-scroll', 'reset')); ?>">
    <meta name="turbo-prefetch" content="<?php echo e(var_export(config('platform.turbo.prefetch', true))); ?>">
    <meta name="dashboard-prefix" content="<?php echo e(Dashboard::prefix()); ?>">

    <?php if(!config('platform.turbo.cache', false)): ?>
        <meta name="turbo-cache-control" content="no-cache">
    <?php endif; ?>

    <?php $__currentLoopData = collect(['/js/manifest.js', '/js/vendor.js', '/js/orchid.js']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <script src="<?php echo e(mix($key,'vendor/orchid')); ?>" data-turbo-track="reload" type="text/javascript"></script>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php $__currentLoopData = Dashboard::getResource('stylesheets'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stylesheet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <link rel="stylesheet" href="<?php echo e($stylesheet); ?>" data-turbo-track="reload">
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php echo $__env->yieldPushContent('stylesheets'); ?>

    <?php $__currentLoopData = Dashboard::getResource('scripts'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $scripts): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <script src="<?php echo e($scripts); ?>" defer type="text/javascript" data-turbo-track="reload"></script>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php if(!empty(config('platform.vite', []))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(config('platform.vite')); ?>
    <?php endif; ?>
</head>

<body class="<?php echo e(\Orchid\Support\Names::getPageNameClass()); ?>" data-controller="pull-to-refresh">

<div class="container-fluid" data-controller="<?php echo $__env->yieldContent('controller'); ?>" <?php echo $__env->yieldContent('controller-data'); ?>>

    <div class="row justify-content-center d-md-flex h-100">
        <?php echo $__env->yieldContent('aside'); ?>

        <div class="col-xxl col-lg-9 col-xl-9 col-12 mx-auto">
            <?php echo $__env->yieldContent('body'); ?>
        </div>
    </div>


    <?php echo $__env->make('platform::partials.toast', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>

<?php echo $__env->make('platform::partials.search-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html>
<?php /**PATH D:\leisdatabase.com\vendor\orchid\platform\resources\views/app.blade.php ENDPATH**/ ?>