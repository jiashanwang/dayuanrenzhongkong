(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-my-apply"],{1133:function(t,e,n){"use strict";n.r(e);var i=n("5e19"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},2072:function(t,e,n){"use strict";n.r(e);var i=n("3b79"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},"23df":function(t,e,n){"use strict";(function(e){var i=n("4ea4"),a=(i(n("d065")),function(t){});t.exports={subscribe:a}}).call(this,n("5a52")["default"])},"30c7":function(t,e,n){"use strict";n.r(e);var i=n("4ef5"),a=n.n(i);for(var o in i)"default"!==o&&function(t){n.d(e,t,(function(){return i[t]}))}(o);e["default"]=a.a},"3a19":function(t,e,n){var i=n("9781");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("d487962a",i,!0,{sourceMap:!1,shadowMode:!1})},"3b79":function(t,e,n){"use strict";(function(t){var i=n("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=i(n("68f7")),o=i(n("80fc")),r=i(n("98f6")),c=(i(n("ad09")),i(n("23df"))),s={components:{CopyRight:o.default,DocBox:r.default},data:function(){return{yxchecked:!1,banners:[],tsdoc:{},agent2info:{}}},onLoad:function(){this.getAgentInfo(),this.initUserInfo()},mounted:function(){this.getBanner(),this.getDoc()},onShow:function(){a.default.init()},methods:{getAgentInfo:function(){var e=this;this.$request.post("customer/get_grade_info",{data:{grade_id:2}}).then((function(t){0==t.data.errno&&(e.agent2info=t.data.data)})).catch((function(e){t.error("error:",e)}))},initUserInfo:function(){uni.showLoading({title:"加载中.."}),this.$request.post("Customer/info",{data:{}}).then((function(t){if(uni.hideLoading(),0==t.data.errno){var e=t.data.data;2==e.grade_id&&uni.redirectTo({url:"/pages/agent/index"})}})).catch((function(t){}))},ckChange:function(t){this.yxchecked=!this.yxchecked,this.yxchecked&&c.default.subscribe("upsus_template_id")},getBanner:function(){var e=this;this.$request.post("open/get_ads",{data:{key:"agapply"}}).then((function(n){t.log(n),0==n.data.errno&&(e.banners=n.data.data)})).catch((function(e){t.error("error:",e)}))},getDoc:function(){var e=this;this.$request.post("open/get_doc",{data:{id:9}}).then((function(t){0==t.data.errno&&(e.tsdoc=t.data.data)})).catch((function(e){t.error("error:",e)}))},openAgXieyi:function(){this.$refs.docbox.openPop()},queOrder:function(){if(!this.yxchecked)return this.toast("请先同意代理协议");this.createOrder()},createOrder:function(){var e=this;this.$request.post("customer/create_agent_order",{data:{grade_id:2}}).then((function(n){t.log(n),0==n.data.errno?e.requestPayment(n.data.data.payinfo,(function(e){t.log(e),e.status&&uni.redirectTo({url:"/pages/agent/index"})})):100==n.data.errno?(setTimeout((function(){uni.showModal({title:"支付信息",content:"是否已经成功支付？",cancelText:"未支付",confirmText:"已支付",success:function(t){t.confirm&&uni.navigateTo({url:"/pages/agent/index"})}})}),1e3),window.location.href=n.data.data.payinfo):uni.showToast({title:n.data.errmsg,icon:"none",duration:2e3})})).catch((function(e){t.error("error:",e)}))}}};e.default=s}).call(this,n("5a52")["default"])},"4c96":function(t,e,n){"use strict";var i=n("c071"),a=n.n(i);a.a},"4ef5":function(t,e,n){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={components:{},data:function(){return{tsdoc:{}}},mounted:function(){this.getDoc()},methods:{getDoc:function(){var e=this;this.$request.post("open/get_doc",{data:{id:5}}).then((function(t){0==t.data.errno&&(e.tsdoc=t.data.data)})).catch((function(e){t.error("error:",e)}))}}};e.default=n}).call(this,n("5a52")["default"])},"5e19":function(t,e,n){"use strict";(function(t){n("a9e3"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={data:function(){return{info:{}}},props:{docid:{type:Number},btntxt:{type:String,default:"知道了"}},mounted:function(){},onShow:function(){},methods:{openPop:function(t){this.getDoc(),this.$refs.popref.open()},closePop:function(){this.$refs.popref.close()},getDoc:function(e){var n=this;uni.showLoading({title:"请稍后"}),this.$request.post("open/get_doc",{data:{id:this.docid}}).then((function(t){uni.hideLoading(),0==t.data.errno?n.info=t.data.data:(n.toast("内容未找到"),n.$refs.popref.close())})).catch((function(e){t.error("error:",e)}))}}};e.default=i}).call(this,n("5a52")["default"])},"68f7":function(t,e,n){"use strict";(function(e){var i=n("4ea4");n("c975");var a=i(n("e9d6")),o=i(n("ad09")),r=i(n("d065")),c=i(n("b606")),s=function(){if(!o.default.isWeixinH5())return e.log("非微信H5端不获取配置"),!1;var t=window.location.href,n=t.substring(0,t.indexOf("#"));r.default.request.post("Weixin/create_js_config",{data:{url:n,shareurl:document.location.protocol+"//"+window.location.hostname+"/#/"}}).then((function(t){if(0==t.data.errno){var e=t.data.data.config,n=t.data.data.share;a.default.config({debug:!1,appId:e.appid,timestamp:e.timestamp,nonceStr:e.noncestr,signature:e.signature,jsApiList:["updateAppMessageShareData","updateTimelineShareData","onMenuShareAppMessage","onMenuShareTimeline"]}),a.default.ready((function(){var t=uni.getStorageSync("userinfo")?JSON.parse(uni.getStorageSync("userinfo")):{},e={};e["appid"]=o.default.getAppid(),t&&(e["vi"]=t.id);var i="?"+c.default.stringify(e);a.default.updateAppMessageShareData({title:n.title,desc:n.desc,link:n.link+i,imgUrl:n.imgUrl,success:function(){}}),a.default.onMenuShareAppMessage({title:n.title,desc:n.desc,link:n.link+i,imgUrl:n.imgUrl,type:"link",dataUrl:"",success:function(){}}),a.default.onMenuShareTimeline({title:n.title,link:n.link+i,imgUrl:n.imgUrl,success:function(){}}),a.default.updateTimelineShareData({title:n.title,link:n.link+i,imgUrl:n.imgUrl,success:function(){}})}))}else uni.showToast({title:t.data.errmsg,icon:"none",duration:2e3})})).catch((function(t){e.error("error:",t)}))};t.exports={init:s}}).call(this,n("5a52")["default"])},"7a6a":function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"botif"},[n("v-uni-view",{staticClass:"copyrg"},[n("div",{staticClass:"richbox",domProps:{innerHTML:t._s(t.tsdoc.body)}})])],1)},o=[]},"80fc":function(t,e,n){"use strict";n.r(e);var i=n("7a6a"),a=n("30c7");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("9ee2");var r,c=n("f0c5"),s=Object(c["a"])(a["default"],i["b"],i["c"],!1,null,"53c57609",null,!1,i["a"],r);e["default"]=s.exports},9781:function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,".boxs[data-v-29db1a81]{width:%?650?%;background-color:#fff;border-radius:%?24?%;min-height:%?20?%;padding-bottom:%?30?%;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;position:relative;box-sizing:border-box}.title[data-v-29db1a81]{line-height:%?100?%;font-size:%?34?%;font-weight:600}.topbg[data-v-29db1a81]{width:100%;border-radius:%?24?% %?24?% 0 0;height:%?200?%}.close_ico[data-v-29db1a81]{position:absolute;right:10px;top:10px;width:%?30?%;height:%?30?%;z-index:999}.content[data-v-29db1a81]{max-height:60vh;min-height:%?300?%;width:%?610?%;overflow-y:scroll;margin-top:%?20?%}.btns[data-v-29db1a81]{width:%?610?%;display:flex;flex-direction:row;justify-content:space-around;align-items:center;margin-top:%?40?%}.btns .btn[data-v-29db1a81]{background-color:#0d8eea;color:#fff;height:%?80?%;line-height:%?80?%;text-align:center;padding-left:%?90?%;padding-right:%?90?%;border-radius:%?40?%;font-size:%?30?%}",""]),t.exports=e},"98f6":function(t,e,n){"use strict";n.r(e);var i=n("b34d"),a=n("1133");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("d323");var r,c=n("f0c5"),s=Object(c["a"])(a["default"],i["b"],i["c"],!1,null,"29db1a81",null,!1,i["a"],r);e["default"]=s.exports},"9ee2":function(t,e,n){"use strict";var i=n("bd65"),a=n.n(i);a.a},adb3:function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,"uni-page-body[data-v-38c32465]{background-color:#f8f8f8}.main[data-v-38c32465]{display:flex;flex-direction:column;align-items:center}.swiper[data-v-38c32465]{width:%?750?%;height:%?340?%}.swiper-item .swiper-img[data-v-38c32465]{width:%?750?%;height:%?340?%}.apmain[data-v-38c32465]{width:%?710?%;background-color:#fff;box-shadow:0 0 0 1px #f8f8f8;border-radius:%?24?%;margin-top:%?-70?%;z-index:9;box-sizing:border-box;padding:%?40?% %?30?% %?80?% %?30?%;display:flex;flex-direction:column;align-items:center}.apmain .price[data-v-38c32465]{font-size:%?80?%;margin-top:%?20?%;font-weight:600}.apmain .xieyi[data-v-38c32465]{width:100%;display:flex;flex-direction:row;align-items:center;justify-content:center;color:#666;font-size:%?28?%;margin-top:%?40?%}.apmain .xieyi .checkbox[data-v-38c32465]{-webkit-transform:scale(.6);transform:scale(.6)}.apmain .xieyi .a[data-v-38c32465]{color:#1f2091}.apmain .btn[data-v-38c32465]{width:100%;background-color:#0d8eea;line-height:%?90?%;border-radius:%?45?%;color:#fff;text-align:center;font-size:%?32?%;margin-top:%?40?%}.apmain .tishi[data-v-38c32465]{width:100%;margin-top:%?80?%}body.?%PAGE?%[data-v-38c32465]{background-color:#f8f8f8}",""]),t.exports=e},b34d:function(t,e,n){"use strict";n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var i={uniPopup:n("06c7").default},a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("uni-popup",{ref:"popref",attrs:{type:"center"}},[n("v-uni-view",{staticClass:"boxs"},[t.info.litpic?[n("v-uni-image",{staticClass:"close_ico",attrs:{src:"/static/close_w.png"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closePop.apply(void 0,arguments)}}}),n("v-uni-image",{staticClass:"topbg",attrs:{src:t.info.litpic,mode:"aspectFill"}})]:[n("v-uni-image",{staticClass:"close_ico",attrs:{src:"/static/close_g.png"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closePop.apply(void 0,arguments)}}}),n("v-uni-view",{staticClass:"title"},[t._v(t._s(t.info.title))])],n("v-uni-scroll-view",{staticClass:"content",attrs:{"scroll-y":"true"}},[n("div",{staticClass:"richbox",domProps:{innerHTML:t._s(t.info.body)}})]),t.btntxt?n("v-uni-view",{staticClass:"btns"},[n("v-uni-view",{staticClass:"btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closePop.apply(void 0,arguments)}}},[t._v(t._s(t.btntxt))])],1):t._e()],2)],1)},o=[]},b5d3:function(t,e,n){"use strict";n.r(e);var i=n("c3bd"),a=n("2072");for(var o in a)"default"!==o&&function(t){n.d(e,t,(function(){return a[t]}))}(o);n("4c96");var r,c=n("f0c5"),s=Object(c["a"])(a["default"],i["b"],i["c"],!1,null,"38c32465",null,!1,i["a"],r);e["default"]=s.exports},bd65:function(t,e,n){var i=n("c012");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("2fb7cb17",i,!0,{sourceMap:!1,shadowMode:!1})},c012:function(t,e,n){var i=n("24fb");e=i(!1),e.push([t.i,".botif[data-v-53c57609]{margin-top:%?20?%;width:%?750?%;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;padding-top:%?20?%;padding-bottom:%?40?%}.botif .copyrg[data-v-53c57609]{width:%?710?%}",""]),t.exports=e},c071:function(t,e,n){var i=n("adb3");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var a=n("4f06").default;a("577cb79d",i,!0,{sourceMap:!1,shadowMode:!1})},c3bd:function(t,e,n){"use strict";var i;n.d(e,"b",(function(){return a})),n.d(e,"c",(function(){return o})),n.d(e,"a",(function(){return i}));var a=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("v-uni-view",{staticClass:"main"},[n("v-uni-view",{staticClass:"page-section swiper"},[n("v-uni-view",{staticClass:"page-section-spacing"},[n("v-uni-swiper",{staticClass:"swiper",attrs:{"indicator-dots":!0,circular:!0,autoplay:!0,interval:"5000",duration:500}},t._l(t.banners,(function(e,i){return n("v-uni-swiper-item",{key:i,staticClass:"swiper-item"},[n("v-uni-image",{staticClass:"swiper-img",attrs:{src:e.path,mode:"aspectFill"},on:{click:function(n){arguments[0]=n=t.$handleEvent(n),t.openH5Url(e.url)}}})],1)})),1)],1)],1),n("v-uni-view",{staticClass:"apmain"},[n("v-uni-view",{staticClass:"price"},[n("v-uni-text",{staticClass:"text-size40"},[t._v("￥")]),t._v(t._s(t.agent2info.up_price))],1),n("v-uni-view",{staticClass:"xieyi"},[n("v-uni-checkbox",{staticClass:"checkbox",attrs:{color:"#0075F0",checked:t.yxchecked},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.ckChange.apply(void 0,arguments)}}}),n("v-uni-view",{on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.ckChange.apply(void 0,arguments)}}},[t._v("我已阅读并同意"),n("v-uni-text",{staticClass:"a",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.openAgXieyi.apply(void 0,arguments)}}},[t._v("《代理协议》")])],1)],1),n("v-uni-view",{staticClass:"btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.queOrder.apply(void 0,arguments)}}},[t._v("提交")]),n("v-uni-view",{staticClass:"tishi"},[n("v-uni-rich-text",{attrs:{nodes:t.tsdoc.body}})],1)],1),n("copy-right"),n("doc-box",{ref:"docbox",attrs:{docid:8}})],1)},o=[]},d323:function(t,e,n){"use strict";var i=n("3a19"),a=n.n(i);a.a}}]);