<?php

function googleImageSearch ($query, $page, $debug=false)
{
    // Get the search results page
    $response = file_get_contents('http://images.google.com/images?hl=en&q=' . urlencode ($query) . '&imgtype=photo&start=' . (($page - 1) * 21));
    // Extract the image information. This is found inside of a javascript call to setResults
    preg_match('/<table class="images_table" width="100%" style="table-layout:fixed">(.*)<\/table>/', $response, $match);
    $res = array();
    $res['next'] = 0;
    $res['html'] = "Grab all the results....</br>";
    if (isset($match[1])) {
        // Grab all the arrays
        preg_match_all('/imgurl=([^&]*)&/', $match[1], $retVal);
        $res['work'] = "urls=".json_encode($retVal[1])."&query=$query";
        $n = count($retVal[1]);
        $res['next'] = 1;
        $res['html'] = "Found $n pics in the $page th page</br>";
    }
    return $res;
}

function flickrImageSearch ($query, $page, $debug=false)
{
    // Get the search results page
    $response = exec("python get_flickr_group.py $query $page", $output);
    // Extract the image information. This is found inside of a javascript call to setResults
    preg_match('/<table class="images_table" width="100%" style="table-layout:fixed">(.*)<\/table>/', $response, $match);
    $res = array();
    $res['next'] = 0;
    $res['html'] = "Grab all the results....</br>";
    if (count($output)>1 ){
        // Grab all the arrays
        $res['work'] = "urls=".json_encode($output)."&query=$query";
        $n = count($output);
        $res['next'] = 1;
        $res['html'] = "Found $n pics in the $page th page</br>";
    }
    return $res;
}

if(isset($_POST['query'])){
    $query = $_POST['query'];
    $page = 1;
    if(isset($_POST['page']))
        $page= $_POST['page'];
    header('Content-Type: application/json');
    if(strpos($query, '@') != FALSE)
        echo json_encode (flickrImageSearch($query, $page));
    else
        echo json_encode(googleImageSearch($query, $page));    
}


?>
