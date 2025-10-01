<?php

namespace App\Core\Entities\Card;

class CategoryEntity
{
    public $id;
    public $name;

    public function __construct(string $name, int $id = null)
    {
        $this->id = $id;
        $this->name = $name;
    }
}