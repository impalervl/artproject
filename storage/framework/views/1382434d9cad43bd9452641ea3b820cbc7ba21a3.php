<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">



    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                        <?php echo e(config('app.name', 'Laravel')); ?>

                    </a>


                </div>

                <ul class="nav navbar-nav navbar-left">
                    <li><a href="<?php echo e(url('/plans')); ?>">Plans</a></li>
                </ul>
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        <?php if(Auth::guest()): ?>
                            <li><a href="<?php echo e(route('login')); ?>">Login</a></li>
                            <li><a href="<?php echo e(route('register')); ?>">Register</a></li>
                        <?php else: ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="<?php echo e(route('logout')); ?>"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                            <?php echo e(csrf_field()); ?>

                                        </form>
                                    </li>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <?php echo $__env->yieldContent('content'); ?>
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <script src="https://js.braintreegateway.com/web/dropin/1.2.0/js/dropin.min.js"></script>
    <!-- <script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script> -->




    <script>

        $.ajax({
            url: '<?php echo e(url('braintree/token')); ?>'
        }).done(function (response){

            var button = document.querySelector('#payment-button');
            var form = document.querySelector('#payment-form');
            var nonceInput = document.querySelector('#nonce');
            var paypalEmail = document.querySelector('#paypalEmail');


            braintree.dropin.create({
                authorization: response.data.token,
                container: '#dropin-container',
                paypal: {
                    flow: 'vault'
                }
            }, function (err, dropinInstance) {
                if (err) {
                    // Handle any errors that might've occurred when creating Drop-in
                    console.error(err);
                    return;
                }
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    dropinInstance.requestPaymentMethod(function (err, payload) {
                        if (err) {
                            // Handle errors in requesting payment method
                            return;
                        }
                        console.log(payload);
                        // Send payload.nonce to your server
                        paypalEmail.value = payload.details.email;
                        nonceInput.value = payload.nonce;
                        form.submit();
                    });
                });
            });
        });

    </script>

</body>
</html>
