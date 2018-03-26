<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FailedRequests extends Mailable
{
    use Queueable, SerializesModels;

    protected $urls;

    /**
     * Create a new message instance.
     *
     * @return void
     * @param $urls
     */
    public function __construct($urls)
    {
        $this->urls = $urls;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM'))
            ->view('monitoring.failed_requests')
            ->with(['urls' => $this->urls]);
    }
}
