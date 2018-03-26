<?php

namespace App\Repositories;

use App\Repositories\Contracts\WebsiteMonitorRepositoryInterface;
use App\Url;
use App\UrlLog;

/**
 * Class WebsiteMonitorRepository
 * @package App\Repositories
 */
class WebsiteMonitorRepository implements WebsiteMonitorRepositoryInterface
{
    /**
     * @param $url
     * @return bool|mixed
     */
    public function addUrl($url)
    {
        $add_url = new Url();
        $add_url->url = $url;

        if ($add_url->save()) {
            return $add_url->id;
        }

        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function removeUrl($id)
    {
        return Url::where('id', '=', $id)->delete();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return Url::all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return Url::where('id', '=', $id)->first();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function addLog($data)
    {
        return UrlLog::create($data);
    }

    /**
     * @param $data
     * @return bool
     */
    public function updateLog($data)
    {
        $url = UrlLog::where('batch', '=', $data['batch'])->where('url', '=', $data['url'])->first();
        if ($url) {
            $url->complete = $data['complete'];
            $url->success = $data['success'];
            $url->save();

            return true;
        }
        return false;
    }

    /**
     * @param $batch
     * @return mixed
     */
    public function getFinishedJobs($batch)
    {
        return UrlLog::where('batch', '=', $batch)->where('complete', '=', 1)->get();
    }

    /**
     * @param $batch
     * @return mixed
     */
    public function getFailedJobs($batch)
    {
        return UrlLog::where('batch', '=', $batch)
            ->where('complete', '=', 1)
            ->where('success', '=', 0)
            ->get();
    }

}