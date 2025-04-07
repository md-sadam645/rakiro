<?php

namespace App\Traits;

trait Common {

   public function fun1(){
        return "Trait respons";
   }

   public function status($status = 0){
        $statusText = "In Active";
        if($status == 1){
           $statusText = "Active";
        }
        return $statusText;
   }
}

trait Common1 {

    public function fun2(){
         return "Trait respons dfsd";
    }
 
    public function status2($status = 0){
 
        $statusText = "In Active";
        if($status == 1){
            $statusText = "Active";
        }
        return $statusText;
    }
 }

