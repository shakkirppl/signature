@extends('layouts.layout')

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Pending Edit Requests (Skinning)</h4>

          @if($skinnings->isEmpty())
            <p>No edit requests pending.</p>
          @else
            @php
              $allShipments = \App\Models\Shipment::pluck('shipment_no', 'id')->toArray();
              $allEmployees = \App\Models\Employee::pluck('name', 'id')->toArray();
              $allProducts = \App\Models\Product::pluck('product_name', 'id')->toArray();
            @endphp

            @foreach($skinnings as $skinning)
              @if(count($skinning->changed_fields))
                <div class="border p-3 mb-4">
                  <h5>Skinning Code: {{ $skinning->skinning_code }}</h5>
                  <p><strong>Shipment:</strong> {{ $allShipments[$skinning->shipment_id] ?? 'N/A' }}</p>

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
                          'skinning_code' => 'Skinning Code',
                          'date' => 'Date',
                          'time' => 'Time',
                          'shipment_id' => 'Shipment',
                        ];
                      @endphp

                      {{-- Main fields --}}
                      @foreach($skinning->changed_fields as $field => $values)
                        @if($field !== 'details' && isset($values['original'], $values['requested']))
                          <tr>
                            <td>{{ $fieldNames[$field] ?? ucfirst($field) }}</td>
                            <td>
                              @switch($field)
                                @case('shipment_id')
                                  {{ $allShipments[$values['original']] ?? 'N/A' }}
                                  @break
                                @default
                                  {{ $values['original'] }}
                              @endswitch
                            </td>
                            <td>
                              @switch($field)
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

                      {{-- Detail changes --}}
                      @if(isset($skinning->changed_fields['details']))
                        @foreach($skinning->changed_fields['details'] as $detailChange)
                          @php
                            $hasEmployeeChange = isset($detailChange['employee_id']) && is_array($detailChange['employee_id']);
                            $originalEmployeeId = $hasEmployeeChange ? $detailChange['employee_id']['original'] : ($detailChange['employee_id'] ?? null);
                            $currentEmployeeId = $hasEmployeeChange ? $detailChange['employee_id']['requested'] : ($detailChange['employee_id'] ?? null);

                            $isNewDetail = is_null($originalEmployeeId) && !is_null($currentEmployeeId);
                            $isDeletedDetail = !is_null($originalEmployeeId) && is_null($currentEmployeeId);
                            $isEmployeeChange = $hasEmployeeChange && $originalEmployeeId != $currentEmployeeId;

                            $originalEmployeeName = $originalEmployeeId ? ($allEmployees[$originalEmployeeId] ?? 'N/A') : 'N/A';
                            $currentEmployeeName = $currentEmployeeId ? ($allEmployees[$currentEmployeeId] ?? 'N/A') : 'N/A';

                            $originalProductId = $detailChange['product_id']['original'] ?? null;
                            $currentProductId = $detailChange['product_id']['requested'] ?? null;
                            $originalProductName = $originalProductId ? ($allProducts[$originalProductId] ?? 'N/A') : 'N/A';
                            $currentProductName = $currentProductId ? ($allProducts[$currentProductId] ?? 'N/A') : 'N/A';
                          @endphp

                          <tr class="table-info">
                            <td colspan="3">
                              <strong>Detail Changes</strong>
                              @if($isNewDetail)
                                <span class="badge bg-success">New Detail Added</span>
                              @elseif($isDeletedDetail)
                                <span class="badge bg-danger">Detail Removed</span>
                              @elseif($isEmployeeChange)
                                <span class="badge bg-primary">Employee Changed</span>
                              @else
                                <span class="badge bg-warning">Detail Modified</span>
                              @endif
                            </td>
                          </tr>

                          {{-- Employee --}}
                          <tr>
                            <td>Employee</td>
                            <td>{{ $isNewDetail ? 'N/A' : $originalEmployeeName }}</td>
                            <td>{{ $isDeletedDetail ? 'N/A' : $currentEmployeeName }}</td>
                          </tr>

                          {{-- Product --}}
                          <tr>
                            <td>Product</td>
                            <td>{{ $isNewDetail ? 'N/A' : $originalProductName }}</td>
                            <td>{{ $isDeletedDetail ? 'N/A' : $currentProductName }}</td>
                          </tr>

                          {{-- Show changed attributes --}}
                          @foreach(['quandity', 'damaged_quandity', 'skin_percentage'] as $attr)
                            @if(isset($detailChange[$attr]))
                              <tr>
                                <td>{{ ucfirst(str_replace('_', ' ', $attr)) }}</td>
                                <td>
                                  {{ $detailChange[$attr]['original'] ?? 'N/A' }}
                                </td>
                                <td>
                                  {{ $detailChange[$attr]['requested'] ?? 'N/A' }}
                                </td>
                              </tr>
                            @endif
                          @endforeach
                        @endforeach
                      @endif
                    </tbody>
                  </table>

                  {{-- Action Buttons --}}
                  <form action="{{ route('skinning.approveEdit', $skinning->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-sm">Approve</button>
                  </form>

                  <form action="{{ route('skinning.rejectEdit', $skinning->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this edit request?');">
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