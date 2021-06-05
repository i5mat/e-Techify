<?php


namespace App\QueryFilters;
use Closure;

class ProductPrice extends Filter
{
    protected function applyFilter($builder)
    {
        // TODO: Implement applyFilter() method.
        return $builder->orderBy('product_price', \request($this->filterName()));
    }
}
