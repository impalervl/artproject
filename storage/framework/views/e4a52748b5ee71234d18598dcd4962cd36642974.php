<?php $__env->startComponent('mail::message'); ?>
    <h2>Спасибо за регистрацию, для подтверждения нажмите кнопку "Подтвердить регистрацию"</h2>.

    <?php $__env->startComponent('mail::button', ['url' => "artproject/api/verifyemail/$user->email_token"]); ?>
        Подтвердить регистрацию
    <?php echo $__env->renderComponent(); ?>


    Thanks,<br>
    <?php echo e(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?>
