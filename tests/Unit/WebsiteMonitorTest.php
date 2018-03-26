<?php

namespace Tests\Unit;

use App\Jobs\CheckUrls;
use App\Mail\FailedRequests;
use App\Repositories\WebsiteMonitorService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Mockery as m;
use App\Repositories\Contracts\WebsiteMonitorRepositoryInterface;
use Illuminate\Support\Facades\Queue;

/**
 * Class WebsiteMonitorTest
 * @package Tests\Unit
 */
class WebsiteMonitorTest extends TestCase
{
    /**
     * @return void
     */

    private $websiteMonitorRepository;
    /**
     * @var
     */
    private $websiteMonitorService;

    /**
     *
     */
    public function setUp()
    {
        parent::setup();
        // Mock repository
        $this->websiteMonitorRepository = m::mock(WebsiteMonitorRepositoryInterface::class);
        $this->app->instance(WebsiteMonitorRepositoryInterface::class, $this->websiteMonitorRepository);

        // Initialize Service
        $this->websiteMonitorService = new WebsiteMonitorService($this->websiteMonitorRepository);
    }

    /**
     *
     */
    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    /**
     *
     */
    public function testAddUrl()
    {
        $this->websiteMonitorRepository->shouldReceive('addUrl')->once()->andReturn(3);
        $response = $this->websiteMonitorService->addUrl('http://www.google.co.uk');
        $this->assertTrue(is_int($response));
    }

    /**
     *
     */
    public function testRemoveUrl()
    {
        $this->websiteMonitorRepository->shouldReceive('removeUrl')->once()->andReturn(true);
        $this->assertTrue($this->websiteMonitorService->removeUrl(3));
    }

    /**
     *
     */
    public function testGetAllUrls()
    {
        $this->websiteMonitorRepository->shouldReceive('getAll')->once()->andReturn('urls');
        $this->assertEquals('urls', $this->websiteMonitorService->getAll());
    }

    /**
     *
     */
    public function testAddUrlRoute()
    {
        $this->websiteMonitorRepository->shouldReceive('addUrl')->once()->andReturn(3);
        $response = $this->json('POST', '/api/add-url', ['url' => 'https://www.google.co.uk']);
        $response->assertStatus(200)->assertExactJson(['success' => true, 'url_id' => 3]);
    }

    /**
     *
     */
    public function testRemoveUrlRoute()
    {
        $this->websiteMonitorRepository->shouldReceive('removeUrl')->once()->andReturn(true);
        $response = $this->json('POST', '/api/remove-url', ['url_id' => 4]);
        $response->assertStatus(200)->assertExactJson(['success' => true]);
    }

    /**
     *
     */
    public function testCheckUrlsRoute()
    {
        $collection = collect([
            (object)['url' => 'http://localhost'],
            (object)['url' => '127.0.0.1']
        ]);

        // Called from command
        $this->websiteMonitorRepository->shouldReceive('getAll')->once()->andReturn($collection);

        // Called from service
        $mockService = m::mock(WebsiteMonitorService::class);
        $mockService->shouldReceive('addLog')->andReturn(true);
        $mockService->shouldReceive('updateLog')->andReturn(true);
        $this->websiteMonitorRepository->shouldReceive('addLog')->andReturn(true);
        $this->websiteMonitorRepository->shouldReceive('updateLog')->andReturn(true);
        $this->websiteMonitorRepository->shouldReceive('getFinishedJobs')->andReturn([]);

        $response = $this->json('GET', '/api/check-urls');
        $response->assertStatus(200)->assertExactJson(['success' => true, 'message' => 'Websites successfully added to the check list.']);
    }

    /**
     *
     */
    public function testJobWasDispatched()
    {
        Bus::fake();
        dispatch((new CheckUrls('http://localhost', '1521719921', 10))->onQueue('work'));
        Bus::assertDispatched(CheckUrls::class);
    }

    /**
     *
     */
    public function testJobWasQueued()
    {
        Queue::fake();
        dispatch((new CheckUrls('http://localhost', '1521719921', 10))->onQueue('work'));
        Queue::assertPushedOn('work', CheckUrls::class);
    }

    /**
     *
     */
    public function testEmailWasSent()
    {
        $collection = collect([
            (object)['url' => 'http://localhost'],
            (object)['url' => '127.0.0.1']
        ]);
        Mail::fake();
        $mailable = new FailedRequests($collection);
        Mail::to('decastro84@gmail.com')->send($mailable);

        Mail::assertSent(FailedRequests::class); // passes
    }

    /**
     *
     */
    public function testSendEmail()
    {
        $collection = collect([
            (object)['url' => 'http://localhost'],
            (object)['url' => '127.0.0.1']
        ]);
        Mail::fake();
        $this->websiteMonitorRepository->shouldReceive('getFinishedJobs')->once()->andReturn($collection);
        $this->websiteMonitorRepository->shouldReceive('getFailedJobs')->once()->andReturn($collection);
        // Complete jobs need to be equal to the count of urls being checked
        // Otherwise email is not sent
        $this->websiteMonitorService->sendEmail('1521719921', 2);

        Mail::assertSent(FailedRequests::class);
    }

}
