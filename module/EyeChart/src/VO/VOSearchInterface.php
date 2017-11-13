<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Josh Pacheco <joshua.pacheco@gmail.com>
 * Date: 8/14/2017
 * (c) 2017
 */

namespace EyeChart\VO;

/**
 * Interface VOSearchInterface
 * @package EyeChart\VO
 */
interface VOSearchInterface
{
    /**
     * @return int
     */
    public function getLimit():? int;

    /**
     * @return array
     */
    public function getOrder():? array;

    /**
     * @return string
     */
    public function getSearch():? string;

    /**
     * @return string
     */
    public function getSearchCapitalized():? string;

    /**
     * @return int
     */
    public function getOffset():? int;
}
