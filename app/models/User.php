<?php

/**
 * Class User
 */
class User extends AbstractModel
{
    /**
     * @var int
     */
	public $id;

    /**
     * @var string
     */
	public $email;

    /**
     * @var string
     */
	public $password;

    /**
     * User constructor.
     */
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
