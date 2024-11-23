<?php

declare(strict_types=1);

namespace App\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Api\Action\ImportCsvAction;
use App\Doctrine\Enum\RoleEnum;

#[ApiResource]
#[Post(
    uriTemplate: '/import-csv',
    controller: ImportCsvAction::class,
    security: RoleEnum::IS_GRANTED_ADMIN,
    deserialize: false
)]
class ImportCsv
{
}
