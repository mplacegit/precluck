(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);throw new Error("Cannot find module '"+o+"'")}var f=n[o]={exports:{}};t[o][0].call(f.exports,function(e){var n=t[o][1][e];return s(n?n:e)},f,f.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
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
},{"./httpclient":2,"./iFrameBridge":3}],2:[function(require,module,exports){
'use strict';
var  Httpclient=
{
    ajax: function (src, config) 
	{
        var linksrc=src;
	    config = config ||{};
    	var errorFn= config.errorFn  || function(){};
		var successFn = config.successFn || function(){};
	
		var type= config.type || "GET";
		var data = config.data || {};
        var serialized_Data = JSON.stringify(data);

		type = type.toUpperCase();
        if (window.XMLHttpRequest) 
		{
            var xhttp = new XMLHttpRequest();
        }
        else 
		{
            var xhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
		
        if (type == "GET") 
		{
             serialized_Data = null;
			if(linksrc.indexOf('?')<0){
				linksrc+="?1=1";
			}
			for(var i in data)
			{
				if(data.hasOwnProperty(i)){
					linksrc+="&"+i+"="+data[i];
				}
			}
        }
		xhttp.open(type, linksrc, true);
		xhttp.onreadystatechange = function () 
		{
            if (xhttp.readyState == 4)
			{
              if (xhttp.status == 200) 
			    {
                  successFn({response:xhttp.responseText});
                }
			else
				{
				    errorFn({status:xhttp.status});
				}
            }
			else
			{
			 errorFn({status:xhttp.readyState});
			}
        };
	     xhttp.onreadystatechange = function () 
		 {
          if (xhttp.readyState == 4)
		  {
				if (xhttp.status == 200)
				{
                 successFn(xhttp.responseText);
                }
				else
				{
				 errorFn({status:xhttp.status});
		        }
		  }
         };
        try 
		{
            xhttp.withCredentials = config.withCredentials||false;
			
			xhttp.send(serialized_Data);
        } catch (err){} 
    }
};
module.exports = Httpclient;
},{}],3:[function(require,module,exports){
/**
 * Created by admin on 16.02.17.
 */
'use strict';
function makeBridge(index){
   var index=index||getUniqueIndex();
    if(typeof  window.MpFrameBridges=="undefined") {
        window.MpFrameBridges={};
    };
    if(typeof  window.MpFrameBridges[index]!="undefined") {
        return  window.MpFrameBridges[index];
    }else {
        window.MpFrameBridges[index]=new Bridge(index);
        return window.MpFrameBridges[index];
    }

}
function callAction(name,data,window) {
    // посылает сообщение для указанного window.
    // action содержит в себе имя события и данные для развертывания
    window.postMessage({name:name,data:data,bridgeAction:true},'*');
}
function getUniqueIndex(){
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
        return v.toString(16);
    });
}
function Bridge(index){

    this.index=index||getUniqueIndex();


var self=this;

    var actions={
        "default":function(){
            // console.log(actions,this,self)
        }
    };

    this.execAction=function(name,data){
        var action=actions[name]||actions['default']||function(){};
            action.call(this,data);
    };

    this.addAction=function(name,dispatcher){
        actions[name]=dispatcher;
    };
    this.showActions=function(){console.log(actions)};



}
window.makeBridge=makeBridge;
window.mp_bridge_listener=function(event){
   
    if(typeof  event.data=="object") {
        if(typeof event.data.bridgeAction!="undefined"&& (event.data.bridgeAction==true)) {
            //broadcast
		console.log(["событие",event.data]);
            if(event.data.data.index=="broadcast"&&typeof window.MpFrameBridges!="undefined") {

 
                for(var i in window.MpFrameBridges)
                {

                    if(window.MpFrameBridges.hasOwnProperty(i)){
                        window.MpFrameBridges[i].execAction(event.data.name,event.data.data);
                    }
                }
            }
			
          
            makeBridge(event.data.data.index).execAction(event.data.name,event.data.data);

        }
    }

};
if(typeof window.MpBridgeListenerAttached=="undefined"){
    if (window.addEventListener) {
        window.addEventListener("message",mp_bridge_listener);
    } else {
        // IE8
        window.attachEvent("onmessage",  mp_bridge_listener);
    }
    window.MpBridgeListenerAttached=true;
}

module.exports ={Bridge:makeBridge,callAction:callAction};
},{}],4:[function(require,module,exports){
'use strict';
window.WidgetEditor = require("./../models/WidgetEditor");

},{"./../models/WidgetEditor":1}]},{},[4])