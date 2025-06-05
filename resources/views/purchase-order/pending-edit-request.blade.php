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
            @foreach($orders as $order)
              @if(count($order->changed_fields))
                <div class="border p-3 mb-4">
                  <h5>Order No: {{ $order->order_no }}</h5>
                  <p><strong>Supplier:</strong> {{ $order->supplier->name ?? 'N/A' }}</p>

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
                          'SalesOrder_id' => 'Sales Order No',
                          'advance_amount' => 'Advance Amount',
                        ];
                      @endphp

                      {{-- Main field changes --}}
                      @foreach($order->changed_fields as $field => $values)
                        @if($field !== 'products' && isset($values['original'], $values['requested']))
                          <tr>
                            <td>{{ $fieldNames[$field] ?? ucfirst($field) }}</td>
                            <td>
                              @switch($field)
                                @case('supplier_id')
                                  {{ \App\Models\Supplier::find($values['original'])->name ?? 'N/A' }}
                                  @break
                                @case('shipment_id')
                                  {{ \App\Models\Shipment::find($values['original'])->shipment_no ?? 'N/A' }}
                                  @break
                                @case('SalesOrder_id')
                                  {{ \App\Models\SalesOrder::find($values['original'])->order_no ?? 'N/A' }}
                                  @break
                                @default
                                  {{ $values['original'] }}
                              @endswitch
                            </td>
                            <td>
                              @switch($field)
                                @case('supplier_id')
                                  {{ \App\Models\Supplier::find($values['requested'])->name ?? 'N/A' }}
                                  @break
                                @case('shipment_id')
                                  {{ \App\Models\Shipment::find($values['requested'])->shipment_no ?? 'N/A' }}
                                  @break
                                @case('SalesOrder_id')
                                  {{ \App\Models\SalesOrder::find($values['requested'])->order_no ?? 'N/A' }}
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
                        @foreach($order->changed_fields['products'] as $productId => $changes)
                          @php
                            $product = \App\Models\Product::find($productId);
                          @endphp

                          <tr class="table-info">
                            <td colspan="3"><strong>Product:</strong> {{ $product->product_name ?? 'Unknown Product' }}</td>
                          </tr>

                          @foreach(['qty', 'male', 'female'] as $attr)
                            @if(isset($changes[$attr]))
                              <tr>
                                <td>{{ ucfirst($attr) }}</td>
                                <td>{{ $changes[$attr]['original'] ?? 'N/A' }}</td>
                                <td>{{ $changes[$attr]['requested'] }}</td>
                              </tr>
                            @endif
                          @endforeach
                        @endforeach
                      @endif
                    </tbody>
                  </table>

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