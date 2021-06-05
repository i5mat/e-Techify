<?php


namespace App\QueryFilters;
use Closure;

class ProductBrand extends Filter
{
    protected function applyFilter($builder)
    {
        // TODO: Implement applyFilter() method.
        return $builder->where('product_brand', \request($this->filterName()));
    }
}
