(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-my-apply"],{"08b2":function(t,e,i){"use strict";var n=i("df48"),a=i.n(n);a.a},1133:function(t,e,i){"use strict";i.r(e);var n=i("5e19"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},2072:function(t,e,i){"use strict";i.r(e);var n=i("3b79"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},"23df":function(t,e,i){"use strict";(function(e){var n=i("4ea4"),a=(n(i("d065")),function(t){});t.exports={subscribe:a}}).call(this,i("5a52")["default"])},"30c7":function(t,e,i){"use strict";i.r(e);var n=i("4ef5"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},"3a19":function(t,e,i){var n=i("9781");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("d487962a",n,!0,{sourceMap:!1,shadowMode:!1})},"3b79":function(t,e,i){"use strict";(function(t){var n=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=n(i("68f7")),o=n(i("80fc")),r=n(i("98f6")),s=(n(i("ad09")),n(i("23df"))),c={components:{CopyRight:o.default,DocBox:r.default},data:function(){return{yxchecked:!1,banners:[],tsdoc:{},grades:[],grade_index:0}},onLoad:function(){this.getAgentInfo(),this.initUserInfo()},mounted:function(){this.getBanner(),this.getDoc()},onShow:function(){a.default.init()},methods:{selGrade:function(t){this.grade_index=t},getAgentInfo:function(){var e=this;this.$request.post("customer/get_apply_grade",{data:{}}).then((function(t){0==t.data.errno?e.grades=t.data.data:uni.navigateBack({delta:1})})).catch((function(e){t.error("error:",e)}))},initUserInfo:function(){uni.showLoading({title:"加载中.."}),this.$request.post("Customer/info",{data:{}}).then((function(t){if(uni.hideLoading(),0==t.data.errno){var e=t.data.data;1==e.is_agent&&uni.redirectTo({url:"/pages/agent/index"})}})).catch((function(t){}))},ckChange:function(t){this.yxchecked=!this.yxchecked,this.yxchecked&&s.default.subscribe("upsus_template_id")},getBanner:function(){var e=this;this.$request.post("open/get_ads",{data:{key:"agapply"}}).then((function(t){0==t.data.errno&&(e.banners=t.data.data)})).catch((function(e){t.error("error:",e)}))},getDoc:function(){var e=this;this.$request.post("open/get_doc",{data:{id:9}}).then((function(t){0==t.data.errno&&(e.tsdoc=t.data.data)})).catch((function(e){t.error("error:",e)}))},openAgXieyi:function(){this.$refs.docbox.openPop()},queOrder:function(){if(!this.yxchecked)return this.toast("请先同意代理协议");this.createOrder()},createOrder:function(){var e=this;this.$request.post("customer/create_agent_order",{data:{grade_id:this.grades[this.grade_index].id}}).then((function(t){0==t.data.errno?e.requestPayment(t.data.data.payinfo,(function(t){t.status&&uni.redirectTo({url:"/pages/agent/index"})})):101==t.data.errno?uni.showModal({title:"支付信息",content:t.data.errmsg,showCancel:!1,confirmText:"好的",success:function(t){t.confirm&&uni.navigateTo({url:"/pages/agent/index"})}}):100==t.data.errno?(setTimeout((function(){uni.showModal({title:"支付信息",content:"是否已经成功支付？",cancelText:"未支付",confirmText:"已支付",success:function(t){t.confirm&&uni.navigateTo({url:"/pages/agent/index"})}})}),1e3),window.location.href=t.data.data.payinfo):uni.showToast({title:t.data.errmsg,icon:"none",duration:2e3})})).catch((function(e){t.error("error:",e)}))}}};e.default=c}).call(this,i("5a52")["default"])},"4ef5":function(t,e,i){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={components:{},data:function(){return{tsdoc:""}},mounted:function(){this.getDoc()},methods:{getDoc:function(){var e=this;this.$request.post("open/get_copy_right",{data:{}}).then((function(t){0==t.data.errno&&(e.tsdoc=t.data.data)})).catch((function(e){t.error("error:",e)}))}}};e.default=i}).call(this,i("5a52")["default"])},"505f":function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,"uni-page-body[data-v-4f36258a]{background-color:#f8f8f8}.main[data-v-4f36258a]{display:flex;flex-direction:column;align-items:center}.swiper[data-v-4f36258a]{width:%?750?%;height:%?340?%}.swiper-item .swiper-img[data-v-4f36258a]{width:%?750?%;height:%?340?%}.apmain[data-v-4f36258a]{width:%?710?%;background-color:#fff;box-shadow:0 0 0 1px #f8f8f8;border-radius:%?24?%;margin-top:%?-70?%;z-index:9;box-sizing:border-box;padding:%?40?% %?30?% %?80?% %?30?%;display:flex;flex-direction:column;align-items:center}.apmain .xieyi[data-v-4f36258a]{width:100%;display:flex;flex-direction:row;align-items:center;justify-content:center;color:#666;font-size:%?28?%;margin-top:%?40?%}.apmain .xieyi .checkbox[data-v-4f36258a]{-webkit-transform:scale(.6);transform:scale(.6)}.apmain .xieyi .a[data-v-4f36258a]{color:#1f2091}.apmain .btn[data-v-4f36258a]{width:100%;background-color:#0d8eea;line-height:%?90?%;border-radius:%?45?%;color:#fff;text-align:center;font-size:%?32?%;margin-top:%?40?%}.apmain .tishi[data-v-4f36258a]{width:100%;margin-top:%?80?%}.grades[data-v-4f36258a]{display:flex;flex-direction:row;min-width:%?750?%;white-space:nowrap;margin-top:%?10?%;margin-bottom:%?10?%;margin-left:%?10?%;margin-right:%?10?%}.apmain .item[data-v-4f36258a]{border:1px solid #f1f1f1;width:%?190?%;height:%?200?%;display:flex;flex-direction:column;align-items:center;justify-content:center;border-radius:%?20?%;margin-right:%?10?%}.apmain .item .name[data-v-4f36258a]{font-size:%?30?%;margin-bottom:%?30?%}.apmain .item .price[data-v-4f36258a]{color:red;font-size:%?28?%}.apmain .active[data-v-4f36258a]{border:1px solid #0d8eea;box-shadow:0 0 1px 1px #0d8eea!important}body.?%PAGE?%[data-v-4f36258a]{background-color:#f8f8f8}",""]),t.exports=e},"5e19":function(t,e,i){"use strict";(function(t){i("a9e3"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={data:function(){return{info:{}}},props:{docid:{type:Number},btntxt:{type:String,default:"知道了"}},mounted:function(){},onShow:function(){},methods:{openPop:function(t){this.getDoc(),this.$refs.popref.open()},closePop:function(){this.$refs.popref.close()},getDoc:function(e){var i=this;uni.showLoading({title:"请稍后"}),this.$request.post("open/get_doc",{data:{id:this.docid}}).then((function(t){uni.hideLoading(),0==t.data.errno?i.info=t.data.data:(i.toast("内容未找到"),i.$refs.popref.close())})).catch((function(e){t.error("error:",e)}))}}};e.default=n}).call(this,i("5a52")["default"])},"68f7":function(t,e,i){"use strict";(function(e){var n=i("4ea4");i("c975");var a=n(i("e9d6")),o=n(i("ad09")),r=n(i("d065")),s=n(i("b606")),c=function(){if(!o.default.isWeixinH5())return!1;var t=window.location.href,i=t.substring(0,t.indexOf("#"));r.default.request.post("Weixin/create_js_config",{data:{url:i,shareurl:document.location.protocol+"//"+window.location.hostname+"/#/"}}).then((function(t){if(0==t.data.errno){var e=t.data.data.config,i=t.data.data.share;a.default.config({debug:!1,appId:e.appid,timestamp:e.timestamp,nonceStr:e.noncestr,signature:e.signature,jsApiList:["updateAppMessageShareData","updateTimelineShareData","onMenuShareAppMessage","onMenuShareTimeline"]}),a.default.ready((function(){var t=uni.getStorageSync("userinfo")?JSON.parse(uni.getStorageSync("userinfo")):{},e={};e["appid"]=o.default.getAppid(),t&&(e["vi"]=t.id);var n="?"+s.default.stringify(e);a.default.updateAppMessageShareData({title:i.title,desc:i.desc,link:i.link+n,imgUrl:i.imgUrl,success:function(){}}),a.default.onMenuShareAppMessage({title:i.title,desc:i.desc,link:i.link+n,imgUrl:i.imgUrl,type:"link",dataUrl:"",success:function(){}}),a.default.onMenuShareTimeline({title:i.title,link:i.link+n,imgUrl:i.imgUrl,success:function(){}}),a.default.updateTimelineShareData({title:i.title,link:i.link+n,imgUrl:i.imgUrl,success:function(){}})}))}else uni.showToast({title:t.data.errmsg,icon:"none",duration:2e3})})).catch((function(t){e.error("error:",t)}))};t.exports={init:c}}).call(this,i("5a52")["default"])},"77ff":function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,".botif[data-v-4199efee]{margin-top:%?20?%;width:%?750?%;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;padding-top:%?20?%;padding-bottom:%?40?%}.botif .copyrg[data-v-4199efee]{width:%?710?%}",""]),t.exports=e},7995:function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"main"},[i("v-uni-view",{staticClass:"page-section swiper"},[i("v-uni-view",{staticClass:"page-section-spacing"},[i("v-uni-swiper",{staticClass:"swiper",attrs:{"indicator-dots":!0,circular:!0,autoplay:!0,interval:"5000",duration:500}},t._l(t.banners,(function(e,n){return i("v-uni-swiper-item",{key:n,staticClass:"swiper-item"},[i("v-uni-image",{staticClass:"swiper-img",attrs:{src:e.path,mode:"aspectFill"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.openH5Url(e.url)}}})],1)})),1)],1)],1),i("v-uni-view",{staticClass:"apmain"},[i("v-uni-scroll-view",{attrs:{"scroll-x":"true","scroll-with-animation":!0}},[i("v-uni-view",{staticClass:"grades",style:{width:3*t.grades.length*210+20+"rpx"}},t._l(t.grades,(function(e,n){return i("v-uni-view",{key:n,class:["item",t.grade_index==n?"active":""],on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.selGrade(n)}}},[i("v-uni-view",{staticClass:"name"},[t._v(t._s(e.grade_name))]),i("v-uni-view",{staticClass:"price"},[t._v("￥"+t._s(e.up_price))])],1)})),1)],1),i("v-uni-view",{staticClass:"xieyi"},[i("v-uni-checkbox",{staticClass:"checkbox",attrs:{color:"#0075F0",checked:t.yxchecked},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.ckChange.apply(void 0,arguments)}}}),i("v-uni-view",{on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.ckChange.apply(void 0,arguments)}}},[t._v("我已阅读并同意"),i("v-uni-text",{staticClass:"a",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.openAgXieyi.apply(void 0,arguments)}}},[t._v("《代理协议》")])],1)],1),i("v-uni-view",{staticClass:"btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.queOrder.apply(void 0,arguments)}}},[t._v("升级")]),i("v-uni-view",{staticClass:"tishi"},[i("v-uni-rich-text",{attrs:{nodes:t.tsdoc.body}})],1)],1),i("copy-right"),i("doc-box",{ref:"docbox",attrs:{docid:8}})],1)},o=[]},"80fc":function(t,e,i){"use strict";i.r(e);var n=i("8d7e"),a=i("30c7");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("08b2");var r,s=i("f0c5"),c=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"4199efee",null,!1,n["a"],r);e["default"]=c.exports},"8d7e":function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return t.tsdoc?i("v-uni-view",{staticClass:"botif"},[i("v-uni-view",{staticClass:"copyrg"},[i("div",{staticClass:"richbox",domProps:{innerHTML:t._s(t.tsdoc)}})])],1):t._e()},o=[]},9781:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,".boxs[data-v-29db1a81]{width:%?650?%;background-color:#fff;border-radius:%?24?%;min-height:%?20?%;padding-bottom:%?30?%;display:flex;flex-direction:column;align-items:center;justify-content:flex-start;position:relative;box-sizing:border-box}.title[data-v-29db1a81]{line-height:%?100?%;font-size:%?34?%;font-weight:600}.topbg[data-v-29db1a81]{width:100%;border-radius:%?24?% %?24?% 0 0;height:%?200?%}.close_ico[data-v-29db1a81]{position:absolute;right:10px;top:10px;width:%?30?%;height:%?30?%;z-index:999}.content[data-v-29db1a81]{max-height:60vh;min-height:%?300?%;width:%?610?%;overflow-y:scroll;margin-top:%?20?%}.btns[data-v-29db1a81]{width:%?610?%;display:flex;flex-direction:row;justify-content:space-around;align-items:center;margin-top:%?40?%}.btns .btn[data-v-29db1a81]{background-color:#0d8eea;color:#fff;height:%?80?%;line-height:%?80?%;text-align:center;padding-left:%?90?%;padding-right:%?90?%;border-radius:%?40?%;font-size:%?30?%}",""]),t.exports=e},"98f6":function(t,e,i){"use strict";i.r(e);var n=i("b34d"),a=i("1133");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("d323");var r,s=i("f0c5"),c=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"29db1a81",null,!1,n["a"],r);e["default"]=c.exports},a6bc:function(t,e,i){var n=i("505f");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("40d5d952",n,!0,{sourceMap:!1,shadowMode:!1})},b34d:function(t,e,i){"use strict";i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var n={uniPopup:i("06c7").default},a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("uni-popup",{ref:"popref",attrs:{type:"center"}},[i("v-uni-view",{staticClass:"boxs"},[t.info.litpic?[i("v-uni-image",{staticClass:"close_ico",attrs:{src:"/static/close_w.png"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closePop.apply(void 0,arguments)}}}),i("v-uni-image",{staticClass:"topbg",attrs:{src:t.info.litpic,mode:"aspectFill"}})]:[i("v-uni-image",{staticClass:"close_ico",attrs:{src:"/static/close_g.png"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closePop.apply(void 0,arguments)}}}),i("v-uni-view",{staticClass:"title"},[t._v(t._s(t.info.title))])],i("v-uni-scroll-view",{staticClass:"content",attrs:{"scroll-y":"true"}},[i("div",{staticClass:"richbox",domProps:{innerHTML:t._s(t.info.body)}})]),t.btntxt?i("v-uni-view",{staticClass:"btns"},[i("v-uni-view",{staticClass:"btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closePop.apply(void 0,arguments)}}},[t._v(t._s(t.btntxt))])],1):t._e()],2)],1)},o=[]},b5d3:function(t,e,i){"use strict";i.r(e);var n=i("7995"),a=i("2072");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("fea9d");var r,s=i("f0c5"),c=Object(s["a"])(a["default"],n["b"],n["c"],!1,null,"4f36258a",null,!1,n["a"],r);e["default"]=c.exports},d323:function(t,e,i){"use strict";var n=i("3a19"),a=i.n(n);a.a},df48:function(t,e,i){var n=i("77ff");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("945ef01e",n,!0,{sourceMap:!1,shadowMode:!1})},fea9d:function(t,e,i){"use strict";var n=i("a6bc"),a=i.n(n);a.a}}]);