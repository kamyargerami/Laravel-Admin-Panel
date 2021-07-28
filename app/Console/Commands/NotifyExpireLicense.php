<?php

namespace App\Console\Commands;

use App\Models\License;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifyExpireLicense extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notification to users of licences in 4 week before expiration date, every week';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        for ($week = 1; $week <= 4; $week++) {
            $licenses = License::with('used')->where('expires_at', Carbon::now()->subWeeks($week)->toDateString());

            $mobiles = array_unique($licenses->used->pluck('mobile')->toArray());

            foreach ($mobiles as $mobile) {
                // Todo send sms
            }

            $emails = array_unique($licenses->used->pluck('mobile')->toArray());

            foreach ($mobiles as $mobile) {
                // Todo send email
            }
        }
    }
}
