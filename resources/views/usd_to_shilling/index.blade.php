
                      
@extends('layouts.layout')
@section('content')

<div class="main-panel">
     <div class="content-wrapper">
         <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                    <div class="card-body">
                       <div class="row">
                           <div class="col-6 col-md-6 col-sm-6 col-xs-12" >
                                <h4 class="card-title">Currency Update</h4>
                            </div>
                           
                  

             <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
            <tr>
                <th>ID</th>
                <th>USD</th>
                <th>Shilling</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($currencies as $currency)
            <tr>
                <td>{{ $currency->id }}</td>
                <td>{{ $currency->usd }}</td>
                <td>{{ number_format($currency->shilling, 4) }}</td>
                <td>
                    <a href="{{ route('usd_to_shilling.edit', $currency->id) }}" class="btn btn-primary">Currency Update</a>
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
