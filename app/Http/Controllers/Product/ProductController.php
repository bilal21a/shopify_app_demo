<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use App\Traits\ProductSaveTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    use ProductSaveTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::with('images')->get();
        return view('product.index', compact('products'));
    }

    public function featchData()
    {
        $shop_name = env('SHOPIFY_NAME');
        $api = ShopifyAPIClient($shop_name);
        $query = <<<GRAPHQL
                query {
                    products(first: 250) {
                        edges {
                            node {
                                id
                                title
                                status
                                tags
                                vendor
                                createdAt
                                hasOnlyDefaultVariant
                                description
                                featuredImage {
                                    altText
                                    id
                                    height
                                    width
                                    url
                                    originalSrc
                                }

                                options {
                                    id
                                    name
                                    values
                                }
                                variants(first: 250) {
                                    edges {
                                        node {
                                            id
                                            title
                                            sku
                                            price
                                            inventoryQuantity
                                        }
                                    }
                                }
                                category {
                                            id
                                }
                                images(first: 250) {
                                    edges {
                                        node {
                                            altText
                                            id
                                            height
                                            width
                                            url
                                            originalSrc
                                        }
                                    }
                                }

                                productType
                                hasOutOfStockVariants
                                tracksInventory
                                totalInventory
                                handle
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
            GRAPHQL;
        $response = $api->graph($query);
        // dd($response);
        $paginfo =$response['body']['data']['products']['pageInfo'];
        if ($response['body']['data']['products']['edges']) {
            $products = $response['body']['data']['products']['edges'];
            foreach ($products as $productData) {
                $response = $this->SaveData($productData);
            }
        }
        if ($paginfo['hasNextPage']) {
            $condition = $paginfo['hasNextPage'];
            $endCursor = $paginfo['endCursor'];
            while ($condition) {
                $nextPageResponse = fetchNextProduct($api, $query, $endCursor);
                $paginfo = $nextPageResponse['body']['data']['products']['pageInfo'];
                $products = $nextPageResponse['body']['data']['products']['edges'];
                foreach ($products as $productData) {
                    $nextPageResponse = $this->SaveData($productData);
                }
                $condition =$paginfo['hasNextPage'];
                $endCursor = $paginfo['endCursor'];
            }
        }
        return "Data Featched Successfully";
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $update_id = $request->id;
        if ($update_id) {
            $product = Product::with('variants')->where('shopify_id', $update_id)->first();
            // dd($product);
        } else {
            $product = null;
        }
        // dd($update_id);
        return view('product.add', compact('update_id', 'product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $imageUrls = $request->media;
        if ($imageUrls) {
            $filePath = $request->media->store('public/images');
            $imageUrlFromRequest = asset(str_replace('public', 'storage', $filePath));
            $mediaBlocks = [];
            foreach ($imageUrls as $imageUrl) {
                $mediaBlocks[] = [
                    'alt' => 'no img',
                    'mediaContentType' => 'IMAGE',
                    'originalSource' => $imageUrl
                ];
            }
        } else {
            $mediaBlocks = null;
        }
        $mediaBlockString = json_encode($mediaBlocks);
        $tag = explode(",", $request->tags);
        $tags = implode('", "', $tag);
        $tagsString = '"' . $tags . '"';
        $shop_name = env('SHOPIFY_NAME');
        $api = ShopifyAPIClient($shop_name);
        $query = 'mutation {
            productCreate(input: {
              title: "' . $request->title . '"
              productType: "' . $request->product_type . '"
              vendor: "' . $request->vendor . '"
              tags: [' . $tagsString . '],
              descriptionHtml: "' . $request->description . '"
            }
            ) {product {id}}
            }';
        $response = $api->graph($query);
        if ($response != null) {
            $graphProductId = $response['body']['data']['productCreate']['product']['id'];
            foreach ($request->variant_name as $key => $variant_name) {
                $queryVariants = 'mutation {
                    productVariantCreate(input:
                    {
                        productId: "' . $graphProductId . '",
                        price: "' . $request->variant_price[$key] . '",
                        requiresShipping: true,
                        options: "' . $variant_name . '",
                        inventoryItem: {
                            cost: 50,
                            tracked: true
                        },
                        inventoryQuantities: {
                            availableQuantity: ' . $request->stock[$key] . ',
                            locationId: "gid://shopify/Location/64814579911"
                        }
                    }
                    ) {
                        productVariant {
                            id
                        }
                    }
                }';
                $variantResponse = $api->graph($queryVariants);
            }
        }
        if ($response) {
            return redirect()->route('product');
        } else {
            return response()->json(['error' => 'Failed to fetch products'], 500);
        }
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $imageUrls = $request->media;
        if ($imageUrls) {
            $filePath = $request->media->store('public/images');
            $imageUrlFromRequest = asset(str_replace('public', 'storage', $filePath));
            $mediaBlocks = [];
            foreach ($imageUrls as $imageUrl) {
                $mediaBlocks[] = [
                    'alt' => 'no img',
                    'mediaContentType' => 'IMAGE',
                    'originalSource' => $imageUrl
                ];
            }
        } else {
            $mediaBlocks = null;
        }
        $mediaBlockString = json_encode($mediaBlocks);
        $tag = explode(",", $request->tags);
        $tags = implode('", "', $tag);
        $tagsString = '"' . $tags . '"';
        $shop_name = env('SHOPIFY_NAME');
        $api = ShopifyAPIClient($shop_name);
        $query = 'mutation {
            productUpdate(input: {
                id: "gid://shopify/Product/' . $id . '",
                title: "' . $request->title . '"
                productType: "' . $request->product_type . '"
                vendor: "' . $request->vendor . '"
                tags: [' . $tagsString . '],
                descriptionHtml: "' . $request->description . '"
            }
            ) {product {id}}
            }';
        $response = $api->graph($query);
        if ($response != null) {
            foreach ($request->variant_name as $key => $variant_name) {
                $queryVariants = 'mutation {
                    productVariantUpdate(input:
                    {
                        id: "gid://shopify/ProductVariant/' . $request->variant_id[$key] . '",
                        price: "' . $request->variant_price[$key] . '",
                        requiresShipping: true,
                        options: "' . $variant_name . '",
                        inventoryItem: {
                            cost: 50,
                            tracked: true
                        },
                        inventoryQuantities: {
                            availableQuantity: ' . $request->stock[$key] . ',
                            locationId: "gid://shopify/Location/64814579911"
                        }
                    }
                    ) {
                        productVariant {
                            id
                        }
                    }
                }';
                $variantResponse = $api->graph($queryVariants);
            }
        }
        if ($response) {
            return redirect()->back();
        } else {
            return response()->json(['error' => 'Failed to fetch products'], 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $shop_name = env('SHOPIFY_NAME');
        $api = ShopifyAPIClient($shop_name);
        $query = <<<QUERY
                mutation {
                    productDelete(input: {id: "gid://shopify/Product/$id"}) {
                    deletedProductId
                    }
                }
                QUERY;
        $variantResponse = $api->graph($query);
        Product::where('shopify_id',$id)->first()->delete();
        return redirect()->back();
    }
}
