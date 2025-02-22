@extends('layouts.layout') 

@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Rejected Animal Report</h4>
            </div>
            <div class="col-md-6 text-right"></div>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Shipment No</th>
                  
                </tr>
              </thead>
              <tbody>
                @foreach ($rejectedReports as $index => $report)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>
                    <a href="{{ route('shipment.rejected.details', ['shipment_no' => $report->shipment_no]) }}" class="btn btn-primary btn-sm">
                      {{ $report->shipment_no }}
                    </a>
                  </td>
                 
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
