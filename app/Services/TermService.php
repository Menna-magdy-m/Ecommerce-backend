<?php

namespace App\Services;

use App\Models\ProductVariationAttribute;
use App\Models\Taxonomy;
use App\Models\Term;
use App\Models\TermTaxonomy;
use Illuminate\Database\Eloquent\Builder;

class TermService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = ['name'];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = ['name'];

    protected array $searchables = ['term_id'];


    public function builder(): Builder
    {
        return Term::query();
    }

    protected function prepare(string $operation, array $attributes): array
    {
        return parent::prepare($operation, $attributes);
    }

    // get term with name
    public function getTermByName(string $term)
    {
        return Term::query()->where('name', '=', $term)->first();
    }

    public function addColorsToTerms(string $color, int $product_id)
    {
        // check if the term existed
        $term_color = $this->getTermByName($color);

        // If the term not existed => create a new term
        if (!$term_color) {
            // 1- create term with the color
            $term = Term::query()->create([
                'name' => $color,
                'slug' => $color
            ]);
            // 2- create taxonomy with [ term_id => term->id, taxonomy => ba_color ]
            $taxonomy = Taxonomy::query()->create([
                'term_id' => $term['term_id'],
                'taxonomy' => 'pa_color',
                'description' => 'color'
            ]);
            // 3- create attribute lookup
            $look_attribute = ProductVariationAttribute::query()->create([
                'product_id' => $product_id,
                'product_or_parent_id' => $product_id,
                'taxonomy' => 'pa_color',
                'term_id' => $term['term_id'],
                'is_variation_attribute' => 1,
                'in_stock' => 1
            ]);
        } else {
            // If the term exist ==> we will create attribute look up with term id
            // create attribute lookup
            $look_attribute = ProductVariationAttribute::query()->create([
                'product_id' => $product_id,
                'product_or_parent_id' => $product_id,
                'taxonomy' => 'pa_color',
                'term_id' => $term_color['term_id'],
                'is_variation_attribute' => 1,
                'in_stock' => 1
            ]);
        }

        return $look_attribute;
    }

    public function addSizesToTerms(string $size, int $product_id)
    {
        // check if the term existed
        $term_size = $this->getTermByName($size);

        if (!$term_size) {
            // 1- create term with the color
            $term = Term::query()->create([
                'name' => $size,
                'slug' => $size
            ]);
            // 2- create taxonomy with [ term_id => term->id, taxonomy => ba_color ]
            $taxonomy = Taxonomy::query()->create([
                'term_id' => $term['term_id'],
                'taxonomy' => 'pa_size',
                'description' => 'color'
            ]);
            // 3- create attribute lookup
            $look_attribute = ProductVariationAttribute::query()->create([
                'product_id' => $product_id,
                'product_or_parent_id' => $product_id,
                'taxonomy' => 'pa_size',
                'term_id' => $term['term_id'],
                'is_variation_attribute' => 1,
                'in_stock' => 1
            ]);

        } else {
            // If the term exist ==> we will create attribute look up with term id
            // create attribute lookup
            $look_attribute = ProductVariationAttribute::query()->create([
                'product_id' => $product_id,
                'product_or_parent_id' => $product_id,
                'taxonomy' => 'pa_size',
                'term_id' => $term_size['term_id'],
                'is_variation_attribute' => 1,
                'in_stock' => 1
            ]);
        }

        return $look_attribute;
    }
}
