<?php

namespace App\Prototype;

class ShoeRegistry
{
    private array $items = [];

    public function addItem($id, ShoePrototype $shoe)
    {
        $this->items[$id] = $shoe;
    }

    public function getById($id)
    {
        return $this->items[$id]->cloneShoe();
    }
}