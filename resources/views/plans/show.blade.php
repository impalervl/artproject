@extends('layouts.app')

@section('content')


    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ $plan->name }}</div>
                    <div class="panel-body">
                        <form id="payment-form" action="{{ url('/subscribe') }}" method="post">
                            <div id="dropin-container"></div>
                            <input type="submit" class="btn btn-primary btn-flat" value="Purchase"></input>
                            <input type="hidden" id="nonce" name="payment_method_nonce"></input>
                            <input type="hidden" name="plan" value="{{ $plan->id }}">
                            <input type="hidden" id="paypalEmail" name="paypal_email" >
                            {{ csrf_field() }}
                        </form>
                        <!--
                        <form action="{{ url('/subscribe') }}" method="post">
                            <div id="dropin-container"></div>
                            <input type="hidden" name="plan" value="{{ $plan->id }}">
                            {{ csrf_field() }}
                            <hr>
                            <button id="payment-button" class="btn btn-primary btn-flat hidden" type="submit">Pay now</button>
                        </form>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
