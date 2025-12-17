@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
        <div class="p-8">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-8 text-center">Secure Payment Simulation</h2>
            
            <div class="mb-8 p-4 bg-blue-50 rounded-lg border border-blue-100">
                <div class="flex justify-between items-center text-lg">
                    <span class="font-medium text-blue-900">Total Amount to Pay:</span>
                    <span class="font-bold text-2xl text-blue-700">Rs. {{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="mt-2 text-sm text-blue-600 text-right">
                    Order ID: <span class="font-mono">{{ $order->order_no }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Payment Method: EasyPaisa -->
                <button onclick="selectMethod('Easypaisa')" id="btn-Easypaisa" class="payment-option group relative bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-green-500 hover:bg-green-50 transition-all duration-200 focus:outline-none">
                    <div class="text-center">
                        <div class="h-12 w-12 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-green-200 transaction-colors">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.2-2.858.578-4.18M7.747 10.892a5.966 5.966 0 00-2.348 9.584l-.399-.446z" /></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">EasyPaisa</h3>
                        <p class="text-sm text-gray-500 mt-1">Mobile Account</p>
                    </div>
                    <div class="absolute -top-3 -right-3 hidden check-icon bg-green-500 text-white rounded-full p-1 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                </button>

                <!-- Payment Method: Card -->
                <button onclick="selectMethod('Card')" id="btn-Card" class="payment-option group relative bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-blue-500 hover:bg-blue-50 transition-all duration-200 focus:outline-none">
                    <div class="text-center">
                        <div class="h-12 w-12 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-blue-200 transaction-colors">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Debit / Credit</h3>
                        <p class="text-sm text-gray-500 mt-1">Visa / Mastercard</p>
                    </div>
                     <div class="absolute -top-3 -right-3 hidden check-icon bg-blue-500 text-white rounded-full p-1 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                </button>

                <!-- Payment Method: Cash -->
                <button onclick="selectMethod('Cash')" id="btn-Cash" class="payment-option group relative bg-white border-2 border-gray-200 rounded-xl p-6 hover:border-amber-500 hover:bg-amber-50 transition-all duration-200 focus:outline-none">
                    <div class="text-center">
                        <div class="h-12 w-12 mx-auto bg-amber-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-amber-200 transaction-colors">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Cash on Counter</h3>
                        <p class="text-sm text-gray-500 mt-1">Pay at Pharmacy</p>
                    </div>
                     <div class="absolute -top-3 -right-3 hidden check-icon bg-amber-500 text-white rounded-full p-1 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                </button>
            </div>

            <!-- Action Area -->
            <div class="mt-8">
                <div id="processing" class="hidden text-center py-4">
                    <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="mt-3 text-gray-600">Processing Payment Securely...</p>
                </div>

                <div id="action-btn-container" class="opacity-50 pointer-events-none transition-opacity duration-200">
                    <button id="pay-btn" onclick="processPayment()" class="w-full bg-blue-600 text-white font-bold py-4 px-6 rounded-lg text-lg shadow-lg hover:bg-blue-700 transform hover:scale-[1.01] transition-all">
                        Pay Now <span id="method-name"></span>
                    </button>
                </div>
                
                <p class="mt-4 text-center text-xs text-gray-400">
                    <span class="inline-flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" /></svg>
                        Transactions are simulated for educational purposes. 128-bit Encrypted.
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Form for Final Submission -->
<form id="confirm-form" action="{{ route('payment.confirm') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="order_id" value="{{ $order->id }}">
    <input type="hidden" name="transaction_id" id="input-trx-id">
    <input type="hidden" name="method" id="input-method">
    <input type="hidden" name="amount" value="{{ $order->total_amount }}">
</form>

<script>
    let selectedMethod = null;

    function selectMethod(method) {
        selectedMethod = method;
        
        // Reset UI
        document.querySelectorAll('.payment-option').forEach(el => {
            el.classList.remove('border-green-500', 'bg-green-50', 'bg-blue-50', 'border-blue-500', 'bg-amber-50', 'border-amber-500', 'ring-2', 'ring-offset-2');
            el.querySelector('.check-icon').classList.add('hidden');
        });

        // Activate Selected
        const btn = document.getElementById('btn-' + method);
        const check = btn.querySelector('.check-icon');
        
        check.classList.remove('hidden');
        
        let colorClass = 'blue';
        if(method === 'Easypaisa') colorClass = 'green';
        if(method === 'Cash') colorClass = 'amber';

        btn.classList.add(`border-${colorClass}-500`, `bg-${colorClass}-50`, 'ring-2', `ring-${colorClass}-500`, 'ring-offset-2');
        
        // Enable Pay Button
        const container = document.getElementById('action-btn-container');
        container.classList.remove('opacity-50', 'pointer-events-none');
        
        document.getElementById('method-name').innerText = `with ${method}`;
    }

    async function processPayment() {
        if(!selectedMethod) return;

        const btnContainer = document.getElementById('action-btn-container');
        const processing = document.getElementById('processing');

        btnContainer.classList.add('hidden');
        processing.classList.remove('hidden');

        try {
            // Call Fake API
            const response = await fetch("{{ route('payment.simulate') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    order_id: {{ $order->id }},
                    amount: {{ $order->total_amount }},
                    method: selectedMethod
                })
            });

            const data = await response.json();

            if(data.status === 'SUCCESS') {
                // Submit Form to Confirm
                document.getElementById('input-trx-id').value = data.transaction_id;
                document.getElementById('input-method').value = selectedMethod;
                document.getElementById('confirm-form').submit();
            } else {
                alert('Payment Failed: ' + (data.message || 'Unknown error'));
                location.reload();
            }

        } catch (error) {
            console.error(error);
            alert('Simulation Error. Please try again.');
            location.reload();
        }
    }
</script>
@endsection
