<?php

/**
 * Class Product
 */
class Product extends AbstractModel
{
    /**
     * @var int
     */
	public $id;

    /**
     * @var string
     */
	public $name;

    /**
     * @var string
     */
	public $description;

    /**
     * @var string
     */
	public $price;

    /**
     * Product constructor.
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getPrice(): string
    {
        return $this->price;
    }

    /**
     * @param string $price
     */
    public function setPrice(string $price): void
    {
        $this->price = $price;
    }
}
