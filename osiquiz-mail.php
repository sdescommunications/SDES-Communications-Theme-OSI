<?php
if (isset($_POST["data"])){
$user = $_POST["data"];
$usrObj = json_decode($user);

$email = $usrObj->email;
$subject = "OSI Involvement Quiz Results"."\r\n";

$agency = $usrObj->results[0]->name;
$description= $usrObj->results[0]->info;
$url= $usrObj->results[0]->link;


$message = " Your best match was ".$agency."\n\n\t".$description."\nLearn more: ".$url."\n\n\n";


if (sizeof($usrObj->results) > 1){
    $message .= "You also matched with: \n\n";
    for($i = 1; $i  < sizeof($usrObj->results); $i++){
        $message .= $usrObj->results[$i]->name."\n\n\t".$usrObj->results[$i]->info."\nLearn more: ".$usrObj->results[$i]->link."\n\n";
    }
}

wp_mail($email, $subject, $message );

echo "Results were sent!";
} else{ 
    echo "Results were not sent.";
}

?>
