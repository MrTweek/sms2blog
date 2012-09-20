<?php 

require('/var/www/blog.flupps.net/htdocs/wp-includes/class-IXR.php');

$rpcurl = 'http://blog.flupps.net/wp-app.php/service';
$username = 'Philipp';
$password = 'Senf!11k';
$category = 'Telegramm';

$data = '';

$f = fopen('php://stdin', 'r');

$active = false;

while ($l = fgets($f)) {
    $plain = trim($l);

    if (empty($plain)) {
        $active = true;
        continue;
    }
    
    if (!$active)
        continue;

    if (preg_match('!A Message was received from the mobile number .+? with the message:!', $plain)) {
        $number = preg_replace('!^A Message was received from the mobile number (.+?) with the message:!', '+$1', $plain);
        continue;
    }

    if (preg_match('!If you want to send emails from your mobile!', $plain))
        break;

    $data .= $l;
}

fclose($f);

$rpc = new IXR_Client('http://blog.flupps.net/xmlrpc.php');

$post['title']       = "Telegramm von $number";
$post['categories']  = array('Telegramme');
$post['mt_keywords'] = '';
$post['description'] = $data;

$status = $rpc->query(
    'metaWeblog.newPost',   // Methode
    1,              // Blog ID, in der Regel 1
    $username, 
    $password, 
    $post,    
    true            // Veröffentlichen?
);

if (!$status) {
     echo 'Error ('.$rpc->getErrorCode().'): '.$rpc->getErrorMessage();
}

?>
