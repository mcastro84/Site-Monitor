<?php

namespace App\Jobs;

use App\Repositories\WebsiteMonitorService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

/**
 * Class UpdateEpisodes
 * @package App\Jobs
 */
class CheckUrls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var
     */
    protected $url;
    protected $batch;
    protected $urls_count;

    /**
     * CheckUrls constructor.
     * @param $url
     * @param $batch
     * @param $urls_count
     */
    public function __construct($url, $batch, $urls_count)
    {
        $this->url = $url;
        $this->batch = $batch;
        $this->urls_count = $urls_count;
    }

    /**
     * Execute the job.
     *
     * @param WebsiteMonitorService $websiteMonitorService
     * @return void
     */
    public function handle(WebsiteMonitorService $websiteMonitorService)
    {
        $websiteMonitorService->checkUrls($this->url, $this->batch, $this->urls_count);
    }
}
