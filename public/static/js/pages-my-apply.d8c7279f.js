(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-my-apply"],{1133:function(t,e,i){"use strict";i.r(e);var n=i("5e19"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},2072:function(t,e,i){"use strict";i.r(e);var n=i("3b79"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},"2bab":function(t,e,i){"use strict";var n=i("7da5"),a=i.n(n);a.a},"30c7":function(t,e,i){"use strict";i.r(e);var n=i("4ef5"),a=i.n(n);for(var o in n)"default"!==o&&function(t){i.d(e,t,(function(){return n[t]}))}(o);e["default"]=a.a},"3a61":function(t,e,i){var n=i("a923");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("a0d84a6e",n,!0,{sourceMap:!1,shadowMode:!1})},"3b10":function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,"uni-page-body[data-v-c679d1e6]{background-color:#f8f8f8}.main[data-v-c679d1e6]{display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.swiper[data-v-c679d1e6]{width:%?750?%;height:%?340?%}.swiper-item .swiper-img[data-v-c679d1e6]{width:%?750?%;height:%?340?%}.apmain[data-v-c679d1e6]{width:%?710?%;background-color:#fff;-webkit-box-shadow:0 0 0 1px #f8f8f8;box-shadow:0 0 0 1px #f8f8f8;border-radius:%?24?%;margin-top:%?-70?%;z-index:9;-webkit-box-sizing:border-box;box-sizing:border-box;padding:%?40?% %?30?% %?80?% %?30?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center}.apmain .price[data-v-c679d1e6]{font-size:%?80?%;margin-top:%?20?%;font-weight:600}.apmain .xieyi[data-v-c679d1e6]{width:100%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:center;-webkit-justify-content:center;justify-content:center;color:#666;font-size:%?28?%;margin-top:%?40?%}.apmain .xieyi .checkbox[data-v-c679d1e6]{-webkit-transform:scale(.6);transform:scale(.6)}.apmain .xieyi .a[data-v-c679d1e6]{color:#1f2091}.apmain .btn[data-v-c679d1e6]{width:100%;background-color:#0d8eea;line-height:%?90?%;border-radius:%?45?%;color:#fff;text-align:center;font-size:%?32?%;margin-top:%?40?%}.apmain .tishi[data-v-c679d1e6]{width:100%;margin-top:%?80?%}body.?%PAGE?%[data-v-c679d1e6]{background-color:#f8f8f8}",""]),t.exports=e},"3b79":function(t,e,i){"use strict";(function(t){var n=i("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var a=n(i("80fc")),o=n(i("98f6")),r=n(i("ad09")),c={components:{CopyRight:a.default,DocBox:o.default},data:function(){return{yxchecked:!1,banners:[],tsdoc:{},agent2info:{}}},onLoad:function(){this.getAgentInfo(),this.initUserInfo()},mounted:function(){this.getBanner(),this.getDoc()},watch:{},methods:{getAgentInfo:function(){var e=this;this.$request.post("customer/get_grade_info",{data:{grade_id:2}}).then((function(t){0==t.data.errno&&(e.agent2info=t.data.data)})).catch((function(e){t.error("error:",e)}))},initUserInfo:function(){uni.showLoading({title:"加载中.."}),this.$request.post("Customer/info",{data:{}}).then((function(t){if(uni.hideLoading(),0==t.data.errno){var e=t.data.data;2==e.grade_id&&uni.redirectTo({url:"/pages/agent/index"}),3==e.grade_id&&uni.redirectTo({url:"/pages/agent/index"})}})).catch((function(t){}))},ckChange:function(t){this.yxchecked=!this.yxchecked},getBanner:function(){var e=this;this.$request.post("open/get_ads",{data:{key:"agapply"}}).then((function(i){t.log(i),0==i.data.errno&&(e.banners=i.data.data)})).catch((function(e){t.error("error:",e)}))},getDoc:function(){var e=this;this.$request.post("open/get_doc",{data:{id:9}}).then((function(t){0==t.data.errno&&(e.tsdoc=t.data.data)})).catch((function(e){t.error("error:",e)}))},openAgXieyi:function(){this.$refs.docbox.openPop()},queOrder:function(){if(!this.yxchecked)return this.toast("请先同意代理协议");this.createOrder()},createOrder:function(){var e=this;this.$request.post("customer/create_agent_order",{data:{grade_id:2}}).then((function(i){t.log(i),0==i.data.errno?r.default.isWeixinH5()?e.requestPayment(i.data.data.payinfo,(function(e){t.log(e),e.status&&uni.navigateTo({url:"/pages/agent/index"})})):(uni.showModal({title:"支付信息",content:"是否已经成功支付？",cancelText:"未支付",confirmText:"已支付",success:function(t){t.confirm&&uni.navigateTo({url:"/pages/agent/index"})}}),window.location.href=i.data.data.payinfo):2==i.data.errno?uni.showModal({title:"提示",content:i.data.errmsg,confirmText:"确定",showCancel:!1,success:function(t){window.location.reload()}}):uni.showToast({title:i.data.errmsg,icon:"none",duration:2e3})})).catch((function(e){t.error("error:",e)}))}}};e.default=c}).call(this,i("5a52")["default"])},"4ef5":function(t,e,i){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={components:{},data:function(){return{tsdoc:{}}},mounted:function(){this.getDoc()},methods:{getDoc:function(){var e=this;this.$request.post("open/get_doc",{data:{id:5}}).then((function(t){0==t.data.errno&&(e.tsdoc=t.data.data)})).catch((function(e){t.error("error:",e)}))}}};e.default=i}).call(this,i("5a52")["default"])},"5e19":function(t,e,i){"use strict";(function(t){i("a9e3"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n={data:function(){return{info:{}}},props:{docid:{type:Number},btntxt:{type:String,default:"知道了"}},mounted:function(){},onShow:function(){},methods:{openPop:function(t){this.getDoc(),this.$refs.popref.open()},closePop:function(){this.$refs.popref.close()},getDoc:function(e){var i=this;uni.showLoading({title:"请稍后"}),this.$request.post("open/get_doc",{data:{id:this.docid}}).then((function(t){uni.hideLoading(),0==t.data.errno?i.info=t.data.data:(i.toast("内容未找到"),i.$refs.popref.close())})).catch((function(e){t.error("error:",e)}))}}};e.default=n}).call(this,i("5a52")["default"])},"6f1b":function(t,e,i){"use strict";i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var n={uniPopup:i("06c7").default},a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("uni-popup",{ref:"popref",attrs:{type:"center"}},[i("v-uni-view",{staticClass:"boxs"},[t.info.litpic?[i("v-uni-image",{staticClass:"close_ico",attrs:{src:"/static/close_w.png"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closePop.apply(void 0,arguments)}}}),i("v-uni-image",{staticClass:"topbg",attrs:{src:t.info.litpic,mode:"aspectFill"}})]:[i("v-uni-image",{staticClass:"close_ico",attrs:{src:"/static/close_g.png"},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closePop.apply(void 0,arguments)}}}),i("v-uni-view",{staticClass:"title"},[t._v(t._s(t.info.title))])],i("v-uni-scroll-view",{staticClass:"content",attrs:{"scroll-y":"true"}},[i("div",{staticClass:"richbox",domProps:{innerHTML:t._s(t.info.body)}})]),t.btntxt?i("v-uni-view",{staticClass:"btns"},[i("v-uni-view",{staticClass:"btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.closePop.apply(void 0,arguments)}}},[t._v(t._s(t.btntxt))])],1):t._e()],2)],1)},o=[]},"7a6a":function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"botif"},[i("v-uni-view",{staticClass:"copyrg"},[i("div",{staticClass:"richbox",domProps:{innerHTML:t._s(t.tsdoc.body)}})])],1)},o=[]},"7a9c":function(t,e,i){"use strict";var n;i.d(e,"b",(function(){return a})),i.d(e,"c",(function(){return o})),i.d(e,"a",(function(){return n}));var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",{staticClass:"main"},[i("v-uni-view",{staticClass:"page-section swiper"},[i("v-uni-view",{staticClass:"page-section-spacing"},[i("v-uni-swiper",{staticClass:"swiper",attrs:{"indicator-dots":!0,circular:!0,autoplay:!0,interval:"5000",duration:500}},t._l(t.banners,(function(e,n){return i("v-uni-swiper-item",{key:n,staticClass:"swiper-item"},[i("v-uni-image",{staticClass:"swiper-img",attrs:{src:e.path,mode:"aspectFill"},on:{click:function(i){arguments[0]=i=t.$handleEvent(i),t.openH5Url(e.url)}}})],1)})),1)],1)],1),i("v-uni-view",{staticClass:"apmain"},[i("v-uni-view",{staticClass:"price"},[i("v-uni-text",{staticClass:"text-size40"},[t._v("￥")]),t._v(t._s(t.agent2info.up_price))],1),i("v-uni-view",{staticClass:"xieyi"},[i("v-uni-checkbox",{staticClass:"checkbox",attrs:{color:"#0075F0",checked:t.yxchecked},on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.ckChange.apply(void 0,arguments)}}}),i("v-uni-view",{on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.ckChange.apply(void 0,arguments)}}},[t._v("我已阅读并同意"),i("v-uni-text",{staticClass:"a",on:{click:function(e){e.stopPropagation(),arguments[0]=e=t.$handleEvent(e),t.openAgXieyi.apply(void 0,arguments)}}},[t._v("《代理协议》")])],1)],1),i("v-uni-view",{staticClass:"btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.queOrder.apply(void 0,arguments)}}},[t._v("提交")]),i("v-uni-view",{staticClass:"tishi"},[i("v-uni-rich-text",{attrs:{nodes:t.tsdoc.body}})],1)],1),i("copy-right"),i("doc-box",{ref:"docbox",attrs:{docid:8}})],1)},o=[]},"7da5":function(t,e,i){var n=i("3b10");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("67ab1b80",n,!0,{sourceMap:!1,shadowMode:!1})},"80fc":function(t,e,i){"use strict";i.r(e);var n=i("7a6a"),a=i("30c7");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("9ee2");var r,c=i("f0c5"),s=Object(c["a"])(a["default"],n["b"],n["c"],!1,null,"53c57609",null,!1,n["a"],r);e["default"]=s.exports},"98f6":function(t,e,i){"use strict";i.r(e);var n=i("6f1b"),a=i("1133");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("d65f");var r,c=i("f0c5"),s=Object(c["a"])(a["default"],n["b"],n["c"],!1,null,"b7e0e4fc",null,!1,n["a"],r);e["default"]=s.exports},"9ee2":function(t,e,i){"use strict";var n=i("bd65"),a=i.n(n);a.a},a923:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,".boxs[data-v-b7e0e4fc]{width:%?650?%;background-color:#fff;border-radius:%?24?%;min-height:%?20?%;padding-bottom:%?30?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start;position:relative;-webkit-box-sizing:border-box;box-sizing:border-box}.title[data-v-b7e0e4fc]{line-height:%?100?%;font-size:%?34?%;font-weight:600}.topbg[data-v-b7e0e4fc]{width:100%;border-radius:%?24?% %?24?% 0 0;height:%?200?%}.close_ico[data-v-b7e0e4fc]{position:absolute;right:10px;top:10px;width:%?30?%;height:%?30?%;z-index:999}.content[data-v-b7e0e4fc]{max-height:70vh;min-height:%?300?%;width:%?610?%;overflow-y:scroll;margin-top:%?20?%}.btns[data-v-b7e0e4fc]{width:%?610?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-webkit-flex-direction:row;flex-direction:row;-webkit-justify-content:space-around;justify-content:space-around;-webkit-box-align:center;-webkit-align-items:center;align-items:center;margin-top:%?40?%}.btns .btn[data-v-b7e0e4fc]{background-color:#0d8eea;color:#fff;height:%?80?%;line-height:%?80?%;text-align:center;padding-left:%?90?%;padding-right:%?90?%;border-radius:%?40?%;font-size:%?30?%}",""]),t.exports=e},b5d3:function(t,e,i){"use strict";i.r(e);var n=i("7a9c"),a=i("2072");for(var o in a)"default"!==o&&function(t){i.d(e,t,(function(){return a[t]}))}(o);i("2bab");var r,c=i("f0c5"),s=Object(c["a"])(a["default"],n["b"],n["c"],!1,null,"c679d1e6",null,!1,n["a"],r);e["default"]=s.exports},bd65:function(t,e,i){var n=i("c012");"string"===typeof n&&(n=[[t.i,n,""]]),n.locals&&(t.exports=n.locals);var a=i("4f06").default;a("2fb7cb17",n,!0,{sourceMap:!1,shadowMode:!1})},c012:function(t,e,i){var n=i("24fb");e=n(!1),e.push([t.i,".botif[data-v-53c57609]{margin-top:%?20?%;width:%?750?%;display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-webkit-flex-direction:column;flex-direction:column;-webkit-box-align:center;-webkit-align-items:center;align-items:center;-webkit-box-pack:start;-webkit-justify-content:flex-start;justify-content:flex-start;padding-top:%?20?%;padding-bottom:%?40?%}.botif .copyrg[data-v-53c57609]{width:%?710?%}",""]),t.exports=e},d65f:function(t,e,i){"use strict";var n=i("3a61"),a=i.n(n);a.a}}]);