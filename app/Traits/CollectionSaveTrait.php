<?php

namespace App\Traits;

use App\Models\Collection;
use App\Models\Image;
use App\Models\Rule;

trait CollectionSaveTrait
{
    public function SaveData($collectionData)
    {
        $user_id = 1;
        $id = $collectionData['node']['id'];
        $numericId = substr($id, strrpos($id, '/') + 1);
        $collection_check = Collection::where('shopify_id', $numericId)->first();
        if ($collection_check) {
            $collection = $collection_check;
        } else {
            $collection = new Collection();
        }
        $collection->shopify_id = $numericId;
        $collection->user_id = $user_id;
        $collection->title = $collectionData['node']['title'];
        $collection->handle = $collectionData['node']['handle'];
        $collection->shopify_updated_at = $collectionData['node']['updatedAt'];
        $collection->sort_order = $collectionData['node']['sortOrder'];
        $collection->description = $collectionData['node']['description'];
        $collection->description_html = $collectionData['node']['descriptionHtml'];
        $collection->template_suffix = $collectionData['node']['templateSuffix'];
        $collection->products_count = $collectionData['node']['productsCount'] != null ? $collectionData['node']['productsCount']['count'] : "";
        if ($collectionData['node']['ruleSet']) {
            $collection->applied_disjunctively = $collectionData['node']['ruleSet']['appliedDisjunctively'];
            if ($collectionData['node']['ruleSet']['rules']) {
                foreach ($collectionData['node']['ruleSet']['rules'] as $key => $rule) {
                    $rule_check = Rule::where('rules_condition', $rule['condition'])->where('shopify_collection_id',$numericId)->first();
                    if ($rule_check) {
                        $rule_save = $rule_check;
                    } else {
                        $rule_save = new Rule();
                    }
                    $rule_save->shopify_collection_id =  $numericId;
                    $rule_save->user_id = $user_id;
                    $rule_save->rules_column = $rule['column'];
                    $rule_save->rules_relation = $rule['relation'];
                    $rule_save->rules_condition = $rule['condition'];
                    $rule_save->save();
                }
            }
        }

        $collection->save();

        if ($collectionData['node']['image']) {
            $id_img = $collectionData['node']['image']['id'];
            $numericIdimg = substr($id_img, strrpos($id_img, '/') + 1);
            $image_save = Image::where('shopify_id', $numericIdimg)->first();
            if (!$image_save) {
                $image_save = new Image();
            }else{
                $image_save = $image_save;
            }
            $image_save->user_id = $user_id;
            $image_save->shopify_id = $numericIdimg;
            $image_save->product_id = null;
            $image_save->shopify_product_id = null;
            $image_save->shopify_collection_id = $numericId;
            $image_save->alt =$collectionData['node']['image']['altText'];
            $image_save->height = $collectionData['node']['image']['height'];
            $image_save->width = $collectionData['node']['image']['width'];
            $image_save->src = $collectionData['node']['image']['url'];
            $image_save->save();
        }
    }
}
