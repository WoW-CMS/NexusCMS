@extends('layouts.main')

@section('title', 'Braintree Checkout')

@section('content')
<div class="max-w-2xl mx-auto pt-24 pb-16 px-4">
    <div class="bg-slate-900/50 border border-slate-800 rounded-xl p-8">
        <h2 class="text-2xl font-bold mb-4">Complete your payment</h2>
        <p class="text-slate-400 mb-6">Amount: ${{ number_format($amount ?? 0, 2) }}</p>
        <form id="bt-form" method="POST" action="{{ route('donate.checkout') }}">
            @csrf
            <input type="hidden" name="gateway" value="braintree">
            <input type="hidden" name="amount" value="{{ $amount }}">
            <input type="hidden" name="nonce" id="nonce">
            <div id="dropin-container" class="mb-6"></div>
            <button id="submit-button" type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-semibold">
                Pay
            </button>
        </form>
    </div>
</div>

<script src="https://js.braintreegateway.com/web/dropin/1.39.0/js/dropin.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('bt-form');
    var button = document.getElementById('submit-button');
    braintree.dropin.create({
        authorization: '{{ $token }}',
        container: '#dropin-container'
    }, function (createErr, instance) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            instance.requestPaymentMethod(function (err, payload) {
                if (err) return;
                document.getElementById('nonce').value = payload.nonce;
                form.submit();
            });
        });
    });
});
</script>
@endsection

