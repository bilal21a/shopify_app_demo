<?php

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Gnikyt\BasicShopifyAPI\BasicShopifyAPI;
use Gnikyt\BasicShopifyAPI\Options;
use Gnikyt\BasicShopifyAPI\Session;

function ShopifyAPIClient($shop)
{
    $options = new Options();
    $options->setVersion(env('SHOPIFY_API_VERSION'));
    $user = User::where("name", $shop)->first();
    $accessToken = env('SHOPIFY_ACCESS_TOKEN');
    $api = new BasicShopifyAPI($options);
    $api->setSession(new Session($shop, $accessToken));
    return $api;
}
function fetchNextCollection($api, $query, $endCursor = null)
{
    $pagination = null;
    if ($endCursor) {
        $pagination = ', after: "' . $endCursor . '"';
    }
    $nextQuery = '
    query {
        collections(first: 5' . $pagination . ') {
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
    return $api->graph($nextQuery);
}
function fetchNextProduct($api, $query, $endCursor = null)
{
    $pagination = null;
    if ($endCursor) {
        $pagination = ', after: "' . $endCursor . '"';
    }
    $nextQuery = <<<GRAPHQL
    query {
        products(first: 250$pagination) {
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
                                weight
                                weightUnit
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
    return $api->graph($nextQuery);
}
function saveCollection($request)
{
    $query = 'mutation {
        collectionCreate(input: {
            title: "' . $request->title . '",
            descriptionHtml: "' . $request->description . '"
        }) {
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
    return $query;
}
function saveSmartCollection($request)
{
    $query = 'mutation {
        collectionCreate(input: {
            title: "' . $request->title . '",
            descriptionHtml: "' . $request->description . '",
            ruleSet: {
                appliedDisjunctively: false,
                rules:[';
    $rulesCount = count($request->column);
    for ($i = 0; $i < $rulesCount; $i++) {
        $query .= '{column: ' . $request->column[$i] . ', relation: ' . $request->relation[$i] . ', condition: "' . $request->condition[$i] . '"}';
        if ($i < $rulesCount - 1) {
            $query .= ',';
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
                }}';
    return $query;
}
