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
 * Class EntitiesCollection
 *
 * @package CodeInc\EntitiesCollection
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class EntitiesCollection extends AbstractEntitiesCollection
{
    /**
     * @var string[]|null
     */
    private $allowedEntityClasses;

    /**
     * AbstractCollection constructor.
     *
     * @param iterable $entities
     * @param array $allowedEntitiesClass Defines the allowed entity classes.
     */
    public function __construct(?iterable $entities = null, ?array $allowedEntitiesClass = null)
    {
        $this->allowedEntityClasses = $allowedEntitiesClass;
        parent::__construct($entities);
    }

    /**
     * Verifies if a class is allowed here.
     *
     * @param EntityInterface $entity
     * @return bool
     */
    protected function isEntityAllowed(EntityInterface $entity):bool
    {
        if (!$this->allowedEntityClasses) {
            return true;
        }
        else {
            $entityClass = get_class($entity);
            foreach ($this->allowedEntityClasses as $allowedEntityClass) {
                if ($entityClass == $allowedEntityClass || is_subclass_of($entity, $allowedEntityClass)) {
                    return true;
                }
            }
            return false;
        }
    }
}