<?php

namespace App\Enum;

enum StorageTypeEnum: string
{
    case CSV = 'csv';
    case DB = 'db';
    case ALL = 'all';
}
