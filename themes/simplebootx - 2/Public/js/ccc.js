// JavaScript Document

                    var rnum=$(".mkeUl ul li").size();
                    var cnum=0;
                    $(".mke_ns2").html(rnum);
                    $(".mkeUl ul").width(rnum*320);
                    $(".mkeRbtn").click(function(){
                        cnum++;
                        if(cnum>(rnum-1)){
                        cnum=0	
                        }
                        $(".mkeUl ul").animate({"left":-cnum*320},290);
                        $(".mke_ns1").html(cnum+1);
                    });
                    $(".mkeLbtn").click(function(){
                        cnum--;
                        if(cnum<0){
                        cnum=rnum-1;	
                        }
                        $(".mkeUl ul").animate({"left":-cnum*320},290);
                        $(".mke_ns1").html(cnum+1);
                    });
                    
                    function autoPlay(){
                        cnum++;
                        if(cnum>(rnum-1)){
                        cnum=0	
                        }
                        $(".mkeUl ul").animate({"left":-cnum*320},290);
                        $(".mke_ns1").html(cnum+1);
                    }
                    var Timer=setInterval(autoPlay,4000);
                    $(".mkeFocus").hover(function(){clearInterval(Timer)},function(){Timer=setInterval(autoPlay,4000);});
