$(function(){
	$(".dropdown").click(function(){
		$(this).toggleClass("dropdown-click");
		$("#dropdown-menu").toggleClass("dropdown-open");
	});

	$(document).bind("click",function(e){          
        if($(e.target).closest("#dropdown-menu").length == 0 && $(e.target).closest(".dropdown").length == 0){
        	$("#dropdown-menu").removeClass("dropdown-open");
        	$(".dropdown").removeClass("dropdown-click");
        }
    });

	$(".sl-name").hover(function(){
		$(this).parent().css("background-color","#444444");
	},function(){
		$(this).parent().css("background-color","#515151");
	});

	$(".sidebar-list>li").click(function(){
		$(this).children("a").toggleClass("sl-name").toggleClass("sl-name-down").siblings(".sidebar-list-child").slideToggle("fast");
		$(this).siblings().children(".sidebar-list-child").slideUp("fast");
		$(this).siblings().children("a").removeClass("sl-name-down").addClass("sl-name");
	});

	$(".sidebar-list-child>li").hover(function(){
		$(this).css("background-color","#373737");
	},function(){
		$(this).css("background-color","#444444");
	});

	$(".image-box").click(function(){
		$(".img-operation").toggle();
	});
	$(".image-edit").click(function(){
		$(".img-operation").hide();
	});
	$(".image-delete").click(function(){
		$(".img-operation").hide();
		$("#show").attr("src", "/static/admin/image/no_image.jpg");
		$("#image-url").val("");
	});

	$(".config-nav-tabs").children("li").click(function(){
		$(this).addClass("active");
		$(this).siblings().removeClass("active");

		var data = $(this).children("a").data("tab");
		tab_active(data);
	});

	//确认框
	$("#cancel-button").click(function(){
		$(this).parent().parent().parent("#msg-confirm").fadeOut(200);
	});

	//关闭报错弹窗
	$(".alert-dismissible").click(function(){
		$(this).parent().addClass("fade");
	});

	//全选/反选
	$("input[name=check_all]").click(function(){
		if($(this).is(':checked')){
			$("input[name='check_one[]']").prop("checked", true);
		}else{
			$("input[name='check_one[]']").prop("checked", false);
		}
	});
});
function image_file(){
	$("#image-file").click();
}
function tab_active(data){
	$("#"+data).removeClass("fade");
	$("#"+data).siblings("div").removeClass("active").addClass("fade");
}

//AJAX删除列表
function ajax_delete_list(url){
	var del_id = [], del_id_str = '';
	$("input[name='check_one[]']:checked").each(function(){
		del_id.push($(this).val());
	});
	del_id_str = del_id.join(',');
	var data = {'data' : del_id_str};
	$.ajax({
		type : "POST",
		url : url,
		data : data,
		dataType : "json",
		beforeSend : function(){
			$(".loading-image").removeClass("fade");
		},
		success : function(data){
			$(".msg-popup-content").text(data.msg);
			$("#msg-popup").fadeIn(200);
			$(".msg-popup-button>a").click(function(){
				window.location.reload();
			});
		},
		complete : function(){
			$(".loading-image").addClass("fade");
		}
	});
}

//AJAX获取列表方法
function ajax_get_list(data, url){
	$.ajax({
		type : "POST",
		url : url,
		data : data,
		dataType : "json",
		beforeSend : function(){
			$(".loading-image").removeClass("fade");
		},
		success : function(data){
			ajax_assemble(data);
			$("#page").html(data.data.page);
		},
		complete : function(){
			$(".loading-image").addClass("fade");
		}
	});
}

//JS时间格式
function time_format(time_stamp, is_time_divisions){
	var date = new Date(time_stamp*1000),
	 	year = date.getFullYear(),
	 	month = date.getMonth(),
	 	day = date.getDate();

	var time_format = year + '-' + month + '-' + day;

	//设定时间格式需要时分秒
	if(is_time_divisions == true){
		var hour = date.getHours(),
			minute = date.getMinutes(),
			second = date.getSeconds();
		
		time_format += '&nbsp;&nbsp;' + hour + ':' + minute + ':' + second;
	}

	return time_format;
}