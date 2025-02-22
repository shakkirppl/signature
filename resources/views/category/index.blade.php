@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
  <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    
                     <div class="row">
                    <div class="col-6 col-md-6 col-sm-6 col-xs-12" >
                             <h4 class="card-title">Category</h4>
                    </div>
                    <div class="col-6 col-md-6 col-sm-6 col-xs-12  heading" style="text-align:end;">
                    <a href="{{ route('category.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
                    </div>
                       
                   
                </div>
                    
@if($message = Session::get('success'))
<div class="alert alert-sucess">
  <p>{{$message}}</p>
</div>
@endif
 
                 
                  <p class="card-description">
                
                  </p>
                  <div class="table-responsive">
                    <table class="table table-hover" id="value-table">
                      <thead>
                        <tr>
                          <th>No</th>
                          <th>Name</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      @if(count($category))
                        @foreach($category as $key=>$category)
                        <tr id="{{$category->id}}">
                            <td>{{1+$key}}</td>
                            <td class="name">{{$category->name}}</td>
                            <td>
                            @if($category->status==0) <label class="btn btn-danger">Deative</label>@else<lable class="btn btn-success">Active</label> @endif</td>
                            <td><form action="{{ route('category.destroy',$category->id) }}" method="post">
                            <a class="btn btn-minier btn-warning btn-edit" href="{{ route('category.edit', $category->id) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>               
                              </form>
                            </td>
                      </tr>
                        @endforeach
                        @else
                        <tr><td colspan="2">Sorry, No Records found!</td></tr>
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            </div>
            </div>
            
@endsection
@section('script')

@endsection
