define(["core","tpl","biz/member/cart","biz/plugin/diyform"],function(n,s,i,e){var c={goodsid:0,goods:[],option:!1,specs:[],options:[],params:{titles:"",optionthumb:"",split:";",option:!1,total:1,optionid:0,onSelected:!1,onConfirm:!1,autoClose:!0},open:function(e){if(c.params=$.extend(c.params,e||{}),c.goodsid!=e.goodsid||e.refresh){c.specs=[],c.options=[],c.option=!1,c.params.optionid=0,c.goodsid=e.goodsid;var i={id:e.goodsid};e.liveid&&(i.liveid=e.liveid),e.cangift&&(i.cangift=e.cangift),n.json("goods/picker",i,function(i){if(0!=i.status){if(c.followtip="",c.followurl="",2==i.status)return c.followtip=i.result.followtip,c.followurl=i.result.followurl,c.followqrcode=i.result.followqrcode,""!=c.followqrcode&&null!=c.followqrcode&&(c.containerFollowHTML=s("followqrcode",i.result)),void c.show();if(4==i.status)return c.followtip=0,c.needlogin=1,c.endtime=i.result.endtime||0,c.imgcode=i.result.imgcode||0,void c.show();if(3==i.status)return c.followtip=0,c.needlogin=0,c.mustbind=1,c.endtime=i.result.endtime||0,c.imgcode=i.result.imgcode||0,void c.show();if(5==i.status)return FoxUI.toast.show(i.result.message),void(c.goodsid="");var o=window.screen.width*window.devicePixelRatio,t=window.screen.height*window.devicePixelRatio;i.result.width=o,i.result.height=t,c.containerHTML=s("option-picker",i.result),c.goods=i.result.goods,c.specs=i.result.specs,c.options=i.result.options,c.seckillinfo=i.result.seckillinfo,""==c.goods.unit&&(c.goods.unit="件"),c.needlogin=0,c.followtip=0,c.mustbind=0,c.show(),""!=e.action&&null!=e.action||(0<i.result.goods.giftid?$(".cartbtn").hide():$(".cartbtn").show(),$(".confirmbtn").hide(),$(".buybtn").show())}else FoxUI.toast.show("未找到商品!")},!0,!1)}else c.show(),""==e.action&&($(".confirmbtn").hide(),$(".cartbtn").show(),$(".buybtn").show())},close:function(){c.container.close()},init:function(){$(".other-time").click(function(){$(".cyceltime").css("display","block"),$(".cyclenotime").css("display","none"),$(".cancelbtn").css("display"," table-cell")});var t=document.documentElement.clientHeight||document.body.clientHeight;$(window).on("resize",function(){if((document.documentElement.clientHeight||document.body.clientHeight)<t){var i=document.body.clientHeight;$(".fui-page.fui-page-current").css("overflow","hidden"),$(".fui-page.fui-page-current").css("height",i)}else{i=document.body.clientHeight;$(".fui-page.fui-page-current").css("overflow","auto"),$(".fui-page.fui-page-current").css("height",i)}}),$(".closebtn",c.container.container).unbind("click").click(function(){c.close()}),$(".cancelbtn",c.container.container).unbind("click").click(function(){$(".cyceltime").css("display","none"),$(".cyclenotime").css("display","block"),$(".cancelbtn").css("display"," none")}),$(".fui-mask").unbind("click").click(function(){c.close()}),0==c.seckillinfo?$(".fui-number",c.container.container).numbers({value:c.params.total,max:c.goods.maxbuy,min:c.goods.minbuy,minToast:"{min}"+c.goods.unit+"起售",maxToast:"最多购买{max}"+c.goods.unit,callback:function(i){c.params.total=i}}):c.params.total=1,$(".spec-item",c.container.container).unbind("click").click(function(){c.chooseSpec(this)}),$(".cartbtn",c.container.container).unbind("click").click(function(){c.addToCart()}),$(".gift-item").on("click",function(){$.ajax({url:n.getUrl("goods/detail/querygift",{id:$(this).val()}),cache:!0,success:function(i){0<(i=window.JSON.parse(i)).status&&(c.params.giftid=i.result.id,c.params.getgift=1)}})}),$(".buybtn",c.container.container).unbind("click").click(function(){if(!$(this).hasClass("disabled")&&c.check())if(giftid=0,1==c.params.cangift&&1==c.goods.giftinfo.length&&(giftid=c.goods.giftid),1==c.params.cangift&&null==c.params.giftid&&1<c.goods.giftinfo.length)FoxUI.toast.show("请选择赠品");else{if(null==c.params.getgift?giftid=c.goods.giftid:giftid=c.params.giftid,0<$(".diyform-container").length){var i=e.getData(".diyform-container");if(!i)return;n.json("order/create/diyform",{id:c.goods.id,diyformdata:i},function(i){location.href=n.getUrl("order/create",{id:c.goods.id,optionid:c.params.optionid,giftid:giftid,total:c.params.total,gdid:i.result.goods_data_id})},!0,!0)}else location.href=n.getUrl("order/create",{id:c.goods.id,optionid:c.params.optionid,giftid:giftid,total:c.params.total});c.params.autoClose&&c.close()}}),$(".confirmbtn",c.container.container).unbind("click").click(function(){$(this).hasClass("disabled")||c.check()&&(c.params.onConfirm&&(c.params.total=parseInt($(".num",c.container.container).val()),c.params.onConfirm(c.params.total,c.params.optionid,c.params.titles,c.params.optionthumb)),c.params.autoClose&&c.close())});var i=.6*$(document.body).height(),o=i-$(".option-picker-cell").outerHeight()-$(".option-picker .fui-navbar").outerHeight();c.container.container.find(".option-picker").css("height",i),$(".date-picker").css("height","18rem"),c.container.container.find(".option-picker .option-picker-options").css("height",o);t=document.documentElement.clientHeight||document.body.clientHeight;$(window).on("resize",function(){if((document.documentElement.clientHeight||document.body.clientHeight)<t){$(".fui-navbar").css({display:"none"}),$(".option-picker").css({height:"auto"});var i=(o=.6*$(document.body).height())-$(".option-picker-cell").outerHeight();c.container.container.find(".option-picker").css("height",o),c.container.container.find(".option-picker .option-picker-options").css("height",i),$(".option-picker").addClass("android")}else{$(".fui-navbar").css({display:"block"});var o;i=(o=.6*$(document.body).height())-$(".option-picker-cell").outerHeight()-$(".option-picker .fui-navbar").outerHeight();c.container.container.find(".option-picker").css("height",o),c.container.container.find(".option-picker .option-picker-options").css("height",i),$(".option-picker").addClass("android")}})},addToCart:function(){if(c.goods.canAddCart){if(!$(this).hasClass("disabled")&&c.check()){if(c.params.total=parseInt($(".num",c.container.container).val()),0<$(".diyform-container").length){FoxUI.loader.show("mini");var o=e.getData(".option-picker .diyform-container");if(FoxUI.loader.hide(),!o)return;require(["biz/member/cart"],function(i){i.add(c.goodsid,c.params.optionid,c.params.total,o,function(i){FoxUI.toast.show("添加成功"),c.changeCartcount(i.cartcount)})})}else require(["biz/member/cart"],function(i){i.add(c.goodsid,c.params.optionid,c.params.total,!1,function(i){FoxUI.toast.show("添加成功"),c.changeCartcount(i.cartcount)})});c.params.autoClose&&c.close()}}else FoxUI.toast.show("此商品不可加入购物车<br>请直接点击立刻购买")},show:function(){if(c.followtip)FoxUI.confirm(c.followtip,function(){""!=c.followqrcode&&null!=c.followqrcode?(follow_container=new FoxUIModal({content:c.containerFollowHTML,extraClass:"popup-modal",maskClick:function(){follow_container.close()}}),$(".verify-pop").find(".qrimg").attr("src",c.followqrcode).show(),follow_container.show(),console.log(follow_container),$(".verify-pop").find(".close").unbind("click").click(function(){follow_container.close()})):""!=c.followurl&&null!=c.followurl&&(location.href=c.followurl)});else{if(c.needlogin){var o=n.getUrl("goods/detail",{id:c.goodsid});return o=o.replace("./index.php?",""),void require(["biz/member/account"],function(i){i.initQuick({action:"login",backurl:btoa(o),endtime:c.endtime,imgcode:c.imgcode,success:function(){var i=c.params;i.refresh=!0,c.open(i)}})})}if(c.mustbind)require(["biz/member/account"],function(i){i.initQuick({action:"bind",backurl:btoa(location.href),endtime:c.endtime,imgcode:c.imgcode,success:function(){var i=c.params;i.refresh=!0,c.open(i)}})});else{if(c.container=new FoxUIModal({content:c.containerHTML,extraClass:"picker-modal"}),c.init(),c.seckillinfo&&0==c.seckillinfo.status&&($(".fui-mask").hide(),$(".picker-modal").hide(),(void 0===c.options.length||c.options.length<=0)&&$(".diyform-container").length<=0)){if("buy"==c.params.action)return void(location.href=n.getUrl("order/create",{id:c.goods.id,total:1,optionid:0}));if("cart"==c.params.action)return void c.addToCart()}$(".fui-mask").show(),$(".picker-modal").show(),c.params.showConfirm?$(".confirmbtn",c.container.container).show():($(".buybtn",c.container.container).show(),c.goods.canAddCart&&$(".cartbtn",c.container.container).show()),"0"!=c.params.optionid&&c.initOption(),c.container.show(),1==c.specs.length&&$.each(c.options,function(){var i=this.specs;0==this.stock&&$(".spec-item"+i).removeClass("spec-item").removeClass("btn-danger").addClass("disabled").off("click")})}}},initOption:function(){$(".spec-item").removeClass("btn-danger");var i=c.params.optionid,t=!1;if($.each(c.options,function(){if(this.id==i)return t=this.specs.split("_"),!1}),t){var e=[];if($(".spec-item").each(function(){var i=$(this),o=i.data("id");$.each(t,function(){this==o&&(e.push(i),i.addClass("btn-danger"))})}),0<e.length){var o=e[e.length-1];c.chooseSpec(o,!1)}}},chooseSpec:function(i,o){var n=$(i);n.hasClass("btn-danger")?(n.removeClass("btn-danger"),$(".nav").removeClass("disabled").addClass("spec-item"),$(".member_discount",c.container.container).hide(),$(".spec-item",c.container.container).unbind("click").click(function(){c.chooseSpec(this)})):(n.closest(".spec").find(".spec-item").removeClass("btn-danger"),n.addClass("btn-danger"));var t=n.data("thumb")||"";t&&$(".thumb",c.container.container).attr("src",t),c.params.optionthumb=t;var s=$(".spec-item.btn-danger",c.container.container),e=[];s.length<=c.specs.length&&$.each(c.options,function(){if(c.specs.length-s.length==1){var i=[],o=this.specs;if($.each(s,function(){0<=o.indexOf(this.getAttribute("data-id"))&&i.push(this.getAttribute("data-id"))}),i.length==s.length){for(var t=0;t<i.length;t++)o=o.replace(i[t],"");o=o.split("_");var e=[];$.each(o,function(i,o){var t=$.trim(o);""!=t&&e.push(t)}),this.stock<=0&&-1!=this.stock?$(".spec-item"+e[0]).removeClass("spec-item").removeClass("btn-danger").addClass("disabled").off("click"):$(".spec-item"+e[0]).removeClass("disabled").addClass("spec-item").off("click").on("click",function(){c.chooseSpec(this)})}}else if(c.specs.length==s.length){i=[],o=this.specs;$.each(s,function(){0<=o.indexOf(this.getAttribute("data-id"))&&0<=o.indexOf(n.data("id"))&&i.push(this.getAttribute("data-id"))});e=[];if(i.length==c.specs.length-1){for(t=0;t<i.length;t++)o=o.replace(i[t],"");o=o.split("_"),$.each(o,function(i,o){var t=$.trim(o);""!=t&&e.push(t)}),this.stock<=0&&-1!=this.stock?$(".spec-item"+e[0]).removeClass("spec-item").removeClass("btn-danger").addClass("disabled").off("click"):$(".spec-item"+e[0]).removeClass("disabled").addClass("spec-item").off("click").on("click",function(){c.chooseSpec(this)})}}}),s.length==c.specs.length&&(s.each(function(){e.push($(this).data("id"))}),$.each(c.options,function(){if(this.specs.split("_").sort().join("_")==e.sort().join("_")){var i="-1"==this.stock?"无限":this.stock;$(".total",c.container.container).html(i),"-1"!=this.stock&&this.stock<=0?($(".confirmbtn",c.container).show().addClass("disabled").html("库存不足"),$(".cartbtn,.buybtn",c.container).hide()):c.params.showConfirm?($(".confirmbtn",c.container).removeClass("disabled").html("确定"),$(".cartbtn,.buybtn",c.container).hide()):($(".cartbtn,.buybtn",c.container).show(),$(".confirmbtn").hide());var o=Date.parse(new Date)/1e3;if(0!=c.seckillinfo&&o>c.seckillinfo.starttime&&o<c.seckillinfo.endtime){var t=this;$.each(c.seckillinfo.options,function(){this.optionid==t.id&&$(".price",c.container.container).html(this.price)})}else 0<c.goods.ispresell&&(0==c.goods.preselltimeend||c.goods.preselltimeend>o)?$(".price",c.container.container).html(this.presellprice):$(".price",c.container.container).html(this.marketprice);0<this.seecommission&&($(".option-Commission").addClass("show"),$(".option-Commission span",c.container.container).html(this.seecommission)),0<this.member_discount?($(".member_discount .text-danger",c.container.container).html(" ￥ "+this.member_discount),$(".member_discount",c.container.container).show(),$(".member_discount",c.container.container).css("display","inline-block")):$(".member_discount",c.container.container).hide(),c.option=this,c.params.optionid=this.id}}));var a=[];s.each(function(){a.push($.trim($(this).html()))}),c.params.titles=a.join(c.params.split),$(".info-titles",c.container.container).html("已选 "+c.params.titles),o&&c.params.onSelected&&c.params.onSelected(c.params.total,c.params.optionid,c.params.titles)},check:function(){var i=$(".spec",c.container.container),o=!0;if(i.each(function(){if($(this).find(".spec-item.btn-danger").length<=0)return FoxUI.toast.show("请选择"+$(this).find(".title").html()),o=!1}),o){if(-1!=c.option.stock&&c.option.stock<=0)return FoxUI.toast.show("库存不足"),!1;var t=parseInt($(".num",c.container.container).val());return t<=0&&(t=1),t>c.option.stock&&(t=c.option.stock),$(".num",c.container.container).val(t),0<c.goods.maxbuy&&t>c.goods.maxbuy?(FoxUI.toast.show("最多购买 "+c.goods.maxbuy+" "+c.goods.unit),!1):!(0<c.goods.minbuy&&t<c.goods.minbuy)||(FoxUI.toast.show(c.goods.minbuy+c.goods.unit+"起售"),!1)}return!1},changeCartcount:function(i){if(0<$("#menucart").length){var o=$("#menucart").find(".badge");o.length<1?$("#menucart").append('<span class="badge in">'+i+"</div>"):($(".cart-item").find(".badge").html(i).removeClass("out").addClass("in"),o.text(i))}}};return c});