jQuery(document).ready(function(){
	//主导航菜单
	$ = jQuery;
    $(".guanzhu").hover(function(){
		$(this).addClass("over");
		$(this).find('div:first').show();

	},
	function(){
		$(this).removeClass("over");
		$(this).find('div:first').hide();
	})
    
	$("#main-nav").find('li').hover(function(){
		$(this).addClass("over");
		$(this).find('div:first').show();

	},
	function(){
		$(this).removeClass("over");
		$(this).find('div:first').hide();
	})
	
	
	//购物车
	$("#minicart").hover(function(){
		$(".cart-content").fadeIn("show");
		$("#minicart .bnt").addClass("hover")
	},
	function(){		
		$(".cart-content").hide();
		$("#minicart .bnt").removeClass("hover")		
	});
	
	$(".catelogs").hover(function(){
		$(".cate_cont").show();
		$(".catelogs .bnt").addClass("hover")
	},
	function(){		
		$(".cate_cont").hide();
		$(".catelogs .bnt").removeClass("hover")		
	});
	
	$(".products-grid li dl").hover(function(){
		$(this).addClass("on")
	},function(){
		$(this).removeClass("on")
	});

	
	//商品对比
	var compare = jQuery(".block-compare");
	var closes= jQuery(".block-compare .close");
	var contents= jQuery(".block-compare .block-content");
	
	closes.click(function(){
		compare.hide();		
	});
	
	if($('#compare-items .item').length){
		compare.show();
	}else{
		compare.hide();
	}
	
	
	
	
});

function SelectStyle(on,option){
	on = "." + on;
	option = "." + option;
	var currentSort = $(on).attr('id');
	var currentText = $(option+" li."+currentSort + " a").html();
	$(on + " .text").addClass(currentSort).html(currentText);
	
	$(".selectbox").hover(function(){
		$(option).show()
	},function(){		
		$(option).hide()
		
	})
}

function decorateDataList(el, option){
	//option; // ['odd','even','first','last']
	$(el).find('>:last-child').addClass('last');
	$(el).find('>:first-child').addClass('first');
	$(el).find('>:nth-child(even)').addClass('even');
	$(el).find('>:nth-child(odd)').addClass('odd');
	
}
function decorateList(el,hover){
	el = '#' + el;
	$(el).find('>:last-child').addClass('last');
	$(el).find('>:first-child').addClass('first');
	$(el).find('>:nth-child(even)').addClass('even');
	$(el).find('>:nth-child(odd)').addClass('odd');
	if(hover){
		$(el).find('>*').hover(function(){
			$(this).addClass('hover');
		},
		function(){
			$(this).removeClass('hover');
		});
		}
}
function decorateTable(el){
	el = '#' + el;
	$(el).find('tbody tr:nth-child(even)').addClass('even');
	$(el).find('tbody tr:nth-child(odd)').addClass('odd');
	$(el).find('tr:last-child').addClass('last');
	$(el).find('tr:first-child').addClass('first');
}


function popWin(url,win,para) {
    var win = window.open(url,win,para);
    win.focus();
}
function setLocation(url){
    window.location.href = url;
}

function hoverdiv(mc,oper){
	$(mc).hover(function(){
		$(this).addClass("hoverbg");
		$(this).find(oper).show()
	},
	function(){
		$(this).find(oper).hide()
		$(this).removeClass("hoverbg");
	});
	
}

function formatNumber(number) {
	var parts = number.toString().split('.');
	var integer = parts[0].split('');
	var decimal = parts[1];
	var start = integer.length % 3;
	var length = integer.length;
	if(start == 0) {
		start = 3;
	}
	while(start < integer.length) {
		integer.splice(start, 0, ',');
		start += 4;
	}
	return [integer.join(''), decimal].join('.');
}