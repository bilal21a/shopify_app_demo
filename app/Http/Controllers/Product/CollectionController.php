<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Jobs\ProductsCreateJob;
use App\Models\Collection;
use App\Models\CollectionProduct;
use App\Models\Product;
use App\Traits\CollectionSaveTrait;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    use CollectionSaveTrait;
    public function index()
    {
        // dispatch(ProductsCreateJob);
        $collections = Collection::with(['rules', 'images'])->paginate('10');
        return view('product.collections.index', compact('collections'));
    }
    public function featchData()
    {
        $shop_name = 'quickstart-1825f737.myshopify.com';
        $api = ShopifyAPIClient($shop_name);
        $query = '
                query {
                    collections(first: 250) {
                        edges {
                            node {
                                id
                                title
                                handle
                                updatedAt
                                sortOrder
                                description
                                descriptionHtml
                                sortOrder
                                templateSuffix
                                productsCount {
                                    count
                                    precision
                                }
                                ruleSet {
                                    appliedDisjunctively
                                    rules {
                                        column
                                        relation
                                        condition
                                    }
                                }
                                image{
                                    altText
                                    height
                                    width
                                    id
                                    url
                                }
                            }
                        }
                        pageInfo {
                            startCursor
                            hasPreviousPage
                            hasNextPage
                            endCursor
                        }
                    }
                }
            ';
        $response = $api->graph($query);
        // dd($response);
        $paginfo = $response['body']['data']['collections']['pageInfo'];
        if ($response['body']['data']['collections']['edges']) {
            $collections = $response['body']['data']['collections']['edges'];
            foreach ($collections as $collectionData) {
                $response = $this->SaveData($collectionData);
            }
        }
        if ($paginfo['hasNextPage']) {
            $condition = $paginfo['hasNextPage'];
            $endCursor = $paginfo['endCursor'];
            while ($condition) {
                $nextPageResponse = fetchNextCollection($api, $query, $endCursor);
                $paginfo = $nextPageResponse['body']['data']['collections']['pageInfo'];
                $collections = $nextPageResponse['body']['data']['collections']['edges'];
                foreach ($collections as $collectionData) {
                    $nextPageResponse = $this->SaveData($collectionData);
                }
                $condition = $paginfo['hasNextPage'];
                $endCursor = $paginfo['endCursor'];
            }
        }
        return "Collection saved Successfully";
    }
    public function create(Request $request)
    {
        $update_id = $request->id;
        if ($update_id) {
            $collection = Collection::with('rules')->where('shopify_id', $update_id)->first();
            $products = Product::select('id', 'shopify_id', 'title')->get();
        } else {
            $collection = null;
            $products = null;
        }
        $columnArray = ['TAG', 'TITLE', 'TYPE', 'VARIANT_COMPARE_AT_PRICE', 'VARIANT_INVENTORY', 'VARIANT_PRICE', 'VARIANT_TITLE', 'VENDOR'];
        $relationArray = ['EQUALS'];
        return view('product.collections.add', compact('update_id', 'products', 'collection', 'columnArray', 'relationArray'));
    }
    public function store(Request $request)
    {
        $shop_name = auth()->user()->name;
        $api = ShopifyAPIClient($shop_name);
        if ($request->rd1 == "rd1") {
            $query = saveCollection($request);
        } elseif ($request->rd1 == "rd2") {
            $query = saveSmartCollection($request);
        }
        $response = $api->graph($query);
        return redirect()->route('collection_index');
    }
    public function update(Request $request, $id)
    {
        $shop_name = auth()->user()->name;
        $api = ShopifyAPIClient($shop_name);
        $query = 'mutation {
            collectionUpdate(input: {
                id: "gid://shopify/Collection/' . $id . '",
                title: "' . $request->title . '",
                descriptionHtml: "' . $request->description . '",
                ruleSet: {
                    appliedDisjunctively: false,
                    rules:[';
        if (isset($request->column) && is_array($request->column)) {
            $rulesCount = count($request->column);
            for ($i = 0; $i < $rulesCount; $i++) {
                $query .= '{column: ' . $request->column[$i] . ', relation: ' . $request->relation[$i] . ', condition: "' . $request->condition[$i] . '"}';
                if ($i < $rulesCount - 1) {
                    $query .= ',';
                }
            }
        }
        $query .= ']
                    }}) {
                        userErrors {
                            field
                            message
                        }
                        collection {
                            id
                            title
                            descriptionHtml
                            handle
                            sortOrder
                            ruleSet {
                                appliedDisjunctively
                                rules {
                                    column
                                    relation
                                    condition
                                }
                            }
                        }
                }
            }';
        $response = $api->graph($query);
        return redirect()->route('collection_index');
    }
    public function addProductCollection(Request $request)
    {
        $updateId = $request->input('update_id');
        $selectedProducts = $request->input('selected_products');
        $shop_name = auth()->user()->name;
        $api = ShopifyAPIClient($shop_name);
        $productIds = array_map(function ($productId) {
            return '"gid://shopify/Product/' . $productId . '"';
        }, $selectedProducts);

        // Convert the array to a comma-separated string
        $productIdsString = implode(',', $productIds);
        // dd($selectedProducts);
        $query = 'mutation {
            collectionAddProducts(
                id: "gid://shopify/Collection/' . $updateId . '",
                productIds: [' . $productIdsString . ']
            ) {
              userErrors {
                field
                message
              }
              collection {
                id
                title
                products(first: 10) {
                    nodes {
                      id
                      title
                    }
                  }
              }
            }
          }';
        $response = $api->graph($query);
        if ($updateId) {
            foreach ($selectedProducts as $selectedProduct) {
                $collectionProduct = new CollectionProduct();
                $collectionProduct->collection_id = $updateId;
                $collectionProduct->product_id = $selectedProduct;
                $collectionProduct->save();
            }
        }
    }
}
