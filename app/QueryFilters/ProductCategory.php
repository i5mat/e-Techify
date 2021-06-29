<?php


namespace App\QueryFilters;


class ProductCategory extends Filter
{
    protected function applyFilter($builder)
    {
        // TODO: Implement applyFilter() method.
        return $builder->where('product_category', \request($this->filterName()));
    }
}
