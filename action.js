/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var last_state='remove',
    toTag = 0,
    tagged = 0;

function check(event){
    var cls = event.target.className;
    if(cls != "img_neu")
        event.target.className = "img_neu";
    else if(event.button == 0)
        event.target.className = "img_true";
    else
        event.target.className = "img_false";
    
}

function add_label(xhr, cb){
    return (function(){
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert(xhr.responseText);
            var label = JSON.parse(xhr.responseText);
            alert('Add new label :' + label['label']);
            if(label['id'] === 0) return;
            var option = new Option(label['label'], label['id']);
            cb.appendChild(option);
        }
    })
}

function load_pics(req){
    return (function() { 
        if (req.readyState === 4 && req.status === 200) {
            var data = JSON.parse(req.responseText);
            var div_pics = document.getElementById("pictures");
            while(div_pics.childElementCount > 0){
                div_pics.removeChild(div_pics.firstChild);
            }
            toTag = data['count'];
            tagged = data['tot'] - data['count'];
            var count = document.createElement("p");
            count.innerHTML = "There is " + tagged + " tagged pictures for the current label";
            count.innerHTML += " and " + toTag + " pictures to tag";
            count.id = "status";
            div_pics.appendChild(count);
            
            for(var i = 0; i < data['count']; i++){
                var pic = document.createElement("img");
                pic.className = "img_neu";
                pic.oncontextmenu = (function(){return false;});
                pic.onmousedown = check;
                pic.setAttribute('src', data[i]['path']);
                pic.setAttribute('id', data[i]['id']);
                div_pics.appendChild(pic);
            }
        }})
}


function comboBox(event){
    var select = event.target;
    var val = select.value;
    if(val === 'add'){
        select.value = last_state;
        var label = prompt("New label :" , "");
        if(label=== null || label === "") 
            return;
        var xhr = new XMLHttpRequest();
        xhr.open('POST',"add_lab.php", true);
        xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhr.send("label="+label);
        xhr.onreadystatechange = add_label(xhr,select);
    }
    else if ( val !== last_state){
        if(val === 'remove'){
            val = 0;
            alert(val);
        }
        var xhr = new XMLHttpRequest();
        xhr.open('POST',"get_label.php", true);
        xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhr.send("label="+val);
        xhr.onreadystatechange = load_pics(xhr);
    }
    
}



function saved(req){
    return (function() { 
        if (req.readyState === 4 && req.status === 200) {
            alert('Saved!' + req.responseText);
        }});
}

function save(){
    var images_true = document.getElementsByClassName("img_true");
    var images_false = document.getElementsByClassName("img_false");
    var lab = document.getElementById('combo').value;
    if(lab === 'add') return;
    if(lab === 'remove')
        lab ='R';
    var post = 'label='+ lab + '&list_t=';
    var i =0;
    var tmp = images_true.length + images_false.length;
    tagged +=  tmp;
    toTag -= tmp;
    while(images_true.length > 0 && lab !== 'R'){
        post += images_true[i].id + ',';
        images_true[i].parentNode.removeChild(images_true[i]);
    }
    post += '&list_n=';
    while(images_false.length > 0){
        post += images_false[i].id + ',';
        images_false[i].parentNode.removeChild(images_false[i]);
    }
    var xhr = new XMLHttpRequest();
    xhr.open('POST',"save_label.php", true);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.send(post);
    xhr.onreadystatechange = saved(xhr);
    var stat = document.getElementById("status");
    stat.innerHTML = "There is " + tagged + " tagged pictures for the current label";
    stat.innerHTML += " and " + toTag + " pictures to tag";
    
}

function csv(req){
    return (function() { 
        if (req.readyState === 4 && req.status === 200) {
            var stat = document.getElementById("status");
            stat.innerHTML =  req.responseText;
        }});
}

function get(){
    var lab = document.getElementById('combo').value;
    var xhr = new XMLHttpRequest();
    xhr.open('POST',"get_db.php", true);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xhr.send("label="+lab);
    xhr.onreadystatechange = csv(xhr);
    
}
