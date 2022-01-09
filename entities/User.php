<?php

/*
 * Сущность пользователя для дальнейшей сериализации
 */

class User
{
    public $id;
    public $name;
    public $username;
    public $password;
    public $description;
    public $email;
    public $validated = false;

    /**
     * @param string|null $name
     * @param string|null $username
     * @param string|null $password
     * @param string|null $description
     * @param string|null $email
     * @param $validated
     */
    public function __construct(string $name = null, string $username = null, string $password = null, string $description = null, string $email = null, $validated = 0)
    {
        $this->name = $name;
        $this->username = $username;
        $this->password = $password;
        $this->description = $description;
        $this->email = $email;
        $this->validated = $validated;
    }

    public function __toString(): string
    {
        return "id=" . $this->id .
            ";name=" . $this->name .
            ";username=" . $this->username .
            ";password=" . $this->password .
            ";=description" . $this->description .
            ";email=" . $this->email .
            ";validated=" . $this->validated;
    }


}