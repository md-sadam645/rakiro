<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\Automail;
use App\Models\Customer_master;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;

class Automation implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $allCusId;
    public $allFetchedData;
    public $emailRowId;
    public $allShortNameData;
    public $msg;
    public $subject;
    public $fromEmail;
    public $attachmentPath;
    public $attachmentName;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($allCusIdChunks,$allFetchedDataChunks,$emailRowId,$allShortNameData,$msg,$subject,$fromEmail,$attachmentPath,$attachmentName)
    {
        $this->allCusId = $allCusIdChunks;
        $this->allFetchedData = $allFetchedDataChunks;
        $this->emailRowId = $emailRowId;
        $this->allShortNameData = $allShortNameData;
        $this->msg = $msg;
        $this->subject = $subject;
        $this->fromEmail = $fromEmail;
        $this->attachmentPath = $attachmentPath;
        $this->attachmentName = $attachmentName;    
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subject = $this->subject;
        $fromEmail = $this->fromEmail;
        $attachmentName = $this->attachmentName;
        $attachmentPath = $this->attachmentPath;

        if(count($this->allShortNameData) != 0 && count($this->allFetchedData) != 0)
        {
            //replacing short name with customer data
            for($k=0; $k< count($this->allFetchedData); $k++)
            {
                $cusMsg = $this->msg;
                $GettingCusEmail = Customer_master::where("bp_code",$this->allCusId[$k])->first();
                $perCusData = $this->allFetchedData[$this->allCusId[$k]];

                for ($l = 0; $l < count($perCusData); $l++)
                {
                    $search = $this->allShortNameData[$l];
                    $replacement = $perCusData[$search];

                    $cusMsg = preg_replace_callback(
                        "/\b" . preg_quote($search, '/') . "\b/i", 
                        function ($matches) use ($replacement, $search) {
                            return $matches[0] === $search ? $replacement : $matches[0];
                        }, 
                        $cusMsg
                    );
                }
            
                //sending email to cus email id
                $cusEmail = $GettingCusEmail->email_id;
                $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $cusMsg], function($message) use($subject,$cusEmail,$fromEmail,$attachmentPath,$attachmentName){
                    $message->to("sadamaalam0786@gmail.com");
                    $message->from($fromEmail,env('MAIL_FROM_NAME')); 
                    if(!empty($attachmentPath) && !empty($attachmentName))
                    {
                        for($m = 0; $m< count($attachmentPath); $m++)
                        {
                            $message->attach($attachmentPath[$m], array(
                                'as' => $attachmentName[$m],)
                            );
                        }
                    }
                    $message->subject($subject);
                });
            }
        }
        else
        {
            for($k=0; $k<count($this->allCusId); $k++)
            {  
                $GettingCusEmail = Customer_master::where("bp_code",$this->allCusId[$k])->first();
                $cusEmail = $GettingCusEmail->email_id;
                
                $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $this->msg], function($message) use($subject,$cusEmail,$fromEmail,$attachmentPath,$attachmentName){
                    $message->to("sadamaalam0786@gmail.com");
                    $message->from($fromEmail,env('MAIL_FROM_NAME')); 
                    if(!empty($attachmentPath) && !empty($attachmentName))
                    {
                        for($m = 0; $m< count($attachmentPath); $m++)
                        {
                            $message->attach($attachmentPath[$m], array(
                                'as' => $attachmentName[$m],)
                            );
                        }
                    }
                    $message->subject($subject);
                });
            }
        }

        Automail::where("id",$this->emailRowId)->update(["status" => 2,"last_executed_date" => date('Y-m-d')]); 
    }
}
