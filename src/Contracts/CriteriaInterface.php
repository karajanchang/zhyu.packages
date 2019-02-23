<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 2019-02-21
 * Time: 17:39
 */

namespace Zhyu\Repositories\Contracts;

use Zhyu\Repositories\Criterias\Criteria;


/**
 * Interface CriteriaInterface
 * @package Zhyu\Repositories\Contracts
 */
interface CriteriaInterface {

    /**
     * @param bool $status
     * @return $this
     */
    public function skipCriteria($status = true);

    /**
     * @return mixed
     */
    public function getCriteria();

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function getByCriteria(Criteria $criteria);

    /**
     * @param Criteria $criteria
     * @return $this
     */
    public function pushCriteria(Criteria $criteria);

    /**
     * @return $this
     */
    public function  applyCriteria();
}