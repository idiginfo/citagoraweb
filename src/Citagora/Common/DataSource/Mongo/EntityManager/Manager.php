<?php

namespace Citagora\Common\DataSource\Mongo\EntityManager;

use Doctrine\ODM\MongoDB\DocumentManager;
use RuntimeException;

class Manager
{
    /**
     * @var Doctrine\ODM\MongoDB\DocumentManager
     */
    private $dm;

    /**
     * @var array  Array of Collection objects
     */
    private $collections;

    /**
     * @var string  A default namespace to use
     */
    private $defaultNamespace;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Doctrine\ODM\MongoDB\DocumentManager $dm
     * @param string $namespace                    The fully-qualified namespace where entities are
     */
    public function __construct(DocumentManager $dm, $entityNamespace = null)
    {
        $this->dm = $dm;
        $this->defaultNamespace = rtrim($entityNamespace, "\\");
    }


    // --------------------------------------------------------------

    /**
     * Add an entity collection
     *
     * @param Collection $collection
     */
    public function addCollection(Collection $collection)
    {
        //Connect the collection to the manager
        $collection->connect($this);
        $className = $collection->getEntityClassName();

        //Add it
        $this->collections[$className] = $collection;
    }

    // --------------------------------------------------------------

    /**
     * Get a collection to work with based on the entity classname
     *
     * Will build a generic collection if a collection object for this
     * entity has not been registered
     *
     * @param string  $entityClassName
     * @return Collection
     * @throws RuntimeException
     */
    public function getCollection($entityClassName)
    {
        if (isset($this->collections[$entityClassName])) {
            return $this->collections[$entityClassName];
        }
        else {

            //Try/Catch to build a better error message in class of non-valid entity class
            try {
                $className = $this->resolveClassName($entityClassName);
                $collection = new GenericCollection($className);
                $collection->connect($this);
                return $collection;
            }
            catch (RuntimeException $e) {
                throw new RuntimeException("Could not find or build the entity 
                    collection for entity with classname: " . $entityClassName);
            }
        }
    }

    // --------------------------------------------------------------

    /**
     * Get the document manager
     *
     * @return Doctrine\ODM\MongoDB\DocumentManager
     */
    public function getDocumentManager()
    {
        return $this->dm;
    }

    // --------------------------------------------------------------

    /**
     * Resolve and normalize the classname
     *
     * If classname doesn't exist, throw exception
     *
     * @param string $className
     * @return string
     * @throws RuntimeException
     */
    public function resolveClassName($className)
    {
        if (class_exists($className)) {
            return $className;
        }
        elseif (class_exists($this->defaultNamespace . "\\" . $className)) {
            return $this->defaultNamespace . "\\" . $className;
        }
        else {
            throw new RuntimeException("Cannot find entity class: " . $className);
        }
    }
}

/* EOF: Manager.php */