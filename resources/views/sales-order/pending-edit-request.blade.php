@extends('layouts.layout')

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Pending Edit Requests (Sales Orders)</h4>

          @if($orders->isEmpty())
            <p>No edit requests pending.</p>
          @else
            @php
              $allCustomers = \App\Models\Customer::pluck('customer_name', 'id')->toArray();
              $allShipments = \App\Models\Shipment::pluck('shipment_no', 'id')->toArray();
              $allProducts = \App\Models\Product::pluck('product_name', 'id')->toArray();
            @endphp

            @foreach($orders as $order)
              @if(count($order->changed_fields))
                <div class="border p-3 mb-4">
                  <h5>Order No: {{ $order->order_no }}</h5>
                  <p><strong>Customer:</strong> {{ $allCustomers[$order->customer_id] ?? 'N/A' }}</p>

                  <table class="table table-sm table-bordered">
                    <thead>
                      <tr>
                        <th>Field</th>
                        <th>Original</th>
                        <th>Requested</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                        $fieldNames = [
                          'order_no' => 'Order No',
                          'date' => 'Date',
                          'customer_id' => 'Customer',
                          'shipment_id' => 'Shipment',
                          'advance_amount' => 'Advance Amount',
                          'total' => 'Total Amount',
                          'balance_amount' => 'Balance Amount',
                          'grand_total' => 'Grand Total'
                        ];
                      @endphp

                      {{-- Main fields --}}
                      @foreach($order->changed_fields as $field => $values)
                        @if($field !== 'products' && isset($values['original'], $values['requested']))
                          <tr>
                            <td>{{ $fieldNames[$field] ?? ucfirst($field) }}</td>
                            <td>
                              @switch($field)
                                @case('customer_id')
                                  {{ $allCustomers[$values['original']] ?? 'N/A' }}
                                  @break
                                @case('shipment_id')
                                  {{ $allShipments[$values['original']] ?? 'N/A' }}
                                  @break
                                @default
                                  {{ $values['original'] }}
                              @endswitch
                            </td>
                            <td>
                              @switch($field)
                                @case('customer_id')
                                  {{ $allCustomers[$values['requested']] ?? 'N/A' }}
                                  @break
                                @case('shipment_id')
                                  {{ $allShipments[$values['requested']] ?? 'N/A' }}
                                  @break
                                @default
                                  {{ $values['requested'] }}
                              @endswitch
                            </td>
                          </tr>
                        @endif
                      @endforeach

                  
{{-- Product changes --}}
@if(isset($order->changed_fields['products']))
    @foreach($order->changed_fields['products'] as $productChange)
        @php
            // Get the current product ID (either from new data or original)
            $currentProductId = $productChange['product_id']['requested'] ?? 
                              ($productChange['product_id'] ?? 
                              ($productChange['old_product_id'] ?? null));
            
            // Get the original product ID if available
            $originalProductId = $productChange['product_id']['original'] ?? 
                                $productChange['old_product_id'] ?? null;
            
            // Get product names
            $currentProductName = $currentProductId ? ($allProducts[$currentProductId] ?? 'N/A') : 'N/A';
            $originalProductName = $originalProductId ? ($allProducts[$originalProductId] ?? 'N/A') : 'N/A';
            
            // Determine change type
            $isNewProduct = is_null($originalProductId) && !is_null($currentProductId);
            $isDeletedProduct = !is_null($originalProductId) && is_null($currentProductId);
            $isProductChange = !$isNewProduct && !$isDeletedProduct && ($originalProductId != $currentProductId);
        @endphp

        <tr class="table-info">
            <td colspan="3">
                <strong>Product Changes</strong>
                @if($isNewProduct)
                    <span class="badge bg-success">New Product Added</span>
                @elseif($isDeletedProduct)
                    <span class="badge bg-danger">Product Removed</span>
                @elseif($isProductChange)
                    <span class="badge bg-primary">Product Changed</span>
                @else
                    <span class="badge bg-warning">Product Modified</span>
                @endif
            </td>
        </tr>

        {{-- Always show product name --}}
        <tr>
            <td>Product Name</td>
            <td>{{ $isNewProduct ? 'N/A' : $originalProductName }}</td>
            <td>{{ $isDeletedProduct ? 'N/A' : $currentProductName }}</td>
        </tr>

        {{-- Show changed attributes --}}
        @foreach(['qty', 'rate', 'total'] as $attr)
            @if(isset($productChange[$attr]) || isset($productChange['old_'.$attr]))
                <tr>
                    <td>{{ ucfirst($attr) }}</td>
                    <td>
                        {{ $productChange['old_'.$attr] ?? 
                          (isset($productChange[$attr]['original']) ? $productChange[$attr]['original'] : 'N/A') }}
                    </td>
                    <td>
                        {{ is_array($productChange[$attr]) ? $productChange[$attr]['requested'] : $productChange[$attr] ?? 'N/A' }}
                    </td>
                </tr>
            @endif
        @endforeach
    @endforeach
@endif

                    </tbody>
                  </table>

                  {{-- Action Buttons --}}
                  <form action="{{ route('salesorder.approveEdit', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-sm">Approve</button>
                  </form>

                  <form action="{{ route('salesorder.rejectEdit', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this edit request?');">
                    @csrf
                    <button class="btn btn-danger btn-sm">Reject</button>
                  </form>
                </div>
              @endif
            @endforeach
          @endif

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
