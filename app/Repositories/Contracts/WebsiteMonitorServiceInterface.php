<?php

namespace App\Repositories\Contracts;

/**
 * Interface WebsiteMonitorServiceInterface
 * @package App\Repositories\Contracts
 */
interface WebsiteMonitorServiceInterface {

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
     * @param $url
     * @param $batch
     * @param $urls_count
     * @return mixed
     */
    public function checkUrls($url, $batch, $urls_count);

    /**
     * @param $url
     * @return mixed
     */
    public function validateUrl($url);

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
}