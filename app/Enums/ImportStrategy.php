<?php

namespace App\Enums;

enum ImportStrategy: string
{
    case OVERWRITE = 'overwrite';
    case SKIP = 'skip';
    case DUPLICATE = 'duplicate';
    case REJECT = 'reject';
}
