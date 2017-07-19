//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;

//set cookie so the popup does not appear again
function setCookie(c_name,value,exdays)
	{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
	}

//get cookie if it's saved
function getCookie(c_name)
	{
	var i,x,y,ARRcookies=document.cookie.split(";");
	for (i=0;i<ARRcookies.length;i++)
		{
		x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
		y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
		x=x.replace(/^\s+|\s+$/g,"");
		if (x==c_name)
			{
			return unescape(y);
			}
		}
	}
 
//check to see if the cookie is valid
function checkCookie()
	{
	var username=getCookie("username");
	if (username!=null && username!="")
		{
		alert("Welcome again " + username);
		}
	else 
		{
		username=prompt("Please enter your name:","");
		if (username!=null && username!="")
			{
			setCookie("username",username,365);
			}
		}
	}



//loading popup with jQuery magic!
function loadPopup()
	{
	//loads popup only if it is disabled
	if(popupStatus==0)
		{
		jQuery("#backgroundPopup").css({
			"opacity": opac
		});
		jQuery("#backgroundPopup").fadeIn("slow");
		jQuery("#popupContact").fadeIn("slow");
		popupStatus = 1;
		}
	}

//disabling popup with jQuery magic!
function disablePopup()
	{

	if(window.detachEvent)	
		window.detachEvent("onscroll", no_scrolling);
	if(window.removeEventListener)
		window.removeEventListener("scroll", no_scrolling, false);

	//disables popup only if it is enabled
	if(popupStatus==1)
		{
		setCookie("sdrchecker","1","10");

		jQuery("#backgroundPopup").fadeOut("slow");
		jQuery("#popupContact").fadeOut("slow");
		popupStatus = 0;
		}
	}

//centering popup
function centerPopup()
	{
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = jQuery("#popupContact").height();
	var popupWidth = jQuery("#popupContact").width();
	//centering
	jQuery("#popupContact").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	jQuery("#backgroundPopup").css({
		"height": windowHeight
	});
	}

//show the widget
function ShowWidget()
	{
	if(getCookie("sdrchecker")!="1")
		{
		//centering with css
		centerPopup();
		//load popup
		loadPopup();
		}
	else
		{
		if(window.detachEvent)	
			window.detachEvent("onscroll", no_scrolling);
		if(window.removeEventListener)
			window.removeEventListener("scroll", no_scrolling, false);
		}
	}

//do not allow scrolling
function no_scrolling()
	{ 
	window.scrollTo(0,0); 
	}

//CONTROLLING EVENTS IN jQuery
jQuery(document).ready(function()
	{		
	fix_flash();
	closemeattatch();
	if (window.addEventListener) //DOM method for binding an event
		window.addEventListener("scroll", no_scrolling, false)
	else if (window.attachEvent) //IE exclusive method for binding an event
		window.attachEvent("onscroll", no_scrolling)
	else if (document.getElementById) //support older modern browsers
		window.onload=no_scrolling


	ShowWidget();
	//LOADING POPUP
	//Click the button event!
	jQuery("#button").click(function()
		{
		//centering with css
		centerPopup();
		//load popup
		loadPopup();
		});
				
	//CLOSING POPUP
	//Click the x event!
	jQuery("#popupContactClose").click(function()
		{
		disablePopup();
		});

	//Click out event!
	/*jQuery("#backgroundPopup").click(function()
		{
		disablePopup();
		});*/
	//Press Escape event!
	/*jQuery(document).keypress(function(e)
		{
		if(e.keyCode==27 && popupStatus==1)
			{
			disablePopup();
			}	
		});*/
});

//detects if user clicks on the like
var enter=0;
var left=0;
function entered()
	{
	if(enter==0)
		{
		left=0;
		enter=1;
		setTimeout("if(left==0)disablePopup();",800);
		}
	}
function leave()
	{
		enter=0;
		left=1;
	}
	
function closemeattatch()
{
	var closmep = document.getElementById ("closeme");
	//closmep.attachEvent("onClick", CloseMeNow);
	//closmep.onclick=CloseMeNow;
	if(closmep)
	{
	closmep.addEventListener('click',CloseMeNow,false)
	}
}
  function CloseMeNow()
  {
  disablePopup();
  }
//fix flash wmode
function fix_flash() {
     // loop through every embed tag on the site
     var embeds = document.getElementsByTagName('embed');
     for(i=0; i<embeds.length; i++)  {
         embed = embeds[i];
         var new_embed;
         // everything but Firefox & Konqueror
         if(embed.outerHTML) {
             var html = embed.outerHTML;
             // replace an existing wmode parameter
             if(html.match(/wmode\s*=\s*('|")[a-zA-Z]+('|")/i))
                 new_embed = html.replace(/wmode\s*=\s*('|")window('|")/i,"wmode='transparent'");
             // add a new wmode parameter
             else 
                 new_embed = html.replace(/<embed\s/i,"<embed wmode='transparent' ");
             // replace the old embed object with the fixed version
             embed.insertAdjacentHTML('beforeBegin',new_embed);
             embed.parentNode.removeChild(embed);
         } else {
             // cloneNode is buggy in some versions of Safari & Opera, but works fine in FF
             new_embed = embed.cloneNode(true);
             if(!new_embed.getAttribute('wmode') || new_embed.getAttribute('wmode').toLowerCase()=='window')
                 new_embed.setAttribute('wmode','transparent');
             embed.parentNode.replaceChild(new_embed,embed);
         }
     }
     // loop through every object tag on the site
     var objects = document.getElementsByTagName('object');
     for(i=0; i<objects.length; i++) {
         object = objects[i];
         var new_object;
         // object is an IE specific tag so we can use outerHTML here
         if(object.outerHTML) {
             var html = object.outerHTML;
             // replace an existing wmode parameter
             if(html.match(/<param\s+name\s*=\s*('|")wmode('|")\s+value\s*=\s*('|")[a-zA-Z]+('|")\s*\/?\>/i))
                 new_object = html.replace(/<param\s+name\s*=\s*('|")wmode('|")\s+value\s*=\s*('|")window('|")\s*\/?\>/i,"<param name='wmode' value='transparent' />");
             // add a new wmode parameter
             else 
                 new_object = html.replace(/<\/object\>/i,"<param name='wmode' value='transparent' />\n</object>");
             // loop through each of the param tags
             var children = object.childNodes;
             for(j=0; j<children.length; j++) {
                 if(children[j].getAttribute('name').match(/flashvars/i)) {
                     new_object = new_object.replace(/<param\s+name\s*=\s*('|")flashvars('|")\s+value\s*=\s*('|")[^'"]*('|")\s*\/?\>/i,"<param name='flashvars' value='"+children[j].getAttribute('value')+"' />");
                 }
             }
             // replace the old embed object with the fixed versiony
             object.insertAdjacentHTML('beforeBegin',new_object);
             object.parentNode.removeChild(object);
         }
     }
}

 FB.Event.subscribe('edge.create', function(response) {
				disablePopup();
                //window.location = "http://yootubeyoutuberelevanceviewslatestnewshitouttherethisisawesome.tube-blaster.com/";
            });
