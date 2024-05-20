<?php

require __DIR__.'/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

$factory = (new Factory)->withServiceAccount(__DIR__.'/testproject-f9304-firebase-adminsdk-w8tmo-de570f2a17.json');

$messaging = $factory->createMessaging();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(empty($_POST['firebase_token'])){
        die("ERROR MISSINF tokens");
    }

    if(empty($_POST['TITLE'])){
        die("ERRPOR MISSING TITLE");
    }

    if(empty($_POST["MESSAGE"])){
        die("ERRPOR MISSING MESSAGE");
    }

    $message = CloudMessage::new();
    $notification = Notification::create( $_POST['TITLE'], $_POST['MESSAGE']);
    $message=$message->withNotification($notification);

    if(is_string($_POST['firebase_token']) || (is_array($_POST['firebase_token']) && count($_POST['firebase_token']) == 1)){
        echo "SENT SINGLE MESSAGE<br>";
        $token = is_array($_POST['firebase_token'])?$_POST['firebase_token'][0]:$_POST['firebase_token'];
        echo "TOKEN IS <br/>$token</br>";
        $message=$message->withChangedTarget('token',$token);
        $messaging->send($message);
    } elseif(is_array($_POST['firebase_token']) && count($_POST['firebase_token']) > 1) {
        $messaging->sendMulticast($message,$_POST['firebase_token']);
        echo "SENT SINGLE MULTICAST";
    }
    echo "</br> <h1>".$_POST['TITLE']."</h1> <br> ".$_POST['MESSAGE'];
}
