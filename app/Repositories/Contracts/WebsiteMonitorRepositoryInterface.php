<?php

namespace App\Repositories\Contracts;

/**
 * Interface WebsiteMonitorRepositoryInterface
 * @package App\Repositories\Contracts
 */
interface WebsiteMonitorRepositoryInterface {

    /**
     * @param $url
     * @return mixed
     */
    public function addUrl($url);

    /**
     * @param $id
     * @return mixed
     */
    public function removeUrl($id);

    /**
     * @return mixed
     */
    public function getAll();

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param $data
     * @return mixed
     */
    public function addLog($data);

    /**
     * @param $data
     * @return mixed
     */
    public function updateLog($data);

    /**
     * @param $batch
     * @return mixed
     */
    public function getFinishedJobs($batch);

    /**
     * @param $batch
     * @return mixed
     */
    public function getFailedJobs($batch);

}