function ajax(elementToChange, siteToRead) {
    if(typeof elementToChange === "undefined" || typeof siteToRead === "undefined") return false;
    if (window.XMLHttpRequest) {
        //code for IE7+ and all other good browser
        xmlhttp=new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            document.getElementById(elementToChange).innerHTML=xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET",siteToRead,true);
    xmlhttp.send();
}