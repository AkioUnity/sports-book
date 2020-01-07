(function($) {

jQuery.fn.mbTooltip = function (options){
	return this.each (function () {
		this.options = {
			opacity : .9,
			wait:2000,
			timePerWord:70,
			cssClass:"default",
			hasArrow:true,
			imgPath:"images/",
			hasShadow:true,
			shadowColor:"black",
			shadowLeft:1,
			ancor:"mouse", //"parent",
			shadowTop:1,
			fade:200
		}
		$.extend (this.options, options);
		var ttEl=$(this).is("[title]")? $(this): $(this).find("[title]");
		var wait=this.options.wait;
		var hasShadow=this.options.hasShadow;
		var fade=this.options.fade;
		var myOptions=this.options;
		$(ttEl).each(function(){
			$(this).attr("tooltip", $(this).attr("title"));
			$(this).removeAttr("title")
			$(this).attr("tooltipEnable",true)
			var theEl=$(this);
			var ttCont= theEl.attr("tooltip");
			var hover=$.browser.msie?"mouseenter":"mouseover";
			$(this).bind(hover,function(e){
				if (myOptions.ancor=="mouse") $().getXY();
				$(this).one("mouseout",function(){
					$(this).stopTime();
					$(this).deleteTooltip(hasShadow,fade);
				}).one("click",function(){
					$(this).stopTime();
					$(this).deleteTooltip(hasShadow,fade);
				})
				$(this).oneTime(wait, function() {
					if ($(this).attr("tooltipEnable")=="true")
						$(this).buildTooltip(ttCont,myOptions,e);
				})
			})
		})
	})
};

var X = 0;
var Y = 0;

$.fn.extend({
	getXY:function(){
		$().bind("mousemove", function(e) {
			X = e.pageX;
			Y = e.pageY;
		});
	},
	buildTooltip: function(cont,options){
		this.options={};
		$.extend (this.options, options);
		var parent=$(this);
		$("body").append("<div id='tooltip'></div>");
		var imgUrl=this.options.imgPath+"up.png";
		$("#tooltip").html(cont);
		$("#tooltip").addClass(this.options.cssClass)
		if (this.options.hasArrow){
			$("#tooltip").prepend("<img id='ttimg' src='"+imgUrl+"'>");
			$("#ttimg").css({
				position:"absolute",
				opacity:.5
			})
			$("#ttimg").addClass("top");
		}
		$("#tooltip").css({
			position:"absolute",
			top:  this.options.ancor=="mouse"?Y+7:parent.offset().top+(parent.outerHeight()),
			left:this.options.ancor=="mouse"?X+7:parent.offset().left,
			opacity:0
		})
		$("#tooltip").findBestPos(parent,this.options.imgPath,this.options.ancor);
		if (this.options.ancor=="mouse") $().unbind("mousemove");
		if (this.options.hasShadow) {
			$("#tooltip").dropShadow({left: 1, top: 1, blur: 2, opacity: 0.3, color:this.options.shadowColor});
			$(".dropShadow").css("display","none")
			$(".dropShadow").fadeIn(this.options.fade);
		}
		$("#tooltip").fadeTo(this.options.fade,this.options.opacity,function(){});
		var timetoshow=3000+cont.length*this.options.timePerWord;
		var hasShadow=this.options.hasShadow;
		var fade=this.options.fade;
		$(this).oneTime(timetoshow,function(){$(this).deleteTooltip(hasShadow,fade)})
	},
	deleteTooltip: function(hasShadow,fade){
		var sel=hasShadow?"#tooltip,.dropShadow":"#tooltip"
		$(sel).fadeOut(fade,function(){$(sel).remove()});
	},
	findBestPos:function(parent,imgPath,ancor){
		var theEl=$(this);
		var ww= $(window).width()+$(window).scrollLeft();
		var wh= $(window).height()+$(window).scrollTop();
		var w=theEl.outerWidth();
		theEl.css({width:w});
		var t=((theEl.offset().top+theEl.outerHeight(true))>wh)? theEl.offset().top-(ancor!="mouse"? parent.outerHeight():0)-theEl.outerHeight()-20 : theEl.offset().top;
		t=t<0?0:t;
		var l=((theEl.offset().left+w)>ww-5) ? theEl.offset().left-(w-(ancor!="mouse"?parent.outerWidth():0)) : theEl.offset().left;
		l=l<0?0:l;
		if (theEl.offset().top+theEl.outerHeight(true)>wh){
			$("#ttimg").attr("src",imgPath+"bottom.png")
			$("#ttimg").removeClass("top").addClass("bottom");
		}
		theEl.css({width:w, top:t, left:l});
	},
	disableTooltip:function(){
		$(this).find("[tooltip]").attr("tooltipEnable",false);
	},
	enableTooltip:function(){
		$(this).find("[tooltip]").attr("tooltipEnable",true);
	}
})

})(jQuery)