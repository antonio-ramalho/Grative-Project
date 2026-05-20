<?php

return [
    'connection' => getenv('DB_CONNECTION') ?: 'sqlite',
    'database' => getenv('DB_DATABASE') ?: 'storage/database.sqlite',
];