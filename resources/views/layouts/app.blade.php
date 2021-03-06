<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">



    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>


                </div>

                <ul class="nav navbar-nav navbar-left">
                    <li><a href="{{ url('/plans') }}">Plans</a></li>
                </ul>
                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://js.braintreegateway.com/web/dropin/1.2.0/js/dropin.min.js"></script>
    <!-- <script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script> -->




    <script>

        $.ajax({
            url: '{{ url('braintree/token') }}'
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
    <script>
        $(document).ready(function() {
            $.ajaxSetup({cache: true}); // since I am using jquery as well in my app
            $.getScript('//connect.facebook.net/en_US/sdk.js', function () {
                // initialize facebook sdk
                FB.init({
                    appId: '242039686279364', // replace this with your id
                    status: true,
                    cookie: true,
                    version: 'v2.9'
                });

                // attach login click event handler
                $("#btn-login").click(function () {
                    FB.login(processLoginClick, {scope: 'public_profile,email,user_friends', return_scopes: true});
                });
            });

// function to send uid and access_token back to server
// actual permissions granted by user are also included just as an addition
            function processLoginClick(response) {
                var uid = response.authResponse.userID;
                var access_token = response.authResponse.accessToken;
                var permissions = response.authResponse.grantedScopes;
                var data = {
                    uid: uid,
                    access_token: access_token,
                    _token: '{{ csrf_token() }}',
                    permissions: permissions
                };
                postData("http://artproject.dev/api/facebook/login", data, "post");
            }

// function to post any data to server
            function postData(url, data, method) {
                method = method || "post";
                var form = document.createElement("form");
                form.setAttribute("method", method);
                form.setAttribute("action", url);
                for (var key in data) {
                    if (data.hasOwnProperty(key)) {
                        var hiddenField = document.createElement("input");
                        hiddenField.setAttribute("type", "hidden");
                        hiddenField.setAttribute("name", key);
                        hiddenField.setAttribute("value", data[key]);
                        form.appendChild(hiddenField);
                    }
                }
                document.body.appendChild(form);
                form.submit();
            }
        });


    </script>

</body>
</html>
