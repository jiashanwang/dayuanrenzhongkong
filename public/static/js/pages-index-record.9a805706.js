(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-index-record"],{"0d08":function(t,e,a){"use strict";var i=a("1a71"),n=a.n(i);n.a},"0df0":function(t,e,a){var i=a("24fb");e=i(!1),e.push([t.i,".tabs[data-v-158ba03e]{display:flex;flex-direction:row;align-items:center;justify-content:space-around;box-shadow:0 0 1px 0 #e1e1e1;background-color:#fff;z-index:9999}.tabs .item[data-v-158ba03e]{line-height:%?90?%;display:flex;flex-direction:column;align-items:center;font-size:%?32?%}.tabs .item .border[data-v-158ba03e]{height:%?6?%;width:%?36?%;opacity:0;border-radius:%?3?%;margin-bottom:%?10?%;background-color:#fff}.tabs .active[data-v-158ba03e]{color:#0d89eb}.tabs .active .border[data-v-158ba03e]{background-color:#0d89eb;opacity:1}.ul[data-v-158ba03e]{width:%?700?%;\n\t/* height: 280rpx; */margin:%?20?% auto;box-shadow:0 0 2px 2px #eaeaea;border-radius:%?10?%}.ul .li[data-v-158ba03e]{display:flex;justify-content:space-between;align-items:center;height:%?90?%;font-size:%?32?%;color:#000;padding:0 %?20?%}.disalet[data-v-158ba03e]{border-bottom:1px dashed #dadada}.blod[data-v-158ba03e]{font-weight:700}.ul>.li>.colors[data-v-158ba03e]{color:#637eee}.span uni-image[data-v-158ba03e]{display:block;width:%?32?%;height:%?36?%}.ul .btns[data-v-158ba03e]{width:%?700?%;height:%?80?%;display:flex;flex-direction:row;flex-wrap:nowrap;justify-content:flex-end;align-items:center}.ul .btns .btn[data-v-158ba03e]{font-size:%?24?%;height:%?60?%;background-color:#007aff;color:#fff;border-radius:%?10?%;line-height:%?60?%;padding-left:%?20?%;padding-right:%?20?%;margin-right:%?20?%}",""]),t.exports=e},"1a71":function(t,e,a){var i=a("0df0");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("14976df6",i,!0,{sourceMap:!1,shadowMode:!1})},"8f06":function(t,e,a){"use strict";var i;a.d(e,"b",(function(){return n})),a.d(e,"c",(function(){return s})),a.d(e,"a",(function(){return i}));var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",[a("v-uni-view",{staticClass:"tabs"},t._l(t.producttype,(function(e,i){return a("v-uni-view",{key:i,class:["item",t.typeid==e.id?"active":""],on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.tabChange(e.id)}}},[a("v-uni-view",[t._v(t._s(e.type_name)+"订单")]),a("v-uni-view",{staticClass:"border"})],1)})),1),a("s-pull-scroll",{ref:"pullScroll",attrs:{pullDown:t.pullDown,pullUp:t.loadData,top:110,emptyText:"还没有充值记录"}},[a("v-uni-view",{staticClass:"content"},t._l(t.lists,(function(e,i){return a("v-uni-view",{key:i,staticClass:"ul"},[a("v-uni-view",{staticClass:"li disalet"},[a("v-uni-view",{staticClass:"span"},[t._v(t._s(e.create_time_text))]),a("v-uni-view",{staticClass:"span colors"},[t._v(t._s(e.state_text))])],1),a("v-uni-view",{staticClass:"li"},[a("v-uni-view",{staticClass:"span",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.copyText(e.order_number)}}},[t._v(t._s(e.order_number))]),a("v-uni-view",{staticClass:"span"},[t._v(t._s(e.product_desc))])],1),a("v-uni-view",{staticClass:"li"},[a("v-uni-view",{staticClass:"span",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.copyText(e.mobile)}}},[t._v(t._s(e.mobile))]),a("v-uni-view",{staticClass:"span blod"},[t._v("￥"+t._s(e.total_price))])],1),a("v-uni-view",{staticClass:"btns"},[a("div",{staticClass:"btn cancel",on:{click:function(a){arguments[0]=a=t.$handleEvent(a),t.navigateTo("/pages/my/complaint?id="+e.id)}}},[t._v("订单投诉")])])],1)})),1)],1)],1)},s=[]},a36f:function(t,e,a){"use strict";a.r(e);var i=a("b9c7"),n=a.n(i);for(var s in i)"default"!==s&&function(t){a.d(e,t,(function(){return i[t]}))}(s);e["default"]=n.a},b9c7:function(t,e,a){"use strict";(function(t){var i=a("4ea4");Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=i(a("8745")),s={components:{sPullScroll:n.default},data:function(){return{producttype:[],typeid:0,lists:[]}},onLoad:function(t){t.type&&(this.typeid=t.type),this.getProductType()},mounted:function(){},methods:{getProductType:function(){var e=this;this.$request.post("index/get_product_type",{data:{key:"PRODUCT_TYPE"}}).then((function(t){0==t.data.errno&&(e.producttype=t.data.data,e.producttype.length>0&&0==e.typeid&&(e.typeid=e.producttype[0].id),e.refresh())})).catch((function(e){t.error("error:",e)}))},tabChange:function(t){this.typeid=t,this.refresh()},refresh:function(){var t=this;this.$nextTick((function(){t.$refs.pullScroll.refresh()}))},pullDown:function(t){var e=this;setTimeout((function(){e.loadData(t)}),200)},loadData:function(e){var a=this;1==e.page&&(this.lists=[]),this.$request.post("Porder/order_list?page="+e.page,{data:{type:this.typeid}}).then((function(t){if(1==e.page&&(a.lists=[]),0==t.data.errno&&t.data.data.data.length>0){e.success();for(var i=0;i<t.data.data.data.length;i++)a.lists.push(t.data.data.data[i])}else 1==e.page?e.empty():e.finish()})).catch((function(e){t.error("error:",e)}))}}};e.default=s}).call(this,a("5a52")["default"])},d5f2:function(t,e,a){"use strict";a.r(e);var i=a("8f06"),n=a("a36f");for(var s in n)"default"!==s&&function(t){a.d(e,t,(function(){return n[t]}))}(s);a("0d08");var o,r=a("f0c5"),d=Object(r["a"])(n["default"],i["b"],i["c"],!1,null,"158ba03e",null,!1,i["a"],o);e["default"]=d.exports}}]);