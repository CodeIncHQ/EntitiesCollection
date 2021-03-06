<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2018 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material is strictly forbidden unless prior    |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     10/08/2018
// Project:  EntitiesCollection
//
declare(strict_types=1);
namespace CodeInc\EntitiesCollection;
use CodeInc\EntityInterface\EntityInterface;


/**
 * Class AbstractEntitiesCollection
 *
 * @package CodeInc\EntitiesCollection
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractEntitiesCollection implements \Countable, \Iterator
{
    /**
     * @var EntityInterface[]
     */
    protected $entities = [];

    /**
     * @var array|null
     */
    private $iteratorIndex;

    /**
     * @var int|null
     */
    private $iteratorPosition;

    /**
     * AbstractCollection constructor.
     *
     * @param iterable $entities
     */
    public function __construct(?iterable $entities = null)
    {
        if ($entities !== null) {
            $this->addMultiple($entities);
        }
    }

    /**
     * Returns all the ids of the entities in the current collection.
     *
     * @return string[]|int[]
     */
    public function getEntitiesId():array
    {
        return array_keys($this->entities);
    }

    /**
     * Verifies if a class is allowed here.
     *
     * @param EntityInterface $entity
     * @return bool
     */
    abstract protected function isEntityAllowed(EntityInterface $entity):bool;

    /**
     * Adds an entity to the collection.
     *
     * @param EntityInterface $entity
     */
    public function add(EntityInterface $entity):void
    {
        if (!$this->isEntityAllowed($entity)) {
            throw new \RuntimeException(
                sprintf("The entity class '%s' can not be added to the collection '%s'",
                    get_class($entity), get_called_class())
            );
        }
        $this->entities[$entity->getId()] = $entity;
    }

    /**
     * Adds multiple entities.
     *
     * @param iterable $entities
     */
    public function addMultiple(iterable $entities):void
    {
        foreach ($entities as $entity) {
            if (!$entity instanceof EntityInterface) {
                throw new \RuntimeException(sprintf("All entities added to %s must implement %s",
                    get_called_class(), EntityInterface::class));
            }
            $this->add($entity);
        }
    }

    /**
     * Removes an entity from the collection.
     *
     * @param EntityInterface $entity
     */
    public function remove(EntityInterface $entity):void
    {
        if ($this->isEntityAllowed($entity)) {
            unset($this->entities[$entity->getId()]);
        }
    }

    /**
     * Verifies if the collection contains an entity.
     *
     * @param EntityInterface $entity
     * @return bool
     */
    public function contains(EntityInterface $entity):bool
    {
        if ($this->isEntityAllowed($entity)) {
            return array_key_exists($entity->getId(), $this->entities);
        }
        return false;
    }

    /**
     * Verifies if the collection is empty.
     *
     * @return bool
     */
    public function isEmpty():bool
    {
        return empty($this->entities);
    }

    /**
     * Counts the entities.
     *
     * @return int
     */
    public function count():int
    {
        return count($this->entities);
    }

    /**
     * Clears the collection.
     */
    public function clear():void
    {
        $this->entities = [];
    }

    /**
     * @inheritdoc
     */
    public function rewind():void
    {
        $this->iteratorIndex = array_keys($this->entities);
        $this->iteratorPosition = 0;
    }

    /**
     * @inheritdoc
     * @return EntityInterface
     */
    public function current():EntityInterface
    {
        return $this->entities[$this->iteratorIndex[$this->iteratorPosition]];
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function key():string
    {
        return (string)$this->iteratorIndex[$this->iteratorPosition];
    }

    /**
     * @inheritdoc
     */
    public function next():void
    {
        $this->iteratorPosition++;
    }

    /**
     * @inheritdoc
     * @return bool
     */
    public function valid():bool
    {
        return isset(
            $this->iteratorIndex[$this->iteratorPosition],
            $this->entities[$this->iteratorIndex[$this->iteratorPosition]]
        );
    }
}