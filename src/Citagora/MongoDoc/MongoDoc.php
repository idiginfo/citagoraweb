<?php

namespace Citagora\MongoDoc;

abstract class MongoDoc
{    
    // --------------------------------------------------------------

    /**
     * Get Magic Method
     *
     * @return mixed
     */
    public function __get($item)
    {
        return $this->$item;
    }

    // --------------------------------------------------------------
    
    /**
     * Magic __tostring() method for serializing
     *
     * Useful for using array functions on entity objects
     */
    public function __tostring()
    {
        return json_encode($this->toArray());
    }

    // --------------------------------------------------------------
    
    /**
     * Return an array representation of the entity
     *
     * @return array
     */
    public function toArray($includeId = true)
    {
        $ref = new \ReflectionObject($this);
        $properties = $ref->getProperties();

        $arr = array();
        foreach ($properties as $prop) {

            $annots = $prop->getDocComment();
            if (stripos($annots, "@ODM\\")) {

                if (stripos($annots, '@ODM\Id') && ! $includeId) {
                    continue;
                }

                $prop->setAccessible(true);
                $arr[$prop->getName()] = $this->__get($prop->getName());
            }            
        }

        return $arr;
    }
}
/* EOF: AbstractMongoDoc.php */