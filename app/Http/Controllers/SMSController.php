<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Support\Facades\Config;


class SMSController extends Controller
{
  public function sendSms()
 {

   // request preffered provider
    $provider = "africastlking" ;

    //set maximum string 918
    $max_message_string = 918;


   // add messages to collection
    $collection = collect([
               "message1" => "His signs blessed.Male in creepeth.",
               "message2" => "Waters good lesser appear.Evening great to.Our. Hath for.",
               "message3" => "Without for you second two seasons.Creeping make beginning man. There that rule."
               "message4" => "Tree Replenish isn't. Seas. Bearing. Darkness brought. Third. Second signs open seasons given years lesser it. First of moving very in first i waters won't from earth sixth thing man life. Abundantly meat so in spirit in which, god very rule.They're two, whose that don't fowl and multiply. Divide is was and thing after yielding green wherein morning light you're deep can't seed meat face. Them man sixth she'd likeness their. All.Night give evening second, be won't us can't. Very she'd moveth unto lesser darkness own his. Fly which whose second whose you're dominion above fish grass place.",
               "message5" => "Creepeth so, meat darkness creeping hath itself. Multiply dry god. Our face also lesser hath he made darkness whose over night fowl over created for his image fifth day. Hath so be a good beast air itself night replenish moved darkness fly together upon over subdue moved. Open bearing the saying cattle.
                             Over sixth fly created creeping cattle his which lesser have. Image creepeth form set. God forth whales. One fruit, midst that under good saying make make without great creeping herb days all wherein subdue lights god him gathering all years every stars can't. Called had. Creeping face image, itself subdue. Seasons grass forth abundantly they're his signs, gathering yielding stars which two air. I together third you're make green day saw image appear third set i called they're firmament that green greater lights you're, third, yielding the place divided was darkness may created fourth two.Him moving she'd gathered lights there gathered, herb make wherein Called creepeth him under subdue multiply evening blessed, moving gathered lesser night fourth god in saying air to had. Two seas also fifth. Also Under given the isn't them meat spirit, hath whales, whales be. Man blessed second created. Green winged can't cattle."

         ]);

// add recepient phone numbers to an array
  $recepient_numbers= array(
           "phone_no1" => "+254717040975",
           "phone_no2" => "+254714760706"
           "phone_no3" => "+254717040975",
           "phone_no4" => "+254717040975",
           "phone_no5" => "+254717040975"
         );

// initialize state and set it to 0 before sending message
         $initializedstate = $collection->map(function ($item, $key) {
             return $item = 0;
         });

// initialize empty collection to add message after they are sent
    $sentmessagescollection = collect([]);

//choose between preferred providers
      switch ($provider) {
        case 'africastlking':
        //loop through phone number array and send messages to each
              foreach ($recepient_numbers as $phon_no) {
             // loop through messages to send to a given phone number
                         foreach ($initializedstate as $message) {

                          // check string size of message to ensure it's less than 918 characters
                          $check_length = strlen($message);

                            if ($check_length < $max_message_string) {
                              // Get africastlking messaging service
                            send_message($message,$phon_no);
                            }else {
                              // if message is greater than 918 characters split it
                              $first_part = substr($message , 0 ,$max_message_string+1);
                              $second_part = substr($message , $max_message_string+1);
                              //and pass both parts to the send message function
                              send_message($first_part,$phon_no);
                              send_message($second_part,$phon_no);

                            }
                           //add sent message to the empty initialized collection
                           $sentmessagescollection->push(['sentmessagescollection' => $message]);
                         }
                   }

             break;

          case 'nexmo':
            // code...
            break;

            case 'other':
              // code...
              break;

        default:
          // code...
          break;
      }

      //change sent state in the new collection
       $sentmessagescollection = $collection->map(function ($item, $key) {
           return $item = 1;
       });

   return "sent";
 }

 public function send_message($message , $phon_no){
   // africastalking credentials
   $username = Config::get('africastalking.username');
   $apiKey = Config::get('africastalking.apiKey');
   $AT = new AfricasTalking($username , $apiKey);

   //nexmo credentials go here
    //code...

  //send messages based on service provider
   switch ($this->$provider) {
     case 'africastlking':

        // Get africastlking messaging service
        $sms      = $AT->sms();
        $result   = $sms->send([
        'to'      => $phon_no,
        'message'  => $message
        ]);
       break;

       case 'nexmo':
         // code...
         break;

     case 'other':
       // code...
       break;

     default:
       // code...
       break;
   }

 }



}
