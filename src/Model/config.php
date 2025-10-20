<?php

namespace App\Model;

class Config
{
    public function access(): array
    {
        return  ['db' => 
        ['user'=>'root',
         'dsn'=>'localhost/3306',
         'pass'=>'', 
         'options'=>''],
         'storage' => '..\storage\tasks.json', 
         'repository' => 'mysql | file | memory' ];
    }
}