jQuery(document).ready(function(){ 
    var $cur = 1;
    var $i = 1;
    var $len = jQuery('.showbox>ul>li').length;
    var $pages = Math.ceil($len / $i);
    var $w = jQuery('.ibox').width();
    var $showbox = jQuery('.showbox');
    var $num = jQuery('ol.num li');
    var $pre = jQuery('a.pre');
    var $next = jQuery('a.next');
    $pre.click(function(){
        if (!$showbox.is(':animated')) {
            if ($cur == 1) {
                $showbox.animate({
                    left: '-=' + $w * ($pages - 1)
                }, 500);
                $cur = $pages;
            }
            else {
                $showbox.animate({
                    left: '+=' + $w
                }, 500); 
                $cur--; 
            }
            $num.eq($cur - 1).addClass('numcur').siblings().removeClass('numcur'); 
        }
    });
    $next.click(function(){
        if (!$showbox.is(':animated')) {
            if ($cur == $pages) { 
                $showbox.animate({
                    left: 0
                }, 500); 
                $cur = 1; 
            }
            else {
                $showbox.animate({
                    left: '-=' + $w
                }, 500);
                $cur++; 
            }
            $num.eq($cur - 1).addClass('numcur').siblings().removeClass('numcur'); 
        }
    });
    $num.mouseover(function(){
        if (!$showbox.is(':animated')) { 
            var $index = $num.index(this); 
            $showbox.animate({
                left: '-' + ($w * $index) 
            }, 500); 
            $cur = $index + 1; 
            jQuery(this).addClass('numcur').siblings().removeClass('numcur'); 
        }
    });
    $num.eq(0).mouseover();
    
    setInterval(function(){
    	$next.click();
	},3500);
});