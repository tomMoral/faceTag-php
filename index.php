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
                        var data = JSON.parse(xhr.responseText);
                        console.log(data['work']+"\n\n\n");
                        display($q).innerHTML += data['html'] + "</br>";
                        if(data['work'] == null) return;
                        if($q === 'GIG'){
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
                if($q === 'IL')
                    xhr.onprogress = function(e) {
                        display('IL').innerHTML += e.loaded +' / '+ e.total+'</br>';
                    };
            }
            
            start('GIG');
            
            
            /*function onreadyGIG(xhr) {
                return function(){
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        
                        alert("GIG_" +$page+"   "+xhr.responseText);
                        var data = JSON.parse(xhr.responseText);
                        if(data == null) return;
                        state.innerHTML += "Found "+ data.length + " pics in the " + $page + " th page</br>";
                        var n_xhr = new XMLHttpRequest();
                        var $urls = encodeURIComponent(xhr.responseText),
                            $query = encodeURIComponent('<?echo $query;?>');
                        n_xhr.open('POST','ImageLoader.php', true);
                        n_xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                        n_xhr.send('urls=' + $urls + '&query=' + $query);
                        console.log("Load pictures : " + $urls);
                        n_xhr.onreadystatechange = onreadyIL(n_xhr);
                    }
                };
            };
            
            function onreadyIL(xhr) {
                return function(){
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var data = JSON.parse(xhr.responseText);
                        state.innerHTML += "Load "+ data.length + " pics in the " + $page + " th page</br>";
                        if(data.length == 0) return;
                        var n_xhr = new XMLHttpRequest();
                        var $files = encodeURIComponent(xhr.responseText);
                        n_xhr.open('POST','FaceDetect.php', true);
                        n_xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                        n_xhr.send('files=' + $files);
                        console.log("Detect face in : " + $files);
                        n_xhr.onreadystatechange = onreadyFD(n_xhr);
                        
                        //Load the next page
                        var g_xhr = new XMLHttpRequest();
                        $page ++;
                        g_xhr.open('POST','GoogleImageGraber.php', true);
                        g_xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
                        g_xhr.send('query=' + $query + '&page=' + $page);
                        g_xhr.onreadystatechange = onreadyGIG(g_xhr);
                    }
                };
            };
            
            function onreadyFD(xhr) {
                return function(){
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert(xhr.responseText);
                        var data = JSON.parse(xhr.responseText);
                        state.innerHTML += data['stderr'];
                        alert(data['count']);
                        for(var i = 0; i < data['count'] ; i++){
                            state.innerHTML += '<img src="'+ data[i] +'">'
                        }
                    }
                };
            };
            
            var xhr = new XMLHttpRequest();
            
            xhr.open('POST','GoogleImageGraber.php', true);
            xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            xhr.send('query=' + $query + '&page=' + $page);
            xhr.onreadystatechange = onreadyGIG(xhr);
                    */
            
        </script>
            
        
        
        
            <?php

            // put your code here
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
