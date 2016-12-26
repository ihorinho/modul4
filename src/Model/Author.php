<?php
/**
 * Created by PhpStorm.
 * User: ihorinho
 * Date: 12/25/16
 * Time: 6:38 PM
 */
namespace Model;

class Author{

    private $id;
    private $firstName;
    private $lastName;
    private $dateBirth;
    private $dateDeath;

    /**
     * @param mixed $dateBirth
     */
    public function setDateBirth($dateBirth)
    {
        $this->dateBirth = $dateBirth;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateBirth()
    {
        return $this->dateBirth;
    }

    /**
     * @param mixed $dateDeath
     */
    public function setDateDeath($dateDeath)
    {
        $this->dateDeath = $dateDeath;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateDeath()
    {
        return $this->dateDeath;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }
}