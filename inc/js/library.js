/* 

*/

/*NOW COMES THE --------COOKIES----------*/

//Extracts a cookie of the document.cookie string
//You can call it simply by typing getCookie("ExampleName");
//IMORTANT: I fixed the problem that if u had 2 cookies
//  ffoo=bar
//  foo=bar
//And you extracted "foo" you got the value of ffoo...

function getCookie(c_name)
{
    if(c_name !== null ||typeof c_name === "undefined")
    {
        if(navigator.cookieEnabled){
		//Split it and put it in a 2D-Array
		var Single = document.cookie.split(";");
		var length = Single.length;
		
		for (var i = 0; i < length; i++)
		{
                        //Split in Name and Value pairs
			Single[i] = Single[i].split("=");
			//Remove Whitespaces in front ant et the end of the node
                        for(var a = 0; a < Single[i].length; a++)
			{
				Single[i][a] = Single[i][a].replace (/^\s+/, '').replace(/\s+$/, '');
			}
		}
		//Now go through the Array and search for the Parameter
		for (i = 0; i < length; i++)
		{
			if(Single[i][0] === c_name)
			{
				return Single[i][1];
			}
		}	
                //CASE: Not Found
		return false;
	} else {
            //CASE: noCookies
            return false;
	}
    }
    else
    {
        //CASE: no Parameter
        return false;
    }
}

/*NOW COMES THE -------INPUT BOXES---------*/
/*Clear textboxes after loading site*/
if(document.getElementById("regUser"))
    document.getElementById("regUser").value = "";
if(document.getElementById("regPwd"))
    document.getElementById("regPwd").value = "";
if(document.getElementById("regPwdAgain"))
    document.getElementById("regPwdAgain").value = "";
if(document.getElementById("regEmail"))
    document.getElementById("regEmail").value = "";
if(document.getElementById("username"))
    document.getElementById("username").value = "";
if(document.getElementById("password"))
    document.getElementById("password").value = "";
