<?php

namespace App\Repositories;

use App\Mail\FailedRequests;
use App\Repositories\Contracts\WebsiteMonitorRepositoryInterface;
use App\Repositories\Contracts\WebsiteMonitorServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Mail;

/**
 * Class WebsiteMonitorService
 * @package App\Repositories
 */
class WebsiteMonitorService implements WebsiteMonitorServiceInterface
{
    /**
     * @var WebsiteMonitorRepositoryInterface|string
     */
    protected $websiteMonitorRepository = '';

    /**
     * WebsiteMonitorService constructor.
     * @param WebsiteMonitorRepositoryInterface $websiteMonitorRepository
     */
    function __construct(WebsiteMonitorRepositoryInterface $websiteMonitorRepository)
    {
        $this->websiteMonitorRepository = $websiteMonitorRepository;
    }

    /**
     * @param $url
     * @return mixed
     */
    public function addUrl($url)
    {
        return $this->websiteMonitorRepository->addUrl($url);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function removeUrl($id)
    {
        return $this->websiteMonitorRepository->removeUrl($id);
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return $this->websiteMonitorRepository->getAll();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->websiteMonitorRepository->getById($id);
    }

    /**
     * @param $url
     * @param $batch
     * @param $urls_count
     * @return bool
     */
    public function checkUrls($url, $batch, $urls_count)
    {
        $client = new Client(['base_uri' => $url]);

        try {
            $res = $client->request('GET', '/');
            $success = ($res->getStatusCode() == 200) ? 1 : 0;

            $this->updateLog([
                'batch' => $batch,
                'url' => $url,
                'complete' => 1,
                'success' => $success,
            ]);
        } catch (RequestException $e) {
            $this->updateLog([
                'batch' => $batch,
                'url' => $url,
                'complete' => 1,
                'success' => 0,
            ]);
        }

        $this->sendEmail($batch,$urls_count);

        return true;

    }

    /**
     * @param $batch
     * @param $urls_count
     * @return bool
     */
    public function sendEmail($batch, $urls_count)
    {
        $finished_jobs = ($this->websiteMonitorRepository->getFinishedJobs($batch));
        if (count($finished_jobs) !== $urls_count) {
            return false;
        }

        $failed_jobs = $this->websiteMonitorRepository->getFailedJobs($batch);
        if (count($failed_jobs) > 0) {
            Mail::to(env('MAIL_TO'))->send(new FailedRequests($failed_jobs));
            return true;
        }

        return false;
    }

    /**
     * @param $data
     */
    public function addLog($data)
    {
        $this->websiteMonitorRepository->addLog($data);
    }

    /**
     * @param $data
     */
    public function updateLog($data)
    {
        $this->websiteMonitorRepository->updateLog($data);
    }

    /**
     * @param $url
     * @return mixed
     */
    public function validateUrl($url)
    {
        ///return true;
        return filter_var($url, FILTER_VALIDATE_URL);
    }

}