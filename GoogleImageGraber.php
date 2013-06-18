<?php

function googleImageSearch ($query, $page, $debug=false)
{
    if($debug){
        
        $retVal = [
            
            [
                "http://casafamilyday.org/familyday/files/media/Family%20of%204%20outside_new%20homepage%20photo.jpg",
                "http://www.southpointefamilyresourcecenter.com/images/family-on-stomachs-smile.jpg"
            ],
            [
                "http://familyreformation.files.wordpress.com/2007/10/family1.jpg",
                "http://www.anieleirose.org/images/OurFamily01.jpg"
            ]
        ];
        
        $res = array();
        $res['next'] = 0;
        $res['html'] = "Grab all the results....</br>";
        if (isset($retVal[$page-1])) {
            $res['work'] = "urls=".json_encode($retVal[$page-1])."&query=$query";
            $n = count($retVal[$page-1]);
            $res['next'] = 1;
            $res['html'] = "Found $n pics in the $page th page</br>";
        }
        return $res;
    }
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

if(isset($_POST['query'])){
    $query = $_POST['query'];
    $page = 1;
    if(isset($_POST['page']))
        $page= $_POST['page'];
    header('Content-Type: application/json');
    echo json_encode(googleImageSearch($query, $page));    
}


?>
