<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Batchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailCompose;
use App\Models\Customer_master;
class ScheduleEmail implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $allCusId;
    public $allFetchedData;
    public $emailComId;
    public $allShortNameData;
    public $msg;
    public $cc;
    public $subject;
    public $fromEmail;
    public $attachmentPath;
    public $attachmentName;
    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($allCusIdChunks,$allFetchedDataChunks,$emailComId,$allShortNameData,$msg,$cc,$subject,$fromEmail,$attachmentPath,$attachmentName)
    {
        $this->allCusId = $allCusIdChunks;
        $this->allFetchedData = $allFetchedDataChunks;
        $this->emailComId = $emailComId;
        $this->allShortNameData = $allShortNameData;
        $this->msg = $msg;
        $this->cc = $cc;
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
            for($k=0; $k<count($this->allFetchedData); $k++)
            {
                $sendingStatus = EmailCompose::where('id', $this->emailComId)->first();
                if($sendingStatus->sending_status == 1)
                {
                    EmailCompose::where("id",$this->emailComId)->update([
                        'status' => 1
                    ]);
                    
                    $cusMsg = $this->msg;
                    $GettingCusEmail = Customer_master::where("bp_code",$this->allCusId[$k])->first();
                    $perCusData = $this->allFetchedData[$this->allCusId[$k]];
                
                    for($l = 0; $l<count($perCusData); $l++)
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

                    //if cc mail available send to cc mail
                    $emailCC = json_decode($this->cc);
                    if(!empty($emailCC))
                    {
                        $explodeCc = explode(",",$emailCC[0]);
                        for($j=0; $j<count($explodeCc); $j++)
                        {
                            if(!empty($explodeCc[$j]))
                            {
                                $ccMail = $explodeCc[$j];
                                $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $cusMsg], function($message) use($ccMail,$subject,$fromEmail,$attachmentPath,$attachmentName){
                                    $message->to($ccMail);
                                    $message->subject($subject,env('MAIL_FROM_NAME'));
                                    $message->from($fromEmail); 
                                    if(!empty($attachmentPath) && !empty($attachmentName))
                                    {
                                        for($m = 0; $m< count($attachmentPath); $m++)
                                        {
                                            $message->attach($attachmentPath[$m], array(
                                                'as' => $attachmentName[$m],)
                                            );
                                        }
                                    }
                                });
                            }
                        }
                    }

                    //sending email to cus email id
                    $cusEmail = $GettingCusEmail->email_id;
                    if(!empty($cusEmail))
                    {
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
                // else
                // {
                //     EmailCompose::where("id", $this->emailComId)->update([
                //         'status' => 3,
                //         'sending_status' => 0
                //     ]);
                // }
            }
        }  
        else
        {
            //sending email to cus email id
            for($k=0; $k<count($this->allCusId); $k++)
            {   
                $sendingStatus = EmailCompose::where('id', $this->emailComId)->first();
                if($sendingStatus->sending_status == 1)
                {
                    //making email compose status sending
                    EmailCompose::where("id",$this->emailComId)->update([
                        'status' => 1
                    ]);

                    //if cc mail available send to cc mail
                    $emailCC = json_decode($this->cc);
                    if(!empty($emailCC))
                    {
                        $explodeCc = explode(",",$emailCC[0]);
                        for($j=0; $j<count($explodeCc); $j++)
                        {
                            if(!empty($explodeCc[$j]))
                            {
                                $ccMail = $explodeCc[$j];
                                $sendmail = Mail::send('admin.auth.emailSend', ['msg' => $this->msg], function($message) use($ccMail,$subject,$fromEmail,$attachmentPath,$attachmentName){
                                    $message->to($ccMail);
                                    $message->subject($subject);
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
                                });
                            }
                        }
                    }

                    $GettingCusEmail = Customer_master::where("bp_code",$this->allCusId[$k])->first();
                    if(!empty($GettingCusEmail))
                    {
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
                // else
                // {
                //     EmailCompose::where("id",$this->emailComId)->update([
                //         'status' => 3,
                //         'sending_status' => 0
                //     ]);
                // }
            }
            
        }

        EmailCompose::where("id",$this->emailComId)->update([
            'status' => 2
        ]);
        
    }
}
