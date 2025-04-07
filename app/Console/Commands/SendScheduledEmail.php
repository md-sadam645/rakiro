<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\GroupList;
use App\Models\EmailCompose;
use App\Models\CommonSetup;
use App\Models\Customer_master;
use App\Models\Customer_contact;
use App\Models\Customer_address;
use App\Models\Customer_group;
use App\Models\Item_master;
use App\Models\Price_list;
use App\Jobs\ScheduleEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use App\Mail\ScheduledEmail as ScheduledEmailMail;

class SendScheduledEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
   
    protected $description = 'Sending scheduled emails';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dateTime = Carbon::now();
        $cTime = $dateTime->toDateTimeString();
        $scheduleEmail = EmailCompose::where('schedule_time', '<=', $cTime)->where("status",0)->where("sending_status",1)->get();
        
        if(!empty($scheduleEmail))
        {
            // dispatch(new ScheduleEmail); 
            // Artisan::call('queue:work --stop-when-empty');
        }
    }
}
