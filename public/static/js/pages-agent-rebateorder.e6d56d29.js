(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["pages-agent-rebateorder"],{"025e":function(t,e,a){var i=a("839f");"string"===typeof i&&(i=[[t.i,i,""]]),i.locals&&(t.exports=i.locals);var n=a("4f06").default;n("4ad9d302",i,!0,{sourceMap:!1,shadowMode:!1})},"839f":function(t,e,a){var i=a("24fb");e=i(!1),e.push([t.i,".datapicker[data-v-ab1c2670]{width:%?750?%;display:flex;flex-direction:row;flex-wrap:nowrap;height:%?80?%;border-bottom:%?1?% solid #f1f1f1}.datapicker .item[data-v-ab1c2670]{flex-grow:1;font-size:%?30?%;color:#444;text-align:center;line-height:%?80?%;height:%?80?%}.sel[data-v-ab1c2670]{border-right:%?1?% solid #f1f1f1}.tongji[data-v-ab1c2670]{font-size:%?30?%;height:%?60?%;line-height:%?60?%;text-align:center;border-bottom:%?1?% solid #f1f1f1;color:#ff6801}.jllist[data-v-ab1c2670]{width:%?750?%;display:flex;flex-direction:column}.jllist .item[data-v-ab1c2670]{width:%?750?%;height:%?120?%;border-bottom:1px solid #dfdfdf;display:flex;flex-direction:row}.jllist .item .l[data-v-ab1c2670]{width:%?100?%;display:flex;flex-direction:row;align-items:center;justify-content:center}.jllist .item .r[data-v-ab1c2670]{width:%?630?%;display:flex;flex-direction:column;justify-content:center;margin-left:%?10?%}.jllist .item .icon[data-v-ab1c2670]{width:%?60?%;height:%?60?%}.jllist .item .jl[data-v-ab1c2670]{font-size:%?35?%;display:flex;flex-direction:row;flex-wrap:nowrap;justify-content:space-between;width:%?590?%;height:%?50?%;line-height:%?50?%}.jllist .item .jl>uni-text[data-v-ab1c2670]{font-size:%?28?%}.jllist .item .xiao[data-v-ab1c2670]{color:#666;font-size:%?25?%}",""]),t.exports=e},"9e2d0":function(t,e,a){"use strict";(function(t){var i=a("4ea4");a("99af"),Object.defineProperty(e,"__esModule",{value:!0}),e.default=void 0;var n=i(a("8745")),s={components:{sPullScroll:n.default},data:function(){this.getDate({format:!0});return{lists:[],begin_time:this.getDate("month"),end_time:this.getDate(),total_price:0,rebate_price:0,counts:0}},onLoad:function(){},mounted:function(){this.refresh()},computed:{startDate:function(){return this.getDate("start")},endDate:function(){return this.getDate("end")}},watch:{begin_time:function(){this.refresh()},end_time:function(){this.refresh()}},methods:{refresh:function(){var t=this;this.$nextTick((function(){t.$refs.pullScroll.refresh()}))},pullDown:function(t){var e=this;setTimeout((function(){e.loadData(t)}),200)},loadData:function(e){var a=this;1==e.page&&(this.lists=[]),this.$request.post("Agent/rebate_order?page="+e.page,{data:{begin_time:this.begin_time,end_time:this.end_time}}).then((function(t){if(0==t.data.errno&&t.data.data.lists.data.length>0){e.success(),a.total_price=t.data.data.total_price,a.rebate_price=t.data.data.rebate_price,a.counts=t.data.data.counts;for(var i=0;i<t.data.data.lists.data.length;i++)a.lists.push(t.data.data.lists.data[i])}else 1==e.page?e.empty():e.finish()})).catch((function(e){t.error("error:",e)}))},bindBeginDateChange:function(t){this.begin_time=t.target.value},bindEndDateChange:function(t){this.end_time=t.target.value},getDate:function(t){var e=new Date,a=e.getFullYear(),i=e.getMonth()+1,n=e.getDate();return"start"===t&&(a=2020,i=3,n=1),"month"===t&&(n=1),i=i>9?i:"0"+i,n=n>9?n:"0"+n,"".concat(a,"-").concat(i,"-").concat(n)}}};e.default=s}).call(this,a("5a52")["default"])},b404:function(t,e,a){"use strict";a.r(e);var i=a("9e2d0"),n=a.n(i);for(var s in i)"default"!==s&&function(t){a.d(e,t,(function(){return i[t]}))}(s);e["default"]=n.a},ba7f:function(t,e,a){"use strict";var i;a.d(e,"b",(function(){return n})),a.d(e,"c",(function(){return s})),a.d(e,"a",(function(){return i}));var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-uni-view",{},[a("v-uni-view",{staticClass:"datapicker"},[a("v-uni-view",{staticClass:"item"},[t._v("查询区间:")]),a("v-uni-picker",{staticClass:"sel item",attrs:{mode:"date",start:t.startDate,end:t.endDate,value:t.begin_time},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.bindBeginDateChange.apply(void 0,arguments)}}},[a("v-uni-view",{staticClass:"uni-input"},[t._v(t._s(t.begin_time))])],1),a("v-uni-picker",{staticClass:"item",attrs:{mode:"date",start:t.startDate,end:t.endDate,value:t.end_time},on:{change:function(e){arguments[0]=e=t.$handleEvent(e),t.bindEndDateChange.apply(void 0,arguments)}}},[a("v-uni-view",{staticClass:"uni-input"},[t._v(t._s(t.end_time))])],1)],1),a("v-uni-view",{staticClass:"tongji"},[t._v("订单数:"+t._s(t.counts)+" 充值金额:￥"+t._s(t.total_price)+" 返利金额:￥"+t._s(t.rebate_price))]),a("s-pull-scroll",{ref:"pullScroll",attrs:{pullDown:t.pullDown,pullUp:t.loadData,top:140,emptyText:"还没有返利订单"}},[a("v-uni-view",{staticClass:"jllist"},t._l(t.lists,(function(e,i){return a("v-uni-view",{key:i,staticClass:"item"},[a("v-uni-view",{staticClass:"l"},[a("v-uni-image",{staticClass:"icon",attrs:{src:"/static/shouyiicon.png"}})],1),a("v-uni-view",{staticClass:"r"},[a("v-uni-view",{staticClass:"jl"},[a("v-uni-text",{staticStyle:{color:"#444"}},[t._v(t._s(e.title))]),a("v-uni-text",{staticStyle:{color:"#444"}},[t._v("返利：￥"+t._s(t.moneyFloat(e.rebate_price)))]),e.is_rebate?a("v-uni-text",{staticStyle:{color:"#00CC33"}},[t._v(t._s(e.rebate_status_text))]):a("v-uni-text",{staticStyle:{color:"#f00"}},[t._v(t._s(e.rebate_status_text))])],1),a("v-uni-view",{staticClass:"jl"},[a("v-uni-text",{staticClass:"xiao"},[t._v(t._s(e.order_number))]),a("v-uni-text",{staticClass:"xiao"},[t._v(t._s(e.create_time_text))])],1)],1)],1)})),1)],1)],1)},s=[]},c0b8:function(t,e,a){"use strict";var i=a("025e"),n=a.n(i);n.a},d6a4:function(t,e,a){"use strict";a.r(e);var i=a("ba7f"),n=a("b404");for(var s in n)"default"!==s&&function(t){a.d(e,t,(function(){return n[t]}))}(s);a("c0b8");var r,l=a("f0c5"),o=Object(l["a"])(n["default"],i["b"],i["c"],!1,null,"ab1c2670",null,!1,i["a"],r);e["default"]=o.exports}}]);