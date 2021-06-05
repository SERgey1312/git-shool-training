<?php


namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TableOfCourseManager extends AbstractController
{
    private $courseID;
    private $courseTheme;
    private $courseName;
    private $courseOwner;

    public function initializeParams(array $paramsArray) :void
    {
        $this->courseName = array_key_exists('name', $paramsArray) ? $paramsArray['name'] : "";
        $this->courseTheme = array_key_exists('theme', $paramsArray) ? $paramsArray['theme'] : "";
        $this->courseID = array_key_exists('id', $paramsArray) ? $paramsArray['id'] : "";
        $this->courseOwner = array_key_exists('owner', $paramsArray) ? $paramsArray['owner'] : "";
    }

    public function queryID(QueryBuilder $queryBuilder):QueryBuilder
    {
        if(strlen($this->courseID) > 0){
            $queryBuilder
                ->andWhere('course.id = :id')
                ->setParameter('id', $this->courseID)
            ;
        }
        return $queryBuilder;

    }

    public function queryOwnerID(QueryBuilder $queryBuilder):QueryBuilder
    {
        if(strlen($this->courseOwner) > 0){
            $queryBuilder
                ->andWhere('course.owner_id = :owner_id')
                ->setParameter('owner', $this->courseOwner)
            ;
        }
        return $queryBuilder;

    }

    public function queryName(QueryBuilder $queryBuilder):QueryBuilder
    {
        if(strlen($this->courseName) > 0){
            $queryBuilder
                ->andWhere('course.name LIKE :name')
                ->setParameter('name', '%'.$this->courseName.'%')
            ;
        }
        return $queryBuilder;
    }

    public function queryTheme(QueryBuilder $queryBuilder):QueryBuilder
    {
        if(strlen($this->courseTheme) > 0){
            $queryBuilder
                ->andWhere('course.theme LIKE :theme')
                ->setParameter('theme', '%'.$this->courseTheme.'%')
            ;
        }
        return $queryBuilder;
    }
}