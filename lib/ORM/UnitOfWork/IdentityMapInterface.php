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

namespace Nulldark\ORM\UnitOfWork;

use BackedEnum;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Nulldark\ORM\UnitOfWork
 * @since 0.1.0
 */
interface IdentityMapInterface
{
    /**
     * Puts a entity into identity map.
     *
     * @param mixed   $identifier
     * @param object  $entity
     * @psalm-param T $entity
     *
     * @return bool
     *
     * @template T of object
     */
    public function put(mixed $identifier, object $entity): bool;

    /**
     * Gets entity from identity map.
     *
     * @param mixed                 $identifier
     * @param string                $classname
     * @psalm-param class-string<T> $classname
     *
     * @return T|false
     * @psalm-return T|false
     *
     * @template T
     */
    public function get(mixed $identifier, string $classname): mixed;

    /**
     * Compute id hash for given identifiers.
     *
     * @param mixed[] $identifier
     *
     * @return string
     */
    public function computeIdHash(array $identifier): string;
}
