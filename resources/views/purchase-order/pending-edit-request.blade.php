@extends('layouts.layout')

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Pending Edit Requests (Purchase Orders)</h4>

          @if($orders->isEmpty())
            <p>No edit requests pending.</p>
          @else
            @php
              $allSuppliers = \App\Models\Supplier::pluck('name', 'id')->toArray();
              $allShipments = \App\Models\Shipment::pluck('shipment_no', 'id')->toArray();
              $allSalesOrders = \App\Models\SalesOrder::pluck('order_no', 'id')->toArray();
              $allProducts = \App\Models\Product::pluck('product_name', 'id')->toArray();
            @endphp

            @foreach($orders as $order)
              @if(count($order->changed_fields))
                <div class="border p-3 mb-4">
                  <h5>Order No: {{ $order->order_no }}</h5>
                  <p><strong>Supplier:</strong> {{ $allSuppliers[$order->supplier_id] ?? 'N/A' }}</p>

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
                          'supplier_id' => 'Supplier',
                          'shipment_id' => 'Shipment',
                          'SalesOrder_id' => 'Sales Order',
                          'advance_amount' => 'Advance Amount',
                        ];
                      @endphp

                      {{-- Main fields --}}
                      @foreach($order->changed_fields as $field => $values)
                        @if($field !== 'products' && isset($values['original'], $values['requested']))
                          <tr>
                            <td>{{ $fieldNames[$field] ?? ucfirst($field) }}</td>
                            <td>
                              @switch($field)
                                @case('supplier_id')
                                  {{ $allSuppliers[$values['original']] ?? 'N/A' }}
                                  @break
                                @case('shipment_id')
                                  {{ $allShipments[$values['original']] ?? 'N/A' }}
                                  @break
                                @case('SalesOrder_id')
                                  {{ $allSalesOrders[$values['original']] ?? 'N/A' }}
                                  @break
                                @default
                                  {{ $values['original'] }}
                              @endswitch
                            </td>
                            <td>
                              @switch($field)
                                @case('supplier_id')
                                  {{ $allSuppliers[$values['requested']] ?? 'N/A' }}
                                  @break
                                @case('shipment_id')
                                  {{ $allShipments[$values['requested']] ?? 'N/A' }}
                                  @break
                                @case('SalesOrder_id')
                                  {{ $allSalesOrders[$values['requested']] ?? 'N/A' }}
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
            $hasProductIdChange = isset($productChange['product_id']) && is_array($productChange['product_id']);
            $originalProductId = $hasProductIdChange ? $productChange['product_id']['original'] : ($productChange['product_id'] ?? null);
            $currentProductId = $hasProductIdChange ? $productChange['product_id']['requested'] : ($productChange['product_id'] ?? null);
            
            // For cases where only qty/male/female are changed (no product_id in changes)
            if(!$hasProductIdChange && !isset($productChange['product_id'])) {
                // Try to get product ID from order details
                $productId = null;
                foreach ($order->details as $detail) {
                    if(isset($productChange['qty']) || isset($productChange['male']) || isset($productChange['female'])) {
                        $productId = $detail->product_id;
                        break;
                    }
                }
                $originalProductId = $productId;
                $currentProductId = $productId;
            }

            $isNewProduct = is_null($originalProductId) && !is_null($currentProductId);
            $isDeletedProduct = !is_null($originalProductId) && is_null($currentProductId);
            $isProductChange = $hasProductIdChange && $originalProductId != $currentProductId;

            $originalProductName = $originalProductId ? ($allProducts[$originalProductId] ?? 'N/A') : 'N/A';
            $currentProductName = $currentProductId ? ($allProducts[$currentProductId] ?? 'N/A') : 'N/A';
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
        @foreach(['qty', 'male', 'female'] as $attr)
            @if(isset($productChange[$attr]) || isset($productChange['old_'.$attr]))
                <tr>
                    <td>{{ ucfirst($attr) }}</td>
                    <td>
                        {{ $productChange['old_'.$attr] ?? 
                          (isset($productChange[$attr]['original'])) ? $productChange[$attr]['original'] : 'N/A' }}
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
                  <form action="{{ route('purchaseorder.approveEdit', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-sm">Approve</button>
                  </form>

                  <form action="{{ route('purchaseorder.rejectEdit', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this edit request?');">
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