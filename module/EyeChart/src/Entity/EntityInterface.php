<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 7/13/2017
 * (c) 2017
 */

namespace EyeChart\Entity;

use EyeChart\VO\VOInterface;

/**
 * Interface EntityInterface
 * @package EyeChart\Entity
 */
interface EntityInterface
{
    /**
     * @param mixed $dataSource
     */
    public function initialize($dataSource): void;

    /**
     * @param VOInterface $vo
     */
    public function initializeByVO(VOInterface $vo): void;

    /**
     * @param mixed[] $dataSource
     */
    public function initializeByArray(array $dataSource): void;

    /**
     * @param mixed[] $dataSource
     */
    public function hydrateFromDataBase(array $dataSource): void;

    /**
     * @return mixed[]
     */
    public function toArray(): array;
}
