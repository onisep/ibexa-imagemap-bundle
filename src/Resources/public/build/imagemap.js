/*! For license information please see imagemap.js.LICENSE.txt */
(()=>{var e={486:(e,t)=>{var n,a,r;!function(){"use strict";function i(){function e(){var e={width:i.width/i.naturalWidth,height:i.height/i.naturalHeight},t={width:parseInt(window.getComputedStyle(i,null).getPropertyValue("padding-left"),10),height:parseInt(window.getComputedStyle(i,null).getPropertyValue("padding-top"),10)};r.forEach((function(n,r){var i=0;a[r].coords=n.split(",").map((function(n){var a=1==(i=1-i)?"width":"height";return t[a]+Math.floor(Number(n)*e[a])})).join(",")}))}function t(e){return document.querySelector('img[usemap="'+e+'"]')}var n=this,a=null,r=null,i=null,o=null;"function"!=typeof n._resize?(a=n.getElementsByTagName("area"),r=Array.prototype.map.call(a,(function(e){return e.coords.replace(/ *, */g,",").replace(/ +/g,",")})),i=t("#"+n.name)||t(n.name),n._resize=e,i.addEventListener("load",e,!1),window.addEventListener("focus",e,!1),window.addEventListener("resize",(function(){clearTimeout(o),o=setTimeout(e,250)}),!1),window.addEventListener("readystatechange",e,!1),document.addEventListener("fullscreenchange",e,!1),i.width===i.naturalWidth&&i.height===i.naturalHeight||e()):n._resize()}a=[],n=function(){function e(e){e&&(function(e){if(!e.tagName)throw new TypeError("Object is not a valid DOM element");if("MAP"!==e.tagName.toUpperCase())throw new TypeError("Expected <MAP> tag, found <"+e.tagName+">.")}(e),i.call(e),t.push(e))}var t;return function(n){switch(t=[],typeof n){case"undefined":case"string":Array.prototype.forEach.call(document.querySelectorAll(n||"map"),e);break;case"object":e(n);break;default:throw new TypeError("Unexpected data type ("+typeof n+").")}return t}},void 0!==(r="function"==typeof n?n.apply(t,a):n)&&(e.exports=r),"jQuery"in window&&(window.jQuery.fn.imageMapResize=function(){return this.filter("map").each(i).end()})}()}},t={};function n(a){var r=t[a];if(void 0!==r)return r.exports;var i=t[a]={exports:{}};return e[a](i,i.exports,n),i.exports}n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var a in t)n.o(t,a)&&!n.o(e,a)&&Object.defineProperty(e,a,{enumerable:!0,get:t[a]})},n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{"use strict";var e=n(486),t=n.n(e);document.addEventListener("DOMContentLoaded",(function(){var e=function(e,t){t.preventDefault();var n=document.querySelector(this.getAttribute("href")),a=!n.hidden;if(a&&"true"===this.dataset.active)return this.dataset.active="false",n.hidden=!0,void n.dispatchEvent(new Event("imagemap-embed-closed"));e.querySelectorAll('area[data-active="true"]').forEach((function(e){return e.dataset.active="false"})),this.dataset.active="true",e.querySelectorAll(".imagemap__embeds__item:not([hidden])").forEach((function(e){return e.hidden=!0})),n.hidden=!1,scroll({top:n.offsetTop,behavior:"smooth"}),a||n.dispatchEvent(new Event("imagemap-embed-opened"))},n=function(e){e.preventDefault();var t=document.querySelector(this.getAttribute("href"));t.classList.add("imagemap__popins__item-open"),t.dispatchEvent(new Event("imagemap-popin-opened"))};t()(".imagemap map"),document.querySelectorAll(".imagemap").forEach((function(t){t.querySelectorAll('area[data-mode="embed"]').forEach((function(n){return n.addEventListener("click",e.bind(n,t))})),t.querySelectorAll('area[data-mode="popin"]').forEach((function(e){e.addEventListener("click",n);var t=document.querySelector(e.getAttribute("href"));t.querySelectorAll(".imagemap__popins__item__exit").forEach((function(e){e.addEventListener("click",(function(e){e.preventDefault(),t.classList.remove("imagemap__popins__item-open"),t.dispatchEvent(new Event("imagemap-popin-closed"))}))}))}))})),location.hash&&document.querySelector(location.hash+".imagemap__embeds__item")&&(document.querySelector(location.hash+".imagemap__embeds__item").hidden=!1)}))})()})();