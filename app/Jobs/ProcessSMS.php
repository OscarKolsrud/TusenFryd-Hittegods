<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessSMS implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $recipent;

    protected $text;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($recipent, $text)
    {
        $this->recipent = $recipent;
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @param $recipent
     * @param $text
     * @return void
     */
    public function handle()
    {
        $sender = env('SMS_SENDER');
        $phoneNo = $this->recipent;
        $message = $this->text;
        $deliveryreport = "https://webhook.site/fbce1b46-6b80-4aaf-aea1-1902c4b73084";

        $url = urlencode("https://admin.intouch.no/smsgateway/sendSms?sender=$sender&targetNumbers=$phoneNo&sms=$message&deliveryReportUrl=$deliveryreport");

        $response = Http::retry(3, 100)->get($url);
    }
}
