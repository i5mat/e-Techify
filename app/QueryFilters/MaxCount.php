<?php


namespace App\QueryFilters;


class MaxCount extends Filter
{
    protected function applyFilter($builder)
    {
        // TODO: Implement applyFilter() method.
        return $builder->take(\request($this->filterName()));
    }
}
