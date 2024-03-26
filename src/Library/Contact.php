<?php

namespace Phonebook\Library;

class Contact extends Database
{
    protected $item;
    protected $table = "contacts";

    function find($value, $key = "id")
    {
        $this->item = $this->where([$key => $value], true);

        return $this->item;
    }

    function create(array $data)
    {
        return $this->insert($data);
    }

    function updateItem(array $data)
    {
        return $this->update($data, $this->id);
    }

    function getItemBy(array $conditions, $single = true)
    {
        $this->item = $this->where($conditions, $single);

        return $this;
    }
}