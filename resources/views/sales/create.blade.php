@extends('layouts.admin')

@section('header', 'New Sale')

@section('content')
<div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-8rem)]">
    <!-- Left: Product Selection -->
    <div class="lg:w-2/5 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50">
            <label class="block text-sm font-medium text-gray-700 mb-1">Add Product</label>
            <select id="product-select" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                <option value="">-- Select Medicine --</option>
                @foreach($medicines as $m)
                    <option value="{{ $m->id }}" 
                        data-price="{{ $m->default_price }}" 
                        data-sku="{{ $m->sku }}"
                        data-batches="{{ json_encode($m->batches) }}">
                        {{ $m->name }} ({{ $m->sku }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="p-4 flex-1 overflow-y-auto space-y-4" id="batch-selection-area" style="display:none;">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h4 class="font-bold text-blue-900 mb-2" id="selected-medicine-name">Medicine Name</h4>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-semibold text-blue-700 uppercase mb-1">Select Batch</label>
                        <select id="batch-select" class="w-full px-3 py-2 text-sm border border-blue-200 rounded">
                            <!-- Batches populated by JS -->
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-blue-700 uppercase mb-1">Quantity</label>
                            <input type="number" id="item-quantity" value="1" min="1" class="w-full px-3 py-2 text-sm border border-blue-200 rounded">
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-semibold text-blue-700 uppercase mb-1">Price</label>
                            <input type="number" id="item-price" step="0.01" class="w-full px-3 py-2 text-sm border border-blue-200 rounded">
                        </div>
                    </div>
                    <button id="add-to-cart-btn" type="button" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded transition-colors">
                        Add to Invoice
                    </button>
                </div>
            </div>
        </div>
        
        <div class="p-4 bg-gray-50 border-t border-gray-100 text-center text-gray-400 text-sm">
            Select a medicine to see available batches and add to cart.
        </div>
    </div>

    <!-- Right: Invoice Preview -->
    <div class="lg:w-3/5 bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-lg">Current Invoice</h3>
            <div class="w-48">
                <input type="date" form="invoice-form" name="invoice_date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-1 text-sm border border-gray-200 rounded text-right">
            </div>
        </div>

        <form id="invoice-form" method="POST" action="{{ route('sales.store') }}" class="flex-1 flex flex-col overflow-hidden">
            @csrf
            
            <div class="p-6 border-b border-gray-100 grid grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-gray-500 uppercase">Invoice No</label>
                    <input type="text" name="invoice_no" value="INV-{{ time() }}" readonly class="block w-full text-lg font-mono font-bold text-gray-900 bg-transparent border-none p-0 focus:ring-0">
                </div>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-0">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-500 uppercase">
                        <tr>
                            <th class="px-6 py-3">Item</th>
                            <th class="px-6 py-3 text-center">Batch</th>
                            <th class="px-6 py-3 text-center">Qty</th>
                            <th class="px-6 py-3 text-right">Price</th>
                            <th class="px-6 py-3 text-right">Total</th>
                            <th class="px-6 py-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody id="cart-tbody" class="divide-y divide-gray-100">
                        <!-- Items will be added here -->
                    </tbody>
                </table>
                
                <div id="empty-cart-msg" class="text-center py-10 text-gray-400">
                    No items in invoice
                </div>
            </div>

            <!-- Totals & Submit -->
            <div class="bg-gray-50 p-6 border-t border-gray-100">
                <div class="flex justify-end gap-10 mb-2">
                    <span class="text-gray-600 font-medium">Subtotal</span>
                    <span class="text-gray-900 font-bold" id="subtotal-display">$0.00</span>
                </div>
                <div class="flex justify-end gap-4 items-center mb-4">
                    <span class="text-gray-600 font-medium text-sm">Discount</span>
                    <div class="relative w-24">
                        <input type="number" name="discount_percent" id="global-discount" value="0" min="0" max="100" class="w-full px-2 py-1 text-right border border-gray-300 rounded text-sm">
                        <span class="absolute right-7 top-1 text-gray-400 text-xs">%</span>
                    </div>
                </div>
                <div class="flex justify-end gap-10 text-xl border-t border-gray-200 pt-4">
                    <span class="font-bold text-gray-800">Total Payable</span>
                    <span class="font-bold text-blue-600" id="total-display">$0.00</span>
                </div>

                <button type="submit" class="w-full mt-6 bg-slate-900 hover:bg-slate-800 text-white font-bold py-4 rounded-lg shadow-lg hover:shadow-xl transition-all flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Process Payment & Print
                </button>
            </div>
            
            <!-- Hidden inputs container -->
            <div id="hidden-inputs"></div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productSelect = document.getElementById('product-select');
        const batchSelectionArea = document.getElementById('batch-selection-area');
        const selectedMedicineName = document.getElementById('selected-medicine-name');
        const batchSelect = document.getElementById('batch-select');
        const itemQuantity = document.getElementById('item-quantity');
        const itemPrice = document.getElementById('item-price');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        
        const cartTbody = document.getElementById('cart-tbody');
        const emptyCartMsg = document.getElementById('empty-cart-msg');
        const hiddenInputs = document.getElementById('hidden-inputs');
        const globalDiscount = document.getElementById('global-discount');
        const subtotalDisplay = document.getElementById('subtotal-display');
        const totalDisplay = document.getElementById('total-display');

        let cart = [];
        let currentMedicine = null;

        // When medicine is selected
        productSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            if (!option.value) {
                batchSelectionArea.style.display = 'none';
                return;
            }

            currentMedicine = {
                id: option.value,
                name: option.text,
                sku: option.getAttribute('data-sku'),
                default_price: parseFloat(option.getAttribute('data-price')),
                batches: JSON.parse(option.getAttribute('data-batches'))
            };

            selectedMedicineName.textContent = currentMedicine.name;
            itemPrice.value = currentMedicine.default_price;
            
            // Populate batches
            batchSelect.innerHTML = '';
            if (currentMedicine.batches.length > 0) {
                currentMedicine.batches.forEach(b => {
                    const opt = document.createElement('option');
                    opt.value = b.id;
                    opt.text = `Batch: ${b.batch_no} (Exp: ${b.expiry_date || 'N/A'}) - Qty: ${b.quantity}`;
                    opt.setAttribute('data-price', b.selling_price || currentMedicine.default_price);
                    batchSelect.appendChild(opt);
                });
                // Auto select first batch price
                const firstBatchPrice = batchSelect.options[0].getAttribute('data-price');
                if(firstBatchPrice) itemPrice.value = firstBatchPrice;
            } else {
                const opt = document.createElement('option');
                opt.value = "";
                opt.text = "No Batches (Generic Stock)";
                batchSelect.appendChild(opt);
            }

            batchSelectionArea.style.display = 'block';
        });

        // When batch changes, update price
        batchSelect.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const price = option.getAttribute('data-price');
            if(price) itemPrice.value = price;
        });

        // Add to Cart
        addToCartBtn.addEventListener('click', function() {
            if(!currentMedicine) return;

            const qty = parseInt(itemQuantity.value);
            const price = parseFloat(itemPrice.value);
            const batchId = batchSelect.value;
            const batchText = batchSelect.options[batchSelect.selectedIndex].text;

            if (qty <= 0) return alert('Quantity must be greater than 0');

            // Check if items exists in cart (same batch)
            const existing = cart.find(i => i.medicine_id === currentMedicine.id && i.batch_id === batchId);
            if (existing) {
                existing.quantity += qty;
                existing.unit_price = price; // Update price to latest
            } else {
                cart.push({
                    medicine_id: currentMedicine.id,
                    medicine_name: currentMedicine.name,
                    batch_id: batchId,
                    batch_text: batchText,
                    quantity: qty,
                    unit_price: price
                });
            }

            renderCart();
            
            // Reset quantity
            itemQuantity.value = 1;
        });

        // Global Discount Chagne
        globalDiscount.addEventListener('input', renderCart);

        // Render functions
        function renderCart() {
            cartTbody.innerHTML = '';
            hiddenInputs.innerHTML = '';
            let subtotal = 0;

            if (cart.length === 0) {
                emptyCartMsg.style.display = 'block';
            } else {
                emptyCartMsg.style.display = 'none';
            }

            cart.forEach((item, index) => {
                const itemTotal = item.quantity * item.unit_price;
                subtotal += itemTotal;

                // UI Row
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';
                row.innerHTML = `
                    <td class="px-6 py-3 text-sm font-medium text-gray-900">${item.medicine_name}</td>
                    <td class="px-6 py-3 text-center text-xs text-gray-500">${item.batch_text}</td>
                    <td class="px-6 py-3 text-center text-sm">${item.quantity}</td>
                    <td class="px-6 py-3 text-right text-sm">$${item.unit_price.toFixed(2)}</td>
                    <td class="px-6 py-3 text-right text-sm font-bold">$${itemTotal.toFixed(2)}</td>
                    <td class="px-6 py-3 text-center">
                        <button type="button" class="text-red-500 hover:text-red-700" onclick="removeCartItem(${index})">
                            &times;
                        </button>
                    </td>
                `;
                cartTbody.appendChild(row);

                // Hidden Inputs
                addHiddenInput(index, 'medicine_id', item.medicine_id);
                if(item.batch_id) addHiddenInput(index, 'batch_id', item.batch_id);
                addHiddenInput(index, 'quantity', item.quantity);
                addHiddenInput(index, 'unit_price', item.unit_price);
            });

            // Calculate Totals
            const discountPercent = parseFloat(globalDiscount.value) || 0;
            const total = subtotal - (subtotal * (discountPercent / 100));

            subtotalDisplay.textContent = '$' + subtotal.toFixed(2);
            totalDisplay.textContent = '$' + total.toFixed(2);
        }

        function addHiddenInput(index, field, value) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `items[${index}][${field}]`;
            input.value = value;
            hiddenInputs.appendChild(input);
        }

        // Expose remove function to window
        window.removeCartItem = function(index) {
            cart.splice(index, 1);
            renderCart();
        };
    });
</script>
@endsection
