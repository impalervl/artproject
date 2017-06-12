<?php $__env->startComponent('mail::message'); ?>

Visit your account for detail information

<?php $__env->startComponent('mail::button', ['url' => $url]); ?>
Button Text
<?php echo $__env->renderComponent(); ?>

Thanks,<br>
<?php echo e(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?>
