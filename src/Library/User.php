<?php

namespace Phonebook\Library;

class User extends Database
{
    protected $table = "users";

    function getProfilePictureUrl()
    {
        return 'https://ui-avatars.com/api/?name=' . $this->name;
    }

    function find($value, $key = "id")
    {
        $this->item = $this->where([$key => $value], true);

        return $this->item;
    }

    function create(array $data)
    {
        $data['password'] = md5($data['password']);
        return $this->insert($data);
    }

    function updateUser(array $data)
    {
        if ($data['password'] ?? false) {
            $data['password'] = md5($data['password']);
        }
        return $this->update($data, $this->id);
    }

    function getUserBy(array $conditions, $single = true)
    {
        $this->item = $this->where($conditions, $single);

        return $this;
    }

    static function getAuthUser()
    {
        $obj = new static;

        return $obj->getUserBy([
            'id' => session()->authId()
        ], true);
    }

    function toArray()
    {
        return $this->item;
    }
}
