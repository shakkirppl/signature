@extends('layouts.layout')

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Pending Edit Requests (Packing Lists)</h4>

          @if($packingLists->isEmpty())
            <p>No edit requests pending.</p>
          @else
            @php
              $allCustomers = \App\Models\Customer::pluck('customer_name', 'id')->toArray();
              $allSalesOrders = \App\Models\SalesOrder::pluck('order_no', 'id')->toArray();
              $allProducts = \App\Models\Product::pluck('product_name', 'id')->toArray();
            @endphp

            @foreach($packingLists as $packingList)
              @if(count($packingList->changed_fields))
                <div class="border p-3 mb-4">
                  <h5>Packing List No: {{ $packingList->packing_no }}</h5>
                  <p><strong>Customer:</strong> {{ $allCustomers[$packingList->customer_id] ?? 'N/A' }}</p>
                  <p><strong>Sales Order:</strong> {{ $allSalesOrders[$packingList->salesOrder_id] ?? 'N/A' }}</p>

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
                          'packing_no' => 'Packing List No',
                          'date' => 'Date',
                          'customer_id' => 'Customer',
                          'salesOrder_id' => 'Sales Order',
                          'shipping_mode' => 'Shipping Mode',
                          'shipping_agent' => 'Shipping Agent',
                          'terms_of_delivery' => 'Terms of Delivery',
                          'terms_of_payment' => 'Terms of Payment',
                          'currency' => 'Currency',
                          'net_weight' => 'Net Weight',
                          'gross_weight' => 'Gross Weight'
                        ];
                      @endphp

                      {{-- Main fields --}}
                      @foreach($packingList->changed_fields as $field => $values)
                        @if($field !== 'products' && isset($values['original'], $values['requested']))
                          <tr>
                            <td>{{ $fieldNames[$field] ?? ucfirst($field) }}</td>
                            <td>
                              @switch($field)
                                @case('customer_id')
                                  {{ $allCustomers[$values['original']] ?? 'N/A' }}
                                  @break
                                @case('salesOrder_id')
                                  {{ $allSalesOrders[$values['original']] ?? 'N/A' }}
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
                                @case('salesOrder_id')
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
                      @if(isset($packingList->changed_fields['products']))
                        @foreach($packingList->changed_fields['products'] as $productChange)
                          @php
                            $hasProductIdChange = isset($productChange['product_id']) && is_array($productChange['product_id']);
                            $originalProductId = $hasProductIdChange ? $productChange['product_id']['original'] : ($productChange['product_id'] ?? null);
                            $currentProductId = $hasProductIdChange ? $productChange['product_id']['requested'] : ($productChange['product_id'] ?? null);

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
                          @foreach(['packaging', 'weight', 'par', 'total'] as $attr)
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
                  <form action="{{ route('packinglist.approveEdit', $packingList->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-sm">Approve</button>
                  </form>

                  <form action="{{ route('packinglist.rejectEdit', $packingList->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this edit request?');">
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