<?php


namespace test;


class Student
{
    private  $id = 0;
    private  $name;
    private  $age = 0;
    private $map;
    private $teachers;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param int|null $age
     */
    public function setAge(?int $age): void
    {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param mixed $map
     */
    public function setMap($map): void
    {
        $this->map = $map;
    }

    /**
     * @return mixed
     */
    public function getTeachers()
    {
        return $this->teachers;
    }

    /**
     * @param mixed $teachers
     */
    public function setTeachers($teachers): void
    {
        $this->teachers = $teachers;
    }







}