(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-index-index"],{"0181":function(t,e,i){"use strict";var a;i.d(e,"b",(function(){return n})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return a}));var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[i("v-uni-view",{staticClass:"page-section swiper"},[i("v-uni-view",{staticClass:"page-section-spacing"},[i("v-uni-swiper",{staticClass:"swiper",attrs:{"indicator-dots":!0,circular:!0,autoplay:!0,interval:"5000",duration:500}},t._l(t.banners,(function(e,a){return i("v-uni-swiper-item",{key:a,staticClass:"swiper-item"},[i("v-uni-image",{staticClass:"swiper-img",attrs:{src:e.path,mode:"scaleToFill"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.openH5Url(e.url)}}})],1)})),1)],1)],1),i("v-uni-view",{staticClass:"banban"},[i("v-uni-view",{staticClass:"number-box"},[i("v-uni-view",{staticClass:"mobile_icon"},[i("v-uni-image",{attrs:{src:"/static/mobile.png",mode:""}})],1),i("v-uni-view",{staticClass:"input"},[i("v-uni-input",{attrs:{type:"phone",maxlength:"13",placeholder:"输入号码自动匹配运营商和套餐","placeholder-style":"font-size:32rpx",focus:t.mobile_focus},model:{value:t.mobile,callback:function(e){t.mobile=e},expression:"mobile"}}),t.guishu.city&&t.active?i("v-uni-view",{staticClass:"guishudi"},[t._v(t._s(t.guishu.prov)+" "+t._s(t.guishu.city)+" "+t._s(t.guishu.ispstr))]):t._e()],1),i("v-uni-view",{staticClass:"del"},[t.mobile?i("v-uni-image",{attrs:{src:"/static/csncer.png"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.delMobile.apply(void 0,arguments)}}}):t._e()],1)],1),i("v-uni-view",{class:["cates",t.active?"active":""]},[t._l(t.lists,(function(e,a){return[i("v-uni-view",{key:a+"_0",staticClass:"c"},[i("v-uni-view",[t._v(t._s(e.cate))])],1),i("v-uni-view",{key:a+"_1",staticClass:"content-box clearfloat"},[t._l(e.products,(function(e,a){return i("v-uni-view",{key:a,staticClass:"li",on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.queOrder(e)}}},[e.ys_tag?i("v-uni-view",{staticClass:"tag"},[i("v-uni-text",{staticClass:"text"},[t._v(t._s(e.ys_tag))]),i("v-uni-view",{staticClass:"sjx"})],1):t._e(),i("v-uni-view",{staticClass:"name"},[t._v(t._s(e.name))]),e.price?i("v-uni-view",{staticClass:"price"},[t._v("仅售￥"+t._s(e.price))]):t._e()],1)})),0==e.products.length?i("Nothing",{attrs:{msg:"暂时没有可充值的产品"}}):t._e()],2)]})),0==t.lists.length?i("Nothing",{attrs:{msg:"暂时没有可充值的产品"}}):t._e()],2)],1),i("v-uni-view",{staticClass:"banban",staticStyle:{"margin-top":"20rpx"}},[i("v-uni-view",{staticClass:"wenxintishi"},[i("v-uni-rich-text",{attrs:{nodes:t.wenxindoc.body}})],1)],1),i("copy-right"),i("weixin-qr"),i("move-btn",{attrs:{text:"客服帮助",bottom:100},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.moveBtnClick.apply(void 0,arguments)}}}),i("recharge-box",{ref:"rechargebox"})],1)},o=[]},"0267":function(t,e,i){"use strict";i.r(e);var a=i("84c5"),n=i.n(a);for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);e["default"]=n.a},"1ebd":function(t,e,i){var a=i("94c1");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var n=i("4f06").default;n("bd07c0f6",a,!0,{sourceMap:!1,shadowMode:!1})},"29e0":function(t,e,i){"use strict";i.r(e);var a=i("991a"),n=i("0267");for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);i("ee46");var r,c=i("f0c5"),s=Object(c["a"])(n["default"],a["b"],a["c"],!1,null,"6aa51c7d",null,!1,a["a"],r);e["default"]=s.exports},"30c7":function(t,e,i){"use strict";i.r(e);var a=i("4ef5"),n=i.n(a);for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);e["default"]=n.a},"35d3":function(t,e,i){"use strict";var a=i("38ec"),n=i.n(a);n.a},"38ec":function(t,e,i){var a=i("b101");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var n=i("4f06").default;n("6d9b66c7",a,!0,{sourceMap:!1,shadowMode:!1})},4401:function(t,e,i){"use strict";i.r(e);var a=i("ffed"),n=i.n(a);for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);e["default"]=n.a},"4ef5":function(t,e,i){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={components:{},data:function(){return{tsdoc:{}}},mounted:function(){this.getDoc()},methods:{getDoc:function(){var e=this;this.$request.post("open/get_doc",{data:{id:5}}).then((function(t){0==t.data.errno&&(e.tsdoc=t.data.data)})).catch((function(e){t.error("error:",e)}))}}};e.default=i}).call(this,i("5a52")["default"])},"4fe6":function(t,e,i){var a=i("ceb6");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var n=i("4f06").default;n("28a8a5dd",a,!0,{sourceMap:!1,shadowMode:!1})},5137:function(t,e,i){t.exports=i.p+"static/img/nothing.183013b8.png"},"55df":function(t,e,i){"use strict";i.r(e);var a=i("0181"),n=i("4401");for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);i("bad1");var r,c=i("f0c5"),s=Object(c["a"])(n["default"],a["b"],a["c"],!1,null,"3aa86b8b",null,!1,a["a"],r);e["default"]=s.exports},"68f3":function(t,e,i){"use strict";i.r(e);var a=i("cb48"),n=i.n(a);for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);e["default"]=n.a},"68f7":function(t,e,i){"use strict";(function(e){var a=i("4ea4");i("c975");var n=a(i("e9d6")),o=a(i("ad09")),r=a(i("d065")),c=(a(i("17a6")),function(){if(!o.default.isWeixinH5())return e.log("非微信H5端不获取配置"),!1;var t=window.location.href,i=t.substring(0,t.indexOf("#"));r.default.request.post("Weixin/create_js_config",{data:{url:i,shareurl:t}}).then((function(t){if(0==t.data.errno){var e=t.data.data.config,i=t.data.data.share;n.default.config({debug:!1,appId:e.appid,timestamp:e.timestamp,nonceStr:e.noncestr,signature:e.signature,jsApiList:["updateAppMessageShareData","updateTimelineShareData","onMenuShareAppMessage","onMenuShareTimeline"]}),n.default.ready((function(){var t=uni.getStorageSync("userinfo")?JSON.parse(uni.getStorageSync("userinfo")):{},e="";t&&(e="?vi="+t.id),n.default.updateAppMessageShareData({title:i.title,desc:i.desc,link:i.link+e,imgUrl:i.imgUrl,success:function(){}}),n.default.onMenuShareAppMessage({title:i.title,desc:i.desc,link:i.link+e,imgUrl:i.imgUrl,type:"link",dataUrl:"",success:function(){}}),n.default.onMenuShareTimeline({title:i.title,link:i.link+e,imgUrl:i.imgUrl,success:function(){}}),n.default.updateTimelineShareData({title:i.title,link:i.link+e,imgUrl:i.imgUrl,success:function(){}})}))}else uni.showToast({title:t.data.errmsg,icon:"none",duration:2e3})})).catch((function(t){e.error("error:",t)}))});t.exports={init:c}}).call(this,i("5a52")["default"])},"69b9":function(t,e,i){"use strict";var a=i("1ebd"),n=i.n(a);n.a},"730c":function(t,e,i){var a=i("fee1");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var n=i("4f06").default;n("5efa3ed8",a,!0,{sourceMap:!1,shadowMode:!1})},"7a6a":function(t,e,i){"use strict";var a;i.d(e,"b",(function(){return n})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return a}));var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"botif"},[i("v-uni-view",{staticClass:"copyrg"},[i("div",{staticClass:"richbox",domProps:{innerHTML:t._s(t.tsdoc.body)}})])],1)},o=[]},"80fc":function(t,e,i){"use strict";i.r(e);var a=i("7a6a"),n=i("30c7");for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);i("9ee2");var r,c=i("f0c5"),s=Object(c["a"])(n["default"],a["b"],a["c"],!1,null,"53c57609",null,!1,a["a"],r);e["default"]=s.exports},"84c5":function(t,e,i){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={data:function(){return{}},props:{have:{type:Boolean,default:!1},msg:{type:String,default:"未查询到信息"},loading:{type:Boolean,default:!1},bottom:{type:Boolean,default:!1},bottommsg:{type:String,default:"数据到底了"}},onLoad:function(){},watch:{have:function(e,i){t.log("have",e)},loading:function(e,i){t.log("loading",e)}},methods:{}};e.default=i}).call(this,i("5a52")["default"])},"91eb":function(t,e,i){"use strict";i.r(e);var a=i("fa1f"),n=i("9bfb");for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);i("35d3");var r,c=i("f0c5"),s=Object(c["a"])(n["default"],a["b"],a["c"],!1,null,"45aa50b6",null,!1,a["a"],r);e["default"]=s.exports},"94c1":function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,".qr-box[data-v-70f1dd04]{width:%?750?%;height:100vh;position:fixed;left:%?0?%;top:%?0?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.qr-box>uni-image[data-v-70f1dd04]{width:%?700?%;-webkit-box-shadow:%?0?% %?0?% %?6?% %?0?% #ccc;box-shadow:%?0?% %?0?% %?6?% %?0?% #ccc;border-radius:%?6?%}.qr-box .btn[data-v-70f1dd04]{padding-left:%?30?%;padding-right:%?30?%;-webkit-box-shadow:%?0?% %?0?% %?3?% %?1?% #ccc;box-shadow:%?0?% %?0?% %?3?% %?1?% #ccc;margin-top:%?40?%;line-height:%?60?%;font-size:%?30?%;border-radius:%?10?%;color:#666;background-color:#fff}",""]),t.exports=e},"954e":function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,".boxs[data-v-4d0cb2f2]{width:%?650?%;background-color:#fff;border-radius:%?24?%;min-height:%?20?%;padding-bottom:%?30?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start;position:relative;-webkit-box-sizing:border-box;box-sizing:border-box}.title[data-v-4d0cb2f2]{line-height:%?100?%;font-size:%?34?%;font-weight:600}.wxts[data-v-4d0cb2f2]{background-color:#f7eeef;font-size:%?24?%;width:100%;color:#dc0000;line-height:%?50?%;text-indent:%?30?%}.close_ico[data-v-4d0cb2f2]{position:absolute;right:10px;top:10px;width:%?30?%;height:%?30?%;z-index:999}.mobile[data-v-4d0cb2f2]{font-size:%?60?%;margin-top:%?20?%;margin-bottom:%?20?%}.rows[data-v-4d0cb2f2]{width:%?590?%;font-size:%?28?%;margin-top:%?30?%}.rows .item[data-v-4d0cb2f2]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;color:#333;min-height:%?60?%;max-height:%?150?%;overflow-y:scroll}.paystyle[data-v-4d0cb2f2]{width:%?590?%;font-size:%?28?%;margin-top:%?10?%}.paystyle .lists[data-v-4d0cb2f2]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column}.paystyle .lists .item[data-v-4d0cb2f2]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-box-align:center;-webkit-align-items:center;align-items:center;height:%?80?%;font-size:%?30?%}.paystyle .lists .item>uni-image[data-v-4d0cb2f2]{width:%?40?%;height:%?40?%;margin-right:%?20?%}.paystyle .lists .item .radio[data-v-4d0cb2f2]{width:%?34?%;height:%?34?%;background-image:url(/static/selected1.png);background-position:50%;background-size:%?34?%;margin-right:%?20?%;background-repeat:no-repeat}.paystyle .lists .active .radio[data-v-4d0cb2f2]{background-image:url(/static/selected2.png)}.btns[data-v-4d0cb2f2]{width:%?590?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;-webkit-box-align:center;-webkit-align-items:center;align-items:center;margin-top:%?40?%}.btns .btn[data-v-4d0cb2f2]{color:#fff;height:%?80?%;line-height:%?80?%;text-align:center;width:100%;border-radius:%?40?%;font-size:%?30?%;background-color:#0d8eea}",""]),t.exports=e},"972b":function(t,e,i){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={data:function(){return{ishow:!1,qrimg:""}},mounted:function(){this.isSubscribe()},methods:{isSubscribe:function(){var e=this;this.$request.post("customer/is_subscribe",{data:{}}).then((function(t){0==t.data.errno&&(e.qrimg=t.data.data,e.ishow=!0)})).catch((function(e){t.error("error:",e)}))},closeQr:function(){this.ishow=!1}}};e.default=i}).call(this,i("5a52")["default"])},"991a":function(t,e,i){"use strict";var a;i.d(e,"b",(function(){return n})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return a}));var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{staticClass:"nonomsg"},[t.loading?a("section",[a("div",{staticClass:"sk-fading-circle"},[a("div",{staticClass:"sk-circle sk-circle-1"}),a("div",{staticClass:"sk-circle sk-circle-2"}),a("div",{staticClass:"sk-circle sk-circle-3"}),a("div",{staticClass:"sk-circle sk-circle-4"}),a("div",{staticClass:"sk-circle sk-circle-5"}),a("div",{staticClass:"sk-circle sk-circle-6"}),a("div",{staticClass:"sk-circle sk-circle-7"}),a("div",{staticClass:"sk-circle sk-circle-8"}),a("div",{staticClass:"sk-circle sk-circle-9"}),a("div",{staticClass:"sk-circle sk-circle-10"}),a("div",{staticClass:"sk-circle sk-circle-11"}),a("div",{staticClass:"sk-circle sk-circle-12"})])]):t._e(),t.have||t.loading?t._e():a("v-uni-view",{staticClass:"nomsgview"},[a("img",{staticClass:"iconfont",attrs:{src:i("5137")}}),a("v-uni-view",[t._v(t._s(t.msg))])],1),t.bottom&&t.have?a("v-uni-view",[a("v-uni-view",[t._v(t._s(t.bottommsg))])],1):t._e()],1)},o=[]},"9bfb":function(t,e,i){"use strict";i.r(e);var a=i("b6ea"),n=i.n(a);for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);e["default"]=n.a},"9ee2":function(t,e,i){"use strict";var a=i("bd65"),n=i.n(a);n.a},b101:function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,".back-first-page .img-box[data-v-45aa50b6]{width:100px;height:36px;position:fixed;bottom:100px;right:0;z-index:9999;background-color:#fff;border-radius:18px 18px 18px 18px;border:1px solid #f8f8f8;-webkit-box-shadow:0 0 5px 0 #f8f8f8;box-shadow:0 0 5px 0 #f8f8f8;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;color:#999;-webkit-box-sizing:border-box;box-sizing:border-box;font-size:14px}.img-box>uni-image[data-v-45aa50b6]{width:14px;height:14px}",""]),t.exports=e},b6ea:function(t,e,i){"use strict";i("a9e3"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a={name:"backFirstPage",data:function(){return{screenX:"0px",screenY:null,isDown:!1,mx:null,my:null,ox:null,oy:null,windowWidth:0,windowHeight:0}},props:{text:{type:String,default:"菜单"},bottom:{type:Number,default:100}},mounted:function(){var t=uni.getSystemInfoSync();this.windowWidth=t.windowWidth,this.windowHeight=t.windowHeight,this.screenX=this.windowWidth-100+14+"px",this.screenY=this.windowHeight-this.bottom+"px"},methods:{backFirstClick:function(){this.$emit("click",{})},touchmoveClick:function(t){if(t=t||event,t.preventDefault(),1==t.touches.length){var e={x:t.touches[0].clientX,y:t.touches[0].clientY};e.x<36&&(e.x=36),e.x>window.innerWidth-36&&(e.x=window.innerWidth-36),e.y<18&&(e.y=18),e.y>window.innerHeight-36&&(e.y=window.innerHeight-36),this.screenX=e.x-50+"px",this.screenY=e.y-18+"px"}}}};e.default=a},b9b9:function(t,e,i){"use strict";i.d(e,"b",(function(){return n})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return a}));var a={uniPopup:i("06c7").default},n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("uni-popup",{ref:"popref",attrs:{type:"center"}},[i("v-uni-view",{staticClass:"boxs"},[i("v-uni-image",{staticClass:"close_ico",attrs:{src:"/static/close_g.png"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closePop.apply(void 0,arguments)}}}),i("v-uni-view",{staticClass:"title"},[t._v("确认充值信息")]),i("v-uni-view",{staticClass:"wxts"},[t._v("*温馨提示：请仔细核对充值号码，充错无法退回")]),i("v-uni-view",{staticClass:"mobile"},[t._v(t._s(t.mobileFormat(t.mobile)))]),i("v-uni-view",{staticClass:"rows"},[i("v-uni-view",{staticClass:"item"},[i("v-uni-view",[t._v("充值产品:")]),i("v-uni-view",[t._v(t._s(t.product.desc))])],1),i("v-uni-view",{staticClass:"item"},[i("v-uni-view",[t._v("付款金额:")]),i("v-uni-view",[i("v-uni-text",{staticStyle:{"font-size":"24rpx"}},[t._v("￥")]),i("v-uni-text",{staticStyle:{color:"#f00","font-size":"32rpx"}},[t._v(t._s(t.product.price))])],1)],1)],1),i("v-uni-view",{staticClass:"paystyle"},[i("v-uni-view",{staticClass:"tit"},[t._v("请选择支付方式：")]),i("v-uni-view",{staticClass:"lists"},[i("v-uni-view",{class:["item",2==t.paytype?"active":""],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changeType(2)}}},[i("v-uni-view",{staticClass:"radio"}),i("v-uni-image",{attrs:{src:"/static/pay_zfb.png"}}),i("v-uni-view",{staticClass:"name"},[t._v("支付宝支付")])],1),i("v-uni-view",{class:["item",1==t.paytype?"active":""],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.changeType(1)}}},[i("v-uni-view",{staticClass:"radio"}),i("v-uni-image",{attrs:{src:"/static/pay_wx.png"}}),i("v-uni-view",{staticClass:"name"},[t._v("微信支付")])],1)],1)],1),i("v-uni-view",{staticClass:"btns"},[i("v-uni-view",{staticClass:"btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.subOrder.apply(void 0,arguments)}}},[t._v("提交")])],1)],1)],1)},o=[]},bad1:function(t,e,i){"use strict";var a=i("730c"),n=i.n(a);n.a},bd65:function(t,e,i){var a=i("c012");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var n=i("4f06").default;n("2fb7cb17",a,!0,{sourceMap:!1,shadowMode:!1})},c012:function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,".botif[data-v-53c57609]{margin-top:%?20?%;width:%?750?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start;padding-top:%?20?%;padding-bottom:%?40?%}.botif .copyrg[data-v-53c57609]{width:%?710?%}",""]),t.exports=e},c058:function(t,e,i){"use strict";var a;i.d(e,"b",(function(){return n})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return a}));var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return t.ishow?i("v-uni-view",[i("v-uni-view",{staticClass:"qr-box"},[i("v-uni-image",{attrs:{src:t.qrimg,mode:"widthFix"}}),i("v-uni-view",{staticClass:"btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closeQr.apply(void 0,arguments)}}},[t._v("知道了")])],1)],1):t._e()},o=[]},c42c:function(t,e,i){"use strict";i.r(e);var a=i("b9b9"),n=i("68f3");for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);i("fc24");var r,c=i("f0c5"),s=Object(c["a"])(n["default"],a["b"],a["c"],!1,null,"4d0cb2f2",null,!1,a["a"],r);e["default"]=s.exports},c590:function(t,e,i){"use strict";i.r(e);var a=i("972b"),n=i.n(a);for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);e["default"]=n.a},cb48:function(t,e,i){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={props:{},data:function(){return{mobile:"",product:{},paytype:1,inter:null}},mounted:function(){},onShow:function(){},methods:{openPop:function(t,e){this.mobile=t,this.product=e,this.$refs.popref.open()},closePop:function(){this.$refs.popref.close()},changeType:function(t){this.paytype=t},subOrder:function(){var e=this,i=this;uni.showLoading({title:"提交中"});uni.getStorageSync("vi");this.$request.post("Porder/create_order",{data:{product_id:this.product.id,mobile:this.mobile}}).then((function(t){uni.hideLoading(),0==t.data.errno?e.requestPayment(t.data.data.payinfo,(function(t){t.status&&(i.toast("支付完成"),uni.navigateTo({url:"/pages/index/record"}),i.closePop())})):100==t.data.errno?(setTimeout((function(){uni.showModal({title:"支付信息",content:"是否已经成功支付？",cancelText:"未支付",confirmText:"已支付",success:function(t){t.confirm&&(uni.navigateTo({url:"/pages/index/record"}),i.closePop())}})}),1e3),window.location.href=t.data.data.payinfo):uni.showToast({title:t.data.errmsg,icon:"none",duration:2e3})})).catch((function(e){t.error("error:",e)}))}}};e.default=i}).call(this,i("5a52")["default"])},cd33:function(t,e,i){var a=i("954e");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var n=i("4f06").default;n("31e231ac",a,!0,{sourceMap:!1,shadowMode:!1})},ceb6:function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,'.nonomsg[data-v-6aa51c7d]{width:100%;text-align:center;color:#999;clear:both}.nomsgview[data-v-6aa51c7d]{padding-top:2rem;padding-bottom:2rem}.nonomsg .iconfont[data-v-6aa51c7d]{width:%?142?%;height:%?142?%}.sk-fading-circle[data-v-6aa51c7d]{width:2rem;height:2rem;position:relative;margin:auto}.sk-fading-circle .sk-circle[data-v-6aa51c7d]{width:100%;height:100%;position:absolute;left:0;top:0}.sk-fading-circle .sk-circle[data-v-6aa51c7d]:before{content:"";display:block;margin:0 auto;width:15%;height:15%;background-color:#888;border-radius:100%;-webkit-animation:sk-fading-circle-delay-data-v-6aa51c7d 1.2s infinite ease-in-out both;animation:sk-fading-circle-delay-data-v-6aa51c7d 1.2s infinite ease-in-out both}.sk-fading-circle .sk-circle-2[data-v-6aa51c7d]{-webkit-transform:rotate(30deg);transform:rotate(30deg)}.sk-fading-circle .sk-circle-3[data-v-6aa51c7d]{-webkit-transform:rotate(60deg);transform:rotate(60deg)}.sk-fading-circle .sk-circle-4[data-v-6aa51c7d]{-webkit-transform:rotate(90deg);transform:rotate(90deg)}.sk-fading-circle .sk-circle-5[data-v-6aa51c7d]{-webkit-transform:rotate(120deg);transform:rotate(120deg)}.sk-fading-circle .sk-circle-6[data-v-6aa51c7d]{-webkit-transform:rotate(150deg);transform:rotate(150deg)}.sk-fading-circle .sk-circle-7[data-v-6aa51c7d]{-webkit-transform:rotate(180deg);transform:rotate(180deg)}.sk-fading-circle .sk-circle-8[data-v-6aa51c7d]{-webkit-transform:rotate(210deg);transform:rotate(210deg)}.sk-fading-circle .sk-circle-9[data-v-6aa51c7d]{-webkit-transform:rotate(240deg);transform:rotate(240deg)}.sk-fading-circle .sk-circle-10[data-v-6aa51c7d]{-webkit-transform:rotate(270deg);transform:rotate(270deg)}.sk-fading-circle .sk-circle-11[data-v-6aa51c7d]{-webkit-transform:rotate(300deg);transform:rotate(300deg)}.sk-fading-circle .sk-circle-12[data-v-6aa51c7d]{-webkit-transform:rotate(330deg);transform:rotate(330deg)}.sk-fading-circle .sk-circle-2[data-v-6aa51c7d]:before{-webkit-animation-delay:-1.1s;animation-delay:-1.1s}.sk-fading-circle .sk-circle-3[data-v-6aa51c7d]:before{-webkit-animation-delay:-1s;animation-delay:-1s}.sk-fading-circle .sk-circle-4[data-v-6aa51c7d]:before{-webkit-animation-delay:-.9s;animation-delay:-.9s}.sk-fading-circle .sk-circle-5[data-v-6aa51c7d]:before{-webkit-animation-delay:-.8s;animation-delay:-.8s}.sk-fading-circle .sk-circle-6[data-v-6aa51c7d]:before{-webkit-animation-delay:-.7s;animation-delay:-.7s}.sk-fading-circle .sk-circle-7[data-v-6aa51c7d]:before{-webkit-animation-delay:-.6s;animation-delay:-.6s}.sk-fading-circle .sk-circle-8[data-v-6aa51c7d]:before{-webkit-animation-delay:-.5s;animation-delay:-.5s}.sk-fading-circle .sk-circle-9[data-v-6aa51c7d]:before{-webkit-animation-delay:-.4s;animation-delay:-.4s}.sk-fading-circle .sk-circle-10[data-v-6aa51c7d]:before{-webkit-animation-delay:-.3s;animation-delay:-.3s}.sk-fading-circle .sk-circle-11[data-v-6aa51c7d]:before{-webkit-animation-delay:-.2s;animation-delay:-.2s}.sk-fading-circle .sk-circle-12[data-v-6aa51c7d]:before{-webkit-animation-delay:-.1s;animation-delay:-.1s}@-webkit-keyframes sk-fading-circle-delay-data-v-6aa51c7d{0%,\n\t39%,\n\t100%{opacity:0}40%{opacity:1}}@keyframes sk-fading-circle-delay-data-v-6aa51c7d{0%,\n\t39%,\n\t100%{opacity:0}40%{opacity:1}}',""]),t.exports=e},e529:function(t,e,i){"use strict";i.r(e);var a=i("c058"),n=i("c590");for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);i("69b9");var r,c=i("f0c5"),s=Object(c["a"])(n["default"],a["b"],a["c"],!1,null,"70f1dd04",null,!1,a["a"],r);e["default"]=s.exports},ee46:function(t,e,i){"use strict";var a=i("4fe6"),n=i.n(a);n.a},fa1f:function(t,e,i){"use strict";var a;i.d(e,"b",(function(){return n})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return a}));var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"back-first-page"},[i("div",{staticClass:"img-box",style:{left:t.screenX,top:t.screenY},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.backFirstClick.apply(void 0,arguments)},touchmove:function(e){arguments[0]=e=t.$handleEvent(e),t.touchmoveClick.apply(void 0,arguments)}}},[i("v-uni-view",{},[t._v(t._s(t.text))]),i("v-uni-image",{attrs:{src:"/static/next2.png"}})],1)])},o=[]},fc24:function(t,e,i){"use strict";var a=i("cd33"),n=i.n(a);n.a},fee1:function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,'uni-page-body[data-v-3aa86b8b]{background-color:#f8f8f8}.banban[data-v-3aa86b8b]{width:%?750?%;background-color:#fff;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center;padding-bottom:%?40?%}.clearfloat[data-v-3aa86b8b]:after{display:block;clear:both;content:"";visibility:hidden;height:0}.clearfloat[data-v-3aa86b8b]{zoom:1}.number-box[data-v-3aa86b8b]{width:%?690?%;height:%?140?%;margin:%?20?% auto 0;background-color:#eef2f5;border-radius:%?12?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-justify-content:space-around;justify-content:space-around;-webkit-box-align:center;-webkit-align-items:center;align-items:center;margin-bottom:%?20?%}.number-box .mobile_icon[data-v-3aa86b8b]{width:%?100?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center}.number-box .mobile_icon>uni-image[data-v-3aa86b8b]{height:%?60?%;width:%?60?%}.number-box .input[data-v-3aa86b8b]{width:%?500?%;color:#333;text-align:left;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column}.number-box .input>uni-input[data-v-3aa86b8b]{font-size:%?48?%;font-weight:500}.number-box .input .guishudi[data-v-3aa86b8b]{font-size:%?24?%;color:#0d8eea;padding-left:%?5?%}.number-box .del[data-v-3aa86b8b]{width:%?90?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center}.number-box .del>uni-image[data-v-3aa86b8b]{width:%?46?%;height:%?46?%}.cates[data-v-3aa86b8b]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-sizing:border-box;box-sizing:border-box;width:%?750?%}.cates .c[data-v-3aa86b8b]{color:#444;margin-top:%?10?%;font-size:%?30?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;width:%?690?%;color:#333}.cates .c .xq[data-v-3aa86b8b]{font-size:%?24?%;background-color:#e6f3fd;color:#0d8eea;height:%?48?%;line-height:%?48?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center;border-radius:%?24?%;padding-left:%?24?%;padding-right:%?10?%}.cates .c .xq>uni-image[data-v-3aa86b8b]{width:%?24?%;height:%?24?%;margin-left:%?6?%}.content-box[data-v-3aa86b8b]{width:%?690?%;-webkit-box-sizing:border-box;box-sizing:border-box;margin-bottom:%?20?%}.active .li[data-v-3aa86b8b]:active{background-color:#e6f3fd}.content-box .li[data-v-3aa86b8b]{width:%?210?%;height:%?120?%;border-radius:%?10?%;margin:%?30?% %?30?% 0 %?0?%;-webkit-box-shadow:0 0 0 1px #0d8eea!important;box-shadow:0 0 0 1px #0d8eea!important;float:left;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;position:relative}.content-box .li>uni-view[data-v-3aa86b8b]{text-align:center;color:#0d8eea;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center}.content-box .li[data-v-3aa86b8b]:nth-child(3n){margin-right:0}.content-box .li .name[data-v-3aa86b8b]{font-size:%?32?%;line-height:%?45?%;font-weight:600}.content-box .li .price[data-v-3aa86b8b]{font-size:%?26?%;line-height:%?36?%;margin-top:%?8?%}.content-box .li .tag[data-v-3aa86b8b]{font-size:%?28?%;line-height:%?36?%;position:absolute;left:%?-10?%;top:%?-20?%;border-radius:%?10?% %?18?% %?18?% %?8?%;padding-left:%?15?%;padding-right:%?20?%;color:#fff!important;background:-webkit-gradient(linear,left top,right top,from(#e73827),to(#f85032));background:-webkit-linear-gradient(left,#e73827,#f85032);background:linear-gradient(90deg,#e73827,#f85032);z-index:99}.content-box .li .tag .text[data-v-3aa86b8b]{-webkit-transform:scale(.9);transform:scale(.9);font-size:%?24?%;height:%?36?%}.content-box .li .tag .sjx[data-v-3aa86b8b]{position:absolute;left:%?0?%;top:%?32?%;width:0;height:0;border-top:%?14?% solid #e73827;border-left:%?10?% solid transparent;z-index:98}.content-box .li .weihu[data-v-3aa86b8b]{position:absolute;right:%?0?%;top:%?0?%;font-size:%?24?%;width:0;height:0;border-top:%?50?% solid #cecece;border-left:%?50?% solid transparent}.content-box .li .weihu .text[data-v-3aa86b8b]{-webkit-transform:rotate(-45deg) scale(.58);transform:rotate(-45deg) scale(.58);margin-top:%?-70?%;margin-left:%?-30?%;color:#fff}.wenxintishi[data-v-3aa86b8b]{width:%?690?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;font-size:%?28?%;width:%?690?%;margin:auto;line-height:%?40?%;margin-top:%?30?%}.swiper[data-v-3aa86b8b]{height:%?250?%}.swiper-item .swiper-img[data-v-3aa86b8b]{width:%?750?%;height:%?250?%}.botif[data-v-3aa86b8b]{margin-top:%?20?%;width:%?750?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start;padding-top:%?20?%;padding-bottom:%?40?%}.botif .btns[data-v-3aa86b8b]{color:#2f8ee0;width:%?400?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-box-pack:justify;-webkit-justify-content:space-between;justify-content:space-between;font-size:%?28?%;margin-bottom:%?10?%}.botif .mif[data-v-3aa86b8b]{font-size:%?24?%;color:#999;line-height:%?40?%}body.?%PAGE?%[data-v-3aa86b8b]{background-color:#f8f8f8}',""]),t.exports=e},ffed:function(t,e,i){"use strict";(function(t){var a=i("4ea4");i("ac1f"),i("5319"),i("498a"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=a(i("68f7")),o=a(i("e529")),r=a(i("29e0")),c=a(i("80fc")),s=a(i("91eb")),l=a(i("c42c")),d={components:{WeixinQr:o.default,Nothing:r.default,MoveBtn:s.default,RechargeBox:l.default,CopyRight:c.default},data:function(){return{mobile:"",lists:[],active:!1,isloading:!1,guishu:{},wenxindoc:{},banners:[],mobile_focus:!1}},onLoad:function(){},mounted:function(){this.getDoc(),this.getBanner(),n.default.init()},onShow:function(){this.initTaocan()},watch:{mobile:function(e,i){if(e.length>i.length?this.mobile=this.mobileFormat(e):this.mobile=this.mobile.trim(),13==this.mobile.length){var a=this.mobile.replace(/\s/g,"");this.initTaocanPhone(a),uni.onKeyboardHeightChange((function(e){t.log(e.height)}))}else this.active=!1}},methods:{openUrl:function(t){t&&(window.location.href=t)},delMobile:function(){this.mobile="",this.initTaocan()},setMobileFocus:function(){this.mobile_focus=!1;var t=this;this.$nextTick((function(){t.mobile_focus=!0}))},initTaocan:function(){var e=this;this.isloading=!0,this.$request.post("index/get_product",{data:{isp:1}}).then((function(t){e.isloading=!1,0==t.data.errno?e.lists=t.data.data:uni.showToast({title:t.data.errmsg,icon:"none",duration:2e3})})).catch((function(e){t.error("error:",e)}))},initTaocanPhone:function(e){var i=this;this.isloading=!0,this.$request.post("index/get_product_mobile",{data:{mobile:e}}).then((function(t){uni.hideKeyboard(),i.isloading=!1,0==t.data.errno?(i.active=!0,i.lists=t.data.data.lists,i.guishu=t.data.data.guishu):(i.active=!1,uni.showToast({title:t.data.errmsg,icon:"none",duration:2e3}))})).catch((function(e){t.error("error:",e)}))},queOrder:function(t){if(!this.active)return this.toast("请先输入正确的手机号码"),void this.setMobileFocus();var e=this.mobile.replace(/\D/g,"").substring(0,11);11==e.length&&this.$refs.rechargebox.openPop(e,t)},getDoc:function(){var e=this;this.$request.post("open/get_doc",{data:{id:2}}).then((function(t){0==t.data.errno&&(e.wenxindoc=t.data.data)})).catch((function(e){t.error("error:",e)}))},getBanner:function(){var e=this;this.$request.post("open/get_ads",{data:{key:"indexkey"}}).then((function(i){t.log(i),0==i.data.errno&&(e.banners=i.data.data)})).catch((function(e){t.error("error:",e)}))},moveBtnClick:function(){uni.navigateTo({url:"/pages/other/helps"})}}};e.default=d}).call(this,i("5a52")["default"])}}]);