@extends('layouts.layout')
@section('content')
<style>
  .required:after {
    content: " *";
    color: red;
  }
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Product</h4>
                    <div class="col-md-6 heading">
                        <a href="{{ route('product.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <!-- First Section -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="required">Product Name</label>
                                    <input type="text" class="form-control" id="name" name="product_name" value="{{ old('product_name', $product->product_name) }}" placeholder="Enter Product Name">
                                </div>

                                <div class="form-group">
                                    <label for="category" class="required">Category</label>
                                    <select class="form-control" id="category" name="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="hsn_code">HSN Code</label>
                                    <input type="text" class="form-control" id="hsn_code" name="hsn_code" value="{{ old('hsn_code', $product->hsn_code) }}" placeholder="Enter HSN Code">
                                </div>
                            </div>

                            <!-- Second Section -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_image">Product Image</label>
                                    <input type="file" class="form-control" id="product_image" name="product_image">
                                    @if($product->product_image)
                                        <img src="{{ asset('public/Image/' . $product->product_image) }}" alt="Product Image" width="100">
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
