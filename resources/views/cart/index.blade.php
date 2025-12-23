@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('cart'))
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(session('cart') as $id => $details)
                        <tr data-id="{{ $id }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $details['name'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${{ number_format($details['price'], 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="number" value="{{ $details['quantity'] }}" class="quantity update-cart w-20 border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${{ number_format($details['price'] * $details['quantity'], 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button class="text-red-600 hover:text-red-900 remove-from-cart">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-right">
                            <div class="space-y-2 flex flex-col items-end">
                                <div class="text-gray-600">Subtotal: ${{ number_format($total ?? 0, 2) }}</div>
                                
                                @if(isset($discountAmount) && $discountAmount > 0)
                                    <div class="text-green-600 font-medium">
                                        Loyalty Discount: -${{ number_format($discountAmount, 2) }}
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900 border-t pt-2 mt-2">
                                        Net Total: ${{ number_format($netTotal, 2) }}
                                    </div>
                                @else
                                    <div class="text-xl font-bold text-gray-900">
                                        Total: ${{ number_format($total ?? 0, 2) }}
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="mt-8 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Continue Shopping
            </a>
            
            <form action="{{ route('checkout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition-all">
                    Checkout Now
                </button>
            </form>
        </div>
    @else
        <div class="text-center py-20 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-6 text-blue-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="text-xl font-medium text-gray-900 mb-2">Your cart is empty</h3>
            <p class="text-gray-500 mb-8">Looks like you haven't added any medicines yet.</p>
            <a href="{{ url('/') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700">
                Start Shopping
            </a>
        </div>
    @endif
</div>

<script type="text/javascript">
    // Simple JS for updating cart via AJAX
    // Note: In a real app we'd use a separate JS file
    const updateCartInputs = document.querySelectorAll('.update-cart');
    updateCartInputs.forEach(input => {
        input.addEventListener('change', function() {
            const id = this.closest('tr').dataset.id;
            const quantity = this.value;
            
            fetch('{{ route('cart.update') }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ id: id, quantity: quantity })
            }).then(response => {
                window.location.reload();
            });
        });
    });

    const removeButtons = document.querySelectorAll('.remove-from-cart');
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            if(confirm("Are you sure you want to remove this?")) {
                const id = this.closest('tr').dataset.id;
                fetch('{{ route('cart.remove') }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ id: id })
                }).then(response => {
                    window.location.reload();
                });
            }
        });
    });
</script>
@endsection
