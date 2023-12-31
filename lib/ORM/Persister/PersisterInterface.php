<?php

/**
 * Copyright (c) 2023 Dominik Szamburski
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Nulldark\ORM\Persister;

use Nulldark\ORM\Hydrator\HydratorInterface;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Nulldark\ORM\Persister
 * @since 0.1.0
 */
interface PersisterInterface
{
    /**
     * Gets an entity by a list of field criteria.
     *
     * @param array<string, mixed>  $criteria
     * @param object|null           $entity
     * @psalm-param object|null          $entity
     *
     * @return object|null
     */
    public function load(array $criteria, object $entity = null): ?object;

    /**
     * Gets a list of entities by a list of field criteria.
     *
     * @param array<string, mixed> $criteria
     *
     * @return object[]
     * @psalm-return list<object>
     */
    public function loadAll(array $criteria): array;

    /**
     * Returns a hydrator instance.
     *
     * @return HydratorInterface
     */
    public function getEntityHydrator(): HydratorInterface;
}
