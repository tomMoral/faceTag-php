<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Image grabber</title>
    </head>
    <body>
        <?php
        if(isset($_GET['query']) && !file_exists("image/".$_GET['query'])){
            $query = $_GET['query'];
            $page = 1;
        ?>
            <h1>Grabbing the pictures from Google/Flickr image for the query : <? echo $query;?> </h1>
            <div id="status">Grabbed 0 faces...<button onclick="location.href='faceTag.php';">Tag faces</button></div>
            <div id="stderr"></div>
            <div id="GIG"></div>
            <div id="IL"></div>
            <div id="FD"></div>
         
        <script>
            function display($q){
                return document.getElementById($q);
            }
            var debug = true;
            var $query = encodeURIComponent('<?echo $query;?>'),
                $page = <?echo $page;?>;
            var queues= { "GIG": ["query=<?echo $query;?>&page=1"],
                           "IL" : [],
                           "FD" : []
                         };
            var proc = { "GIG": "GoogleImageGraber.php",
                          "IL" : "ImageLoader.php",
                          "FD" : "FaceDetect.php"
            };
            var next = { "GIG": "IL", "IL" : "FD"};
            var grabbed = 0;
            var stat = document.getElementById("status"),
                stderr = document.getElementById("stderr");
            
            function add_work($q, $w){
                queues[$q].push($w);
                if(queues[$q].length === 1)
                    start($q);
            }
            
            function onready(xhr, $q) {
                return function(){
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        console.log(xhr.responseText);
                        var data = JSON.parse(xhr.responseText);
                        if ($q === 'FD'){
                            if(data['html'] !== '')
                                display($q).innerHTML += data['html'] + "</br>";
                            else
                                stderr.innerHTML = data['out'];
                            grabbed += data['count'];
                            stat.innerHTML = "Grabbed " + grabbed + " faces ...";
                            stat.innerHTML += "<button onclick=\"location.href='faceTag.php';\">Tag faces</button>"
                        }
                        else
                            display($q).innerHTML = data['html'] + "</br>";
                        if($q === 'GIG' && data['work'] != null && !debug ){
                            $page ++;
                            add_work($q, "query=<?echo $query;?>&page=" + $page);
                        }
                        if(data['next'] === 1)
                            add_work(next[$q], data['work']);
                        start($q);
                    }
                };
            };
            
            function start($q){
                if(queues[$q].length === 0)
                    return;
                var job = queues[$q].shift();
                
                var xhr = new XMLHttpRequest();
                xhr.open('POST',proc[$q], true);
                xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                xhr.send(job);
                xhr.onreadystatechange = onready(xhr, $q);
            }
            
            start('GIG');
        </script>
            
        
        
        
            <?php
        }
        else{
        ?>
            <form name="input" action="index.php" methode="get">
                Query: <input type="text" name="query">
                <input type="submit" value="Grab">
            </form>
        <button onclick="location.href='faceTag.php';">Tag faces</button>

        <?php    
        }
        ?>
    </body>
</html>
