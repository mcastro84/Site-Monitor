<?php

namespace App\Console\Commands;

use App\Jobs\CheckUrls;
use App\Repositories\Contracts\WebsiteMonitorServiceInterface;
use App\Repositories\WebsiteMonitorService;
use Illuminate\Console\Command;

class CheckUrlsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'urls:check {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Urls';

    protected $websiteMonitorService;

    /**
     * Create a new command instance.
     *
     * @return void
     * @param WebsiteMonitorServiceInterface $websiteMonitorService
     */
    public function __construct(WebsiteMonitorServiceInterface $websiteMonitorService)
    {
        parent::__construct();
        $this->websiteMonitorService = $websiteMonitorService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($id = $this->argument('id')) {
            $urls = [$this->websiteMonitorService->getById($id)];
        } else {
            $urls = $this->websiteMonitorService->getAll();
        }

        $this->info('[ --- Checking Urls --- ]');

        $batch = time();
        $urls_count = count($urls);

        foreach ($urls as $url) {
            // Add job to the log table
            $this->websiteMonitorService->addLog([
                'batch' => $batch,
                'url' => $url->url,
                'complete' => 0,
                'success' => 0,
            ]);
            $this->info('Url added: ' . $url->url);
            dispatch((new CheckUrls($url->url, $batch, $urls_count))->onQueue('work'));
            //$this->websiteMonitorService->checkUrls($url->url, $batch, $urls_count);
        }

        return true;

    }

}
