@extends('layouts.app')

@section('content')

<!-- formulier is wel gewoon gemaakt met AI man ik ben niet zo goed in tailwind frontend, probeerde het zelf te maken en ohmydays was het ugly -->

<!-- bij eindresultaat ga ik wel een eigen formulier maken -->


@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
        {{ session('success') }}
        @php
            // Try to extract the shipmentId from the label_pdf_url if present
            $shipmentId = null;
            if (session('label_pdf_url')) {
                // Example: .../shipments/{shipmentId}/labels/pdf
                if (preg_match('/shipments\/([\w-]+)\/labels\/pdf/', session('label_pdf_url'), $matches)) {
                    $shipmentId = $matches[1];
                }
            }
        @endphp
        @if($shipmentId)
            <a href="{{ url('/download-label/' . $shipmentId) }}" class="underline text-green-600 ml-2" target="_blank">Download label (PDF) via backend</a>
        @endif
    </div>
    <script>console.log('Label succesvol aangemaakt!');</script>
@endif
@if(session('error'))
    <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
        {{ session('error') }}
        @if(session('api_error'))
            <pre class="text-xs mt-2">{{ session('api_error') }}</pre>
        @endif
    </div>
    <script>console.error('Label aanmaken mislukt!');</script>
@endif
@if(session('label_pdf_url'))
    <script>console.log('Label ophalen gelukt: {{ session('label_pdf_url') }}');</script>
@endif

<div class="max-w-4xl mx-auto bg-white p-8 shadow-md rounded-lg mt-10">
    <h2 class="text-2xl font-semibold mb-6">Order Details</h2>
    <form action="/create-label" method="POST" x-data="{ tab: 'billing' }">
        @csrf
        <!-- tabbladen -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="flex space-x-4" aria-label="Tabs">
                <button type="button" @click="tab = 'billing'" :class="tab === 'billing' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">Billing Address</button>
                <button type="button" @click="tab = 'delivery'" :class="tab === 'delivery' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">Delivery Address</button>
                <button type="button" @click="tab = 'orderlines'" :class="tab === 'orderlines' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm focus:outline-none">Order Lines</button>
            </nav>
        </div>
        <!-- ordernummer -->
        <div class="mb-4">
            <label for="order_number" class="block text-sm font-medium text-gray-700">Order Number</label>
            <input type="text" id="order_number" name="order[number]" value="{{ old('order.number', $order['number']) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" readonly>
        </div>
        <!-- billing adres -->
        <div x-show="tab === 'billing'">
            <h3 class="text-xl font-semibold mb-4">Billing Address</h3>
            @php
                $fields = [
                    'name' => 'Full Name',
                    'companyname' => 'Company Name (if any)',
                    'street' => 'Street',
                    'housenumber' => 'House Number',
                    'address_line_2' => 'Address Line 2 (if any)',
                    'zipcode' => 'Zip Code',
                    'city' => 'City',
                    'country' => 'Country',
                    'email' => 'Email',
                    'phone' => 'Phone Number'
                ];
                $deliveryFields = [
                    'name' => 'Full Name',
                    'companyname' => 'Company Name (if any)',
                    'street' => 'Street',
                    'housenumber' => 'House Number',
                    'address_line_2' => 'Address Line 2 (if any)',
                    'zipcode' => 'Zip Code',
                    'city' => 'City',
                    'country' => 'Country',
                ];
            @endphp
            @foreach ($fields as $key => $label)
                <div class="mb-4">
                    <label for="billing_{{ $key }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                    <input type="text" id="billing_{{ $key }}" name="order[billing_address][{{ $key }}]" value="{{ old('order.billing_address.' . $key, $order['billing_address'][$key]) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" @if($key != 'companyname' && $key != 'address_line_2') required @endif>
                </div>
            @endforeach
        </div>
        <!-- Delivery Address Tab -->
        <div x-show="tab === 'delivery'">
            <h3 class="text-xl font-semibold mb-4">Delivery Address</h3>
            @foreach ($deliveryFields as $key => $label)
                <div class="mb-4">
                    <label for="delivery_{{ $key }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
                    <input type="text" id="delivery_{{ $key }}" name="order[delivery_address][{{ $key }}]" value="{{ old('order.delivery_address.' . $key, $order['delivery_address'][$key] ?? '') }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" @if($key != 'companyname' && $key != 'address_line_2') required @endif>
                </div>
            @endforeach
        </div>
        <!-- order lijntjes -->
        <div x-show="tab === 'orderlines'">
            <h3 class="text-xl font-semibold mb-4">Order Lines</h3>
            @foreach ($order['order_lines'] as $index => $item)
                <div class="mb-4 border p-4 rounded-lg">
                    <h4 class="font-medium text-lg mb-2">Item {{ $index + 1 }}</h4>
                    <label for="order_line_{{ $index }}_name" class="block text-sm font-medium text-gray-700">Item Name</label>
                    <input type="text" id="order_line_{{ $index }}_name" name="order[order_lines][{{ $index }}][name]" value="{{ old('order.order_lines.' . $index . '.name', $item['name']) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    <label for="order_line_{{ $index }}_sku" class="block text-sm font-medium text-gray-700">SKU</label>
                    <input type="text" id="order_line_{{ $index }}_sku" name="order[order_lines][{{ $index }}][sku]" value="{{ old('order.order_lines.' . $index . '.sku', $item['sku']) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    <label for="order_line_{{ $index }}_ean" class="block text-sm font-medium text-gray-700">EAN</label>
                    <input type="text" id="order_line_{{ $index }}_ean" name="order[order_lines][{{ $index }}][ean]" value="{{ old('order.order_lines.' . $index . '.ean', $item['ean']) }}" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                    <label for="order_line_{{ $index }}_amount_ordered" class="block text-sm font-medium text-gray-700">Amount Ordered</label>
                    <input type="number" id="order_line_{{ $index }}_amount_ordered" name="order[order_lines][{{ $index }}][amount_ordered]" value="{{ old('order.order_lines.' . $index . '.amount_ordered', $item['amount_ordered']) }}" min="1" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
            @endforeach
        </div>
        <button type="submit" class="mt-6 bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-indigo-700">Submit Order</button>
    </form>
    <!-- alpine.js voor het switchen tussen tabbladen -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</div>
@endsection
