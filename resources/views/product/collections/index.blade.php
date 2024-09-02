@extends('layouts.app')
@section('css')
    <style>
        th {
            width: 220px;
        }

        .toast {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #1A1A1A;
            color: rgb(255, 255, 255);
            padding: 10px;
            border-radius: 5px;
            z-index: 9999;
        }

        .plus_styling {
            display: block;
            font-size: 13px;
            margin-right: 3px;
            margin-top: 3px;
        }

        .sync_styling {
            display: block;
            font-size: 13px;
            margin-right: 3px;
            margin-top: 3px;
        }
    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6 pl-3 pt-2">
                <div class="pl-3">
                    <h3>Collections</h3>
                </div>
            </div>
            <div class="col-md-6 pl-3 pt-2 d-flex justify-content-end">
                <div class="pl-3">
                    <a href="{{ route('collection_add') }}" class="btn btn-lg btn-dark d-flex mb-2"
                        style="margin-left: auto;border-radius: 12px;">
                        <span class="oi oi-plus plus_styling" style=""></span>
                        <span>Add Collection</span></a>
                </div>
                <div class="pl-3">
                    <button class="btn btn-lg btn-dark d-flex mb-2" onclick="syncBtn()"
                        style="margin-left: auto;border-radius: 12px;">
                        <span class="oi oi-loop-circular fs-1 spin loader sync_styling" style="display: none"></span>
                        <span class="oi oi-loop-circular fs-1 loader_no_spin sync_styling" style="display: block"></span>
                        <span>Sync Collections</span></button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table shadow-0">
                    <thead>
                        <tr>
                            <th class="fw_bold w-15">Image</th>
                            <th class="fw_bold w-25">Title</th>
                            <th class="fw_bold w-25">Products</th>
                            <th class="fw_bold w-50">Product Conditions</th>
                            <th class="fw_bold" style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($collections as $collection)
                            <tr>
                                <td scope="row">
                                    {{-- @dd( $collections[8]->images[0]->src) --}}
                                    <img width="70px" src="{{ $collection->images ? $collection->images->src: asset('assets/notfound.png') }}" alt="" srcset="">
                                </td>
                                <td>{{ $collection->title }}</td>
                                <td>{{ $collection->products_count != null ? $collection->products_count : '0' }}</td>
                                <td>
                                    @foreach ($collection->rules as $rule )
                                    Product {{strtolower($rule->rules_column)}} is {{strtolower($rule->rules_relation)}} to {{strtolower($rule->rules_condition)}}<br>
                                    @endforeach
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-lg btn-danger d-flex mb-2"
                                            style="border-radius: 12px;background: #B5260B !important;"
                                            {{-- onclick="deleteBtn(this,{{ $product->shopify_id }})" --}}
                                            >
                                            <span>Delete</span>
                                        </button>
                                        <a
                                        href="{{ route('collection_add') }}?id={{ $collection->shopify_id }}"
                                            class="btn btn-lg btn-dark d-flex mb-2"
                                            style="margin-left: 8px;border-radius: 12px;">
                                            <span>Update</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="fw_bold">No data Found !</td>
                                <td></td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <nav aria-label="...">
                                    <ul class="pagination">
                                        <!-- Previous Page Link -->
                                        @if ($collections->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link" tabindex="-1">Previous</span>
                                        </li>
                                        @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $collections->previousPageUrl() }}" tabindex="-1">Previous</a>
                                        </li>
                                        @endif

                                        <!-- Pagination Elements -->
                                        @foreach ($collections->getUrlRange(1, $collections->lastPage()) as $page => $url)
                                        @if ($page == $collections->currentPage())
                                        <li class="page-item active" aria-current="page">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                        @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                        @endif
                                        @endforeach

                                        <!-- Next Page Link -->
                                        @if ($collections->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $collections->nextPageUrl() }}">Next</a>
                                        </li>
                                        @else
                                        <li class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </li>
                                        @endif
                                    </ul>
                                </nav>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js_after')
    <script>
        function showToast(message) {
            var toast = document.createElement("div");
            toast.className = "toast";
            toast.textContent = message;
            document.body.appendChild(toast);
        }

        function syncBtn() {
            showToast("Syncing Collections....");
            var elements = document.getElementsByClassName("loader");
            var elementsLoadStop = document.getElementsByClassName("loader_no_spin");
            console.log('elementsLoadStop: ', elementsLoadStop);
            for (var i = 0; i < elements.length; i++) {
                elements[i].style.display = "block";
            }
            for (var i = 0; i < elementsLoadStop.length; i++) {
                elementsLoadStop[i].style.display = "none";
            }
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "{{ route('collection_featch') }}", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var data = xhr.responseText;
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        var error = "Error: " + xhr.status;
                    }
                }
            };
            xhr.send();
        }

        function deleteBtn(Element, id) {
            console.log(Element);
            var xhr = new XMLHttpRequest();
            var url = `{{ route('product_delete', ['id' => ':id']) }}`; // Define the route with a placeholder for the id
            url = url.replace(':id', id); // Replace the placeholder with the actual id value
            xhr.open("GET", url, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        var data = xhr.responseText;
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        var error = "Error: " + xhr.status;
                        // toastr.error('Update failed: ' + error, 'Error');
                    }
                }
            };
            xhr.send();
        }
    </script>
@endsection
