;/*!common/dep/bad.js*/
var BJ_REPORT=function(r){if(r.BJ_REPORT)return r.BJ_REPORT;var n=[],e={},t={id:0,uin:0,url:"",combo:1,ext:null,level:4,ignore:[],random:1,delay:1e3,submit:null,repeat:1},o=function(r,n){return Object.prototype.toString.call(r)==="[object "+(n||"Object")+"]"},u=function(r){var n=typeof r;return"object"===n&&!!r},i=function(r){return null===r?!0:o(r,"Number")?!1:!r},a=r.onerror;r.onerror=function(n,e,t,u,i){var s=n;i&&i.stack&&(s=c(i)),o(s,"Event")&&(s+=s.type?"--"+s.type+"--"+(s.target?s.target.tagName+"::"+s.target.src:""):""),h.push({msg:s,target:e,rowNum:t,colNum:u}),d(),a&&a.apply(r,arguments)};var s=function(r){try{if(r.stack){var n=r.stack.match("https?://[^\n]+");n=n?n[0]:"";var e=n.match(":(\\d+):(\\d+)");e||(e=[0,0,0]);var t=c(r);return{msg:t,rowNum:e[1],colNum:e[2],target:n.replace(e[0],"")}}return r.name&&r.message&&r.description?{msg:JSON.stringify(r)}:r}catch(o){return r}},c=function(r){var n=r.stack.replace(/\n/gi,"").split(/\bat\b/).slice(0,9).join("@").replace(/\?[^:]+/gi,""),e=r.toString();return n.indexOf(e)<0&&(n=e+"@"+n),n},f=function(r,n){var e=[],o=[],a=[];if(u(r)){r.level=r.level||t.level;for(var s in r){var c=r[s];if(!i(c)){if(u(c))try{c=JSON.stringify(c)}catch(f){c="[BJ_REPORT detect value stringify error] "+f.toString()}a.push(s+":"+c),e.push(s+"="+encodeURIComponent(c)),o.push(s+"["+n+"]="+encodeURIComponent(c))}}}return[o.join("&"),a.join(","),e.join("&")]},l=[],p=function(r){if(t.submit)t.submit(r);else{var n=new Image;l.push(n),n.src=r}},m=function(r){if(!u(r))return!0;var n=r.msg,o=e[n]=(parseInt(e[n],10)||0)+1;return o>t.repeat},g=[],v=0,d=function(r){if(t.report){for(;n.length;){var e=!1,u=n.shift();if(!m(u)){var i=f(u,g.length);if(o(t.ignore,"Array"))for(var a=0,s=t.ignore.length;s>a;a++){var c=t.ignore[a];if(o(c,"RegExp")&&c.test(i[1])||o(c,"Function")&&c(u,i[1])){e=!0;break}}e||(t.combo?g.push(i[0]):p(t.report+i[2]+"&_t="+ +new Date),t.onReport&&t.onReport(t.id,u))}}var l=g.length;if(l){var d=function(){clearTimeout(v),p(t.report+g.join("&")+"&count="+g.length+"&_t="+ +new Date),v=0,g=[]};r?d():v||(v=setTimeout(d,t.delay))}}},h=r.BJ_REPORT={push:function(r){if(Math.random()>=t.random)return h;var e=u(r)?s(r):{msg:r};return t.ext&&!e.ext&&(e.ext=t.ext),n.push(e),d(),h},report:function(r){return r&&h.push(r),d(!0),h},info:function(r){return r?(u(r)?r.level=2:r={msg:r,level:2},h.push(r),h):h},debug:function(r){return r?(u(r)?r.level=1:r={msg:r,level:1},h.push(r),h):h},init:function(r){if(u(r))for(var e in r)t[e]=r[e];var o=parseInt(t.id,10);return o&&(/qq\.com$/gi.test(location.hostname)&&(t.url||(t.url="//badjs2.qq.com/badjs"),t.uin||(t.uin=parseInt((document.cookie.match(/\buin=\D+(\d+)/)||[])[1],10))),t.report=(t.url||"/badjs")+"?id="+o+"&uin="+t.uin+"&from="+encodeURIComponent(location.href)+"&"),n.length&&d(),h},__onerror__:r.onerror};return"undefined"!=typeof console&&console.error&&setTimeout(function(){var r=((location.hash||"").match(/([#&])BJ_ERROR=([^&$]+)/)||[])[2];r&&console.error("BJ_ERROR",decodeURIComponent(r).replace(/(:\d+:\d+)\s*/g,"$1\n"))},0),h}(window);"undefined"!=typeof module&&(module.exports=BJ_REPORT),function(r){if(!r.BJ_REPORT)return void console.error("please load bg-report first");var n=function(n){r.BJ_REPORT.report(n)},e={};r.BJ_REPORT.tryJs=function(r){return r&&(n=r),e};var t,o=function(r,n){for(var e in n)r[e]=n[e]},u=function(r){return"function"==typeof r},i=function(e,o){return function(){try{return e.apply(this,o||arguments)}catch(u){if(n(u),u.stack&&console&&console.error&&console.error("[BJ-REPORT]",u.stack),!t){var i=r.onerror;r.onerror=function(){},t=setTimeout(function(){r.onerror=i,t=null},50)}throw u}}},a=function(r){return function(){for(var n,e=[],t=0,o=arguments.length;o>t;t++)n=arguments[t],u(n)&&(n=i(n)),e.push(n);return r.apply(this,e)}},s=function(r){return function(n,e){if("string"==typeof n)try{n=new Function(n)}catch(t){throw t}var o=[].slice.call(arguments,2);return n=i(n,o.length&&o),r(n,e)}},c=function(r,n){return function(){for(var e,t,o=[],a=0,s=arguments.length;s>a;a++)e=arguments[a],u(e)&&(t=i(e))&&(e.tryWrap=t)&&(e=t),o.push(e);return r.apply(n||this,o)}},f=function(r){var n,e;for(n in r)e=r[n],u(e)&&(r[n]=i(e));return r};e.spyJquery=function(){var n=r.$;if(!n||!n.event)return e;var t,o;n.zepto?(t=n.fn.on,o=n.fn.off,n.fn.on=c(t),n.fn.off=function(){for(var r,n=[],e=0,t=arguments.length;t>e;e++)r=arguments[e],u(r)&&r.tryWrap&&(r=r.tryWrap),n.push(r);return o.apply(this,n)}):window.jQuery&&(t=n.event.add,o=n.event.remove,n.event.add=c(t),n.event.remove=function(){for(var r,n=[],e=0,t=arguments.length;t>e;e++)r=arguments[e],u(r)&&r.tryWrap&&(r=r.tryWrap),n.push(r);return o.apply(this,n)});var i=n.ajax;return i&&(n.ajax=function(r,e){return e||(e=r,r=void 0),f(e),r?i.call(n,r,e):i.call(n,e)}),e},e.spyModules=function(){var n=r.require,t=r.define;return t&&(t.amd||t.mod)&&n&&(r.require=a(n),o(r.require,n),r.define=a(t),o(r.define,t)),r.seajs&&t&&(r.define=function(){for(var r,n=[],e=0,o=arguments.length;o>e;e++)r=arguments[e],u(r)&&(r=i(r),r.toString=function(r){return function(){return r.toString()}}(arguments[e])),n.push(r);return t.apply(this,n)},r.seajs.use=a(r.seajs.use),o(r.define,t)),e},e.spySystem=function(){return r.setTimeout=s(r.setTimeout),r.setInterval=s(r.setInterval),e},e.spyCustom=function(r){return u(r)?i(r):f(r)},e.spyAll=function(){return e.spyJquery().spyModules().spySystem(),e}}(window);
;/*!common/dep/mod.js*/
var require,define;!function(e){function r(e,r){function t(){clearTimeout(a)}if(!(e in u)){u[e]=!0;var i=document.createElement("script");if(r){var a=setTimeout(r,require.timeout);i.onerror=function(){clearTimeout(a),r()},"onload"in i?i.onload=t:i.onreadystatechange=function(){("loaded"==this.readyState||"complete"==this.readyState)&&t()}}return i.type="text/javascript",i.src=e,n.appendChild(i),i}}function t(e,t,n){var a=i[e]||(i[e]=[]);a.push(t);var o,u=s[e]||s[e+".js"]||{},l=u.pkg;u.url&&(o=l?c[l].url:u.url||e,r(o,n&&function(){n(e)}))}if(!require){var n=document.getElementsByTagName("head")[0],i={},a={},o={},u={},s={},c={};define=function(e,r){e=e.replace(/\.js$/i,""),a[e]=r;var t=i[e];if(t){for(var n=0,o=t.length;o>n;n++)t[n]();delete i[e]}},require=function(e){if(e&&e.splice)return require.async.apply(this,arguments);e=require.alias(e);var r=o[e];if(r)return r.exports;var t=a[e];if(!t)throw"[ModJS] Cannot find module `"+e+"`";r=o[e]={exports:{}};var n="function"==typeof t?t.apply(r,[require,r.exports,r]):t;return n&&(r.exports=n),r.exports},require.async=function(r,n,i){function o(e){for(var r=0,n=e.length;n>r;r++){var f=require.alias(e[r]);if(f in a){var p=s[f]||s[f+".js"];p&&"deps"in p&&p.deps!==e&&o(p.deps)}else if(!(f in c)){c[f]=!0,l++,t(f,u,i);var p=s[f]||s[f+".js"];p&&"deps"in p&&o(p.deps)}}}function u(){if(0==l--){for(var t=[],i=0,a=r.length;a>i;i++)t[i]=require(r[i]);n&&n.apply(e,t)}}"string"==typeof r&&(r=[r]);var c={},l=0;o(r),u()},require.resourceMap=function(e){var r,t;t=e.res;for(r in t)t.hasOwnProperty(r)&&(s[r]=t[r]);t=e.pkg;for(r in t)t.hasOwnProperty(r)&&(c[r]=t[r])},require.loadJs=function(e){r(e)},require.loadCss=function(e){if(e.content){var r=document.createElement("style");r.type="text/css",r.styleSheet?r.styleSheet.cssText=e.content:r.innerHTML=e.content,n.appendChild(r)}else if(e.url){var t=document.createElement("link");t.href=e.url,t.rel="stylesheet",t.type="text/css",n.appendChild(t)}},require.alias=function(e){return e.replace(/\.js$/i,"")},require.timeout=5e3}}(this);