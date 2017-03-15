//     COJM Courier Online Operations Management
//     cojm1.3.js - Main Javascript File
//     Copyright (C) 2017 S.Young cojm.co.uk
//
//    This program is free software: you can redistribute it and/or modify
//    it under the terms of the GNU Affero General Public License as
//    published by the Free Software Foundation, either version 3 of the
//    License, or (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU Affero General Public License for more details.
//
//    You should have received a copy of the GNU Affero General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.

// Contains JQuery + loads of plugins






// jquery - v 1.7.1
// jquery ui 1.8.16
// jQuery UI Tabs 1.8.18
// jQuery-Plugin "daterangepicker.jQuery.js" 1050
// date.js  Version: 1.0 Alpha-1 1455
// zebra dialogue function v1.3.12
// http://ryanday.org/konami   * Dual licensed under the MIT and GPL licenses.  1840

// + other stuff

// + site wide every page js


(function(a,b){function cy(a){return f.isWindow(a)?a:a.nodeType===9?a.defaultView||a.parentWindow:!1}function cv(a){if(!ck[a]){var b=c.body,d=f("<"+a+">").appendTo(b),e=d.css("display");d.remove();if(e==="none"||e===""){cl||(cl=c.createElement("iframe"),cl.frameBorder=cl.width=cl.height=0),b.appendChild(cl);if(!cm||!cl.createElement)cm=(cl.contentWindow||cl.contentDocument).document,cm.write((c.compatMode==="CSS1Compat"?"<!doctype html>":"")+"<html><body>"),cm.close();d=cm.createElement(a),cm.body.appendChild(d),e=f.css(d,"display"),b.removeChild(cl)}ck[a]=e}return ck[a]}function cu(a,b){var c={};f.each(cq.concat.apply([],cq.slice(0,b)),function(){c[this]=a});return c}function ct(){cr=b}function cs(){setTimeout(ct,0);return cr=f.now()}function cj(){try{return new a.ActiveXObject("Microsoft.XMLHTTP")}catch(b){}}function ci(){try{return new a.XMLHttpRequest}catch(b){}}function cc(a,c){a.dataFilter&&(c=a.dataFilter(c,a.dataType));var d=a.dataTypes,e={},g,h,i=d.length,j,k=d[0],l,m,n,o,p;for(g=1;g<i;g++){if(g===1)for(h in a.converters)typeof h=="string"&&(e[h.toLowerCase()]=a.converters[h]);l=k,k=d[g];if(k==="*")k=l;else if(l!=="*"&&l!==k){m=l+" "+k,n=e[m]||e["* "+k];if(!n){p=b;for(o in e){j=o.split(" ");if(j[0]===l||j[0]==="*"){p=e[j[1]+" "+k];if(p){o=e[o],o===!0?n=p:p===!0&&(n=o);break}}}}!n&&!p&&f.error("No conversion from "+m.replace(" "," to ")),n!==!0&&(c=n?n(c):p(o(c)))}}return c}function cb(a,c,d){var e=a.contents,f=a.dataTypes,g=a.responseFields,h,i,j,k;for(i in g)i in d&&(c[g[i]]=d[i]);while(f[0]==="*")f.shift(),h===b&&(h=a.mimeType||c.getResponseHeader("content-type"));if(h)for(i in e)if(e[i]&&e[i].test(h)){f.unshift(i);break}if(f[0]in d)j=f[0];else{for(i in d){if(!f[0]||a.converters[i+" "+f[0]]){j=i;break}k||(k=i)}j=j||k}if(j){j!==f[0]&&f.unshift(j);return d[j]}}function ca(a,b,c,d){if(f.isArray(b))f.each(b,function(b,e){c||bE.test(a)?d(a,e):ca(a+"["+(typeof e=="object"||f.isArray(e)?b:"")+"]",e,c,d)});else if(!c&&b!=null&&typeof b=="object")for(var e in b)ca(a+"["+e+"]",b[e],c,d);else d(a,b)}function b_(a,c){var d,e,g=f.ajaxSettings.flatOptions||{};for(d in c)c[d]!==b&&((g[d]?a:e||(e={}))[d]=c[d]);e&&f.extend(!0,a,e)}function b$(a,c,d,e,f,g){f=f||c.dataTypes[0],g=g||{},g[f]=!0;var h=a[f],i=0,j=h?h.length:0,k=a===bT,l;for(;i<j&&(k||!l);i++)l=h[i](c,d,e),typeof l=="string"&&(!k||g[l]?l=b:(c.dataTypes.unshift(l),l=b$(a,c,d,e,l,g)));(k||!l)&&!g["*"]&&(l=b$(a,c,d,e,"*",g));return l}function bZ(a){return function(b,c){typeof b!="string"&&(c=b,b="*");if(f.isFunction(c)){var d=b.toLowerCase().split(bP),e=0,g=d.length,h,i,j;for(;e<g;e++)h=d[e],j=/^\+/.test(h),j&&(h=h.substr(1)||"*"),i=a[h]=a[h]||[],i[j?"unshift":"push"](c)}}}function bC(a,b,c){var d=b==="width"?a.offsetWidth:a.offsetHeight,e=b==="width"?bx:by,g=0,h=e.length;if(d>0){if(c!=="border")for(;g<h;g++)c||(d-=parseFloat(f.css(a,"padding"+e[g]))||0),c==="margin"?d+=parseFloat(f.css(a,c+e[g]))||0:d-=parseFloat(f.css(a,"border"+e[g]+"Width"))||0;return d+"px"}d=bz(a,b,b);if(d<0||d==null)d=a.style[b]||0;d=parseFloat(d)||0;if(c)for(;g<h;g++)d+=parseFloat(f.css(a,"padding"+e[g]))||0,c!=="padding"&&(d+=parseFloat(f.css(a,"border"+e[g]+"Width"))||0),c==="margin"&&(d+=parseFloat(f.css(a,c+e[g]))||0);return d+"px"}function bp(a,b){b.src?f.ajax({url:b.src,async:!1,dataType:"script"}):f.globalEval((b.text||b.textContent||b.innerHTML||"").replace(bf,"/*$0*/")),b.parentNode&&b.parentNode.removeChild(b)}function bo(a){var b=c.createElement("div");bh.appendChild(b),b.innerHTML=a.outerHTML;return b.firstChild}function bn(a){var b=(a.nodeName||"").toLowerCase();b==="input"?bm(a):b!=="script"&&typeof a.getElementsByTagName!="undefined"&&f.grep(a.getElementsByTagName("input"),bm)}function bm(a){if(a.type==="checkbox"||a.type==="radio")a.defaultChecked=a.checked}function bl(a){return typeof a.getElementsByTagName!="undefined"?a.getElementsByTagName("*"):typeof a.querySelectorAll!="undefined"?a.querySelectorAll("*"):[]}function bk(a,b){var c;if(b.nodeType===1){b.clearAttributes&&b.clearAttributes(),b.mergeAttributes&&b.mergeAttributes(a),c=b.nodeName.toLowerCase();if(c==="object")b.outerHTML=a.outerHTML;else if(c!=="input"||a.type!=="checkbox"&&a.type!=="radio"){if(c==="option")b.selected=a.defaultSelected;else if(c==="input"||c==="textarea")b.defaultValue=a.defaultValue}else a.checked&&(b.defaultChecked=b.checked=a.checked),b.value!==a.value&&(b.value=a.value);b.removeAttribute(f.expando)}}function bj(a,b){if(b.nodeType===1&&!!f.hasData(a)){var c,d,e,g=f._data(a),h=f._data(b,g),i=g.events;if(i){delete h.handle,h.events={};for(c in i)for(d=0,e=i[c].length;d<e;d++)f.event.add(b,c+(i[c][d].namespace?".":"")+i[c][d].namespace,i[c][d],i[c][d].data)}h.data&&(h.data=f.extend({},h.data))}}function bi(a,b){return f.nodeName(a,"table")?a.getElementsByTagName("tbody")[0]||a.appendChild(a.ownerDocument.createElement("tbody")):a}function U(a){var b=V.split("|"),c=a.createDocumentFragment();if(c.createElement)while(b.length)c.createElement(b.pop());return c}function T(a,b,c){b=b||0;if(f.isFunction(b))return f.grep(a,function(a,d){var e=!!b.call(a,d,a);return e===c});if(b.nodeType)return f.grep(a,function(a,d){return a===b===c});if(typeof b=="string"){var d=f.grep(a,function(a){return a.nodeType===1});if(O.test(b))return f.filter(b,d,!c);b=f.filter(b,d)}return f.grep(a,function(a,d){return f.inArray(a,b)>=0===c})}function S(a){return!a||!a.parentNode||a.parentNode.nodeType===11}function K(){return!0}function J(){return!1}function n(a,b,c){var d=b+"defer",e=b+"queue",g=b+"mark",h=f._data(a,d);h&&(c==="queue"||!f._data(a,e))&&(c==="mark"||!f._data(a,g))&&setTimeout(function(){!f._data(a,e)&&!f._data(a,g)&&(f.removeData(a,d,!0),h.fire())},0)}function m(a){for(var b in a){if(b==="data"&&f.isEmptyObject(a[b]))continue;if(b!=="toJSON")return!1}return!0}function l(a,c,d){if(d===b&&a.nodeType===1){var e="data-"+c.replace(k,"-$1").toLowerCase();d=a.getAttribute(e);if(typeof d=="string"){try{d=d==="true"?!0:d==="false"?!1:d==="null"?null:f.isNumeric(d)?parseFloat(d):j.test(d)?f.parseJSON(d):d}catch(g){}f.data(a,c,d)}else d=b}return d}function h(a){var b=g[a]={},c,d;a=a.split(/\s+/);for(c=0,d=a.length;c<d;c++)b[a[c]]=!0;return b}var c=a.document,d=a.navigator,e=a.location,f=function(){function J(){if(!e.isReady){try{c.documentElement.doScroll("left")}catch(a){setTimeout(J,1);return}e.ready()}}var e=function(a,b){return new e.fn.init(a,b,h)},f=a.jQuery,g=a.$,h,i=/^(?:[^#<]*(<[\w\W]+>)[^>]*$|#([\w\-]*)$)/,j=/\S/,k=/^\s+/,l=/\s+$/,m=/^<(\w+)\s*\/?>(?:<\/\1>)?$/,n=/^[\],:{}\s]*$/,o=/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,p=/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,q=/(?:^|:|,)(?:\s*\[)+/g,r=/(webkit)[ \/]([\w.]+)/,s=/(opera)(?:.*version)?[ \/]([\w.]+)/,t=/(msie) ([\w.]+)/,u=/(mozilla)(?:.*? rv:([\w.]+))?/,v=/-([a-z]|[0-9])/ig,w=/^-ms-/,x=function(a,b){return(b+"").toUpperCase()},y=d.userAgent,z,A,B,C=Object.prototype.toString,D=Object.prototype.hasOwnProperty,E=Array.prototype.push,F=Array.prototype.slice,G=String.prototype.trim,H=Array.prototype.indexOf,I={};e.fn=e.prototype={constructor:e,init:function(a,d,f){var g,h,j,k;if(!a)return this;if(a.nodeType){this.context=this[0]=a,this.length=1;return this}if(a==="body"&&!d&&c.body){this.context=c,this[0]=c.body,this.selector=a,this.length=1;return this}if(typeof a=="string"){a.charAt(0)!=="<"||a.charAt(a.length-1)!==">"||a.length<3?g=i.exec(a):g=[null,a,null];if(g&&(g[1]||!d)){if(g[1]){d=d instanceof e?d[0]:d,k=d?d.ownerDocument||d:c,j=m.exec(a),j?e.isPlainObject(d)?(a=[c.createElement(j[1])],e.fn.attr.call(a,d,!0)):a=[k.createElement(j[1])]:(j=e.buildFragment([g[1]],[k]),a=(j.cacheable?e.clone(j.fragment):j.fragment).childNodes);return e.merge(this,a)}h=c.getElementById(g[2]);if(h&&h.parentNode){if(h.id!==g[2])return f.find(a);this.length=1,this[0]=h}this.context=c,this.selector=a;return this}return!d||d.jquery?(d||f).find(a):this.constructor(d).find(a)}if(e.isFunction(a))return f.ready(a);a.selector!==b&&(this.selector=a.selector,this.context=a.context);return e.makeArray(a,this)},selector:"",jquery:"1.7.1",length:0,size:function(){return this.length},toArray:function(){return F.call(this,0)},get:function(a){return a==null?this.toArray():a<0?this[this.length+a]:this[a]},pushStack:function(a,b,c){var d=this.constructor();e.isArray(a)?E.apply(d,a):e.merge(d,a),d.prevObject=this,d.context=this.context,b==="find"?d.selector=this.selector+(this.selector?" ":"")+c:b&&(d.selector=this.selector+"."+b+"("+c+")");return d},each:function(a,b){return e.each(this,a,b)},ready:function(a){e.bindReady(),A.add(a);return this},eq:function(a){a=+a;return a===-1?this.slice(a):this.slice(a,a+1)},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},slice:function(){return this.pushStack(F.apply(this,arguments),"slice",F.call(arguments).join(","))},map:function(a){return this.pushStack(e.map(this,function(b,c){return a.call(b,c,b)}))},end:function(){return this.prevObject||this.constructor(null)},push:E,sort:[].sort,splice:[].splice},e.fn.init.prototype=e.fn,e.extend=e.fn.extend=function(){var a,c,d,f,g,h,i=arguments[0]||{},j=1,k=arguments.length,l=!1;typeof i=="boolean"&&(l=i,i=arguments[1]||{},j=2),typeof i!="object"&&!e.isFunction(i)&&(i={}),k===j&&(i=this,--j);for(;j<k;j++)if((a=arguments[j])!=null)for(c in a){d=i[c],f=a[c];if(i===f)continue;l&&f&&(e.isPlainObject(f)||(g=e.isArray(f)))?(g?(g=!1,h=d&&e.isArray(d)?d:[]):h=d&&e.isPlainObject(d)?d:{},i[c]=e.extend(l,h,f)):f!==b&&(i[c]=f)}return i},e.extend({noConflict:function(b){a.$===e&&(a.$=g),b&&a.jQuery===e&&(a.jQuery=f);return e},isReady:!1,readyWait:1,holdReady:function(a){a?e.readyWait++:e.ready(!0)},ready:function(a){if(a===!0&&!--e.readyWait||a!==!0&&!e.isReady){if(!c.body)return setTimeout(e.ready,1);e.isReady=!0;if(a!==!0&&--e.readyWait>0)return;A.fireWith(c,[e]),e.fn.trigger&&e(c).trigger("ready").off("ready")}},bindReady:function(){if(!A){A=e.Callbacks("once memory");if(c.readyState==="complete")return setTimeout(e.ready,1);if(c.addEventListener)c.addEventListener("DOMContentLoaded",B,!1),a.addEventListener("load",e.ready,!1);else if(c.attachEvent){c.attachEvent("onreadystatechange",B),a.attachEvent("onload",e.ready);var b=!1;try{b=a.frameElement==null}catch(d){}c.documentElement.doScroll&&b&&J()}}},isFunction:function(a){return e.type(a)==="function"},isArray:Array.isArray||function(a){return e.type(a)==="array"},isWindow:function(a){return a&&typeof a=="object"&&"setInterval"in a},isNumeric:function(a){return!isNaN(parseFloat(a))&&isFinite(a)},type:function(a){return a==null?String(a):I[C.call(a)]||"object"},isPlainObject:function(a){if(!a||e.type(a)!=="object"||a.nodeType||e.isWindow(a))return!1;try{if(a.constructor&&!D.call(a,"constructor")&&!D.call(a.constructor.prototype,"isPrototypeOf"))return!1}catch(c){return!1}var d;for(d in a);return d===b||D.call(a,d)},isEmptyObject:function(a){for(var b in a)return!1;return!0},error:function(a){throw new Error(a)},parseJSON:function(b){if(typeof b!="string"||!b)return null;b=e.trim(b);if(a.JSON&&a.JSON.parse)return a.JSON.parse(b);if(n.test(b.replace(o,"@").replace(p,"]").replace(q,"")))return(new Function("return "+b))();e.error("Invalid JSON: "+b)},parseXML:function(c){var d,f;try{a.DOMParser?(f=new DOMParser,d=f.parseFromString(c,"text/xml")):(d=new ActiveXObject("Microsoft.XMLDOM"),d.async="false",d.loadXML(c))}catch(g){d=b}(!d||!d.documentElement||d.getElementsByTagName("parsererror").length)&&e.error("Invalid XML: "+c);return d},noop:function(){},globalEval:function(b){b&&j.test(b)&&(a.execScript||function(b){a.eval.call(a,b)})(b)},camelCase:function(a){return a.replace(w,"ms-").replace(v,x)},nodeName:function(a,b){return a.nodeName&&a.nodeName.toUpperCase()===b.toUpperCase()},each:function(a,c,d){var f,g=0,h=a.length,i=h===b||e.isFunction(a);if(d){if(i){for(f in a)if(c.apply(a[f],d)===!1)break}else for(;g<h;)if(c.apply(a[g++],d)===!1)break}else if(i){for(f in a)if(c.call(a[f],f,a[f])===!1)break}else for(;g<h;)if(c.call(a[g],g,a[g++])===!1)break;return a},trim:G?function(a){return a==null?"":G.call(a)}:function(a){return a==null?"":(a+"").replace(k,"").replace(l,"")},makeArray:function(a,b){var c=b||[];if(a!=null){var d=e.type(a);a.length==null||d==="string"||d==="function"||d==="regexp"||e.isWindow(a)?E.call(c,a):e.merge(c,a)}return c},inArray:function(a,b,c){var d;if(b){if(H)return H.call(b,a,c);d=b.length,c=c?c<0?Math.max(0,d+c):c:0;for(;c<d;c++)if(c in b&&b[c]===a)return c}return-1},merge:function(a,c){var d=a.length,e=0;if(typeof c.length=="number")for(var f=c.length;e<f;e++)a[d++]=c[e];else while(c[e]!==b)a[d++]=c[e++];a.length=d;return a},grep:function(a,b,c){var d=[],e;c=!!c;for(var f=0,g=a.length;f<g;f++)e=!!b(a[f],f),c!==e&&d.push(a[f]);return d},map:function(a,c,d){var f,g,h=[],i=0,j=a.length,k=a instanceof e||j!==b&&typeof j=="number"&&(j>0&&a[0]&&a[j-1]||j===0||e.isArray(a));if(k)for(;i<j;i++)f=c(a[i],i,d),f!=null&&(h[h.length]=f);else for(g in a)f=c(a[g],g,d),f!=null&&(h[h.length]=f);return h.concat.apply([],h)},guid:1,proxy:function(a,c){if(typeof c=="string"){var d=a[c];c=a,a=d}if(!e.isFunction(a))return b;var f=F.call(arguments,2),g=function(){return a.apply(c,f.concat(F.call(arguments)))};g.guid=a.guid=a.guid||g.guid||e.guid++;return g},access:function(a,c,d,f,g,h){var i=a.length;if(typeof c=="object"){for(var j in c)e.access(a,j,c[j],f,g,d);return a}if(d!==b){f=!h&&f&&e.isFunction(d);for(var k=0;k<i;k++)g(a[k],c,f?d.call(a[k],k,g(a[k],c)):d,h);return a}return i?g(a[0],c):b},now:function(){return(new Date).getTime()},uaMatch:function(a){a=a.toLowerCase();var b=r.exec(a)||s.exec(a)||t.exec(a)||a.indexOf("compatible")<0&&u.exec(a)||[];return{browser:b[1]||"",version:b[2]||"0"}},sub:function(){function a(b,c){return new a.fn.init(b,c)}e.extend(!0,a,this),a.superclass=this,a.fn=a.prototype=this(),a.fn.constructor=a,a.sub=this.sub,a.fn.init=function(d,f){f&&f instanceof e&&!(f instanceof a)&&(f=a(f));return e.fn.init.call(this,d,f,b)},a.fn.init.prototype=a.fn;var b=a(c);return a},browser:{}}),e.each("Boolean Number String Function Array Date RegExp Object".split(" "),function(a,b){I["[object "+b+"]"]=b.toLowerCase()}),z=e.uaMatch(y),z.browser&&(e.browser[z.browser]=!0,e.browser.version=z.version),e.browser.webkit&&(e.browser.safari=!0),j.test("Â ")&&(k=/^[\s\xA0]+/,l=/[\s\xA0]+$/),h=e(c),c.addEventListener?B=function(){c.removeEventListener("DOMContentLoaded",B,!1),e.ready()}:c.attachEvent&&(B=function(){c.readyState==="complete"&&(c.detachEvent("onreadystatechange",B),e.ready())});return e}(),g={};f.Callbacks=function(a){a=a?g[a]||h(a):{};var c=[],d=[],e,i,j,k,l,m=function(b){var d,e,g,h,i;for(d=0,e=b.length;d<e;d++)g=b[d],h=f.type(g),h==="array"?m(g):h==="function"&&(!a.unique||!o.has(g))&&c.push(g)},n=function(b,f){f=f||[],e=!a.memory||[b,f],i=!0,l=j||0,j=0,k=c.length;for(;c&&l<k;l++)if(c[l].apply(b,f)===!1&&a.stopOnFalse){e=!0;break}i=!1,c&&(a.once?e===!0?o.disable():c=[]:d&&d.length&&(e=d.shift(),o.fireWith(e[0],e[1])))},o={add:function(){if(c){var a=c.length;m(arguments),i?k=c.length:e&&e!==!0&&(j=a,n(e[0],e[1]))}return this},remove:function(){if(c){var b=arguments,d=0,e=b.length;for(;d<e;d++)for(var f=0;f<c.length;f++)if(b[d]===c[f]){i&&f<=k&&(k--,f<=l&&l--),c.splice(f--,1);if(a.unique)break}}return this},has:function(a){if(c){var b=0,d=c.length;for(;b<d;b++)if(a===c[b])return!0}return!1},empty:function(){c=[];return this},disable:function(){c=d=e=b;return this},disabled:function(){return!c},lock:function(){d=b,(!e||e===!0)&&o.disable();return this},locked:function(){return!d},fireWith:function(b,c){d&&(i?a.once||d.push([b,c]):(!a.once||!e)&&n(b,c));return this},fire:function(){o.fireWith(this,arguments);return this},fired:function(){return!!e}};return o};var i=[].slice;f.extend({Deferred:function(a){var b=f.Callbacks("once memory"),c=f.Callbacks("once memory"),d=f.Callbacks("memory"),e="pending",g={resolve:b,reject:c,notify:d},h={done:b.add,fail:c.add,progress:d.add,state:function(){return e},isResolved:b.fired,isRejected:c.fired,then:function(a,b,c){i.done(a).fail(b).progress(c);return this},always:function(){i.done.apply(i,arguments).fail.apply(i,arguments);return this},pipe:function(a,b,c){return f.Deferred(function(d){f.each({done:[a,"resolve"],fail:[b,"reject"],progress:[c,"notify"]},function(a,b){var c=b[0],e=b[1],g;f.isFunction(c)?i[a](function(){g=c.apply(this,arguments),g&&f.isFunction(g.promise)?g.promise().then(d.resolve,d.reject,d.notify):d[e+"With"](this===i?d:this,[g])}):i[a](d[e])})}).promise()},promise:function(a){if(a==null)a=h;else for(var b in h)a[b]=h[b];return a}},i=h.promise({}),j;for(j in g)i[j]=g[j].fire,i[j+"With"]=g[j].fireWith;i.done(function(){e="resolved"},c.disable,d.lock).fail(function(){e="rejected"},b.disable,d.lock),a&&a.call(i,i);return i},when:function(a){function m(a){return function(b){e[a]=arguments.length>1?i.call(arguments,0):b,j.notifyWith(k,e)}}function l(a){return function(c){b[a]=arguments.length>1?i.call(arguments,0):c,--g||j.resolveWith(j,b)}}var b=i.call(arguments,0),c=0,d=b.length,e=Array(d),g=d,h=d,j=d<=1&&a&&f.isFunction(a.promise)?a:f.Deferred(),k=j.promise();if(d>1){for(;c<d;c++)b[c]&&b[c].promise&&f.isFunction(b[c].promise)?b[c].promise().then(l(c),j.reject,m(c)):--g;g||j.resolveWith(j,b)}else j!==a&&j.resolveWith(j,d?[a]:[]);return k}}),f.support=function(){var b,d,e,g,h,i,j,k,l,m,n,o,p,q=c.createElement("div"),r=c.documentElement;q.setAttribute("className","t"),q.innerHTML="   <link/><table></table><a href='/a' style='top:1px;float:left;opacity:.55;'>a</a><input type='checkbox'/>",d=q.getElementsByTagName("*"),e=q.getElementsByTagName("a")[0];if(!d||!d.length||!e)return{};g=c.createElement("select"),h=g.appendChild(c.createElement("option")),i=q.getElementsByTagName("input")[0],b={leadingWhitespace:q.firstChild.nodeType===3,tbody:!q.getElementsByTagName("tbody").length,htmlSerialize:!!q.getElementsByTagName("link").length,style:/top/.test(e.getAttribute("style")),hrefNormalized:e.getAttribute("href")==="/a",opacity:/^0.55/.test(e.style.opacity),cssFloat:!!e.style.cssFloat,checkOn:i.value==="on",optSelected:h.selected,getSetAttribute:q.className!=="t",enctype:!!c.createElement("form").enctype,html5Clone:c.createElement("nav").cloneNode(!0).outerHTML!=="<:nav></:nav>",submitBubbles:!0,changeBubbles:!0,focusinBubbles:!1,deleteExpando:!0,noCloneEvent:!0,inlineBlockNeedsLayout:!1,shrinkWrapBlocks:!1,reliableMarginRight:!0},i.checked=!0,b.noCloneChecked=i.cloneNode(!0).checked,g.disabled=!0,b.optDisabled=!h.disabled;try{delete q.test}catch(s){b.deleteExpando=!1}!q.addEventListener&&q.attachEvent&&q.fireEvent&&(q.attachEvent("onclick",function(){b.noCloneEvent=!1}),q.cloneNode(!0).fireEvent("onclick")),i=c.createElement("input"),i.value="t",i.setAttribute("type","radio"),b.radioValue=i.value==="t",i.setAttribute("checked","checked"),q.appendChild(i),k=c.createDocumentFragment(),k.appendChild(q.lastChild),b.checkClone=k.cloneNode(!0).cloneNode(!0).lastChild.checked,b.appendChecked=i.checked,k.removeChild(i),k.appendChild(q),q.innerHTML="",a.getComputedStyle&&(j=c.createElement("div"),j.style.width="0",j.style.marginRight="0",q.style.width="2px",q.appendChild(j),b.reliableMarginRight=(parseInt((a.getComputedStyle(j,null)||{marginRight:0}).marginRight,10)||0)===0);if(q.attachEvent)for(o in{submit:1,change:1,focusin:1})n="on"+o,p=n in q,p||(q.setAttribute(n,"return;"),p=typeof q[n]=="function"),b[o+"Bubbles"]=p;k.removeChild(q),k=g=h=j=q=i=null,f(function(){var a,d,e,g,h,i,j,k,m,n,o,r=c.getElementsByTagName("body")[0];!r||(j=1,k="position:absolute;top:0;left:0;width:1px;height:1px;margin:0;",m="visibility:hidden;border:0;",n="style='"+k+"border:5px solid #000;padding:0;'",o="<div "+n+"><div></div></div>"+"<table "+n+" cellpadding='0' cellspacing='0'>"+"<tr><td></td></tr></table>",a=c.createElement("div"),a.style.cssText=m+"width:0;height:0;position:static;top:0;margin-top:"+j+"px",r.insertBefore(a,r.firstChild),q=c.createElement("div"),a.appendChild(q),q.innerHTML="<table><tr><td style='padding:0;border:0;display:none'></td><td>t</td></tr></table>",l=q.getElementsByTagName("td"),p=l[0].offsetHeight===0,l[0].style.display="",l[1].style.display="none",b.reliableHiddenOffsets=p&&l[0].offsetHeight===0,q.innerHTML="",q.style.width=q.style.paddingLeft="1px",f.boxModel=b.boxModel=q.offsetWidth===2,typeof q.style.zoom!="undefined"&&(q.style.display="inline",q.style.zoom=1,b.inlineBlockNeedsLayout=q.offsetWidth===2,q.style.display="",q.innerHTML="<div style='width:4px;'></div>",b.shrinkWrapBlocks=q.offsetWidth!==2),q.style.cssText=k+m,q.innerHTML=o,d=q.firstChild,e=d.firstChild,h=d.nextSibling.firstChild.firstChild,i={doesNotAddBorder:e.offsetTop!==5,doesAddBorderForTableAndCells:h.offsetTop===5},e.style.position="fixed",e.style.top="20px",i.fixedPosition=e.offsetTop===20||e.offsetTop===15,e.style.position=e.style.top="",d.style.overflow="hidden",d.style.position="relative",i.subtractsBorderForOverflowNotVisible=e.offsetTop===-5,i.doesNotIncludeMarginInBodyOffset=r.offsetTop!==j,r.removeChild(a),q=a=null,f.extend(b,i))});return b}();var j=/^(?:\{.*\}|\[.*\])$/,k=/([A-Z])/g;f.extend({cache:{},uuid:0,expando:"jQuery"+(f.fn.jquery+Math.random()).replace(/\D/g,""),noData:{embed:!0,object:"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",applet:!0},hasData:function(a){a=a.nodeType?f.cache[a[f.expando]]:a[f.expando];return!!a&&!m(a)},data:function(a,c,d,e){if(!!f.acceptData(a)){var g,h,i,j=f.expando,k=typeof c=="string",l=a.nodeType,m=l?f.cache:a,n=l?a[j]:a[j]&&j,o=c==="events";if((!n||!m[n]||!o&&!e&&!m[n].data)&&k&&d===b)return;n||(l?a[j]=n=++f.uuid:n=j),m[n]||(m[n]={},l||(m[n].toJSON=f.noop));if(typeof c=="object"||typeof c=="function")e?m[n]=f.extend(m[n],c):m[n].data=f.extend(m[n].data,c);g=h=m[n],e||(h.data||(h.data={}),h=h.data),d!==b&&(h[f.camelCase(c)]=d);if(o&&!h[c])return g.events;k?(i=h[c],i==null&&(i=h[f.camelCase(c)])):i=h;return i}},removeData:function(a,b,c){if(!!f.acceptData(a)){var d,e,g,h=f.expando,i=a.nodeType,j=i?f.cache:a,k=i?a[h]:h;if(!j[k])return;if(b){d=c?j[k]:j[k].data;if(d){f.isArray(b)||(b in d?b=[b]:(b=f.camelCase(b),b in d?b=[b]:b=b.split(" ")));for(e=0,g=b.length;e<g;e++)delete d[b[e]];if(!(c?m:f.isEmptyObject)(d))return}}if(!c){delete j[k].data;if(!m(j[k]))return}f.support.deleteExpando||!j.setInterval?delete j[k]:j[k]=null,i&&(f.support.deleteExpando?delete a[h]:a.removeAttribute?a.removeAttribute(h):a[h]=null)}},_data:function(a,b,c){return f.data(a,b,c,!0)},acceptData:function(a){if(a.nodeName){var b=f.noData[a.nodeName.toLowerCase()];if(b)return b!==!0&&a.getAttribute("classid")===b}return!0}}),f.fn.extend({data:function(a,c){var d,e,g,h=null;if(typeof a=="undefined"){if(this.length){h=f.data(this[0]);if(this[0].nodeType===1&&!f._data(this[0],"parsedAttrs")){e=this[0].attributes;for(var i=0,j=e.length;i<j;i++)g=e[i].name,g.indexOf("data-")===0&&(g=f.camelCase(g.substring(5)),l(this[0],g,h[g]));f._data(this[0],"parsedAttrs",!0)}}return h}if(typeof a=="object")return this.each(function(){f.data(this,a)});d=a.split("."),d[1]=d[1]?"."+d[1]:"";if(c===b){h=this.triggerHandler("getData"+d[1]+"!",[d[0]]),h===b&&this.length&&(h=f.data(this[0],a),h=l(this[0],a,h));return h===b&&d[1]?this.data(d[0]):h}return this.each(function(){var b=f(this),e=[d[0],c];b.triggerHandler("setData"+d[1]+"!",e),f.data(this,a,c),b.triggerHandler("changeData"+d[1]+"!",e)})},removeData:function(a){return this.each(function(){f.removeData(this,a)})}}),f.extend({_mark:function(a,b){a&&(b=(b||"fx")+"mark",f._data(a,b,(f._data(a,b)||0)+1))},_unmark:function(a,b,c){a!==!0&&(c=b,b=a,a=!1);if(b){c=c||"fx";var d=c+"mark",e=a?0:(f._data(b,d)||1)-1;e?f._data(b,d,e):(f.removeData(b,d,!0),n(b,c,"mark"))}},queue:function(a,b,c){var d;if(a){b=(b||"fx")+"queue",d=f._data(a,b),c&&(!d||f.isArray(c)?d=f._data(a,b,f.makeArray(c)):d.push(c));return d||[]}},dequeue:function(a,b){b=b||"fx";var c=f.queue(a,b),d=c.shift(),e={};d==="inprogress"&&(d=c.shift()),d&&(b==="fx"&&c.unshift("inprogress"),f._data(a,b+".run",e),d.call(a,function(){f.dequeue(a,b)},e)),c.length||(f.removeData(a,b+"queue "+b+".run",!0),n(a,b,"queue"))}}),f.fn.extend({queue:function(a,c){typeof a!="string"&&(c=a,a="fx");if(c===b)return f.queue(this[0],a);return this.each(function(){var b=f.queue(this,a,c);a==="fx"&&b[0]!=="inprogress"&&f.dequeue(this,a)})},dequeue:function(a){return this.each(function(){f.dequeue(this,a)})},delay:function(a,b){a=f.fx?f.fx.speeds[a]||a:a,b=b||"fx";return this.queue(b,function(b,c){var d=setTimeout(b,a);c.stop=function(){clearTimeout(d)}})},clearQueue:function(a){return this.queue(a||"fx",[])},promise:function(a,c){function m(){--h||d.resolveWith(e,[e])}typeof a!="string"&&(c=a,a=b),a=a||"fx";var d=f.Deferred(),e=this,g=e.length,h=1,i=a+"defer",j=a+"queue",k=a+"mark",l;while(g--)if(l=f.data(e[g],i,b,!0)||(f.data(e[g],j,b,!0)||f.data(e[g],k,b,!0))&&f.data(e[g],i,f.Callbacks("once memory"),!0))h++,l.add(m);m();return d.promise()}});var o=/[\n\t\r]/g,p=/\s+/,q=/\r/g,r=/^(?:button|input)$/i,s=/^(?:button|input|object|select|textarea)$/i,t=/^a(?:rea)?$/i,u=/^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i,v=f.support.getSetAttribute,w,x,y;f.fn.extend({attr:function(a,b){return f.access(this,a,b,!0,f.attr)},removeAttr:function(a){return this.each(function(){f.removeAttr(this,a)})},prop:function(a,b){return f.access(this,a,b,!0,f.prop)},removeProp:function(a){a=f.propFix[a]||a;return this.each(function(){try{this[a]=b,delete this[a]}catch(c){}})},addClass:function(a){var b,c,d,e,g,h,i;if(f.isFunction(a))return this.each(function(b){f(this).addClass(a.call(this,b,this.className))});if(a&&typeof a=="string"){b=a.split(p);for(c=0,d=this.length;c<d;c++){e=this[c];if(e.nodeType===1)if(!e.className&&b.length===1)e.className=a;else{g=" "+e.className+" ";for(h=0,i=b.length;h<i;h++)~g.indexOf(" "+b[h]+" ")||(g+=b[h]+" ");e.className=f.trim(g)}}}return this},removeClass:function(a){var c,d,e,g,h,i,j;if(f.isFunction(a))return this.each(function(b){f(this).removeClass(a.call(this,b,this.className))});if(a&&typeof a=="string"||a===b){c=(a||"").split(p);for(d=0,e=this.length;d<e;d++){g=this[d];if(g.nodeType===1&&g.className)if(a){h=(" "+g.className+" ").replace(o," ");for(i=0,j=c.length;i<j;i++)h=h.replace(" "+c[i]+" "," ");g.className=f.trim(h)}else g.className=""}}return this},toggleClass:function(a,b){var c=typeof a,d=typeof b=="boolean";if(f.isFunction(a))return this.each(function(c){f(this).toggleClass(a.call(this,c,this.className,b),b)});return this.each(function(){if(c==="string"){var e,g=0,h=f(this),i=b,j=a.split(p);while(e=j[g++])i=d?i:!h.hasClass(e),h[i?"addClass":"removeClass"](e)}else if(c==="undefined"||c==="boolean")this.className&&f._data(this,"__className__",this.className),this.className=this.className||a===!1?"":f._data(this,"__className__")||""})},hasClass:function(a){var b=" "+a+" ",c=0,d=this.length;for(;c<d;c++)if(this[c].nodeType===1&&(" "+this[c].className+" ").replace(o," ").indexOf(b)>-1)return!0;return!1},val:function(a){var c,d,e,g=this[0];{if(!!arguments.length){e=f.isFunction(a);return this.each(function(d){var g=f(this),h;if(this.nodeType===1){e?h=a.call(this,d,g.val()):h=a,h==null?h="":typeof h=="number"?h+="":f.isArray(h)&&(h=f.map(h,function(a){return a==null?"":a+""})),c=f.valHooks[this.nodeName.toLowerCase()]||f.valHooks[this.type];if(!c||!("set"in c)||c.set(this,h,"value")===b)this.value=h}})}if(g){c=f.valHooks[g.nodeName.toLowerCase()]||f.valHooks[g.type];if(c&&"get"in c&&(d=c.get(g,"value"))!==b)return d;d=g.value;return typeof d=="string"?d.replace(q,""):d==null?"":d}}}}),f.extend({valHooks:{option:{get:function(a){var b=a.attributes.value;return!b||b.specified?a.value:a.text}},select:{get:function(a){var b,c,d,e,g=a.selectedIndex,h=[],i=a.options,j=a.type==="select-one";
if(g<0)return null;c=j?g:0,d=j?g+1:i.length;for(;c<d;c++){e=i[c];if(e.selected&&(f.support.optDisabled?!e.disabled:e.getAttribute("disabled")===null)&&(!e.parentNode.disabled||!f.nodeName(e.parentNode,"optgroup"))){b=f(e).val();if(j)return b;h.push(b)}}if(j&&!h.length&&i.length)return f(i[g]).val();return h},set:function(a,b){var c=f.makeArray(b);f(a).find("option").each(function(){this.selected=f.inArray(f(this).val(),c)>=0}),c.length||(a.selectedIndex=-1);return c}}},attrFn:{val:!0,css:!0,html:!0,text:!0,data:!0,width:!0,height:!0,offset:!0},attr:function(a,c,d,e){var g,h,i,j=a.nodeType;if(!!a&&j!==3&&j!==8&&j!==2){if(e&&c in f.attrFn)return f(a)[c](d);if(typeof a.getAttribute=="undefined")return f.prop(a,c,d);i=j!==1||!f.isXMLDoc(a),i&&(c=c.toLowerCase(),h=f.attrHooks[c]||(u.test(c)?x:w));if(d!==b){if(d===null){f.removeAttr(a,c);return}if(h&&"set"in h&&i&&(g=h.set(a,d,c))!==b)return g;a.setAttribute(c,""+d);return d}if(h&&"get"in h&&i&&(g=h.get(a,c))!==null)return g;g=a.getAttribute(c);return g===null?b:g}},removeAttr:function(a,b){var c,d,e,g,h=0;if(b&&a.nodeType===1){d=b.toLowerCase().split(p),g=d.length;for(;h<g;h++)e=d[h],e&&(c=f.propFix[e]||e,f.attr(a,e,""),a.removeAttribute(v?e:c),u.test(e)&&c in a&&(a[c]=!1))}},attrHooks:{type:{set:function(a,b){if(r.test(a.nodeName)&&a.parentNode)f.error("type property can't be changed");else if(!f.support.radioValue&&b==="radio"&&f.nodeName(a,"input")){var c=a.value;a.setAttribute("type",b),c&&(a.value=c);return b}}},value:{get:function(a,b){if(w&&f.nodeName(a,"button"))return w.get(a,b);return b in a?a.value:null},set:function(a,b,c){if(w&&f.nodeName(a,"button"))return w.set(a,b,c);a.value=b}}},propFix:{tabindex:"tabIndex",readonly:"readOnly","for":"htmlFor","class":"className",maxlength:"maxLength",cellspacing:"cellSpacing",cellpadding:"cellPadding",rowspan:"rowSpan",colspan:"colSpan",usemap:"useMap",frameborder:"frameBorder",contenteditable:"contentEditable"},prop:function(a,c,d){var e,g,h,i=a.nodeType;if(!!a&&i!==3&&i!==8&&i!==2){h=i!==1||!f.isXMLDoc(a),h&&(c=f.propFix[c]||c,g=f.propHooks[c]);return d!==b?g&&"set"in g&&(e=g.set(a,d,c))!==b?e:a[c]=d:g&&"get"in g&&(e=g.get(a,c))!==null?e:a[c]}},propHooks:{tabIndex:{get:function(a){var c=a.getAttributeNode("tabindex");return c&&c.specified?parseInt(c.value,10):s.test(a.nodeName)||t.test(a.nodeName)&&a.href?0:b}}}}),f.attrHooks.tabindex=f.propHooks.tabIndex,x={get:function(a,c){

var d,e=f.prop(a,c);return e===!0||typeof e!="boolean"&&(d=a.getAttributeNode(c))&&d.nodeValue!==!1?c.toLowerCase():b},set:function(a,b,c){var d;b===!1?f.removeAttr(a,c):(d=f.propFix[c]||c,d in a&&(a[d]=!0),a.setAttribute(c,c.toLowerCase()));return c}},v||(y={name:!0,id:!0},w=f.valHooks.button={get:function(a,c){var d;d=a.getAttributeNode(c);return d&&(y[c]?d.nodeValue!=="":d.specified)?d.nodeValue:b},set:function(a,b,d){var e=a.getAttributeNode(d);e||(e=c.createAttribute(d),a.setAttributeNode(e));return e.nodeValue=b+""}},f.attrHooks.tabindex.set=w.set,f.each(["width","height"],function(a,b){f.attrHooks[b]=f.extend(f.attrHooks[b],{set:function(a,c){if(c===""){a.setAttribute(b,"auto");return c}}})}),f.attrHooks.contenteditable={get:w.get,set:function(a,b,c){b===""&&(b="false"),w.set(a,b,c)}}),f.support.hrefNormalized||f.each(["href","src","width","height"],function(a,c){f.attrHooks[c]=f.extend(f.attrHooks[c],{get:function(a){var d=a.getAttribute(c,2);return d===null?b:d}})}),f.support.style||(f.attrHooks.style={get:function(a){return a.style.cssText.toLowerCase()||b},set:function(a,b){return a.style.cssText=""+b}}),f.support.optSelected||(f.propHooks.selected=f.extend(f.propHooks.selected,{get:function(a){var b=a.parentNode;b&&(b.selectedIndex,b.parentNode&&b.parentNode.selectedIndex);return null}})),f.support.enctype||(f.propFix.enctype="encoding"),f.support.checkOn||f.each(["radio","checkbox"],function(){f.valHooks[this]={get:function(a){return a.getAttribute("value")===null?"on":a.value}}}),f.each(["radio","checkbox"],function(){f.valHooks[this]=f.extend(f.valHooks[this],{set:function(a,b){
if(f.isArray(b))return a.checked=f.inArray(f(a).val(),b)>=0}})});var z=/^(?:textarea|input|select)$/i,A=/^([^\.]*)?(?:\.(.+))?$/,B=/\bhover(\.\S+)?\b/,C=/^key/,D=/^(?:mouse|contextmenu)|click/,E=/^(?:focusinfocus|focusoutblur)$/,F=/^(\w*)(?:#([\w\-]+))?(?:\.([\w\-]+))?$/,G=function(a){var b=F.exec(a);b&&(b[1]=(b[1]||"").toLowerCase(),b[3]=b[3]&&new RegExp("(?:^|\\s)"+b[3]+"(?:\\s|$)"));return b},H=function(a,b){var c=a.attributes||{};return(!b[1]||a.nodeName.toLowerCase()===b[1])&&(!b[2]||(c.id||{}).value===b[2])&&(!b[3]||b[3].test((c["class"]||{}).value))},I=function(a){return f.event.special.hover?a:a.replace(B,"mouseenter$1 mouseleave$1")};f.event={add:function(a,c,d,e,g){var h,i,j,k,l,m,n,o,p,q,r,s;if(!(a.nodeType===3||a.nodeType===8||!c||!d||!(h=f._data(a)))){d.handler&&(p=d,d=p.handler),d.guid||(d.guid=f.guid++),j=h.events,j||(h.events=j={}),i=h.handle,i||(h.handle=i=function(a){return typeof f!="undefined"&&(!a||f.event.triggered!==a.type)?f.event.dispatch.apply(i.elem,arguments):b},i.elem=a),c=f.trim(I(c)).split(" ");for(k=0;k<c.length;k++){l=A.exec(c[k])||[],m=l[1],n=(l[2]||"").split(".").sort(),s=f.event.special[m]||{},m=(g?s.delegateType:s.bindType)||m,s=f.event.special[m]||{},o=f.extend({type:m,origType:l[1],data:e,handler:d,guid:d.guid,selector:g,quick:G(g),namespace:n.join(".")},p),r=j[m];if(!r){r=j[m]=[],r.delegateCount=0;if(!s.setup||s.setup.call(a,e,n,i)===!1)a.addEventListener?a.addEventListener(m,i,!1):a.attachEvent&&a.attachEvent("on"+m,i)}s.add&&(s.add.call(a,o),o.handler.guid||(o.handler.guid=d.guid)),g?r.splice(r.delegateCount++,0,o):r.push(o),f.event.global[m]=!0}a=null}},global:{},remove:function(a,b,c,d,e){var g=f.hasData(a)&&f._data(a),h,i,j,k,l,m,n,o,p,q,r,s;if(!!g&&!!(o=g.events)){b=f.trim(I(b||"")).split(" ");for(h=0;h<b.length;h++){i=A.exec(b[h])||[],j=k=i[1],l=i[2];if(!j){for(j in o)f.event.remove(a,j+b[h],c,d,!0);continue}p=f.event.special[j]||{},j=(d?p.delegateType:p.bindType)||j,r=o[j]||[],m=r.length,l=l?new RegExp("(^|\\.)"+l.split(".").sort().join("\\.(?:.*\\.)?")+"(\\.|$)"):null;for(n=0;n<r.length;n++)s=r[n],(e||k===s.origType)&&(!c||c.guid===s.guid)&&(!l||l.test(s.namespace))&&(!d||d===s.selector||d==="**"&&s.selector)&&(r.splice(n--,1),s.selector&&r.delegateCount--,p.remove&&p.remove.call(a,s));r.length===0&&m!==r.length&&((!p.teardown||p.teardown.call(a,l)===!1)&&f.removeEvent(a,j,g.handle),delete o[j])}f.isEmptyObject(o)&&(q=g.handle,q&&(q.elem=null),f.removeData(a,["events","handle"],!0))}},customEvent:{getData:!0,setData:!0,changeData:!0},trigger:function(c,d,e,g){if(!e||e.nodeType!==3&&e.nodeType!==8){var h=c.type||c,i=[],j,k,l,m,n,o,p,q,r,s;if(E.test(h+f.event.triggered))return;h.indexOf("!")>=0&&(h=h.slice(0,-1),k=!0),h.indexOf(".")>=0&&(i=h.split("."),h=i.shift(),i.sort());if((!e||f.event.customEvent[h])&&!f.event.global[h])return;c=typeof c=="object"?c[f.expando]?c:new f.Event(h,c):new f.Event(h),c.type=h,c.isTrigger=!0,c.exclusive=k,c.namespace=i.join("."),c.namespace_re=c.namespace?new RegExp("(^|\\.)"+i.join("\\.(?:.*\\.)?")+"(\\.|$)"):null,o=h.indexOf(":")<0?"on"+h:"";if(!e){j=f.cache;for(l in j)j[l].events&&j[l].events[h]&&f.event.trigger(c,d,j[l].handle.elem,!0);return}c.result=b,c.target||(c.target=e),d=d!=null?f.makeArray(d):[],d.unshift(c),p=f.event.special[h]||{};if(p.trigger&&p.trigger.apply(e,d)===!1)return;r=[[e,p.bindType||h]];if(!g&&!p.noBubble&&!f.isWindow(e)){s=p.delegateType||h,m=E.test(s+h)?e:e.parentNode,n=null;for(;m;m=m.parentNode)r.push([m,s]),n=m;n&&n===e.ownerDocument&&r.push([n.defaultView||n.parentWindow||a,s])}for(l=0;l<r.length&&!c.isPropagationStopped();l++)m=r[l][0],c.type=r[l][1],q=(f._data(m,"events")||{})[c.type]&&f._data(m,"handle"),q&&q.apply(m,d),q=o&&m[o],q&&f.acceptData(m)&&q.apply(m,d)===!1&&c.preventDefault();c.type=h,!g&&!c.isDefaultPrevented()&&(!p._default||p._default.apply(e.ownerDocument,d)===!1)&&(h!=="click"||!f.nodeName(e,"a"))&&f.acceptData(e)&&o&&e[h]&&(h!=="focus"&&h!=="blur"||c.target.offsetWidth!==0)&&!f.isWindow(e)&&(n=e[o],n&&(e[o]=null),f.event.triggered=h,e[h](),f.event.triggered=b,n&&(e[o]=n));return c.result}},dispatch:function(c){c=f.event.fix(c||a.event);var d=(f._data(this,"events")||{})[c.type]||[],e=d.delegateCount,g=[].slice.call(arguments,0),h=!c.exclusive&&!c.namespace,i=[],j,k,l,m,n,o,p,q,r,s,t;g[0]=c,c.delegateTarget=this;if(e&&!c.target.disabled&&(!c.button||c.type!=="click")){m=f(this),m.context=this.ownerDocument||this;for(l=c.target;l!=this;l=l.parentNode||this){o={},q=[],m[0]=l;for(j=0;j<e;j++)r=d[j],s=r.selector,o[s]===b&&(o[s]=r.quick?H(l,r.quick):m.is(s)),o[s]&&q.push(r);q.length&&i.push({elem:l,matches:q})}}d.length>e&&i.push({elem:this,matches:d.slice(e)});for(j=0;j<i.length&&!c.isPropagationStopped();j++){p=i[j],c.currentTarget=p.elem;for(k=0;k<p.matches.length&&!c.isImmediatePropagationStopped();k++){r=p.matches[k];if(h||!c.namespace&&!r.namespace||c.namespace_re&&c.namespace_re.test(r.namespace))c.data=r.data,c.handleObj=r,n=((f.event.special[r.origType]||{}).handle||r.handler).apply(p.elem,g),n!==b&&(c.result=n,n===!1&&(c.preventDefault(),c.stopPropagation()))}}return c.result},props:"attrChange attrName relatedNode srcElement altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(a,b){

a.which==null&&(a.which=b.charCode!=null?b.charCode:b.keyCode);return a}},mouseHooks:{props:"button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(a,d){var e,f,g,h=d.button,i=d.fromElement;a.pageX==null&&d.clientX!=null&&(e=a.target.ownerDocument||c,f=e.documentElement,g=e.body,a.pageX=d.clientX+(f&&f.scrollLeft||g&&g.scrollLeft||0)-(f&&f.clientLeft||g&&g.clientLeft||0),a.pageY=d.clientY+(f&&f.scrollTop||g&&g.scrollTop||0)-(f&&f.clientTop||g&&g.clientTop||0)),!a.relatedTarget&&i&&(a.relatedTarget=i===a.target?d.toElement:i),!a.which&&h!==b&&(a.which=h&1?1:h&2?3:h&4?2:0);return a}},fix:function(a){if(a[f.expando])return a;
var d,e,g=a,h=f.event.fixHooks[a.type]||{},i=h.props?this.props.concat(h.props):this.props;a=f.Event(g);

for(d=i.length;d;)e=i[--d],a[e]=g[e];a.target||(a.target=g.srcElement||c),a.target.nodeType===3&&(a.target=a.target.parentNode),a.metaKey===b&&(a.metaKey=a.ctrlKey);

return h.filter?h.filter(a,g):a},special:{ready:{setup:f.bindReady},load:{noBubble:!0},focus:{delegateType:"focusin"},blur:{delegateType:"focusout"},beforeunload:{setup:function(a,b,c){f.isWindow(this)&&(this.onbeforeunload=c)},teardown:function(a,b){this.onbeforeunload===b&&(this.onbeforeunload=null)}}},simulate:function(a,b,c,d){
var e=f.extend(new f.Event,c,{type:a,isSimulated:!0,originalEvent:{}});
d?f.event.trigger(e,null,b):f.event.dispatch.call(b,e),e.isDefaultPrevented()&&c.preventDefault()}},f.event.handle=f.event.dispatch,f.removeEvent=c.removeEventListener?function(a,b,c){a.removeEventListener&&a.removeEventListener(b,c,!1)}:function(a,b,c){a.detachEvent&&a.detachEvent("on"+b,c)},f.Event=function(a,b){if(!(this instanceof f.Event))return new f.Event(a,b);a&&a.type?(this.originalEvent=a,this.type=a.type,this.isDefaultPrevented=a.defaultPrevented||a.returnValue===!1||a.defaultPrevented &&a.defaultPrevented ()?K:J):this.type=a,b&&f.extend(this,b),this.timeStamp=a&&a.timeStamp||f.now(),this[f.expando]=!0},f.Event.prototype={preventDefault:function(){this.isDefaultPrevented=K;var a=this.originalEvent;!a||(a.preventDefault?a.preventDefault():a.returnValue=!1)},stopPropagation:function(){this.isPropagationStopped=K;var a=this.originalEvent;!a||(a.stopPropagation&&a.stopPropagation(),a.cancelBubble=!0)},stopImmediatePropagation:function(){
this.isImmediatePropagationStopped=K,this.stopPropagation()},isDefaultPrevented:J,isPropagationStopped:J,isImmediatePropagationStopped:J},f.each({mouseenter:"mouseover",mouseleave:"mouseout"},function(a,b){f.event.special[a]={delegateType:b,bindType:b,handle:function(a){var c=this,d=a.relatedTarget,e=a.handleObj,g=e.selector,h;if(!d||d!==c&&!f.contains(c,d))a.type=e.origType,h=e.handler.apply(this,arguments),a.type=b;return h}}}),f.support.submitBubbles||(f.event.special.submit={setup:function(){if(f.nodeName(this,"form"))return!1;f.event.add(this,"click._submit keypress._submit",function(a){var c=a.target,d=f.nodeName(c,"input")||f.nodeName(c,"button")?c.form:b;d&&!d._submit_attached&&(f.event.add(d,"submit._submit",function(a){this.parentNode&&!a.isTrigger&&f.event.simulate("submit",this.parentNode,a,!0)}),d._submit_attached=!0)})},teardown:function(){if(f.nodeName(this,"form"))return!1;f.event.remove(this,"._submit")}}),f.support.changeBubbles||(f.event.special.change={setup:function(){if(z.test(this.nodeName)){

if(this.type==="checkbox"||this.type==="radio")f.event.add(this,"propertychange._change",function(a){a.originalEvent.propertyName==="checked"&&(this._just_changed=!0)}),f.event.add(this,"click._change",function(a){this._just_changed&&!a.isTrigger&&(this._just_changed=!1,f.event.simulate("change",this,a,!0))});return!1}f.event.add(this,"beforeactivate._change",function(a){var b=a.target;z.test(b.nodeName)&&!b._change_attached&&(f.event.add(b,"change._change",function(a){this.parentNode&&!a.isSimulated&&!a.isTrigger&&f.event.simulate("change",this.parentNode,a,!0)}),b._change_attached=!0)})},handle:function(a){var b=a.target;if(this!==b||a.isSimulated||a.isTrigger||b.type!=="radio"&&b.type!=="checkbox")return a.handleObj.handler.apply(this,arguments)},teardown:function(){f.event.remove(this,"._change");return z.test(this.nodeName)}}),f.support.focusinBubbles||f.each({focus:"focusin",blur:"focusout"},function(a,b){var d=0,e=function(a){f.event.simulate(b,a.target,f.event.fix(a),!0)};f.event.special[b]={setup:function(){d++===0&&c.addEventListener(a,e,!0)},teardown:function(){--d===0&&c.removeEventListener(a,e,!0)}}}),f.fn.extend({on:function(a,c,d,e,g){var h,i;if(typeof a=="object"){typeof c!="string"&&(d=c,c=b);for(i in a)this.on(i,c,d,a[i],g);return this}d==null&&e==null?(e=c,d=c=b):e==null&&(typeof c=="string"?(e=d,d=b):(e=d,d=c,c=b));if(e===!1)e=J;else if(!e)return this;g===1&&(h=e,e=function(a){f().off(a);return h.apply(this,arguments)},e.guid=h.guid||(h.guid=f.guid++));return this.each(function(){f.event.add(this,a,e,d,c)})},one:function(a,b,c,d){return this.on.call(this,a,b,c,d,1)},off:function(a,c,d){if(a&&a.preventDefault&&a.handleObj){var e=a.handleObj;f(a.delegateTarget).off(e.namespace?e.type+"."+e.namespace:e.type,e.selector,e.handler);return this}if(typeof a=="object"){for(var g in a)this.off(g,c,a[g]);return this}if(c===!1||typeof c=="function")d=c,c=b;d===!1&&(d=J);return this.each(function(){f.event.remove(this,a,d,c)})},bind:function(a,b,c){return this.on(a,null,b,c)},unbind:function(a,b){return this.off(a,null,b)},live:function(a,b,c){f(this.context).on(a,this.selector,b,c);return this},die:function(a,b){f(this.context).off(a,this.selector||"**",b);return this},delegate:function(a,b,c,d){return this.on(b,a,c,d)},undelegate:function(a,b,c){return arguments.length==1?this.off(a,"**"):this.off(b,a,c)},trigger:function(a,b){return this.each(function(){f.event.trigger(a,b,this)})},triggerHandler:function(a,b){if(this[0])return f.event.trigger(a,b,this[0],!0)},toggle:function(a){var b=arguments,c=a.guid||f.guid++,d=0,e=function(c){var e=(f._data(this,"lastToggle"+a.guid)||0)%d;f._data(this,"lastToggle"+a.guid,e+1),c.preventDefault();return b[e].apply(this,arguments)||!1};e.guid=c;while(d<b.length)b[d++].guid=c;return this.click(e)},hover:function(a,b){return this.mouseenter(a).mouseleave(b||a)}}),f.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(a,b){f.fn[b]=function(a,c){c==null&&(c=a,a=null);return arguments.length>0?this.on(b,null,a,c):this.trigger(b)},f.attrFn&&(f.attrFn[b]=!0),C.test(b)&&(f.event.fixHooks[b]=f.event.keyHooks),D.test(b)&&(f.event.fixHooks[b]=f.event.mouseHooks)}),function(){function x(a,b,c,e,f,g){for(var h=0,i=e.length;h<i;h++){var j=e[h];if(j){var k=!1;j=j[a];while(j){if(j[d]===c){k=e[j.sizset];break}if(j.nodeType===1){g||(j[d]=c,j.sizset=h);if(typeof b!="string"){if(j===b){k=!0;break}}else if(m.filter(b,[j]).length>0){k=j;break}}j=j[a]}e[h]=k}}}function w(a,b,c,e,f,g){for(var h=0,i=e.length;h<i;h++){var j=e[h];if(j){var k=!1;j=j[a];while(j){if(j[d]===c){k=e[j.sizset];break}j.nodeType===1&&!g&&(j[d]=c,j.sizset=h);if(j.nodeName.toLowerCase()===b){k=j;break}j=j[a]}e[h]=k}}}var a=/((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^\[\]]*\]|['"][^'"]*['"]|[^\[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?((?:.|\r|\n)*)/g,d="sizcache"+(Math.random()+"").replace(".",""),e=0,g=Object.prototype.toString,h=!1,i=!0,j=/\\/g,k=/\r\n/g,l=/\W/;[0,0].sort(function(){i=!1;return 0});var m=function(b,d,e,f){e=e||[],d=d||c;var h=d;if(d.nodeType!==1&&d.nodeType!==9)return[];if(!b||typeof b!="string")return e;var i,j,k,l,n,q,r,t,u=!0,v=m.isXML(d),w=[],x=b;do{a.exec(""),i=a.exec(x);if(i){x=i[3],w.push(i[1]);if(i[2]){l=i[3];break}}}while(i);if(w.length>1&&p.exec(b))if(w.length===2&&o.relative[w[0]])j=y(w[0]+w[1],d,f);else{j=o.relative[w[0]]?[d]:m(w.shift(),d);while(w.length)b=w.shift(),o.relative[b]&&(b+=w.shift()),j=y(b,j,f)}else{!f&&w.length>1&&d.nodeType===9&&!v&&o.match.ID.test(w[0])&&!o.match.ID.test(w[w.length-1])&&(n=m.find(w.shift(),d,v),d=n.expr?m.filter(n.expr,n.set)[0]:n.set[0]);

if(d){n=f?{expr:w.pop(),set:s(f)}:m.find(w.pop(),w.length===1&&(w[0]==="~"||w[0]==="+")&&d.parentNode?d.parentNode:d,v),j=n.expr?m.filter(n.expr,n.set):n.set,w.length>0?k=s(j):u=!1;while(w.length)q=w.pop(),r=q,o.relative[q]?r=w.pop():q="",r==null&&(r=d),o.relative[q](k,r,v)}else k=w=[]}k||(k=j),k||m.error(q||b);if(g.call(k)==="[object Array]")if(!u)e.push.apply(e,k);else if(d&&d.nodeType===1)for(t=0;k[t]!=null;t++)k[t]&&(k[t]===!0||k[t].nodeType===1&&m.contains(d,k[t]))&&e.push(j[t]);else for(t=0;k[t]!=null;t++)k[t]&&k[t].nodeType===1&&e.push(j[t]);else s(k,e);l&&(m(l,h,e,f),m.uniqueSort(e));return e};m.uniqueSort=function(a){if(u){h=i,a.sort(u);if(h)for(var b=1;b<a.length;b++)a[b]===a[b-1]&&a.splice(b--,1)}return a},m.matches=function(a,b){return m(a,null,null,b)},m.matchesSelector=function(a,b){return m(b,null,null,[a]).length>0},m.find=function(a,b,c){var d,e,f,g,h,i;if(!a)return[];for(e=0,f=o.order.length;e<f;e++){h=o.order[e];if(g=o.leftMatch[h].exec(a)){i=g[1],g.splice(1,1);if(i.substr(i.length-1)!=="\\"){g[1]=(g[1]||"").replace(j,""),d=o.find[h](g,b,c);if(d!=null){a=a.replace(o.match[h],"");break}}}}d||(d=typeof b.getElementsByTagName!="undefined"?b.getElementsByTagName("*"):[]);return{set:d,expr:a}},m.filter=function(a,c,d,e){var f,g,h,i,j,k,l,n,p,q=a,r=[],s=c,t=c&&c[0]&&m.isXML(c[0]);while(a&&c.length){for(h in o.filter)if((f=o.leftMatch[h].exec(a))!=null&&f[2]){k=o.filter[h],l=f[1],g=!1,f.splice(1,1);if(l.substr(l.length-1)==="\\")continue;s===r&&(r=[]);if(o.preFilter[h]){f=o.preFilter[h](f,s,d,r,e,t);if(!f)g=i=!0;else if(f===!0)continue}if(f)for(n=0;(j=s[n])!=null;n++)j&&(i=k(j,f,n,s),p=e^i,d&&i!=null?p?g=!0:s[n]=!1:p&&(r.push(j),g=!0));if(i!==b){d||(s=r),a=a.replace(o.match[h],"");if(!g)return[];break}}if(a===q)if(g==null)m.error(a);else break;q=a}return s},m.error=function(a){throw new Error("Syntax error, unrecognized expression: "+a)};var n=m.getText=function(a){var b,c,d=a.nodeType,e="";if(d){if(d===1||d===9){if(typeof a.textContent=="string")return a.textContent;if(typeof a.innerText=="string")return a.innerText.replace(k,"");for(a=a.firstChild;a;a=a.nextSibling)e+=n(a)}else if(d===3||d===4)return a.nodeValue}else for(b=0;c=a[b];b++)c.nodeType!==8&&(e+=n(c));return e},o=m.selectors={order:["ID","NAME","TAG"],match:{ID:/#((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,CLASS:/\.((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,NAME:/\[name=['"]*((?:[\w\u00c0-\uFFFF\-]|\\.)+)['"]*\]/,ATTR:/\[\s*((?:[\w\u00c0-\uFFFF\-]|\\.)+)\s*(?:(\S?=)\s*(?:(['"])(.*?)\3|(#?(?:[\w\u00c0-\uFFFF\-]|\\.)*)|)|)\s*\]/,TAG:/^((?:[\w\u00c0-\uFFFF\*\-]|\\.)+)/,CHILD:/:(only|nth|last|first)-child(?:\(\s*(even|odd|(?:[+\-]?\d+|(?:[+\-]?\d*)?n\s*(?:[+\-]\s*\d+)?))\s*\))?/,POS:/:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^\-]|$)/,PSEUDO:/:((?:[\w\u00c0-\uFFFF\-]|\\.)+)(?:\((['"]?)((?:\([^\)]+\)|[^\(\)]*)+)\2\))?/},leftMatch:{},attrMap:{"class":"className","for":"htmlFor"},attrHandle:{href:function(a){return a.getAttribute("href")},type:function(a){return a.getAttribute("type")}},relative:{"+":function(a,b){var c=typeof b=="string",d=c&&!l.test(b),e=c&&!d;d&&(b=b.toLowerCase());for(var f=0,g=a.length,h;f<g;f++)if(h=a[f]){while((h=h.previousSibling)&&h.nodeType!==1);a[f]=e||h&&h.nodeName.toLowerCase()===b?h||!1:h===b}e&&m.filter(b,a,!0)},">":function(a,b){var c,d=typeof b=="string",e=0,f=a.length;if(d&&!l.test(b)){b=b.toLowerCase();for(;e<f;e++){c=a[e];if(c){var g=c.parentNode;a[e]=g.nodeName.toLowerCase()===b?g:!1}}}else{for(;e<f;e++)c=a[e],c&&(a[e]=d?c.parentNode:c.parentNode===b);d&&m.filter(b,a,!0)}},"":function(a,b,c){var d,f=e++,g=x;typeof b=="string"&&!l.test(b)&&(b=b.toLowerCase(),d=b,g=w),g("parentNode",b,f,a,d,c)},"~":function(a,b,c){var d,f=e++,g=x;typeof b=="string"&&!l.test(b)&&(b=b.toLowerCase(),d=b,g=w),g("previousSibling",b,f,a,d,c)}},find:{ID:function(a,b,c){if(typeof b.getElementById!="undefined"&&!c){var d=b.getElementById(a[1]);return d&&d.parentNode?[d]:[]}},NAME:function(a,b){if(typeof b.getElementsByName!="undefined"){var c=[],d=b.getElementsByName(a[1]);for(var e=0,f=d.length;e<f;e++)d[e].getAttribute("name")===a[1]&&c.push(d[e]);return c.length===0?null:c}},TAG:function(a,b){if(typeof b.getElementsByTagName!="undefined")return b.getElementsByTagName(a[1])}},preFilter:{CLASS:function(a,b,c,d,e,f){a=" "+a[1].replace(j,"")+" ";if(f)return a;for(var g=0,h;(h=b[g])!=null;g++)h&&(e^(h.className&&(" "+h.className+" ").replace(/[\t\n\r]/g," ").indexOf(a)>=0)?c||d.push(h):c&&(b[g]=!1));return!1},ID:function(a){return a[1].replace(j,"")},TAG:function(a,b){return a[1].replace(j,"").toLowerCase()},CHILD:function(a){if(a[1]==="nth"){a[2]||m.error(a[0]),a[2]=a[2].replace(/^\+|\s*/g,"");var b=/(-?)(\d*)(?:n([+\-]?\d*))?/.exec(a[2]==="even"&&"2n"||a[2]==="odd"&&"2n+1"||!/\D/.test(a[2])&&"0n+"+a[2]||a[2]);a[2]=b[1]+(b[2]||1)-0,a[3]=b[3]-0}else a[2]&&m.error(a[0]);a[0]=e++;return a},ATTR:function(a,b,c,d,e,f){var g=a[1]=a[1].replace(j,"");!f&&o.attrMap[g]&&(a[1]=o.attrMap[g]),a[4]=(a[4]||a[5]||"").replace(j,""),a[2]==="~="&&(a[4]=" "+a[4]+" ");return a},PSEUDO:function(b,c,d,e,f){if(b[1]==="not")if((a.exec(b[3])||"").length>1||/^\w/.test(b[3]))b[3]=m(b[3],null,null,c);else{var g=m.filter(b[3],c,d,!0^f);d||e.push.apply(e,g);return!1}else if(o.match.POS.test(b[0])||o.match.CHILD.test(b[0]))return!0;return b},POS:function(a){a.unshift(!0);return a}},filters:{enabled:function(a){return a.disabled===!1&&a.type!=="hidden"},disabled:function(a){return a.disabled===!0},checked:function(a){return a.checked===!0},selected:function(a){a.parentNode&&a.parentNode.selectedIndex;return a.selected===!0},parent:function(a){return!!a.firstChild},empty:function(a){return!a.firstChild},has:function(a,b,c){return!!m(c[3],a).length},header:function(a){return/h\d/i.test(a.nodeName)},text:function(a){var b=a.getAttribute("type"),c=a.type;return a.nodeName.toLowerCase()==="input"&&"text"===c&&(b===c||b===null)},radio:function(a){return a.nodeName.toLowerCase()==="input"&&"radio"===a.type},checkbox:function(a){return a.nodeName.toLowerCase()==="input"&&"checkbox"===a.type},file:function(a){return a.nodeName.toLowerCase()==="input"&&"file"===a.type},password:function(a){return a.nodeName.toLowerCase()==="input"&&"password"===a.type},submit:function(a){var b=a.nodeName.toLowerCase();return(b==="input"||b==="button")&&"submit"===a.type},image:function(a){

return a.nodeName.toLowerCase()==="input"&&"image"===a.type},reset:function(a){var b=a.nodeName.toLowerCase();return(b==="input"||b==="button")&&"reset"===a.type},button:function(a){var b=a.nodeName.toLowerCase();return b==="input"&&"button"===a.type||b==="button"},input:function(a){return/input|select|textarea|button/i.test(a.nodeName)},focus:function(a){return a===a.ownerDocument.activeElement}},setFilters:{first:function(a,b){return b===0},last:function(a,b,c,d){return b===d.length-1},even:function(a,b){return b%2===0},odd:function(a,b){return b%2===1},lt:function(a,b,c){return b<c[3]-0},gt:function(a,b,c){return b>c[3]-0},nth:function(a,b,c){return c[3]-0===b},eq:function(a,b,c){return c[3]-0===b}},filter:{PSEUDO:function(a,b,c,d){var e=b[1],f=o.filters[e];if(f)return f(a,c,b,d);if(e==="contains")return(a.textContent||a.innerText||n([a])||"").indexOf(b[3])>=0;if(e==="not"){var g=b[3];for(var h=0,i=g.length;h<i;h++)if(g[h]===a)return!1;return!0}m.error(e)},CHILD:function(a,b){var c,e,f,g,h,i,j,k=b[1],l=a;switch(k){case"only":case"first":while(l=l.previousSibling)if(l.nodeType===1)return!1;if(k==="first")return!0;l=a;case"last":while(l=l.nextSibling)if(l.nodeType===1)return!1;return!0;case"nth":c=b[2],e=b[3];if(c===1&&e===0)return!0;f=b[0],g=a.parentNode;if(g&&(g[d]!==f||!a.nodeIndex)){i=0;for(l=g.firstChild;l;l=l.nextSibling)l.nodeType===1&&(l.nodeIndex=++i);g[d]=f}j=a.nodeIndex-e;return c===0?j===0:j%c===0&&j/c>=0}},ID:function(a,b){return a.nodeType===1&&a.getAttribute("id")===b},TAG:function(a,b){return b==="*"&&a.nodeType===1||!!a.nodeName&&a.nodeName.toLowerCase()===b},CLASS:function(a,b){return(" "+(a.className||a.getAttribute("class"))+" ").indexOf(b)>-1},ATTR:function(a,b){var c=b[1],d=m.attr?m.attr(a,c):o.attrHandle[c]?o.attrHandle[c](a):a[c]!=null?a[c]:a.getAttribute(c),e=d+"",f=b[2],g=b[4];return d==null?f==="!=":!f&&m.attr?d!=null:f==="="?e===g:f==="*="?e.indexOf(g)>=0:f==="~="?(" "+e+" ").indexOf(g)>=0:g?f==="!="?e!==g:f==="^="?e.indexOf(g)===0:f==="$="?e.substr(e.length-g.length)===g:f==="|="?e===g||e.substr(0,g.length+1)===g+"-":!1:e&&d!==!1},POS:function(a,b,c,d){var e=b[2],f=o.setFilters[e];if(f)return f(a,c,b,d)}}},p=o.match.POS,q=function(a,b){return"\\"+(b-0+1)};for(var r in o.match)o.match[r]=new RegExp(o.match[r].source+/(?![^\[]*\])(?![^\(]*\))/.source),o.leftMatch[r]=new RegExp(/(^(?:.|\r|\n)*?)/.source+o.match[r].source.replace(/\\(\d+)/g,q));var s=function(a,b){a=Array.prototype.slice.call(a,0);if(b){b.push.apply(b,a);return b}return a};try{Array.prototype.slice.call(c.documentElement.childNodes,0)[0].nodeType}catch(t){s=function(a,b){var c=0,d=b||[];if(g.call(a)==="[object Array]")Array.prototype.push.apply(d,a);else if(typeof a.length=="number")for(var e=a.length;c<e;c++)d.push(a[c]);else for(;a[c];c++)d.push(a[c]);return d}}var u,v;c.documentElement.compareDocumentPosition?u=function(a,b){if(a===b){h=!0;return 0}if(!a.compareDocumentPosition||!b.compareDocumentPosition)return a.compareDocumentPosition?-1:1;return a.compareDocumentPosition(b)&4?-1:1}:(u=function(a,b){if(a===b){h=!0;return 0}if(a.sourceIndex&&b.sourceIndex)return a.sourceIndex-b.sourceIndex;var c,d,e=[],f=[],g=a.parentNode,i=b.parentNode,j=g;if(g===i)return v(a,b);if(!g)return-1;if(!i)return 1;while(j)e.unshift(j),j=j.parentNode;j=i;while(j)f.unshift(j),j=j.parentNode;c=e.length,d=f.length;for(var k=0;k<c&&k<d;k++)if(e[k]!==f[k])return v(e[k],f[k]);return k===c?v(a,f[k],-1):v(e[k],b,1)},v=function(a,b,c){if(a===b)return c;var d=a.nextSibling;while(d){if(d===b)return-1;d=d.nextSibling}return 1}),function(){var a=c.createElement("div"),d="script"+(new Date).getTime(),e=c.documentElement;a.innerHTML="<a name='"+d+"'/>",e.insertBefore(a,e.firstChild),c.getElementById(d)&&(o.find.ID=function(a,c,d){if(typeof c.getElementById!="undefined"&&!d){var e=c.getElementById(a[1]);return e?e.id===a[1]||typeof e.getAttributeNode!="undefined"&&e.getAttributeNode("id").nodeValue===a[1]?[e]:b:[]}},o.filter.ID=function(a,b){var c=typeof a.getAttributeNode!="undefined"&&a.getAttributeNode("id");return a.nodeType===1&&c&&c.nodeValue===b}),e.removeChild(a),e=a=null}(),function(){var a=c.createElement("div");a.appendChild(c.createComment("")),a.getElementsByTagName("*").length>0&&(o.find.TAG=function(a,b){var c=b.getElementsByTagName(a[1]);if(a[1]==="*"){var d=[];for(var e=0;c[e];e++)c[e].nodeType===1&&d.push(c[e]);c=d}return c}),a.innerHTML="<a href='#'></a>",a.firstChild&&typeof a.firstChild.getAttribute!="undefined"&&a.firstChild.getAttribute("href")!=="#"&&(o.attrHandle.href=function(a){return a.getAttribute("href",2)}),a=null}(),c.querySelectorAll&&function(){var a=m,b=c.createElement("div"),d="__sizzle__";b.innerHTML="<p class='TEST'></p>";if(!b.querySelectorAll||b.querySelectorAll(".TEST").length!==0){m=function(b,e,f,g){e=e||c;if(!g&&!m.isXML(e)){var h=/^(\w+$)|^\.([\w\-]+$)|^#([\w\-]+$)/.exec(b);if(h&&(e.nodeType===1||e.nodeType===9)){if(h[1])return s(e.getElementsByTagName(b),f);if(h[2]&&o.find.CLASS&&e.getElementsByClassName)return s(e.getElementsByClassName(h[2]),f)}if(e.nodeType===9){if(b==="body"&&e.body)return s([e.body],f);if(h&&h[3]){var i=e.getElementById(h[3]);if(!i||!i.parentNode)return s([],f);if(i.id===h[3])return s([i],f)}try{return s(e.querySelectorAll(b),f)}catch(j){}}else if(e.nodeType===1&&e.nodeName.toLowerCase()!=="object"){var k=e,l=e.getAttribute("id"),n=l||d,p=e.parentNode,q=/^\s*[+~]/.test(b);l?n=n.replace(/'/g,"\\$&"):e.setAttribute("id",n),q&&p&&(e=e.parentNode);try{if(!q||p)return s(e.querySelectorAll("[id='"+n+"'] "+b),f)}catch(r){}finally{l||k.removeAttribute("id")}}}return a(b,e,f,g)};for(var e in a)m[e]=a[e];b=null}}(),function(){var a=c.documentElement,b=a.matchesSelector||a.mozMatchesSelector||a.webkitMatchesSelector||a.msMatchesSelector;if(b){var d=!b.call(c.createElement("div"),"div"),e=!1;try{b.call(c.documentElement,"[test!='']:sizzle")}catch(f){e=!0}m.matchesSelector=function(a,c){c=c.replace(/\=\s*([^'"\]]*)\s*\]/g,"='$1']");if(!m.isXML(a))try{if(e||!o.match.PSEUDO.test(c)&&!/!=/.test(c)){var f=b.call(a,c);if(f||!d||a.document&&a.document.nodeType!==11)return f}}catch(g){}return m(c,null,null,[a]).length>0}}}(),function(){var a=c.createElement("div");a.innerHTML="<div class='test e'></div><div class='test'></div>";if(!!a.getElementsByClassName&&a.getElementsByClassName("e").length!==0){a.lastChild.className="e";if(a.getElementsByClassName("e").length===1)return;o.order.splice(1,0,"CLASS"),o.find.CLASS=function(a,b,c){if(typeof b.getElementsByClassName!="undefined"&&!c)return b.getElementsByClassName(a[1])},a=null}}(),c.documentElement.contains?m.contains=function(a,b){return a!==b&&(a.contains?a.contains(b):!0)}:c.documentElement.compareDocumentPosition?m.contains=function(a,b){return!!(a.compareDocumentPosition(b)&16)}:m.contains=function(){return!1},m.isXML=function(a){var b=(a?a.ownerDocument||a:0).documentElement;
return b?b.nodeName!=="HTML":!1};var y=function(a,b,c){var d,e=[],f="",g=b.nodeType?[b]:b;while(d=o.match.PSEUDO.exec(a))f+=d[0],a=a.replace(o.match.PSEUDO,"");a=o.relative[a]?a+"*":a;for(var h=0,i=g.length;h<i;h++)m(a,g[h],e,c);return m.filter(f,e)};m.attr=f.attr,m.selectors.attrMap={},f.find=m,f.expr=m.selectors,f.expr[":"]=f.expr.filters,f.unique=m.uniqueSort,f.text=m.getText,f.isXMLDoc=m.isXML,f.contains=m.contains}();var L=/Until$/,M=/^(?:parents|prevUntil|prevAll)/,N=/,/,O=/^.[^:#\[\.,]*$/,P=Array.prototype.slice,Q=f.expr.match.POS,R={children:!0,contents:!0,next:!0,prev:!0};f.fn.extend({find:function(a){var b=this,c,d;if(typeof a!="string")return f(a).filter(function(){for(c=0,d=b.length;c<d;c++)if(f.contains(b[c],this))return!0});var e=this.pushStack("","find",a),g,h,i;for(c=0,d=this.length;c<d;c++){g=e.length,f.find(a,this[c],e);if(c>0)for(h=g;h<e.length;h++)for(i=0;i<g;i++)if(e[i]===e[h]){e.splice(h--,1);break}}return e},has:function(a){var b=f(a);return this.filter(function(){for(var a=0,c=b.length;a<c;a++)if(f.contains(this,b[a]))return!0})},not:function(a){return this.pushStack(T(this,a,!1),"not",a)},filter:function(a){return this.pushStack(T(this,a,!0),"filter",a)},is:function(a){return!!a&&(typeof a=="string"?Q.test(a)?f(a,this.context).index(this[0])>=0:f.filter(a,this).length>0:this.filter(a).length>0)},closest:function(a,b){var c=[],d,e,g=this[0];if(f.isArray(a)){var h=1;while(g&&g.ownerDocument&&g!==b){for(d=0;d<a.length;d++)f(g).is(a[d])&&c.push({selector:a[d],elem:g,level:h});g=g.parentNode,h++}return c}var i=Q.test(a)||typeof a!="string"?f(a,b||this.context):0;for(d=0,e=this.length;d<e;d++){g=this[d];while(g){if(i?i.index(g)>-1:f.find.matchesSelector(g,a)){c.push(g);break}g=g.parentNode;if(!g||!g.ownerDocument||g===b||g.nodeType===11)break}}c=c.length>1?f.unique(c):c;return this.pushStack(c,"closest",a)},index:function(a){if(!a)return this[0]&&this[0].parentNode?this.prevAll().length:-1;if(typeof a=="string")return f.inArray(this[0],f(a));return f.inArray(a.jquery?a[0]:a,this)},add:function(a,b){var c=typeof a=="string"?f(a,b):f.makeArray(a&&a.nodeType?[a]:a),d=f.merge(this.get(),c);return this.pushStack(S(c[0])||S(d[0])?d:f.unique(d))},andSelf:function(){return this.add(this.prevObject)}}),f.each({parent:function(a){var b=a.parentNode;return b&&b.nodeType!==11?b:null},parents:function(a){return f.dir(a,"parentNode")},parentsUntil:function(a,b,c){return f.dir(a,"parentNode",c)},next:function(a){return f.nth(a,2,"nextSibling")},prev:function(a){return f.nth(a,2,"previousSibling")},nextAll:function(a){return f.dir(a,"nextSibling")},prevAll:function(a){return f.dir(a,"previousSibling")},nextUntil:function(a,b,c){return f.dir(a,"nextSibling",c)},prevUntil:function(a,b,c){return f.dir(a,"previousSibling",c)},siblings:function(a){return f.sibling(a.parentNode.firstChild,a)},children:function(a){return f.sibling(a.firstChild)},contents:function(a){return f.nodeName(a,"iframe")?a.contentDocument||a.contentWindow.document:f.makeArray(a.childNodes)}},function(a,b){f.fn[a]=function(c,d){var e=f.map(this,b,c);L.test(a)||(d=c),d&&typeof d=="string"&&(e=f.filter(d,e)),e=this.length>1&&!R[a]?f.unique(e):e,(this.length>1||N.test(d))&&M.test(a)&&(e=e.reverse());return this.pushStack(e,a,P.call(arguments).join(","))}}),f.extend({filter:function(a,b,c){c&&(a=":not("+a+")");return b.length===1?f.find.matchesSelector(b[0],a)?[b[0]]:[]:f.find.matches(a,b)},dir:function(a,c,d){var e=[],g=a[c];while(g&&g.nodeType!==9&&(d===b||g.nodeType!==1||!f(g).is(d)))g.nodeType===1&&e.push(g),g=g[c];return e},nth:function(a,b,c,d){b=b||1;var e=0;for(;a;a=a[c])if(a.nodeType===1&&++e===b)break;return a},sibling:function(a,b){var c=[];for(;a;a=a.nextSibling)a.nodeType===1&&a!==b&&c.push(a);return c}});var V="abbr|article|aside|audio|canvas|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",W=/ jQuery\d+="(?:\d+|null)"/g,X=/^\s+/,Y=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/ig,Z=/<([\w:]+)/,$=/<tbody/i,_=/<|&#?\w+;/,ba=/<(?:script|style)/i,bb=/<(?:script|object|embed|option|style)/i,bc=new RegExp("<(?:"+V+")","i"),bd=/checked\s*(?:[^=]|=\s*.checked.)/i,be=/\/(java|ecma)script/i,bf=/^\s*<!(?:\[CDATA\[|\-\-)/,bg={option:[1,"<select multiple='multiple'>","</select>"],legend:[1,"<fieldset>","</fieldset>"],thead:[1,"<table>","</table>"],tr:[2,"<table><tbody>","</tbody></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],col:[2,"<table><tbody></tbody><colgroup>","</colgroup></table>"],area:[1,"<map>","</map>"],_default:[0,"",""]},bh=U(c);bg.optgroup=bg.option,bg.tbody=bg.tfoot=bg.colgroup=bg.caption=bg.thead,bg.th=bg.td,f.support.htmlSerialize||(bg._default=[1,"div<div>","</div>"]),f.fn.extend({text:function(a){if(f.isFunction(a))return this.each(function(b){var c=f(this);c.text(a.call(this,b,c.text()))});if(typeof a!="object"&&a!==b)return this.empty().append((this[0]&&this[0].ownerDocument||c).createTextNode(a));return f.text(this)},wrapAll:function(a){if(f.isFunction(a))return this.each(function(b){f(this).wrapAll(a.call(this,b))});if(this[0]){var b=f(a,this[0].ownerDocument).eq(0).clone(!0);this[0].parentNode&&b.insertBefore(this[0]),b.map(function(){var a=this;while(a.firstChild&&a.firstChild.nodeType===1)a=a.firstChild;return a}).append(this)}return this},wrapInner:function(a){if(f.isFunction(a))return this.each(function(b){f(this).wrapInner(a.call(this,b))});return this.each(function(){var b=f(this),c=b.contents();c.length?c.wrapAll(a):b.append(a)})},wrap:function(a){var b=f.isFunction(a);return this.each(function(c){f(this).wrapAll(b?a.call(this,c):a)})},unwrap:function(){return this.parent().each(function(){f.nodeName(this,"body")||f(this).replaceWith(this.childNodes)}).end()},append:function(){return this.domManip(arguments,!0,function(a){this.nodeType===1&&this.appendChild(a)})},prepend:function(){return this.domManip(arguments,!0,function(a){this.nodeType===1&&this.insertBefore(a,this.firstChild)})},before:function(){if(this[0]&&this[0].parentNode)return this.domManip(arguments,!1,function(a){this.parentNode.insertBefore(a,this)});if(arguments.length){var a=f.clean(arguments);a.push.apply(a,this.toArray());return this.pushStack(a,"before",arguments)}},after:function(){if(this[0]&&this[0].parentNode)return this.domManip(arguments,!1,function(a){this.parentNode.insertBefore(a,this.nextSibling)});if(arguments.length){var a=this.pushStack(this,"after",arguments);a.push.apply(a,f.clean(arguments));return a}},remove:function(a,b){for(var c=0,d;(d=this[c])!=null;c++)if(!a||f.filter(a,[d]).length)!b&&d.nodeType===1&&(f.cleanData(d.getElementsByTagName("*")),f.cleanData([d])),d.parentNode&&d.parentNode.removeChild(d);return this},empty:function()

{for(var a=0,b;(b=this[a])!=null;a++){b.nodeType===1&&f.cleanData(b.getElementsByTagName("*"));while(b.firstChild)b.removeChild(b.firstChild)}return this},clone:function(a,b){a=a==null?!1:a,b=b==null?a:b;return this.map(function(){return f.clone(this,a,b)})},html:function(a){if(a===b)return this[0]&&this[0].nodeType===1?this[0].innerHTML.replace(W,""):null;if(typeof a=="string"&&!ba.test(a)&&(f.support.leadingWhitespace||!X.test(a))&&!bg[(Z.exec(a)||["",""])[1].toLowerCase()]){a=a.replace(Y,"<$1></$2>");try{for(var c=0,d=this.length;c<d;c++)this[c].nodeType===1&&(f.cleanData(this[c].getElementsByTagName("*")),this[c].innerHTML=a)}catch(e){this.empty().append(a)}}else f.isFunction(a)?this.each(function(b){var c=f(this);c.html(a.call(this,b,c.html()))}):this.empty().append(a);return this},replaceWith:function(a){if(this[0]&&this[0].parentNode){if(f.isFunction(a))return this.each(function(b){var c=f(this),d=c.html();c.replaceWith(a.call(this,b,d))});typeof a!="string"&&(a=f(a).detach());return this.each(function(){var b=this.nextSibling,c=this.parentNode;f(this).remove(),b?f(b).before(a):f(c).append(a)})}return this.length?this.pushStack(f(f.isFunction(a)?a():a),"replaceWith",a):this},detach:function(a){return this.remove(a,!0)},domManip:function(a,c,d){var e,g,h,i,j=a[0],k=[];if(!f.support.checkClone&&arguments.length===3&&typeof j=="string"&&bd.test(j))return this.each(function(){f(this).domManip(a,c,d,!0)});if(f.isFunction(j))return this.each(function(e){var g=f(this);a[0]=j.call(this,e,c?g.html():b),g.domManip(a,c,d)});if(this[0]){i=j&&j.parentNode,f.support.parentNode&&i&&i.nodeType===11&&i.childNodes.length===this.length?e={fragment:i}:e=f.buildFragment(a,this,k),h=e.fragment,h.childNodes.length===1?g=h=h.firstChild:g=h.firstChild;if(g){c=c&&f.nodeName(g,"tr");for(var l=0,m=this.length,n=m-1;l<m;l++)d.call(c?bi(this[l],g):this[l],e.cacheable||m>1&&l<n?f.clone(h,!0,!0):h)}k.length&&f.each(k,bp)}return this}}),f.buildFragment=function(a,b,d){var e,g,h,i,j=a[0];b&&b[0]&&(i=b[0].ownerDocument||b[0]),i.createDocumentFragment||(i=c),a.length===1&&typeof j=="string"&&j.length<512&&i===c&&j.charAt(0)==="<"&&!bb.test(j)&&(f.support.checkClone||!bd.test(j))&&(f.support.html5Clone||!bc.test(j))&&(g=!0,h=f.fragments[j],h&&h!==1&&(e=h)),e||(e=i.createDocumentFragment(),f.clean(a,i,e,d)),g&&(f.fragments[j]=h?e:1);return{fragment:e,cacheable:g}},f.fragments={},f.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(a,b){f.fn[a]=function(c){var d=[],e=f(c),g=this.length===1&&this[0].parentNode;if(g&&g.nodeType===11&&g.childNodes.length===1&&e.length===1){e[b](this[0]);return this}for(var h=0,i=e.length;h<i;h++){var j=(h>0?this.clone(!0):this).get();f(e[h])[b](j),d=d.concat(j)}return this.pushStack(d,a,e.selector)}}),f.extend({clone:function(a,b,c){var d,e,g,h=f.support.html5Clone||!bc.test("<"+a.nodeName)?a.cloneNode(!0):bo(a);if((!f.support.noCloneEvent||!f.support.noCloneChecked)&&(a.nodeType===1||a.nodeType===11)&&!f.isXMLDoc(a)){bk(a,h),d=bl(a),e=bl(h);for(g=0;d[g];++g)e[g]&&bk(d[g],e[g])}if(b){bj(a,h);if(c){d=bl(a),e=bl(h);for(g=0;d[g];++g)bj(d[g],e[g])}}d=e=null;return h},clean:function(a,b,d,e){var g;b=b||c,typeof b.createElement=="undefined"&&(b=b.ownerDocument||b[0]&&b[0].ownerDocument||c);var h=[],i;for(var j=0,k;(k=a[j])!=null;j++){typeof k=="number"&&(k+="");if(!k)continue;if(typeof k=="string")if(!_.test(k))k=b.createTextNode(k);else{k=k.replace(Y,"<$1></$2>");var l=(Z.exec(k)||["",""])[1].toLowerCase(),m=bg[l]||bg._default,n=m[0],o=b.createElement("div");b===c?bh.appendChild(o):U(b).appendChild(o),o.innerHTML=m[1]+k+m[2];while(n--)o=o.lastChild;if(!f.support.tbody){var p=$.test(k),q=l==="table"&&!p?o.firstChild&&o.firstChild.childNodes:m[1]==="<table>"&&!p?o.childNodes:[];for(i=q.length-1;i>=0;--i)f.nodeName(q[i],"tbody")&&!q[i].childNodes.length&&q[i].parentNode.removeChild(q[i])}!f.support.leadingWhitespace&&X.test(k)&&o.insertBefore(b.createTextNode(X.exec(k)[0]),o.firstChild),k=o.childNodes}var r;if(!f.support.appendChecked)if(k[0]&&typeof(r=k.length)=="number")for(i=0;i<r;i++)bn(k[i]);else bn(k);k.nodeType?h.push(k):h=f.merge(h,k)}if(d){g=function(a){return!a.type||be.test(a.type)};for(j=0;h[j];j++)if(e&&f.nodeName(h[j],"script")&&(!h[j].type||h[j].type.toLowerCase()==="text/javascript"))e.push(h[j].parentNode?h[j].parentNode.removeChild(h[j]):h[j]);else{if(h[j].nodeType===1){var s=f.grep(h[j].getElementsByTagName("script"),g);h.splice.apply(h,[j+1,0].concat(s))}d.appendChild(h[j])}}return h},cleanData:function(a){var b,c,d=f.cache,e=f.event.special,g=f.support.deleteExpando;for(var h=0,i;(i=a[h])!=null;h++){if(i.nodeName&&f.noData[i.nodeName.toLowerCase()])continue;c=i[f.expando];if(c){b=d[c];if(b&&b.events){for(var j in b.events)e[j]?f.event.remove(i,j):f.removeEvent(i,j,b.handle);b.handle&&(b.handle.elem=null)}g?delete i[f.expando]:i.removeAttribute&&i.removeAttribute(f.expando),delete d[c]}}}});var bq=/alpha\([^)]*\)/i,br=/opacity=([^)]*)/,bs=/([A-Z]|^ms)/g,bt=/^-?\d+(?:px)?$/i,bu=/^-?\d/,bv=/^([\-+])=([\-+.\de]+)/,bw={position:"absolute",visibility:"hidden",display:"block"},bx=["Left","Right"],by=["Top","Bottom"],bz,bA,bB;f.fn.css=function(a,c){if(arguments.length===2&&c===b)return this;return f.access(this,a,c,!0,function(a,c,d){return d!==b?f.style(a,c,d):f.css(a,c)})},f.extend({cssHooks:{opacity:{get:function(a,b){if(b){var c=bz(a,"opacity","opacity");return c===""?"1":c}return a.style.opacity}}},cssNumber:{fillOpacity:!0,fontWeight:!0,lineHeight:!0,opacity:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{"float":f.support.cssFloat?"cssFloat":"styleFloat"},style:function(a,c,d,e){if(!!a&&a.nodeType!==3&&a.nodeType!==8&&!!a.style){var g,h,i=f.camelCase(c),j=a.style,k=f.cssHooks[i];c=f.cssProps[i]||i;if(d===b){if(k&&"get"in k&&(g=k.get(a,!1,e))!==b)return g;return j[c]}h=typeof d,h==="string"&&(g=bv.exec(d))&&(d=+(g[1]+1)*+g[2]+parseFloat(f.css(a,c)),h="number");if(d==null||h==="number"&&isNaN(d))return;h==="number"&&!f.cssNumber[i]&&(d+="px");if(!k||!("set"in k)||(d=k.set(a,d))!==b)try{j[c]=d}catch(l){}}},css:function(a,c,d){var e,g;c=f.camelCase(c),g=f.cssHooks[c],c=f.cssProps[c]||c,c==="cssFloat"&&(c="float");if(g&&"get"in g&&(e=g.get(a,!0,d))!==b)return e;if(bz)return bz(a,c)},swap:function(a,b,c){var d={};for(var e in b)d[e]=a.style[e],a.style[e]=b[e];c.call(a);for(e in b)a.style[e]=d[e]}}),f.curCSS=f.css,f.each(["height","width"],function(a,b){f.cssHooks[b]={get:function(a,c,d){var e;if(c){if(a.offsetWidth!==0)return bC(a,b,d);f.swap(a,bw,function(){e=bC(a,b,d)});return e}},set:function(a,b){if(!bt.test(b))return b;b=parseFloat(b);if(b>=0)return b+"px"}}}),f.support.opacity||(f.cssHooks.opacity={get:function(a,b){return br.test((b&&a.currentStyle?a.currentStyle.filter:a.style.filter)||"")?parseFloat(RegExp.$1)/100+"":b?"1":""},set:function(a,b){var c=a.style,d=a.currentStyle,e=f.isNumeric(b)?"alpha(opacity="+b*100+")":"",g=d&&d.filter||c.filter||"";c.zoom=1;if(b>=1&&f.trim(g.replace(bq,""))===""){c.removeAttribute("filter");if(d&&!d.filter)return}c.filter=bq.test(g)?g.replace(bq,e):g+" "+e}}),f(function(){f.support.reliableMarginRight||(f.cssHooks.marginRight={get:function(a,b){var c;f.swap(a,{display:"inline-block"},function(){b?c=bz(a,"margin-right","marginRight"):c=a.style.marginRight});return c}})}),c.defaultView&&c.defaultView.getComputedStyle&&(bA=function(a,b){var c,d,e;b=b.replace(bs,"-$1").toLowerCase(),(d=a.ownerDocument.defaultView)&&(e=d.getComputedStyle(a,null))&&(c=e.getPropertyValue(b),c===""&&!f.contains(a.ownerDocument.documentElement,a)&&(c=f.style(a,b)));return c}),c.documentElement.currentStyle&&(bB=function(a,b){var c,d,e,f=a.currentStyle&&a.currentStyle[b],g=a.style;f===null&&g&&(e=g[b])&&(f=e),!bt.test(f)&&bu.test(f)&&(c=g.left,d=a.runtimeStyle&&a.runtimeStyle.left,d&&(a.runtimeStyle.left=a.currentStyle.left),g.left=b==="fontSize"?"1em":f||0,f=g.pixelLeft+"px",g.left=c,d&&(a.runtimeStyle.left=d));return f===""?"auto":f}),bz=bA||bB,f.expr&&f.expr.filters&&(f.expr.filters.hidden=function(a){var b=a.offsetWidth,c=a.offsetHeight;return b===0&&c===0||!f.support.reliableHiddenOffsets&&(a.style&&a.style.display||f.css(a,"display"))==="none"},f.expr.filters.visible=function(a){return!f.expr.filters.hidden(a)});var bD=/%20/g,bE=/\[\]$/,bF=/\r?\n/g,bG=/#.*$/,bH=/^(.*?):[ \t]*([^\r\n]*)\r?$/mg,bI=/^(?:color|date|datetime|datetime-local|email|hidden|month|number|password|range|search|tel|text|time|url|week)$/i,bJ=/^(?:about|app|app\-storage|.+\-extension|file|res|widget):$/,bK=/^(?:GET|HEAD)$/,bL=/^\/\//,bM=/\?/,bN=/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,bO=/^(?:select|textarea)/i,bP=/\s+/,bQ=/([?&])_=[^&]*/,bR=/^([\w\+\.\-]+:)(?:\/\/([^\/?#:]*)(?::(\d+))?)?/,bS=f.fn.load,bT={},bU={},bV,bW,bX=["*/"]+["*"];try{bV=e.href}catch(bY){bV=c.createElement("a"),bV.href="",bV=bV.href}bW=bR.exec(bV.toLowerCase())||[],f.fn.extend({load:function(a,c,d){if(typeof a!="string"&&bS)return bS.apply(this,arguments);if(!this.length)return this;var e=a.indexOf(" ");if(e>=0){var g=a.slice(e,a.length);a=a.slice(0,e)}var h="GET";c&&(f.isFunction(c)?(d=c,c=b):typeof c=="object"&&(c=f.param(c,f.ajaxSettings.traditional),h="POST"));var i=this;f.ajax({url:a,type:h,dataType:"html",data:c,complete:function(a,b,c){c=a.responseText,a.isResolved()&&(a.done(function(a){c=a}),i.html(g?f("<div>").append(c.replace(bN,"")).find(g):c)),d&&i.each(d,[c,b,a])}});return this},serialize:function(){return f.param(this.serializeArray())},serializeArray:function(){return this.map(function(){return this.elements?f.makeArray(this.elements):this}).filter(function(){return this.name&&!this.disabled&&(this.checked||bO.test(this.nodeName)||bI.test(this.type))}).map(function(a,b){var c=f(this).val();return c==null?null:f.isArray(c)?f.map(c,function(a,c){return{name:b.name,value:a.replace(bF,"\r\n")}}):{name:b.name,value:c.replace(bF,"\r\n")}}).get()}}),f.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "),function(a,b){f.fn[b]=function(a){return this.on(b,a)}}),f.each(["get","post"],function(a,c){f[c]=function(a,d,e,g){f.isFunction(d)&&(g=g||e,e=d,d=b);return f.ajax({type:c,url:a,data:d,success:e,dataType:g})}}),f.extend({getScript:function(a,c){return f.get(a,b,c,"script")},getJSON:function(a,b,c){return f.get(a,b,c,"json")},ajaxSetup:function(a,b){b?b_(a,f.ajaxSettings):(b=a,a=f.ajaxSettings),b_(a,b);return a},ajaxSettings:{url:bV,isLocal:bJ.test(bW[1]),global:!0,type:"GET",contentType:"application/x-www-form-urlencoded",processData:!0,async:!0,accepts:{xml:"application/xml, text/xml",html:"text/html",text:"text/plain",json:"application/json, text/javascript","*":bX},contents:{xml:/xml/,html:/html/,json:/json/},responseFields:{xml:"responseXML",text:"responseText"},converters:{"* text":a.String,"text html":!0,"text json":f.parseJSON,"text xml":f.parseXML},flatOptions:{context:!0,url:!0}},ajaxPrefilter:bZ(bT),ajaxTransport:bZ(bU),ajax:function(a,c){function w(a,c,l,m){if(s!==2){s=2,q&&clearTimeout(q),p=b,n=m||"",v.readyState=a>0?4:0;var o,r,u,w=c,x=l?cb(d,v,l):b,y,z;if(a>=200&&a<300||a===304){if(d.ifModified){if(y=v.getResponseHeader("Last-Modified"))f.lastModified[k]=y;if(z=v.getResponseHeader("Etag"))f.etag[k]=z}if(a===304)w="notmodified",o=!0;else try{r=cc(d,x),w="success",o=!0}catch(A){w="parsererror",u=A}}else{u=w;if(!w||a)w="error",a<0&&(a=0)}v.status=a,v.statusText=""+(c||w),o?h.resolveWith(e,[r,w,v]):h.rejectWith(e,[v,w,u]),v.statusCode(j),j=b,t&&g.trigger("ajax"+(o?"Success":"Error"),[v,d,o?r:u]),i.fireWith(e,[v,w]),t&&(g.trigger("ajaxComplete",[v,d]),--f.active||f.event.trigger("ajaxStop"))}}typeof a=="object"&&(c=a,a=b),c=c||{};var d=f.ajaxSetup({},c),e=d.context||d,g=e!==d&&(e.nodeType||e instanceof f)?f(e):f.event,h=f.Deferred(),i=f.Callbacks("once memory"),j=d.statusCode||{},k,l={},m={},n,o,p,q,r,s=0,t,u,v={readyState:0,setRequestHeader:function(a,b){if(!s){var c=a.toLowerCase();a=m[c]=m[c]||a,l[a]=b}return this},getAllResponseHeaders:function(){return s===2?n:null},getResponseHeader:function(a){var c;if(s===2){if(!o){o={};while(c=bH.exec(n))o[c[1].toLowerCase()]=c[2]}c=o[a.toLowerCase()]}return c===b?null:c},overrideMimeType:function(a){s||(d.mimeType=a);return this},abort:function(a){a=a||"abort",p&&p.abort(a),w(0,a);return this}};h.promise(v),v.success=v.done,v.error=v.fail,v.complete=i.add,v.statusCode=function(a){if(a){var b;if(s<2)for(b in a)j[b]=[j[b],a[b]];else b=a[v.status],v.then(b,b)}return this},d.url=((a||d.url)+"").replace(bG,"").replace(bL,bW[1]+"//"),d.dataTypes=f.trim(d.dataType||"*").toLowerCase().split(bP),d.crossDomain==null&&(r=bR.exec(d.url.toLowerCase()),d.crossDomain=!(!r||r[1]==bW[1]&&r[2]==bW[2]&&(r[3]||(r[1]==="http:"?80:443))==(bW[3]||(bW[1]==="http:"?80:443)))),d.data&&d.processData&&typeof d.data!="string"&&(d.data=f.param(d.data,d.traditional)),b$(bT,d,c,v);if(s===2)return!1;t=d.global,d.type=d.type.toUpperCase(),d.hasContent=!bK.test(d.type),t&&f.active++===0&&f.event.trigger("ajaxStart");if(!d.hasContent){d.data&&(d.url+=(bM.test(d.url)?"&":"?")+d.data,delete d.data),k=d.url;if(d.cache===!1){var x=f.now(),y=d.url.replace(bQ,"$1_="+x);d.url=y+(y===d.url?(bM.test(d.url)?"&":"?")+"_="+x:"")}}(d.data&&d.hasContent&&d.contentType!==!1||c.contentType)&&v.setRequestHeader("Content-Type",d.contentType),d.ifModified&&(k=k||d.url,f.lastModified[k]&&v.setRequestHeader("If-Modified-Since",f.lastModified[k]),f.etag[k]&&v.setRequestHeader("If-None-Match",f.etag[k])),v.setRequestHeader("Accept",d.dataTypes[0]&&d.accepts[d.dataTypes[0]]?d.accepts[d.dataTypes[0]]+(d.dataTypes[0]!=="*"?", "+bX+"; q=0.01":""):d.accepts["*"]);for(u in d.headers)v.setRequestHeader(u,d.headers[u]);if(d.beforeSend&&(d.beforeSend.call(e,v,d)===!1||s===2)){v.abort();return!1}for(u in{success:1,error:1,complete:1})v[u](d[u]);p=b$(bU,d,c,v);if(!p)w(-1,"No Transport");else{v.readyState=1,t&&g.trigger("ajaxSend",[v,d]),d.async&&d.timeout>0&&(q=setTimeout(function(){v.abort("timeout")},d.timeout));try{s=1,p.send(l,w)}catch(z){if(s<2)w(-1,z);else throw z}}return v},param:function(a,c){var d=[],e=function(a,b){b=f.isFunction(b)?b():b,d[d.length]=encodeURIComponent(a)+"="+encodeURIComponent(b)};c===b&&(c=f.ajaxSettings.traditional);if(f.isArray(a)||a.jquery&&!f.isPlainObject(a))f.each(a,function(){e(this.name,this.value)});else for(var g in a)ca(g,a[g],c,e);return d.join("&").replace(bD,"+")}}),f.extend({active:0,lastModified:{},etag:{}});var cd=f.now(),ce=/(\=)\?(&|$)|\?\?/i;f.ajaxSetup({jsonp:"callback",jsonpCallback:function(){return f.expando+"_"+cd++}}),f.ajaxPrefilter("json jsonp",function(b,c,d){var e=b.contentType==="application/x-www-form-urlencoded"&&typeof b.data=="string";if(b.dataTypes[0]==="jsonp"||b.jsonp!==!1&&(ce.test(b.url)||e&&ce.test(b.data))){var g,h=b.jsonpCallback=f.isFunction(b.jsonpCallback)?b.jsonpCallback():b.jsonpCallback,i=a[h],j=b.url,k=b.data,l="$1"+h+"$2";b.jsonp!==!1&&(j=j.replace(ce,l),b.url===j&&(e&&(k=k.replace(ce,l)),b.data===k&&(j+=(/\?/.test(j)?"&":"?")+b.jsonp+"="+h))),b.url=j,b.data=k,a[h]=function(a){g=[a]},d.always(function(){a[h]=i,g&&f.isFunction(i)&&a[h](g[0])}),b.converters["script json"]=function(){g||f.error(h+" was not called");return g[0]},b.dataTypes[0]="json";return"script"}}),f.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/javascript|ecmascript/},converters:{"text script":function(a){f.globalEval(a);return a}}}),f.ajaxPrefilter("script",function(a){a.cache===b&&(a.cache=!1),a.crossDomain&&(a.type="GET",a.global=!1)}),f.ajaxTransport("script",function(a){if(a.crossDomain){var d,e=c.head||c.getElementsByTagName("head")[0]||c.documentElement;return{send:function(f,g){d=c.createElement("script"),d.async="async",a.scriptCharset&&(d.charset=a.scriptCharset),d.src=a.url,d.onload=d.onreadystatechange=function(a,c){if(c||!d.readyState||/loaded|complete/.test(d.readyState))d.onload=d.onreadystatechange=null,e&&d.parentNode&&e.removeChild(d),d=b,c||g(200,"success")},e.insertBefore(d,e.firstChild)},abort:function(){d&&d.onload(0,1)}}}});var cf=a.ActiveXObject?function(){for(var a in ch)ch[a](0,1)}:!1,cg=0,ch;f.ajaxSettings.xhr=a.ActiveXObject?function(){return!this.isLocal&&ci()||cj()}:ci,function(a){f.extend(f.support,{ajax:!!a,cors:!!a&&"withCredentials"in a})}(f.ajaxSettings.xhr()),f.support.ajax&&f.ajaxTransport(function(c){if(!c.crossDomain||f.support.cors){var d;return{send:function(e,g){var h=c.xhr(),i,j;c.username?h.open(c.type,c.url,c.async,c.username,c.password):h.open(c.type,c.url,c.async);if(c.xhrFields)for(j in c.xhrFields)h[j]=c.xhrFields[j];c.mimeType&&h.overrideMimeType&&h.overrideMimeType(c.mimeType),!c.crossDomain&&!e["X-Requested-With"]&&(e["X-Requested-With"]="XMLHttpRequest");try{for(j in e)h.setRequestHeader(j,e[j])}catch(k){}h.send(c.hasContent&&c.data||null),d=function(a,e){var j,k,l,m,n;try{if(d&&(e||h.readyState===4)){d=b,i&&(h.onreadystatechange=f.noop,cf&&delete ch[i]);if(e)h.readyState!==4&&h.abort();else{j=h.status,l=h.getAllResponseHeaders(),m={},n=h.responseXML,n&&n.documentElement&&(m.xml=n),m.text=h.responseText;try{k=h.statusText}catch(o){k=""}!j&&c.isLocal&&!c.crossDomain?j=m.text?200:404:j===1223&&(j=204)}}}catch(p){e||g(-1,p)}m&&g(j,k,m,l)},!c.async||h.readyState===4?d():(i=++cg,cf&&(ch||(ch={},f(a).unload(cf)),ch[i]=d),h.onreadystatechange=d)},abort:function(){d&&d(0,1)}}}});var ck={},cl,cm,cn=/^(?:toggle|show|hide)$/,co=/^([+\-]=)?([\d+.\-]+)([a-z%]*)$/i,cp,cq=[["height","marginTop","marginBottom","paddingTop","paddingBottom"],["width","marginLeft","marginRight","paddingLeft","paddingRight"],["opacity"]],cr;f.fn.extend({show:function(a,b,c){var d,e;if(a||a===0)return this.animate(cu("show",3),a,b,c);for(var g=0,h=this.length;g<h;g++)d=this[g],d.style&&(e=d.style.display,!f._data(d,"olddisplay")&&e==="none"&&(e=d.style.display=""),e===""&&f.css(d,"display")==="none"&&f._data(d,"olddisplay",cv(d.nodeName)));for(g=0;g<h;g++){d=this[g];if(d.style){e=d.style.display;if(e===""||e==="none")d.style.display=f._data(d,"olddisplay")||""}}return this},hide:function(a,b,c){if(a||a===0)return this.animate(cu("hide",3),a,b,c);var d,e,g=0,h=this.length;for(;g<h;g++)d=this[g],d.style&&(e=f.css(d,"display"),e!=="none"&&!f._data(d,"olddisplay")&&f._data(d,"olddisplay",e));for(g=0;g<h;g++)this[g].style&&(this[g].style.display="none");return this},_toggle:f.fn.toggle,toggle:function(a,b,c){var d=typeof a=="boolean";f.isFunction(a)&&f.isFunction(b)?this._toggle.apply(this,arguments):a==null||d?this.each(function(){var b=d?a:f(this).is(":hidden");f(this)[b?"show":"hide"]()}):this.animate(cu("toggle",3),a,b,c);return this},fadeTo:function(a,b,c,d){return this.filter(":hidden").css("opacity",0).show().end().animate({opacity:b},a,c,d)},animate:function(a,b,c,d){function g(){e.queue===!1&&f._mark(this);var b=f.extend({},e),c=this.nodeType===1,d=c&&f(this).is(":hidden"),g,h,i,j,k,l,m,n,o;b.animatedProperties={};for(i in a){g=f.camelCase(i),i!==g&&(a[g]=a[i],delete a[i]),h=a[g],f.isArray(h)?(b.animatedProperties[g]=h[1],h=a[g]=h[0]):b.animatedProperties[g]=b.specialEasing&&b.specialEasing[g]||b.easing||"swing";if(h==="hide"&&d||h==="show"&&!d)return b.complete.call(this);c&&(g==="height"||g==="width")&&(b.overflow=[this.style.overflow,this.style.overflowX,this.style.overflowY],f.css(this,"display")==="inline"&&f.css(this,"float")==="none"&&(!f.support.inlineBlockNeedsLayout||cv(this.nodeName)==="inline"?this.style.display="inline-block":this.style.zoom=1))}b.overflow!=null&&(this.style.overflow="hidden");for(i in a)j=new f.fx(this,b,i),h=a[i],cn.test(h)?(o=f._data(this,"toggle"+i)||(h==="toggle"?d?"show":"hide":0),o?(f._data(this,"toggle"+i,o==="show"?"hide":"show"),j[o]()):j[h]()):(k=co.exec(h),l=j.cur(),k?(m=parseFloat(k[2]),n=k[3]||(f.cssNumber[i]?"":"px"),n!=="px"&&(f.style(this,i,(m||1)+n),l=(m||1)/j.cur()*l,f.style(this,i,l+n)),k[1]&&(m=(k[1]==="-="?-1:1)*m+l),j.custom(l,m,n)):j.custom(l,h,""));return!0}var e=f.speed(b,c,d);if(f.isEmptyObject(a))return this.each(e.complete,[!1]);a=f.extend({},a);return e.queue===!1?this.each(g):this.queue(e.queue,g)},stop:function(a,c,d){typeof a!="string"&&(d=c,c=a,a=b),c&&a!==!1&&this.queue(a||"fx",[]);return this.each(function(){function h(a,b,c){var e=b[c];f.removeData(a,c,!0),e.stop(d)}var b,c=!1,e=f.timers,g=f._data(this);d||f._unmark(!0,this);if(a==null)for(b in g)g[b]&&g[b].stop&&b.indexOf(".run")===b.length-4&&h(this,g,b);else g[b=a+".run"]&&g[b].stop&&h(this,g,b);for(b=e.length;b--;)e[b].elem===this&&(a==null||e[b].queue===a)&&(d?e[b](!0):e[b].saveState(),c=!0,e.splice(b,1));(!d||!c)&&f.dequeue(this,a)})}}),f.each({slideDown:cu("show",1),slideUp:cu("hide",1),slideToggle:cu("toggle",1),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(a,b){f.fn[a]=function(a,c,d){return this.animate(b,a,c,d)}}),f.extend({speed:function(a,b,c){var d=a&&typeof a=="object"?f.extend({},a):{complete:c||!c&&b||f.isFunction(a)&&a,duration:a,easing:c&&b||b&&!f.isFunction(b)&&b};d.duration=f.fx.off?0:typeof d.duration=="number"?d.duration:d.duration in f.fx.speeds?f.fx.speeds[d.duration]:f.fx.speeds._default;if(d.queue==null||d.queue===!0)d.queue="fx";d.old=d.complete,d.complete=function(a){f.isFunction(d.old)&&d.old.call(this),d.queue?f.dequeue(this,d.queue):a!==!1&&f._unmark(this)};return d},easing:{linear:function(a,b,c,d){return c+d*a},swing:function(a,b,c,d){return(-Math.cos(a*Math.PI)/2+.5)*d+c}},timers:[],fx:function(a,b,c){this.options=b,this.elem=a,this.prop=c,b.orig=b.orig||{}}}),f.fx.prototype={update:function(){this.options.step&&this.options.step.call(this.elem,this.now,this),(f.fx.step[this.prop]||f.fx.step._default)(this)},cur:function(){if(this.elem[this.prop]!=null&&(!this.elem.style||this.elem.style[this.prop]==null))return this.elem[this.prop];var a,b=f.css(this.elem,this.prop);return isNaN(a=parseFloat(b))?!b||b==="auto"?0:b:a},custom:function(a,c,d){function h(a){return e.step(a)}var e=this,g=f.fx;this.startTime=cr||cs(),this.end=c,this.now=this.start=a,this.pos=this.state=0,this.unit=d||this.unit||(f.cssNumber[this.prop]?"":"px"),h.queue=this.options.queue,h.elem=this.elem,h.saveState=function(){e.options.hide&&f._data(e.elem,"fxshow"+e.prop)===b&&f._data(e.elem,"fxshow"+e.prop,e.start)},h()&&f.timers.push(h)&&!cp&&(cp=setInterval(g.tick,g.interval))},show:function(){var a=f._data(this.elem,"fxshow"+this.prop);this.options.orig[this.prop]=a||f.style(this.elem,this.prop),this.options.show=!0,a!==b?this.custom(this.cur(),a):this.custom(this.prop==="width"||this.prop==="height"?1:0,this.cur()),f(this.elem).show()},hide:function(){this.options.orig[this.prop]=f._data(this.elem,"fxshow"+this.prop)||f.style(this.elem,this.prop),this.options.hide=!0,this.custom(this.cur(),0)},step:function(a){var b,c,d,e=cr||cs(),g=!0,h=this.elem,i=this.options;if(a||e>=i.duration+this.startTime){this.now=this.end,this.pos=this.state=1,this.update(),i.animatedProperties[this.prop]=!0;for(b in i.animatedProperties)i.animatedProperties[b]!==!0&&(g=!1);if(g){i.overflow!=null&&!f.support.shrinkWrapBlocks&&f.each(["","X","Y"],function(a,b){h.style["overflow"+b]=i.overflow[a]}),i.hide&&f(h).hide();if(i.hide||i.show)for(b in i.animatedProperties)f.style(h,b,i.orig[b]),f.removeData(h,"fxshow"+b,!0),f.removeData(h,"toggle"+b,!0);d=i.complete,d&&(i.complete=!1,d.call(h))}return!1}i.duration==Infinity?this.now=e:(c=e-this.startTime,this.state=c/i.duration,this.pos=f.easing[i.animatedProperties[this.prop]](this.state,c,0,1,i.duration),this.now=this.start+(this.end-this.start)*this.pos),this.update();return!0}},f.extend(f.fx,{tick:function(){var a,b=f.timers,c=0;for(;c<b.length;c++)a=b[c],!a()&&b[c]===a&&b.splice(c--,1);b.length||f.fx.stop()},interval:13,stop:function(){clearInterval(cp),cp=null},speeds:{slow:600,fast:200,_default:400},step:{opacity:function(a){f.style(a.elem,"opacity",a.now)},_default:function(a){a.elem.style&&a.elem.style[a.prop]!=null?a.elem.style[a.prop]=a.now+a.unit:a.elem[a.prop]=a.now}}}),f.each(["width","height"],function(a,b){f.fx.step[b]=function(a){f.style(a.elem,b,Math.max(0,a.now)+a.unit)}}),f.expr&&f.expr.filters&&(f.expr.filters.animated=function(a){return f.grep(f.timers,function(b){return a===b.elem}).length});var cw=/^t(?:able|d|h)$/i,cx=/^(?:body|html)$/i;"getBoundingClientRect"in c.documentElement?f.fn.offset=function(a){var b=this[0],c;if(a)return this.each(function(b){f.offset.setOffset(this,a,b)});if(!b||!b.ownerDocument)return null;if(b===b.ownerDocument.body)return f.offset.bodyOffset(b);try{c=b.getBoundingClientRect()}catch(d){}var e=b.ownerDocument,g=e.documentElement;if(!c||!f.contains(g,b))return c?{top:c.top,left:c.left}:{top:0,left:0};var h=e.body,i=cy(e),j=g.clientTop||h.clientTop||0,k=g.clientLeft||h.clientLeft||0,l=i.pageYOffset||f.support.boxModel&&g.scrollTop||h.scrollTop,m=i.pageXOffset||f.support.boxModel&&g.scrollLeft||h.scrollLeft,n=c.top+l-j,o=c.left+m-k;return{top:n,left:o}}:f.fn.offset=function(a){var b=this[0];if(a)return this.each(function(b){f.offset.setOffset(this,a,b)});if(!b||!b.ownerDocument)return null;if(b===b.ownerDocument.body)return f.offset.bodyOffset(b);var c,d=b.offsetParent,e=b,g=b.ownerDocument,h=g.documentElement,i=g.body,j=g.defaultView,k=j?j.getComputedStyle(b,null):b.currentStyle,l=b.offsetTop,m=b.offsetLeft;while((b=b.parentNode)&&b!==i&&b!==h){if(f.support.fixedPosition&&k.position==="fixed")break;c=j?j.getComputedStyle(b,null):b.currentStyle,l-=b.scrollTop,m-=b.scrollLeft,b===d&&(l+=b.offsetTop,m+=b.offsetLeft,f.support.doesNotAddBorder&&(!f.support.doesAddBorderForTableAndCells||!cw.test(b.nodeName))&&(l+=parseFloat(c.borderTopWidth)||0,m+=parseFloat(c.borderLeftWidth)||0),e=d,d=b.offsetParent),f.support.subtractsBorderForOverflowNotVisible&&c.overflow!=="visible"&&(l+=parseFloat(c.borderTopWidth)||0,m+=parseFloat(c.borderLeftWidth)||0),k=c}if(k.position==="relative"||k.position==="static")l+=i.offsetTop,m+=i.offsetLeft;f.support.fixedPosition&&k.position==="fixed"&&(l+=Math.max(h.scrollTop,i.scrollTop),m+=Math.max(h.scrollLeft,i.scrollLeft));return{top:l,left:m}},f.offset={bodyOffset:function(a){var b=a.offsetTop,c=a.offsetLeft;f.support.doesNotIncludeMarginInBodyOffset&&(b+=parseFloat(f.css(a,"marginTop"))||0,c+=parseFloat(f.css(a,"marginLeft"))||0);return{top:b,left:c}},setOffset:function(a,b,c){var d=f.css(a,"position");d==="static"&&(a.style.position="relative");var e=f(a),g=e.offset(),h=f.css(a,"top"),i=f.css(a,"left"),j=(d==="absolute"||d==="fixed")&&f.inArray("auto",[h,i])>-1,k={},l={},m,n;j?(l=e.position(),m=l.top,n=l.left):(m=parseFloat(h)||0,n=parseFloat(i)||0),f.isFunction(b)&&(b=b.call(a,c,g)),b.top!=null&&(k.top=b.top-g.top+m),b.left!=null&&(k.left=b.left-g.left+n),"using"in b?b.using.call(a,k):e.css(k)}},f.fn.extend({position:function(){if(!this[0])return null;var a=this[0],b=this.offsetParent(),c=this.offset(),d=cx.test(b[0].nodeName)?{top:0,left:0}:b.offset();c.top-=parseFloat(f.css(a,"marginTop"))||0,c.left-=parseFloat(f.css(a,"marginLeft"))||0,d.top+=parseFloat(f.css(b[0],"borderTopWidth"))||0,d.left+=parseFloat(f.css(b[0],"borderLeftWidth"))||0;return{top:c.top-d.top,left:c.left-d.left}},offsetParent:function(){return this.map(function(){var a=this.offsetParent||c.body;while(a&&!cx.test(a.nodeName)&&f.css(a,"position")==="static")a=a.offsetParent;return a})}}),f.each(["Left","Top"],function(a,c){var d="scroll"+c;f.fn[d]=function(c){var e,g;if(c===b){e=this[0];if(!e)return null;g=cy(e);return g?"pageXOffset"in g?g[a?"pageYOffset":"pageXOffset"]:f.support.boxModel&&g.document.documentElement[d]||g.document.body[d]:e[d]}return this.each(function(){g=cy(this),g?g.scrollTo(a?f(g).scrollLeft():c,a?c:f(g).scrollTop()):this[d]=c})}}),f.each(["Height","Width"],function(a,c){var d=c.toLowerCase();f.fn["inner"+c]=function(){var a=this[0];return a?a.style?parseFloat(f.css(a,d,"padding")):this[d]():null},f.fn["outer"+c]=function(a){var b=this[0];return b?b.style?parseFloat(f.css(b,d,a?"margin":"border")):this[d]():null},f.fn[d]=function(a){var e=this[0];if(!e)return a==null?null:this;if(f.isFunction(a))return this.each(function(b){var c=f(this);c[d](a.call(this,b,c[d]()))});if(f.isWindow(e)){var g=e.document.documentElement["client"+c],h=e.document.body;return e.document.compatMode==="CSS1Compat"&&g||h&&h["client"+c]||g}if(e.nodeType===9)return Math.max(e.documentElement["client"+c],e.body["scroll"+c],e.documentElement["scroll"+c],e.body["offset"+c],e.documentElement["offset"+c]);if(a===b){var i=f.css(e,d),j=parseFloat(i);return f.isNumeric(j)?j:i}return this.css(d,typeof a=="string"?a:a+"px")}}),a.jQuery=a.$=f,typeof define=="function"&&define.amd&&define.amd.jQuery&&define("jquery",[],function(){return f})})(window);(function(c,j){function k(a,b){var d=a.nodeName.toLowerCase();if("area"===d){b=a.parentNode;d=b.name;if(!a.href||!d||b.nodeName.toLowerCase()!=="map")return false;a=c("img[usemap=#"+d+"]")[0];return!!a&&l(a)}return(/input|select|textarea|button|object/.test(d)?!a.disabled:"a"==d?a.href||b:b)&&l(a)}function l(a){return!c(a).parents().andSelf().filter(function(){return c.curCSS(this,"visibility")==="hidden"||c.expr.filters.hidden(this)}).length}c.ui=c.ui||{};if(!c.ui.version){c.extend(c.ui,{version:"1.8.16",keyCode:{ALT:18,BACKSPACE:8,CAPS_LOCK:20,COMMA:188,COMMAND:91,COMMAND_LEFT:91,COMMAND_RIGHT:93,CONTROL:17,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,INSERT:45,LEFT:37,MENU:93,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SHIFT:16,SPACE:32,TAB:9,UP:38,WINDOWS:91}});c.fn.extend({propAttr:c.fn.prop||c.fn.attr,_focus:c.fn.focus,focus:function(a,b){return typeof a==="number"?this.each(function(){var d=this;setTimeout(function(){c(d).focus();b&&b.call(d)},a)}):this._focus.apply(this,arguments)},scrollParent:function(){var a;a=c.browser.msie&&/(static|relative)/.test(this.css("position"))||/absolute/.test(this.css("position"))?this.parents().filter(function(){return/(relative|absolute|fixed)/.test(c.curCSS(this,"position",1))&&/(auto|scroll)/.test(c.curCSS(this,"overflow",1)+c.curCSS(this,"overflow-y",1)+c.curCSS(this,"overflow-x",1))}).eq(0):this.parents().filter(function(){return/(auto|scroll)/.test(c.curCSS(this,"overflow",1)+c.curCSS(this,"overflow-y",1)+c.curCSS(this,"overflow-x",1))}).eq(0);return/fixed/.test(this.css("position"))||!a.length?c(document):a},zIndex:function(a){if(a!==j)return this.css("zIndex",a);if(this.length){a=c(this[0]);for(var b;a.length&&a[0]!==document;){b=a.css("position");if(b==="absolute"||b==="relative"||b==="fixed"){b=parseInt(a.css("zIndex"),10);if(!isNaN(b)&&b!==0)return b}a=a.parent()}}return 0},disableSelection:function(){return this.bind((c.support.selectstart?"selectstart":"mousedown")+".ui-disableSelection",function(a){a.preventDefault()})},enableSelection:function(){return this.unbind(".ui-disableSelection")}});c.each(["Width","Height"],function(a,b){function d(f,g,m,n){c.each(e,function(){g-=parseFloat(c.curCSS(f,"padding"+this,true))||0;if(m)g-=parseFloat(c.curCSS(f,"border"+this+"Width",true))||0;if(n)g-=parseFloat(c.curCSS(f,"margin"+this,true))||0});return g}var e=b==="Width"?["Left","Right"]:["Top","Bottom"],h=b.toLowerCase(),i={innerWidth:c.fn.innerWidth,innerHeight:c.fn.innerHeight,outerWidth:c.fn.outerWidth,outerHeight:c.fn.outerHeight};c.fn["inner"+b]=function(f){if(f===j)return i["inner"+b].call(this);return this.each(function(){c(this).css(h,d(this,f)+"px")})};c.fn["outer"+b]=function(f,g){if(typeof f!=="number")return i["outer"+b].call(this,f);return this.each(function(){c(this).css(h,d(this,f,true,g)+"px")})}});c.extend(c.expr[":"],{data:function(a,b,d){return!!c.data(a,d[3])},focusable:function(a){return k(a,!isNaN(c.attr(a,"tabindex")))},tabbable:function(a){var b=c.attr(a,"tabindex"),d=isNaN(b);return(d||b>=0)&&k(a,!d)}});c(function(){var a=document.body,b=a.appendChild(b=document.createElement("div"));c.extend(b.style,{minHeight:"100px",height:"auto",padding:0,borderWidth:0});c.support.minHeight=b.offsetHeight===100;c.support.selectstart="onselectstart"in b;a.removeChild(b).style.display="none"});c.extend(c.ui,{plugin:{add:function(a,b,d){a=c.ui[a].prototype;for(var e in d){a.plugins[e]=a.plugins[e]||[];a.plugins[e].push([b,d[e]])}},call:function(a,b,d){if((b=a.plugins[b])&&a.element[0].parentNode)for(var e=0;e<b.length;e++)a.options[b[e][0]]&&b[e][1].apply(a.element,d)}},contains:function(a,b){
return document.compareDocumentPosition?a.compareDocumentPosition(b)&16:a!==b&&a.contains(b)},hasScroll:function(a,b){if(c(a).css("overflow")==="hidden")return false;b=b&&b==="left"?"scrollLeft":"scrollTop";var d=false;if(a[b]>0)return true;a[b]=1;d=a[b]>0;a[b]=0;return d},isOverAxis:function(a,b,d){return a>b&&a<b+d},isOver:function(a,b,d,e,h,i){return c.ui.isOverAxis(a,d,h)&&c.ui.isOverAxis(b,e,i)}})}})(jQuery);;(function(b,j){if(b.cleanData){var k=b.cleanData;b.cleanData=function(a){for(var c=0,d;(d=a[c])!=null;c++)try{b(d).triggerHandler("remove")}catch(e){}k(a)}}else{var l=b.fn.remove;b.fn.remove=function(a,c){return this.each(function(){if(!c)if(!a||b.filter(a,[this]).length)b("*",this).add([this]).each(function(){try{b(this).triggerHandler("remove")}catch(d){}});return l.call(b(this),a,c)})}}b.widget=function(a,c,d){var e=a.split(".")[0],f;a=a.split(".")[1];f=e+"-"+a;if(!d){d=c;c=b.Widget}b.expr[":"][f]=function(h){return!!b.data(h,a)};b[e]=b[e]||{};b[e][a]=function(h,g){arguments.length&&this._createWidget(h,g)};c=new c;c.options=b.extend(true,{},c.options);b[e][a].prototype=b.extend(true,c,{namespace:e,widgetName:a,widgetEventPrefix:b[e][a].prototype.widgetEventPrefix||a,widgetBaseClass:f},d);b.widget.bridge(a,b[e][a])};b.widget.bridge=function(a,c){b.fn[a]=function(d){var e=typeof d==="string",f=Array.prototype.slice.call(arguments,1),h=this;d=!e&&f.length?b.extend.apply(null,[true,d].concat(f)):d;if(e&&d.charAt(0)==="_")return h;e?this.each(function(){var g=b.data(this,a),i=g&&b.isFunction(g[d])?g[d].apply(g,f):g;if(i!==g&&i!==j){h=i;return false}}):this.each(function(){var g=b.data(this,a);g?g.option(d||{})._init():b.data(this,a,new c(d,this))});return h}};b.Widget=function(a,c){arguments.length&&this._createWidget(a,c)};b.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",options:{disabled:false},_createWidget:function(a,c){b.data(c,this.widgetName,this);this.element=b(c);this.options=b.extend(true,{},this.options,this._getCreateOptions(),a);var d=this;this.element.bind("remove."+this.widgetName,function(){d.destroy()});this._create();this._trigger("create");this._init()},_getCreateOptions:function(){return b.metadata&&b.metadata.get(this.element[0])[this.widgetName]},_create:function(){},_init:function(){},destroy:function(){this.element.unbind("."+this.widgetName).removeData(this.widgetName);this.widget().unbind("."+this.widgetName).removeAttr("aria-disabled").removeClass(this.widgetBaseClass+"-disabled ui-state-disabled")},widget:function(){return this.element},option:function(a,c){var d=a;if(arguments.length===0)return b.extend({},this.options);if(typeof a==="string"){if(c===j)return this.options[a];d={};d[a]=c}this._setOptions(d);return this},_setOptions:function(a){var c=this;b.each(a,function(d,e){c._setOption(d,e)});return this},_setOption:function(a,c){this.options[a]=c;if(a==="disabled")this.widget()[c?"addClass":"removeClass"](this.widgetBaseClass+"-disabled ui-state-disabled").attr("aria-disabled",c);return this},enable:function(){return this._setOption("disabled",false)},disable:function(){return this._setOption("disabled",true)},_trigger:function(a,c,d){var e=this.options[a];c=b.Event(c);c.type=(a===this.widgetEventPrefix?a:this.widgetEventPrefix+a).toLowerCase();d=d||{};if(c.originalEvent){a=b.event.props.length;for(var f;a;){f=b.event.props[--a];c[f]=c.originalEvent[f]}}this.element.trigger(c,d);return!(b.isFunction(e)&&e.call(this.element[0],c,d)===false||c.isDefaultPrevented())}}})(jQuery);;(function(b){var d=false;b(document).mouseup(function(){d=false});b.widget("ui.mouse",{options:{cancel:":input,option",distance:1,delay:0},_mouseInit:function(){var a=this;this.element.bind("mousedown."+this.widgetName,function(c){return a._mouseDown(c)}).bind("click."+this.widgetName,function(c){if(true===b.data(c.target,a.widgetName+".preventClickEvent")){b.removeData(c.target,a.widgetName+".preventClickEvent");c.stopImmediatePropagation();return false}});this.started=false},_mouseDestroy:function(){this.element.unbind("."+
this.widgetName)},_mouseDown:function(a){if(!d){this._mouseStarted&&this._mouseUp(a);this._mouseDownEvent=a;var c=this,f=a.which==1,g=typeof this.options.cancel=="string"&&a.target.nodeName?b(a.target).closest(this.options.cancel).length:false;if(!f||g||!this._mouseCapture(a))return true;this.mouseDelayMet=!this.options.delay;if(!this.mouseDelayMet)this._mouseDelayTimer=setTimeout(function(){c.mouseDelayMet=true},this.options.delay);if(this._mouseDistanceMet(a)&&this._mouseDelayMet(a)){this._mouseStarted=this._mouseStart(a)!==false;if(!this._mouseStarted){a.preventDefault();return true}}true===b.data(a.target,this.widgetName+".preventClickEvent")&&b.removeData(a.target,this.widgetName+".preventClickEvent");this._mouseMoveDelegate=function(e){return c._mouseMove(e)};this._mouseUpDelegate=function(e){return c._mouseUp(e)};b(document).bind("mousemove."+this.widgetName,this._mouseMoveDelegate).bind("mouseup."+this.widgetName,this._mouseUpDelegate);a.preventDefault();return d=true}},_mouseMove:function(a){if(b.browser.msie&&!(document.documentMode>=9)&&!a.button)return this._mouseUp(a);if(this._mouseStarted){this._mouseDrag(a);return a.preventDefault()}if(this._mouseDistanceMet(a)&&this._mouseDelayMet(a))(this._mouseStarted=this._mouseStart(this._mouseDownEvent,a)!==false)?this._mouseDrag(a):this._mouseUp(a);return!this._mouseStarted},_mouseUp:function(a){b(document).unbind("mousemove."+this.widgetName,this._mouseMoveDelegate).unbind("mouseup."+this.widgetName,this._mouseUpDelegate);if(this._mouseStarted){this._mouseStarted=false;a.target==this._mouseDownEvent.target&&b.data(a.target,this.widgetName+".preventClickEvent",true);this._mouseStop(a)}return false},_mouseDistanceMet:function(a){return Math.max(Math.abs(this._mouseDownEvent.pageX-a.pageX),Math.abs(this._mouseDownEvent.pageY-a.pageY))>=this.options.distance},_mouseDelayMet:function(){return this.mouseDelayMet},_mouseStart:function(){},_mouseDrag:function(){},_mouseStop:function(){},_mouseCapture:function(){return true}})})(jQuery);;(function(b){var h,i,j,g,l=function(){var a=b(this).find(":ui-button");setTimeout(function(){a.button("refresh")},1)},k=function(a){var c=a.name,e=a.form,f=b([]);if(c)f=e?b(e).find("[name='"+c+"']"):b("[name='"+c+"']",a.ownerDocument).filter(function(){return!this.form});return f};b.widget("ui.button",{options:{disabled:null,text:true,label:null,icons:{primary:null,secondary:null}},_create:function(){this.element.closest("form").unbind("reset.button").bind("reset.button",l);if(typeof this.options.disabled!=="boolean")this.options.disabled=this.element.propAttr("disabled");this._determineButtonType();this.hasTitle=!!this.buttonElement.attr("title");var a=this,c=this.options,e=this.type==="checkbox"||this.type==="radio",f="ui-state-hover"+(!e?" ui-state-active":"");if(c.label===null)c.label=this.buttonElement.html();if(this.element.is(":disabled"))c.disabled=true;this.buttonElement.addClass("ui-button ui-widget ui-state-default ui-corner-all").attr("role","button").bind("mouseenter.button",function(){if(!c.disabled){b(this).addClass("ui-state-hover");this===h&&b(this).addClass("ui-state-active")}}).bind("mouseleave.button",function(){c.disabled||b(this).removeClass(f)}).bind("click.button",function(d){if(c.disabled){d.preventDefault();d.stopImmediatePropagation()}});this.element.bind("focus.button",function(){a.buttonElement.addClass("ui-state-focus")}).bind("blur.button",function(){a.buttonElement.removeClass("ui-state-focus")});if(e){this.element.bind("change.button",function(){g||a.refresh()});this.buttonElement.bind("mousedown.button",function(d){if(!c.disabled){g=false;i=d.pageX;j=d.pageY}}).bind("mouseup.button",function(d){if(!c.disabled)if(i!==d.pageX||j!==d.pageY)g=true})}if(this.type==="checkbox")this.buttonElement.bind("click.button",function(){if(c.disabled||g)return false;b(this).toggleClass("ui-state-active");a.buttonElement.attr("aria-pressed",a.element[0].checked)});else if(this.type==="radio")this.buttonElement.bind("click.button",function(){if(c.disabled||g)return false;b(this).addClass("ui-state-active");a.buttonElement.attr("aria-pressed","true");var d=a.element[0];k(d).not(d).map(function(){return b(this).button("widget")[0]}).removeClass("ui-state-active").attr("aria-pressed","false")});else{this.buttonElement.bind("mousedown.button",function(){if(c.disabled)return false;b(this).addClass("ui-state-active");h=this;b(document).one("mouseup",function(){h=null})}).bind("mouseup.button",function(){if(c.disabled)return false;b(this).removeClass("ui-state-active")}).bind("keydown.button",function(d){if(c.disabled)return false;if(d.keyCode==b.ui.keyCode.SPACE||d.keyCode==b.ui.keyCode.ENTER)b(this).addClass("ui-state-active")}).bind("keyup.button",function(){b(this).removeClass("ui-state-active")});this.buttonElement.is("a")&&this.buttonElement.keyup(function(d){d.keyCode===b.ui.keyCode.SPACE&&b(this).click()})}this._setOption("disabled",c.disabled);this._resetButton()},_determineButtonType:function(){this.type=this.element.is(":checkbox")?"checkbox":this.element.is(":radio")?"radio":this.element.is("input")?"input":"button";if(this.type==="checkbox"||this.type==="radio"){var a=this.element.parents().filter(":last"),c="label[for='"+this.element.attr("id")+"']";this.buttonElement=a.find(c);if(!this.buttonElement.length){a=a.length?a.siblings():this.element.siblings();this.buttonElement=a.filter(c);if(!this.buttonElement.length)this.buttonElement=a.find(c)}this.element.addClass("ui-helper-hidden-accessible");(a=this.element.is(":checked"))&&this.buttonElement.addClass("ui-state-active");this.buttonElement.attr("aria-pressed",a)}else this.buttonElement=this.element},widget:function(){return this.buttonElement},destroy:function(){this.element.removeClass("ui-helper-hidden-accessible");this.buttonElement.removeClass("ui-button ui-widget ui-state-default ui-corner-all ui-state-hover ui-state-active  ui-button-icons-only ui-button-icon-only ui-button-text-icons ui-button-text-icon-primary ui-button-text-icon-secondary ui-button-text-only").removeAttr("role").removeAttr("aria-pressed").html(this.buttonElement.find(".ui-button-text").html());this.hasTitle||this.buttonElement.removeAttr("title");b.Widget.prototype.destroy.call(this)},_setOption:function(a,c){b.Widget.prototype._setOption.apply(this,arguments);if(a==="disabled")c?this.element.propAttr("disabled",true):this.element.propAttr("disabled",false);else this._resetButton()},refresh:function(){var a=this.element.is(":disabled");a!==this.options.disabled&&this._setOption("disabled",a);if(this.type==="radio")k(this.element[0]).each(function(){b(this).is(":checked")?b(this).button("widget").addClass("ui-state-active").attr("aria-pressed","true"):b(this).button("widget").removeClass("ui-state-active").attr("aria-pressed","false")});else if(this.type==="checkbox")this.element.is(":checked")?this.buttonElement.addClass("ui-state-active").attr("aria-pressed","true"):this.buttonElement.removeClass("ui-state-active").attr("aria-pressed","false")},_resetButton:function(){if(this.type==="input")this.options.label&&this.element.val(this.options.label);else{var a=this.buttonElement.removeClass("ui-button-icons-only ui-button-icon-only ui-button-text-icons ui-button-text-icon-primary ui-button-text-icon-secondary ui-button-text-only"),c=b("<span></span>").addClass("ui-button-text").html(this.options.label).appendTo(a.empty()).text(),e=this.options.icons,f=e.primary&&e.secondary,d=[];if(e.primary||e.secondary){if(this.options.text)d.push("ui-button-text-icon"+(f?"s":e.primary?"-primary":"-secondary"));e.primary&&a.prepend("<span class='ui-button-icon-primary ui-icon "+e.primary+"'></span>");e.secondary&&a.append("<span class='ui-button-icon-secondary ui-icon "+e.secondary+"'></span>");if(!this.options.text){d.push(f?"ui-button-icons-only":"ui-button-icon-only");this.hasTitle||a.attr("title",c)}}else d.push("ui-button-text-only");a.addClass(d.join(" "))}}});b.widget("ui.buttonset",{options:{items:":button, :submit, :reset, :checkbox, :radio, a, :data(button)"},_create:function(){this.element.addClass("ui-buttonset")},_init:function(){this.refresh()},_setOption:function(a,c){a==="disabled"&&this.buttons.button("option",a,c);b.Widget.prototype._setOption.apply(this,arguments)},refresh:function(){var a=this.element.css("direction")==="ltr";this.buttons=this.element.find(this.options.items).filter(":ui-button").button("refresh").end().not(":ui-button").button().end().map(function(){return b(this).button("widget")[0]}).removeClass("ui-corner-all ui-corner-left ui-corner-right").filter(":first").addClass(a?"ui-corner-left":"ui-corner-right").end().filter(":last").addClass(a?"ui-corner-right":"ui-corner-left").end().end()},destroy:function(){this.element.removeClass("ui-buttonset");this.buttons.map(function(){return b(this).button("widget")[0]}).removeClass("ui-corner-left ui-corner-right").end().button("destroy");b.Widget.prototype.destroy.call(this)}})})(jQuery);;(function(d){d.widget("ui.slider",d.ui.mouse,{widgetEventPrefix:"slide",options:{animate:false,distance:0,max:100,min:0,orientation:"horizontal",range:false,step:1,value:0,values:null},_create:function(){var a=this,b=this.options,c=this.element.find(".ui-slider-handle").addClass("ui-state-default ui-corner-all"),f=b.values&&b.values.length||1,e=[];this._mouseSliding=this._keySliding=false;this._animateOff=true;this._handleIndex=null;this._detectOrientation();this._mouseInit();this.element.addClass("ui-slider ui-slider-"+
this.orientation+" ui-widget ui-widget-content ui-corner-all"+(b.disabled?" ui-slider-disabled ui-disabled":""));this.range=d([]);if(b.range){if(b.range===true){if(!b.values)b.values=[this._valueMin(),this._valueMin()];if(b.values.length&&b.values.length!==2)b.values=[b.values[0],b.values[0]]}this.range=d("<div></div>").appendTo(this.element).addClass("ui-slider-range ui-widget-header"+(b.range==="min"||b.range==="max"?" ui-slider-range-"+b.range:""))}for(var j=c.length;j<f;j+=1)e.push("<a class='ui-slider-handle ui-state-default ui-corner-all' href='#'></a>");this.handles=c.add(d(e.join("")).appendTo(a.element));this.handle=this.handles.eq(0);this.handles.add(this.range).filter("a").click(function(g){g.preventDefault()}).hover(function(){b.disabled||d(this).addClass("ui-state-hover")},function(){d(this).removeClass("ui-state-hover")}).focus(function(){if(b.disabled)d(this).blur();else{d(".ui-slider .ui-state-focus").removeClass("ui-state-focus");d(this).addClass("ui-state-focus")}}).blur(function(){d(this).removeClass("ui-state-focus")});this.handles.each(function(g){d(this).data("index.ui-slider-handle",g)});this.handles.keydown(function(g){var k=true,l=d(this).data("index.ui-slider-handle"),i,h,m;if(!a.options.disabled){switch(g.keyCode){case d.ui.keyCode.HOME:case d.ui.keyCode.END:case d.ui.keyCode.PAGE_UP:case d.ui.keyCode.PAGE_DOWN:case d.ui.keyCode.UP:case d.ui.keyCode.RIGHT:case d.ui.keyCode.DOWN:case d.ui.keyCode.LEFT:k=false;if(!a._keySliding){a._keySliding=true;d(this).addClass("ui-state-active");i=a._start(g,l);if(i===false)return}break}m=a.options.step;i=a.options.values&&a.options.values.length?(h=a.values(l)):(h=a.value());switch(g.keyCode){case d.ui.keyCode.HOME:h=a._valueMin();break;case d.ui.keyCode.END:h=a._valueMax();break;case d.ui.keyCode.PAGE_UP:h=a._trimAlignValue(i+(a._valueMax()-a._valueMin())/5);break;case d.ui.keyCode.PAGE_DOWN:h=a._trimAlignValue(i-(a._valueMax()-a._valueMin())/5);break;case d.ui.keyCode.UP:case d.ui.keyCode.RIGHT:if(i===a._valueMax())return;h=a._trimAlignValue(i+m);break;case d.ui.keyCode.DOWN:case d.ui.keyCode.LEFT:if(i===a._valueMin())return;h=a._trimAlignValue(i-
m);break}a._slide(g,l,h);return k}}).keyup(function(g){var k=d(this).data("index.ui-slider-handle");if(a._keySliding){a._keySliding=false;a._stop(g,k);a._change(g,k);d(this).removeClass("ui-state-active")}});this._refreshValue();this._animateOff=false},destroy:function(){this.handles.remove();this.range.remove();this.element.removeClass("ui-slider ui-slider-horizontal ui-slider-vertical ui-slider-disabled ui-widget ui-widget-content ui-corner-all").removeData("slider").unbind(".slider");this._mouseDestroy();return this},_mouseCapture:function(a){var b=this.options,c,f,e,j,g;if(b.disabled)return false;this.elementSize={width:this.element.outerWidth(),height:this.element.outerHeight()};this.elementOffset=this.element.offset();c=this._normValueFromMouse({x:a.pageX,y:a.pageY});f=this._valueMax()-this._valueMin()+1;j=this;this.handles.each(function(k){var l=Math.abs(c-j.values(k));if(f>l){f=l;e=d(this);g=k}});if(b.range===true&&this.values(1)===b.min){g+=1;e=d(this.handles[g])}if(this._start(a,g)===false)return false;this._mouseSliding=true;j._handleIndex=g;e.addClass("ui-state-active").focus();b=e.offset();this._clickOffset=!d(a.target).parents().andSelf().is(".ui-slider-handle")?{left:0,top:0}:{left:a.pageX-b.left-e.width()/2,top:a.pageY-b.top-e.height()/2-(parseInt(e.css("borderTopWidth"),10)||0)-(parseInt(e.css("borderBottomWidth"),10)||0)+(parseInt(e.css("marginTop"),10)||0)};this.handles.hasClass("ui-state-hover")||this._slide(a,g,c);return this._animateOff=true},_mouseStart:function(){return true},_mouseDrag:function(a){var b=this._normValueFromMouse({x:a.pageX,y:a.pageY});this._slide(a,this._handleIndex,b);return false},_mouseStop:function(a){this.handles.removeClass("ui-state-active");this._mouseSliding=false;this._stop(a,this._handleIndex);this._change(a,this._handleIndex);this._clickOffset=this._handleIndex=null;return this._animateOff=false},_detectOrientation:function(){this.orientation=this.options.orientation==="vertical"?"vertical":"horizontal"},_normValueFromMouse:function(a){var b;if(this.orientation==="horizontal"){b=this.elementSize.width;a=a.x-this.elementOffset.left-(this._clickOffset?this._clickOffset.left:0)}else{b=this.elementSize.height;a=a.y-this.elementOffset.top-(this._clickOffset?this._clickOffset.top:0)}b=a/b;if(b>1)b=1;if(b<0)b=0;if(this.orientation==="vertical")b=1-b;a=this._valueMax()-this._valueMin();return this._trimAlignValue(this._valueMin()+b*a)},_start:function(a,b){var c={handle:this.handles[b],value:this.value()};if(this.options.values&&this.options.values.length){c.value=this.values(b);c.values=this.values()}return this._trigger("start",a,c)},_slide:function(a,b,c){var f;if(this.options.values&&this.options.values.length){f=this.values(b?0:1);if(this.options.values.length===2&&this.options.range===true&&(b===0&&c>f||b===1&&c<f))c=f;if(c!==this.values(b)){f=this.values();f[b]=c;a=this._trigger("slide",a,{handle:this.handles[b],value:c,values:f});this.values(b?0:1);a!==false&&this.values(b,c,true)}}else if(c!==this.value()){a=this._trigger("slide",a,{handle:this.handles[b],value:c});a!==false&&this.value(c)}},_stop:function(a,b){var c={handle:this.handles[b],value:this.value()};if(this.options.values&&this.options.values.length){c.value=this.values(b);c.values=this.values()}this._trigger("stop",a,c)},_change:function(a,b){if(!this._keySliding&&!this._mouseSliding){var c={handle:this.handles[b],value:this.value()};if(this.options.values&&this.options.values.length){c.value=this.values(b);c.values=this.values()}this._trigger("change",a,c)}},value:function(a){if(arguments.length){this.options.value=this._trimAlignValue(a);this._refreshValue();this._change(null,0)}else return this._value()},values:function(a,b){var c,f,e;if(arguments.length>1){this.options.values[a]=this._trimAlignValue(b);this._refreshValue();this._change(null,a)}else if(arguments.length)if(d.isArray(arguments[0])){c=this.options.values;f=arguments[0];for(e=0;e<c.length;e+=1){c[e]=this._trimAlignValue(f[e]);this._change(null,e)}this._refreshValue()}else return this.options.values&&this.options.values.length?this._values(a):this.value();else return this._values()},_setOption:function(a,b){var c,f=0;if(d.isArray(this.options.values))f=this.options.values.length;d.Widget.prototype._setOption.apply(this,arguments);switch(a){case"disabled":if(b){this.handles.filter(".ui-state-focus").blur();this.handles.removeClass("ui-state-hover");this.handles.propAttr("disabled",true);this.element.addClass("ui-disabled")}else{this.handles.propAttr("disabled",false);this.element.removeClass("ui-disabled")}break;case"orientation":this._detectOrientation();this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-"+this.orientation);this._refreshValue();break;case"value":this._animateOff=true;this._refreshValue();this._change(null,0);this._animateOff=false;break;case"values":this._animateOff=true;this._refreshValue();for(c=0;c<f;c+=1)this._change(null,c);this._animateOff=false;break}},_value:function(){var a=this.options.value;return a=this._trimAlignValue(a)},_values:function(a){var b,c;if(arguments.length){b=this.options.values[a];return b=this._trimAlignValue(b)}else{b=this.options.values.slice();for(c=0;c<b.length;c+=1)b[c]=this._trimAlignValue(b[c]);return b}},_trimAlignValue:function(a){if(a<=this._valueMin())return this._valueMin();if(a>=this._valueMax())return this._valueMax();var b=this.options.step>0?this.options.step:1,c=(a-this._valueMin())%b;a=a-c;if(Math.abs(c)*2>=b)a+=c>0?b:-b;return parseFloat(a.toFixed(5))},_valueMin:function(){return this.options.min},_valueMax:function(){return this.options.max},_refreshValue:function(){var a=this.options.range,b=this.options,c=this,f=!this._animateOff?b.animate:false,e,j={},g,k,l,i;if(this.options.values&&this.options.values.length)this.handles.each(function(h){e=(c.values(h)-c._valueMin())/(c._valueMax()-c._valueMin())*100;j[c.orientation==="horizontal"?"left":"bottom"]=e+"%";d(this).stop(1,1)[f?"animate":"css"](j,b.animate);if(c.options.range===true)if(c.orientation==="horizontal"){if(h===0)c.range.stop(1,1)[f?"animate":"css"]({left:e+"%"},b.animate);if(h===1)c.range[f?"animate":"css"]({width:e-
g+"%"},{queue:false,duration:b.animate})}else{if(h===0)c.range.stop(1,1)[f?"animate":"css"]({bottom:e+"%"},b.animate);if(h===1)c.range[f?"animate":"css"]({height:e-g+"%"},{queue:false,duration:b.animate})}g=e});else{k=this.value();l=this._valueMin();i=this._valueMax();e=i!==l?(k-l)/(i-l)*100:0;j[c.orientation==="horizontal"?"left":"bottom"]=e+"%";this.handle.stop(1,1)[f?"animate":"css"](j,b.animate);if(a==="min"&&this.orientation==="horizontal")this.range.stop(1,1)[f?"animate":"css"]({width:e+"%"},b.animate);if(a==="max"&&this.orientation==="horizontal")this.range[f?"animate":"css"]({width:100-e+"%"},{queue:false,duration:b.animate});if(a==="min"&&this.orientation==="vertical")this.range.stop(1,1)[f?"animate":"css"]({height:e+"%"},b.animate);if(a==="max"&&this.orientation==="vertical")this.range[f?"animate":"css"]({height:100-e+"%"},{queue:false,duration:b.animate})}}});d.extend(d.ui.slider,{version:"1.8.16"})})(jQuery);;(function(d,C){function M(){this.debug=false;this._curInst=null;this._keyEvent=false;this._disabledInputs=[];this._inDialog=this._datepickerShowing=false;this._mainDivId="ui-datepicker-div";this._inlineClass="ui-datepicker-inline";this._appendClass="ui-datepicker-append";this._triggerClass="ui-datepicker-trigger";this._dialogClass="ui-datepicker-dialog";this._disableClass="ui-datepicker-disabled";this._unselectableClass="ui-datepicker-unselectable";this._currentClass="ui-datepicker-current-day";this._dayOverClass="ui-datepicker-days-cell-over";this.regional=[];this.regional[""]={closeText:"Done",prevText:"Prev",nextText:"Next",currentText:"Today",monthNames:["January","February","March","April","May","June","July","August","September","October","November","December"],monthNamesShort:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],dayNames:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],dayNamesShort:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],dayNamesMin:["Su","Mo","Tu","We","Th","Fr","Sa"],weekHeader:"Wk",dateFormat:"dd-mm-yy",firstDay:0,isRTL:false,showMonthAfterYear:false,yearSuffix:""};this._defaults={showOn:"focus",showAnim:"fadeIn",showOptions:{},defaultDate:null,appendText:"",buttonText:"...",buttonImage:"",buttonImageOnly:false,hideIfNoPrevNext:false,navigationAsDateFormat:false,gotoCurrent:false,changeMonth:false,changeYear:false,yearRange:"c-10:c+10",showOtherMonths:false,selectOtherMonths:false,showWeek:false,calculateWeek:this.iso8601Week,shortYearCutoff:"+10",minDate:null,maxDate:null,duration:"fast",beforeShowDay:null,beforeShow:null,onSelect:null,onChangeMonthYear:null,onClose:null,numberOfMonths:1,showCurrentAtPos:0,stepMonths:1,stepBigMonths:12,altField:"",altFormat:"",constrainInput:true,showButtonPanel:false,autoSize:false,disabled:false};d.extend(this._defaults,this.regional[""]);this.dpDiv=N(d('<div id="'+this._mainDivId+'" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>'))}function N(a){return a.bind("mouseout",function(b){b=d(b.target).closest("button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a");b.length&&b.removeClass("ui-state-hover ui-datepicker-prev-hover ui-datepicker-next-hover")}).bind("mouseover",function(b){b=d(b.target).closest("button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a");if(!(d.datepicker._isDisabledDatepicker(J.inline?a.parent()[0]:J.input[0])||!b.length)){b.parents(".ui-datepicker-calendar").find("a").removeClass("ui-state-hover");b.addClass("ui-state-hover");b.hasClass("ui-datepicker-prev")&&b.addClass("ui-datepicker-prev-hover");b.hasClass("ui-datepicker-next")&&b.addClass("ui-datepicker-next-hover")}})}function H(a,b){d.extend(a,b);for(var c in b)if(b[c]==null||b[c]==C)a[c]=b[c];return a}d.extend(d.ui,{datepicker:{version:"1.8.16"}});var B=(new Date).getTime(),J;d.extend(M.prototype,{markerClassName:"hasDatepicker",maxRows:4,log:function(){this.debug&&console.log.apply("",arguments)},_widgetDatepicker:function(){return this.dpDiv},setDefaults:function(a){H(this._defaults,a||{});return this},_attachDatepicker:function(a,b){var c=null;for(var e in this._defaults){var f=a.getAttribute("date:"+e);if(f){c=c||{};try{c[e]=eval(f)}catch(h){c[e]=f}}}e=a.nodeName.toLowerCase();f=e=="div"||e=="span";if(!a.id){this.uuid+=1;a.id="dp"+this.uuid}var i=this._newInst(d(a),f);i.settings=d.extend({},b||{},c||{});if(e=="input")this._connectDatepicker(a,i);else f&&this._inlineDatepicker(a,i)},_newInst:function(a,b){return{id:a[0].id.replace(/([^A-Za-z0-9_-])/g,"\\\\$1"),input:a,selectedDay:0,selectedMonth:0,selectedYear:0,drawMonth:0,drawYear:0,inline:b,dpDiv:!b?this.dpDiv:N(d('<div class="'+this._inlineClass+' ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>'))}},_connectDatepicker:function(a,b){var c=d(a);b.append=d([]);b.trigger=d([]);if(!c.hasClass(this.markerClassName)){this._attachments(c,b);c.addClass(this.markerClassName).keydown(this._doKeyDown).keypress(this._doKeyPress).keyup(this._doKeyUp).bind("setData.datepicker",function(e,f,h){b.settings[f]=h}).bind("getData.datepicker",function(e,f){return this._get(b,f)});this._autoSize(b);d.data(a,"datepicker",b);b.settings.disabled&&this._disableDatepicker(a)}},_attachments:function(a,b){var c=this._get(b,"appendText"),e=this._get(b,"isRTL");b.append&&b.append.remove();if(c){b.append=d('<span class="'+this._appendClass+'">'+c+"</span>");a[e?"before":"after"](b.append)}a.unbind("focus",this._showDatepicker);b.trigger&&b.trigger.remove();c=this._get(b,"showOn");if(c=="focus"||c=="both")a.focus(this._showDatepicker);if(c=="button"||c=="both"){c=this._get(b,"buttonText");var f=this._get(b,"buttonImage");b.trigger=d(this._get(b,"buttonImageOnly")?d("<img/>").addClass(this._triggerClass).attr({src:f,alt:c,title:c}):d('<button type="button"></button>').addClass(this._triggerClass).html(f==""?c:d("<img/>").attr({src:f,alt:c,title:c})));a[e?"before":"after"](b.trigger);b.trigger.click(function(){d.datepicker._datepickerShowing&&d.datepicker._lastInput==a[0]?d.datepicker._hideDatepicker():d.datepicker._showDatepicker(a[0]);return false})}},_autoSize:function(a){if(this._get(a,"autoSize")&&!a.inline){var b=new Date(2009,11,20),c=this._get(a,"dateFormat");if(c.match(/[DM]/)){var e=function(f){for(var h=0,i=0,g=0;g<f.length;g++)if(f[g].length>h){h=f[g].length;i=g}return i};b.setMonth(e(this._get(a,c.match(/MM/)?"monthNames":"monthNamesShort")));b.setDate(e(this._get(a,c.match(/DD/)?"dayNames":"dayNamesShort"))+20-b.getDay())}a.input.attr("size",this._formatDate(a,b).length)}},_inlineDatepicker:function(a,b){var c=d(a);if(!c.hasClass(this.markerClassName)){c.addClass(this.markerClassName).append(b.dpDiv).bind("setData.datepicker",function(e,f,h){b.settings[f]=h}).bind("getData.datepicker",function(e,f){return this._get(b,f)});d.data(a,"datepicker",b);this._setDate(b,this._getDefaultDate(b),true);this._updateDatepicker(b);this._updateAlternate(b);b.settings.disabled&&this._disableDatepicker(a);b.dpDiv.css("display","block")}},_dialogDatepicker:function(a,b,c,e,f){a=this._dialogInst;if(!a){this.uuid+=1;this._dialogInput=d('<input type="text" id="'+("dp"+this.uuid)+'" style="position: absolute; top: -100px; width: 0px; z-index: 22;"/>');this._dialogInput.keydown(this._doKeyDown);d("body").append(this._dialogInput);a=this._dialogInst=this._newInst(this._dialogInput,false);a.settings={};d.data(this._dialogInput[0],"datepicker",a)}H(a.settings,e||{});b=b&&b.constructor==Date?this._formatDate(a,b):b;this._dialogInput.val(b);this._pos=f?f.length?f:[f.pageX,f.pageY]:null;if(!this._pos)this._pos=[document.documentElement.clientWidth/2-100+(document.documentElement.scrollLeft||document.body.scrollLeft),document.documentElement.clientHeight/2-150+(document.documentElement.scrollTop||document.body.scrollTop)];this._dialogInput.css("left",this._pos[0]+20+"px").css("top",this._pos[1]+"px");a.settings.onSelect=c;this._inDialog=true;this.dpDiv.addClass(this._dialogClass);this._showDatepicker(this._dialogInput[0]);d.blockUI&&d.blockUI(this.dpDiv);d.data(this._dialogInput[0],"datepicker",a);return this},_destroyDatepicker:function(a){var b=d(a),c=d.data(a,"datepicker");if(b.hasClass(this.markerClassName)){var e=a.nodeName.toLowerCase();d.removeData(a,"datepicker");if(e=="input"){c.append.remove();c.trigger.remove();b.removeClass(this.markerClassName).unbind("focus",this._showDatepicker).unbind("keydown",this._doKeyDown).unbind("keypress",this._doKeyPress).unbind("keyup",this._doKeyUp)}else if(e=="div"||e=="span")b.removeClass(this.markerClassName).empty()}},_enableDatepicker:function(a){var b=d(a),c=d.data(a,"datepicker");if(b.hasClass(this.markerClassName)){var e=a.nodeName.toLowerCase();if(e=="input"){a.disabled=false;c.trigger.filter("button").each(function(){this.disabled=false}).end().filter("img").css({opacity:"1.0",cursor:""})}else if(e=="div"||e=="span"){b=b.children("."+this._inlineClass);b.children().removeClass("ui-state-disabled");b.find("select.ui-datepicker-month, select.ui-datepicker-year").removeAttr("disabled")}this._disabledInputs=d.map(this._disabledInputs,function(f){return f==a?null:f})}},_disableDatepicker:function(a){var b=d(a),c=d.data(a,"datepicker");if(b.hasClass(this.markerClassName)){var e=a.nodeName.toLowerCase();if(e=="input"){a.disabled=true;c.trigger.filter("button").each(function(){this.disabled=true}).end().filter("img").css({opacity:"0.5",cursor:"default"})}else if(e=="div"||e=="span"){b=b.children("."+this._inlineClass);b.children().addClass("ui-state-disabled");b.find("select.ui-datepicker-month, select.ui-datepicker-year").attr("disabled","disabled")}this._disabledInputs=d.map(this._disabledInputs,function(f){return f==a?null:f});this._disabledInputs[this._disabledInputs.length]=a}},_isDisabledDatepicker:function(a){if(!a)return false;for(var b=0;b<this._disabledInputs.length;b++)if(this._disabledInputs[b]==a)return true;return false},_getInst:function(a){try{return d.data(a,"datepicker")}catch(b){throw"Missing instance data for this datepicker";}},_optionDatepicker:function(a,b,c){var e=this._getInst(a);if(arguments.length==2&&typeof b=="string")return b=="defaults"?d.extend({},d.datepicker._defaults):e?b=="all"?d.extend({},e.settings):this._get(e,b):null;var f=b||{};if(typeof b=="string"){f={};f[b]=c}if(e){this._curInst==e&&this._hideDatepicker();var h=this._getDateDatepicker(a,true),i=this._getMinMaxDate(e,"min"),g=this._getMinMaxDate(e,"max");H(e.settings,f);if(i!==null&&f.dateFormat!==C&&f.minDate===C)e.settings.minDate=this._formatDate(e,i);if(g!==null&&f.dateFormat!==C&&f.maxDate===C)e.settings.maxDate=this._formatDate(e,g);this._attachments(d(a),e);this._autoSize(e);this._setDate(e,h);this._updateAlternate(e);this._updateDatepicker(e)}},_changeDatepicker:function(a,b,c){this._optionDatepicker(a,b,c)},_refreshDatepicker:function(a){(a=this._getInst(a))&&this._updateDatepicker(a)},_setDateDatepicker:function(a,b){if(a=this._getInst(a)){this._setDate(a,b);this._updateDatepicker(a);this._updateAlternate(a)}},_getDateDatepicker:function(a,b){(a=this._getInst(a))&&!a.inline&&this._setDateFromField(a,b);return a?this._getDate(a):null},_doKeyDown:function(a){var b=d.datepicker._getInst(a.target),c=true,e=b.dpDiv.is(".ui-datepicker-rtl");b._keyEvent=true;if(d.datepicker._datepickerShowing)switch(a.keyCode){case 9:d.datepicker._hideDatepicker();c=false;break;case 13:c=d("td."+d.datepicker._dayOverClass+":not(."+d.datepicker._currentClass+")",b.dpDiv);c[0]&&d.datepicker._selectDay(a.target,b.selectedMonth,b.selectedYear,c[0]);if(a=d.datepicker._get(b,"onSelect")){c=d.datepicker._formatDate(b);a.apply(b.input?b.input[0]:null,[c,b])}else d.datepicker._hideDatepicker();return false;case 27:d.datepicker._hideDatepicker();break;case 33:d.datepicker._adjustDate(a.target,a.ctrlKey?-d.datepicker._get(b,"stepBigMonths"):-d.datepicker._get(b,"stepMonths"),"M");break;case 34:d.datepicker._adjustDate(a.target,a.ctrlKey?+d.datepicker._get(b,"stepBigMonths"):+d.datepicker._get(b,"stepMonths"),"M");break;case 35:if(a.ctrlKey||a.metaKey)d.datepicker._clearDate(a.target);c=a.ctrlKey||a.metaKey;break;case 36:if(a.ctrlKey||a.metaKey)d.datepicker._gotoToday(a.target);c=a.ctrlKey||a.metaKey;break;case 37:if(a.ctrlKey||a.metaKey)d.datepicker._adjustDate(a.target,e?+1:-1,"D");c=a.ctrlKey||a.metaKey;if(a.originalEvent.altKey)d.datepicker._adjustDate(a.target,a.ctrlKey?-d.datepicker._get(b,"stepBigMonths"):-d.datepicker._get(b,"stepMonths"),"M");break;case 38:if(a.ctrlKey||a.metaKey)d.datepicker._adjustDate(a.target,-7,"D");c=a.ctrlKey||a.metaKey;break;case 39:if(a.ctrlKey||a.metaKey)d.datepicker._adjustDate(a.target,e?-1:+1,"D");c=a.ctrlKey||a.metaKey;if(a.originalEvent.altKey)d.datepicker._adjustDate(a.target,a.ctrlKey?+d.datepicker._get(b,"stepBigMonths"):+d.datepicker._get(b,"stepMonths"),"M");break;case 40:if(a.ctrlKey||a.metaKey)d.datepicker._adjustDate(a.target,+7,"D");c=a.ctrlKey||a.metaKey;break;default:c=false}else if(a.keyCode==36&&a.ctrlKey)d.datepicker._showDatepicker(this);else c=false;if(c){a.preventDefault();a.stopPropagation()}},_doKeyPress:function(a){var b=d.datepicker._getInst(a.target);if(d.datepicker._get(b,"constrainInput")){b=d.datepicker._possibleChars(d.datepicker._get(b,"dateFormat"));var c=String.fromCharCode(a.charCode==C?a.keyCode:a.charCode);return a.ctrlKey||a.metaKey||c<" "||!b||b.indexOf(c)>-1}},_doKeyUp:function(a){a=d.datepicker._getInst(a.target);if(a.input.val()!=a.lastVal)try{if(d.datepicker.parseDate(d.datepicker._get(a,"dateFormat"),a.input?a.input.val():null,d.datepicker._getFormatConfig(a))){d.datepicker._setDateFromField(a);d.datepicker._updateAlternate(a);d.datepicker._updateDatepicker(a)}}catch(b){d.datepicker.log(b)}return true},_showDatepicker:function(a){a=a.target||a;if(a.nodeName.toLowerCase()!="input")a=d("input",a.parentNode)[0];if(!(d.datepicker._isDisabledDatepicker(a)||d.datepicker._lastInput==a)){var b=d.datepicker._getInst(a);if(d.datepicker._curInst&&d.datepicker._curInst!=b){d.datepicker._datepickerShowing&&d.datepicker._triggerOnClose(d.datepicker._curInst);d.datepicker._curInst.dpDiv.stop(true,true)}var c=d.datepicker._get(b,"beforeShow");c=c?c.apply(a,[a,b]):{};if(c!==false){H(b.settings,c);b.lastVal=null;d.datepicker._lastInput=a;d.datepicker._setDateFromField(b);if(d.datepicker._inDialog)a.value="";if(!d.datepicker._pos){d.datepicker._pos=d.datepicker._findPos(a);d.datepicker._pos[1]+=a.offsetHeight}var e=false;d(a).parents().each(function(){e|=d(this).css("position")=="fixed";return!e});if(e&&d.browser.opera){d.datepicker._pos[0]-=document.documentElement.scrollLeft;d.datepicker._pos[1]-=document.documentElement.scrollTop}c={left:d.datepicker._pos[0],top:d.datepicker._pos[1]};d.datepicker._pos=null;b.dpDiv.empty();b.dpDiv.css({position:"absolute",display:"block",top:"-1000px"});d.datepicker._updateDatepicker(b);c=d.datepicker._checkOffset(b,c,e);b.dpDiv.css({position:d.datepicker._inDialog&&d.blockUI?"static":e?"fixed":"absolute",display:"none",left:c.left+"px",top:c.top+"px"});if(!b.inline){c=d.datepicker._get(b,"showAnim");var f=d.datepicker._get(b,"duration"),h=function(){var i=b.dpDiv.find("iframe.ui-datepicker-cover");if(i.length){var g=d.datepicker._getBorders(b.dpDiv);i.css({left:-g[0],top:-g[1],width:b.dpDiv.outerWidth(),height:b.dpDiv.outerHeight()})}};b.dpDiv.zIndex(d(a).zIndex()+1);d.datepicker._datepickerShowing=true;d.effects&&d.effects[c]?b.dpDiv.show(c,d.datepicker._get(b,"showOptions"),f,h):b.dpDiv[c||"show"](c?f:null,h);if(!c||!f)h();b.input.is(":visible")&&!b.input.is(":disabled")&&b.input.focus();d.datepicker._curInst=b}}}},_updateDatepicker:function(a){this.maxRows=4;var b=d.datepicker._getBorders(a.dpDiv);J=a;a.dpDiv.empty().append(this._generateHTML(a));var c=a.dpDiv.find("iframe.ui-datepicker-cover");c.length&&c.css({left:-b[0],top:-b[1],width:a.dpDiv.outerWidth(),height:a.dpDiv.outerHeight()});a.dpDiv.find("."+this._dayOverClass+" a").mouseover();b=this._getNumberOfMonths(a);c=b[1];a.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width("");c>1&&a.dpDiv.addClass("ui-datepicker-multi-"+c).css("width",17*c+"em");a.dpDiv[(b[0]!=1||b[1]!=1?"add":"remove")+"Class"]("ui-datepicker-multi");a.dpDiv[(this._get(a,"isRTL")?"add":"remove")+"Class"]("ui-datepicker-rtl");a==d.datepicker._curInst&&d.datepicker._datepickerShowing&&a.input&&a.input.is(":visible")&&!a.input.is(":disabled")&&a.input[0]!=document.activeElement&&a.input.focus();if(a.yearshtml){var e=a.yearshtml;setTimeout(function(){e===a.yearshtml&&a.yearshtml&&a.dpDiv.find("select.ui-datepicker-year:first").replaceWith(a.yearshtml);e=a.yearshtml=null},0)}},_getBorders:function(a){var b=function(c){return{thin:1,medium:2,thick:3}[c]||c};return[parseFloat(b(a.css("border-left-width"))),parseFloat(b(a.css("border-top-width")))]},_checkOffset:function(a,b,c){var e=a.dpDiv.outerWidth(),f=a.dpDiv.outerHeight(),h=a.input?a.input.outerWidth():0,i=a.input?a.input.outerHeight():0,g=document.documentElement.clientWidth+d(document).scrollLeft(),j=document.documentElement.clientHeight+d(document).scrollTop();b.left-=this._get(a,"isRTL")?e-h:0;b.left-=c&&b.left==a.input.offset().left?d(document).scrollLeft():0;b.top-=c&&b.top==a.input.offset().top+i?d(document).scrollTop():0;b.left-=Math.min(b.left,b.left+e>g&&g>e?Math.abs(b.left+e-g):0);b.top-=Math.min(b.top,b.top+f>j&&j>f?Math.abs(f+i):0);return b},_findPos:function(a){for(var b=this._get(this._getInst(a),"isRTL");a&&(a.type=="hidden"||a.nodeType!=1||d.expr.filters.hidden(a));)a=a[b?"previousSibling":"nextSibling"];a=d(a).offset();return[a.left,a.top]},_triggerOnClose:function(a){var b=this._get(a,"onClose");if(b)b.apply(a.input?a.input[0]:null,[a.input?a.input.val():"",a])},_hideDatepicker:function(a){var b=this._curInst;if(!(!b||a&&b!=d.data(a,"datepicker")))if(this._datepickerShowing){a=this._get(b,"showAnim");var c=this._get(b,"duration"),e=function(){d.datepicker._tidyDialog(b);this._curInst=null};d.effects&&d.effects[a]?b.dpDiv.hide(a,d.datepicker._get(b,"showOptions"),c,e):b.dpDiv[a=="slideDown"?"slideUp":a=="fadeIn"?"fadeOut":"hide"](a?c:null,e);a||e();d.datepicker._triggerOnClose(b);this._datepickerShowing=false;this._lastInput=null;if(this._inDialog){this._dialogInput.css({position:"absolute",left:"0",top:"-100px"});if(d.blockUI){d.unblockUI();d("body").append(this.dpDiv)}}this._inDialog=false}},_tidyDialog:function(a){a.dpDiv.removeClass(this._dialogClass).unbind(".ui-datepicker-calendar")},_checkExternalClick:function(a){if(d.datepicker._curInst){a=d(a.target);a[0].id!=d.datepicker._mainDivId&&a.parents("#"+d.datepicker._mainDivId).length==0&&!a.hasClass(d.datepicker.markerClassName)&&!a.hasClass(d.datepicker._triggerClass)&&d.datepicker._datepickerShowing&&!(d.datepicker._inDialog&&d.blockUI)&&d.datepicker._hideDatepicker()}},_adjustDate:function(a,b,c){a=d(a);var e=this._getInst(a[0]);if(!this._isDisabledDatepicker(a[0])){this._adjustInstDate(e,b+(c=="M"?this._get(e,"showCurrentAtPos"):0),c);this._updateDatepicker(e)}},_gotoToday:function(a){a=d(a);var b=this._getInst(a[0]);if(this._get(b,"gotoCurrent")&&b.currentDay){b.selectedDay=b.currentDay;b.drawMonth=b.selectedMonth=b.currentMonth;b.drawYear=b.selectedYear=b.currentYear}else{var c=new Date;b.selectedDay=c.getDate();b.drawMonth=b.selectedMonth=c.getMonth();b.drawYear=b.selectedYear=c.getFullYear()}this._notifyChange(b);this._adjustDate(a)},_selectMonthYear:function(a,b,c){a=d(a);var e=this._getInst(a[0]);e["selected"+(c=="M"?"Month":"Year")]=e["draw"+(c=="M"?"Month":"Year")]=parseInt(b.options[b.selectedIndex].value,10);this._notifyChange(e);this._adjustDate(a)},_selectDay:function(a,b,c,e){var f=d(a);if(!(d(e).hasClass(this._unselectableClass)||this._isDisabledDatepicker(f[0]))){f=this._getInst(f[0]);f.selectedDay=f.currentDay=d("a",e).html();f.selectedMonth=f.currentMonth=b;f.selectedYear=f.currentYear=c;this._selectDate(a,this._formatDate(f,f.currentDay,f.currentMonth,f.currentYear))}},_clearDate:function(a){a=d(a);this._getInst(a[0]);this._selectDate(a,"")},_selectDate:function(a,b){a=this._getInst(d(a)[0]);b=b!=null?b:this._formatDate(a);a.input&&a.input.val(b);this._updateAlternate(a);var c=this._get(a,"onSelect");if(c)c.apply(a.input?a.input[0]:null,[b,a]);else a.input&&a.input.trigger("change");if(a.inline)this._updateDatepicker(a);else{this._hideDatepicker();this._lastInput=a.input[0];typeof a.input[0]!="object"&&a.input.focus();this._lastInput=null}},_updateAlternate:function(a){var b=this._get(a,"altField");if(b){var c=this._get(a,"altFormat")||this._get(a,"dateFormat"),e=this._getDate(a),f=this.formatDate(c,e,this._getFormatConfig(a));d(b).each(function(){d(this).val(f)})}},noWeekends:function(a){a=a.getDay();return[a>0&&a<6,""]},iso8601Week:function(a){a=new Date(a.getTime());a.setDate(a.getDate()+4-(a.getDay()||7));var b=a.getTime();a.setMonth(0);a.setDate(1);return Math.floor(Math.round((b-a)/864E5)/7)+1},parseDate:function(a,b,c){if(a==null||b==null)throw"Invalid arguments";b=typeof b=="object"?b.toString():b+"";if(b=="")return null;var e=(c?c.shortYearCutoff:null)||this._defaults.shortYearCutoff;e=typeof e!="string"?e:(new Date).getFullYear()%100+parseInt(e,10);for(var f=(c?c.dayNamesShort:null)||this._defaults.dayNamesShort,h=(c?c.dayNames:null)||this._defaults.dayNames,i=(c?c.monthNamesShort:null)||this._defaults.monthNamesShort,g=(c?c.monthNames:null)||this._defaults.monthNames,j=c=-1,l=-1,u=-1,k=false,o=function(p){(p=A+1<a.length&&a.charAt(A+1)==p)&&A++;return p},m=function(p){var D=o(p);p=new RegExp("^\\d{1,"+(p=="@"?14:p=="!"?20:p=="y"&&D?4:p=="o"?3:2)+"}");p=b.substring(q).match(p);if(!p)throw"Missing number at position "+q;q+=p[0].length;return parseInt(p[0],10)},n=function(p,D,K){p=d.map(o(p)?K:D,function(w,x){return[[x,w]]}).sort(function(w,x){return-(w[1].length-x[1].length)});var E=-1;d.each(p,function(w,x){w=x[1];if(b.substr(q,w.length).toLowerCase()==w.toLowerCase()){E=x[0];q+=w.length;return false}});if(E!=-1)return E+1;else throw"Unknown name at position "+q;},s=function(){if(b.charAt(q)!=a.charAt(A))throw"Unexpected literal at position "+q;q++},q=0,A=0;A<a.length;A++)if(k)if(a.charAt(A)=="'"&&!o("'"))k=false;else s();else switch(a.charAt(A)){case"d":l=m("d");break;case"D":n("D",f,h);break;case"o":u=m("o");break;case"m":j=m("m");break;case"M":j=n("M",i,g);break;case"y":c=m("y");break;case"@":var v=new Date(m("@"));c=v.getFullYear();j=v.getMonth()+1;l=v.getDate();break;case"!":v=new Date((m("!")-this._ticksTo1970)/1E4);c=v.getFullYear();j=v.getMonth()+
1;l=v.getDate();break;case"'":if(o("'"))s();else k=true;break;default:s()}if(q<b.length)throw"Extra/unparsed characters found in date: "+b.substring(q);if(c==-1)c=(new Date).getFullYear();else if(c<100)c+=(new Date).getFullYear()-(new Date).getFullYear()%100+(c<=e?0:-100);if(u>-1){j=1;l=u;do{e=this._getDaysInMonth(c,j-1);if(l<=e)break;j++;l-=e}while(1)}v=this._daylightSavingAdjust(new Date(c,j-1,l));if(v.getFullYear()!=c||v.getMonth()+1!=j||v.getDate()!=l)throw"Invalid date";return v},ATOM:"yy-mm-dd",COOKIE:"D, dd M yy",ISO_8601:"yy-mm-dd",RFC_822:"D, d M y",RFC_850:"DD, dd-M-y",RFC_1036:"D, d M y",RFC_1123:"D, d M yy",RFC_2822:"D, d M yy",RSS:"D, d M y",TICKS:"!",TIMESTAMP:"@",W3C:"yy-mm-dd",_ticksTo1970:(718685+Math.floor(492.5)-Math.floor(19.7)+Math.floor(4.925))*24*60*60*1E7,formatDate:function(a,b,c){if(!b)return"";var e=(c?c.dayNamesShort:null)||this._defaults.dayNamesShort,f=(c?c.dayNames:null)||this._defaults.dayNames,h=(c?c.monthNamesShort:null)||this._defaults.monthNamesShort;c=(c?c.monthNames:null)||this._defaults.monthNames;var i=function(o){(o=k+1<a.length&&a.charAt(k+1)==o)&&k++;return o},g=function(o,m,n){m=""+m;if(i(o))for(;m.length<n;)m="0"+m;return m},j=function(o,m,n,s){return i(o)?s[m]:n[m]},l="",u=false;if(b)for(var k=0;k<a.length;k++)if(u)if(a.charAt(k)=="'"&&!i("'"))u=false;else l+=a.charAt(k);else switch(a.charAt(k)){case"d":l+=g("d",b.getDate(),2);break;case"D":l+=j("D",b.getDay(),e,f);break;case"o":l+=g("o",Math.round(((new Date(b.getFullYear(),b.getMonth(),b.getDate())).getTime()-
(new Date(b.getFullYear(),0,0)).getTime())/864E5),3);break;case"m":l+=g("m",b.getMonth()+1,2);break;case"M":l+=j("M",b.getMonth(),h,c);break;case"y":l+=i("y")?b.getFullYear():(b.getYear()%100<10?"0":"")+b.getYear()%100;break;case"@":l+=b.getTime();break;case"!":l+=b.getTime()*1E4+this._ticksTo1970;break;case"'":if(i("'"))l+="'";else u=true;break;default:l+=a.charAt(k)}return l},_possibleChars:function(a){for(var b="",c=false,e=function(h){(h=f+1<a.length&&a.charAt(f+1)==h)&&f++;return h},f=0;f<a.length;f++)if(c)if(a.charAt(f)=="'"&&!e("'"))c=false;else b+=a.charAt(f);else switch(a.charAt(f)){case"d":case"m":case"y":case"@":b+="0123456789";break;case"D":case"M":return null;case"'":if(e("'"))b+="'";else c=true;break;default:b+=a.charAt(f)}return b},_get:function(a,b){return a.settings[b]!==C?a.settings[b]:this._defaults[b]},_setDateFromField:function(a,b){if(a.input.val()!=a.lastVal){var c=this._get(a,"dateFormat"),e=a.lastVal=a.input?a.input.val():null,f,h;f=h=this._getDefaultDate(a);var i=this._getFormatConfig(a);try{f=this.parseDate(c,e,i)||h}catch(g){this.log(g);e=b?"":e}a.selectedDay=f.getDate();a.drawMonth=a.selectedMonth=f.getMonth();a.drawYear=a.selectedYear=f.getFullYear();a.currentDay=e?f.getDate():0;a.currentMonth=e?f.getMonth():0;a.currentYear=e?f.getFullYear():0;this._adjustInstDate(a)}},_getDefaultDate:function(a){return this._restrictMinMax(a,this._determineDate(a,this._get(a,"defaultDate"),new Date))},_determineDate:function(a,b,c){var e=function(h){var i=new Date;i.setDate(i.getDate()+h);return i},f=function(h){try{return d.datepicker.parseDate(d.datepicker._get(a,"dateFormat"),h,d.datepicker._getFormatConfig(a))}catch(i){}var g=(h.toLowerCase().match(/^c/)?d.datepicker._getDate(a):null)||new Date,j=g.getFullYear(),l=g.getMonth();g=g.getDate();for(var u=/([+-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?/g,k=u.exec(h);k;){switch(k[2]||"d"){case"d":case"D":g+=parseInt(k[1],10);break;case"w":case"W":g+=parseInt(k[1],10)*7;break;case"m":case"M":l+=parseInt(k[1],10);g=Math.min(g,d.datepicker._getDaysInMonth(j,l));break;case"y":case"Y":j+=parseInt(k[1],10);g=Math.min(g,d.datepicker._getDaysInMonth(j,l));break}k=u.exec(h)}return new Date(j,l,g)};if(b=(b=b==null||b===""?c:typeof b=="string"?f(b):typeof b=="number"?isNaN(b)?c:e(b):new Date(b.getTime()))&&b.toString()=="Invalid Date"?c:b){b.setHours(0);b.setMinutes(0);b.setSeconds(0);b.setMilliseconds(0)}return this._daylightSavingAdjust(b)},_daylightSavingAdjust:function(a){if(!a)return null;a.setHours(a.getHours()>12?a.getHours()+2:0);return a},_setDate:function(a,b,c){var e=!b,f=a.selectedMonth,h=a.selectedYear;b=this._restrictMinMax(a,this._determineDate(a,b,new Date));a.selectedDay=a.currentDay=b.getDate();a.drawMonth=a.selectedMonth=a.currentMonth=b.getMonth();a.drawYear=a.selectedYear=a.currentYear=b.getFullYear();if((f!=a.selectedMonth||h!=a.selectedYear)&&!c)this._notifyChange(a);this._adjustInstDate(a);if(a.input)a.input.val(e?"":this._formatDate(a))},_getDate:function(a){return!a.currentYear||a.input&&a.input.val()==""?null:this._daylightSavingAdjust(new Date(a.currentYear,a.currentMonth,a.currentDay))},_generateHTML:function(a){var b=new Date;b=this._daylightSavingAdjust(new Date(b.getFullYear(),b.getMonth(),b.getDate()));var c=this._get(a,"isRTL"),e=this._get(a,"showButtonPanel"),f=this._get(a,"hideIfNoPrevNext"),h=this._get(a,"navigationAsDateFormat"),i=this._getNumberOfMonths(a),g=this._get(a,"showCurrentAtPos"),j=this._get(a,"stepMonths"),l=i[0]!=1||i[1]!=1,u=this._daylightSavingAdjust(!a.currentDay?new Date(9999,9,9):new Date(a.currentYear,a.currentMonth,a.currentDay)),k=this._getMinMaxDate(a,"min"),o=this._getMinMaxDate(a,"max");g=a.drawMonth-g;var m=a.drawYear;if(g<0){g+=12;m--}if(o){var n=this._daylightSavingAdjust(new Date(o.getFullYear(),o.getMonth()-i[0]*i[1]+1,o.getDate()));for(n=k&&n<k?k:n;this._daylightSavingAdjust(new Date(m,g,1))>n;){g--;if(g<0){g=11;m--}}}a.drawMonth=g;a.drawYear=m;n=this._get(a,"prevText");n=!h?n:this.formatDate(n,this._daylightSavingAdjust(new Date(m,g-j,1)),this._getFormatConfig(a));n=this._canAdjustMonth(a,-1,m,g)?'<a class="ui-datepicker-prev ui-corner-all" onclick="DP_jQuery_'+B+".datepicker._adjustDate('#"+a.id+"', -"+j+", 'M');\" title=\""+n+'"><span class="ui-icon ui-icon-circle-triangle-'+(c?"e":"w")+'">'+n+"</span></a>":f?"":'<a class="ui-datepicker-prev ui-corner-all ui-state-disabled" title="'+n+'"><span class="ui-icon ui-icon-circle-triangle-'+(c?"e":"w")+'">'+n+"</span></a>";var s=this._get(a,"nextText");s=!h?s:this.formatDate(s,this._daylightSavingAdjust(new Date(m,g+j,1)),this._getFormatConfig(a));f=this._canAdjustMonth(a,+1,m,g)?'<a class="ui-datepicker-next ui-corner-all" onclick="DP_jQuery_'+B+".datepicker._adjustDate('#"+a.id+"', +"+j+", 'M');\" title=\""+s+'"><span class="ui-icon ui-icon-circle-triangle-'+(c?"w":"e")+'">'+s+"</span></a>":f?"":'<a class="ui-datepicker-next ui-corner-all ui-state-disabled" title="'+s+'"><span class="ui-icon ui-icon-circle-triangle-'+(c?"w":"e")+'">'+s+"</span></a>";j=this._get(a,"currentText");s=this._get(a,"gotoCurrent")&&a.currentDay?u:b;j=!h?j:this.formatDate(j,s,this._getFormatConfig(a));h=!a.inline?'<button type="button" class="ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all" onclick="DP_jQuery_'+B+'.datepicker._hideDatepicker();">'+this._get(a,"closeText")+"</button>":"";e=e?'<div class="ui-datepicker-buttonpane ui-widget-content">'+(c?h:"")+(this._isInRange(a,s)?'<button type="button" class="ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all" onclick="DP_jQuery_'+
B+".datepicker._gotoToday('#"+a.id+"');\">"+j+"</button>":"")+(c?"":h)+"</div>":"";h=parseInt(this._get(a,"firstDay"),10);h=isNaN(h)?0:h;j=this._get(a,"showWeek");s=this._get(a,"dayNames");this._get(a,"dayNamesShort");var q=this._get(a,"dayNamesMin"),A=this._get(a,"monthNames"),v=this._get(a,"monthNamesShort"),p=this._get(a,"beforeShowDay"),D=this._get(a,"showOtherMonths"),K=this._get(a,"selectOtherMonths");this._get(a,"calculateWeek");for(var E=this._getDefaultDate(a),w="",x=0;x<i[0];x++){var O="";this.maxRows=4;for(var G=0;G<i[1];G++){var P=this._daylightSavingAdjust(new Date(m,g,a.selectedDay)),t=" ui-corner-all",y="";if(l){y+='<div class="ui-datepicker-group';if(i[1]>1)switch(G){case 0:y+=" ui-datepicker-group-first";t=" ui-corner-"+(c?"right":"left");break;case i[1]-1:y+=" ui-datepicker-group-last";t=" ui-corner-"+(c?"left":"right");break;default:y+=" ui-datepicker-group-middle";t="";break}y+='">'}y+='<div class="ui-datepicker-header ui-widget-header ui-helper-clearfix'+t+'">'+(/all|left/.test(t)&&x==0?c?f:n:"")+(/all|right/.test(t)&&x==0?c?n:f:"")+this._generateMonthYearHeader(a,g,m,k,o,x>0||G>0,A,v)+'</div><table class="ui-datepicker-calendar"><thead><tr>';var z=j?'<th class="ui-datepicker-week-col">'+this._get(a,"weekHeader")+"</th>":"";for(t=0;t<7;t++){var r=(t+h)%7;z+="<th"+((t+h+6)%7>=5?' class="ui-datepicker-week-end"':"")+'><span title="'+s[r]+'">'+q[r]+"</span></th>"}y+=z+"</tr></thead><tbody>";z=this._getDaysInMonth(m,g);if(m==a.selectedYear&&g==a.selectedMonth)a.selectedDay=Math.min(a.selectedDay,z);t=(this._getFirstDayOfMonth(m,g)-h+7)%7;z=Math.ceil((t+z)/7);this.maxRows=z=l?this.maxRows>z?this.maxRows:z:z;r=this._daylightSavingAdjust(new Date(m,g,1-t));for(var Q=0;Q<z;Q++){y+="<tr>";var R=!j?"":'<td class="ui-datepicker-week-col">'+this._get(a,"calculateWeek")(r)+"</td>";for(t=0;t<7;t++){var I=p?p.apply(a.input?a.input[0]:null,[r]):[true,""],F=r.getMonth()!=g,L=F&&!K||!I[0]||k&&r<k||o&&r>o;R+='<td class="'+((t+h+6)%7>=5?" ui-datepicker-week-end":"")+(F?" ui-datepicker-other-month":"")+(r.getTime()==P.getTime()&&g==a.selectedMonth&&a._keyEvent||E.getTime()==r.getTime()&&E.getTime()==P.getTime()?" "+this._dayOverClass:"")+(L?" "+this._unselectableClass+" ui-state-disabled":"")+(F&&!D?"":" "+I[1]+(r.getTime()==u.getTime()?" "+this._currentClass:"")+(r.getTime()==b.getTime()?" ui-datepicker-today":""))+'"'+((!F||D)&&I[2]?' title=" title '+I[2]+'"':"")+(L?"":' onclick="DP_jQuery_'+B+".datepicker._selectDay('#"+a.id+"',"+r.getMonth()+","+r.getFullYear()+', this);return false;"')+">"+(F&&!D?" ":L?'<span class="ui-state-default">'+
r.getDate()+"</span>":'<a class="ui-state-default'+(r.getTime()==b.getTime()?" ui-state-highlight":"")+(r.getTime()==u.getTime()?" ui-state-active":"")+(F?" ui-priority-secondary":"")+'" href="#">'+r.getDate()+"</a>")+"</td>";r.setDate(r.getDate()+1);r=this._daylightSavingAdjust(r)}y+=R+"</tr>"}g++;if(g>11){g=0;m++}y+="</tbody></table>"+(l?"</div>"+(i[0]>0&&G==i[1]-1?'<div class="ui-datepicker-row-break"></div>':""):"");O+=y}w+=O}w+=e+(d.browser.msie&&parseInt(d.browser.version,10)<7&&!a.inline?'<iframe src="javascript:false;" class="ui-datepicker-cover" frameborder="0"></iframe>':"");a._keyEvent=false;return w},_generateMonthYearHeader:function(a,b,c,e,f,h,i,g){var j=this._get(a,"changeMonth"),l=this._get(a,"changeYear"),u=this._get(a,"showMonthAfterYear"),k='<div class="ui-datepicker-title">',o="";if(h||!j)o+='<span class="ui-datepicker-month">'+i[b]+"</span>";else{i=e&&e.getFullYear()==c;var m=f&&f.getFullYear()==c;o+='<select class="ui-datepicker-month" onchange="DP_jQuery_'+B+".datepicker._selectMonthYear('#"+a.id+"', this, 'M');\" >";for(var n=0;n<12;n++)if((!i||n>=e.getMonth())&&(!m||n<=f.getMonth()))o+='<option value="'+n+'"'+(n==b?' selected="selected"':"")+">"+g[n]+"</option>";o+="</select>"}u||(k+=o+(h||!(j&&l)?" ":""));if(!a.yearshtml){a.yearshtml="";if(h||!l)k+='<span class="ui-datepicker-year">'+c+"</span>";else{g=this._get(a,"yearRange").split(":");var s=(new Date).getFullYear();i=function(q){q=q.match(/c[+-].*/)?c+parseInt(q.substring(1),10):q.match(/[+-].*/)?s+parseInt(q,10):parseInt(q,10);return isNaN(q)?s:q};b=i(g[0]);g=Math.max(b,i(g[1]||""));b=e?Math.max(b,e.getFullYear()):b;g=f?Math.min(g,f.getFullYear()):g;for(a.yearshtml+='<select class="ui-datepicker-year" onchange="DP_jQuery_'+B+".datepicker._selectMonthYear('#"+a.id+"', this, 'Y');\" >";b<=g;b++)a.yearshtml+='<option value="'+b+'"'+(b==c?' selected="selected"':"")+">"+b+"</option>";a.yearshtml+="</select>";k+=a.yearshtml;a.yearshtml=null}}k+=this._get(a,"yearSuffix");if(u)k+=(h||!(j&&l)?" ":"")+o;k+="</div>";return k},_adjustInstDate:function(a,b,c){var e=a.drawYear+(c=="Y"?b:0),f=a.drawMonth+
(c=="M"?b:0);b=Math.min(a.selectedDay,this._getDaysInMonth(e,f))+(c=="D"?b:0);e=this._restrictMinMax(a,this._daylightSavingAdjust(new Date(e,f,b)));a.selectedDay=e.getDate();a.drawMonth=a.selectedMonth=e.getMonth();a.drawYear=a.selectedYear=e.getFullYear();if(c=="M"||c=="Y")this._notifyChange(a)},_restrictMinMax:function(a,b){var c=this._getMinMaxDate(a,"min");a=this._getMinMaxDate(a,"max");b=c&&b<c?c:b;return b=a&&b>a?a:b},_notifyChange:function(a){var b=this._get(a,"onChangeMonthYear");if(b)b.apply(a.input?a.input[0]:null,[a.selectedYear,a.selectedMonth+1,a])},_getNumberOfMonths:function(a){a=this._get(a,"numberOfMonths");return a==null?[1,1]:typeof a=="number"?[1,a]:a},_getMinMaxDate:function(a,b){return this._determineDate(a,this._get(a,b+"Date"),null)},_getDaysInMonth:function(a,b){return 32-this._daylightSavingAdjust(new Date(a,b,32)).getDate()},_getFirstDayOfMonth:function(a,b){return(new Date(a,b,1)).getDay()},_canAdjustMonth:function(a,b,c,e){var f=this._getNumberOfMonths(a);c=this._daylightSavingAdjust(new Date(c,e+(b<0?b:f[0]*f[1]),1));b<0&&c.setDate(this._getDaysInMonth(c.getFullYear(),c.getMonth()));return this._isInRange(a,c)},_isInRange:function(a,b){var c=this._getMinMaxDate(a,"min");a=this._getMinMaxDate(a,"max");return(!c||b.getTime()>=c.getTime())&&(!a||b.getTime()<=a.getTime())},_getFormatConfig:function(a){var b=this._get(a,"shortYearCutoff");b=typeof b!="string"?b:(new Date).getFullYear()%100+parseInt(b,10);return{shortYearCutoff:b,dayNamesShort:this._get(a,"dayNamesShort"),dayNames:this._get(a,"dayNames"),monthNamesShort:this._get(a,"monthNamesShort"),monthNames:this._get(a,"monthNames")}},_formatDate:function(a,b,c,e){if(!b){a.currentDay=a.selectedDay;a.currentMonth=a.selectedMonth;a.currentYear=a.selectedYear}b=b?typeof b=="object"?b:this._daylightSavingAdjust(new Date(e,c,b)):this._daylightSavingAdjust(new Date(a.currentYear,a.currentMonth,a.currentDay));return this.formatDate(this._get(a,"dateFormat"),b,this._getFormatConfig(a))}});d.fn.datepicker=function(a){if(!this.length)return this;if(!d.datepicker.initialized){d(document).mousedown(d.datepicker._checkExternalClick).find("body").append(d.datepicker.dpDiv);d.datepicker.initialized=true}var b=Array.prototype.slice.call(arguments,1);if(typeof a=="string"&&(a=="isDisabled"||a=="getDate"||a=="widget"))return d.datepicker["_"+a+"Datepicker"].apply(d.datepicker,[this[0]].concat(b));if(a=="option"&&arguments.length==2&&typeof arguments[1]=="string")return d.datepicker["_"+a+"Datepicker"].apply(d.datepicker,[this[0]].concat(b));return this.each(function(){typeof a=="string"?d.datepicker["_"+a+"Datepicker"].apply(d.datepicker,[this].concat(b)):d.datepicker._attachDatepicker(this,a)})};d.datepicker=new M;d.datepicker.initialized=false;d.datepicker.uuid=(new Date).getTime();d.datepicker.version="1.8.16";window["DP_jQuery_"+B]=d})(jQuery);;(function($){$.extend($.ui,{timepicker:{version:"0.9.9"}});function Timepicker(){this.regional=[];this.regional['']={currentText:'Now',closeText:'Done',ampm:false,amNames:['AM','A'],pmNames:['PM','P'],timeFormat:'hh:mm ',timeSuffix:'',timeOnlyTitle:'Choose Time',timeText:'Time',hourText:'Hour',minuteText:'Minute',secondText:'Second',millisecText:'Millisecond',timezoneText:'Time Zone'};this._defaults={showButtonPanel:true,timeOnly:false,showHour:true,showMinute:true,showSecond:false,showMillisec:false,showTimezone:false,showTime:true,stepHour:1,stepMinute:1,stepSecond:1,stepMillisec:1,hour:0,minute:0,second:0,millisec:0,timezone:'+0000',hourMin:0,minuteMin:0,secondMin:0,millisecMin:0,hourMax:23,minuteMax:59,secondMax:59,millisecMax:999,minDateTime:null,maxDateTime:null,onSelect:null,hourGrid:0,minuteGrid:0,secondGrid:0,millisecGrid:0,alwaysSetTime:true,separator:' ',altFieldTimeOnly:true,showTimepicker:true,timezoneIso8609:false,timezoneList:null,addSliderAccess:false,sliderAccessArgs:null};$.extend(this._defaults,this.regional['']);};$.extend(Timepicker.prototype,{$input:null,$altInput:null,$timeObj:null,inst:null,hour_slider:null,minute_slider:null,second_slider:null,millisec_slider:null,timezone_select:null,hour:0,minute:0,second:0,millisec:0,timezone:'+0000',hourMinOriginal:null,minuteMinOriginal:null,secondMinOriginal:null,millisecMinOriginal:null,hourMaxOriginal:null,minuteMaxOriginal:null,secondMaxOriginal:null,millisecMaxOriginal:null,ampm:'',formattedDate:'',formattedTime:'',formattedDateTime:'',timezoneList:null,setDefaults:function(settings){extendRemove(this._defaults,settings||{});return this;},_newInst:function($input,o){var tp_inst=new Timepicker(),inlineSettings={};for(var attrName in this._defaults){var attrValue=$input.attr('time:'+attrName);if(attrValue){try{inlineSettings[attrName]=eval(attrValue);}catch(err){inlineSettings[attrName]=attrValue;}}}




tp_inst._defaults=$.extend({},this._defaults,inlineSettings,o,{beforeShow:function(input,dp_inst){if($.isFunction(o.beforeShow))
return o.beforeShow(input,dp_inst,tp_inst);},onChangeMonthYear:function(year,month,dp_inst){tp_inst._updateDateTime(dp_inst);if($.isFunction(o.onChangeMonthYear))
o.onChangeMonthYear.call($input[0],year,month,dp_inst,tp_inst);},onClose:function(dateText,dp_inst){if(tp_inst.timeDefined===true&&$input.val()!='')
tp_inst._updateDateTime(dp_inst);if($.isFunction(o.onClose))
o.onClose.call($input[0],dateText,dp_inst,tp_inst);},timepicker:tp_inst});tp_inst.amNames=$.map(tp_inst._defaults.amNames,function(val){return val.toUpperCase()});tp_inst.pmNames=$.map(tp_inst._defaults.pmNames,function(val){return val.toUpperCase()});if(tp_inst._defaults.timezoneList===null){var timezoneList=[];for(var i=-11;i<=12;i++)
timezoneList.push((i>=0?'+':'-')+('0'+Math.abs(i).toString()).slice(-2)+'00');if(tp_inst._defaults.timezoneIso8609)
timezoneList=$.map(timezoneList,function(val){return val=='+0000'?'Z':(val.substring(0,3)+':'+val.substring(3));});tp_inst._defaults.timezoneList=timezoneList;}
tp_inst.hour=tp_inst._defaults.hour;tp_inst.minute=tp_inst._defaults.minute;tp_inst.second=tp_inst._defaults.second;tp_inst.millisec=tp_inst._defaults.millisec;tp_inst.ampm='';tp_inst.$input=$input;if(o.altField)
tp_inst.$altInput=$(o.altField).css({cursor:'pointer'}).focus(function(){$input.trigger("focus");});if(tp_inst._defaults.minDate==0||tp_inst._defaults.minDateTime==0)
{tp_inst._defaults.minDate=new Date();}
if(tp_inst._defaults.maxDate==0||tp_inst._defaults.maxDateTime==0)
{tp_inst._defaults.maxDate=new Date();}
if(tp_inst._defaults.minDate!==undefined&&tp_inst._defaults.minDate instanceof Date)
tp_inst._defaults.minDateTime=new Date(tp_inst._defaults.minDate.getTime());if(tp_inst._defaults.minDateTime!==undefined&&tp_inst._defaults.minDateTime instanceof Date)
tp_inst._defaults.minDate=new Date(tp_inst._defaults.minDateTime.getTime());if(tp_inst._defaults.maxDate!==undefined&&tp_inst._defaults.maxDate instanceof Date)
tp_inst._defaults.maxDateTime=new Date(tp_inst._defaults.maxDate.getTime());if(tp_inst._defaults.maxDateTime!==undefined&&tp_inst._defaults.maxDateTime instanceof Date)
tp_inst._defaults.maxDate=new Date(tp_inst._defaults.maxDateTime.getTime());return tp_inst;},_addTimePicker:function(dp_inst){var currDT=(this.$altInput&&this._defaults.altFieldTimeOnly)?this.$input.val()+' '+this.$altInput.val():this.$input.val();this.timeDefined=this._parseTime(currDT);this._limitMinMaxDateTime(dp_inst,false);this._injectTimePicker();},_parseTime:function(timeString,withDate){var regstr=this._defaults.timeFormat.toString().replace(/h{1,2}/ig,'(\\d?\\d)').replace(/m{1,2}/ig,'(\\d?\\d)').replace(/s{1,2}/ig,'(\\d?\\d)').replace(/l{1}/ig,'(\\d?\\d?\\d)').replace(/t{1,2}/ig,this._getPatternAmpm()).replace(/z{1}/ig,'(z|[-+]\\d\\d:?\\d\\d)?').replace(/\s/g,'\\s?')+this._defaults.timeSuffix+'$',order=this._getFormatPositions(),ampm='',treg;if(!this.inst)this.inst=$.datepicker._getInst(this.$input[0]);if(withDate||!this._defaults.timeOnly){var dp_dateFormat=$.datepicker._get(this.inst,'dateFormat');var specials=new RegExp("[.*+?|()\\[\\]{}\\\\]","g");regstr='^.{'+dp_dateFormat.length+',}?'+this._defaults.separator.replace(specials,"\\$&")+regstr;}
treg=timeString.match(new RegExp(regstr,'i'));if(treg){if(order.t!==-1){if(treg[order.t]===undefined||treg[order.t].length===0){ampm='';this.ampm='';}else{ampm=$.inArray(treg[order.t].toUpperCase(),this.amNames)!==-1?'AM':'PM';this.ampm=this._defaults[ampm=='AM'?'amNames':'pmNames'][0];}}
if(order.h!==-1){if(ampm=='AM'&&treg[order.h]=='12')
this.hour=0;else if(ampm=='PM'&&treg[order.h]!='12')
this.hour=(parseFloat(treg[order.h])+12).toFixed(0);else this.hour=Number(treg[order.h]);}
if(order.m!==-1)this.minute=Number(treg[order.m]);if(order.s!==-1)this.second=Number(treg[order.s]);if(order.l!==-1)this.millisec=Number(treg[order.l]);if(order.z!==-1&&treg[order.z]!==undefined){var tz=treg[order.z].toUpperCase();switch(tz.length){case 1:tz=this._defaults.timezoneIso8609?'Z':'+0000';break;case 5:if(this._defaults.timezoneIso8609)
tz=tz.substring(1)=='0000'?'Z':tz.substring(0,3)+':'+tz.substring(3);break;case 6:if(!this._defaults.timezoneIso8609)
tz=tz=='Z'||tz.substring(1)=='00:00'?'+0000':tz.replace(/:/,'');else if(tz.substring(1)=='00:00')
tz='Z';break;}
this.timezone=tz;}
return true;}
return false;},_getPatternAmpm:function(){var markers=[];o=this._defaults;if(o.amNames)
$.merge(markers,o.amNames);if(o.pmNames)
$.merge(markers,o.pmNames);markers=$.map(markers,function(val){return val.replace(/[.*+?|()\[\]{}\\]/g,'\\$&')});return'('+markers.join('|')+')?';},_getFormatPositions:function(){var finds=this._defaults.timeFormat.toLowerCase().match(/(h{1,2}|m{1,2}|s{1,2}|l{1}|t{1,2}|z)/g),orders={h:-1,m:-1,s:-1,l:-1,t:-1,z:-1};if(finds)
for(var i=0;i<finds.length;i++)
if(orders[finds[i].toString().charAt(0)]==-1)
orders[finds[i].toString().charAt(0)]=i+1;return orders;},_injectTimePicker:function(){var $dp=this.inst.dpDiv,o=this._defaults,tp_inst=this,hourMax=parseInt((o.hourMax-((o.hourMax-o.hourMin)%o.stepHour)),10),minMax=parseInt((o.minuteMax-((o.minuteMax-o.minuteMin)%o.stepMinute)),10),secMax=parseInt((o.secondMax-((o.secondMax-o.secondMin)%o.stepSecond)),10),millisecMax=parseInt((o.millisecMax-((o.millisecMax-o.millisecMin)%o.stepMillisec)),10),dp_id=this.inst.id.toString().replace(/([^A-Za-z0-9_])/g,'');if($dp.find("div#ui-timepicker-div-"+dp_id).length===0&&o.showTimepicker){var noDisplay=' style="display:none;"',html='<div class="ui-timepicker-div" id="ui-timepicker-div-'+dp_id+'"><dl>'+'<dt class="ui_tpicker_time_label" id="ui_tpicker_time_label_'+dp_id+'"'+
((o.showTime)?'':noDisplay)+'>'+o.timeText+'</dt>'+'<dd class="ui_tpicker_time" id="ui_tpicker_time_'+dp_id+'"'+
((o.showTime)?'':noDisplay)+'></dd>'+'<dt class="ui_tpicker_hour_label" id="ui_tpicker_hour_label_'+dp_id+'"'+
((o.showHour)?'':noDisplay)+'>'+o.hourText+'</dt>',hourGridSize=0,minuteGridSize=0,secondGridSize=0,millisecGridSize=0,size;html+='<dd class="ui_tpicker_hour"><div id="ui_tpicker_hour_'+dp_id+'"'+
((o.showHour)?'':noDisplay)+'></div>';if(o.showHour&&o.hourGrid>0){html+='<div style="padding-left: 1px"><table class="ui-tpicker-grid-label"><tr>';for(var h=o.hourMin;h<=hourMax;h+=parseInt(o.hourGrid,10)){hourGridSize++;var tmph=(o.ampm&&h>12)?h-12:h;if(tmph<10)tmph='0'+tmph;if(o.ampm){if(h==0)tmph=12+'a';else if(h<12)tmph+='a';else tmph+='p';}
html+='<td>'+tmph+'</td>';}
html+='</tr></table></div>';}
html+='</dd>';html+='<dt class="ui_tpicker_minute_label" id="ui_tpicker_minute_label_'+dp_id+'"'+
((o.showMinute)?'':noDisplay)+'>'+o.minuteText+'</dt>'+'<dd class="ui_tpicker_minute"><div id="ui_tpicker_minute_'+dp_id+'"'+
((o.showMinute)?'':noDisplay)+'></div>';if(o.showMinute&&o.minuteGrid>0){html+='<div style="padding-left: 1px"><table class="ui-tpicker-grid-label"><tr>';for(var m=o.minuteMin;m<=minMax;m+=parseInt(o.minuteGrid,10)){minuteGridSize++;html+='<td>'+((m<10)?'0':'')+m+'</td>';}
html+='</tr></table></div>';}
html+='</dd>';html+='<dt class="ui_tpicker_second_label" id="ui_tpicker_second_label_'+dp_id+'"'+
((o.showSecond)?'':noDisplay)+'>'+o.secondText+'</dt>'+'<dd class="ui_tpicker_second"><div id="ui_tpicker_second_'+dp_id+'"'+
((o.showSecond)?'':noDisplay)+'></div>';if(o.showSecond&&o.secondGrid>0){html+='<div style="padding-left: 1px"><table><tr>';for(var s=o.secondMin;s<=secMax;s+=parseInt(o.secondGrid,10)){secondGridSize++;html+='<td>'+((s<10)?'0':'')+s+'</td>';}
html+='</tr></table></div>';}
html+='</dd>';html+='<dt class="ui_tpicker_millisec_label" id="ui_tpicker_millisec_label_'+dp_id+'"'+
((o.showMillisec)?'':noDisplay)+'>'+o.millisecText+'</dt>'+'<dd class="ui_tpicker_millisec"><div id="ui_tpicker_millisec_'+dp_id+'"'+
((o.showMillisec)?'':noDisplay)+'></div>';if(o.showMillisec&&o.millisecGrid>0){html+='<div style="padding-left: 1px"><table><tr>';for(var l=o.millisecMin;l<=millisecMax;l+=parseInt(o.millisecGrid,10)){millisecGridSize++;html+='<td>'+((l<10)?'0':'')+l+'</td>';}
html+='</tr></table></div>';}
html+='</dd>';html+='<dt class="ui_tpicker_timezone_label" id="ui_tpicker_timezone_label_'+dp_id+'"'+
((o.showTimezone)?'':noDisplay)+'>'+o.timezoneText+'</dt>';html+='<dd class="ui_tpicker_timezone" id="ui_tpicker_timezone_'+dp_id+'"'+
((o.showTimezone)?'':noDisplay)+'></dd>';html+='</dl></div>';$tp=$(html);if(o.timeOnly===true){$tp.prepend('<div class="ui-widget-header ui-helper-clearfix ui-corner-all">'+'<div class="ui-datepicker-title">'+o.timeOnlyTitle+'</div>'+'</div>');$dp.find('.ui-datepicker-header, .ui-datepicker-calendar').hide();}
this.hour_slider=$tp.find('#ui_tpicker_hour_'+dp_id).slider({orientation:"horizontal",value:this.hour,min:o.hourMin,max:hourMax,step:o.stepHour,slide:function(event,ui){tp_inst.hour_slider.slider("option","value",ui.value);tp_inst._onTimeChange();}});this.minute_slider=$tp.find('#ui_tpicker_minute_'+dp_id).slider({orientation:"horizontal",value:this.minute,min:o.minuteMin,max:minMax,step:o.stepMinute,slide:function(event,ui){tp_inst.minute_slider.slider("option","value",ui.value);tp_inst._onTimeChange();}});this.second_slider=$tp.find('#ui_tpicker_second_'+dp_id).slider({orientation:"horizontal",value:this.second,min:o.secondMin,max:secMax,step:o.stepSecond,slide:function(event,ui){tp_inst.second_slider.slider("option","value",ui.value);tp_inst._onTimeChange();}});this.millisec_slider=$tp.find('#ui_tpicker_millisec_'+dp_id).slider({orientation:"horizontal",value:this.millisec,min:o.millisecMin,max:millisecMax,step:o.stepMillisec,slide:function(event,ui){tp_inst.millisec_slider.slider("option","value",ui.value);tp_inst._onTimeChange();}});this.timezone_select=$tp.find('#ui_tpicker_timezone_'+dp_id).append('<select></select>').find("select");$.fn.append.apply(this.timezone_select,$.map(o.timezoneList,function(val,idx){return $("<option />").val(typeof val=="object"?val.value:val).text(typeof val=="object"?val.label:val);}));this.timezone_select.val((typeof this.timezone!="undefined"&&this.timezone!=null&&this.timezone!="")?this.timezone:o.timezone);this.timezone_select.change(function(){tp_inst._onTimeChange();});if(o.showHour&&o.hourGrid>0){size=100*hourGridSize*o.hourGrid/(hourMax-o.hourMin);$tp.find(".ui_tpicker_hour table").css({width:size+"%",marginLeft:(size/(-2*hourGridSize))+"%",borderCollapse:'collapse'}).find("td").each(function(index){$(this).click(function(){var h=$(this).html();if(o.ampm){var ap=h.substring(2).toLowerCase(),aph=parseInt(h.substring(0,2),10);if(ap=='a'){if(aph==12)h=0;else h=aph;}else if(aph==12)h=12;else h=aph+12;}
tp_inst.hour_slider.slider("option","value",h);tp_inst._onTimeChange();tp_inst._onSelectHandler();}).css({cursor:'pointer',width:(100/hourGridSize)+'%',textAlign:'center',overflow:'hidden'});});}
if(o.showMinute&&o.minuteGrid>0){size=100*minuteGridSize*o.minuteGrid/(minMax-o.minuteMin);$tp.find(".ui_tpicker_minute table").css({width:size+"%",marginLeft:(size/(-2*minuteGridSize))+"%",borderCollapse:'collapse'}).find("td").each(function(index){$(this).click(function(){tp_inst.minute_slider.slider("option","value",$(this).html());tp_inst._onTimeChange();tp_inst._onSelectHandler();}).css({cursor:'pointer',width:(100/minuteGridSize)+'%',textAlign:'center',overflow:'hidden'});});}
if(o.showSecond&&o.secondGrid>0){$tp.find(".ui_tpicker_second table").css({width:size+"%",marginLeft:(size/(-2*secondGridSize))+"%",borderCollapse:'collapse'}).find("td").each(function(index){$(this).click(function(){tp_inst.second_slider.slider("option","value",$(this).html());tp_inst._onTimeChange();tp_inst._onSelectHandler();}).css({cursor:'pointer',width:(100/secondGridSize)+'%',textAlign:'center',overflow:'hidden'});});}
if(o.showMillisec&&o.millisecGrid>0){$tp.find(".ui_tpicker_millisec table").css({width:size+"%",marginLeft:(size/(-2*millisecGridSize))+"%",borderCollapse:'collapse'}).find("td").each(function(index){$(this).click(function(){tp_inst.millisec_slider.slider("option","value",$(this).html());tp_inst._onTimeChange();tp_inst._onSelectHandler();}).css({cursor:'pointer',width:(100/millisecGridSize)+'%',textAlign:'center',overflow:'hidden'});});}
var $buttonPanel=$dp.find('.ui-datepicker-buttonpane');if($buttonPanel.length)$buttonPanel.before($tp);else $dp.append($tp);this.$timeObj=$tp.find('#ui_tpicker_time_'+dp_id);if(this.inst!==null){var timeDefined=this.timeDefined;this._onTimeChange();this.timeDefined=timeDefined;}
var onSelectDelegate=function(){tp_inst._onSelectHandler();};this.hour_slider.bind('slidestop',onSelectDelegate);this.minute_slider.bind('slidestop',onSelectDelegate);this.second_slider.bind('slidestop',onSelectDelegate);this.millisec_slider.bind('slidestop',onSelectDelegate);if(this._defaults.addSliderAccess){var sliderAccessArgs=this._defaults.sliderAccessArgs;setTimeout(function(){if($tp.find('.ui-slider-access').length==0){$tp.find('.ui-slider:visible').sliderAccess(sliderAccessArgs);var sliderAccessWidth=$tp.find('.ui-slider-access:eq(0)').outerWidth(true);if(sliderAccessWidth){$tp.find('table:visible').each(function(){var $g=$(this),oldWidth=$g.outerWidth(),oldMarginLeft=$g.css('marginLeft').toString().replace('%',''),newWidth=oldWidth-sliderAccessWidth,newMarginLeft=((oldMarginLeft*newWidth)/oldWidth)+'%';$g.css({width:newWidth,marginLeft:newMarginLeft});});}}},0);}}},_limitMinMaxDateTime:function(dp_inst,adjustSliders){var o=this._defaults,dp_date=new Date(dp_inst.selectedYear,dp_inst.selectedMonth,dp_inst.selectedDay);if(!this._defaults.showTimepicker)return;if($.datepicker._get(dp_inst,'minDateTime')!==null&&$.datepicker._get(dp_inst,'minDateTime')!==undefined&&dp_date){var minDateTime=$.datepicker._get(dp_inst,'minDateTime'),minDateTimeDate=new Date(minDateTime.getFullYear(),minDateTime.getMonth(),minDateTime.getDate(),0,0,0,0);if(this.hourMinOriginal===null||this.minuteMinOriginal===null||this.secondMinOriginal===null||this.millisecMinOriginal===null){this.hourMinOriginal=o.hourMin;this.minuteMinOriginal=o.minuteMin;this.secondMinOriginal=o.secondMin;this.millisecMinOriginal=o.millisecMin;}
if(dp_inst.settings.timeOnly||minDateTimeDate.getTime()==dp_date.getTime()){this._defaults.hourMin=minDateTime.getHours();if(this.hour<=this._defaults.hourMin){this.hour=this._defaults.hourMin;this._defaults.minuteMin=minDateTime.getMinutes();if(this.minute<=this._defaults.minuteMin){this.minute=this._defaults.minuteMin;this._defaults.secondMin=minDateTime.getSeconds();}else if(this.second<=this._defaults.secondMin){this.second=this._defaults.secondMin;this._defaults.millisecMin=minDateTime.getMilliseconds();}else{if(this.millisec<this._defaults.millisecMin)
this.millisec=this._defaults.millisecMin;this._defaults.millisecMin=this.millisecMinOriginal;}}else{this._defaults.minuteMin=this.minuteMinOriginal;this._defaults.secondMin=this.secondMinOriginal;this._defaults.millisecMin=this.millisecMinOriginal;}}else{this._defaults.hourMin=this.hourMinOriginal;this._defaults.minuteMin=this.minuteMinOriginal;this._defaults.secondMin=this.secondMinOriginal;this._defaults.millisecMin=this.millisecMinOriginal;}}
if($.datepicker._get(dp_inst,'maxDateTime')!==null&&$.datepicker._get(dp_inst,'maxDateTime')!==undefined&&dp_date){var maxDateTime=$.datepicker._get(dp_inst,'maxDateTime'),maxDateTimeDate=new Date(maxDateTime.getFullYear(),maxDateTime.getMonth(),maxDateTime.getDate(),0,0,0,0);if(this.hourMaxOriginal===null||this.minuteMaxOriginal===null||this.secondMaxOriginal===null){this.hourMaxOriginal=o.hourMax;this.minuteMaxOriginal=o.minuteMax;this.secondMaxOriginal=o.secondMax;this.millisecMaxOriginal=o.millisecMax;}
if(dp_inst.settings.timeOnly||maxDateTimeDate.getTime()==dp_date.getTime()){this._defaults.hourMax=maxDateTime.getHours();if(this.hour>=this._defaults.hourMax){this.hour=this._defaults.hourMax;this._defaults.minuteMax=maxDateTime.getMinutes();if(this.minute>=this._defaults.minuteMax){this.minute=this._defaults.minuteMax;this._defaults.secondMax=maxDateTime.getSeconds();}else if(this.second>=this._defaults.secondMax){this.second=this._defaults.secondMax;this._defaults.millisecMax=maxDateTime.getMilliseconds();}else{if(this.millisec>this._defaults.millisecMax)this.millisec=this._defaults.millisecMax;this._defaults.millisecMax=this.millisecMaxOriginal;}}else{this._defaults.minuteMax=this.minuteMaxOriginal;this._defaults.secondMax=this.secondMaxOriginal;this._defaults.millisecMax=this.millisecMaxOriginal;}}else{this._defaults.hourMax=this.hourMaxOriginal;this._defaults.minuteMax=this.minuteMaxOriginal;this._defaults.secondMax=this.secondMaxOriginal;this._defaults.millisecMax=this.millisecMaxOriginal;}}
if(adjustSliders!==undefined&&adjustSliders===true){var hourMax=parseInt((this._defaults.hourMax-((this._defaults.hourMax-this._defaults.hourMin)%this._defaults.stepHour)),10),minMax=parseInt((this._defaults.minuteMax-((this._defaults.minuteMax-this._defaults.minuteMin)%this._defaults.stepMinute)),10),secMax=parseInt((this._defaults.secondMax-((this._defaults.secondMax-this._defaults.secondMin)%this._defaults.stepSecond)),10),millisecMax=parseInt((this._defaults.millisecMax-((this._defaults.millisecMax-this._defaults.millisecMin)%this._defaults.stepMillisec)),10);if(this.hour_slider)
this.hour_slider.slider("option",{min:this._defaults.hourMin,max:hourMax}).slider('value',this.hour);if(this.minute_slider)
this.minute_slider.slider("option",{min:this._defaults.minuteMin,max:minMax}).slider('value',this.minute);if(this.second_slider)
this.second_slider.slider("option",{min:this._defaults.secondMin,max:secMax}).slider('value',this.second);if(this.millisec_slider)
this.millisec_slider.slider("option",{min:this._defaults.millisecMin,max:millisecMax}).slider('value',this.millisec);}},_onTimeChange:function(){var hour=(this.hour_slider)?this.hour_slider.slider('value'):false,minute=(this.minute_slider)?this.minute_slider.slider('value'):false,second=(this.second_slider)?this.second_slider.slider('value'):false,millisec=(this.millisec_slider)?this.millisec_slider.slider('value'):false,timezone=(this.timezone_select)?this.timezone_select.val():false,o=this._defaults;if(typeof(hour)=='object')hour=false;if(typeof(minute)=='object')minute=false;if(typeof(second)=='object')second=false;if(typeof(millisec)=='object')millisec=false;if(typeof(timezone)=='object')timezone=false;if(hour!==false)hour=parseInt(hour,10);if(minute!==false)minute=parseInt(minute,10);if(second!==false)second=parseInt(second,10);if(millisec!==false)millisec=parseInt(millisec,10);var ampm=o[hour<12?'amNames':'pmNames'][0];var hasChanged=(hour!=this.hour||minute!=this.minute||second!=this.second||millisec!=this.millisec||(this.ampm.length>0&&(hour<12)!=($.inArray(this.ampm.toUpperCase(),this.amNames)!==-1))||timezone!=this.timezone);if(hasChanged){if(hour!==false)this.hour=hour;if(minute!==false)this.minute=minute;if(second!==false)this.second=second;if(millisec!==false)this.millisec=millisec;if(timezone!==false)this.timezone=timezone;if(!this.inst)this.inst=$.datepicker._getInst(this.$input[0]);this._limitMinMaxDateTime(this.inst,true);}
if(o.ampm)this.ampm=ampm;this.formattedTime=$.datepicker.formatTime(this._defaults.timeFormat,this,this._defaults);if(this.$timeObj)this.$timeObj.text(this.formattedTime+o.timeSuffix);this.timeDefined=true;if(hasChanged)this._updateDateTime();},_onSelectHandler:function(){var onSelect=this._defaults.onSelect;var inputEl=this.$input?this.$input[0]:null;if(onSelect&&inputEl){onSelect.apply(inputEl,[this.formattedDateTime,this]);}},_formatTime:function(time,format){time=time||{hour:this.hour,minute:this.minute,second:this.second,millisec:this.millisec,ampm:this.ampm,timezone:this.timezone};var tmptime=(format||this._defaults.timeFormat).toString();tmptime=$.datepicker.formatTime(tmptime,time,this._defaults);if(arguments.length)return tmptime;else this.formattedTime=tmptime;},_updateDateTime:function(dp_inst){dp_inst=this.inst||dp_inst;var dt=$.datepicker._daylightSavingAdjust(new Date(dp_inst.selectedYear,dp_inst.selectedMonth,dp_inst.selectedDay)),dateFmt=$.datepicker._get(dp_inst,'dateFormat'),formatCfg=$.datepicker._getFormatConfig(dp_inst),timeAvailable=dt!==null&&this.timeDefined;this.formattedDate=$.datepicker.formatDate(dateFmt,(dt===null?new Date():dt),formatCfg);var formattedDateTime=this.formattedDate;if(dp_inst.lastVal!==undefined&&(dp_inst.lastVal.length>0&&this.$input.val().length===0))
return;if(this._defaults.timeOnly===true){formattedDateTime=this.formattedTime;}else if(this._defaults.timeOnly!==true&&(this._defaults.alwaysSetTime||timeAvailable)){formattedDateTime+=this._defaults.separator+this.formattedTime+this._defaults.timeSuffix;}
this.formattedDateTime=formattedDateTime;if(!this._defaults.showTimepicker){this.$input.val(this.formattedDate);}else if(this.$altInput&&this._defaults.altFieldTimeOnly===true){this.$altInput.val(this.formattedTime);this.$input.val(this.formattedDate);}else if(this.$altInput){this.$altInput.val(formattedDateTime);this.$input.val(formattedDateTime);}else{this.$input.val(formattedDateTime);}
this.$input.trigger("change");}});$.fn.extend({timepicker:function(o){o=o||{};var tmp_args=arguments;if(typeof o=='object')tmp_args[0]=$.extend(o,{timeOnly:true});return $(this).each(function(){$.fn.datetimepicker.apply($(this),tmp_args);});},datetimepicker:function(o){o=o||{};var $input=this,tmp_args=arguments;if(typeof(o)=='string'){if(o=='getDate')
return $.fn.datepicker.apply($(this[0]),tmp_args);else
return this.each(function(){var $t=$(this);$t.datepicker.apply($t,tmp_args);});}
else
return this.each(function(){var $t=$(this);$t.datepicker($.timepicker._newInst($t,o)._defaults);});}});$.datepicker.formatTime=function(format,time,options){options=options||{};options=$.extend($.timepicker._defaults,options);time=$.extend({hour:0,minute:0,second:0,millisec:0,timezone:'+0000'},time);var tmptime=format;var ampmName=options['amNames'][0];var hour=parseInt(time.hour,10);if(options.ampm){if(hour>11){ampmName=options['pmNames'][0];if(hour>12)
hour=hour%12;}
if(hour===0)
hour=12;}
tmptime=tmptime.replace(/(?:hh?|mm?|ss?|[tT]{1,2}|[lz])/g,function(match){switch(match.toLowerCase()){case'hh':return('0'+hour).slice(-2);case'h':return hour;case'mm':return('0'+time.minute).slice(-2);case'm':return time.minute;case'ss':return('0'+time.second).slice(-2);case's':return time.second;case'l':return('00'+time.millisec).slice(-3);case'z':return time.timezone;case't':case'tt':if(options.ampm){if(match.length==1)
ampmName=ampmName.charAt(0);return match.charAt(0)=='T'?ampmName.toUpperCase():ampmName.toLowerCase();}
return'';}});tmptime=$.trim(tmptime);return tmptime;}
$.datepicker._base_selectDate=$.datepicker._selectDate;$.datepicker._selectDate=function(id,dateStr){var inst=this._getInst($(id)[0]),tp_inst=this._get(inst,'timepicker');if(tp_inst){tp_inst._limitMinMaxDateTime(inst,true);inst.inline=inst.stay_open=true;this._base_selectDate(id,dateStr);inst.inline=inst.stay_open=false;this._notifyChange(inst);this._updateDatepicker(inst);}
else this._base_selectDate(id,dateStr);};$.datepicker._base_updateDatepicker=$.datepicker._updateDatepicker;$.datepicker._updateDatepicker=function(inst){var input=inst.input[0];if($.datepicker._curInst&&$.datepicker._curInst!=inst&&$.datepicker._datepickerShowing&&$.datepicker._lastInput!=input){return;}
if(typeof(inst.stay_open)!=='boolean'||inst.stay_open===false){this._base_updateDatepicker(inst);var tp_inst=this._get(inst,'timepicker');if(tp_inst)tp_inst._addTimePicker(inst);}};$.datepicker._base_doKeyPress=$.datepicker._doKeyPress;$.datepicker._doKeyPress=function(event){var inst=$.datepicker._getInst(event.target),tp_inst=$.datepicker._get(inst,'timepicker');if(tp_inst){if($.datepicker._get(inst,'constrainInput')){var ampm=tp_inst._defaults.ampm,dateChars=$.datepicker._possibleChars($.datepicker._get(inst,'dateFormat')),datetimeChars=tp_inst._defaults.timeFormat.toString().replace(/[hms]/g,'').replace(/TT/g,ampm?'APM':'').replace(/Tt/g,ampm?'AaPpMm':'').replace(/tT/g,ampm?'AaPpMm':'').replace(/T/g,ampm?'AP':'').replace(/tt/g,ampm?'apm':'').replace(/t/g,ampm?'ap':'')+" "+
tp_inst._defaults.separator+
tp_inst._defaults.timeSuffix+
(tp_inst._defaults.showTimezone?tp_inst._defaults.timezoneList.join(''):'')+
(tp_inst._defaults.amNames.join(''))+
(tp_inst._defaults.pmNames.join(''))+
dateChars,chr=String.fromCharCode(event.charCode===undefined?event.keyCode:event.charCode);return event.ctrlKey||(chr<' '||!dateChars||datetimeChars.indexOf(chr)>-1);}}
return $.datepicker._base_doKeyPress(event);};$.datepicker._base_doKeyUp=$.datepicker._doKeyUp;$.datepicker._doKeyUp=function(event){var inst=$.datepicker._getInst(event.target),tp_inst=$.datepicker._get(inst,'timepicker');if(tp_inst){if(tp_inst._defaults.timeOnly&&(inst.input.val()!=inst.lastVal)){try{$.datepicker._updateDatepicker(inst);}
catch(err){$.datepicker.log(err);}}}
return $.datepicker._base_doKeyUp(event);};$.datepicker._base_gotoToday=$.datepicker._gotoToday;$.datepicker._gotoToday=function(id){var inst=this._getInst($(id)[0]),$dp=inst.dpDiv;this._base_gotoToday(id);var now=new Date();var tp_inst=this._get(inst,'timepicker');if(tp_inst&&tp_inst._defaults.showTimezone&&tp_inst.timezone_select){var tzoffset=now.getTimezoneOffset();var tzsign=tzoffset>0?'-':'+';tzoffset=Math.abs(tzoffset);var tzmin=tzoffset%60;tzoffset=tzsign+('0'+(tzoffset-tzmin)/60).slice(-2)+('0'+tzmin).slice(-2);if(tp_inst._defaults.timezoneIso8609)
tzoffset=tzoffset.substring(0,3)+':'+tzoffset.substring(3);tp_inst.timezone_select.val(tzoffset);}
this._setTime(inst,now);$('.ui-datepicker-today',$dp).click();};$.datepicker._disableTimepickerDatepicker=function(target,date,withDate){var inst=this._getInst(target),tp_inst=this._get(inst,'timepicker');$(target).datepicker('getDate');if(tp_inst){tp_inst._defaults.showTimepicker=false;tp_inst._updateDateTime(inst);}};$.datepicker._enableTimepickerDatepicker=function(target,date,withDate){var inst=this._getInst(target),tp_inst=this._get(inst,'timepicker');$(target).datepicker('getDate');if(tp_inst){tp_inst._defaults.showTimepicker=true;tp_inst._addTimePicker(inst);tp_inst._updateDateTime(inst);}};$.datepicker._setTime=function(inst,date){var tp_inst=this._get(inst,'timepicker');if(tp_inst){var defaults=tp_inst._defaults,hour=date?date.getHours():defaults.hour,minute=date?date.getMinutes():defaults.minute,second=date?date.getSeconds():defaults.second,millisec=date?date.getMilliseconds():defaults.millisec;if((hour<defaults.hourMin||hour>defaults.hourMax)||(minute<defaults.minuteMin||minute>defaults.minuteMax)||(second<defaults.secondMin||second>defaults.secondMax)||(millisec<defaults.millisecMin||millisec>defaults.millisecMax)){hour=defaults.hourMin;minute=defaults.minuteMin;second=defaults.secondMin;millisec=defaults.millisecMin;}
tp_inst.hour=hour;tp_inst.minute=minute;tp_inst.second=second;tp_inst.millisec=millisec;if(tp_inst.hour_slider)tp_inst.hour_slider.slider('value',hour);if(tp_inst.minute_slider)tp_inst.minute_slider.slider('value',minute);if(tp_inst.second_slider)tp_inst.second_slider.slider('value',second);if(tp_inst.millisec_slider)tp_inst.millisec_slider.slider('value',millisec);tp_inst._onTimeChange();tp_inst._updateDateTime(inst);}};$.datepicker._setTimeDatepicker=function(target,date,withDate){var inst=this._getInst(target),tp_inst=this._get(inst,'timepicker');if(tp_inst){this._setDateFromField(inst);var tp_date;if(date){if(typeof date=="string"){tp_inst._parseTime(date,withDate);tp_date=new Date();tp_date.setHours(tp_inst.hour,tp_inst.minute,tp_inst.second,tp_inst.millisec);}
else tp_date=new Date(date.getTime());if(tp_date.toString()=='Invalid Date')tp_date=undefined;this._setTime(inst,tp_date);}}};$.datepicker._base_setDateDatepicker=$.datepicker._setDateDatepicker;$.datepicker._setDateDatepicker=function(target,date){var inst=this._getInst(target),tp_date=(date instanceof Date)?new Date(date.getTime()):date;this._updateDatepicker(inst);this._base_setDateDatepicker.apply(this,arguments);this._setTimeDatepicker(target,tp_date,true);};$.datepicker._base_getDateDatepicker=$.datepicker._getDateDatepicker;$.datepicker._getDateDatepicker=function(target,noDefault){var inst=this._getInst(target),tp_inst=this._get(inst,'timepicker');if(tp_inst){this._setDateFromField(inst,noDefault);var date=this._getDate(inst);if(date&&tp_inst._parseTime($(target).val(),tp_inst.timeOnly))date.setHours(tp_inst.hour,tp_inst.minute,tp_inst.second,tp_inst.millisec);return date;}
return this._base_getDateDatepicker(target,noDefault);};$.datepicker._base_parseDate=$.datepicker.parseDate;$.datepicker.parseDate=function(format,value,settings){var date;try{date=this._base_parseDate(format,value,settings);}catch(err){if(err.indexOf(":")>=0){date=this._base_parseDate(format,value.substring(0,value.length-(err.length-err.indexOf(':')-2)),settings);}else{throw err;}}
return date;};$.datepicker._base_formatDate=$.datepicker._formatDate;$.datepicker._formatDate=function(inst,day,month,year){var tp_inst=this._get(inst,'timepicker');if(tp_inst)
{if(day)
var b=this._base_formatDate(inst,day,month,year);tp_inst._updateDateTime(inst);return tp_inst.$input.val();}
return this._base_formatDate(inst);};$.datepicker._base_optionDatepicker=$.datepicker._optionDatepicker;$.datepicker._optionDatepicker=function(target,name,value){var inst=this._getInst(target),tp_inst=this._get(inst,'timepicker');if(tp_inst){var min,max,onselect;if(typeof name=='string'){if(name==='minDate'||name==='minDateTime')
min=value;else if(name==='maxDate'||name==='maxDateTime')
max=value;else if(name==='onSelect')
onselect=value;}else if(typeof name=='object'){if(name.minDate)
min=name.minDate;else if(name.minDateTime)
min=name.minDateTime;else if(name.maxDate)
max=name.maxDate;else if(name.maxDateTime)
max=name.maxDateTime;}
if(min){if(min==0)
min=new Date();else
min=new Date(min);tp_inst._defaults.minDate=min;tp_inst._defaults.minDateTime=min;}else if(max){if(max==0)
max=new Date();else
max=new Date(max);tp_inst._defaults.maxDate=max;tp_inst._defaults.maxDateTime=max;}
else if(onselect)
tp_inst._defaults.onSelect=onselect;}
if(value===undefined)
return this._base_optionDatepicker(target,name);return this._base_optionDatepicker(target,name,value);};function extendRemove(target,props){$.extend(target,props);for(var name in props)
if(props[name]===null||props[name]===undefined)
target[name]=props[name];return target;};$.timepicker=new Timepicker();$.timepicker.version="0.9.9";})(jQuery);(function($){$.fn.extend({sliderAccess:function(options){options=options||{};options.touchonly=options.touchonly!==undefined?options.touchonly:true;if(options.touchonly===true&&!("ontouchend"in document))
return $(this);return $(this).each(function(i,obj){var $t=$(this),o=$.extend({},{where:'after',step:$t.slider('option','step'),upIcon:'ui-icon-plus',downIcon:'ui-icon-minus',text:false,upText:'+',downText:'-',buttonset:true,buttonsetTag:'span'},options),$buttons=$('<'+o.buttonsetTag+' class="ui-slider-access">'+'<button data-icon="'+o.downIcon+'" data-step="-'+o.step+'">'+o.downText+'</button>'+'<button data-icon="'+o.upIcon+'" data-step="'+o.step+'">'+o.upText+'</button>'+'</'+o.buttonsetTag+'>');$buttons.children('button').each(function(j,jobj){var $jt=$(this);$jt.button({text:o.text,icons:{primary:$jt.data('icon')}}).click(function(e){var step=$jt.data('step'),curr=$t.slider('value'),newval=curr+=step*1,minval=$t.slider('option','min'),maxval=$t.slider('option','max');e.preventDefault();if(newval<minval||newval>maxval)
return;$t.slider('value',newval);$t.slider("option","slide").call($t,null,{value:newval});});});$t[o.where]($buttons);if(o.buttonset){$buttons.removeClass('ui-corner-right').removeClass('ui-corner-left').buttonset();$buttons.eq(0).addClass('ui-corner-left');$buttons.eq(1).addClass('ui-corner-right');}
var bOuterWidth=$buttons.css({marginLeft:(o.where=='after'?10:0),marginRight:(o.where=='before'?10:0)}).outerWidth(true)+5;var tOuterWidth=$t.outerWidth(true);$t.css('display','inline-block').width(tOuterWidth-bOuterWidth);});}});})(jQuery);(function($,undefined){$.ui=$.ui||{};if($.ui.version){return;}
$.extend($.ui,{version:"1.8.18",keyCode:{ALT:18,BACKSPACE:8,CAPS_LOCK:20,COMMA:188,COMMAND:91,COMMAND_LEFT:91,COMMAND_RIGHT:93,CONTROL:17,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,INSERT:45,LEFT:37,MENU:93,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SHIFT:16,SPACE:32,TAB:9,UP:38,WINDOWS:91}});$.fn.extend({propAttr:$.fn.prop||$.fn.attr,_focus:$.fn.focus,focus:function(delay,fn){return typeof delay==="number"?this.each(function(){var elem=this;setTimeout(function(){$(elem).focus();if(fn){fn.call(elem);}},delay);}):this._focus.apply(this,arguments);},scrollParent:function(){var scrollParent;if(($.browser.msie&&(/(static|relative)/).test(this.css('position')))||(/absolute/).test(this.css('position'))){scrollParent=this.parents().filter(function(){return(/(relative|absolute|fixed)/).test($.curCSS(this,'position',1))&&(/(auto|scroll)/).test($.curCSS(this,'overflow',1)+$.curCSS(this,'overflow-y',1)+$.curCSS(this,'overflow-x',1));}).eq(0);}else{scrollParent=this.parents().filter(function(){return(/(auto|scroll)/).test($.curCSS(this,'overflow',1)+$.curCSS(this,'overflow-y',1)+$.curCSS(this,'overflow-x',1));}).eq(0);}
return(/fixed/).test(this.css('position'))||!scrollParent.length?$(document):scrollParent;},zIndex:function(zIndex){if(zIndex!==undefined){return this.css("zIndex",zIndex);}
if(this.length){var elem=$(this[0]),position,value;while(elem.length&&elem[0]!==document){position=elem.css("position");if(position==="absolute"||position==="relative"||position==="fixed"){value=parseInt(elem.css("zIndex"),10);if(!isNaN(value)&&value!==0){return value;}}
elem=elem.parent();}}
return 0;},disableSelection:function(){return this.bind(($.support.selectstart?"selectstart":"mousedown")+".ui-disableSelection",function(event){event.preventDefault();});},enableSelection:function(){return this.unbind(".ui-disableSelection");}});$.each(["Width","Height"],function(i,name){var side=name==="Width"?["Left","Right"]:["Top","Bottom"],type=name.toLowerCase(),orig={innerWidth:$.fn.innerWidth,innerHeight:$.fn.innerHeight,outerWidth:$.fn.outerWidth,outerHeight:$.fn.outerHeight};function reduce(elem,size,border,margin){$.each(side,function(){size-=parseFloat($.curCSS(elem,"padding"+this,true))||0;if(border){size-=parseFloat($.curCSS(elem,"border"+this+"Width",true))||0;}
if(margin){size-=parseFloat($.curCSS(elem,"margin"+this,true))||0;}});return size;}
$.fn["inner"+name]=function(size){if(size===undefined){return orig["inner"+name].call(this);}
return this.each(function(){$(this).css(type,reduce(this,size)+"px");});};$.fn["outer"+name]=function(size,margin){if(typeof size!=="number"){return orig["outer"+name].call(this,size);}
return this.each(function(){$(this).css(type,reduce(this,size,true,margin)+"px");});};});function focusable(element,isTabIndexNotNaN){var nodeName=element.nodeName.toLowerCase();if("area"===nodeName){var map=element.parentNode,mapName=map.name,img;if(!element.href||!mapName||map.nodeName.toLowerCase()!=="map"){return false;}
img=$("img[usemap=#"+mapName+"]")[0];return!!img&&visible(img);}
return(/input|select|textarea|button|object/.test(nodeName)?!element.disabled:"a"==nodeName?element.href||isTabIndexNotNaN:isTabIndexNotNaN)&&visible(element);}
function visible(element){return!$(element).parents().andSelf().filter(function(){return $.curCSS(this,"visibility")==="hidden"||$.expr.filters.hidden(this);}).length;}
$.extend($.expr[":"],{data:function(elem,i,match){return!!$.data(elem,match[3]);},focusable:function(element){return focusable(element,!isNaN($.attr(element,"tabindex")));},tabbable:function(element){var tabIndex=$.attr(element,"tabindex"),isTabIndexNaN=isNaN(tabIndex);return(isTabIndexNaN||tabIndex>=0)&&focusable(element,!isTabIndexNaN);}});$(function(){var body=document.body,div=body.appendChild(div=document.createElement("div"));div.offsetHeight;$.extend(div.style,{minHeight:"100px",height:"auto",padding:0,borderWidth:0});$.support.minHeight=div.offsetHeight===100;$.support.selectstart="onselectstart"in div;body.removeChild(div).style.display="none";});$.extend($.ui,{plugin:{add:function(module,option,set){var proto=$.ui[module].prototype;for(var i in set){proto.plugins[i]=proto.plugins[i]||[];proto.plugins[i].push([option,set[i]]);}},call:function(instance,name,args){var set=instance.plugins[name];if(!set||!instance.element[0].parentNode){return;}
for(var i=0;i<set.length;i++){if(instance.options[set[i][0]]){set[i][1].apply(instance.element,args);}}}},contains:function(a,b){return document.compareDocumentPosition?a.compareDocumentPosition(b)&16:a!==b&&a.contains(b);},hasScroll:function(el,a){if($(el).css("overflow")==="hidden"){return false;}
var scroll=(a&&a==="left")?"scrollLeft":"scrollTop",has=false;if(el[scroll]>0){return true;}
el[scroll]=1;has=(el[scroll]>0);el[scroll]=0;return has;},isOverAxis:function(x,reference,size){return(x>reference)&&(x<(reference+size));},isOver:function(y,x,top,left,height,width){return $.ui.isOverAxis(y,top,height)&&$.ui.isOverAxis(x,left,width);}});})(jQuery);(function($,undefined){$.ui=$.ui||{};var horizontalPositions=/left|center|right/,verticalPositions=/top|center|bottom/,center="center",support={},_position=$.fn.position,_offset=$.fn.offset;$.fn.position=function(options){if(!options||!options.of){return _position.apply(this,arguments);}
options=$.extend({},options);var target=$(options.of),targetElem=target[0],collision=(options.collision||"flip").split(" "),offset=options.offset?options.offset.split(" "):[0,0],targetWidth,targetHeight,basePosition;if(targetElem.nodeType===9){targetWidth=target.width();targetHeight=target.height();basePosition={top:0,left:0};}else if(targetElem.setTimeout){targetWidth=target.width();targetHeight=target.height();basePosition={top:target.scrollTop(),left:target.scrollLeft()};}else if(targetElem.preventDefault){options.at="left top";targetWidth=targetHeight=0;basePosition={top:options.of.pageY,left:options.of.pageX};}else{targetWidth=target.outerWidth();targetHeight=target.outerHeight();basePosition=target.offset();}
$.each(["my","at"],function(){var pos=(options[this]||"").split(" ");if(pos.length===1){pos=horizontalPositions.test(pos[0])?pos.concat([center]):verticalPositions.test(pos[0])?[center].concat(pos):[center,center];}
pos[0]=horizontalPositions.test(pos[0])?pos[0]:center;pos[1]=verticalPositions.test(pos[1])?pos[1]:center;options[this]=pos;});if(collision.length===1){collision[1]=collision[0];}
offset[0]=parseInt(offset[0],10)||0;if(offset.length===1){offset[1]=offset[0];}
offset[1]=parseInt(offset[1],10)||0;if(options.at[0]==="right"){basePosition.left+=targetWidth;}else if(options.at[0]===center){basePosition.left+=targetWidth/2;}
if(options.at[1]==="bottom"){basePosition.top+=targetHeight;}else if(options.at[1]===center){basePosition.top+=targetHeight/2;}
basePosition.left+=offset[0];basePosition.top+=offset[1];return this.each(function(){var elem=$(this),elemWidth=elem.outerWidth(),elemHeight=elem.outerHeight(),marginLeft=parseInt($.curCSS(this,"marginLeft",true))||0,marginTop=parseInt($.curCSS(this,"marginTop",true))||0,collisionWidth=elemWidth+marginLeft+
(parseInt($.curCSS(this,"marginRight",true))||0),collisionHeight=elemHeight+marginTop+
(parseInt($.curCSS(this,"marginBottom",true))||0),position=$.extend({},basePosition),collisionPosition;if(options.my[0]==="right"){position.left-=elemWidth;}else if(options.my[0]===center){position.left-=elemWidth/2;}
if(options.my[1]==="bottom"){position.top-=elemHeight;}else if(options.my[1]===center){position.top-=elemHeight/2;}
if(!support.fractions){position.left=Math.round(position.left);position.top=Math.round(position.top);}
collisionPosition={left:position.left-marginLeft,top:position.top-marginTop};$.each(["left","top"],function(i,dir){if($.ui.position[collision[i]]){$.ui.position[collision[i]][dir](position,{targetWidth:targetWidth,targetHeight:targetHeight,elemWidth:elemWidth,elemHeight:elemHeight,collisionPosition:collisionPosition,collisionWidth:collisionWidth,collisionHeight:collisionHeight,offset:offset,my:options.my,at:options.at});}});if($.fn.bgiframe){elem.bgiframe();}
elem.offset($.extend(position,{using:options.using}));});};$.ui.position={fit:{left:function(position,data){var win=$(window),over=data.collisionPosition.left+data.collisionWidth-win.width()-win.scrollLeft();position.left=over>0?position.left-over:Math.max(position.left-data.collisionPosition.left,position.left);},top:function(position,data){var win=$(window),over=data.collisionPosition.top+data.collisionHeight-win.height()-win.scrollTop();position.top=over>0?position.top-over:Math.max(position.top-data.collisionPosition.top,position.top);}},flip:{left:function(position,data){if(data.at[0]===center){return;}
var win=$(window),over=data.collisionPosition.left+data.collisionWidth-win.width()-win.scrollLeft(),myOffset=data.my[0]==="left"?-data.elemWidth:data.my[0]==="right"?data.elemWidth:0,atOffset=data.at[0]==="left"?data.targetWidth:-data.targetWidth,offset=-2*data.offset[0];position.left+=data.collisionPosition.left<0?myOffset+atOffset+offset:over>0?myOffset+atOffset+offset:0;},top:function(position,data){if(data.at[1]===center){return;}
var win=$(window),over=data.collisionPosition.top+data.collisionHeight-win.height()-win.scrollTop(),myOffset=data.my[1]==="top"?-data.elemHeight:data.my[1]==="bottom"?data.elemHeight:0,atOffset=data.at[1]==="top"?data.targetHeight:-data.targetHeight,offset=-2*data.offset[1];position.top+=data.collisionPosition.top<0?myOffset+atOffset+offset:over>0?myOffset+atOffset+offset:0;}}};if(!$.offset.setOffset){$.offset.setOffset=function(elem,options){if(/static/.test($.curCSS(elem,"position"))){elem.style.position="relative";}
var curElem=$(elem),curOffset=curElem.offset(),curTop=parseInt($.curCSS(elem,"top",true),10)||0,curLeft=parseInt($.curCSS(elem,"left",true),10)||0,props={top:(options.top-curOffset.top)+curTop,left:(options.left-curOffset.left)+curLeft};if('using'in options){options.using.call(elem,props);}else{curElem.css(props);}};$.fn.offset=function(options){var elem=this[0];if(!elem||!elem.ownerDocument){return null;}
if(options){return this.each(function(){$.offset.setOffset(this,options);});}
return _offset.call(this);};}



(function(){var body=document.getElementsByTagName("body")[0],div=document.createElement("div"),testElement,testElementParent,testElementStyle,offset,offsetTotal;testElement=document.createElement(body?"div":"body");testElementStyle={visibility:"hidden",width:0,height:0,border:0,margin:0,background:"none"};if(body){$.extend(testElementStyle,{position:"absolute",left:"-1000px",top:"-1000px"});}
for(var i in testElementStyle){testElement.style[i]=testElementStyle[i];}

testElement.appendChild(div);testElementParent=body||document.documentElement;testElementParent.insertBefore(testElement,testElementParent.firstChild);div.style.cssText="position: absolute; left: 10.7432222px; top: 10.432325px; height: 30px; width: 201px;";offset=$(div).offset(function(_,offset){return offset;}).offset();testElement.innerHTML="";testElementParent.removeChild(testElement);offsetTotal=offset.top+offset.left+(body?2000:0);support.fractions=offsetTotal>21&&offsetTotal<22;})();}(jQuery));(function($,undefined){var requestIndex=0;$.widget("ui.autocomplete",{options:{appendTo:"body",delay:300,minLength:1,position:{my:"left top",at:"left bottom",collision:"none"},source:null},pending:0,_create:function(){var self=this,doc=this.element[0].ownerDocument,suppressKeyPress;this.element.addClass("ui-autocomplete-input").attr("autocomplete","off").attr({role:"textbox","aria-autocomplete":"list","aria-haspopup":"true"}).bind("keydown.autocomplete",function(event){if(self.options.disabled||self.element.propAttr("readOnly")){return;}
suppressKeyPress=false;var keyCode=$.ui.keyCode;switch(event.keyCode){case keyCode.PAGE_UP:self._move("previousPage",event);break;case keyCode.PAGE_DOWN:self._move("nextPage",event);break;case keyCode.UP:self._move("previous",event);event.preventDefault();break;case keyCode.DOWN:self._move("next",event);event.preventDefault();break;case keyCode.ENTER:case keyCode.NUMPAD_ENTER:if(self.menu.active){suppressKeyPress=true;event.preventDefault();}
case keyCode.TAB:if(!self.menu.active){return;}
self.menu.select(event);break;case keyCode.ESCAPE:self.element.val(self.term);self.close(event);break;default:clearTimeout(self.searching);self.searching=setTimeout(function(){if(self.term!=self.element.val()){self.selectedItem=null;self.search(null,event);}},self.options.delay);break;}}).bind("keypress.autocomplete",function(event){if(suppressKeyPress){suppressKeyPress=false;event.preventDefault();}}).bind("focus.autocomplete",function(){if(self.options.disabled){return;}
self.selectedItem=null;self.previous=self.element.val();}).bind("blur.autocomplete",function(event){if(self.options.disabled){return;}
clearTimeout(self.searching);self.closing=setTimeout(function(){self.close(event);self._change(event);},150);});this._initSource();this.response=function(){return self._response.apply(self,arguments);};this.menu=$("<ul></ul>").addClass("ui-autocomplete").appendTo($(this.options.appendTo||"body",doc)[0]).mousedown(function(event){var menuElement=self.menu.element[0];if(!$(event.target).closest(".ui-menu-item").length){setTimeout(function(){$(document).one('mousedown',function(event){if(event.target!==self.element[0]&&event.target!==menuElement&&!$.ui.contains(menuElement,event.target)){self.close();}});},1);}
setTimeout(function(){clearTimeout(self.closing);},13);}).menu({focus:function(event,ui){var item=ui.item.data("item.autocomplete");if(false!==self._trigger("focus",event,{item:item})){if(/^key/.test(event.originalEvent.type)){self.element.val(item.value);}}},selected:function(event,ui){var item=ui.item.data("item.autocomplete"),previous=self.previous;if(self.element[0]!==doc.activeElement){self.element.focus();self.previous=previous;setTimeout(function(){self.previous=previous;self.selectedItem=item;},1);}
if(false!==self._trigger("select",event,{item:item})){self.element.val(item.value);}
self.term=self.element.val();self.close(event);self.selectedItem=item;},blur:function(event,ui){if(self.menu.element.is(":visible")&&(self.element.val()!==self.term)){self.element.val(self.term);}}}).zIndex(this.element.zIndex()+1).css({top:0,left:0}).hide().data("menu");if($.fn.bgiframe){this.menu.element.bgiframe();}
self.beforeunloadHandler=function(){self.element.removeAttr("autocomplete");};$(window).bind("beforeunload",self.beforeunloadHandler);},destroy:function(){this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete").removeAttr("role").removeAttr("aria-autocomplete").removeAttr("aria-haspopup");this.menu.element.remove();$(window).unbind("beforeunload",this.beforeunloadHandler);$.Widget.prototype.destroy.call(this);},_setOption:function(key,value){$.Widget.prototype._setOption.apply(this,arguments);if(key==="source"){this._initSource();}
if(key==="appendTo"){this.menu.element.appendTo($(value||"body",this.element[0].ownerDocument)[0])}
if(key==="disabled"&&value&&this.xhr){this.xhr.abort();}},_initSource:function(){var self=this,array,url;if($.isArray(this.options.source)){array=this.options.source;this.source=function(request,response){response($.ui.autocomplete.filter(array,request.term));};}else if(typeof this.options.source==="string"){url=this.options.source;this.source=function(request,response){if(self.xhr){self.xhr.abort();}
self.xhr=$.ajax({url:url,data:request,dataType:"json",context:{autocompleteRequest:++requestIndex},success:function(data,status){if(this.autocompleteRequest===requestIndex){response(data);}},error:function(){if(this.autocompleteRequest===requestIndex){response([]);}}});};}else{this.source=this.options.source;}},search:function(value,event){value=value!=null?value:this.element.val();this.term=this.element.val();if(value.length<this.options.minLength){return this.close(event);}
clearTimeout(this.closing);if(this._trigger("search",event)===false){return;}
return this._search(value);},_search:function(value){this.pending++;this.element.addClass("ui-autocomplete-loading");this.source({term:value},this.response);},_response:function(content){if(!this.options.disabled&&content&&content.length){content=this._normalize(content);this._suggest(content);this._trigger("open");}else{this.close();}
this.pending--;if(!this.pending){this.element.removeClass("ui-autocomplete-loading");}},close:function(event){clearTimeout(this.closing);if(this.menu.element.is(":visible")){this.menu.element.hide();this.menu.deactivate();this._trigger("close",event);}},_change:function(event){if(this.previous!==this.element.val()){this._trigger("change",event,{item:this.selectedItem});}},_normalize:function(items){if(items.length&&items[0].label&&items[0].value){return items;}
return $.map(items,function(item){if(typeof item==="string"){return{label:item,value:item};}
return $.extend({label:item.label||item.value,value:item.value||item.label},item);});},_suggest:function(items){var ul=this.menu.element.empty().zIndex(this.element.zIndex()+1);this._renderMenu(ul,items);this.menu.deactivate();this.menu.refresh();ul.show();this._resizeMenu();ul.position($.extend({of:this.element},this.options.position));if(this.options.autoFocus){this.menu.next(new $.Event("mouseover"));}},_resizeMenu:function(){var ul=this.menu.element;ul.outerWidth(Math.max(ul.width("").outerWidth()+1,this.element.outerWidth()));},_renderMenu:function(ul,items){var self=this;$.each(items,function(index,item){self._renderItem(ul,item);});},_renderItem:function(ul,item){return $("<li></li>").data("item.autocomplete",item).append($("<a></a>").text(item.label)).appendTo(ul);},_move:function(direction,event){if(!this.menu.element.is(":visible")){this.search(null,event);return;}
if(this.menu.first()&&/^previous/.test(direction)||this.menu.last()&&/^next/.test(direction)){this.element.val(this.term);this.menu.deactivate();return;}
this.menu[direction](event);},widget:function(){return this.menu.element;}});$.extend($.ui.autocomplete,{escapeRegex:function(value){return value.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&");},filter:function(array,term){var matcher=new RegExp($.ui.autocomplete.escapeRegex(term),"i");return $.grep(array,function(value){return matcher.test(value.label||value.value||value);});}});}(jQuery));(function($){$.widget("ui.menu",{_create:function(){var self=this;this.element.addClass("ui-menu ui-widget ui-widget-content ui-corner-all").attr({role:"listbox","aria-activedescendant":"ui-active-menuitem"}).click(function(event){if(!$(event.target).closest(".ui-menu-item a").length){return;}
event.preventDefault();self.select(event);});this.refresh();},refresh:function(){var self=this;var items=this.element.children("li:not(.ui-menu-item):has(a)").addClass("ui-menu-item").attr("role","menuitem");items.children("a").addClass("ui-corner-all").attr("tabindex",-1).mouseenter(function(event){self.activate(event,$(this).parent());}).mouseleave(function(){self.deactivate();});},activate:function(event,item){this.deactivate();if(this.hasScroll()){var offset=item.offset().top-this.element.offset().top,scroll=this.element.scrollTop(),elementHeight=this.element.height();if(offset<0){this.element.scrollTop(scroll+offset);}else if(offset>=elementHeight){this.element.scrollTop(scroll+offset-elementHeight+item.height());}}
this.active=item.eq(0).children("a").addClass("ui-state-hover").attr("id","ui-active-menuitem").end();this._trigger("focus",event,{item:item});},deactivate:function(){if(!this.active){return;}
this.active.children("a").removeClass("ui-state-hover").removeAttr("id");this._trigger("blur");this.active=null;},next:function(event){this.move("next",".ui-menu-item:first",event);},previous:function(event){this.move("prev",".ui-menu-item:last",event);},first:function(){return this.active&&!this.active.prevAll(".ui-menu-item").length;},last:function(){return this.active&&!this.active.nextAll(".ui-menu-item").length;},move:function(direction,edge,event){if(!this.active){this.activate(event,this.element.children(edge));return;}
var next=this.active[direction+"All"](".ui-menu-item").eq(0);if(next.length){this.activate(event,next);}else{this.activate(event,this.element.children(edge));}},nextPage:function(event){if(this.hasScroll()){if(!this.active||this.last()){this.activate(event,this.element.children(".ui-menu-item:first"));return;}
var base=this.active.offset().top,height=this.element.height(),result=this.element.children(".ui-menu-item").filter(function(){var close=$(this).offset().top-base-height+$(this).height();return close<10&&close>-10;});if(!result.length){result=this.element.children(".ui-menu-item:last");}
this.activate(event,result);}else{this.activate(event,this.element.children(".ui-menu-item").filter(!this.active||this.last()?":first":":last"));}},previousPage:function(event){if(this.hasScroll()){if(!this.active||this.first()){this.activate(event,this.element.children(".ui-menu-item:last"));return;}
var base=this.active.offset().top,height=this.element.height();result=this.element.children(".ui-menu-item").filter(function(){var close=$(this).offset().top-base+height-$(this).height();return close<10&&close>-10;});if(!result.length){result=this.element.children(".ui-menu-item:first");}
this.activate(event,result);}else{this.activate(event,this.element.children(".ui-menu-item").filter(!this.active||this.first()?":last":":first"));}},hasScroll:function(){return this.element.height()<this.element[$.fn.prop?"prop":"attr"]("scrollHeight");},select:function(event){this._trigger("selected",event,{item:this.active});}});}(jQuery));















/*
 * jQuery UI Tabs 1.8.18
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Tabs
 *
 * Depends:
 *	jquery.ui.core.js
 *	jquery.ui.widget.js
 */
(function( $, undefined ) {

var tabId = 0,
	listId = 0;

	
function getNextTabId() {
	return ++tabId;
}

function getNextListId() {
	return ++listId;
}

$.widget( "ui.tabs", {
	options: {
		add: null,
		ajaxOptions: null,
		cache: false,
		cookie: null, // e.g. { expires: 7, path: '/', domain: 'jquery.com', secure: true }
		collapsible: false,
		disable: null,
		disabled: [],
		enable: null,
		event: "click",
		fx: null, // e.g. { height: 'toggle', opacity: 'toggle', duration: 200 }
		idPrefix: "ui-tabs-",
		load: null,
		panelTemplate: "<div></div>",
		remove: null,
		select: null,
		show: null,
		spinner: "<em>Loading&#8230;</em>",
		tabTemplate: "<li><a href='#{href}'><span>#{label}</span></a></li>"
	},

	_create: function() {
		this._tabify( true );
	},

	_setOption: function( key, value ) {
		if ( key == "selected" ) {
			if (this.options.collapsible && value == this.options.selected ) {
				return;
			}
			this.select( value );
		} else {
			this.options[ key ] = value;
			this._tabify();
		}
	},

	_tabId: function( a ) {
		return a.title && a.title.replace( /\s/g, "_" ).replace( /[^\w\u00c0-\uFFFF-]/g, "" ) ||
			this.options.idPrefix + getNextTabId();
	},

	_sanitizeSelector: function( hash ) {
		// we need this because an id may contain a ":"
		return hash.replace( /:/g, "\\:" );
	},

	_cookie: function() {
		var cookie = this.cookie ||
			( this.cookie = this.options.cookie.name || "ui-tabs-" + getNextListId() );
		return $.cookie.apply( null, [ cookie ].concat( $.makeArray( arguments ) ) );
	},

	_ui: function( tab, panel ) {
		return {
			tab: tab,
			panel: panel,
			index: this.anchors.index( tab )
		};
	},

	_cleanup: function() {
		// restore all former loading tabs labels
		this.lis.filter( ".ui-state-processing" )
			.removeClass( "ui-state-processing" )
			.find( "span:data(label.tabs)" )
				.each(function() {
					var el = $( this );
					el.html( el.data( "label.tabs" ) ).removeData( "label.tabs" );
				});
	},

	_tabify: function( init ) {
		var self = this,
			o = this.options,
			fragmentId = /^#.+/; // Safari 2 reports '#' for an empty hash

		this.list = this.element.find( "ol,ul" ).eq( 0 );
		this.lis = $( " > li:has(a[href])", this.list );
		this.anchors = this.lis.map(function() {
			return $( "a", this )[ 0 ];
		});
		this.panels = $( [] );

		this.anchors.each(function( i, a ) {
			var href = $( a ).attr( "href" );
			// For dynamically created HTML that contains a hash as href IE < 8 expands
			// such href to the full page url with hash and then misinterprets tab as ajax.
			// Same consideration applies for an added tab with a fragment identifier
			// since a[href=#fragment-identifier] does unexpectedly not match.
			// Thus normalize href attribute...
			var hrefBase = href.split( "#" )[ 0 ],
				baseEl;
			if ( hrefBase && ( hrefBase === location.toString().split( "#" )[ 0 ] ||
					( baseEl = $( "base" )[ 0 ]) && hrefBase === baseEl.href ) ) {
				href = a.hash;
				a.href = href;
			}

			// inline tab
			if ( fragmentId.test( href ) ) {
				self.panels = self.panels.add( self.element.find( self._sanitizeSelector( href ) ) );
			// remote tab
			// prevent loading the page itself if href is just "#"
			} else if ( href && href !== "#" ) {
				// required for restore on destroy
				$.data( a, "href.tabs", href );

				// TODO until #3808 is fixed strip fragment identifier from url
				// (IE fails to load from such url)
				$.data( a, "load.tabs", href.replace( /#.*$/, "" ) );

				var id = self._tabId( a );
				a.href = "#" + id;
				var $panel = self.element.find( "#" + id );
				if ( !$panel.length ) {
					$panel = $( o.panelTemplate )
						.attr( "id", id )
						.addClass( "ui-tabs-panel ui-widget-content ui-corner-bottom" )
						.insertAfter( self.panels[ i - 1 ] || self.list );
					$panel.data( "destroy.tabs", true );
				}
				self.panels = self.panels.add( $panel );
			// invalid tab href
			} else {
				o.disabled.push( i );
			}
		});

		// initialization from scratch
		if ( init ) {
			// attach necessary classes for styling
			this.element.addClass( "ui-tabs ui-widget ui-widget-content ui-corner-all" );
			this.list.addClass( "ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" );
			this.lis.addClass( "ui-state-default ui-corner-top" );
			this.panels.addClass( "ui-tabs-panel ui-widget-content ui-corner-bottom" );

			// Selected tab
			// use "selected" option or try to retrieve:
			// 1. from fragment identifier in url
			// 2. from cookie
			// 3. from selected class attribute on <li>
			if ( o.selected === undefined ) {
				if ( location.hash ) {
					this.anchors.each(function( i, a ) {
						if ( a.hash == location.hash ) {
							o.selected = i;
							return false;
						}
					});
				}
				if ( typeof o.selected !== "number" && o.cookie ) {
					o.selected = parseInt( self._cookie(), 10 );
				}
				if ( typeof o.selected !== "number" && this.lis.filter( ".ui-tabs-selected" ).length ) {
					o.selected = this.lis.index( this.lis.filter( ".ui-tabs-selected" ) );
				}
				o.selected = o.selected || ( this.lis.length ? 0 : -1 );
			} else if ( o.selected === null ) { // usage of null is deprecated, TODO remove in next release
				o.selected = -1;
			}

			// sanity check - default to first tab...
			o.selected = ( ( o.selected >= 0 && this.anchors[ o.selected ] ) || o.selected < 0 )
				? o.selected
				: 0;

			// Take disabling tabs via class attribute from HTML
			// into account and update option properly.
			// A selected tab cannot become disabled.
			o.disabled = $.unique( o.disabled.concat(
				$.map( this.lis.filter( ".ui-state-disabled" ), function( n, i ) {
					return self.lis.index( n );
				})
			) ).sort();

			if ( $.inArray( o.selected, o.disabled ) != -1 ) {
				o.disabled.splice( $.inArray( o.selected, o.disabled ), 1 );
			}

			// highlight selected tab
			this.panels.addClass( "ui-tabs-hide" );
			this.lis.removeClass( "ui-tabs-selected ui-state-active" );
			// check for length avoids error when initializing empty list
			if ( o.selected >= 0 && this.anchors.length ) {
				self.element.find( self._sanitizeSelector( self.anchors[ o.selected ].hash ) ).removeClass( "ui-tabs-hide" );
				this.lis.eq( o.selected ).addClass( "ui-tabs-selected ui-state-active" );

				// seems to be expected behavior that the show callback is fired
				self.element.queue( "tabs", function() {
					self._trigger( "show", null,
						self._ui( self.anchors[ o.selected ], self.element.find( self._sanitizeSelector( self.anchors[ o.selected ].hash ) )[ 0 ] ) );
				});

				this.load( o.selected );
			}

			// clean up to avoid memory leaks in certain versions of IE 6
			// TODO: namespace this event
			$( window ).bind( "unload", function() {
				self.lis.add( self.anchors ).unbind( ".tabs" );
				self.lis = self.anchors = self.panels = null;
			});
		// update selected after add/remove
		} else {
			o.selected = this.lis.index( this.lis.filter( ".ui-tabs-selected" ) );
		}

		// update collapsible
		// TODO: use .toggleClass()
		this.element[ o.collapsible ? "addClass" : "removeClass" ]( "ui-tabs-collapsible" );

		// set or update cookie after init and add/remove respectively
		if ( o.cookie ) {
			this._cookie( o.selected, o.cookie );
		}

		// disable tabs
		for ( var i = 0, li; ( li = this.lis[ i ] ); i++ ) {
			$( li )[ $.inArray( i, o.disabled ) != -1 &&
				// TODO: use .toggleClass()
				!$( li ).hasClass( "ui-tabs-selected" ) ? "addClass" : "removeClass" ]( "ui-state-disabled" );
		}

		// reset cache if switching from cached to not cached
		if ( o.cache === false ) {
			this.anchors.removeData( "cache.tabs" );
		}

		// remove all handlers before, tabify may run on existing tabs after add or option change
		this.lis.add( this.anchors ).unbind( ".tabs" );

		if ( o.event !== "mouseover" ) {
			var addState = function( state, el ) {
				if ( el.is( ":not(.ui-state-disabled)" ) ) {
					el.addClass( "ui-state-" + state );
				}
			};
			var removeState = function( state, el ) {
				el.removeClass( "ui-state-" + state );
			};
			this.lis.bind( "mouseover.tabs" , function() {
				addState( "hover", $( this ) );
			});
			this.lis.bind( "mouseout.tabs", function() {
				removeState( "hover", $( this ) );
			});
			this.anchors.bind( "focus.tabs", function() {
				addState( "focus", $( this ).closest( "li" ) );
			});
			this.anchors.bind( "blur.tabs", function() {
				removeState( "focus", $( this ).closest( "li" ) );
			});
		}

		// set up animations
		var hideFx, showFx;
		if ( o.fx ) {
			if ( $.isArray( o.fx ) ) {
				hideFx = o.fx[ 0 ];
				showFx = o.fx[ 1 ];
			} else {
				hideFx = showFx = o.fx;
			}
		}

		// Reset certain styles left over from animation
		// and prevent IE's ClearType bug...
		function resetStyle( $el, fx ) {
			$el.css( "display", "" );
			if ( !$.support.opacity && fx.opacity ) {
				$el[ 0 ].style.removeAttribute( "filter" );
			}
		}

		// Show a tab...
		var showTab = showFx
			? function( clicked, $show ) {
				$( clicked ).closest( "li" ).addClass( "ui-tabs-selected ui-state-active" );
				$show.hide().removeClass( "ui-tabs-hide" ) // avoid flicker that way
					.animate( showFx, showFx.duration || "normal", function() {
						resetStyle( $show, showFx );
						self._trigger( "show", null, self._ui( clicked, $show[ 0 ] ) );
					});
			}
			: function( clicked, $show ) {
				$( clicked ).closest( "li" ).addClass( "ui-tabs-selected ui-state-active" );
				$show.removeClass( "ui-tabs-hide" );
				self._trigger( "show", null, self._ui( clicked, $show[ 0 ] ) );
			};

		// Hide a tab, $show is optional...
		var hideTab = hideFx
			? function( clicked, $hide ) {
				$hide.animate( hideFx, hideFx.duration || "normal", function() {
					self.lis.removeClass( "ui-tabs-selected ui-state-active" );
					$hide.addClass( "ui-tabs-hide" );
					resetStyle( $hide, hideFx );
					self.element.dequeue( "tabs" );
				});
			}
			: function( clicked, $hide, $show ) {
				self.lis.removeClass( "ui-tabs-selected ui-state-active" );
				$hide.addClass( "ui-tabs-hide" );
				self.element.dequeue( "tabs" );
			};

		// attach tab event handler, unbind to avoid duplicates from former tabifying...
		this.anchors.bind( o.event + ".tabs", function() {
			var el = this,
				$li = $(el).closest( "li" ),
				$hide = self.panels.filter( ":not(.ui-tabs-hide)" ),
				$show = self.element.find( self._sanitizeSelector( el.hash ) );

			// If tab is already selected and not collapsible or tab disabled or
			// or is already loading or click callback returns false stop here.
			// Check if click handler returns false last so that it is not executed
			// for a disabled or loading tab!
			if ( ( $li.hasClass( "ui-tabs-selected" ) && !o.collapsible) ||
				$li.hasClass( "ui-state-disabled" ) ||
				$li.hasClass( "ui-state-processing" ) ||
				self.panels.filter( ":animated" ).length ||
				self._trigger( "select", null, self._ui( this, $show[ 0 ] ) ) === false ) {
				this.blur();
				return false;
			}

			o.selected = self.anchors.index( this );

			self.abort();

			// if tab may be closed
			if ( o.collapsible ) {
				if ( $li.hasClass( "ui-tabs-selected" ) ) {
					o.selected = -1;

					if ( o.cookie ) {
						self._cookie( o.selected, o.cookie );
					}

					self.element.queue( "tabs", function() {
						hideTab( el, $hide );
					}).dequeue( "tabs" );

					this.blur();
					return false;
				} else if ( !$hide.length ) {
					if ( o.cookie ) {
						self._cookie( o.selected, o.cookie );
					}

					self.element.queue( "tabs", function() {
						showTab( el, $show );
					});

					// TODO make passing in node possible, see also http://dev.jqueryui.com/ticket/3171
					self.load( self.anchors.index( this ) );

					this.blur();
					return false;
				}
			}

			if ( o.cookie ) {
				self._cookie( o.selected, o.cookie );
			}

			// show new tab
			if ( $show.length ) {
				if ( $hide.length ) {
					self.element.queue( "tabs", function() {
						hideTab( el, $hide );
					});
				}
				self.element.queue( "tabs", function() {
					showTab( el, $show );
				});

				self.load( self.anchors.index( this ) );
			} else {
				throw "jQuery UI Tabs: Mismatching fragment identifier.";
			}

			// Prevent IE from keeping other link focussed when using the back button
			// and remove dotted border from clicked link. This is controlled via CSS
			// in modern browsers; blur() removes focus from address bar in Firefox
			// which can become a usability and annoying problem with tabs('rotate').
			if ( $.browser.msie ) {
				this.blur();
			}
		});

		// disable click in any case
		this.anchors.bind( "click.tabs", function(){
			return false;
		});
	},

    _getIndex: function( index ) {
		// meta-function to give users option to provide a href string instead of a numerical index.
		// also sanitizes numerical indexes to valid values.
		if ( typeof index == "string" ) {
			index = this.anchors.index( this.anchors.filter( "[href$=" + index + "]" ) );
		}

		return index;
	},

	destroy: function() {
		var o = this.options;

		this.abort();

		this.element
			.unbind( ".tabs" )
			.removeClass( "ui-tabs ui-widget ui-widget-content ui-corner-all ui-tabs-collapsible" )
			.removeData( "tabs" );

		this.list.removeClass( "ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" );

		this.anchors.each(function() {
			var href = $.data( this, "href.tabs" );
			if ( href ) {
				this.href = href;
			}
			var $this = $( this ).unbind( ".tabs" );
			$.each( [ "href", "load", "cache" ], function( i, prefix ) {
				$this.removeData( prefix + ".tabs" );
			});
		});

		this.lis.unbind( ".tabs" ).add( this.panels ).each(function() {
			if ( $.data( this, "destroy.tabs" ) ) {
				$( this ).remove();
			} else {
				$( this ).removeClass([
					"ui-state-default",
					"ui-corner-top",
					"ui-tabs-selected",
					"ui-state-active",
					"ui-state-hover",
					"ui-state-focus",
					"ui-state-disabled",
					"ui-tabs-panel",
					"ui-widget-content",
					"ui-corner-bottom",
					"ui-tabs-hide"
				].join( " " ) );
			}
		});

		if ( o.cookie ) {
			this._cookie( null, o.cookie );
		}

		return this;
	},

	add: function( url, label, index ) {
		if ( index === undefined ) {
			index = this.anchors.length;
		}

		var self = this,
			o = this.options,
			$li = $( o.tabTemplate.replace( /#\{href\}/g, url ).replace( /#\{label\}/g, label ) ),
			id = !url.indexOf( "#" ) ? url.replace( "#", "" ) : this._tabId( $( "a", $li )[ 0 ] );

		$li.addClass( "ui-state-default ui-corner-top" ).data( "destroy.tabs", true );

		// try to find an existing element before creating a new one
		var $panel = self.element.find( "#" + id );
		if ( !$panel.length ) {
			$panel = $( o.panelTemplate )
				.attr( "id", id )
				.data( "destroy.tabs", true );
		}
		$panel.addClass( "ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" );

		if ( index >= this.lis.length ) {
			$li.appendTo( this.list );
			$panel.appendTo( this.list[ 0 ].parentNode );
		} else {
			$li.insertBefore( this.lis[ index ] );
			$panel.insertBefore( this.panels[ index ] );
		}

		o.disabled = $.map( o.disabled, function( n, i ) {
			return n >= index ? ++n : n;
		});

		this._tabify();

		if ( this.anchors.length == 1 ) {
			o.selected = 0;
			$li.addClass( "ui-tabs-selected ui-state-active" );
			$panel.removeClass( "ui-tabs-hide" );
			this.element.queue( "tabs", function() {
				self._trigger( "show", null, self._ui( self.anchors[ 0 ], self.panels[ 0 ] ) );
			});

			this.load( 0 );
		}

		this._trigger( "add", null, this._ui( this.anchors[ index ], this.panels[ index ] ) );
		return this;
	},

	remove: function( index ) {
		index = this._getIndex( index );
		var o = this.options,
			$li = this.lis.eq( index ).remove(),
			$panel = this.panels.eq( index ).remove();

		// If selected tab was removed focus tab to the right or
		// in case the last tab was removed the tab to the left.
		if ( $li.hasClass( "ui-tabs-selected" ) && this.anchors.length > 1) {
			this.select( index + ( index + 1 < this.anchors.length ? 1 : -1 ) );
		}

		o.disabled = $.map(
			$.grep( o.disabled, function(n, i) {
				return n != index;
			}),
			function( n, i ) {
				return n >= index ? --n : n;
			});

		this._tabify();

		this._trigger( "remove", null, this._ui( $li.find( "a" )[ 0 ], $panel[ 0 ] ) );
		return this;
	},

	enable: function( index ) {
		index = this._getIndex( index );
		var o = this.options;
		if ( $.inArray( index, o.disabled ) == -1 ) {
			return;
		}

		this.lis.eq( index ).removeClass( "ui-state-disabled" );
		o.disabled = $.grep( o.disabled, function( n, i ) {
			return n != index;
		});

		this._trigger( "enable", null, this._ui( this.anchors[ index ], this.panels[ index ] ) );
		return this;
	},

	disable: function( index ) {
		index = this._getIndex( index );
		var self = this, o = this.options;
		// cannot disable already selected tab
		if ( index != o.selected ) {
			this.lis.eq( index ).addClass( "ui-state-disabled" );

			o.disabled.push( index );
			o.disabled.sort();

			this._trigger( "disable", null, this._ui( this.anchors[ index ], this.panels[ index ] ) );
		}

		return this;
	},

	select: function( index ) {
		index = this._getIndex( index );
		if ( index == -1 ) {
			if ( this.options.collapsible && this.options.selected != -1 ) {
				index = this.options.selected;
			} else {
				return this;
			}
		}
		this.anchors.eq( index ).trigger( this.options.event + ".tabs" );
		return this;
	},

	load: function( index ) {
		index = this._getIndex( index );
		var self = this,
			o = this.options,
			a = this.anchors.eq( index )[ 0 ],
			url = $.data( a, "load.tabs" );

		this.abort();

		// not remote or from cache
		if ( !url || this.element.queue( "tabs" ).length !== 0 && $.data( a, "cache.tabs" ) ) {
			this.element.dequeue( "tabs" );
			return;
		}

		// load remote from here on
		this.lis.eq( index ).addClass( "ui-state-processing" );

		if ( o.spinner ) {
			var span = $( "span", a );
			span.data( "label.tabs", span.html() ).html( o.spinner );
		}

		this.xhr = $.ajax( $.extend( {}, o.ajaxOptions, {
			url: url,
			success: function( r, s ) {
				self.element.find( self._sanitizeSelector( a.hash ) ).html( r );

				// take care of tab labels
				self._cleanup();

				if ( o.cache ) {
					$.data( a, "cache.tabs", true );
				}

				self._trigger( "load", null, self._ui( self.anchors[ index ], self.panels[ index ] ) );
				try {
					o.ajaxOptions.success( r, s );
				}
				catch ( e ) {}
			},
			error: function( xhr, s, e ) {
				// take care of tab labels
				self._cleanup();

				self._trigger( "load", null, self._ui( self.anchors[ index ], self.panels[ index ] ) );
				try {
					// Passing index avoid a race condition when this method is
					// called after the user has selected another tab.
					// Pass the anchor that initiated this request allows
					// loadError to manipulate the tab content panel via $(a.hash)
					o.ajaxOptions.error( xhr, s, index, a );
				}
				catch ( e ) {}
			}
		} ) );

		// last, so that load event is fired before show...
		self.element.dequeue( "tabs" );

		return this;
	},

	abort: function() {
		// stop possibly running animations
		this.element.queue( [] );
		this.panels.stop( false, true );

		// "tabs" queue must not contain more than two elements,
		// which are the callbacks for the latest clicked tab...
		this.element.queue( "tabs", this.element.queue( "tabs" ).splice( -2, 2 ) );

		// terminate pending requests from other tabs
		if ( this.xhr ) {
			this.xhr.abort();
			delete this.xhr;
		}

		// take care of tab labels
		this._cleanup();
		return this;
	},

	url: function( index, url ) {
		this.anchors.eq( index ).removeData( "cache.tabs" ).data( "load.tabs", url );
		return this;
	},

	length: function() {
		return this.anchors.length;
	}
});

$.extend( $.ui.tabs, {
	version: "1.8.18"
});

/*
 * Tabs Extensions
 */

/*
 * Rotate
 */
$.extend( $.ui.tabs.prototype, {
	rotation: null,
	rotate: function( ms, continuing ) {
		var self = this,
			o = this.options;

		var rotate = self._rotate || ( self._rotate = function( e ) {
			clearTimeout( self.rotation );
			self.rotation = setTimeout(function() {
				var t = o.selected;
				self.select( ++t < self.anchors.length ? t : 0 );
			}, ms );
			
			if ( e ) {
				e.stopPropagation();
			}
		});

		var stop = self._unrotate || ( self._unrotate = !continuing
			? function(e) {
				if (e.clientX) { // in case of a true click
					self.rotate(null);
				}
			}
			: function( e ) {
				t = o.selected;
				rotate();
			});

		// start rotation
		if ( ms ) {
			this.element.bind( "tabsshow", rotate );
			this.anchors.bind( o.event + ".tabs", stop );
			rotate();
		// stop rotation
		} else {
			clearTimeout( self.rotation );
			this.element.unbind( "tabsshow", rotate );
			this.anchors.unbind( o.event + ".tabs", stop );
			delete this._rotate;
			delete this._unrotate;
		}

		return this;
	}
});

})( jQuery );


	


(function ($) {

	/**
	 * --------------------------------------------------------------------
	 * jQuery-Plugin "daterangepicker.jQuery.js"
	 * by Scott Jehl, scott@filamentgroup.com
	 * reference article: http://www.filamentgroup.com/lab/update_date_range_picker_with_jquery_ui/
	 * demo page: http://www.filamentgroup.com/examples/daterangepicker/
	 *
	 * Copyright (c) 2010 Filament Group, Inc
	 * Dual licensed under the MIT (filamentgroup.com/examples/mit-license.txt) and GPL (filamentgroup.com/examples/gpl-license.txt) licenses.
	 *
	 * Dependencies: jquery, jquery UI datepicker, date.js, jQuery UI CSS Framework
	 *  12.15.2010 Made some fixes to resolve breaking changes introduced by jQuery UI 1.8.7
	 * --------------------------------------------------------------------
	 */
	$.fn.daterangepicker = function(settings){
		var rangeInput = $(this);

		//defaults
		var options = $.extend({
			presetRanges: [
				{text: 'Today', dateStart: 'today', dateEnd: 'today' },
				{text: 'Yesterday', dateStart: 'Yesterday', dateEnd: 'Yesterday' },
				{text: 'Tomorrow', dateStart: 'Tomorrow', dateEnd: 'Tomorrow' },
				{text: 'Last 7 days', dateStart: 'today-7days', dateEnd: 'today' },
				{text: 'Month to date', dateStart: function(){ return Date.parse('today').moveToFirstDayOfMonth();  }, dateEnd: 'today' },
				{text: 'Year to date', dateStart: function(){ var x= Date.parse('today'); x.setMonth(0); x.setDate(1); return x; }, dateEnd: 'today' },
				{text: 'The previous Month', dateStart: function(){ 
                    return Date.parse('1 month ago').moveToFirstDayOfMonth(); 
                }, dateEnd: function(){ return Date.parse('1 month ago').moveToLastDayOfMonth();  } 
                },
                {text: '2016', dateStart: '01/01/16', dateEnd: '31/12/16' },			
                {text: '2015', dateStart: '01/01/15', dateEnd: '31/12/15' },
                {text: '2014', dateStart: '01/01/14', dateEnd: '31/12/14' },
                {text: '2014 & 2015', dateStart: '01/01/14', dateEnd: '31/12/15' }
            ],
			//presetRanges: array of objects for each menu preset.
			//Each obj must have text, dateStart, dateEnd. dateStart, dateEnd accept date.js string or a function which returns a date object
			presets: {
				specificDate: 'Specific Date',
				allDatesBefore: 'All Dates Before',
				allDatesAfter: 'All Dates After',
				dateRange: 'Date Range'
            },
            rangeStartTitle: 'Start date',
            rangeEndTitle: 'End date',
            nextLinkText: 'Next',
            prevLinkText: 'Prev',
            target: rangeInput,
            doneButtonText: 'Done',
            earliestDate: Date.parse('-15years'), //earliest date allowed 
            latestDate: Date.parse('+15years'), //latest date allowed 
            constrainDates: false,
            rangeSplitter: '-', //string to use between dates in single input
            dateFormat: 'd/m/yy', // date formatting. Available formats: http://docs.jquery.com/UI/Datepicker/%24.datepicker.formatDate
            closeOnSelect: true, //if a complete selection is made, close the menu
            arrows: false,
            appendTo: 'body',
            onClose: function(){ },
            onOpen: function(){},
            onChange: function(){ },
            datepickerOptions: null //object containing native UI datepicker API options
        }, settings);
	
	

	//custom datepicker options, extended by options
	var datepickerOptions = {
		onSelect: function(dateText, inst) {
			var range_start = rp.find('.range-start');
			var range_end = rp.find('.range-end');
				
			if(rp.find('.ui-daterangepicker-specificDate').is('.ui-state-active')){
				range_end.datepicker('setDate', range_start.datepicker('getDate') ); 
			}
			
			$(this).trigger('constrainOtherPicker');
			
			var rangeA = fDate( range_start.datepicker('getDate') );
			var rangeB = fDate( range_end.datepicker('getDate') );
			
			//send back to input or inputs
			if(rangeInput.length == 2){
				rangeInput.eq(0).val(rangeA);
				rangeInput.eq(1).val(rangeB);
			}
			else{
				rangeInput.val((rangeA != rangeB) ? rangeA+' '+ options.rangeSplitter +' '+rangeB : rangeA);
			}
			//if closeOnSelect is true
			if(options.closeOnSelect){
				if(!rp.find('li.ui-state-active').is('.ui-daterangepicker-dateRange') && !rp.is(':animated') ){
					hideRP();
				}

				$(this).trigger('constrainOtherPicker');

				options.onChange();
			}
		},
		defaultDate: +0
	};

		//change event fires both when a calendar is updated or a change event on the input is triggered
		rangeInput.bind('change', options.onChange);

		//datepicker options from options
		options.datepickerOptions = (settings) ? $.extend(datepickerOptions, settings.datepickerOptions) : datepickerOptions;

		//Capture Dates from input(s)
		var inputDateA, inputDateB = Date.parse('today');
		var inputDateAtemp, inputDateBtemp;
		if(rangeInput.size() == 2){
			inputDateAtemp = Date.parse( rangeInput.eq(0).val() );
			inputDateBtemp = Date.parse( rangeInput.eq(1).val() );
			if(inputDateAtemp == null){inputDateAtemp = inputDateBtemp;}
			if(inputDateBtemp == null){inputDateBtemp = inputDateAtemp;}
		}
		else {
			inputDateAtemp = Date.parse( rangeInput.val().split(options.rangeSplitter)[0] );
			inputDateBtemp = Date.parse( rangeInput.val().split(options.rangeSplitter)[1] );
			if(inputDateBtemp == null){inputDateBtemp = inputDateAtemp;} //if one date, set both
		}
		if(inputDateAtemp != null){inputDateA = inputDateAtemp;}
		if(inputDateBtemp != null){inputDateB = inputDateBtemp;}


		//build picker and
		var rp = $('<div class="ui-daterangepicker ui-widget ui-helper-clearfix ui-widget-content ui-corner-all"></div>');
		var rpPresets = (function(){
			var ul = $('<ul class="ui-widget-content"></ul>').appendTo(rp);
			$.each(options.presetRanges,function(){
				$('<li class="ui-daterangepicker-'+ this.text.replace(/ /g, '') +' ui-corner-all"><a href="#">'+ this.text +'</a></li>')
				.data('dateStart', this.dateStart)
				.data('dateEnd', this.dateEnd)
				.appendTo(ul);
			});
			var x=0;
			$.each(options.presets, function(key, value) {
				$('<li class="ui-daterangepicker-'+ key +' preset_'+ x +' ui-helper-clearfix ui-corner-all"><span class="ui-icon ui-icon-triangle-1-e"></span><a href="#">'+ value +'</a></li>')
				.appendTo(ul);
				x++;
			});

			ul.find('li').hover(
					function(){
						$(this).addClass('ui-state-hover');
					},
					function(){
						$(this).removeClass('ui-state-hover');
					})
				.click(function(){
					rp.find('.ui-state-active').removeClass('ui-state-active');
					$(this).addClass('ui-state-active');
					clickActions($(this),rp, rpPickers, doneBtn);
					return false;
				});
			return ul;
		})();

		//function to format a date string
		function fDate(date){
			 if(!date.getDate()){return '';}
			 var day = date.getDate();
			 var month = date.getMonth();
			 var year = date.getFullYear();
			 month++; // adjust javascript month
			 var dateFormat = options.dateFormat;
			 return $.datepicker.formatDate( dateFormat, date );
		}


		$.fn.restoreDateFromData = function(){
			if($(this).data('saveDate')){
				$(this).datepicker('setDate', $(this).data('saveDate')).removeData('saveDate');
			}
			return this;
		};
		$.fn.saveDateToData = function(){
			if(!$(this).data('saveDate')){
				$(this).data('saveDate', $(this).datepicker('getDate') );
			}
			return this;
		};

		//show, hide, or toggle rangepicker
		function showRP(){
			if(rp.data('state') == 'closed'){
				positionRP();
				rp.fadeIn(300).data('state', 'open');
				options.onOpen();
			}
		}
		function hideRP(){
			if(rp.data('state') == 'open'){
				rp.fadeOut(300).data('state', 'closed');
				options.onClose();
			}
		}
		function toggleRP(){
			if( rp.data('state') == 'open' ){ hideRP(); }
			else { showRP(); }
		}
		function positionRP(){
			var relEl = riContain || rangeInput; //if arrows, use parent for offsets
			var riOffset = relEl.offset(),
				side = 'left',
				val = riOffset.left,
				offRight = $(window).width() - val - relEl.outerWidth();

			if(val > offRight){
				side = 'right', val =  offRight;
			}

			rp.parent().css(side, val).css('top', riOffset.top + relEl.outerHeight());
		}



		//preset menu click events
		function clickActions(el, rp, rpPickers, doneBtn){

			if(el.is('.ui-daterangepicker-specificDate')){
				//Specific Date (show the "start" calendar)
				doneBtn.hide();
				rpPickers.show();
				rp.find('.title-start').text( options.presets.specificDate );
				rp.find('.range-start').restoreDateFromData().css('opacity',1).show(400);
				rp.find('.range-end').restoreDateFromData().css('opacity',0).hide(400);
				setTimeout(function(){doneBtn.fadeIn();}, 400);
			}
			else if(el.is('.ui-daterangepicker-allDatesBefore')){
				//All dates before specific date (show the "end" calendar and set the "start" calendar to the earliest date)
				doneBtn.hide();
				rpPickers.show();
				rp.find('.title-end').text( options.presets.allDatesBefore );
				rp.find('.range-start').saveDateToData().datepicker('setDate', options.earliestDate).css('opacity',0).hide(400);
				rp.find('.range-end').restoreDateFromData().css('opacity',1).show(400);
				setTimeout(function(){doneBtn.fadeIn();}, 400);
			}
			else if(el.is('.ui-daterangepicker-allDatesAfter')){
				//All dates after specific date (show the "start" calendar and set the "end" calendar to the latest date)
				doneBtn.hide();
				rpPickers.show();
				rp.find('.title-start').text( options.presets.allDatesAfter );
				rp.find('.range-start').restoreDateFromData().css('opacity',1).show(400);
				rp.find('.range-end').saveDateToData().datepicker('setDate', options.latestDate).css('opacity',0).hide(400);
				setTimeout(function(){doneBtn.fadeIn();}, 400);
			}
			else if(el.is('.ui-daterangepicker-dateRange')){
				//Specific Date range (show both calendars)
				doneBtn.hide();
				rpPickers.show();
				rp.find('.title-start').text(options.rangeStartTitle);
				rp.find('.title-end').text(options.rangeEndTitle);
				rp.find('.range-start').restoreDateFromData().css('opacity',1).show(400);
				rp.find('.range-end').restoreDateFromData().css('opacity',1).show(400);
				setTimeout(function(){doneBtn.fadeIn();}, 400);
			}
			else {
				//custom date range specified in the options (no calendars shown)
				doneBtn.hide();
				rp.find('.range-start, .range-end').css('opacity',0).hide(400, function(){
					rpPickers.hide();
				});
				var dateStart = (typeof el.data('dateStart') == 'string') ? Date.parse(el.data('dateStart')) : el.data('dateStart')();
				var dateEnd = (typeof el.data('dateEnd') == 'string') ? Date.parse(el.data('dateEnd')) : el.data('dateEnd')();
				rp.find('.range-start').datepicker('setDate', dateStart).find('.ui-datepicker-current-day').trigger('click');
				rp.find('.range-end').datepicker('setDate', dateEnd).find('.ui-datepicker-current-day').trigger('click');
			}

			return false;
		}


		//picker divs
		var rpPickers = $('<div class="ranges ui-widget-header ui-corner-all ui-helper-clearfix"><div class="range-start"><span class="title-start">Start Date</span></div><div class="range-end"><span class="title-end">End Date</span></div></div>').appendTo(rp);
		rpPickers.find('.range-start, .range-end')
			.datepicker(options.datepickerOptions);


		rpPickers.find('.range-start').datepicker('setDate', inputDateA);
		rpPickers.find('.range-end').datepicker('setDate', inputDateB);

		rpPickers.find('.range-start, .range-end')
			.bind('constrainOtherPicker', function(){
				if(options.constrainDates){
					//constrain dates
					if($(this).is('.range-start')){
						rp.find('.range-end').datepicker( "option", "minDate", $(this).datepicker('getDate'));
					}
					else{
						rp.find('.range-start').datepicker( "option", "maxDate", $(this).datepicker('getDate'));
					}
				}
			})
			.trigger('constrainOtherPicker');

		var doneBtn = $('<button class="btnDone ui-state-default ui-corner-all">'+ options.doneButtonText +'</button>')
		.click(function(){
			rp.find('.ui-datepicker-current-day').trigger('click');
			hideRP();
		})
		.hover(
				function(){
					$(this).addClass('ui-state-hover');
				},
				function(){
					$(this).removeClass('ui-state-hover');
				}
		)
		.appendTo(rpPickers);




		//inputs toggle rangepicker visibility
		$(this).click(function(){
			toggleRP();
			return false;
		});
		//hide em all
		rpPickers.hide().find('.range-start, .range-end, .btnDone').hide();

		rp.data('state', 'closed');

		//Fixed for jQuery UI 1.8.7 - Calendars are hidden otherwise!
		rpPickers.find('.ui-datepicker').css("display","block");

		//inject rp
		$(options.appendTo).append(rp);

		//wrap and position
		rp.wrap('<div class="ui-daterangepickercontain"></div>');

		//add arrows (only available on one input)
		if(options.arrows && rangeInput.size()==1){
			var prevLink = $('<a href="#" class="ui-daterangepicker-prev ui-corner-all" title="'+ options.prevLinkText +'"><span class="ui-icon ui-icon-circle-triangle-w">'+ options.prevLinkText +'</span></a>');
			var nextLink = $('<a href="#" class="ui-daterangepicker-next ui-corner-all" title="'+ options.nextLinkText +'"><span class="ui-icon ui-icon-circle-triangle-e">'+ options.nextLinkText +'</span></a>');

			$(this)
			.addClass('ui-rangepicker-input ui-widget-content')
			.wrap('<div class="ui-daterangepicker-arrows ui-widget ui-widget-header ui-helper-clearfix ui-corner-all"></div>')
			.before( prevLink )
			.before( nextLink )
			.parent().find('a').click(function(){
				var dateA = rpPickers.find('.range-start').datepicker('getDate');
				var dateB = rpPickers.find('.range-end').datepicker('getDate');
				var diff = Math.abs( new TimeSpan(dateA - dateB).getTotalMilliseconds() ) + 86400000; //difference plus one day
				if($(this).is('.ui-daterangepicker-prev')){ diff = -diff; }

				rpPickers.find('.range-start, .range-end ').each(function(){
						var thisDate = $(this).datepicker( "getDate");
						if(thisDate == null){return false;}
						$(this).datepicker( "setDate", thisDate.add({milliseconds: diff}) ).find('.ui-datepicker-current-day').trigger('click');
				});
				return false;
			})
			.hover(
				function(){
					$(this).addClass('ui-state-hover');
				},
				function(){
					$(this).removeClass('ui-state-hover');
				});

			var riContain = rangeInput.parent();
		}


		$(document).click(function(){
			if (rp.is(':visible')) {
				hideRP();
			}
		});

		rp.click(function(){return false;}).hide();
		return this;
	}

})(jQuery);
	
	
	
	
	
$(document).ready(function() {
	

// was 1.01 alpha 1 , updated to 23/8/16 to resolve conflict with google searchbar preventing streetview access in certain situations

/** 
 * @overview datejs
 * @version 1.0.0-rc3
 * @author Gregory Wild-Smith <gregory@wild-smith.com>
 * @copyright 2015 Gregory Wild-Smith
 * @license MIT
 * @homepage https://github.com/abritinthebay/datejs
 */
/*
 2015 Gregory Wild-Smith
 @license MIT
 @homepage https://github.com/abritinthebay/datejs
 2015 Gregory Wild-Smith
 @license MIT
 @homepage https://github.com/abritinthebay/datejs
*/
Date.CultureStrings=Date.CultureStrings||{};
Date.CultureStrings["en-GB"]={name:"en-GB",englishName:"English (United Kingdom)",nativeName:"English (United Kingdom)",Sunday:"Sunday",Monday:"Monday",Tuesday:"Tuesday",Wednesday:"Wednesday",Thursday:"Thursday",Friday:"Friday",Saturday:"Saturday",Sun:"Sun",Mon:"Mon",Tue:"Tue",Wed:"Wed",Thu:"Thu",Fri:"Fri",Sat:"Sat",Su:"Su",Mo:"Mo",Tu:"Tu",We:"We",Th:"Th",Fr:"Fr",Sa:"Sa",S_Sun_Initial:"S",M_Mon_Initial:"M",T_Tue_Initial:"T",W_Wed_Initial:"W",T_Thu_Initial:"T",F_Fri_Initial:"F",S_Sat_Initial:"S",January:"January",
February:"February",March:"March",April:"April",May:"May",June:"June",July:"July",August:"August",September:"September",October:"October",November:"November",December:"December",Jan_Abbr:"Jan",Feb_Abbr:"Feb",Mar_Abbr:"Mar",Apr_Abbr:"Apr",May_Abbr:"May",Jun_Abbr:"Jun",Jul_Abbr:"Jul",Aug_Abbr:"Aug",Sep_Abbr:"Sep",Oct_Abbr:"Oct",Nov_Abbr:"Nov",Dec_Abbr:"Dec",AM:"AM",PM:"PM",firstDayOfWeek:1,twoDigitYearMax:2029,mdy:"dmy","M/d/yyyy":"dd/MM/yyyy","dddd, MMMM dd, yyyy":"dd MMMM yyyy","h:mm tt":"HH:mm",
"h:mm:ss tt":"HH:mm:ss","dddd, MMMM dd, yyyy h:mm:ss tt":"dd MMMM yyyy HH:mm:ss","yyyy-MM-ddTHH:mm:ss":"yyyy-MM-ddTHH:mm:ss","yyyy-MM-dd HH:mm:ssZ":"yyyy-MM-dd HH:mm:ssZ","ddd, dd MMM yyyy HH:mm:ss":"ddd, dd MMM yyyy HH:mm:ss","MMMM dd":"dd MMMM","MMMM, yyyy":"MMMM yyyy","/jan(uary)?/":"jan(uary)?","/feb(ruary)?/":"feb(ruary)?","/mar(ch)?/":"mar(ch)?","/apr(il)?/":"apr(il)?","/may/":"may","/jun(e)?/":"jun(e)?","/jul(y)?/":"jul(y)?","/aug(ust)?/":"aug(ust)?","/sep(t(ember)?)?/":"sep(t(ember)?)?","/oct(ober)?/":"oct(ober)?",
"/nov(ember)?/":"nov(ember)?","/dec(ember)?/":"dec(ember)?","/^su(n(day)?)?/":"^su(n(day)?)?","/^mo(n(day)?)?/":"^mo(n(day)?)?","/^tu(e(s(day)?)?)?/":"^tu(e(s(day)?)?)?","/^we(d(nesday)?)?/":"^we(d(nesday)?)?","/^th(u(r(s(day)?)?)?)?/":"^th(u(r(s(day)?)?)?)?","/^fr(i(day)?)?/":"^fr(i(day)?)?","/^sa(t(urday)?)?/":"^sa(t(urday)?)?","/^next/":"^next","/^last|past|prev(ious)?/":"^last|past|prev(ious)?","/^(\\+|aft(er)?|from|hence)/":"^(\\+|aft(er)?|from|hence)","/^(\\-|bef(ore)?|ago)/":"^(\\-|bef(ore)?|ago)",
"/^yes(terday)?/":"^yes(terday)?","/^t(od(ay)?)?/":"^t(od(ay)?)?","/^tom(orrow)?/":"^tom(orrow)?","/^n(ow)?/":"^n(ow)?","/^ms|milli(second)?s?/":"^ms|milli(second)?s?","/^sec(ond)?s?/":"^sec(ond)?s?","/^mn|min(ute)?s?/":"^mn|min(ute)?s?","/^h(our)?s?/":"^h(our)?s?","/^w(eek)?s?/":"^w(eek)?s?","/^m(onth)?s?/":"^m(onth)?s?","/^d(ay)?s?/":"^d(ay)?s?","/^y(ear)?s?/":"^y(ear)?s?","/^(a|p)/":"^(a|p)","/^(a\\.?m?\\.?|p\\.?m?\\.?)/":"^(a\\.?m?\\.?|p\\.?m?\\.?)","/^((e(s|d)t|c(s|d)t|m(s|d)t|p(s|d)t)|((gmt)?\\s*(\\+|\\-)\\s*\\d\\d\\d\\d?)|gmt|utc)/":"^((e(s|d)t|c(s|d)t|m(s|d)t|p(s|d)t)|((gmt)?\\s*(\\+|\\-)\\s*\\d\\d\\d\\d?)|gmt|utc)",
"/^\\s*(st|nd|rd|th)/":"^\\s*(st|nd|rd|th)","/^\\s*(\\:|a(?!u|p)|p)/":"^\\s*(\\:|a(?!u|p)|p)",LINT:"LINT",TOT:"TOT",CHAST:"CHAST",NZST:"NZST",NFT:"NFT",SBT:"SBT",AEST:"AEST",ACST:"ACST",JST:"JST",CWST:"CWST",CT:"CT",ICT:"ICT",MMT:"MMT",BIOT:"BST",NPT:"NPT",IST:"IST",PKT:"PKT",AFT:"AFT",MSK:"MSK",IRST:"IRST",FET:"FET",EET:"EET",CET:"CET",UTC:"UTC",GMT:"GMT",CVT:"CVT",GST:"GST",BRT:"BRT",NST:"NST",AST:"AST",EST:"EST",CST:"CST",MST:"MST",PST:"PST",AKST:"AKST",MIT:"MIT",HST:"HST",SST:"SST",BIT:"BIT",
CHADT:"CHADT",NZDT:"NZDT",AEDT:"AEDT",ACDT:"ACDT",AZST:"AZST",IRDT:"IRDT",EEST:"EEST",CEST:"CEST",BST:"BST",PMDT:"PMDT",ADT:"ADT",NDT:"NDT",EDT:"EDT",CDT:"CDT",MDT:"MDT",PDT:"PDT",AKDT:"AKDT",HADT:"HADT"};Date.CultureStrings.lang="en-GB";
(function(){var h=Date,f=Date.CultureStrings?Date.CultureStrings.lang:null,d={},c={getFromKey:function(a,b){var e;e=Date.CultureStrings&&Date.CultureStrings[b]&&Date.CultureStrings[b][a]?Date.CultureStrings[b][a]:c.buildFromDefault(a);"/"===a.charAt(0)&&(e=c.buildFromRegex(a,b));return e},getFromObjectValues:function(a,b){var e,g={};for(e in a)a.hasOwnProperty(e)&&(g[e]=c.getFromKey(a[e],b));return g},getFromObjectKeys:function(a,b){var e,g={};for(e in a)a.hasOwnProperty(e)&&(g[c.getFromKey(e,b)]=
a[e]);return g},getFromArray:function(a,b){for(var e=[],g=0;g<a.length;g++)g in a&&(e[g]=c.getFromKey(a[g],b));return e},buildFromDefault:function(a){var b,e,g;switch(a){case "name":b="en-US";break;case "englishName":b="English (United States)";break;case "nativeName":b="English (United States)";break;case "twoDigitYearMax":b=2049;break;case "firstDayOfWeek":b=0;break;default:if(b=a,g=a.split("_"),e=g.length,1<e&&"/"!==a.charAt(0)&&(a=g[e-1].toLowerCase(),"initial"===a||"abbr"===a))b=g[0]}return b},
buildFromRegex:function(a,b){return Date.CultureStrings&&Date.CultureStrings[b]&&Date.CultureStrings[b][a]?new RegExp(Date.CultureStrings[b][a],"i"):new RegExp(a.replace(RegExp("/","g"),""),"i")}},a=function(a,b){var e=b?b:f;d[a]=a;return"object"===typeof a?a instanceof Array?c.getFromArray(a,e):c.getFromObjectKeys(a,e):c.getFromKey(a,e)},b=function(a){a=Date.Config.i18n+a+".js";var b=document.getElementsByTagName("head")[0]||document.documentElement,e=document.createElement("script");e.src=a;var g=
{done:function(){}};e.onload=e.onreadystatechange=function(){this.readyState&&"loaded"!==this.readyState&&"complete"!==this.readyState||(g.done(),b.removeChild(e))};setTimeout(function(){b.insertBefore(e,b.firstChild)},0);return{done:function(a){g.done=function(){a&&setTimeout(a,0)}}}},e={buildFromMethodHash:function(a){for(var b in a)a.hasOwnProperty(b)&&(a[b]=e[a[b]]());return a},timeZoneDST:function(){return a({CHADT:"+1345",NZDT:"+1300",AEDT:"+1100",ACDT:"+1030",AZST:"+0500",IRDT:"+0430",EEST:"+0300",
CEST:"+0200",BST:"+0100",PMDT:"-0200",ADT:"-0300",NDT:"-0230",EDT:"-0400",CDT:"-0500",MDT:"-0600",PDT:"-0700",AKDT:"-0800",HADT:"-0900"})},timeZoneStandard:function(){return a({LINT:"+1400",TOT:"+1300",CHAST:"+1245",NZST:"+1200",NFT:"+1130",SBT:"+1100",AEST:"+1000",ACST:"+0930",JST:"+0900",CWST:"+0845",CT:"+0800",ICT:"+0700",MMT:"+0630",BST:"+0600",NPT:"+0545",IST:"+0530",PKT:"+0500",AFT:"+0430",MSK:"+0400",IRST:"+0330",FET:"+0300",EET:"+0200",CET:"+0100",GMT:"+0000",UTC:"+0000",CVT:"-0100",GST:"-0200",
BRT:"-0300",NST:"-0330",AST:"-0400",EST:"-0500",CST:"-0600",MST:"-0700",PST:"-0800",AKST:"-0900",MIT:"-0930",HST:"-1000",SST:"-1100",BIT:"-1200"})},timeZones:function(a){var b;a.timezones=[];for(b in a.abbreviatedTimeZoneStandard)a.abbreviatedTimeZoneStandard.hasOwnProperty(b)&&a.timezones.push({name:b,offset:a.abbreviatedTimeZoneStandard[b]});for(b in a.abbreviatedTimeZoneDST)a.abbreviatedTimeZoneDST.hasOwnProperty(b)&&a.timezones.push({name:b,offset:a.abbreviatedTimeZoneDST[b],dst:!0});return a.timezones},
days:function(){return a("Sunday Monday Tuesday Wednesday Thursday Friday Saturday".split(" "))},dayAbbr:function(){return a("Sun Mon Tue Wed Thu Fri Sat".split(" "))},dayShortNames:function(){return a("Su Mo Tu We Th Fr Sa".split(" "))},dayFirstLetters:function(){return a("S_Sun_Initial M_Mon_Initial T_Tues_Initial W_Wed_Initial T_Thu_Initial F_Fri_Initial S_Sat_Initial".split(" "))},months:function(){return a("January February March April May June July August September October November December".split(" "))},
monthAbbr:function(){return a("Jan_Abbr Feb_Abbr Mar_Abbr Apr_Abbr May_Abbr Jun_Abbr Jul_Abbr Aug_Abbr Sep_Abbr Oct_Abbr Nov_Abbr Dec_Abbr".split(" "))},formatPatterns:function(){return c.getFromObjectValues({shortDate:"M/d/yyyy",longDate:"dddd, MMMM dd, yyyy",shortTime:"h:mm tt",longTime:"h:mm:ss tt",fullDateTime:"dddd, MMMM dd, yyyy h:mm:ss tt",sortableDateTime:"yyyy-MM-ddTHH:mm:ss",universalSortableDateTime:"yyyy-MM-dd HH:mm:ssZ",rfc1123:"ddd, dd MMM yyyy HH:mm:ss",monthDay:"MMMM dd",yearMonth:"MMMM, yyyy"},
Date.i18n.currentLanguage())},regex:function(){return c.getFromObjectValues({inTheMorning:"/( in the )(morn(ing)?)\\b/",thisMorning:"/(this )(morn(ing)?)\\b/",amThisMorning:"/(\b\\d(am)? )(this )(morn(ing)?)/",inTheEvening:"/( in the )(even(ing)?)\\b/",thisEvening:"/(this )(even(ing)?)\\b/",pmThisEvening:"/(\b\\d(pm)? )(this )(even(ing)?)/",jan:"/jan(uary)?/",feb:"/feb(ruary)?/",mar:"/mar(ch)?/",apr:"/apr(il)?/",may:"/may/",jun:"/jun(e)?/",jul:"/jul(y)?/",aug:"/aug(ust)?/",sep:"/sep(t(ember)?)?/",
oct:"/oct(ober)?/",nov:"/nov(ember)?/",dec:"/dec(ember)?/",sun:"/^su(n(day)?)?/",mon:"/^mo(n(day)?)?/",tue:"/^tu(e(s(day)?)?)?/",wed:"/^we(d(nesday)?)?/",thu:"/^th(u(r(s(day)?)?)?)?/",fri:"/fr(i(day)?)?/",sat:"/^sa(t(urday)?)?/",future:"/^next/",past:"/^last|past|prev(ious)?/",add:"/^(\\+|aft(er)?|from|hence)/",subtract:"/^(\\-|bef(ore)?|ago)/",yesterday:"/^yes(terday)?/",today:"/^t(od(ay)?)?/",tomorrow:"/^tom(orrow)?/",now:"/^n(ow)?/",millisecond:"/^ms|milli(second)?s?/",second:"/^sec(ond)?s?/",
minute:"/^mn|min(ute)?s?/",hour:"/^h(our)?s?/",week:"/^w(eek)?s?/",month:"/^m(onth)?s?/",day:"/^d(ay)?s?/",year:"/^y(ear)?s?/",shortMeridian:"/^(a|p)/",longMeridian:"/^(a\\.?m?\\.?|p\\.?m?\\.?)/",timezone:"/^((e(s|d)t|c(s|d)t|m(s|d)t|p(s|d)t)|((gmt)?\\s*(\\+|\\-)\\s*\\d\\d\\d\\d?)|gmt|utc)/",ordinalSuffix:"/^\\s*(st|nd|rd|th)/",timeContext:"/^\\s*(\\:|a(?!u|p)|p)/"},Date.i18n.currentLanguage())}},g=function(){var a=c.getFromObjectValues({name:"name",englishName:"englishName",nativeName:"nativeName",
amDesignator:"AM",pmDesignator:"PM",firstDayOfWeek:"firstDayOfWeek",twoDigitYearMax:"twoDigitYearMax",dateElementOrder:"mdy"},Date.i18n.currentLanguage()),b=e.buildFromMethodHash({dayNames:"days",abbreviatedDayNames:"dayAbbr",shortestDayNames:"dayShortNames",firstLetterDayNames:"dayFirstLetters",monthNames:"months",abbreviatedMonthNames:"monthAbbr",formatPatterns:"formatPatterns",regexPatterns:"regex",abbreviatedTimeZoneDST:"timeZoneDST",abbreviatedTimeZoneStandard:"timeZoneStandard"}),g;for(g in b)b.hasOwnProperty(g)&&
(a[g]=b[g]);e.timeZones(a);return a};h.i18n={__:function(m,b){return a(m,b)},currentLanguage:function(){return f||"en-US"},setLanguage:function(a,e,c){var d=!1;if(e||"en-US"===a||Date.CultureStrings&&Date.CultureStrings[a])f=a,Date.CultureStrings=Date.CultureStrings||{},Date.CultureStrings.lang=a,Date.CultureInfo=new g;else if(!Date.CultureStrings||!Date.CultureStrings[a])if("undefined"!==typeof exports&&this.exports!==exports)try{require("../i18n/"+a+".js"),f=a,Date.CultureStrings.lang=a,Date.CultureInfo=
new g}catch(p){throw Error("The DateJS IETF language tag '"+a+"' could not be loaded by Node. It likely does not exist.");}else if(Date.Config&&Date.Config.i18n)d=!0,b(a).done(function(){f=a;Date.CultureStrings=Date.CultureStrings||{};Date.CultureStrings.lang=a;Date.CultureInfo=new g;h.Parsing.Normalizer.buildReplaceData();h.Grammar&&h.Grammar.buildGrammarFormats();c&&setTimeout(c,0)});else return Date.console.error("The DateJS IETF language tag '"+a+"' is not available and has not been loaded."),
!1;h.Parsing.Normalizer.buildReplaceData();h.Grammar&&h.Grammar.buildGrammarFormats();!d&&c&&setTimeout(c,0)},getLoggedKeys:function(){return d},updateCultureInfo:function(){Date.CultureInfo=new g}};h.i18n.updateCultureInfo()})();
(function(){var h=Date,f=h.prototype,d=function(a,b){b||(b=2);return("000"+a).slice(-1*b)};h.console="undefined"!==typeof window&&"undefined"!==typeof window.console&&"undefined"!==typeof window.console.log?console:{log:function(){},error:function(){}};h.Config=h.Config||{};h.initOverloads=function(){h.now?h._now||(h._now=h.now):h._now=function(){return(new Date).getTime()};h.now=function(a){return a?h.present():h._now()};f.toISOString||(f.toISOString=function(){return this.getUTCFullYear()+"-"+d(this.getUTCMonth()+
1)+"-"+d(this.getUTCDate())+"T"+d(this.getUTCHours())+":"+d(this.getUTCMinutes())+":"+d(this.getUTCSeconds())+"."+String((this.getUTCMilliseconds()/1E3).toFixed(3)).slice(2,5)+"Z"});void 0===f._toString&&(f._toString=f.toString)};h.initOverloads();h.today=function(){return(new Date).clearTime()};h.present=function(){return new Date};h.compare=function(a,b){if(isNaN(a)||isNaN(b))throw Error(a+" - "+b);if(a instanceof Date&&b instanceof Date)return a<b?-1:a>b?1:0;throw new TypeError(a+" - "+b);};h.equals=
function(a,b){return 0===a.compareTo(b)};h.getDayName=function(a){return Date.CultureInfo.dayNames[a]};h.getDayNumberFromName=function(a){var b=Date.CultureInfo.dayNames,e=Date.CultureInfo.abbreviatedDayNames,g=Date.CultureInfo.shortestDayNames;a=a.toLowerCase();for(var m=0;m<b.length;m++)if(b[m].toLowerCase()===a||e[m].toLowerCase()===a||g[m].toLowerCase()===a)return m;return-1};h.getMonthNumberFromName=function(a){var b=Date.CultureInfo.monthNames,e=Date.CultureInfo.abbreviatedMonthNames;a=a.toLowerCase();
for(var g=0;g<b.length;g++)if(b[g].toLowerCase()===a||e[g].toLowerCase()===a)return g;return-1};h.getMonthName=function(a){return Date.CultureInfo.monthNames[a]};h.isLeapYear=function(a){return 0===a%4&&0!==a%100||0===a%400};h.getDaysInMonth=function(a,b){!b&&h.validateMonth(a)&&(b=a,a=Date.today().getFullYear());return[31,h.isLeapYear(a)?29:28,31,30,31,30,31,31,30,31,30,31][b]};f.getDaysInMonth=function(){return h.getDaysInMonth(this.getFullYear(),this.getMonth())};h.getTimezoneAbbreviation=function(a,
b){var e,g=b?Date.CultureInfo.abbreviatedTimeZoneDST:Date.CultureInfo.abbreviatedTimeZoneStandard;for(e in g)if(g.hasOwnProperty(e)&&g[e]===a)return e;return null};h.getTimezoneOffset=function(a,b){var e,g=[],m=Date.CultureInfo.timezones;a||(a=(new Date).getTimezone());for(e=0;e<m.length;e++)m[e].name===a.toUpperCase()&&g.push(e);if(!m[g[0]])return null;if(1!==g.length&&b)for(e=0;e<g.length;e++){if(m[g[e]].dst)return m[g[e]].offset}else return m[g[0]].offset};h.getQuarter=function(a){a=a||new Date;
return[1,2,3,4][Math.floor(a.getMonth()/3)]};h.getDaysLeftInQuarter=function(a){a=a||new Date;var b=new Date(a);b.setMonth(b.getMonth()+3-b.getMonth()%3,0);return Math.floor((b-a)/864E5)};var c=function(a,b,e,g){if("undefined"===typeof a)return!1;if("number"!==typeof a)throw new TypeError(a+" is not a Number.");return a<b||a>e?!1:!0};h.validateMillisecond=function(a){return c(a,0,999,"millisecond")};h.validateSecond=function(a){return c(a,0,59,"second")};h.validateMinute=function(a){return c(a,0,
59,"minute")};h.validateHour=function(a){return c(a,0,23,"hour")};h.validateDay=function(a,b,e){return void 0===b||null===b||void 0===e||null===e?!1:c(a,1,h.getDaysInMonth(b,e),"day")};h.validateWeek=function(a){return c(a,0,53,"week")};h.validateMonth=function(a){return c(a,0,11,"month")};h.validateYear=function(a){return c(a,-271822,275760,"year")};h.validateTimezone=function(a){return 1==={ACDT:1,ACST:1,ACT:1,ADT:1,AEDT:1,AEST:1,AFT:1,AKDT:1,AKST:1,AMST:1,AMT:1,ART:1,AST:1,AWDT:1,AWST:1,AZOST:1,
AZT:1,BDT:1,BIOT:1,BIT:1,BOT:1,BRT:1,BST:1,BTT:1,CAT:1,CCT:1,CDT:1,CEDT:1,CEST:1,CET:1,CHADT:1,CHAST:1,CHOT:1,ChST:1,CHUT:1,CIST:1,CIT:1,CKT:1,CLST:1,CLT:1,COST:1,COT:1,CST:1,CT:1,CVT:1,CWST:1,CXT:1,DAVT:1,DDUT:1,DFT:1,EASST:1,EAST:1,EAT:1,ECT:1,EDT:1,EEDT:1,EEST:1,EET:1,EGST:1,EGT:1,EIT:1,EST:1,FET:1,FJT:1,FKST:1,FKT:1,FNT:1,GALT:1,GAMT:1,GET:1,GFT:1,GILT:1,GIT:1,GMT:1,GST:1,GYT:1,HADT:1,HAEC:1,HAST:1,HKT:1,HMT:1,HOVT:1,HST:1,ICT:1,IDT:1,IOT:1,IRDT:1,IRKT:1,IRST:1,IST:1,JST:1,KGT:1,KOST:1,KRAT:1,
KST:1,LHST:1,LINT:1,MAGT:1,MART:1,MAWT:1,MDT:1,MET:1,MEST:1,MHT:1,MIST:1,MIT:1,MMT:1,MSK:1,MST:1,MUT:1,MVT:1,MYT:1,NCT:1,NDT:1,NFT:1,NPT:1,NST:1,NT:1,NUT:1,NZDT:1,NZST:1,OMST:1,ORAT:1,PDT:1,PET:1,PETT:1,PGT:1,PHOT:1,PHT:1,PKT:1,PMDT:1,PMST:1,PONT:1,PST:1,PYST:1,PYT:1,RET:1,ROTT:1,SAKT:1,SAMT:1,SAST:1,SBT:1,SCT:1,SGT:1,SLST:1,SRT:1,SST:1,SYOT:1,TAHT:1,THA:1,TFT:1,TJT:1,TKT:1,TLT:1,TMT:1,TOT:1,TVT:1,UCT:1,ULAT:1,UTC:1,UYST:1,UYT:1,UZT:1,VET:1,VLAT:1,VOLT:1,VOST:1,VUT:1,WAKT:1,WAST:1,WAT:1,WEDT:1,WEST:1,
WET:1,WST:1,YAKT:1,YEKT:1,Z:1}[a]};h.validateTimezoneOffset=function(a){return-841<a&&721>a}})();
(function(){var h=Date,f=h.prototype,d=function(a,b){b||(b=2);return("000"+a).slice(-1*b)},c=function(a){var b={},e=this,g,c;c=function(b,g,c){if("day"===b){b=void 0!==a.month?a.month:e.getMonth();var d=void 0!==a.year?a.year:e.getFullYear();return h[g](c,d,b)}return h[g](c)};for(g in a)if(Object.prototype.hasOwnProperty.call(a,g)){var d="validate"+g.charAt(0).toUpperCase()+g.slice(1);h[d]&&null!==a[g]&&c(g,d,a[g])&&(b[g]=a[g])}return b};f.clearTime=function(){this.setHours(0);this.setMinutes(0);
this.setSeconds(0);this.setMilliseconds(0);return this};f.setTimeToNow=function(){var a=new Date;this.setHours(a.getHours());this.setMinutes(a.getMinutes());this.setSeconds(a.getSeconds());this.setMilliseconds(a.getMilliseconds());return this};f.clone=function(){return new Date(this.getTime())};f.compareTo=function(a){return Date.compare(this,a)};f.equals=function(a){return Date.equals(this,void 0!==a?a:new Date)};f.between=function(a,b){return this.getTime()>=a.getTime()&&this.getTime()<=b.getTime()};
f.isAfter=function(a){return 1===this.compareTo(a||new Date)};f.isBefore=function(a){return-1===this.compareTo(a||new Date)};f.isToday=f.isSameDay=function(a){return this.clone().clearTime().equals((a||new Date).clone().clearTime())};f.addMilliseconds=function(a){if(!a)return this;this.setTime(this.getTime()+1*a);return this};f.addSeconds=function(a){return a?this.addMilliseconds(1E3*a):this};f.addMinutes=function(a){return a?this.addMilliseconds(6E4*a):this};f.addHours=function(a){return a?this.addMilliseconds(36E5*
a):this};f.addDays=function(a){if(!a)return this;this.setDate(this.getDate()+1*a);return this};f.addWeekdays=function(a){if(!a)return this;var b=this.getDay(),e=Math.ceil(Math.abs(a)/7);(0===b||6===b)&&0<a&&(this.next().monday(),this.addDays(-1),b=this.getDay());if(0>a){for(;0>a;)this.addDays(-1),b=this.getDay(),0!==b&&6!==b&&a++;return this}if(5<a||6-b<=a)a+=2*e;return this.addDays(a)};f.addWeeks=function(a){return a?this.addDays(7*a):this};f.addMonths=function(a){if(!a)return this;var b=this.getDate();
this.setDate(1);this.setMonth(this.getMonth()+1*a);this.setDate(Math.min(b,h.getDaysInMonth(this.getFullYear(),this.getMonth())));return this};f.addQuarters=function(a){return a?this.addMonths(3*a):this};f.addYears=function(a){return a?this.addMonths(12*a):this};f.add=function(a){if("number"===typeof a)return this._orient=a,this;a.day&&0!==a.day-this.getDate()&&this.setDate(a.day);a.milliseconds&&this.addMilliseconds(a.milliseconds);a.seconds&&this.addSeconds(a.seconds);a.minutes&&this.addMinutes(a.minutes);
a.hours&&this.addHours(a.hours);a.weeks&&this.addWeeks(a.weeks);a.months&&this.addMonths(a.months);a.years&&this.addYears(a.years);a.days&&this.addDays(a.days);return this};f.getWeek=function(a){var b=new Date(this.valueOf());a?(b.addMinutes(b.getTimezoneOffset()),a=b.clone()):a=this;a=(a.getDay()+6)%7;b.setDate(b.getDate()-a+3);a=b.valueOf();b.setMonth(0,1);4!==b.getDay()&&b.setMonth(0,1+(4-b.getDay()+7)%7);return 1+Math.ceil((a-b)/6048E5)};f.getISOWeek=function(){return d(this.getWeek(!0))};f.setWeek=
function(a){return 0===a-this.getWeek()?1!==this.getDay()?this.moveToDayOfWeek(1,1<this.getDay()?-1:1):this:this.moveToDayOfWeek(1,1<this.getDay()?-1:1).addWeeks(a-this.getWeek())};f.setQuarter=function(a){a=Math.abs(3*(a-1)+1);return this.setMonth(a,1)};f.getQuarter=function(){return Date.getQuarter(this)};f.getDaysLeftInQuarter=function(){return Date.getDaysLeftInQuarter(this)};f.moveToNthOccurrence=function(a,b){if("Weekday"===a){if(0<b)this.moveToFirstDayOfMonth(),this.is().weekday()&&--b;else if(0>
b)this.moveToLastDayOfMonth(),this.is().weekday()&&(b+=1);else return this;return this.addWeekdays(b)}var e=0;if(0<b)e=b-1;else if(-1===b)return this.moveToLastDayOfMonth(),this.getDay()!==a&&this.moveToDayOfWeek(a,-1),this;return this.moveToFirstDayOfMonth().addDays(-1).moveToDayOfWeek(a,1).addWeeks(e)};var a=function(a,b,e){return function(g,c){var d=(g-this[a]()+e*(c||1))%e;return this[b](0===d?d+e*(c||1):d)}};f.moveToDayOfWeek=a("getDay","addDays",7);f.moveToMonth=a("getMonth","addMonths",12);
f.getOrdinate=function(){var a=this.getDate();return b(a)};f.getOrdinalNumber=function(){return Math.ceil((this.clone().clearTime()-new Date(this.getFullYear(),0,1))/864E5)+1};f.getTimezone=function(){return h.getTimezoneAbbreviation(this.getUTCOffset(),this.isDaylightSavingTime())};f.setTimezoneOffset=function(a){var b=this.getTimezoneOffset();return(a=-6*Number(a)/10)||0===a?this.addMinutes(a-b):this};f.setTimezone=function(a){return this.setTimezoneOffset(h.getTimezoneOffset(a))};f.hasDaylightSavingTime=
function(){return Date.today().set({month:0,day:1}).getTimezoneOffset()!==Date.today().set({month:6,day:1}).getTimezoneOffset()};f.isDaylightSavingTime=function(){return Date.today().set({month:0,day:1}).getTimezoneOffset()!==this.getTimezoneOffset()};f.getUTCOffset=function(a){a=-10*(a||this.getTimezoneOffset())/6;if(0>a)return a=(a-1E4).toString(),a.charAt(0)+a.substr(2);a=(a+1E4).toString();return"+"+a.substr(1)};f.getElapsed=function(a){return(a||new Date)-this};f.set=function(a){a=c.call(this,
a);for(var b in a)if(Object.prototype.hasOwnProperty.call(a,b)){var e=b.charAt(0).toUpperCase()+b.slice(1),g,d;"week"!==b&&"month"!==b&&"timezone"!==b&&"timezoneOffset"!==b&&(e+="s");g="add"+e;d="get"+e;"month"===b?g+="s":"year"===b&&(d="getFullYear");if("day"!==b&&"timezone"!==b&&"timezoneOffset"!==b&&"week"!==b&&"hour"!==b)this[g](a[b]-this[d]());else if("timezone"===b||"timezoneOffset"===b||"week"===b||"hour"===b)this["set"+e](a[b])}a.day&&this.addDays(a.day-this.getDate());return this};f.moveToFirstDayOfMonth=
function(){return this.set({day:1})};f.moveToLastDayOfMonth=function(){return this.set({day:h.getDaysInMonth(this.getFullYear(),this.getMonth())})};var b=function(a){switch(1*a){case 1:case 21:case 31:return"st";case 2:case 22:return"nd";case 3:case 23:return"rd";default:return"th"}},e=function(a){var b=Date.CultureInfo.formatPatterns;switch(a){case "d":return this.toString(b.shortDate);case "D":return this.toString(b.longDate);case "F":return this.toString(b.fullDateTime);case "m":return this.toString(b.monthDay);
case "r":case "R":return a=this.clone().addMinutes(this.getTimezoneOffset()),a.toString(b.rfc1123)+" GMT";case "s":return this.toString(b.sortableDateTime);case "t":return this.toString(b.shortTime);case "T":return this.toString(b.longTime);case "u":return a=this.clone().addMinutes(this.getTimezoneOffset()),a.toString(b.universalSortableDateTime);case "y":return this.toString(b.yearMonth);default:return!1}},g=function(a){return function(e){if("\\"===e.charAt(0))return e.replace("\\","");switch(e){case "hh":return d(13>
a.getHours()?0===a.getHours()?12:a.getHours():a.getHours()-12);case "h":return 13>a.getHours()?0===a.getHours()?12:a.getHours():a.getHours()-12;case "HH":return d(a.getHours());case "H":return a.getHours();case "mm":return d(a.getMinutes());case "m":return a.getMinutes();case "ss":return d(a.getSeconds());case "s":return a.getSeconds();case "yyyy":return d(a.getFullYear(),4);case "yy":return d(a.getFullYear());case "y":return a.getFullYear();case "E":case "dddd":return Date.CultureInfo.dayNames[a.getDay()];
case "ddd":return Date.CultureInfo.abbreviatedDayNames[a.getDay()];case "dd":return d(a.getDate());case "d":return a.getDate();case "MMMM":return Date.CultureInfo.monthNames[a.getMonth()];case "MMM":return Date.CultureInfo.abbreviatedMonthNames[a.getMonth()];case "MM":return d(a.getMonth()+1);case "M":return a.getMonth()+1;case "t":return 12>a.getHours()?Date.CultureInfo.amDesignator.substring(0,1):Date.CultureInfo.pmDesignator.substring(0,1);case "tt":return 12>a.getHours()?Date.CultureInfo.amDesignator:
Date.CultureInfo.pmDesignator;case "S":return b(a.getDate());case "W":return a.getWeek();case "WW":return a.getISOWeek();case "Q":return"Q"+a.getQuarter();case "q":return String(a.getQuarter());case "z":return a.getTimezone();case "Z":case "X":return Date.getTimezoneOffset(a.getTimezone());case "ZZ":return-60*a.getTimezoneOffset();case "u":return a.getDay();case "L":return h.isLeapYear(a.getFullYear())?1:0;case "B":return"@"+(a.getUTCSeconds()+60*a.getUTCMinutes()+3600*(a.getUTCHours()+1))/86.4;default:return e}}};
f.toString=function(a,b){if(!b&&a&&1===a.length&&(output=e.call(this,a)))return output;var c=g(this);return a?a.replace(/((\\)?(dd?d?d?|MM?M?M?|yy?y?y?|hh?|HH?|mm?|ss?|tt?|S|q|Q|WW?W?W?)(?![^\[]*\]))/g,c).replace(/\[|\]/g,""):this._toString()}})();
(function(){var h=Date,f=h.prototype,d=Number.prototype;f._orient=1;f._nth=null;f._is=!1;f._same=!1;f._isSecond=!1;d._dateElement="days";f.next=function(){this._move=!0;this._orient=1;return this};h.next=function(){return h.today().next()};f.last=f.prev=f.previous=function(){this._move=!0;this._orient=-1;return this};h.last=h.prev=h.previous=function(){return h.today().last()};f.is=function(){this._is=!0;return this};f.same=function(){this._same=!0;this._isSecond=!1;return this};f.today=function(){return this.same().day()};
f.weekday=function(){return this._nth?m("Weekday").call(this):this._move?this.addWeekdays(this._orient):this._is?(this._is=!1,!this.is().sat()&&!this.is().sun()):!1};f.weekend=function(){return this._is?(this._is=!1,this.is().sat()||this.is().sun()):!1};f.at=function(a){return"string"===typeof a?h.parse(this.toString("d")+" "+a):this.set(a)};d.fromNow=d.after=function(a){var b={};b[this._dateElement]=this;return(a?a.clone():new Date).add(b)};d.ago=d.before=function(a){var b={};b["s"!==this._dateElement[this._dateElement.length-
1]?this._dateElement+"s":this._dateElement]=-1*this;return(a?a.clone():new Date).add(b)};var c="sunday monday tuesday wednesday thursday friday saturday".split(/\s/),a="january february march april may june july august september october november december".split(/\s/),b="Millisecond Second Minute Hour Day Week Month Year Quarter Weekday".split(/\s/),e="Milliseconds Seconds Minutes Hours Date Week Month FullYear Quarter".split(/\s/),g="final first second third fourth fifth".split(/\s/);f.toObject=function(){for(var a=
{},g=0;g<b.length;g++)this["get"+e[g]]&&(a[b[g].toLowerCase()]=this["get"+e[g]]());return a};h.fromObject=function(a){a.week=null;return Date.today().set(a)};var m=function(a){return function(){if(this._is)return this._is=!1,this.getDay()===a;this._move&&(this._move=null);if(null!==this._nth){this._isSecond&&this.addSeconds(-1*this._orient);this._isSecond=!1;var b=this._nth;this._nth=null;var e=this.clone().moveToLastDayOfMonth();this.moveToNthOccurrence(a,b);if(this>e)throw new RangeError(h.getDayName(a)+
" does not occur "+b+" times in the month of "+h.getMonthName(e.getMonth())+" "+e.getFullYear()+".");return this}return this.moveToDayOfWeek(a,this._orient)}},k=function(a,b,e){for(var g=0;g<a.length;g++)h[a[g].toUpperCase()]=h[a[g].toUpperCase().substring(0,3)]=g,h[a[g]]=h[a[g].substring(0,3)]=b(g),f[a[g]]=f[a[g].substring(0,3)]=e(g)};k(c,function(a){return function(){var b=h.today(),e=a-b.getDay();0===a&&1===Date.CultureInfo.firstDayOfWeek&&0!==b.getDay()&&(e+=7);return b.addDays(e)}},m);k(a,function(a){return function(){return h.today().set({month:a,
day:1})}},function(a){return function(){return this._is?(this._is=!1,this.getMonth()===a):this.moveToMonth(a,this._orient)}});for(var a=function(a){return function(e){if(this._isSecond)return this._isSecond=!1,this;if(this._same){this._same=this._is=!1;var g=this.toObject();e=(e||new Date).toObject();for(var c="",d=a.toLowerCase(),d="s"===d[d.length-1]?d.substring(0,d.length-1):d,f=b.length-1;-1<f;f--){c=b[f].toLowerCase();if(g[c]!==e[c])return!1;if(d===c)break}return!0}"s"!==a.substring(a.length-
1)&&(a+="s");this._move&&(this._move=null);return this["add"+a](this._orient)}},k=function(a){return function(){this._dateElement=a;return this}},n=0;n<b.length;n++)c=b[n].toLowerCase(),"weekday"!==c&&(f[c]=f[c+"s"]=a(b[n]),d[c]=d[c+"s"]=k(c+"s"));f._ss=a("Second");d=function(a){return function(b){if(this._same)return this._ss(b);if(b||0===b)return this.moveToNthOccurrence(b,a);this._nth=a;return 2!==a||void 0!==b&&null!==b?this:(this._isSecond=!0,this.addSeconds(this._orient))}};for(c=0;c<g.length;c++)f[g[c]]=
0===c?d(-1):d(c)})();
(function(){Date.Parsing={Exception:function(a){this.message="Parse error at '"+a.substring(0,10)+" ...'"}};var h=Date.Parsing,f=[0,31,59,90,120,151,181,212,243,273,304,334],d=[0,31,60,91,121,152,182,213,244,274,305,335];h.isLeapYear=function(a){return 0===a%4&&0!==a%100||0===a%400};var c={multiReplace:function(a,b){for(var e in b)if(Object.prototype.hasOwnProperty.call(b,e)){var g;"function"!==typeof b[e]&&(g=b[e]instanceof RegExp?b[e]:new RegExp(b[e],"g"));a=a.replace(g,e)}return a},getDayOfYearFromWeek:function(a){var b;
a.weekDay=a.weekDay||0===a.weekDay?a.weekDay:1;b=new Date(a.year,0,4);b=(0===b.getDay()?7:b.getDay())+3;a.dayOfYear=7*a.week+(0===a.weekDay?7:a.weekDay)-b;return a},getDayOfYear:function(a,b){a.dayOfYear||(a=c.getDayOfYearFromWeek(a));for(var e=0;e<=b.length;e++)if(a.dayOfYear<b[e]||e===b.length){a.day=a.day?a.day:a.dayOfYear-b[e-1];break}else a.month=e;return a},adjustForTimeZone:function(a,b){var e;"Z"===a.zone.toUpperCase()||0===a.zone_hours&&0===a.zone_minutes?e=-b.getTimezoneOffset():(e=60*a.zone_hours+
(a.zone_minutes||0),"+"===a.zone_sign&&(e*=-1),e-=b.getTimezoneOffset());b.setMinutes(b.getMinutes()+e);return b},setDefaults:function(a){a.year=a.year||Date.today().getFullYear();a.hours=a.hours||0;a.minutes=a.minutes||0;a.seconds=a.seconds||0;a.milliseconds=a.milliseconds||0;if(a.month||!a.week&&!a.dayOfYear)a.month=a.month||0,a.day=a.day||1;return a},dataNum:function(a,b,e,g){var c=1*a;return b?g?a?1*b(a):a:a?b(c):a:e?a&&"undefined"!==typeof a?c:a:a?c:a},timeDataProcess:function(a){var b={},e;
for(e in a.data)a.data.hasOwnProperty(e)&&(b[e]=a.ignore[e]?a.data[e]:c.dataNum(a.data[e],a.mods[e],a.explict[e],a.postProcess[e]));a.data.secmins&&(a.data.secmins=60*a.data.secmins.replace(",","."),b.minutes?b.seconds||(b.seconds=a.data.secmins):b.minutes=a.data.secmins,delete a.secmins);return b},buildTimeObjectFromData:function(a){return c.timeDataProcess({data:{year:a[1],month:a[5],day:a[7],week:a[8],dayOfYear:a[10],hours:a[15],zone_hours:a[23],zone_minutes:a[24],zone:a[21],zone_sign:a[22],weekDay:a[9],
minutes:a[16],seconds:a[19],milliseconds:a[20],secmins:a[18]},mods:{month:function(a){return a-1},weekDay:function(a){a=Math.abs(a);return 7===a?0:a},minutes:function(a){return a.replace(":","")},seconds:function(a){return Math.floor(1*a.replace(":","").replace(",","."))},milliseconds:function(a){return 1E3*a.replace(",",".")}},postProcess:{minutes:!0,seconds:!0,milliseconds:!0},explict:{zone_hours:!0,zone_minutes:!0},ignore:{zone:!0,zone_sign:!0,secmins:!0}})},addToHash:function(a,b,e){for(var g=
b.length,c=0;c<g;c++)a[b[c]]=e[c];return a},combineRegex:function(a,b){return new RegExp("(("+a.source+")\\s("+b.source+"))")},getDateNthString:function(a,b,e){if(a)return Date.today().addDays(e).toString("d");if(b)return Date.today().last()[e]().toString("d")},buildRegexData:function(a){for(var b=[],e=a.length,g=0;g<e;g++)"[object Array]"===Object.prototype.toString.call(a[g])?b.push(this.combineRegex(a[g][0],a[g][1])):b.push(a[g]);return b}};h.processTimeObject=function(a){var b;c.setDefaults(a);
b=h.isLeapYear(a.year)?d:f;a.month||!a.week&&!a.dayOfYear?a.dayOfYear=b[a.month]+a.day:c.getDayOfYear(a,b);b=new Date(a.year,a.month,a.day,a.hours,a.minutes,a.seconds,a.milliseconds);a.zone&&c.adjustForTimeZone(a,b);return b};h.ISO={regex:/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-3])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-4])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?\s?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/,
parse:function(a){a=a.match(this.regex);if(!a||!a.length)return null;a=c.buildTimeObjectFromData(a);return a.year&&(a.year||a.month||a.day||a.week||a.dayOfYear)?h.processTimeObject(a):null}};h.Numeric={isNumeric:function(a){return!isNaN(parseFloat(a))&&isFinite(a)},regex:/\b([0-1]?[0-9])([0-3]?[0-9])([0-2]?[0-9]?[0-9][0-9])\b/i,parse:function(a){var b,e={},g=Date.CultureInfo.dateElementOrder.split("");if(!this.isNumeric(a)||"+"===a[0]&&"-"===a[0])return null;if(5>a.length&&0>a.indexOf(".")&&0>a.indexOf("/"))return e.year=
a,h.processTimeObject(e);a=a.match(this.regex);if(!a||!a.length)return null;for(b=0;b<g.length;b++)switch(g[b]){case "d":e.day=a[b+1];break;case "m":e.month=a[b+1]-1;break;case "y":e.year=a[b+1]}return h.processTimeObject(e)}};h.Normalizer={regexData:function(){var a=Date.CultureInfo.regexPatterns;return c.buildRegexData([a.tomorrow,a.yesterday,[a.past,a.mon],[a.past,a.tue],[a.past,a.wed],[a.past,a.thu],[a.past,a.fri],[a.past,a.sat],[a.past,a.sun]])},basicReplaceHash:function(){var a=Date.CultureInfo.regexPatterns;
return{January:a.jan.source,February:a.feb,March:a.mar,April:a.apr,May:a.may,June:a.jun,July:a.jul,August:a.aug,September:a.sep,October:a.oct,November:a.nov,December:a.dec,"":/\bat\b/gi," ":/\s{2,}/,am:a.inTheMorning,"9am":a.thisMorning,pm:a.inTheEvening,"7pm":a.thisEvening}},keys:function(){return[c.getDateNthString(!0,!1,1),c.getDateNthString(!0,!1,-1),c.getDateNthString(!1,!0,"monday"),c.getDateNthString(!1,!0,"tuesday"),c.getDateNthString(!1,!0,"wednesday"),c.getDateNthString(!1,!0,"thursday"),
c.getDateNthString(!1,!0,"friday"),c.getDateNthString(!1,!0,"saturday"),c.getDateNthString(!1,!0,"sunday")]},buildRegexFunctions:function(){var a=Date.CultureInfo.regexPatterns,b=Date.i18n.__,b=new RegExp("(\\b\\d\\d?("+b("AM")+"|"+b("PM")+")? )("+a.tomorrow.source.slice(1)+")","i");this.replaceFuncs=[[new RegExp(a.today.source+"(?!\\s*([+-]))\\b"),function(a){return 1<a.length?Date.today().toString("d"):a}],[b,function(a,b){return Date.today().addDays(1).toString("d")+" "+b}],[a.amThisMorning,function(a,
b){return b}],[a.pmThisEvening,function(a,b){return b}]]},buildReplaceData:function(){this.buildRegexFunctions();this.replaceHash=c.addToHash(this.basicReplaceHash(),this.keys(),this.regexData())},stringReplaceFuncs:function(a){for(var b=0;b<this.replaceFuncs.length;b++)a=a.replace(this.replaceFuncs[b][0],this.replaceFuncs[b][1]);return a},parse:function(a){a=this.stringReplaceFuncs(a);a=c.multiReplace(a,this.replaceHash);try{var b=a.split(/([\s\-\.\,\/\x27]+)/);3===b.length&&h.Numeric.isNumeric(b[0])&&
h.Numeric.isNumeric(b[2])&&4<=b[2].length&&"d"===Date.CultureInfo.dateElementOrder[0]&&(a="1/"+b[0]+"/"+b[2])}catch(e){}return a}};h.Normalizer.buildReplaceData()})();
(function(){for(var h=Date.Parsing,f=h.Operators={rtoken:function(a){return function(e){var g=e.match(a);if(g)return[g[0],e.substring(g[0].length)];throw new h.Exception(e);}},token:function(){return function(a){return f.rtoken(new RegExp("^\\s*"+a+"\\s*"))(a)}},stoken:function(a){return f.rtoken(new RegExp("^"+a))},until:function(a){return function(e){for(var g=[],c=null;e.length;){try{c=a.call(this,e)}catch(d){g.push(c[0]);e=c[1];continue}break}return[g,e]}},many:function(a){return function(e){for(var g=
[],c=null;e.length;){try{c=a.call(this,e)}catch(d){break}g.push(c[0]);e=c[1]}return[g,e]}},optional:function(a){return function(e){var g=null;try{g=a.call(this,e)}catch(c){return[null,e]}return[g[0],g[1]]}},not:function(a){return function(e){try{a.call(this,e)}catch(g){return[null,e]}throw new h.Exception(e);}},ignore:function(a){return a?function(e){var g=null,g=a.call(this,e);return[null,g[1]]}:null},product:function(){for(var a=arguments[0],e=Array.prototype.slice.call(arguments,1),g=[],c=0;c<
a.length;c++)g.push(f.each(a[c],e));return g},cache:function(a){var e={},g=0,c=[],d=Date.Config.CACHE_MAX||1E5,f=null;return function(l){if(g===d)for(var p=0;10>p;p++){var q=c.shift();q&&(delete e[q],g--)}try{f=e[l]=e[l]||a.call(this,l)}catch(s){f=e[l]=s}g++;c.push(l);if(f instanceof h.Exception)throw f;return f}},any:function(){var a=arguments;return function(e){for(var g=null,c=0;c<a.length;c++)if(null!=a[c]){try{g=a[c].call(this,e)}catch(d){g=null}if(g)return g}throw new h.Exception(e);}},each:function(){var a=
arguments;return function(e){for(var c=[],d=null,f=0;f<a.length;f++)if(null!=a[f]){try{d=a[f].call(this,e)}catch(n){throw new h.Exception(e);}c.push(d[0]);e=d[1]}return[c,e]}},all:function(){var a=a;return a.each(a.optional(arguments))},sequence:function(a,e,c){e=e||f.rtoken(/^\s*/);c=c||null;return 1===a.length?a[0]:function(d){for(var f=null,n=null,l=[],p=0;p<a.length;p++){try{f=a[p].call(this,d)}catch(q){break}l.push(f[0]);try{n=e.call(this,f[1])}catch(s){n=null;break}d=n[1]}if(!f)throw new h.Exception(d);
if(n)throw new h.Exception(n[1]);if(c)try{f=c.call(this,f[1])}catch(u){throw new h.Exception(f[1]);}return[l,f?f[1]:d]}},between:function(a,e,c){c=c||a;var d=f.each(f.ignore(a),e,f.ignore(c));return function(a){a=d.call(this,a);return[[a[0][0],r[0][2]],a[1]]}},list:function(a,e,c){e=e||f.rtoken(/^\s*/);c=c||null;return a instanceof Array?f.each(f.product(a.slice(0,-1),f.ignore(e)),a.slice(-1),f.ignore(c)):f.each(f.many(f.each(a,f.ignore(e))),px,f.ignore(c))},set:function(a,e,c){e=e||f.rtoken(/^\s*/);
c=c||null;return function(d){for(var k=null,n=k=null,l=null,p=[[],d],q=!1,s=0;s<a.length;s++){k=n=null;q=1===a.length;try{k=a[s].call(this,d)}catch(u){continue}l=[[k[0]],k[1]];if(0<k[1].length&&!q)try{n=e.call(this,k[1])}catch(v){q=!0}else q=!0;q||0!==n[1].length||(q=!0);if(!q){k=[];for(q=0;q<a.length;q++)s!==q&&k.push(a[q]);k=f.set(k,e).call(this,n[1]);0<k[0].length&&(l[0]=l[0].concat(k[0]),l[1]=k[1])}l[1].length<p[1].length&&(p=l);if(0===p[1].length)break}if(0===p[0].length)return p;if(c){try{n=
c.call(this,p[1])}catch(w){throw new h.Exception(p[1]);}p[1]=n[1]}return p}},forward:function(a,e){return function(c){return a[e].call(this,c)}},replace:function(a,e){return function(c){c=a.call(this,c);return[e,c[1]]}},process:function(a,e){return function(c){c=a.call(this,c);return[e.call(this,c[0]),c[1]]}},min:function(a,e){return function(c){var d=e.call(this,c);if(d[0].length<a)throw new h.Exception(c);return d}}},d=function(a){return function(){var e=null,c=[],d;1<arguments.length?e=Array.prototype.slice.call(arguments):
arguments[0]instanceof Array&&(e=arguments[0]);if(e){if(d=e.shift(),0<d.length)return e.unshift(d[void 0]),c.push(a.apply(null,e)),e.shift(),c}else return a.apply(null,arguments)}},c="optional not ignore cache".split(/\s/),a=0;a<c.length;a++)f[c[a]]=d(f[c[a]]);d=function(a){return function(){return arguments[0]instanceof Array?a.apply(null,arguments[0]):a.apply(null,arguments)}};c="each any all".split(/\s/);for(a=0;a<c.length;a++)f[c[a]]=d(f[c[a]])})();
(function(){var h=Date,f=function(c){for(var a=[],b=0;b<c.length;b++)c[b]instanceof Array?a=a.concat(f(c[b])):c[b]&&a.push(c[b]);return a},d=function(){if(this.meridian&&(this.hour||0===this.hour)){if("a"===this.meridian&&11<this.hour&&Date.Config.strict24hr)throw"Invalid hour and meridian combination";if("p"===this.meridian&&12>this.hour&&Date.Config.strict24hr)throw"Invalid hour and meridian combination";"p"===this.meridian&&12>this.hour?this.hour+=12:"a"===this.meridian&&12===this.hour&&(this.hour=
0)}};h.Translator={hour:function(c){return function(){this.hour=Number(c)}},minute:function(c){return function(){this.minute=Number(c)}},second:function(c){return function(){this.second=Number(c)}},secondAndMillisecond:function(c){return function(){var a=c.match(/^([0-5][0-9])\.([0-9]{1,3})/);this.second=Number(a[1]);this.millisecond=Number(a[2])}},meridian:function(c){return function(){this.meridian=c.slice(0,1).toLowerCase()}},timezone:function(c){return function(){var a=c.replace(/[^\d\+\-]/g,
"");a.length?this.timezoneOffset=Number(a):this.timezone=c.toLowerCase()}},day:function(c){var a=c[0];return function(){this.day=Number(a.match(/\d+/)[0]);if(1>this.day)throw"invalid day";}},month:function(c){return function(){this.month=3===c.length?"jan feb mar apr may jun jul aug sep oct nov dec".indexOf(c)/4:Number(c)-1;if(0>this.month)throw"invalid month";}},year:function(c){return function(){var a=Number(c);this.year=2<c.length?a:a+(a+2E3<Date.CultureInfo.twoDigitYearMax?2E3:1900)}},rday:function(c){return function(){switch(c){case "yesterday":this.days=
-1;break;case "tomorrow":this.days=1;break;case "today":this.days=0;break;case "now":this.days=0,this.now=!0}}},finishExact:function(c){c=c instanceof Array?c:[c];for(var a=0;a<c.length;a++)c[a]&&c[a].call(this);c=new Date;!this.hour&&!this.minute||this.month||this.year||this.day||(this.day=c.getDate());this.year||(this.year=c.getFullYear());this.month||0===this.month||(this.month=c.getMonth());this.day||(this.day=1);this.hour||(this.hour=0);this.minute||(this.minute=0);this.second||(this.second=
0);this.millisecond||(this.millisecond=0);d.call(this);if(this.day>h.getDaysInMonth(this.year,this.month))throw new RangeError(this.day+" is not a valid value for days.");c=new Date(this.year,this.month,this.day,this.hour,this.minute,this.second,this.millisecond);100>this.year&&c.setFullYear(this.year);this.timezone?c.set({timezone:this.timezone}):this.timezoneOffset&&c.set({timezoneOffset:this.timezoneOffset});return c},finish:function(c){var a,b,e;c=c instanceof Array?f(c):[c];if(0===c.length)return null;
for(a=0;a<c.length;a++)"function"===typeof c[a]&&c[a].call(this);if(!this.now||this.unit||this.operator)c=this.now||-1!=="hour minute second".indexOf(this.unit)?new Date:h.today();else return new Date;a=!!(this.days&&null!==this.days||this.orient||this.operator);b="past"===this.orient||"subtract"===this.operator?-1:1;this.month&&"week"===this.unit&&(this.value=this.month+1,delete this.month,delete this.day);!this.month&&0!==this.month||-1==="year day hour minute second".indexOf(this.unit)||(this.value||
(this.value=this.month+1),this.month=null,a=!0);a||!this.weekday||this.day||this.days||(e=Date[this.weekday](),this.day=e.getDate(),this.month||(this.month=e.getMonth()),this.year=e.getFullYear());if(a&&this.weekday&&"month"!==this.unit&&"week"!==this.unit){var g=c;e=b||1;this.unit="day";this.days=(g=h.getDayNumberFromName(this.weekday)-g.getDay())?(g+7*e)%7:7*e}!this.weekday||"week"===this.unit||this.day||this.days||(e=Date[this.weekday](),this.day=e.getDate(),e.getMonth()!==c.getMonth()&&(this.month=
e.getMonth()));this.month&&"day"===this.unit&&this.operator&&(this.value||(this.value=this.month+1),this.month=null);null!=this.value&&null!=this.month&&null!=this.year&&(this.day=1*this.value);this.month&&!this.day&&this.value&&(c.set({day:1*this.value}),a||(this.day=1*this.value));this.month||!this.value||"month"!==this.unit||this.now||(this.month=this.value,a=!0);a&&(this.month||0===this.month)&&"year"!==this.unit&&(e=b||1,this.unit="month",this.months=(g=this.month-c.getMonth())?(g+12*e)%12:12*
e,this.month=null);this.unit||(this.unit="day");if(!this.value&&this.operator&&null!==this.operator&&this[this.unit+"s"]&&null!==this[this.unit+"s"])this[this.unit+"s"]=this[this.unit+"s"]+("add"===this.operator?1:-1)+(this.value||0)*b;else if(null==this[this.unit+"s"]||null!=this.operator)this.value||(this.value=1),this[this.unit+"s"]=this.value*b;d.call(this);!this.month&&0!==this.month||this.day||(this.day=1);if(!this.orient&&!this.operator&&"week"===this.unit&&this.value&&!this.day&&!this.month)return Date.today().setWeek(this.value);
if("week"===this.unit&&this.weeks&&!this.day&&!this.month)return c=Date[void 0!==this.weekday?this.weekday:"today"]().addWeeks(this.weeks),this.now&&c.setTimeToNow(),c;a&&this.timezone&&this.day&&this.days&&(this.day=this.days);a?c.add(this):c.set(this);this.timezone&&(this.timezone=this.timezone.toUpperCase(),a=h.getTimezoneOffset(this.timezone),c.hasDaylightSavingTime()&&(b=h.getTimezoneAbbreviation(a,c.isDaylightSavingTime()),b!==this.timezone&&(c.isDaylightSavingTime()?c.addHours(-1):c.addHours(1))),
c.setTimezoneOffset(a));return c}}})();
(function(){var h=Date;h.Grammar={};var f=h.Parsing.Operators,d=h.Grammar,c=h.Translator,a;a=function(){return f.each(f.any.apply(null,arguments),f.not(d.ctoken2("timeContext")))};d.datePartDelimiter=f.rtoken(/^([\s\-\.\,\/\x27]+)/);d.timePartDelimiter=f.stoken(":");d.whiteSpace=f.rtoken(/^\s*/);d.generalDelimiter=f.rtoken(/^(([\s\,]|at|@|on)+)/);var b={};d.ctoken=function(a){var c=b[a];if(!c){for(var c=Date.CultureInfo.regexPatterns,e=a.split(/\s+/),d=[],g=0;g<e.length;g++)d.push(f.replace(f.rtoken(c[e[g]]),
e[g]));c=b[a]=f.any.apply(null,d)}return c};d.ctoken2=function(a){return f.rtoken(Date.CultureInfo.regexPatterns[a])};var e=function(a,b,c,e){d[a]=e?f.cache(f.process(f.each(f.rtoken(b),f.optional(d.ctoken2(e))),c)):f.cache(f.process(f.rtoken(b),c))},g=function(a,b){return f.cache(f.process(d.ctoken2(a),b))},m={},k=function(a){m[a]=m[a]||d.format(a)[0];return m[a]};d.allformats=function(a){var b=[];if(a instanceof Array)for(var c=0;c<a.length;c++)b.push(k(a[c]));else b.push(k(a));return b};d.formats=
function(a){if(a instanceof Array){for(var b=[],c=0;c<a.length;c++)b.push(k(a[c]));return f.any.apply(null,b)}return k(a)};var n={timeFormats:function(){var a,b="h hh H HH m mm s ss ss.s z zz".split(" "),h=[/^(0[0-9]|1[0-2]|[1-9])/,/^(0[0-9]|1[0-2])/,/^([0-1][0-9]|2[0-3]|[0-9])/,/^([0-1][0-9]|2[0-3])/,/^([0-5][0-9]|[0-9])/,/^[0-5][0-9]/,/^([0-5][0-9]|[0-9])/,/^[0-5][0-9]/,/^[0-5][0-9]\.[0-9]{1,3}/,/^((\+|\-)\s*\d\d\d\d)|((\+|\-)\d\d\:?\d\d)/,/^((\+|\-)\s*\d\d\d\d)|((\+|\-)\d\d\:?\d\d)/],m=[c.hour,
c.hour,c.hour,c.hour,c.minute,c.minute,c.second,c.second,c.secondAndMillisecond,c.timezone,c.timezone];for(a=0;a<b.length;a++)e(b[a],h[a],m[a]);d.hms=f.cache(f.sequence([d.H,d.m,d.s],d.timePartDelimiter));d.t=g("shortMeridian",c.meridian);d.tt=g("longMeridian",c.meridian);d.zzz=g("timezone",c.timezone);d.timeSuffix=f.each(f.ignore(d.whiteSpace),f.set([d.tt,d.zzz]));d.time=f.each(f.optional(f.ignore(f.stoken("T"))),d.hms,d.timeSuffix)},dateFormats:function(){var b=function(){return f.set(arguments,
d.datePartDelimiter)},g,h="d dd M MM y yy yyy yyyy".split(" "),m=[/^([0-2]\d|3[0-1]|\d)/,/^([0-2]\d|3[0-1])/,/^(1[0-2]|0\d|\d)/,/^(1[0-2]|0\d)/,/^(\d+)/,/^(\d\d)/,/^(\d\d?\d?\d?)/,/^(\d\d\d\d)/],n=[c.day,c.day,c.month,c.month,c.year,c.year,c.year,c.year],k=["ordinalSuffix","ordinalSuffix"];for(g=0;g<h.length;g++)e(h[g],m[g],n[g],k[g]);d.MMM=d.MMMM=f.cache(f.process(d.ctoken("jan feb mar apr may jun jul aug sep oct nov dec"),c.month));d.ddd=d.dddd=f.cache(f.process(d.ctoken("sun mon tue wed thu fri sat"),
function(a){return function(){this.weekday=a}}));d.day=a(d.d,d.dd);d.month=a(d.M,d.MMM);d.year=a(d.yyyy,d.yy);d.mdy=b(d.ddd,d.month,d.day,d.year);d.ymd=b(d.ddd,d.year,d.month,d.day);d.dmy=b(d.ddd,d.day,d.month,d.year);d.date=function(a){return(d[Date.CultureInfo.dateElementOrder]||d.mdy).call(this,a)}},relative:function(){d.orientation=f.process(d.ctoken("past future"),function(a){return function(){this.orient=a}});d.operator=f.process(d.ctoken("add subtract"),function(a){return function(){this.operator=
a}});d.rday=f.process(d.ctoken("yesterday tomorrow today now"),c.rday);d.unit=f.process(d.ctoken("second minute hour day week month year"),function(a){return function(){this.unit=a}})}};d.buildGrammarFormats=function(){b={};n.timeFormats();n.dateFormats();n.relative();d.value=f.process(f.rtoken(/^([-+]?\d+)?(st|nd|rd|th)?/),function(a){return function(){this.value=a.replace(/\D/g,"")}});d.expression=f.set([d.rday,d.operator,d.value,d.unit,d.orientation,d.ddd,d.MMM]);d.format=f.process(f.many(f.any(f.process(f.rtoken(/^(dd?d?d?(?!e)|MM?M?M?|yy?y?y?|hh?|HH?|mm?|ss?|tt?|zz?z?)/),
function(a){if(d[a])return d[a];throw h.Parsing.Exception(a);}),f.process(f.rtoken(/^[^dMyhHmstz]+/),function(a){return f.ignore(f.stoken(a))}))),function(a){return f.process(f.each.apply(null,a),c.finishExact)});d._start=f.process(f.set([d.date,d.time,d.expression],d.generalDelimiter,d.whiteSpace),c.finish)};d.buildGrammarFormats();d._formats=d.formats('"yyyy-MM-ddTHH:mm:ssZ";yyyy-MM-ddTHH:mm:ss.sz;yyyy-MM-ddTHH:mm:ssZ;yyyy-MM-ddTHH:mm:ssz;yyyy-MM-ddTHH:mm:ss;yyyy-MM-ddTHH:mmZ;yyyy-MM-ddTHH:mmz;yyyy-MM-ddTHH:mm;ddd, MMM dd, yyyy H:mm:ss tt;ddd MMM d yyyy HH:mm:ss zzz;MMddyyyy;ddMMyyyy;Mddyyyy;ddMyyyy;Mdyyyy;dMyyyy;yyyy;Mdyy;dMyy;d'.split(";"));
d.start=function(a){try{var b=d._formats.call({},a);if(0===b[1].length)return b}catch(c){}return d._start.call({},a)}})();
(function(){var h=Date,f={removeOrds:function(d){return d=(ords=d.match(/\b(\d+)(?:st|nd|rd|th)\b/))&&2===ords.length?d.replace(ords[0],ords[1]):d},grammarParser:function(d){var c=null;try{c=h.Grammar.start.call({},d.replace(/^\s*(\S*(\s+\S+)*)\s*$/,"$1"))}catch(a){return null}return 0===c[1].length?c[0]:null},nativeFallback:function(d){var c;try{return(c=Date._parse(d))||0===c?new Date(c):null}catch(a){return null}}};h._parse||(h._parse=h.parse);h.parse=function(d){var c;if(!d)return null;if(d instanceof
Date)return d.clone();4<=d.length&&"0"!==d.charAt(0)&&"+"!==d.charAt(0)&&"-"!==d.charAt(0)&&(c=h.Parsing.ISO.parse(d)||h.Parsing.Numeric.parse(d));if(c instanceof Date&&!isNaN(c.getTime()))return c;d=h.Parsing.Normalizer.parse(f.removeOrds(d));c=f.grammarParser(d);return null!==c?c:f.nativeFallback(d)};Date.getParseFunction=function(d){var c=Date.Grammar.allformats(d);return function(a){for(var b=null,e=0;e<c.length;e++){try{b=c[e].call({},a)}catch(d){continue}if(0===b[1].length)return b[0]}return null}};
h.parseExact=function(d,c){return h.getParseFunction(c)(d)}})();
(function(){var h=Date,f=h.prototype,d=function(a,b){b||(b=2);return("000"+a).slice(-1*b)},c={d:"dd","%d":"dd",D:"ddd","%a":"ddd",j:"dddd",l:"dddd","%A":"dddd",S:"S",F:"MMMM","%B":"MMMM",m:"MM","%m":"MM",M:"MMM","%b":"MMM","%h":"MMM",n:"M",Y:"yyyy","%Y":"yyyy",y:"yy","%y":"yy",g:"h","%I":"h",G:"H",h:"hh",H:"HH","%H":"HH",i:"mm","%M":"mm",s:"ss","%S":"ss","%r":"hh:mm tt","%R":"H:mm","%T":"H:mm:ss","%X":"t","%x":"d","%e":"d","%D":"MM/dd/yy","%n":"\\n","%t":"\\t",e:"z",T:"z","%z":"z","%Z":"z",Z:"ZZ",
N:"u",w:"u","%w":"u",W:"W","%V":"W"},a={substitutes:function(a){return c[a]},interpreted:function(a,b){var c;switch(a){case "%u":return b.getDay()+1;case "z":return b.getOrdinalNumber();case "%j":return d(b.getOrdinalNumber(),3);case "%U":c=b.clone().set({month:0,day:1}).addDays(-1).moveToDayOfWeek(0);var f=b.clone().addDays(1).moveToDayOfWeek(0,-1);return f<c?"00":d((f.getOrdinalNumber()-c.getOrdinalNumber())/7+1);case "%W":return d(b.getWeek());case "t":return h.getDaysInMonth(b.getFullYear(),b.getMonth());
case "o":case "%G":return b.setWeek(b.getISOWeek()).toString("yyyy");case "%g":return b._format("%G").slice(-2);case "a":case "%p":return t("tt").toLowerCase();case "A":return t("tt").toUpperCase();case "u":return d(b.getMilliseconds(),3);case "I":return b.isDaylightSavingTime()?1:0;case "O":return b.getUTCOffset();case "P":return c=b.getUTCOffset(),c.substring(0,c.length-2)+":"+c.substring(c.length-2);case "B":return c=new Date,Math.floor((3600*c.getHours()+60*c.getMinutes()+c.getSeconds()+60*(c.getTimezoneOffset()+
60))/86.4);case "c":return b.toISOString().replace(/\"/g,"");case "U":return h.strtotime("now");case "%c":return t("d")+" "+t("t");case "%C":return Math.floor(b.getFullYear()/100+1)}},shouldOverrideDefaults:function(a){switch(a){case "%e":return!0;default:return!1}},parse:function(b,c){var d,f=c||new Date;return(d=a.substitutes(b))?d:(d=a.interpreted(b,f))?d:b}};h.normalizeFormat=function(b,c){return b.replace(/(%|\\)?.|%%/g,function(b){return a.parse(b,c)})};h.strftime=function(a,b){return Date.parse(b)._format(a)};
h.strtotime=function(a){a=h.parse(a);return Math.round(h.UTC(a.getUTCFullYear(),a.getUTCMonth(),a.getUTCDate(),a.getUTCHours(),a.getUTCMinutes(),a.getUTCSeconds(),a.getUTCMilliseconds())/1E3)};var b=function(b){return function(c){var d=!1;if("\\"===c.charAt(0)||"%%"===c.substring(0,2))return c.replace("\\","").replace("%%","%");d=a.shouldOverrideDefaults(c);if(c=h.normalizeFormat(c,b))return b.toString(c,d)}};f._format=function(a){var c=b(this);return a?a.replace(/(%|\\)?.|%%/g,c):this._toString()};
f.format||(f.format=f._format)})();
(function(){var h=function(c){return function(){return this[c]}},f=function(c){return function(a){this[c]=a;return this}},d=function(c,a,b,e,f){if(1===arguments.length&&"number"===typeof c){var h=0>c?-1:1,k=Math.abs(c);this.setDays(Math.floor(k/864E5)*h);k%=864E5;this.setHours(Math.floor(k/36E5)*h);k%=36E5;this.setMinutes(Math.floor(k/6E4)*h);k%=6E4;this.setSeconds(Math.floor(k/1E3)*h);this.setMilliseconds(k%1E3*h)}else this.set(c,a,b,e,f);this.getTotalMilliseconds=function(){return 864E5*this.getDays()+
36E5*this.getHours()+6E4*this.getMinutes()+1E3*this.getSeconds()};this.compareTo=function(a){var b=new Date(1970,1,1,this.getHours(),this.getMinutes(),this.getSeconds());a=null===a?new Date(1970,1,1,0,0,0):new Date(1970,1,1,a.getHours(),a.getMinutes(),a.getSeconds());return b<a?-1:b>a?1:0};this.equals=function(a){return 0===this.compareTo(a)};this.add=function(a){return null===a?this:this.addSeconds(a.getTotalMilliseconds()/1E3)};this.subtract=function(a){return null===a?this:this.addSeconds(-a.getTotalMilliseconds()/
1E3)};this.addDays=function(a){return new d(this.getTotalMilliseconds()+864E5*a)};this.addHours=function(a){return new d(this.getTotalMilliseconds()+36E5*a)};this.addMinutes=function(a){return new d(this.getTotalMilliseconds()+6E4*a)};this.addSeconds=function(a){return new d(this.getTotalMilliseconds()+1E3*a)};this.addMilliseconds=function(a){return new d(this.getTotalMilliseconds()+a)};this.get12HourHour=function(){return 12<this.getHours()?this.getHours()-12:0===this.getHours()?12:this.getHours()};
this.getDesignator=function(){return 12>this.getHours()?Date.CultureInfo.amDesignator:Date.CultureInfo.pmDesignator};this.toString=function(a){this._toString=function(){return null!==this.getDays()&&0<this.getDays()?this.getDays()+"."+this.getHours()+":"+this.p(this.getMinutes())+":"+this.p(this.getSeconds()):this.getHours()+":"+this.p(this.getMinutes())+":"+this.p(this.getSeconds())};this.p=function(a){return 2>a.toString().length?"0"+a:a};var b=this;return a?a.replace(/dd?|HH?|hh?|mm?|ss?|tt?/g,
function(a){switch(a){case "d":return b.getDays();case "dd":return b.p(b.getDays());case "H":return b.getHours();case "HH":return b.p(b.getHours());case "h":return b.get12HourHour();case "hh":return b.p(b.get12HourHour());case "m":return b.getMinutes();case "mm":return b.p(b.getMinutes());case "s":return b.getSeconds();case "ss":return b.p(b.getSeconds());case "t":return(12>b.getHours()?Date.CultureInfo.amDesignator:Date.CultureInfo.pmDesignator).substring(0,1);case "tt":return 12>b.getHours()?Date.CultureInfo.amDesignator:
Date.CultureInfo.pmDesignator}}):this._toString()};return this};(function(c,a){for(var b=0;b<a.length;b++){var e=a[b],d=e.slice(0,1).toUpperCase()+e.slice(1);c.prototype[e]=0;c.prototype["get"+d]=h(e);c.prototype["set"+d]=f(e)}})(d,"years months days hours minutes seconds milliseconds".split(" ").slice(2));d.prototype.set=function(c,a,b,e,d){this.setDays(c||this.getDays());this.setHours(a||this.getHours());this.setMinutes(b||this.getMinutes());this.setSeconds(e||this.getSeconds());this.setMilliseconds(d||
this.getMilliseconds())};Date.prototype.getTimeOfDay=function(){return new d(0,this.getHours(),this.getMinutes(),this.getSeconds(),this.getMilliseconds())};Date.TimeSpan=d;"undefined"!==typeof window&&(window.TimeSpan=d)})();
(function(){var h=function(a){return function(){return this[a]}},f=function(a){return function(b){this[a]=b;return this}},d=function(a,b,c,d){function f(){b.addMonths(-a);d.months++;12===d.months&&(d.years++,d.months=0)}if(1===a)for(;b>c;)f();else for(;b<c;)f();d.months--;d.months*=a;d.years*=a},c=function(a,b,c,f,h,k,n){if(7===arguments.length)this.set(a,b,c,f,h,k,n);else if(2===arguments.length&&arguments[0]instanceof Date&&arguments[1]instanceof Date){var l=arguments[0].clone(),p=arguments[1].clone(),
q=l>p?1:-1;this.dates={start:arguments[0].clone(),end:arguments[1].clone()};d(q,l,p,this);var s=!1===(l.isDaylightSavingTime()===p.isDaylightSavingTime());s&&1===q?l.addHours(-1):s&&l.addHours(1);l=p-l;0!==l&&(l=new TimeSpan(l),this.set(this.years,this.months,l.getDays(),l.getHours(),l.getMinutes(),l.getSeconds(),l.getMilliseconds()))}return this};(function(a,b){for(var c=0;c<b.length;c++){var d=b[c],m=d.slice(0,1).toUpperCase()+d.slice(1);a.prototype[d]=0;a.prototype["get"+m]=h(d);a.prototype["set"+
m]=f(d)}})(c,"years months days hours minutes seconds milliseconds".split(" "));c.prototype.set=function(a,b,c,d,f,h,n){this.setYears(a||this.getYears());this.setMonths(b||this.getMonths());this.setDays(c||this.getDays());this.setHours(d||this.getHours());this.setMinutes(f||this.getMinutes());this.setSeconds(h||this.getSeconds());this.setMilliseconds(n||this.getMilliseconds())};Date.TimePeriod=c;"undefined"!==typeof window&&(window.TimePeriod=c)})();






// zebra function 1.3.12

/*
 *  For more resources visit {@link http://stefangabos.ro/}
 *
 *  @author     Stefan Gabos <contact@stefangabos.ro>
 *  @version    1.3.12 (last revision: January 26, 2016)
 *  @copyright  (c) 2011 - 2016 Stefan Gabos
 *  @license    http://www.gnu.org/licenses/lgpl-3.0.txt GNU LESSER GENERAL PUBLIC LICENSE
 *  @package    Zebra_Dialog
 */
;(function($) {

    'use strict';

    $.Zebra_Dialog = function() {

        // default options
        var defaults = {

            animation_speed_hide:       0,            //  The speed, in milliseconds, by which the overlay and the
                                                        //  dialog box will be animated when closing.
                                                        //
                                                        //  Default is 250

            animation_speed_show:       0,              //  The speed, in milliseconds, by which the dialog box will
                                                        //  fade in when appearing.
                                                        //
                                                        //  Default is 0

            auto_close:                 false,          //  The number of milliseconds after which to automatically
                                                        //  close the dialog box or FALSE to not automatically close the
                                                        //  dialog box.
                                                        //
                                                        //  Default is FALSE

            buttons:                    true,           //  Use this for localization and for adding custom buttons.
                                                        //
                                                        //  If set to TRUE, the default buttons will be used, depending
                                                        //  on the type of the dialog box: ['Yes', 'No'] for "question"
                                                        //  type and ['Ok'] for the other dialog box types.
                                                        //
                                                        //  For custom buttons, use an array containing the captions of
                                                        //  the buttons to display: ['My button 1', 'My button 2'].
                                                        //
                                                        //  Set to FALSE if you want no buttons.
                                                        //
                                                        //  Note that when the dialog box is closed as a result of clicking
                                                        //  a button, the "onClose" event is triggered and the callback
                                                        //  function (if any) receives as argument the caption of the
                                                        //  clicked button.
                                                        //
                                                        //  See the comments for the "onClose" event below for more
                                                        //  information.
                                                        //
                                                        //  You can also attach callback functions to individual buttons
                                                        //  by using objects in the form of:
                                                        //
                                                        //  [
                                                        //   {caption: 'My button 1', callback: function() { // code }},
                                                        //   {caption: 'My button 2', callback: function() { // code }}
                                                        //  ]
                                                        //
                                                        //  The main difference is that a callback function attached this
                                                        //  way is executed as soon as the button is clicked rather than
                                                        //  *after* the dialog box is closed, as it is the case with the
                                                        //  "onClose" event.
                                                        //
                                                        //  Callback functions attached to buttons get as argument the
                                                        //  entire dialog box jQuery object.
                                                        //
                                                        //  A callback function returning FALSE will prevent the dialog
                                                        //  box from closing.

            center_buttons:              false,         //  When set to TRUE, the buttons will be centered instead of
                                                        //  right-aligned.
                                                        //
                                                        //  Default is FALSE

            custom_class:                false,         //  An extra class to add to the dialog box's container. Useful
                                                        //  for customizing a dialog box elements' styles at runtime.
                                                        //
                                                        //  For example, setting this value to "mycustom" and in the
                                                        //  CSS file having something like
                                                        //  .mycustom .ZebraDialog_Title { background: red }
                                                        //  would set the dialog box title's background to red.
                                                        //
                                                        //  See the CSS file for what can be changed.
                                                        //
                                                        //  Default is FALSE

            keyboard:                   true,           //  When set to TRUE, pressing the ESC key will close the dialog
                                                        //  box.
                                                        //
                                                        //  Default is TRUE.

            max_height:                 0,              //  The maximum height, in pixels, before the content in the
                                                        //  dialog box is scrolled.
                                                        //
                                                        //  If set to "0" the dialog box's height will automatically
                                                        //  adjust to fit the entire content.
                                                        //
                                                        //  Default is "0"

            message:                    '',             //  The text (or HTML) to be displayed in the dialog box.
                                                        //
                                                        //  See the "source" property on how to add content via AJAX,
                                                        //  iFrames or from inline elements.

            modal:                      true,           //  When set to TRUE there will be a semitransparent overlay
                                                        //  behind the dialog box, preventing users from clicking
                                                        //  the page's content.
                                                        //
                                                        //  Default is TRUE

            overlay_close:              true,           //  Should the dialog box close when the overlay is clicked?
                                                        //
                                                        //  Default is TRUE

            overlay_opacity:            '.9',           //  The opacity of the overlay (between 0 and 1)
                                                        //
                                                        //  Default is .9

            position:                   ['center','top'],       //  Position of the dialog box.
                                                        //
                                                        //  Can be either "center" (which would center the dialog box) or
                                                        //  an array with 2 elements, in the form of
                                                        //  {'horizontal_position +/- offset', 'vertical_position +/- offset'}
                                                        //  (notice how everything is enclosed in quotes) where
                                                        //  "horizontal_position" can be "left", "right" or "center",
                                                        //  "vertical_position" can be "top", "bottom" or "middle", and
                                                        //  "offset" represents an optional number of pixels to add/substract
                                                        //  from the respective horizontal or vertical position.
                                                        //
                                                        //  Positions are relative to the viewport (the area of the
                                                        //  browser that is visible to the user)!
                                                        //
                                                        //  Examples:
                                                        //
                                                        //  ['left + 20', 'top + 20'] would position the dialog box in the
                                                        //  top-left corner, shifted 20 pixels inside.
                                                        //
                                                        //  ['right - 20', 'bottom - 20'] would position the dialog box
                                                        //  in the bottom-right corner, shifted 20 pixels inside.
                                                        //
                                                        //  ['center', 'top + 20'] would position the dialog box in
                                                        //  center-top, shifted 20 pixels down.
                                                        //
                                                        //  Default is "center".

            reposition_speed:           0,            //  The duration (in milliseconds) of the animation used to
                                                        //  reposition the dialog box when the browser window is resized.
                                                        //
                                                        //  Default is 100.

            show_close_button:          true,           //  When set to TRUE, a "close" button (the little "x") will be
                                                        //  shown in the upper right corner of the dialog box.
                                                        //
                                                        //  If the dialog box has a title bar then the "close" button
                                                        //  will be shown in the title bar, vertically centered and
                                                        //  respecting the right padding.
                                                        //
                                                        //  If the dialog box does not have a title bar then the "close"
                                                        //  button will be shown in the upper right corner of the body
                                                        //  of the dialog box, respecting the position related properties
                                                        //  set in the stylesheet.
                                                        //
                                                        //  Default is TRUE.

            source:                     false,          //  Add content via AJAX, iFrames or from inline elements (together
                                                        //  with the already applied events).
                                                        //
                                                        //  This property can be any of the following:
                                                        // 
                                                        //  - 'ajax': object - where "object" can be an object with any
                                                        //  of the properties you'd normally use to make an AJAX call in
                                                        //  jQuery (see the description for the "settings" argument at
                                                        //  http://api.jquery.com/jQuery.ajax/), or it can be a string
                                                        //  representing a valid URL whose content to be fetched via
                                                        //  AJAX and placed inside the dialog box.
                                                        //
                                                        //  Example:
                                                        //
                                                        //  source: {'ajax': 'http://myurl.com/'}
                                                        //
                                                        //  source: {'ajax': {
                                                        //      'url':      'http://myurl.com/',
                                                        //      'cache':    false
                                                        //  }}
                                                        //
                                                        //  Note that you cannot use the "success" property as it will
                                                        //  always be overwritten by the library; use the "complete"
                                                        //  property instead, if you have to!
                                                        //
                                                        //  - 'iframe': object - where "object" can be an object where
                                                        //  property names can be valid attributes of the <iframe> tag
                                                        //  (see https://developer.mozilla.org/en-US/docs/HTML/Element/iframe),
                                                        //  or it can be a string representing a valid URL to be loaded
                                                        //  inside an iFrame and placed inside the dialog box.
                                                        //
                                                        //  Example:
                                                        //
                                                        //  source: {'iframe': 'http://myurl.com/'}
                                                        //
                                                        //  source: {'iframe': {
                                                        //      'src':          'http://myurl.com/',
                                                        //      'width':        480,
                                                        //      'height':       320,
                                                        //      'scrolling':    'no'
                                                        //  }}
                                                        //
                                                        //  Note that you should always set the iFrame's width and height
                                                        //  and adjust the dialog box's "width" property accordingly!
                                                        //
                                                        //  - 'inline': selector - where "element" is a jQuery element
                                                        //  from the page; the element will be copied and placed inside
                                                        //  the dialog box together with any attached events! if you just
                                                        //  want the element's inner HTML, use $('#element').html().
                                                        //
                                                        //  Example:
                                                        //
                                                        //  source: {'inline': $('#myelement')}
                                                        //
                                                        //  Default is FALSE

            title:                      '',             //  Title of the dialog box
                                                        //
                                                        //  Default is "" (an empty string - no title)

            type:                       'information',  //  Dialog box type.
                                                        //
                                                        //  Can be any of the following:
                                                        //
                                                        //  - confirmation
                                                        //  - error
                                                        //  - information
                                                        //  - question
                                                        //  - warning
                                                        //
                                                        //  If you don't want an icon, set the "type" property to FALSE.
                                                        //
                                                        //  By default, all types except "question" have a single button
                                                        //  with the caption "Ok"; type "question" has two buttons, with
                                                        //  the captions "Ok" and "Cancel" respectively.
                                                        //
                                                        //  Default is "information".

            vcenter_short_message:      true,           //  Should short messages be vertically centered?
                                                        //
                                                        //  Default is TRUE

            width:                      0,              //  By default, the width of the dialog box is set in the CSS
                                                        //  file. Use this property to override the default width at
                                                        //  runtime.
                                                        //
                                                        //  Must be an integer, greater than 0. Anything else will instruct
                                                        //  the script to use the default value, as set in the CSS file.
                                                        //  Value should be no less than 200 for optimal output.
                                                        //
                                                        //  Default is 0 - use the value from the CSS file.

            onClose:                null                //  Event fired when *after* the dialog box is closed.
                                                        //
                                                        //  For executing functions *before* the closing of the dialog
                                                        //  box, see the "buttons" attribute.
                                                        //
                                                        //  The callback function (if any) receives as argument the
                                                        //  caption of the clicked button or boolean FALSE if the dialog
                                                        //  box is closed by pressing the ESC key or by clicking on the
                                                        //  overlay.

        };

        var

            // to avoid confusions, we use "plugin" to reference the current instance of the object
            plugin = this,

            // by default, we assume there are no custom options provided
            options = {},

            // we'll use this when resizing
            timeout;

        // this will hold the merged default, and user-provided options
        plugin.settings = {};

        // if plugin is initialized so that first argument is a string
        // that string is the message to be shown in the dialog box
        if (typeof arguments[0] == 'string') options.message = arguments[0];

        // if plugin is initialized so that first or second argument is an object
        if (typeof arguments[0] == 'object' || typeof arguments[1] == 'object')

            // extend the options object with the user-provided options
            options = $.extend(options, (typeof arguments[0] == 'object' ? arguments[0] : arguments[1]));

        /**
         *  Constructor method
         *
         *  @return object  Returns a reference to the plugin
         */
        plugin.init = function() {

            var $title;

            // the plugin's final properties are the merged default and user-provided options (if any)
            plugin.settings = $.extend({}, defaults, options);

            // check if browser is Internet Explorer 6 and set a flag accordingly as we need to perform some extra tasks
            // later on for Internet Explorer 6
            plugin.isIE6 = (browser.name == 'explorer' && browser.version == 6) || false;

            // if dialog box should be modal
            if (plugin.settings.modal) {

                // create the overlay
                plugin.overlay = $('<div>', {

                    'class':    'ZebraDialogOverlay'

                // set some css properties of the overlay
                }).css({

                    'position': (plugin.isIE6 ? 'absolute' : 'fixed'),  //  for IE6 we emulate the "position:fixed" behaviour
                    'left':     0,                                      //  the overlay starts at the top-left corner of the
                    'top':      0,                                      //  browser window (later on we'll stretch it)
                    'opacity':  plugin.settings.overlay_opacity         //  set the overlay's opacity

                });

                // if dialog box can be closed by clicking the overlay
                if (plugin.settings.overlay_close)

                    // when the overlay is clicked
                    // remove the overlay and the dialog box from the DOM
                    plugin.overlay.bind('click', function() {plugin.close();});

                // append the overlay to the DOM
                plugin.overlay.appendTo('body');

            }

            // create the dialog box
            plugin.dialog = $('<div>', {

                'class':        'ZebraDialog' + (plugin.settings.custom_class ? ' ' + plugin.settings.custom_class : '')

            // set some css properties of the dialog box
            }).css({

                'position':     (plugin.isIE6 ? 'absolute' : 'fixed'),  //  for IE6 we emulate the "position:fixed" behaviour
                'left':         0,                                      //  by default, place it in the top-left corner of the
                'top':          0,                                      //  browser window (we'll position it later)
                'visibility':   'hidden'                                //  the dialog box is hidden for now

            });

            // if a notification message
            if (!plugin.settings.buttons && plugin.settings.auto_close)

                // assign a unique id to each notification
                plugin.dialog.attr('id', 'ZebraDialog_' + Math.floor(Math.random() * 9999999));

            // check to see if the "width" property is given as an integer
            // try to convert to a integer
            var tmp = parseInt(plugin.settings.width, 10);

            // if converted value is a valid number
            if (!isNaN(tmp) && tmp == plugin.settings.width && tmp.toString() == plugin.settings.width.toString() && tmp > 0)

                // set the dialog box's width
                plugin.dialog.css({'width' : plugin.settings.width});

            // if dialog box has a title
            if (plugin.settings.title)

                // create the title
                $title = $('<h3>', {

                    'class':    'ZebraDialog_Title'

                // set the title's text
                // and append the title to the dialog box
                }).html(plugin.settings.title).appendTo(plugin.dialog);

            // get the buttons that are to be added to the dialog box
            var buttons = get_buttons();

            // we create an outer container to apply borders to
            var body_container = $('<div>', {

                'class':    'ZebraDialog_BodyOuter' + (!plugin.settings.title ? ' ZebraDialog_NoTitle' : '') + (!buttons ? ' ZebraDialog_NoButtons' : '')

            }).appendTo(plugin.dialog);

            // create the container of the actual message
            // we save it as a reference because we'll use it later in the "draw" method
            // if the "vcenter_short_message" property is TRUE
            plugin.message = $('<div>', {

                // if a known dialog box type is specified, also show the appropriate icon
                'class':    'ZebraDialog_Body' + (get_type() !== false ? ' ZebraDialog_Icon ZebraDialog_' + get_type() : '')

            });

            // if we have a max-height set
            if (plugin.settings.max_height > 0) {

                // set it like this for browsers supporting the "max-height" attribute
                plugin.message.css('max-height', plugin.settings.max_height);

                // for IE6, go this way...
                if (plugin.isIE6) plugin.message.attr('style', 'height: expression(this.scrollHeight > ' + plugin.settings.max_height + ' ? "' + plugin.settings.max_height + 'px" : "85px")');

            }

            // if short messages are to be centered vertically
            if (plugin.settings.vcenter_short_message)

                // create a secondary container for the message and add everything to the message container
                // (we'll later align the container vertically)
                $('<div>').html(plugin.settings.message).appendTo(plugin.message);

            // if short messages are not to be centered vertically
            else

                // add the message to the message container
                plugin.message.html(plugin.settings.message);

            // if dialog box content is to be fetched from an external source
            if (plugin.settings.source && typeof plugin.settings.source == 'object') {

                // the object where the content will be injected into
                var canvas = (plugin.settings.vcenter_short_message ? $('div:first', plugin.message) : plugin.message);

                // let's see what type of content we need
                for (var type in plugin.settings.source) {

                    switch (type) {

                        // if we have to fetch content using an AJAX call
                        case 'ajax':

                            var

                                // prepare the options for the AJAX call
                                ajax_options = typeof plugin.settings.source[type] == 'string' ? {'url': plugin.settings.source[type]} : plugin.settings.source[type],

                                // create the animated preloader and show it
                                preloader = $('<div>').attr('class', 'ZebraDialog_Preloader').appendTo(canvas);

                            // handle the "success" event
                            ajax_options.success = function(result) {

                                // remove the preloader
                                preloader.remove();

                                // append new content
                                canvas.append(result);

                                // reposition the dialog box
                                draw(false);
                            };

                            // make the AJAX call
                            $.ajax(ajax_options);

                            break;

                        // if we have to show an iFrame
                        case 'iframe':

                            // these are the default options
                            var default_options = {
                                    'width':        '100%',
                                    'height':       '100%',
                                    'marginheight': '0',
                                    'marginwidth':  '0',
                                    'frameborder':  '0'
                                },

                                // extend the default options with the ones provided by the user, if any
                                iframe_options = $.extend(default_options, typeof plugin.settings.source[type] == 'string' ? {'src': plugin.settings.source[type]} : plugin.settings.source[type]);

                            // create the iFrame and place it inside the dialog box
                            canvas.append($('<iframe>').attr(iframe_options));

                            break;

                        // if content is to be taken from an inline element
                        case 'inline':

                            // copy content and place it inside the dialog box
                            canvas.append(plugin.settings.source[type]);

                            break;

                    }

                }

            }

            // add the message container to the dialog box
            plugin.message.appendTo(body_container);

            // if there are any buttons to be added to the dialog box
            if (buttons) {

                // reverse the order of the buttons because they are floated to the right
                buttons.reverse();

                // create the button bar
                var button_bar = $('<div>', {

                    'class':    'ZebraDialog_Buttons'

                // append it to the dialog box
                }).appendTo(plugin.dialog);

                // iterate through the buttons that are to be attached to the dialog box
                $.each(buttons, function(index, value) {

                    // create button
                    var button = $('<a>', {

                        'href':     'javascript:void(0)',
                        'class':    'ZebraDialog_Button_' + index

                    });

                    // if button is given as an object, with a caption and a callback function
                    // set the button's caption
                    if ($.isPlainObject(value)) button.html(value.caption);

                    // if button is given as a plain string, set the button's caption accordingly
                    else button.html(value);

                    // handle the button's click event
                    button.bind('click', function() {

                        // by default, clicking a button closes the dialog box
                        var close = true;

                        // execute the callback function when button is clicked
                        if (undefined !== value.callback) close = value.callback(plugin.dialog);

                        // if dialog box is to be closed
                        if (close !== false)

                            // remove the overlay and the dialog box from the DOM
                            // also pass the button's label as argument
                            plugin.close(undefined !== value.caption ? value.caption : value);

                    });

                    // append the button to the button bar
                    button.appendTo(button_bar);

                });

                // wrap everything in another wrapper
                // and center buttons if needed
                button_bar.wrap($('<div>').addClass('ZebraDialog_ButtonsOuter' + (plugin.settings.center_buttons ? ' ZebraDialog_Buttons_Centered' : '')));

            }

            // insert the dialog box in the DOM
            plugin.dialog.appendTo('body');

            // if we need to show the little "x" for closing the dialog box, in the top-right corner
            if (plugin.settings.show_close_button) {

                // create the button now and append it to the dialog box's title, or to the dialog box's body if there's no title
                var $close = $('<a href="javascript:void(0)" class="ZebraDialog_Close">&times;</a>').bind('click', function(e){

                    e.preventDefault();
                    plugin.close();


                }).appendTo($title ? $title : plugin.message);

                // if the close button was added to the title bar
                if ($title)

                    // center it vertically
                    $close.css({
                        'right':    parseInt($title.css('paddingRight'), 10),
                        'top':      (parseInt($title.css('height'), 10) + parseInt($title.css('paddingTop'), 10) + parseInt($title.css('paddingBottom'), 10) - $close.height()) / 2
                    });

            }

            // if the browser window is resized
            $(window).bind('resize.Zebra_Dialog', function() {

                // clear a previously set timeout
                // this will ensure that the next piece of code will not be executed on every step of the resize event
                clearTimeout(timeout);

                // set a small timeout before doing anything
                timeout = setTimeout(function() {

                    // reposition the dialog box
                    draw();

                }, 100);

            });

            // if dialog box can be closed by pressing the ESC key
            if (plugin.settings.keyboard)

                // if a key is pressed
                $(document).bind('keyup.Zebra_Dialog', function(e) {

                    // if pressed key is ESC
                    // remove the overlay and the dialog box from the DOM
                    if (e.which == 27) plugin.close();

                    // let the event bubble up
                    return true;

                });

            // if browser is Internet Explorer 6 we attach an event to be fired whenever the window is scrolled
            // that is because in IE6 we have to simulate "position:fixed"
            if (plugin.isIE6)

                // if window is scrolled
                $(window).bind('scroll.Zebra_Dialog', function() {

                    // make sure the overlay and the dialog box always stay in the correct position
                    emulate_fixed_position();

                });

            // if plugin is to be closed automatically after a given number of milliseconds
            if (plugin.settings.auto_close !== false) {

                // if, in the meantime, the box is clicked
                plugin.dialog.bind('click', function() {

                    // stop the timeout
                    clearTimeout(plugin.timeout);

                    // close the box now
                    plugin.close();

                });

                // call the "close" method after the given number of milliseconds
                plugin.timeout = setTimeout(plugin.close, plugin.settings.auto_close);

            }

            // draw the overlay and the dialog box
            // (no animation)
            draw(false);

            // return a reference to the object itself
            return plugin;

        };

        /**
         *  Close the dialog box
         *
         *  @return void
         */
        plugin.close = function(caption) {

            // remove all event handlers set by the plugin
            $(document).unbind('.Zebra_Dialog');
            $(window).unbind('.Zebra_Dialog');

            // if an overlay exists
            if (plugin.overlay)

                // animate overlay's css properties
                plugin.overlay.animate({

                    opacity: 0  // fade out the overlay

                },

                // animation speed
                plugin.settings.animation_speed_hide,

                // when the animation is complete
                function() {

                    // remove the overlay from the DOM
                    plugin.overlay.remove();

                });

            // animate dialog box's css properties
            plugin.dialog.animate({

                top: 0,     // move the dialog box to the top
                opacity: 0  // fade out the dialog box

            },

            // animation speed
            plugin.settings.animation_speed_hide,

            // when the animation is complete
            function() {

                // remove the dialog box from the DOM
                plugin.dialog.remove();

                // if a callback function exists for when closing the dialog box
                if (plugin.settings.onClose && typeof plugin.settings.onClose == 'function')

                    // execute the callback function
                    plugin.settings.onClose(undefined !== caption ? caption : '');

            });

        };

        /**
         *  Draw the overlay and the dialog box
         *
         *  @return void
         *
         *  @access private
         */
        var draw = function() {

            var

                // get the viewport width and height
                viewport_width = $(window).width(),
                viewport_height = $(window).height(),

                // get dialog box's width and height
                dialog_width = plugin.dialog.width(),
                dialog_height = plugin.dialog.height(),

                // the numeric representations for some constants that may exist in the "position" property
                values = {

                    'left':     0,
                    'top':      0,
                    'right':    viewport_width - dialog_width,
                    'bottom':   viewport_height - dialog_height,
                    'center':   (viewport_width - dialog_width) / 2,
                    'middle':   (viewport_height - dialog_height) / 2

                };

            // reset these values
            plugin.dialog_left = undefined;
            plugin.dialog_top = undefined;

            // if
            if (

                // the position is given as an array
                $.isArray(plugin.settings.position) &&

                // the array has exactly two elements
                plugin.settings.position.length == 2 &&

                // first element is a string
                typeof plugin.settings.position[0] == 'string' &&

                // first element contains only "left", "right", "center", numbers, spaces, plus and minus signs
                plugin.settings.position[0].match(/^(left|right|center)[\s0-9\+\-]*$/) &&

                // second element is a string
                typeof plugin.settings.position[1] == 'string' &&

                // second element contains only "top", "bottom", "middle", numbers, spaces, plus and minus signs
                plugin.settings.position[1].match(/^(top|bottom|middle)[\s0-9\+\-]*$/)

            ) {

                // make sure both entries are lowercase
                plugin.settings.position[0] = plugin.settings.position[0].toLowerCase();
                plugin.settings.position[1] = plugin.settings.position[1].toLowerCase();

                // iterate through the array of replacements
                $.each(values, function(index, value) {

                    // we need to check both the horizontal and vertical values
                    for (var i = 0; i < 2; i++) {

                        // replace if there is anything to be replaced
                        var tmp = plugin.settings.position[i].replace(index, value);

                        // if anything could be replaced
                        if (tmp != plugin.settings.position[i])

                            // evaluate string as a mathematical expression and set the appropriate value
                            if (i === 0) plugin.dialog_left = eval(tmp); else plugin.dialog_top = eval(tmp);

                    }

                });

            }

            // if "dialog_left" and/or "dialog_top" values are still not set
            if (undefined === plugin.dialog_left || undefined === plugin.dialog_top) {

                // the dialog box will be in its default position, centered
                plugin.dialog_left = values['center'];
                plugin.dialog_top = values['middle'];

            }

            // if short messages are to be centered vertically
            if (plugin.settings.vcenter_short_message) {

                var

                    // the secondary container - the one that contains the message
                    message = plugin.message.find('div:first'),

                    // the height of the secondary container
                    message_height = message.height(),

                    // the main container's height
                    container_height = plugin.message.height();

                // if we need to center the message vertically
                if (message_height < container_height)

                    // center the message vertically
                    message.css({

                        'padding-top':   (container_height - message_height) / 2

                    });

            }

            // if dialog box is to be placed without animation
            if ((typeof arguments[0] == 'boolean' && arguments[0] === false) || plugin.settings.reposition_speed === 0) {

                // position the dialog box and make it visible
                plugin.dialog.css({

                    'left':         plugin.dialog_left,
                    'top':          plugin.dialog_top,
                    'visibility':   'visible',
                    'opacity':      0

                }).animate({'opacity': 1}, plugin.settings.animation_speed_show);

            // if dialog box is to be animated into position
            } else {

                // stop any ongoing animation
                // (or animations will queue up when manually resizing the window)
                plugin.dialog.stop(true);

                plugin.dialog.css('visibility', 'visible').animate({
                    'left': plugin.dialog_left,
                    'top':  plugin.dialog_top
                }, plugin.settings.reposition_speed);

            }

            // move the focus to the first of the dialog box's buttons
            plugin.dialog.find('a[class^=ZebraDialog_Button]:first').focus();

            // if the browser is Internet Explorer 6, call the "emulate_fixed_position" method
            // (if we do not apply a short delay, it sometimes does not work as expected)
            if (plugin.isIE6) setTimeout(emulate_fixed_position, 500);

        };

        /**
         *  Emulates "position:fixed" for Internet Explorer 6.
         *
         *  @return void
         *
         *  @access private
         */
        var emulate_fixed_position = function() {

            var

                // get how much the window is scrolled both horizontally and vertically
                scroll_top = $(window).scrollTop(),
                scroll_left = $(window).scrollLeft();

            // if an overlay exists
            if (plugin.settings.modal)

                // make sure the overlay is stays positioned at the top of the viewport
                plugin.overlay.css({

                    'top':  scroll_top,
                    'left': scroll_left

                });

            // make sure the dialog box always stays in the correct position
            plugin.dialog.css({

                'left': plugin.dialog_left + scroll_left,
                'top':  plugin.dialog_top + scroll_top

            });

        };

        /**
         *  Returns an array containing the buttons that are to be added to the dialog box.
         *
         *  If no custom buttons are specified, the returned array depends on the type of the dialog box.
         *
         *  @return array       Returns an array containing the buttons that are to be added to the dialog box.
         *
         *  @access private
         */
        var get_buttons = function() {

            // if plugin.settings.buttons is not TRUE and is not an array either, don't go further
            if (plugin.settings.buttons !== true && !$.isArray(plugin.settings.buttons)) return false;

            // if default buttons are to be used
            if (plugin.settings.buttons === true)

                // there are different buttons for different dialog box types
                switch (plugin.settings.type) {

                    // for "question" type
                    case 'question':

                        // there are two buttons
                        plugin.settings.buttons = ['Yes', 'No'];

                        break;

                    // for the other types
                    default:

                        // there is only one button
                        plugin.settings.buttons = ['Ok'];

                }

            // return the buttons
            return plugin.settings.buttons;

        };

        /**
         *  Returns the type of the dialog box, or FALSE if type is not one of the five known types.
         *
         *  Values that may be returned by this method are:
         *  -   Confirmation
         *  -   Error
         *  -   Information
         *  -   Question
         *  -   Warning
         *
         *  @return boolean     Returns the type of the dialog box, or FALSE if type is not one of the five known types.
         *
         *  @access private
         */
        var get_type = function() {

            // see what is the type of the dialog box
            switch (plugin.settings.type) {

                // if one of the five supported types
                case 'confirmation':
                case 'error':
                case 'information':
                case 'question':
                case 'warning':

                    // return the dialog box's type, first letter capital
                    return plugin.settings.type.charAt(0).toUpperCase() + plugin.settings.type.slice(1).toLowerCase();

                // if unknown type
                default:

                    // return FALSE
                    return false;

            }

        };

        // since with jQuery 1.9.0 the $.browser object was removed, we rely on this piece of code from
        // http://www.quirksmode.org/js/detect.html to detect the browser
        var browser = {
            init: function () {
                this.name = this.searchString(this.dataBrowser) || '';
                this.version = this.searchVersion(navigator.userAgent) || this.searchVersion(navigator.appVersion) || '';
            },
            searchString: function (data) {
                for (var i=0;i<data.length;i++)    {
                    var dataString = data[i].string;
                    var dataProp = data[i].prop;
                    this.versionSearchString = data[i].versionSearch || data[i].identity;
                    if (dataString) {
                        if (dataString.indexOf(data[i].subString) != -1)
                            return data[i].identity;
                    }
                    else if (dataProp)
                        return data[i].identity;
                }
            },
            searchVersion: function (dataString) {
                var index = dataString.indexOf(this.versionSearchString);
                if (index == -1) return;
                return parseFloat(dataString.substring(index+this.versionSearchString.length+1));
            },
            dataBrowser: [
                {
                    string: navigator.userAgent,
                    subString: 'MSIE',
                    identity: 'explorer',
                    versionSearch: 'MSIE'
                }
            ]
        };

        browser.init();

        // fire up the plugin!
        // call the "constructor" method
        return plugin.init();

    };

})(jQuery);








(function($){   /* http://ryanday.org/konami   * Dual licensed under the MIT and GPL licenses.  */

 	var callbacks = [];
	$.extend($,{
		konami:function fn(cb,ctx){
			if(!fn.setup){
				fn.setup = true;
				var buffer = "";
				var code = "38384040373937396665";
				var konami = function(ev){
					var key = ev.keyCode || ev.which;

					if(key){
						buffer += (key+'');
						//console.log(buffer);
						if(buffer == code){
							buffer = "";
							$.each(callbacks,function(k,o){
								o.cb.call(o.ctx,ev);
							});
						} else if(code.indexOf(buffer) !== 0){
							if(buffer.indexOf(key) == 0){
								buffer = key;
							} else {
								buffer = "";
							}
						}
					}
				}

				var lastDown;
				$(document).bind('keyup',function(ev){
					if(ev.keyCode == lastDown){
						lastDown = false;
						konami(ev);
					}
				});

				$(document).bind('keydown',function(ev){
					lastDown = ev.keyCode;
				});
			}
			if(cb) callbacks.push({ctx:ctx||document.body,cb:cb});
		}
	});
	$.fn.extend({
		//refactor into event system.. removed event to stop leak?
		konami:function(cb){
			//console.log(this);
			this.each(function(){
				$.konami(cb,this);
			});
			return this;
		},
		unkonami:function(){
			this.each(function(){
				var el = this;
				//eww bad squared
				$.each(callbacks,function(k,o){
					if(o.ctx === el) callbacks.splice(k,1);
				});
			});
			return this;
		}
	});
})(jQuery);
$(function(){  $.konami(function(){
 /*       var orig  = $("#example").clone()[0]; 
        $("#example").html("<h1 >KONAMMMIIIII!!!!!!!!</h1>").css({background:'blue',color:'orange'}); 
	*/
	$(".top_menu_line").addClass('barrel_roll');
	$('body').addClass('barrel_roll');
        setTimeout(function(){ 		
/*            $("#example").replaceWith(orig); 
*/			
			$(".top_menu_line").removeClass('barrel_roll');
			$('body').removeClass('barrel_roll');
        },7000); 
    }); 
});
	
	
	
	
	
}); // ends doc ready
	
	
	
	/*!
	Autosize v1.17.8 - 2013-09-07
	Automatically adjust textarea height based on user input.
	(c) 2013 Jack Moore - http://www.jacklmoore.com/autosize
	license: http://www.opensource.org/licenses/mit-license.php
*/
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD. Register as an anonymous module.
		define(['jquery'], factory);
	} else {
		// Browser globals: jQuery or jQuery-like library, such as Zepto
		factory(window.jQuery || window.$);
	}
}
(function ($) {
	var
	defaults = {
		className: 'autosizejs',
		append: '',
		callback: false,
		resizeDelay: 0
	},

	// border:0 is unnecessary, but avoids a bug in FireFox on OSX
	copy = '<textarea tabindex="-1" style="position:absolute; top:-999px; left:0; right:auto; bottom:auto; border:0; padding: 0; -moz-box-sizing:content-box; -webkit-box-sizing:content-box; box-sizing:content-box; word-wrap:break-word; height:0 !important; min-height:0 !important; overflow:hidden; transition:none; -webkit-transition:none; -moz-transition:none;"/>',

	// line-height is conditionally included because IE7/IE8/old Opera do not return the correct value.
	typographyStyles = [
		'fontFamily',
		'fontSize',
		'fontWeight',
		'fontStyle',
		'letterSpacing',
		'textTransform',
		'wordSpacing',
		'textIndent'
	],

	// to keep track which textarea is being mirrored when adjust() is called.
	mirrored,

	// the mirror element, which is used to calculate what size the mirrored element should be.
	mirror = $(copy).data('autosize', true)[0];

	// test that line-height can be accurately copied.
	mirror.style.lineHeight = '99px';
	if ($(mirror).css('lineHeight') === '99px') {
		typographyStyles.push('lineHeight');
	}
	mirror.style.lineHeight = '';

	$.fn.autosize = function (options) {
		if (!this.length) {
			return this;
		}

		options = $.extend({}, defaults, options || {});

		if (mirror.parentNode !== document.body) {
			$(document.body).append(mirror);
		}
		return this.each(function () {
			var
			ta = this,
			$ta = $(ta),
			maxHeight,
			minHeight,
			boxOffset = 0,
			callback = $.isFunction(options.callback),
			originalStyles = {
				height: ta.style.height,
				overflow: ta.style.overflow,
				overflowY: ta.style.overflowY,
				wordWrap: ta.style.wordWrap,
				resize: ta.style.resize
			},
			timeout,
			width = $ta.width();

			if ($ta.data('autosize')) {
				// exit if autosize has already been applied, or if the textarea is the mirror element.
				return;
			}
			$ta.data('autosize', true);

			if ($ta.css('box-sizing') === 'border-box' || $ta.css('-moz-box-sizing') === 'border-box' || $ta.css('-webkit-box-sizing') === 'border-box'){
				boxOffset = $ta.outerHeight() - $ta.height();
			}

			// IE8 and lower return 'auto', which parses to NaN, if no min-height is set.
			minHeight = Math.max(parseInt($ta.css('minHeight'), 10) - boxOffset || 0, $ta.height());

			$ta.css({
				overflow: 'hidden',
				overflowY: 'hidden',
				wordWrap: 'break-word', // horizontal overflow is hidden, so break-word is necessary for handling words longer than the textarea width
				resize: ($ta.css('resize') === 'none' || $ta.css('resize') === 'vertical') ? 'none' : 'horizontal'
			});

			// The mirror width must exactly match the textarea width, so using getBoundingClientRect because it doesn't round the sub-pixel value.
			function setWidth() {
				var style, width;
				
				if ('getComputedStyle' in window) {
					style = window.getComputedStyle(ta);
					width = ta.getBoundingClientRect().width;

					$.each(['paddingLeft', 'paddingRight', 'borderLeftWidth', 'borderRightWidth'], function(i,val){
						width -= parseInt(style[val],10);
					});

					mirror.style.width = width + 'px';
				}
				else {
					// window.getComputedStyle, getBoundingClientRect returning a width are unsupported and unneeded in IE8 and lower.
					mirror.style.width = Math.max($ta.width(), 0) + 'px';
				}
			}

			function initMirror() {
				var styles = {};

				mirrored = ta;
				mirror.className = options.className;
				maxHeight = parseInt($ta.css('maxHeight'), 10);

				// mirror is a duplicate textarea located off-screen that
				// is automatically updated to contain the same text as the
				// original textarea.  mirror always has a height of 0.
				// This gives a cross-browser supported way getting the actual
				// height of the text, through the scrollTop property.
				$.each(typographyStyles, function(i,val){
					styles[val] = $ta.css(val);
				});
				$(mirror).css(styles);

				setWidth();

				// Chrome-specific fix:
				// When the textarea y-overflow is hidden, Chrome doesn't reflow the text to account for the space
				// made available by removing the scrollbar. This workaround triggers the reflow for Chrome.
				if (window.chrome) {
					var width = ta.style.width;
					ta.style.width = '0px';
					var ignore = ta.offsetWidth;
					ta.style.width = width;
				}
			}

			// Using mainly bare JS in this function because it is going
			// to fire very often while typing, and needs to very efficient.
			function adjust() {
				var height, original;

				if (mirrored !== ta) {
					initMirror();
				} else {
					setWidth();
				}

				mirror.value = ta.value + options.append;
				mirror.style.overflowY = ta.style.overflowY;
				original = parseInt(ta.style.height,10);

				// Setting scrollTop to zero is needed in IE8 and lower for the next step to be accurately applied
				mirror.scrollTop = 0;

				mirror.scrollTop = 9e4;

				// Using scrollTop rather than scrollHeight because scrollHeight is non-standard and includes padding.
				height = mirror.scrollTop;

				if (maxHeight && height > maxHeight) {
					ta.style.overflowY = 'scroll';
					height = maxHeight;
				} else {
					ta.style.overflowY = 'hidden';
					if (height < minHeight) {
						height = minHeight;
					}
				}

				height += boxOffset;

				if (original !== height) {
					ta.style.height = height + 'px';
					if (callback) {
						options.callback.call(ta,ta);
					}
				}
			}

			function resize () {
				clearTimeout(timeout);
				timeout = setTimeout(function(){
					var newWidth = $ta.width();

					if (newWidth !== width) {
						width = newWidth;
						adjust();
					}
				}, parseInt(options.resizeDelay,1));
			}

			if ('onpropertychange' in ta) {
				if ('oninput' in ta) {
					// Detects IE9.  IE9 does not fire onpropertychange or oninput for deletions,
					// so binding to onkeyup to catch most of those occasions.  There is no way that I
					// know of to detect something like 'cut' in IE9.
					$ta.on('input.autosize keyup.autosize', adjust);
				} else {
					// IE7 / IE8
					$ta.on('propertychange.autosize', function(){
						if(event.propertyName === 'value'){
							adjust();
						}
					});
				}
			} else {
				// Modern Browsers
				$ta.on('input.autosize', adjust);
			}

			// Set options.resizeDelay to false if using fixed-width textarea elements.
			// Uses a timeout and width check to reduce the amount of times adjust needs to be called after window resize.

			if (options.resizeDelay !== false) {
				$(window).on('resize.autosize', resize);
			}

			// Event for manual triggering if needed.
			// Should only be needed when the value of the textarea is changed through JavaScript rather than user input.
			$ta.on('autosize.resize', adjust);

			// Event for manual triggering that also forces the styles to update as well.
			// Should only be needed if one of typography styles of the textarea change, and the textarea is already the target of the adjust method.
			$ta.on('autosize.resizeIncludeStyle', function() {
				mirrored = null;
				adjust();
			});

			$ta.on('autosize.destroy', function(){
				mirrored = null;
				clearTimeout(timeout); 
				$(window).off('resize', resize);
				$ta
					.off('autosize')
					.off('.autosize')
					.css(originalStyles)
					.removeData('autosize');
			});

			// Call adjust in case the textarea already contains text.
			adjust();
		});
	};
}));


	



$(document).on("click","#showallfav",function(event){
    
    var toAppend = "";
    event.preventDefault();
    $("#showallfav").show(); 
    $("#showallfav").addClass("loader");
    
    // save current address details
    
    var temptoval = $("#tobox").val();
    var tempto = $("#modtobox").val();
    
    var tempfromval = $("#frombox").val();
    var tempfrom = $("#modfrombox").val();
    
    

    $.ajax({
        type: "POST",
        url: "ajax_lookup.php",
        data: {
            lookuppage: "allfavjson",
            clientid: "all"
        },
        cache: true,
        dataType: "json",
        success: function(data){
            $("#frombox").children().remove().end();
            $("#tobox").children().remove().end();

            $.each(data,function() {
                toAppend=toAppend + " <option value='" + this.oV + "'>" + this.oD + "</option> ";
            });
        },
        complete: function(){
            // alert(toAppend);            
            $("#frombox").html(toAppend);
            $("#tobox").html(toAppend);  
        
            $("#modtobox").val(tempto);
            $("#tobox").val(temptoval);
            $("#modfrombox").val(tempfrom);
            $("#frombox").val(tempfromval);	
        
        
            $("#toselectbutton").click();     
            $("#showallfav").hide();
    
        }
    }); 

});


$(document).on("click","a#showinactiveclient",function(event){
    event.preventDefault();
    
    $("a#showinactiveclient").addClass("loader");
    
    var toAppendcl = "";

    $.ajax({
        type: "POST",
        url: "ajax_lookup.php",
        data: {
            lookuppage: "allclientjson"
        },
        dataType: "json",
        success: function(data){
            $.each(data,function() {
                toAppendcl+="<option value=" + this.oV + ">" + this.oD + "</option>";
            });
        },
        complete: function(data){
            // $("#newjobselectclient").children().remove().end();
            $("#newjobselectclient").html(toAppendcl);
            $("a#showinactiveclient").addClass("fadeout");
            setTimeout( function() { $("#newjobbutton").click() }, 100 );
        }
    }); 


});



$(function() { // initialise new job client selector
    $( "#newjobselectclient" ).newjobcombobox();
    $( "#toggle" ).click(function() {
		$( "#newjobselectclient" ).toggle();
	});
});


(function( $ ) { $.widget( "ui.selectfavbox", {
			_create: function() {
				var self = this,
					select = this.element.hide(),
					selected = select.children( ":selected" ),
					value = selected.val() ? selected.text() : "";
				var input = this.input = $( "<input>" )
					.insertAfter( select )
					.val( value )
					.autocomplete({
						delay: 0,
						autoFocus: true,
						minLength: 0,
						source: function( request, response ) {
							var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
							response( select.children( "option" ).map(function() {
								var text = $( this ).text();
								if ( this.value && ( !request.term || matcher.test(text) ) )
									return {
										label: text.replace(
											new RegExp(
												"(?![^&;]+;)(?!<[^<>]*)(" +
												$.ui.autocomplete.escapeRegex(request.term) +
												")(?![^<>]*>)(?![^&;]+;)", "gi"
											), "<strong>$1</strong>" ),
										value: text,
										option: this
									};
							}) );
						},
						select: function( event, ui ) {
							ui.item.option.selected = true;
							self._trigger( "selected", event, {
								item: ui.item.option
							});
						},
						change: function( event, ui ) {
							if ( !ui.item ) {
								var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
									valid = false;
								select.children( "option" ).each(function() {
									if ( $( this ).text().match( matcher ) ) {
										this.selected = valid = true;
										return false;
									}
								});
								if ( !valid ) {
									// remove invalid value, as it didn't match anything
									$( this ).val( "" );
									select.val( "" );
									input.data( "autocomplete" ).term = "";
									return false;
								}
							}
						}
					})
					.addClass( "ui-widget ui-widget-content ui-corner-left" );

				input.data( "autocomplete" )._renderItem = function( ul, item ) {
					return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" + item.label + "</a>" )
						.appendTo( ul );
				};

				this.button = $( "<button id='favbutton' type='button'>&nbsp;</button>" )
					.attr( "tabIndex", -1 )
					.attr( "title", "Show All" )
					.insertAfter( input )
					.button({
						icons: {
							primary: "ui-icon-triangle-1-s"
						},
						text: false
					})
					.removeClass( "ui-corner-all" )
					.addClass( "ui-corner-right ui-button-icon" )
					.click(function() {
						// close if already visible
						if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
							input.autocomplete( "close" );
							return;
						}

						// work around a bug (likely same cause as #5265)
						$( this ).blur();

						// pass empty string as value to search for, displaying all results
						input.autocomplete( "search", "" );
						input.focus().setCursorPosition(99);
					});
			},

			destroy: function() {
				this.input.remove();
				this.button.remove();
				this.element.show();
				$.Widget.prototype.destroy.call( this );
			}
		});
	})( jQuery );	


(function( $ ) { $.widget( "ui.combobox", {
			_create: function() {
				var self = this,
					select = this.element.hide(),
					selected = select.children( ":selected" ),
					value = selected.val() ? selected.text() : "";
				var input = this.input = $( "<input>" )
					.insertAfter( select )
					.val( value )
					.autocomplete({
						delay: 0,
						minLength: 0,
						source: function( request, response ) {
							var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
							response( select.children( "option" ).map(function() {
								var text = $( this ).text();
								if ( this.value && ( !request.term || matcher.test(text) ) )
									return {
										label: text.replace(
											new RegExp(
												"(?![^&;]+;)(?!<[^<>]*)(" +
												$.ui.autocomplete.escapeRegex(request.term) +
												")(?![^<>]*>)(?![^&;]+;)", "gi"
											), "<strong>$1</strong>" ),
										value: text,
										option: this
									};
							}) );
						},
						select: function( event, ui ) {
							ui.item.option.selected = true;
							self._trigger( "selected", event, {
								item: ui.item.option
							});
							comboboxchanged();
							
						},
						change: function( event, ui ) {
							if ( !ui.item ) {
								var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
									valid = false;
								select.children( "option" ).each(function() {
									if ( $( this ).text().match( matcher ) ) {
										this.selected = valid = true;
										return false;
									}
								});
								if ( !valid ) {
									// remove invalid value, as it didn't match anything
									$( this ).val( "" );
									select.val( "" );
									input.data( "autocomplete" ).term = "";
									return false;
								}
							}
						
	
						}
						
							
					})
					.addClass( "ui-widget ui-widget-content ui-corner-left" );

				input.data( "autocomplete" )._renderItem = function( ul, item ) {
					return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" + item.label + "</a>" )
						.appendTo( ul );
				};

				this.button = $( "<button type='button'>&nbsp;</button>" )
					.attr( "tabIndex", -1 )
					.attr( "title", "Show All" )
					.attr( "id", "comboboxbutton")
					.insertAfter( input )
					.button({
						icons: {
							primary: "ui-icon-triangle-1-s"
						},
						text: false
					})
					.removeClass( "ui-corner-all" )
					.addClass( "ui-corner-right ui-button-icon" )
					.click(function() {
						// close if already visible
						if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
							input.autocomplete( "close" );
							return;
						}

						// work around a bug (likely same cause as #5265)
						$( this ).blur();

						// pass empty string as value to search for, displaying all results
						input.autocomplete( "search", "" );
						input.focus().setCursorPosition(99);
					});
			},

			destroy: function() {
				this.input.remove();
				this.button.remove();
				this.element.show();
				$.Widget.prototype.destroy.call( this );
			},

                        autocomplete : function(value,value2) {
    this.element.val(value);
    this.input.val(value2);
}
		});
	})( jQuery );
	

(function( $ ) { $.widget( "ui.newdepcombobox", {
			_create: function() {
				var self = this,
					select = this.element.hide(),
					selected = select.children( ":selected" ),
					value = selected.val() ? selected.text() : "";
				var input = this.input = $( "<input>" )
					.insertAfter( select )
					.val( value )
					.autocomplete({
						delay: 0,
						minLength: 0,
						source: function( request, response ) {
							var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
							response( select.children( "option" ).map(function() {
								var text = $( this ).text();
								if ( this.value && ( !request.term || matcher.test(text) ) )
									return {
										label: text.replace(
											new RegExp(
												"(?![^&;]+;)(?!<[^<>]*)(" +
												$.ui.autocomplete.escapeRegex(request.term) +
												")(?![^<>]*>)(?![^&;]+;)", "gi"
											), "<strong>$1</strong>" ),
										value: text,
										option: this
									};
							}) );
						},
						select: function( event, ui ) {				
							ui.item.option.selected = true;
							self._trigger( "selected", event, {
								item: ui.item.option
								});
	var params='value='+ value;
pic1 = new Image(16, 16); 
pic1.src = "images/loader.gif";
var usr = $("#newjobselectdep").val();
// var usr = value ;
if(usr.length >= 1) {
    $("#afterdepselect").html('<a href="#" class="loader"></a> ');
    $("#toploader").show();
    $.ajax({
        type: "POST",  
        url: "ajax_lookup.php",  
        data: "lookuppage=ajaxcheckdep&newjobdepid="+ usr ,  
        success: function(data){
            $("#status").append(data);
            $("#newjobdetails").show();
        },
        complete: function (data) {
            $("#toploader").fadeOut();
            $("input[name=requestedby]").focus().setCursorPosition(42);
        }
    }); 
}
else
	{
	$("#status").html('<font color="red">Please select a department.</font>');
	}							
	},
						change: function( event, ui ) {
							if ( !ui.item ) {
								var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
									valid = false;
								select.children( "option" ).each(function() {
									if ( $( this ).text().match( matcher ) ) {
										this.selected = valid = true;
										return false;
									}
								});
								if ( !valid ) {
									// remove invalid value, as it didn't match anything
									$( this ).val( "" );
									select.val( "" );
									input.data( "autocomplete" ).term = "";
									return false;
								}
							}
						}
					})
					.addClass( "ui-widget ui-widget-content ui-corner-left" );
				input.data( "autocomplete" )._renderItem = function( ul, item ) {
					return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" + item.label + "</a>" )
						.appendTo( ul );
				};
				this.button = $( "<button type='button'>&nbsp;</button>" )
					.attr( "tabIndex", -1 )
					.attr( "title", "Show All" )
					.attr( "id", "depselectbutton" )
					.insertAfter( input )
					.button({
						icons: {
							primary: "ui-icon-triangle-1-s"
						},
						text: false
					})
					.removeClass( "ui-corner-all" )
					.addClass( "ui-corner-right ui-button-icon" )
					.click(function() {
						// close if already visible
						if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
							input.autocomplete( "close" );
									return;					
						}
						// work around a bug (likely same cause as #5265)
						$( this ).blur();

						// pass empty string as value to search for, displaying all results
						input.autocomplete( "search", "" );
							input.focus().setCursorPosition(99);
					});
			},
			destroy: function() {
				this.input.remove();
				this.button.remove();
				this.element.show();
				$.Widget.prototype.destroy.call( this );
			}
		});
		
		
	
		
	})( jQuery );



(function( $ ) { $.widget( "ui.newjobcombobox", {
			_create: function() {
				var self = this,
					select = this.element.hide(),
					selected = select.children( ":selected" ),
					value = selected.val() ? selected.text() : "";
				var input = this.input = $( "<input>" )
					.insertAfter( select )
					.val( value )
					.autocomplete({
						delay: 0,
						minLength: 0,
						source: function( request, response ) {
							var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
							response( select.children( "option" ).map(function() {
								var text = $( this ).text();
								if ( this.value && ( !request.term || matcher.test(text) ) )
									return {
										label: text.replace(
											new RegExp(
												"(?![^&;]+;)(?!<[^<>]*)(" +
												$.ui.autocomplete.escapeRegex(request.term) +
												")(?![^<>]*>)(?![^&;]+;)", "gi"
											), "<strong>$1</strong>" ),
										value: text,
										option: this
									};
							}) );
						},
						select: function( event, ui ) {						
							ui.item.option.selected = true;
							self._trigger( "selected", event, {
								item: ui.item.option
							});								
var params='value='+ value;
pic1 = new Image(16, 16); 
pic1.src = "images/loader.gif";
var usr = $("#newjobselectclient").val();
// var usr = value ;
if(usr.length >= 1)
{
$("#afterclientselect").html('<a href="#" class="loader"></a> ');
$("#toploader").show();
    $.ajax({
    type: "POST",  
    url: "ajax_lookup.php",  
    data: "lookuppage=ajaxcheck&newjobselectclient="+ usr ,  
    success: function(msg){
		$("#status").html(msg);
        $("input[name=requestedby]").focus().setCursorPosition(42);
        $("#newjobselectdep").newdepcombobox();
        $("#afterclientselect").html(clientdetails);
	},
    complete: function(){
        $("#depselectbutton").click();
        $("#toploader").fadeOut();
        $( "#frombox" ).frombox();
        $( "#tobox" ).tobox();
    }    
  }); 
}
else
	{
	$("#status").html('<font color="red">Please select a client.</font>');
	}
	},
						change: function( event, ui ) {
							if ( !ui.item ) {
								var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
									valid = false;
								select.children( "option" ).each(function() {
									if ( $( this ).text().match( matcher ) ) {
										this.selected = valid = true;
										return false;
									}
								});
								if ( !valid ) {
									// remove invalid value, as it didn't match anything
									$( this ).val( "" );
									select.val( "" );
									input.data( "autocomplete" ).term = "";
									return false;
								}
							}
						}
					})
					.addClass( "ui-widget ui-widget-content ui-corner-left" )
					.attr( "id", "newjobinput");

				input.data( "autocomplete" )._renderItem = function( ul, item ) {
					return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" + item.label + "</a>" )
						.appendTo( ul );
				};

				this.button = $( "<button type='button'>&nbsp;</button>" )
					.attr( "tabIndex", -1 )
					.attr( "id", "newjobbutton")
					.attr( "title", "Show All Clients" )
					.insertAfter( input )
					.button({
						icons: {
							primary: "ui-icon-triangle-1-s"
						},
						text: false
					})
					.removeClass( "ui-corner-all" )
					.addClass( "ui-corner-right ui-button-icon" )
					.click(function() {
						// close if already visible
						if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
							input.autocomplete( "close" );
		
							return;					
						}
						// work around a bug (likely same cause as #5265)
						$( this ).blur();

						// pass empty string as value to search for, displaying all results
						input.autocomplete( "search", "" );
							input.focus().setCursorPosition(99);
	
					});
			},
			destroy: function() {
				this.input.remove();
				this.button.remove();
				this.element.show();
				$.Widget.prototype.destroy.call( this );
			}
			});
	})( jQuery );

(function( $ ) { $.widget( "ui.tobox", {
			_create: function() {
				var self = this,
					select = this.element.hide(),
					selected = select.children( ":selected" ),
					value = selected.val() ? selected.text() : "";
				var input = this.input = $( "<input>" )
					.insertAfter( select )
					.val( value )
					.autocomplete({
						delay: 0,
						minLength: 0,
						source: function( request, response ) {
							var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
							response( select.children( "option" ).map(function() {
								var text = $( this ).text();
								if ( this.value && ( !request.term || matcher.test(text) ) )
									return {
										label: text.replace(
											new RegExp(
												"(?![^&;]+;)(?!<[^<>]*)(" +
												$.ui.autocomplete.escapeRegex(request.term) +
												")(?![^<>]*>)(?![^&;]+;)", "gi"
											), "<strong>$1</strong>" ),
										value: text,
										option: this
									};
							}) );
						},
						select: function( event, ui ) {
							ui.item.option.selected = true;
							self._trigger( "selected", event, {
								item: ui.item.option
							});
						},
						change: function( event, ui ) {
							if ( !ui.item ) {
								var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
									valid = false;
								select.children( "option" ).each(function() {
									if ( $( this ).text().match( matcher ) ) {
										this.selected = valid = true;
										return false;
									}
								});
								if ( !valid ) {
									// remove invalid value, as it didn't match anything
									$( this ).val( "" );
									select.val( "" );
									input.data( "autocomplete" ).term = "";
									return false;
								}
							}
						}
					})
					.addClass( "ui-widget ui-widget-content ui-corner-left modtobox" );
input.attr('id', 'modtobox');
				input.data( "autocomplete" )._renderItem = function( ul, item ) {
					return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" + item.label + "</a>" )
						.appendTo( ul );
				};

				this.button = $( "<button type='button'>&nbsp;</button>" )
					.attr( "tabIndex", -1 )
					.attr( "title", "Show All" )
					.attr( "id", "toselectbutton")
					.insertAfter( input )
					.button({
						icons: {
							primary: "ui-icon-triangle-1-s"
						},
						text: false
					})
					.removeClass( "ui-corner-all" )
					.addClass( "ui-corner-right ui-button-icon" )
					.click(function() {
						// close if already visible
						if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
							input.autocomplete( "close" );
							return;
						}

						// work around a bug (likely same cause as #5265)
						$( this ).blur();

						// pass empty string as value to search for, displaying all results
						input.autocomplete( "search", "" );
						input.focus().setCursorPosition(99);
					});
			},

			destroy: function() {
				this.input.remove();
				this.button.remove();
				this.element.show();
				$.Widget.prototype.destroy.call( this );
			}
		});
	})( jQuery );
	
	
(function( $ ) { $.widget( "ui.frombox", {
			_create: function() {
				var self = this,
					select = this.element.hide(),
					selected = select.children( ":selected" ),
					value = selected.val() ? selected.text() : "";
				var input = this.input = $( "<input>" )
					.insertAfter( select )
					.val( value )
					.autocomplete({
						delay: 0,
						minLength: 0,
						source: function( request, response ) {
							var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
							response( select.children( "option" ).map(function() {
								var text = $( this ).text();
								if ( this.value && ( !request.term || matcher.test(text) ) )
									return {
										label: text.replace(
											new RegExp(
												"(?![^&;]+;)(?!<[^<>]*)(" +
												$.ui.autocomplete.escapeRegex(request.term) +
												")(?![^<>]*>)(?![^&;]+;)", "gi"
											), "<strong>$1</strong>" ),
										value: text,
										option: this
									};
							}) );
						},
						select: function( event, ui ) {
							ui.item.option.selected = true;
							self._trigger( "selected", event, {
								item: ui.item.option
							});
						},
						change: function( event, ui ) {
							if ( !ui.item ) {
								var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
									valid = false;
								select.children( "option" ).each(function() {
									if ( $( this ).text().match( matcher ) ) {
										this.selected = valid = true;
										return false;
									}
								});
								if ( !valid ) {
									// remove invalid value, as it didn't match anything
									$( this ).val( "" );
									select.val( "" );
									input.data( "autocomplete" ).term = "";
									return false;
								}
							}
						}
					}) 
				.addClass( "ui-widget ui-widget-content ui-corner-left modfrombox" );
				input.attr('id', 'modfrombox');
 
				input.data( "autocomplete" )._renderItem = function( ul, item ) {
					return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" + item.label + "</a>" )
						.appendTo( ul );
				};

				this.button = $( "<button type='button'>&nbsp;</button>" )
					.attr( "tabIndex", -1 )
					.attr( "title", "Show All" )
					.attr( "id", "fromselectbutton")
					.insertAfter( input )
					.button({
						icons: {
							primary: "ui-icon-triangle-1-s"
						},
						text: false
					})
					.removeClass( "ui-corner-all" )
					.addClass( "ui-corner-right ui-button-icon" )
					.click(function() {
						// close if already visible
						if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
							input.autocomplete( "close" );
							return;
						}

						// work around a bug (likely same cause as #5265)
						$( this ).blur();

						// pass empty string as value to search for, displaying all results
						input.autocomplete( "search", "" );
						input.focus().setCursorPosition(99);
					});
			},

			destroy: function() {
				this.input.remove();
				this.button.remove();
				this.element.show();
				$.Widget.prototype.destroy.call( this );
			}
		});
	})( jQuery );	
	
	

function showmessage() {
	if ((allok)==1) {
        $("#pagetext").stop(true, true).removeAttr("style").attr("height","100%").html(message).delay(ptdelay).slideUp(ptslide);
    } else {
        $("#alerttext").stop(true, true).removeAttr("style").attr("height","100%").html(message).delay(alertdelay).slideUp(alertslide)
    }
}

function pageloadedfine() {
    $(document).ready(function() {
        setTimeout( function() {
        var hr = new XMLHttpRequest();
        var url = "ajaxkickcron.php";
        var vars = "screenWidth=" + screen.width + "&screenHeight=" + screen.height + "&newauditid=" + initialauditid;
        hr.open("POST", url, true);
        hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        // Access the onreadystatechange event for the XMLHttpRequest object
        hr.onreadystatechange = function() {
            if(hr.readyState == 4 && hr.status == 200) {
            var return_data = hr.responseText;
            //  $("#infotext").append("<br /> Sending screen size");	           
			$("#activestatus").append("<br />" + return_data);
            }
        }
        hr.send(vars); // Actually execute the request
        // adds sticky css polyfill for top menu
        }, 450 );
    });
}


$(document).ready(function() {


if (pagetimeout>1) {
    var interval = null;
    $(document).on('ready',function(){
        interval = setInterval(updatetime,1000);
    });
}


    function updatetime() {
        pagetimeout--;
        $('#cdtext').attr('title',pagetimeout + 's. ');
        if (pagetimeout<75) {
            if(pagetimeout == 74){ $("span[id=cdtext]").addClass('t121'); }// opacity 0        
            if(pagetimeout == 64){ $("span[id=cdtext]").addClass('t120').removeClass('hideuntilneeded');; }
            if(pagetimeout == 60){ $("span[id=cdtext]").removeClass('t121') }// opacity 0                
            if(pagetimeout == 45){ $("span[id=cdtext]").removeClass('t120');  }
            if(pagetimeout == 45){ $("span[id=cdtext]").addClass('t60'); }
            
            if(pagetimeout == 35){ $("span[id=cdtext]").removeClass('t60'); }
            if(pagetimeout == 35){ $("span[id=cdtext]").addClass('t30'); }
            
            
            
            
            if(pagetimeout == 25){ $("span[id=cdtext]").removeClass('t60'); }
            if(pagetimeout == 25){ $("span[id=cdtext]").addClass('t20'); }
            
            
            if(pagetimeout == 15){ $("span[id=cdtext]").removeClass('t20'); }
            if(pagetimeout == 15){ $("span[id=cdtext]").addClass('t1'); } // background red
            if(pagetimeout == 9){ $("span[id=cdtext]").removeClass('t1'); }
            if(pagetimeout == 9){ $("span[id=cdtext]").addClass('t2'); }
            if(pagetimeout == 8){ $("span[id=cdtext]").removeClass('t2'); }
            if(pagetimeout == 8){ $("span[id=cdtext]").addClass('t1'); }
            if(pagetimeout == 7){ $("span[id=cdtext]").removeClass('t1'); }
            if(pagetimeout == 7){ $("span[id=cdtext]").addClass('t2'); }
            if(pagetimeout == 6){ $("span[id=cdtext]").removeClass('t2'); }
            if(pagetimeout == 6){ $("span[id=cdtext]").addClass('t1'); }
            if(pagetimeout == 5){ $("span[id=cdtext]").removeClass('t1'); }
            if(pagetimeout == 5){ $("span[id=cdtext]").addClass('t2'); }
            if(pagetimeout == 4){ $("span[id=cdtext]").removeClass('t2'); }
            if(pagetimeout == 4){ $("span[id=cdtext]").addClass('t1'); }
            if(pagetimeout == 3){ $("span[id=cdtext]").removeClass('t1'); }
            if(pagetimeout == 3){ $("span[id=cdtext]").addClass('t2'); }
            if(pagetimeout == 2){ $("span[id=cdtext]").removeClass('t2'); }
            if(pagetimeout == 2){ $("span[id=cdtext]").addClass('t1'); }
            if(pagetimeout == 1){ $("span[id=cdtext]").removeClass('t1'); }
            if(pagetimeout == 1){ $("span[id=cdtext]").addClass('t2'); }
            
            if(pagetimeout == 0){
                if (typeof pagetimeoutfunc !== "undefined") {
                    pagetimeoutfunc();
                    $("span[id=cdtext]").removeClass('t2');
                } else {
                    clearInterval(interval); // stop the interval
                    $("span[id=cdtext]").html('Timed Out');
                }
            }
            
        }

    }




	// https://github.com/MartinF/jQuery.Autosize.Input

// Enable using $(selector).autosizeInput() 
// or data attribute <input type="text" value="" placeholder="Autosize" data-autosize-input='{ "space": 40 }' /> 
// Use css min/max-width to limit the size.
    
var Plugins;
(function (Plugins) {
    var AutosizeInputOptions = (function () {
        function AutosizeInputOptions(space) {
            if (typeof space === "undefined") { space = 30; }
            this.space = space;
        }
        return AutosizeInputOptions;
    })();
    Plugins.AutosizeInputOptions = AutosizeInputOptions;

    var AutosizeInput = (function () {
        function AutosizeInput(input, options) {
            var _this = this;
            this._input = $(input);
            this._options = $.extend({}, AutosizeInput.getDefaultOptions(), options);
            this._updateHandler = function(e){
                _this.update()
            };

            // Init mirror
            this._mirror = $('<span style="position:absolute; top:-999px; left:0; white-space:pre;"/>');

            // Copy to mirror
            $.each(['fontFamily', 'fontSize', 'fontWeight', 'fontStyle', 'letterSpacing', 'textTransform', 'wordSpacing', 'textIndent'], function (i, val) {
                _this._mirror[0].style[val] = _this._input.css(val);
            });
            $("body").append(this._mirror);

            // Bind events - change update paste click mousedown mouseup focus blur
            // IE 9 need keydown to keep updating while deleting (keeping backspace in - else it will first update when backspace is released)
            // IE 9 need keyup incase text is selected and backspace/deleted is hit - keydown is to early
            // How to fix problem with hitting the delete "X" in the box - but not updating!? mouseup is apparently to early
            // Could bind separatly and set timer
            // Add so it automatically updates if value of input is changed http://stackoverflow.com/a/1848414/58524
            this._input.on("keydown keyup input propertychange change", this._updateHandler);

            // Update
            (function () {
                _this.update();
            })();
        }
        AutosizeInput.prototype.getOptions = function () {
            return this._options;
        };

        AutosizeInput.prototype.destroy = function() {
            this._mirror.remove();
            this._input.off("keydown keyup input propertychange change", null,  this._updateHandler);
        };

        AutosizeInput.prototype.update = function () {
            var value = this._input.val() || "";
            if (value === this._mirror.text()) {
                // Nothing have changed - skip
                return;
            }

            // Update mirror
            this._mirror.text(value);

            // Calculate the width
            var newWidth = this._mirror.width() + this._options.space;

            // Update the width
            this._input.width(newWidth);
            
            
            
            
            
        };

        AutosizeInput.getDefaultOptions = function () {
            return this._defaultOptions;
        };

        AutosizeInput.getInstanceKey = function () {
            // Use camelcase because .data()['autosize-input-instance'] will not work
            return "autosizeInputInstance";
        };
        AutosizeInput._defaultOptions = new AutosizeInputOptions();
        return AutosizeInput;
    })();
    Plugins.AutosizeInput = AutosizeInput;

    // jQuery Plugin
    (function ($) {
        var pluginDataAttributeName = "autosize-input";
        var validTypes = ["text", "password", "search", "url", "tel", "email", "number"];
        var methods = {
            destroy : function() {
                var $this = $(this);
                $this.data(Plugins.AutosizeInput.getInstanceKey()).destroy()
            }
        };

        // jQuery Plugin
        $.fn.autosizeInput = function (optionsAndMethods) {
            return this.each(function () {
                // Make sure it is only applied to input elements of valid type
                // Or let it be the responsibility of the programmer to only select and apply to valid elements?
                if (!(this.tagName == "INPUT" && $.inArray(this.type, validTypes) > -1)) {
                    // Skip - if not input and of valid type
                    return;
                }

                var $this = $(this);

                if ( options == undefined ) {
                    if (methods[optionsAndMethods]){
                        var method = optionsAndMethods;
                    }else{
                        var options = optionsAndMethods;
                    }
                }

                if (!$this.data(Plugins.AutosizeInput.getInstanceKey())) {
                    // If instance not already created and attached
                    if (options == undefined) {
                        // Try get options from attribute
                        var options = $this.data(pluginDataAttributeName);
                    }

                    // Create and attach instance
                    $this.data(Plugins.AutosizeInput.getInstanceKey(), new Plugins.AutosizeInput(this, options));
                }

                if ( method !=  undefined) {
                    return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
                }
            });
        };

        // On Document Ready
        $(function () {
            // Instantiate for all with data-provide=autosize-input attribute
            $("input[data-" + pluginDataAttributeName + "]").autosizeInput();
        });
        // Alternative to use On Document Ready and creating the instance immediately
        //$(document).on('focus.autosize-input', 'input[data-autosize-input]', function (e)
        //{
        //	$(this).autosizeInput();
        //});
    })(jQuery);
})(Plugins || (Plugins = {}));







    $.fn.setCursorPosition = function (pos) {
        this.each(function (index, elem) {
            if (elem.setSelectionRange) {
                elem.setSelectionRange(pos, pos);
            } else if (elem.createTextRange) {
                var range = elem.createTextRange();
                range.collapse(true);
                range.moveEnd('character', pos);
                range.moveStart('character', pos);
                range.select();
            }
        });
        return this;
    };


    // Returns a function, that, as long as it continues to be invoked, will not
    // be triggered. The function will be called after it stops being called for
    // N milliseconds. If `immediate` is passed, trigger the function on the
    // leading edge, instead of the trailing.
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };


    // eg 
    var myEfficientFn = debounce(function() {
        // All the taxing stuff you do
        if ($(this).scrollTop() > 700) {
            $("#back-top").fadeIn(249);
        } else {
            $("#back-top").fadeOut(249);
        }
    }, 250);



	

    $(window).scroll(function () { 	// fade in #back-top
        myEfficientFn();
    });
    
            // scroll body to 0px on click
    $("#back-top").click(function () {
        $("body,html").animate({
            scrollTop: 0
            }, 800);
        return false;
    });

 

    $("#menusearch").autosizeInput();
    $('#menusearch').change(function() { $('#menusearch').submit(); });
    
    
    
    // initial page load
    $('#pagetext').delay(ptdelay).slideUp(ptslide);
    $('#alerttext').delay(alertdelay).slideUp(alertslide);



    $("#togglenewjobchoose").click(function(){
        $( "#togglenewjobchoose" ).toggleClass( "selected" )
        if ($("#togglenewjob").is(':visible')) {
            $("#togglenewjob").hide();
            $('div.Post').css({ 'opacity': '1' });        
        } else {
            $("#togglenewjob").show(); 
            $('div.Post').css({ 'opacity': '0.4' });
            var newclientid=$("select#newjobselectclient").val();        
            if (newclientid === "") {
            setTimeout( function() { $("#newjobbutton").click() }, 150 );
            }
        }
    });


    $("#togglesettingsmenuchoose").click(function(){
        $( "#togglesettingsmenuchoose" ).toggleClass( "selected" );
        $("#togglesettingsmenu").slideToggle("fast");
    });
    $("#toggleinvoicemenuchoose").click(function(){
        $( "#toggleinvoicemenuchoose" ).toggleClass( "selected" );
        $("#toggleinvoicemenu").slideToggle("fast");
    });

    $( "#menusearchinput" ).focus(function() {
        $("#menuds").show();    
    });

    setTimeout( function () { pageloadedfine(); }, 950 ); 
    
    
    if ( showdebug >0 ) {
        setTimeout( function () {
            $("#activestatus").slideUp(1500); 
        }, 20000 ); 
    }



    function b64EncodeUnicode(str) {
        return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, function(match, p1) {
            return String.fromCharCode('0x' + p1);
        }));
    }
    
    
}); // ends doc ready


function b64DecodeUnicode(str) {
    return decodeURIComponent(Array.prototype.map.call(atob(str), function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));
}
    
