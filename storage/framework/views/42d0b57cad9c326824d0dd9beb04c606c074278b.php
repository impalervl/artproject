<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Choose your plan</div>

                    <div class="panel-body">
                        <ul class="list-group">
                            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item clearfix">
                                    <div class="pull-left">
                                        <h4><?php echo e($plan->name); ?></h4>
                                        <h4>$<?php echo e(number_format($plan->cost, 2)); ?></h4>
                                        <?php if($plan->description): ?>
                                            <p><?php echo e($plan->description); ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <a href="<?php echo e(url('/plan', $plan->slug)); ?>" class="btn btn-default pull-right">Choose Plan</a>

                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>