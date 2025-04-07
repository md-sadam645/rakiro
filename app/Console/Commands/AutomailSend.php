<?php

namespace App\Console\Commands;

use App\Models\Automail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\GroupList;
use App\Models\Invoice_master;
use App\Models\CommonSetup;
use Illuminate\Support\Facades\DB;
use App\Models\Customer_master;
use App\Models\Customer_contact;
use App\Models\Customer_address;
use App\Models\Customer_group;
use App\Models\Item_master;
use App\Models\Price_list;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\Automation;
use Carbon\Carbon;
class AutomailSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-automail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending Automail';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cDateTime = Carbon::now();
        $cDate = $cDateTime->toDateString();
        $autoMail = Automail::where("status",2)->get();

        if(!empty($autoMail))
        {
            for($i=0; $i<count($autoMail); $i++)
            {
                $lExecuteDate = Carbon::createFromFormat('Y-m-d', $autoMail[$i]->last_executed_date);
                $addedDate = $lExecuteDate->addDays($autoMail[$i]->schedule_time);
                $finalDate = $addedDate->toDateString();
                if($cDate >= $finalDate)
                {
                    Automail::where("id",$autoMail[$i]->id)->update(["status" => 1]);
                }
            }
        }
    
        $automailData = Automail::where("status",1)->get();
        if($automailData != '[]')
        {
            // dispatch(new Automation); 
            // Artisan::call('queue:work --stop-when-empty');
        }
    }
}
