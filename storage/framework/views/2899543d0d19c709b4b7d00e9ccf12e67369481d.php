<?php $__env->startSection('content'); ?>


    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><?php echo e($plan->name); ?></div>
                    <div class="panel-body" >
                        <form id="payment-form" action="<?php echo e(url('/subscribe')); ?>" method="post">
                            <div id="dropin-container"></div>
                            <input type="submit" class="btn btn-primary btn-flat" value="Purchase"></input>
                            <input type="hidden" id="nonce" name="payment_method_nonce"></input>
                            <input type="hidden" name="plan" value="<?php echo e($plan->id); ?>">
                            <input type="hidden" id="paypalEmail" name="paypal_email" >
                            <?php echo e(csrf_field()); ?>

                        </form>
                        <!--
                        <form action="<?php echo e(url('/subscribe')); ?>" method="post">
                            <div id="dropin-container"></div>
                            <input type="hidden" name="plan" value="<?php echo e($plan->id); ?>">
                            <?php echo e(csrf_field()); ?>

                            <hr>
                            <button id="payment-button" class="btn btn-primary btn-flat hidden" type="submit">Pay now</button>
                        </form>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>