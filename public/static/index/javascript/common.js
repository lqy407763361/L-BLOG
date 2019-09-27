$(function(){
	/*login & register*/
	$(".login_button").click(function(){
		$(".mask").fadeIn(200);
		$("#login-popup").fadeIn(200);
		$(".login-tab").addClass("active").siblings("li").removeClass("active");
		$(".login-content").addClass("active").removeClass("fade").siblings("div").removeClass("active").addClass("fade");
	});
	$(".register_button").click(function(){
		$(".mask").fadeIn(200);
		$("#login-popup").fadeIn(200);
		$(".register-tab").addClass("active").siblings("li").removeClass("active");
		$(".register-content").addClass("active").removeClass("fade").siblings("div").removeClass("active").addClass("fade");
	});
	$(".mask").click(function(){
		$("#login-popup").fadeOut(200);
		$(this).fadeOut(200);
	});
	$(".login-popup-tab>li").click(function(){
		var tab = $(this).data("tab");
		$(this).addClass("active").siblings("li").removeClass("active");
		$("."+tab).addClass("active").removeClass("fade").siblings("div").removeClass("active").addClass("fade");
	});

	/*mobile nav*/
	$(".mobile-nav-button").click(function(){
		$(".mobile-nav").toggleClass("mobile-nav-fadein");
		$(".mask").fadeIn(200);
	});
	$(".mobile-close").click(function(){
		$(".mobile-nav").removeClass("mobile-nav-fadein");
		if($("#login-popup").is(":hidden")){
       		$(".mask").fadeOut(200);
		}
	});
	$(".mask").click(function(){
		$(".mobile-nav").removeClass("mobile-nav-fadein");
		$(".mask").fadeOut(200);
	});
	$(".footer-right .fa-weixin").click(function(){
		$(".footer-right .wechat-alert").toggle();
	});
});