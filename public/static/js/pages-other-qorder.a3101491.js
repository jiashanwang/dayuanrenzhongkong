(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-other-qorder"],{"0d82":function(t,e,i){var a=i("c3b6");"string"===typeof a&&(a=[[t.i,a,""]]),a.locals&&(t.exports=a.locals);var n=i("4f06").default;n("051c1bac",a,!0,{sourceMap:!1,shadowMode:!1})},"0f09":function(t,e,i){"use strict";(function(t){Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var i={components:{},data:function(){return{key:"",lists:[]}},onLoad:function(){},methods:{getOrderList:function(){var e=this;this.showLoading(),this.lists=[],this.$request.post("more/queryorder",{data:{key:this.key}}).then((function(t){uni.hideLoading(),0==t.data.errno?e.lists=t.data.data:e.toast(t.data.errmsg)})).catch((function(e){t.error("error:",e)}))}}};e.default=i}).call(this,i("5a52")["default"])},"4cfb":function(t,e,i){"use strict";var a=i("0d82"),n=i.n(a);n.a},"6c00":function(t,e,i){"use strict";i.r(e);var a=i("b2c6"),n=i("808e");for(var r in n)"default"!==r&&function(t){i.d(e,t,(function(){return n[t]}))}(r);i("4cfb");var s,o=i("f0c5"),v=Object(o["a"])(n["default"],a["b"],a["c"],!1,null,"178a91b0",null,!1,a["a"],s);e["default"]=v.exports},"808e":function(t,e,i){"use strict";i.r(e);var a=i("0f09"),n=i.n(a);for(var r in a)"default"!==r&&function(t){i.d(e,t,(function(){return a[t]}))}(r);e["default"]=n.a},b2c6:function(t,e,i){"use strict";var a;i.d(e,"b",(function(){return n})),i.d(e,"c",(function(){return r})),i.d(e,"a",(function(){return a}));var n=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-uni-view",[i("v-uni-view",{staticClass:"bgmain"},[i("v-uni-view",{staticClass:"serdiv"},[i("v-uni-input",{attrs:{type:"text",placeholder:"输入充值号码/单号查询"},model:{value:t.key,callback:function(e){t.key=e},expression:"key"}}),i("v-uni-view",{staticClass:"btn",on:{click:function(e){arguments[0]=e=t.$handleEvent(e),t.getOrderList.apply(void 0,arguments)}}},[t._v("查询")])],1),i("v-uni-scroll-view",{staticClass:"scroll-Y",attrs:{"scroll-y":"false","scroll-x":"true"}},[i("v-uni-view",{staticClass:"tb"},[i("v-uni-view",{staticClass:"table"},[i("v-uni-view",{staticClass:"tr th"},[i("v-uni-view",[t._v("充值账号")]),i("v-uni-view",[t._v("状态")]),i("v-uni-view",[t._v("商家单号")]),i("v-uni-view",[t._v("系统单号")]),i("v-uni-view",[t._v("产品")]),i("v-uni-view",[t._v("下单时间")]),i("v-uni-view",[t._v("完成时间")]),i("v-uni-view",[t._v("其他")]),i("v-uni-view",[t._v("备注")])],1),t._l(t.lists,(function(e,a){return i("v-uni-view",{key:a,staticClass:"tr"},[i("v-uni-view",[t._v(t._s(e.mobile))]),i("v-uni-view",[t._v(t._s(e.status_text2))]),i("v-uni-view",[t._v(t._s(e.out_trade_num))]),i("v-uni-view",[t._v(t._s(e.order_number))]),i("v-uni-view",[t._v(t._s(e.product_name))]),i("v-uni-view",[t._v(t._s(e.create_time))]),i("v-uni-view",[t._v(t._s(t.timeFormat(e.finish_time)))]),i("v-uni-view",[t._v(t._s(e.guishu)+"-"+t._s(e.isp)+"-"+t._s(e.param3)+"-"+t._s(e.param2)+"-"+t._s(e.param1))]),i("v-uni-view",[t._v(t._s(e.remark))])],1)}))],2)],1)],1)],1)],1)},r=[]},c3b6:function(t,e,i){var a=i("24fb");e=a(!1),e.push([t.i,".bgmain[data-v-178a91b0]{display:flex;flex-direction:column;align-items:flex-start}.bgmain .tb[data-v-178a91b0]{width:1200px;margin-top:%?20?%}.serdiv[data-v-178a91b0]{display:flex;flex-direction:row;justify-content:center;align-items:center;margin-top:%?10?%;margin-left:%?20?%}.serdiv>uni-input[data-v-178a91b0]{border:1px solid #0d8eea;height:%?90?%;width:%?480?%;border-radius:%?16?% %?0?% %?0?% %?16?%;padding-left:%?30?%}.serdiv .btn[data-v-178a91b0]{width:%?200?%;text-align:center;height:%?90?%;line-height:%?90?%;border-radius:%?0?% %?16?% %?16?% %?0?%;background-color:#0d8eea;border:1px solid #0d8eea;color:#fff}.table[data-v-178a91b0]{display:flex;flex-direction:column;margin-top:%?10?%}.table .tr[data-v-178a91b0]{display:flex;flex-direction:row;flex-wrap:nowrap;justify-content:center;align-items:center;width:100%;font-size:%?24?%;height:%?100?%;border-bottom:1px solid #f8f8f8}.table .tr[data-v-178a91b0]:nth-child(even){background-color:#fafafa}.table .th[data-v-178a91b0]{color:#000;font-weight:600}.table .tr>uni-view[data-v-178a91b0]{width:16%;text-align:center}",""]),t.exports=e}}]);