@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title">Slaughter Schedule Details</h4>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                            <a href="{{ url('slaughter-schedules-index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>
                    <div class="row mt-3">
                        @if($schedules->isNotEmpty())
                            @foreach ($schedules as $schedule)
                                <div class="col-md-6">
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title"><strong>Slaughter No:</strong> {{ $schedule->slaughter_no }}</h5>
                                            <p><strong>Products:</strong></p>
                                            <ul>
                                                @foreach ($schedule->details as $detail)
                                                    <li>{{ $detail->products->product_name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>No schedules found.</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
