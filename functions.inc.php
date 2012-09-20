<?php

function wpPostXMLRPC($title,$body,$rpcurl,$username,$password,$category,$keywords='',$encoding='UTF-8') {
    $title = htmlentities($title, ENT_NOQUOTES, $encoding);
    $keywords = htmlentities($keywords, ENT_NOQUOTES, $encoding);

    $content = array(
        'title' => $title,
        'description' => $body,
        'mt_allow_comments' => 1,  // 1 to allow comments
        'mt_allow_pings' => 0,  // 1 to allow trackbacks
        'post_type' => 'post',
        'mt_keywords' => $keywords,
        'categories' => array($category)
    );
    $params = array(0, $username, $password, $content, true);
    $request = xmlrpc_encode_request('metaWeblog.newPost', $params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
    curl_setopt($ch, CURLOPT_URL, $rpcurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    $results = curl_exec($ch);
    curl_close($ch);
    return $results;
}
?>
