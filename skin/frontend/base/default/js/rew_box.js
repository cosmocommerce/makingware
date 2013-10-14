
jQuery(document).ready(function(){
	jQuery(".idtabs .tablist li:first").addClass("current");
	jQuery(".idtabs dd.btab:not(:first)").hide();
	jQuery(".idtabs .tablist li").click(function(){
		jQuery(".idtabs .tablist li").removeClass("current");
		jQuery(this).addClass("current");  
		jQuery(".idtabs dd.btab").hide();  
		jQuery("."+jQuery(this).attr("id")).show();  
	});			
	
	jQuery(".block-related .mini-products-list li").hover(function(){
		jQuery(this).addClass("hover");
	},
	function(){
		jQuery(this).removeClass("hover");
	})
	
	if(jQuery(".product_related").html()==''){
		jQuery("#product_related").remove();
	}
	if(jQuery(".additional").html()==''){
		jQuery("#additional").remove();
	}
	
});

function changeDisplay(){
	jQuery("#rew_box").toggle();
}