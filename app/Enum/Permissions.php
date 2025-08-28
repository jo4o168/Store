<?php

namespace App\Enum;

enum Permissions: string
{
    case PRODUCT_LIST = 'product_list';
    case PRODUCT_STORE = 'product_store';
    case PRODUCT_SHOW = 'product_show';
    case PRODUCT_UPDATE = 'product_update';
    case PRODUCT_DESTROY = 'product_destroy';

}
