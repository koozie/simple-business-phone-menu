<?php

    /*
    Copyright (c) 2014 Chris Stansbury

    Permission is hereby granted, free of charge, to any person
    obtaining a copy of this software and associated documentation
    files (the "Software"), to deal in the Software without
    restriction, including without limitation the rights to use,
    copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following
    conditions:

    The above copyright notice and this permission notice shall be
    included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
    EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
    OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
    NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
    HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
    WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
    OTHER DEALINGS IN THE SOFTWARE.
    */
    
require "twilio/Services/Twilio.php";



/*  Please Update the Configuration Hash with the Correct email
 *  audio file (if required), and the department, with phone
 *  numbers
 *  
 *  Do not use '0'.  Reserved for Company voicemail messages.
 */

$config = array(
    'greeting_audio' => 'audio/acme_greeting.mp3',
    'company_name'   => 'ACME Industries',
    'email_voicemail' => 'voicemail@example.org',
    'forward_rules'  => array(
        '1' => array(
            'number' => '210-555-0001',
            'dept'   => 'Billing',
            ),
        '2' => array(
            'number' => '210-555-0002',
            'dept'   => 'Accounting',
            ),
        '3' => array(
            'number' => '210-555-0003',
            'dept'   => 'Technical Support',
            )
    )
);






function audio_filename(){ global $config; return $config['greeting_audio']; }

function url_same_page(){
    return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function opening_message($response){

    global $config;
    $mp3 = audio_filename();
    if (is_readable($mp3)){
        $response->play($mp3);
    }else{
        $response->say("Thank you for calling " . $config['company_name']);
        foreach($config['forward_rules'] as $key => $val){
            $response->say("For " . $val['dept'] . " press $key .");
        }
            /*
        $response->say("For $config['forward_rules']['1']['dept'] press 1.");
        $response->say("For Technical Support press 2.");
        $response->say("For Accounting press 3.");
             */
        $response->say("To leave a voicemail message, please press 0.");
    } 
}

function email_vm(){ global $config; return $config['email_voicemail'];}

function twimlet_vm($email){
    return 'http://twimlets.com/voicemail?Email=' . $email . '&Message=Please+Leave+A+Message';
}

function twimlet_forward($forward_index){
    global $config; 
    $telephone_num = $config['forward_rules'][$forward_index]['number'];
    $url = "http://twimlets.com/callme?PhoneNumber=" . $telephone_num;
    $url .= "&FailUrl=" . url_same_page();
    return $url;
}


// initiate response library
$response = new Services_Twilio_Twiml();

$button_press = $_REQUEST['Digits'];

// Check Department Rules first, then voicemail rule, then default (i.e. no buttons pressed)
if (array_key_exists($button_press, $config['forward_rules'])){
    $response->redirect(twimlet_forward($button_press));
} elseif ($button_press == '0') { // voicemail option
    $response->redirect(twimlet_vm(email_vm() ));
} else {
    $gather = $response->gather(array('numDigits' => 1, 'action' => url_same_page()));
    opening_message($gather);
    $response->redirect(url_same_page());
}

// send the response
print $response;
?>
