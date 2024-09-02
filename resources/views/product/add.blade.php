@extends('layouts.app')
@section('css')
@endsection
@section('content')
    <div class="container">
        <form action="{{ $update_id == null ? route('product_store') : route('product_update', $update_id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 pl-3 pt-2">
                    <div class="pl-3 pt-3">
                        <h3>Products</h3>
                    </div>
                </div>
                <div class="col-md-6 pl-3 pt-2 ps-auto">
                    <div class="pl-3 pt-3">
                        <button class="btn btn-lg btn-dark d-flex mb-2 btn-submit"
                            style="margin-left: auto;border-radius: 12px;">
                            <span class="oi oi-loop-circular fs-1 spin loader" style="display: none"></span>
                            <span>Add Products</span></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row pl-3 pt-2 mb-5">
                        <div class="col-lg-7">
                            <div class="card">
                                <div class="card-body">
                                    <form action="#">
                                        <div class="form-group">
                                            <label for="#">Title</label>
                                            {{-- @dd($product) --}}
                                            <input placeholder="Title" name="title" type="text" class="form-control"
                                                value="{{ $product != null ? $product->title : '' }}">
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" name="description" id="" cols="30" rows="20" style="height: 60px;">{{ $product != null ? $product->description : '' }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="#">Media</label>
                                            <input placeholder="Media" name="media" type="file" class="form-control"
                                                multiple>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card mt-2">
                                <h5 class="pl-3 pt-3">Shipping</h5>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Weight</label>
                                        <input placeholder="Weight" name="weight" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-2">
                                <div class="row pl-3 pr-3">
                                    <div class="col-6">
                                        <h5 class="pl-3 pt-3">Variants</h5>
                                    </div>
                                    <div class="col-6">
                                        <div class="pl-3 pt-3" style="text-align: end;">
                                            <a href="javascript:void(0)" class="btn btn-lg btn-dark mb-2 btn-append"
                                                style="margin-left: auto;border-radius: 12px;">
                                                <span>Add Variants</span></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body ">
                                    @if ($product && $product->variants)
                                        @foreach ($product->variants as $variant)
                                            <div class="variants m-1 p-2" style="border: 1px solid #DFDFDF">
                                                <div class="form-group">
                                                    <label>Option Name</label>
                                                    <input placeholder="Option name" name="variant_name[]"
                                                        value="{{ $variant->title }}" type="text" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Price</label>
                                                    <input placeholder="Price" name="variant_price[]" type="text"
                                                    value="{{ $variant->price }}"
                                                        class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Stock</label>
                                                    <input placeholder="Stock" name="stock[]" type="text" value="{{ $variant->inventoryQuantity }}"
                                                        class="form-control">

                                                    {{-- @dd($variant) --}}
                                                    <input placeholder="Stock" name="variant_id[]" type="hidden" value="{{ $variant->shopify_id }}"
                                                        value="" class="form-control">
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="variants m-1 p-2" style="border: 1px solid #DFDFDF">
                                            <div class="form-group">
                                                <label>Option Name</label>
                                                <input placeholder="Option name" name="variant_name[]" type="text"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Price</label>
                                                <input placeholder="Price" name="variant_price[]" type="text"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Stock</label>
                                                <input placeholder="Stock" name="stock[]" type="text"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="status" id="">
                                            <option selected disabled value="">Status</option>
                                            <option value="ACTIVE"
                                                {{ $product != null ? ($product->status == 'ACTIVE' ? 'selected' : '') : '' }}>
                                                Active</option>
                                            <option value="DRAFT"
                                                {{ $product != null ? ($product->status == 'DRAFT' ? 'selected' : '') : '' }}>
                                                Draft</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-2">
                                <h5 class="pl-3 pt-3">Product Organization</h5>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <input placeholder="Category" name="category" type="text"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Product type</label>
                                        <input placeholder="Product type" name="product_type" type="text"
                                            class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Vendor</label>
                                        <input placeholder="Vendor" name="vendor" type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Tags</label>
                                        <input placeholder="Tags" name="tags" type="text" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('js_after')
    <script>
        // Get the button element
        var addButton = document.querySelector('.btn-append');

        // Add click event listener to the button
        addButton.addEventListener('click', function() {
            // Clone the variants div
            var variantsDiv = document.querySelector('.variants');
            var clonedVariantsDiv = variantsDiv.cloneNode(true);

            // Append the cloned variants div after the existing variants div
            variantsDiv.parentNode.appendChild(clonedVariantsDiv);
        });
    </script>
@endsection
