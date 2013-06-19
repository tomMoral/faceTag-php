<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Image grabber</title>
    </head>
    <body>
        <?php
        if(isset($_GET['query'])){
            $query = $_GET['query'];
            $page = 1;
        ?>
            <h1>Grabbing the pictures from google image for the query : <? echo $query;?> </h1>
            <div id="GIG"></div>
            <div id="IL"></div>
            <div id="FD"></div>
         
        <script>
            function display($q){
                return document.getElementById($q);
            }
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
                        if ($q === 'FD')
                            display($q).innerHTML += data['html'] + "</br>";
                        else
                            display($q).innerHTML = data['html'] + "</br>";
                        if($q === 'GIG' && data['work'] != null ){
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

        <?php    
        }
        ?>
    </body>
</html>
