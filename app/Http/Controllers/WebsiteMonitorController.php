<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\WebsiteMonitorServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/**
 * Class WebsiteMonitorController
 * @package App\Http\Controllers
 */
class WebsiteMonitorController extends Controller {

    /**
     * @var WebsiteMonitorServiceInterface
     */
    protected $websiteMonitorService;

    /**
     * WebsiteMonitorController constructor.
     * @param WebsiteMonitorServiceInterface $websiteMonitorService
     */
    function __construct(WebsiteMonitorServiceInterface $websiteMonitorService) {
        $this->websiteMonitorService= $websiteMonitorService;
    }

    /**
     * @return string
     */
    public function index() {
        $urls = $this->websiteMonitorService->getAll();
        return view('monitoring.index')->with(['urls' => $urls]);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function addUrl(Request $request) {

        if (!isset($request->url) ||
            !$this->websiteMonitorService->validateUrl($request->url)
        ) {
            return json_encode(['success' => false, 'message' => 'Please insert a valid Url.']);
        }

        $insert_url_id = $this->websiteMonitorService->addUrl($request->url);
        if ($insert_url_id) {
            return json_encode(['success' => true, 'url_id' => $insert_url_id]);
        }

        return json_encode(['success' => false, 'message' => 'Something went wrong.']);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function removeUrl(Request $request)
    {
        if (!isset($request->url_id)) {
            return json_encode(['success' => false, 'message' => 'ID not found.']);
        }

        if ($this->websiteMonitorService->removeUrl($request->url_id)) {
            return json_encode(['success' => true]);
        }

        return json_encode(['success' => false, 'message' => 'ID not found.']);
    }

    /**
     * @return string
     */
    public function checkUrls()
    {
        Artisan::call('urls:check');
        return json_encode(['success' => true, 'message' => 'Websites successfully added to the check list.']);
    }
}