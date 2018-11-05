define("common/js/jquery/jquery.placeholder",function(){function e(e){var a={},t=/^jQuery\d+$/;return $.each(e.attributes,function(e,l){l.specified&&!t.test(l.name)&&(a[l.name]=l.value)}),a}function a(e,a){var t=this,r=$(t);if(t.value==r.attr("placeholder")&&r.hasClass("placeholder"))if(r.data("placeholder-password")){if(r=r.hide().next().show().attr("id",r.removeAttr("id").data("placeholder-id")),e===!0)return r[0].value=a;r.focus()}else t.value="",r.removeClass("placeholder"),t==l()&&t.select()}function t(){var t,l=this,r=$(l),o=this.id;if(""==l.value){if("password"==l.type){if(!r.data("placeholder-textinput")){try{t=r.clone().attr({type:"text"})}catch(d){t=$("<input>").attr($.extend(e(this),{type:"text"}))}t.removeAttr("name").data({"placeholder-password":r,"placeholder-id":o}).bind("focus.placeholder",a),r.data({"placeholder-textinput":t,"placeholder-id":o}).before(t)}r=r.removeAttr("id").hide().prev().attr("id",o).show()}r.addClass("placeholder"),r[0].value=r.attr("placeholder")}else r.removeClass("placeholder")}function l(){try{return document.activeElement}catch(e){}}var r,o,d="[object OperaMini]"==Object.prototype.toString.call(window.operamini),c="placeholder"in document.createElement("input")&&!d,n="placeholder"in document.createElement("textarea")&&!d,i=$.fn,u=$.valHooks,p=$.propHooks;c&&n?(o=i.placeholder=function(){return this},o.input=o.textarea=!0):(o=i.placeholder=function(){var e=this;return e.filter((c?"textarea":":input")+"[placeholder]").not(".placeholder").bind({"focus.placeholder":a,"blur.placeholder":t}).data("placeholder-enabled",!0).trigger("blur.placeholder"),e},o.input=c,o.textarea=n,r={get:function(e){var a=$(e),t=a.data("placeholder-password");return t?t[0].value:a.data("placeholder-enabled")&&a.hasClass("placeholder")?"":e.value},set:function(e,r){var o=$(e),d=o.data("placeholder-password");return d?d[0].value=r:o.data("placeholder-enabled")?(""==r?(e.value=r,e!=l()&&t.call(e)):o.hasClass("placeholder")?a.call(e,!0,r)||(e.value=r):e.value=r,o):e.value=r}},c||(u.input=r,p.value=r),n||(u.textarea=r,p.value=r),$(function(){$(document).delegate("form","submit.placeholder",function(){var e=$(".placeholder",this).each(a);setTimeout(function(){e.each(t)},10)})}))});
define('common/module/activePopupBox/activePopupBox.tpl', function(require, exports, module) {

  return  function (it, opt) {
      it = it || {};
      with(it) {
          var _$out_= [];
          _$out_.push('<div class="acpopup"><div class="acpopup-bg"><div class="acpopup-content">');
         if(isIE8){ 
        _$out_.push('<img class="acpopup-content-close" src="', '/pc-dist/common/module/activePopupBox/close_06bab33.png', '"/>');
         }else{ 
        _$out_.push('<i class="acpopup-content-close reasy-font icon-fonts-close3" style="color: ', closeColor, '"></i>');
         } 
        _$out_.push('<img class="acpopup-content-btn" src="', imgUrl, '"></div></div></div>');
        return _$out_.join('');
      }
  }

});

define("common/module/activePopupBox/activePopupBox",function(t){function o(t,o){this.first=!0,this.$cont=$(t),o&&this.init(o)}var n=t("common/module/activePopupBox/activePopupBox.tpl");return o.prototype.renderHTML=function(){this.$cont.append(n(this.datas))},o.prototype.offBindEvent=function(){$(document).off("click",".acpopup-content-close, .acpopup-content-btn")},o.prototype.bindEvent=function(){var t=this;$(document).on("click",".acpopup-content-close",function(){t.datas.onClose&&t.datas.onClose(),t.offBindEvent(),$(this).parents(".acpopup").remove()}).on("click",".acpopup-content-btn",function(){t.datas.onClick&&t.datas.onClick(),t.offBindEvent(),$(this).parents(".acpopup").remove(),window.location=t.datas.linkUrl})},o.prototype.init=function(t){t&&(this.datas=t,this.datas.isIE8=!$.support.htmlSerialize),this.first&&(this.first=!1,this.renderHTML(),this.bindEvent())},o});