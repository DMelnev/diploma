<?php


namespace App\Service;


interface SortConst
{
    const SORT_RANK = 'sort_rank';
    const SORT_PRICE = 'sort_price';
    const SORT_COMMENT = 'sort_comment';
    const SORT_NEW = 'sort_new';
    const DESC = 'desc';
    const ASC = 'asc';
    const EMPTY = 'off';

    const FILTER_MIN_PRICE = 'min_price';
    const FILTER_MAX_PRICE = 'max_price';
    const FILTER_FROM_PRICE = 'from_price';
    const FILTER_TO_PRICE = 'to_price';
    const FILTER_RANGE = 'range';
    const FILTER_TITLE = 'title';
    const FILTER_SELLER_ = 'seller';
    const FILTER_MEMORY_VALUE = 'memoryValue';
    const FILTER_SECTION = 'section';

     const   FILTER_TEMP = 'Объем памяти';
}