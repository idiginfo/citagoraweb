<?php

namespace Citagora\Common\EntityManager;

use ReflectionClass, Exception;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Events;
use Doctrine\ODM\MongoDB\LockMode;

/**
 * Manages a collection of Entities
 *
 * Abstraction layer around the Doctrine ODM DocumentRepository
 */
abstract class Collection
{
    /**
     * @var Doctrine\ODM\MongoDB\DocumentManager
     */
    private $dm;

    /**
     * @var string
     */
    private $className;

    // --------------------------------------------------------------

    /**
     * Return classname for an entity
     *
     * Attempts to guess the classname for the entity based on the
     * name of the collection class
     *
     * e.g. UserCollection would return User; DocumentCollection would return Document
     *
     * @return string
     */
    public function getEntityClassName()
    {
        //Get the classname without namespace prefix
        $classname = join('', array_slice(explode('\\', get_called_class()), -1));

        //Return it
        if (strcasecmp(substr($classname, -10), 'Collection') == 0) {
            return substr($classname, 0, -10);
        }
        else {
            throw new Exception(sprintf(
                "Could not resolve entity classname for collection: %s.  This
                usually means that you either named the collection class inappropriately
                or forgot to override the getEntityClassName() method",
                get_called_class()
            ));
        }
    }

    // --------------------------------------------------------------

    /**
     * Connector
     *
     * @param Doctrine\ODM\MongoDB\DocumentManager $dm
     */
    public function connect(Manager $em)
    {
        $this->dm        = $em->getDocumentManager();
        $this->className = $em->resolveClassName($this->getEntityClassName());

        //Only allow real classes
        if ( ! class_exists($this->className)) {
            throw new Exception("The class " . $this->className . " does not exist");
        }

        //Only allow entities
        if ( ! is_subclass_of($this->className, __NAMESPACE__ . '\Entity')) {
            throw new Exception("The class " . $this->className . " must be a valid entity");
        }

        //Post load
        $emgr = $this->dm->getEventManager();
        if (method_exists($this, 'postLoadEntity')) {
            $emgr->addEventListener(Events::postLoad, $this);
        }
    }

    // --------------------------------------------------------------

    /** 
     * Factory method for the entity
     *
     * @param array $params
     */
    public function factory($params = array())
    {
        if (count($params) > 0) {
            $refl = new ReflectionClass($this->className);
            return $refl->newInstanceArgs($params);
        }
        else {
            return new $this->className;
        }    
    }

    // --------------------------------------------------------------

    // Shortcut methods for repository

    /**
     * @param string|object $id The identifier
     * @param int $lockMode
     * @param int $lockVersion
     * @return object The document.
     */
    public function find($id, $lockMode = LockMode::NONE, $lockVersion = null)
    {
        return $this->getRepository()->find($id, $lockMode, $lockVersion);
    }

    // --------------------------------------------------------------

    public function findOneBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    // --------------------------------------------------------------

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    // --------------------------------------------------------------

    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    // --------------------------------------------------------------

    /**
     * Save the record
     *
     * @param Entity $record
     * @param boolean $flush
     */
    public function save(Entity $record, $flush = true)
    {
        //Persist the record
        $this->dm->persist($record);

        //Flush it?
        if ($flush) {
            $this->dm->flush();
        }
    }

    // --------------------------------------------------------------

    /**
     * Save many records
     *
     * @param array|Iterator  Any iterable array or object with entities
     */
    public function saveMany($records)
    {
        foreach($records as $rec) {
            $this->save($rec, false);
        }

        $this->dm->flush();
    }

    // --------------------------------------------------------------

    /**
     * Get the repository
     *
     * @return Doctrine\ODM\MongoDB\Repository
     */
    public function getRepository()
    {
        return $this->dm->getRepository($this->className);   
    }

    // --------------------------------------------------------------

    /**
     * @return Doctrine\ODM\MongoDB\QueryBuilder
     */
    public function getQueryBuilder()
    {
        return $this->dm->createQueryBuilder($this->className);
    }   

    // --------------------------------------------------------------

    /**
     * Post load calls child postLoad method
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        if (get_class($eventArgs->getDocument()) == $this->className) {
            $this->postLoadEntity($eventArgs->getDocument());
        }

        
    }    
}

/* EOF: EntityCollection.php */