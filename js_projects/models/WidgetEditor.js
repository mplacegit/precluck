'use strict';
var BridgeLib = require('./iFrameBridge');
var Bridge = BridgeLib.Bridge;
var CallAction = BridgeLib.callAction;
var ajax = require('./httpclient').ajax;
var WidgetEditor = function (container,src) {
this.container=container.get(0);

this.src=src||"http://precluck.market-place.su/widget/render";
this.iFrame=null;
this.params={width:"200px",height:"200px"};
this.plugins={};
this.index="0";
 this.Bridge = new Bridge(this.index);
    var self= this;
    this.Bridge.addAction('resize', function (data) {
	    //alert("resize приехал "+data.size);
        self.resize(data.size);
		self.loadPlugins(["WidgetSliderPlugin"]);
        // self.fire('ready');
        // self.appendInFrameElement({id:"geo",className:"geo-frame"},'<input value="inframe"  type="button">');
    });
};
WidgetEditor.prototype.createIframe = function (params) {
params=params||{};
for (var pkey in this.params){
console.log(["пключ",pkey]);
if(!params.hasOwnProperty(pkey))
params[pkey]=this.params[pkey];
}
var self=this;
this.getProducts(params,function d1(res){
var data=JSON.parse(res);

if(data.hasOwnProperty('hash')){
self.iFrame=document.createElement("iframe");
    self.iFrame.scrolling = "no";
    self.iFrame.style.border = "0";
    self.iFrame.style.margin = "0";
    self.iFrame.style.width = "100%";
var url=self.src+'?data='+JSON.stringify(params)+'&index='+self.index+'&hash='+data.hash;

self.iFrame.src=url;
self.container.appendChild(self.iFrame);
}else{

}

},function d2(error){
alert('not ok');
}); 


};
WidgetEditor.prototype.reloadIframe = function (params) {
var self=this;
for (var pkey in this.params){
console.log(["пключ",pkey]);
if(!params.hasOwnProperty(pkey))
params[pkey]=this.params[pkey];
}

this.getProducts(params,function d1(res){
var data=JSON.parse(res);

if(data.hasOwnProperty('hash')){

var url=self.src+'?data='+JSON.stringify(params)+'&index='+self.index+'&hash='+data.hash;

self.iFrame.src=url;

self.container.appendChild(self.iFrame);
}else{

}},function d2(error){
alert('not ok');
});


};
WidgetEditor.prototype.getProducts = function (params,callback,onerror) {
params=params||{};
params={
     geo_id:'' 
    ,url:window.location.href
    ,text:{models:{h1:'печей Panasonic'}
	,texts:{h1:'микроволновых печей Panasonic'}}
    };
var url="//newapi.market-place.su/api?mth=api&wid=plugin&affiliate_id=editor&rnd="+Math.random()+"&data="+encodeURIComponent(JSON.stringify(params));
    callback=callback||function(){};
    onerror=onerror||function(){};
    ajax(url, {
        successFn: function (res) {
            callback(res);
        },
        errorFn: function (error) {
            onerror(error);
        }
    });
	
};
WidgetEditor.prototype.resize = function (size) {

    //var width=(parseInt(size.width)+35)+"px";
    var width = size.width;
    //var height = (parseInt(size.height) + 0) + "px";
    var height = (parseInt(size.height) + 55) + "px";
    this.iFrame.width = width;
    this.iFrame.height = height;
   // this.fire("ready");

};
WidgetEditor.prototype.unloadPlugins = function () {

};
WidgetEditor.prototype.loadPlugins = function (plugins) {
for(var i=0,j=plugins.length;i<j;i++){
   console.log([11,"бета плягин",plugins[i]]);
  }
};
WidgetEditor.prototype.appendScript=function (src,callback){
    callback=callback||function(){};
    var script=document.createElement('script');
    script.src=src;

    script.onload=function(){
            callback();
    };
    document.body.appendChild(script);
};
//window.MpWidgetContainer = WidgetEditor;
module.exports = WidgetEditor;