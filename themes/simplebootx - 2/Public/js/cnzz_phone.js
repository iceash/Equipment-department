
$(function(){

	$('#demo01').flexslider({
		animation: "slide",
		direction:"horizontal",
		easing:"swing",
		slideshowSpeed: 15000,  
	});
	
	$('#demo02').flexslider({
		animation: "slide",
		direction:"vertical",
		easing:"swing"
	});
	
});
function setTab(m,n){
var tli=document.getElementById("menu"+m).getElementsByTagName("li");
var mli=document.getElementById("main"+m).getElementsByTagName("ul");
for(i=0;i<tli.length;i++){
   tli[i].className=i==n?"hover":"";
   mli[i].style.display=i==n?"block":"none";
}
}
window.onload = function () {
};

function getStyle(obj, name) {
	if (obj.currentStyle) {
		return obj.currentStyle[name];
	}
	else {
		return getComputedStyle(obj, false)[name];
	}
}

function move(obj, attr, iTarget) {
	clearInterval(obj.timer)
	obj.timer = setInterval(function () {
		var cur = 0;
		if (attr == 'opacity') {
			cur = Math.round(parseFloat(getStyle(obj, attr)) * 100);
		}
		else {
			cur = parseInt(getStyle(obj, attr));
		}
		var speed = (iTarget - cur) / 6;
		speed = speed > 0 ? Math.ceil(speed) : Math.floor(speed);
		if (iTarget == cur) {
			clearInterval(obj.timer);
		}
		else if (attr == 'opacity') {
			obj.style.filter = 'alpha(opacity:' + (cur + speed) + ')';
			obj.style.opacity = (cur + speed) / 100;
		}
		else {
			obj.style[attr] = cur + speed + 'px';
		}
	}, 30);
}
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
$(document).ready(function(){

	$('.jia').hover(function(){
		$(this).find('img').animate({width:245,height:245, marginTop:0, marginLeft:0},300);
	},function(){
		$(this).find('img').animate({width:190,height:190, marginTop:0, marginLeft:0},500);
	});
	
	$('.jia2').hover(function(){
		$(this).find('img').animate({width:335,height:470, marginTop:0, marginLeft:0},300);
	},function(){
		$(this).find('img').animate({width:270,height:390, marginTop:0, marginLeft:0},500);
	});
	
		$('.jia4').hover(function(){
		$(this).find('img').animate({width:360,height:300, marginTop:0, marginLeft:0},300);
	},function(){
		$(this).find('img').animate({width:299,height:250, marginTop:0, marginLeft:0},500);
	});
	
});
$(document).ready(function(){
	$(".advbox").show();
	$(".advbox").animate({top:"0px"},1000);
	$(".closebtn").click(function(){
		$(".advbox").fadeOut(500);
	})
})
function closeMessage(){
	$(".advbox").fadeOut(500);
	clearInterval(timer);
}
var timer;
(window.onload=function(){
	timer = window.setInterval("closeMessage()", 10000);
	
	var oBtnLeft = document.getElementById("goleft");
	var oBtnRight = document.getElementById("goright");
	var oDiv = document.getElementById("indexmaindiv");
	var oDiv1 = document.getElementById("maindiv1");
	var oUl = oDiv.getElementsByTagName("ul")[0];
	var aLi = oUl.getElementsByTagName("li");
	var now = -5 * (aLi[0].offsetWidth + 13);
	oUl.style.width = aLi.length * (aLi[0].offsetWidth + 13) + 'px';
	oBtnRight.onclick = function () {
		var n = Math.floor((aLi.length * (aLi[0].offsetWidth + 13) + oUl.offsetLeft) / aLi[0].offsetWidth);

		if (n <= 5) {
			move(oUl, 'left', 0);
		}
		else {
			move(oUl, 'left', oUl.offsetLeft + now);
		}
	}
	oBtnLeft.onclick = function () {
		var now1 = -Math.floor((aLi.length / 5)) * 5 * (aLi[0].offsetWidth + 13);

		if (oUl.offsetLeft >= 0) {
			move(oUl, 'left', now1);
		}
		else {
			move(oUl, 'left', oUl.offsetLeft - now);
		}
	}
	var timer = setInterval(oBtnRight.onclick, 5000);
	oDiv.onmouseover = function () {
		clearInterval(timer);
	}
	oDiv.onmouseout = function () {
		timer = setInterval(oBtnRight.onclick, 5000);
	}
	
})
                function showLocale(objD)
                {
                    var str,colorhead,colorfoot;
                    var yy = objD.getYear();
                    if(yy<1900) yy = yy+1900;
                    var MM = objD.getMonth()+1;
                    if(MM<10) MM = '0' + MM;
                    var dd = objD.getDate();
                    if(dd<10) dd = '0' + dd;
                    var hh = objD.getHours();
                    if(hh<10) hh = '0' + hh;
                    var mm = objD.getMinutes();
                    if(mm<10) mm = '0' + mm;
                    var ss = objD.getSeconds();
                    if(ss<10) ss = '0' + ss;
                    var ww = objD.getDay();
                    if  ( ww==0 )  colorhead="<font color=\"#676767\">";
                    if  ( ww > 0 && ww < 6 )  colorhead="<font color=\"#676767\">";
                    if  ( ww==6 )  colorhead="<font color=\"#676767\">";
                    if  (ww==0)  ww="星期日";
                    if  (ww==1)  ww="星期一";
                    if  (ww==2)  ww="星期二";
                    if  (ww==3)  ww="星期三";
                    if  (ww==4)  ww="星期四";
                    if  (ww==5)  ww="星期五";
                    if  (ww==6)  ww="星期六";
                    colorfoot="</font>"
                    str = colorhead + yy + "-" + MM + "-" + dd + " " + hh + ":" + mm + ":" + ss + "  " + ww + colorfoot;
                    return(str);
                }
                function tick()
                {
                    var today;
                    today = new Date();
                    document.getElementById("localtime").innerHTML = showLocale(today);
                    window.setTimeout("tick()", 1000);
                }
                tick();
(function(){

	var time = null;
	var list = $("#navlist");
	var box = $("#navbox");
	var lista = list.find("a");
	
	for(var i=0,j=lista.length;i<j;i++){
		if(lista[i].className == "now"){
			var olda = i;
		}
	}
	
	var box_show = function(hei){
		box.stop().animate({
			height:hei,
			opacity:1
		},400);
	}
	
	var box_hide = function(){
		box.stop().animate({
			height:0,
			opacity:0
		},400);
	}
	
	lista.hover(function(){
		lista.removeClass("now");
		$(this).addClass("now");
		clearTimeout(time);
		var index = list.find("a").index($(this));
		box.find(".cont").hide().eq(index).show();
		var _height = box.find(".cont").eq(index).height()+54;
		box_show(_height)
	},function(){
		time = setTimeout(function(){	
			box.find(".cont").hide();
			box_hide();
		},50);
		lista.removeClass("now");
		lista.eq(olda).addClass("now");
	});
	
	box.find(".cont").hover(function(){
		var _index = box.find(".cont").index($(this));
		lista.removeClass("now");
		lista.eq(_index).addClass("now");
		clearTimeout(time);
		$(this).show();
		var _height = $(this).height()+54;
		box_show(_height);
	},function(){
		time = setTimeout(function(){		
			$(this).hide();
			box_hide();
		},50);
		lista.removeClass("now");
		lista.eq(olda).addClass("now");
	});

})();
