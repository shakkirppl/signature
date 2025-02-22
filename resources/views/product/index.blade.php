@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Product List</h4>
            </div>
            <div class="col-md-6 text-right">
            <a href="{{ route('product.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>No</th>
                 
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>HSN Code</th>
                                <th>Description</th>
                                <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($products as $index => $product)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $product->product_name }}</td>
                  <td>{{ $product->category->name ?? 'N/A' }}</td>  <!-- Display the category name -->
                  <td>{{ $product->hsn_code }}</td>
                  <td>{{ $product->description }}</td>
                  <td>   @if($product->product_image)
                        <img src="{{ asset('public/Image/' . $product->product_image) }}" alt="Product Image" width="50">
                         @else
                         No Image
                         @endif</td>
                  <td>
                    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <a href="{{ route('product.destroy', $product->id) }}" 
                                                    class="btn btn-danger btn-sm delete-btn" 
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <i class="mdi mdi-delete"></i> Delete
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









