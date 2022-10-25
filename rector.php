<?php

use Rector\Set\ValueObject\DowngradeLevelSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        DowngradeLevelSetList::DOWN_TO_PHP_80,
        DowngradeLevelSetList::DOWN_TO_PHP_74,
        DowngradeLevelSetList::DOWN_TO_PHP_73
    ]);
};
