<?php

/**
 * Copyright (C) 2023 Dominik Szamburski
 *
 * This file is part of nulldark\entity-manager
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace Nulldark\EntityManager;

use Nulldark\EntityManager;
use Nulldark\EntityManager\Metadata\AttributeReader;
use Nulldark\EntityManager\Metadata\Reflection\RuntimeReflection;

/**
 * @author Dominik Szamburski
 * @package Metadata
 * @license MIT
 * @version 0.1.0
 */
final class EntityMetadataFactory
{
    /** @var array<string, EntityMetadata> $loadedMetadata */
    private array $loadedMetadata;

    /** @var AttributeReader $reader */
    private AttributeReader $reader;

    public function __construct()
    {
        $this->reader = new AttributeReader();
    }

    /**
     * @param string $className
     * @return EntityMetadata
     */
    public function loadClassMetadataFor(string $className): EntityMetadata
    {
        $className = $this->normalizeClassname($className);

        if (isset($this->loadedMetadata[$className])) {
            return $this->loadedMetadata[$className];
        }

        $this->loadMetadata($className);

        return $this->loadedMetadata[$className];
    }

    /**
     * @param string $className
     * @return string
     */
    private function normalizeClassname(string $className): string
    {
        return ltrim($className, '\\');
    }

    /**
     * @param string $class
     * @return EntityMetadata
     */
    private function newClassMetadataInstance(string $class): EntityMetadata
    {
        return new EntityMetadata(
            $class
        );
    }

    /**
     * @param string $className
     * @return void
     */
    private function loadMetadata(string $className): void
    {
        $reflService = new RuntimeReflection();

        $parentClasses = $reflService->getParentClasses($className);
        $parentClasses[] = $className;

        foreach ($parentClasses as $parentClass) {
            $class = $this->newClassMetadataInstance($parentClass);
            $class->initializeReflection($reflService);

            $this->doLoadMetadata($class);
            $this->loadedMetadata[$parentClass] = $class;
        }
    }

    /**
     * @param EntityMetadata $class
     * @return void
     */
    private function doLoadMetadata(EntityMetadata $class): void
    {
        $reflectionClass = $class->getReflectionClass();
        $classAttributes = $this->reader->getClassAttributes($reflectionClass);

        $primaryTable = [];
        if (isset($classAttributes[EntityManager\Mapping\Table::class])) {
            $table = $classAttributes[EntityManager\Mapping\Table::class];

            $primaryTable['name'] = $table->name;
            $primaryTable['schema'] = $table->schema;
        }

        $class->setPrimaryTable($primaryTable);

        foreach ($reflectionClass->getProperties() as $property) {
            $columnAttribute = $this->reader->getPropertyAttribute($property, EntityManager\Mapping\Column::class);

            if ($columnAttribute !== null) {
                $mapping = $this->columnToArray($property->name, $columnAttribute);

                if ($this->reader->getPropertyAttribute($property, EntityManager\Mapping\Column::class)) {
                    $mapping['id'] = true;
                }

                $class->fieldMapping($mapping);
            }
        }
    }

    /**
     * @param string $fieldName
     * @param \Nulldark\EntityManager\Mapping\Column $column
     * @return array
     */
    private function columnToArray(string $fieldName, EntityManager\Mapping\Column $column): array
    {
        $mapping = [
            'fieldName' => $fieldName,
            'type' => $column->type,
            'length' => $column->length,
            'unique' => $column->unique,
            'nullable' => $column->nullable
        ];

        return $mapping;
    }
}
