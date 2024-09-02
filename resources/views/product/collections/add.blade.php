@extends('layouts.app')
@section('css')
    <style>
        .show_div {
            display: none;
        }

        .alignment {
            margin-left: 20px;
            margin-top: -10px;
            font-size: 13px;
            color: rgba(97, 97, 97, 1);
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <form action="{{ $update_id == null ? route('collection_store') : route('collection_update', $update_id) }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-6 pl-3 pt-2">
                    <div class="pl-3 pt-3">
                        <h3>Collections</h3>
                    </div>
                </div>
                <div class="col-md-6 pl-3 pt-2 ps-auto">
                    <div class="pl-3 pt-3">
                        <button class="btn btn-lg btn-dark d-flex mb-2 btn-submit"
                            style="margin-left: auto;border-radius: 12px;">
                            <span class="oi oi-loop-circular fs-1 spin loader" style="display: none"></span>
                            <span>Add Collections</span></button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row pl-3 pt-2 mb-5">
                        <div class="col-lg-7">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="#">Title</label>
                                        <input placeholder="Title" name="title" type="text" class="form-control"
                                            value="{{ $collection != null ? $collection->title : '' }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" name="description" id="" cols="30" rows="20" style="height: 60px;">{{ $collection != null ? $collection->description : '' }}</textarea>
                                    </div>

                                </div>
                            </div>
                            <div class="card mt-2">
                                <div class="row pl-3 pr-3">
                                    <div class="col-6">
                                        <h5 class="pl-3 pt-3">Collection Type</h5>
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
                                    <input type="radio" name="rd1" id="rd1" value="rd1"
                                        {{ $collection && count($collection->rules) != 0 ? ($collection->rules[0] != null ? '' : '') : 'checked' }}>
                                    <label for="rd1" class="fw-bold">Manual
                                    </label>
                                    <br>
                                    <p class="alignment">Add products to this collection one by one. </p>
                                    <input type="radio" name="rd1" id="rd2" value="rd2"
                                        {{ $collection && count($collection->rules) != 0 ? ($collection->rules[0] != null ? 'checked' : '') : '' }}>
                                    <label for="rd2" class="fw-bold">Automated</label>
                                    <br>
                                    <p class="alignment">Existing and future products that match the conditions you set will
                                        automatically be added to this collection. </p>
                                    <div class="show_div">
                                        @if ($collection && $collection->rules)
                                            @foreach ($collection->rules as $rule)
                                                <div class="variants m-1 p-2 row">
                                                    <div class="form-group col-4">
                                                        <select class="form-control" name="column[]" id="">
                                                            <option disabled value="">Column</option>
                                                            @foreach ($columnArray as $column)
                                                                <option value="{{ $column }}"
                                                                    {{ $rule->rules_column == $column ? 'selected' : '' }}>
                                                                    {{ strtolower(str_replace('_', ' ', $column)) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-4">
                                                        <select class="form-control" name="relation[]" id="">
                                                            <option disabled value="">Relation</option>
                                                            @foreach ($relationArray as $relation)
                                                                <option value="{{ $relation }}"
                                                                    {{ $rule->rules_column == $column ? 'selected' : '' }}>
                                                                    {{ strtolower(str_replace('_', ' ', $relation)) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-4">
                                                        <input placeholder="Condition" name="condition[]" type="text"
                                                            value="{{ $rule->rules_condition }}" class="form-control">
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="variants m-1 p-2 row">
                                                <div class="form-group col-4">
                                                    <select class="form-control" name="column[]" id="">
                                                        <option disabled value="">Column</option>
                                                        @foreach ($columnArray as $column)
                                                            <option value="{{ $column }}">
                                                                {{ strtolower(str_replace('_', ' ', $column)) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-4">
                                                    <select class="form-control" name="relation[]" id="">
                                                        <option disabled value="">Relation</option>
                                                        @foreach ($relationArray as $relation)
                                                            <option value="{{ $relation }}">
                                                                {{ strtolower(str_replace('_', ' ', $relation)) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-4">
                                                    <input placeholder="Condition" name="condition[]" type="text"
                                                        class="form-control">
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-2 {{ $update_id == null ? 'd-none' : '' }} products">
                                <div class="row pl-3 pr-3">
                                    <div class="col-6">
                                        <h5 class="pl-3 pt-3">Product</h5>
                                    </div>
                                </div>
                                <div class="card-body ">
                                    <div class="form-group col-10">
                                        <select class="form-control product_id" id="product_id" name="product_id[]"
                                            id="" multiple>
                                            <option disabled value="">Products</option>
                                            @if ($products)
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->shopify_id }}">
                                                        {{ $product->title }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        {{-- <input type="text"> --}}
                                    </div>
                                    <div class="col-2">
                                        <a href="javascript:void(0)" onclick="SaveProducts(this,{{ $update_id }})"
                                            class="btn btn-lg btn-dark mb-2"
                                            style="margin-left: auto;border-radius: 12px;">
                                            <span>Save Product</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="#">Media</label>
                                        <input placeholder="Media" name="media" type="file" class="form-control"
                                            multiple>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Theme template</label>
                                        <input placeholder="Default collection" name="Default_collection" type="text"
                                            class="form-control" readonly>
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
        function SaveProducts(Element, id) {
            var selectedOptions = [];
            var selectElement = document.getElementById("product_id");
            for (var i = 0; i < selectElement.options.length; i++) {
                if (selectElement.options[i].selected) {
                    selectedOptions.push(selectElement.options[i].value);
                }
            }

            var data = {
                update_id: id,
                selected_products: selectedOptions
            };

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "{{ route('collection_products') }}", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.setRequestHeader("X-CSRF-Token", "{{ csrf_token() }}");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        // Handle success response
                        console.log(response);
                    } else {
                        var error = "Error: " + xhr.status;
                        // Handle error response
                        console.error(error);
                    }
                }
            };
            xhr.send(JSON.stringify(data));
        }
        const radio1 = document.getElementById("rd1");
        const radio2 = document.getElementById("rd2");
        const showDiv = document.querySelector(".show_div");
        const product = document.querySelector(".products");
        if (rd2.checked) {
            showDiv.style.display = 'block';
            product.style.display = 'none';
        }
        radio1.addEventListener("change", function() {
            if (this.checked) {
                showDiv.style.display = "none";
                product.style.display = 'block';

            }
        });

        radio2.addEventListener("change", function() {
            if (this.checked) {
                showDiv.style.display = "block";
                product.style.display = 'none';

            }
        });
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
