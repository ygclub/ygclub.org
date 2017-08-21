function Swipe2(e,t){"use strict";function h(){o=s.children,f=o.length,o.length<2&&(t.continuous=!1),i.transitions&&t.continuous&&o.length<3&&(s.appendChild(o[0].cloneNode(!0)),s.appendChild(s.children[1].cloneNode(!0)),o=s.children),u=new Array(o.length),a=e.getBoundingClientRect().width||e.offsetWidth,s.style.width=o.length*a+"px";var n=o.length;while(n--){var r=o[n];r.style.width=a+"px",r.setAttribute("data-index",n),i.transitions&&(r.style.left=n*-a+"px",g(n,l>n?-a:l<n?a:0,0))}t.continuous&&i.transitions&&(g(v(l-1),-a,0),g(v(l+1),a,0)),i.transitions||(s.style.left=l*-a+"px"),e.style.visibility="visible"}function p(){t.continuous?m(l-1):l&&m(l-1)}function d(){t.continuous?m(l+1):l<o.length-1&&m(l+1)}function v(e){return(o.length+e%o.length)%o.length}function m(e,n){if(l==e)return;if(i.transitions){var s=Math.abs(l-e)/(l-e);if(t.continuous){var f=s;s=-u[v(e)]/a,s!==f&&(e=-s*o.length+e)}var h=Math.abs(l-e)-1;while(h--)g(v((e>l?e:l)-h-1),a*s,0);e=v(e),g(l,a*s,n||c),g(e,0,n||c),t.continuous&&g(v(e-s),-(a*s),0)}else e=v(e),b(l*-a,e*-a,n||c);l=e,r(t.callback&&t.callback(l,o[l]))}function g(e,t,n){y(e,t,n),u[e]=t}function y(e,t,n){var r=o[e],i=r&&r.style;if(!i)return;i.webkitTransitionDuration=i.MozTransitionDuration=i.msTransitionDuration=i.OTransitionDuration=i.transitionDuration=n+"ms",i.webkitTransform="translate("+t+"px,0)"+"translateZ(0)",i.msTransform=i.MozTransform=i.OTransform="translateX("+t+"px)",i.display="table-cell",i.verticalAlign="top"}function b(e,n,r){if(!r){s.style.left=n+"px";return}var i=+(new Date),u=setInterval(function(){var a=+(new Date)-i;if(a>r){s.style.left=n+"px",w&&S(),t.transitionEnd&&t.transitionEnd.call(event,l,o[l]),clearInterval(u);return}s.style.left=(n-e)*(Math.floor(a/r*100)/100)+e+"px"},4)}function S(){E=setTimeout(d,w)}function x(){w=0,clearTimeout(E)}var n=function(){},r=function(e){setTimeout(e||n,0)},i={addEventListener:!!window.addEventListener,touch:"ontouchstart"in window||window.DocumentTouch&&document instanceof DocumentTouch,transitions:function(e){var t=["transitionProperty","WebkitTransition","MozTransition","OTransition","msTransition"];for(var n in t)if(e.style[t[n]]!==undefined)return!0;return!1}(document.createElement("swipe"))};if(!e)return;var s=e.children[0],o,u,a,f;t=t||{};var l=parseInt(t.startSlide,10)||0,c=t.speed||300;t.continuous=t.continuous!==undefined?t.continuous:!0;var w=t.auto||0,E,T={},N={},C,k={handleEvent:function(e){switch(e.type){case"touchstart":this.start(e);break;case"touchmove":this.move(e);break;case"touchend":r(this.end(e));break;case"webkitTransitionEnd":case"msTransitionEnd":case"oTransitionEnd":case"otransitionend":case"transitionend":r(this.transitionEnd(e));break;case"resize":r(h.call())}t.stopPropagation&&e.stopPropagation()},start:function(e){var t=e.touches[0];T={x:t.pageX,y:t.pageY,time:+(new Date)},C=undefined,N={},s.addEventListener("touchmove",this,!1),s.addEventListener("touchend",this,!1)},move:function(e){if(e.touches.length>1||e.scale&&e.scale!==1)return;t.disableScroll&&e.preventDefault();var n=e.touches[0];N={x:n.pageX-T.x,y:n.pageY-T.y},typeof C=="undefined"&&(C=!!(C||Math.abs(N.x)<Math.abs(N.y))),C||(e.preventDefault(),x(),w=t.auto||0,w&&(E=setTimeout(d,w)),clearTimeout(E),t.continuous?(y(v(l-1),N.x+u[v(l-1)],0),y(l,N.x+u[l],0),y(v(l+1),N.x+u[v(l+1)],0)):(N.x=N.x/(!l&&N.x>0||l==o.length-1&&N.x<0?Math.abs(N.x)/a+1:1),y(l-1,N.x+u[l-1],0),y(l,N.x+u[l],0),y(l+1,N.x+u[l+1],0)))},end:function(e){var n=+(new Date)-T.time,r=Number(n)<250&&Math.abs(N.x)>20||Math.abs(N.x)>a/2,i=!l&&N.x>0||l==o.length-1&&N.x<0;t.continuous&&(i=!1);var f=N.x<0;C||(r&&!i?(f?(t.continuous?(g(v(l-1),-a,0),g(v(l+2),a,0)):g(l-1,-a,0),g(l,u[l]-a,c),g(v(l+1),u[v(l+1)]-a,c),l=v(l+1)):(t.continuous?(g(v(l+1),a,0),g(v(l-2),-a,0)):g(l+1,a,0),g(l,u[l]+a,c),g(v(l-1),u[v(l-1)]+a,c),l=v(l-1)),t.callback&&t.callback(l,o[l])):t.continuous?(g(v(l-1),-a,c),g(l,0,c),g(v(l+1),a,c)):(g(l-1,-a,c),g(l,0,c),g(l+1,a,c))),s.removeEventListener("touchmove",k,!1),s.removeEventListener("touchend",k,!1)},transitionEnd:function(e){parseInt(e.target.getAttribute("data-index"),10)==l&&(w&&S(),t.transitionEnd&&t.transitionEnd.call(e,l,o[l]))}};return h(),w&&S(),i.addEventListener?(i.touch&&s.addEventListener("touchstart",k,!1),i.transitions&&(s.addEventListener("webkitTransitionEnd",k,!1),s.addEventListener("msTransitionEnd",k,!1),s.addEventListener("oTransitionEnd",k,!1),s.addEventListener("otransitionend",k,!1),s.addEventListener("transitionend",k,!1)),window.addEventListener("resize",k,!1)):window.onresize=function(){h()},{setup:function(){h()},slide:function(e,t){x(),m(e,t)},prev:function(){x(),p(),w=t.auto||0,w&&(E=setTimeout(p,w)),clearTimeout(E)},next:function(){x(),d(),w=t.auto||0,w&&(E=setTimeout(d,w)),clearTimeout(E)},getPos:function(){return l},getNumSlides:function(){return f},kill:function(){x(),s.style.width="auto",s.style.left=0;var e=o.length;while(e--){var t=o[e];t.style.width="100%",t.style.left=0,i.transitions&&y(e,0,0)}i.addEventListener?(s.removeEventListener("touchstart",k,!1),s.removeEventListener("webkitTransitionEnd",k,!1),s.removeEventListener("msTransitionEnd",k,!1),s.removeEventListener("oTransitionEnd",k,!1),s.removeEventListener("otransitionend",k,!1),s.removeEventListener("transitionend",k,!1),window.removeEventListener("resize",k,!1)):window.onresize=null}}}(window.jQuery||window.Zepto)&&function(e){e.fn.Swipe=function(t){return this.each(function(){e(this).data("Swipe",new Swipe(e(this)[0],t))})}}(window.jQuery||window.Zepto);
jQuery('img').lazyload({effect:'fadeIn'});var vars = vars || {}; var ajaxpostHandle;
(function($, window, undefined){
    "use strict";
    $(document).ready(function() {
        // Main Vars
        vars.hb = $('body,html');
        vars.$body = $("body");
        vars.$myScroll = null;
        vars.$window = $(window);
        vars.$swipe = vars.$body.find('div.swipe');
        vars.topnav = vars.$body.find('div.topnav');
        vars.hd = vars.$body.find('h2.hd');
        vars.container_map = vars.$body.find('div.container_map');
        vars.taglist = vars.$body.find('div.taglist');
        vars.$iconstar = vars.$body.find('#iconstar');
        vars.$iconshare = vars.$body.find('#icon-share');
        vars.former = vars.$body.find('#former');
        vars.dopost = vars.$body.find('#dopost');
        vars.$backtotop = vars.$body.find('#backtotop');
        vars.po = vars.$body.find('#page-loading-overlay');
        vars.searchbox = vars.$body.find('#searchbox');
        vars.formclear = vars.$body.find('#formclear');
        vars.$scrollTop = vars.$window.scrollTop();
        vars.$scrollTop_current = vars.$scrollTop;

        vars.icondottop = vars.$body.find('#icon-dot-top');
        vars.dottopmask = vars.$body.find('#dot-top-mask');
        vars.dottop     = vars.$body.find('#dot-top');
        vars.$floatshare = vars.$body.find('#floatshare');
        vars.$floatsharemask = vars.$body.find('#floatshare-mask');
        vars.$floatsharebox = vars.$body.find('#floatshare-box');
        vars.$wechat = vars.$body.find('#wechat');
        vars.$wechatmask = vars.$body.find('#wechat-mask');
        vars.$wechatguider = vars.$body.find('#wechat-guider');

        vars.$window.ready(function () {
            if (vars.po.length) {
                vars.po.addClass('loaded');
                setTimeout(function(){
                    vars.po.addClass('none');
                },500);
            }
            if(vars.container_map.length){
                setTimeout(function(){
                    vars.container_map.removeClass('container_map_ami');
                },1000);
            }
        });

        if(vars.$wechat.length){
            vars.$wechat.on('click',function(){
                vars.$wechatmask.show();
                Anmi(vars.$wechatguider, 'fadeInUp', 'fast');
                Anmi(vars.$wechatmask, 'fadeIn', 'normal');
            });
            vars.$wechatmask.on('click',function(){
                Anmi(vars.$wechatguider, 'fadeOutUp', 'fast');
                Anmi(vars.$wechatmask, 'fadeOut', 'normal', function(){
                    vars.$wechatmask.hide();
                });
            });
        }
        if(vars.icondottop.length){
            vars.icondottop.on('click', function() {
                vars.dottopmask.show();
                Anmi(vars.dottop, 'fadeInDown', 'fast');
                Anmi(vars.dottopmask, 'fadeIn', 'normal');
            });
            vars.dottopmask.on('click', function(){
                Anmi(vars.dottop, 'fadeOutUp', 'fast');
                Anmi(vars.dottopmask, 'fadeOut', 'normal', function(){
                    vars.dottopmask.hide();
                });
            });
        }
        if(vars.$floatshare.length){
            vars.$floatshare.on('click', function() {
                vars.$floatsharemask.show();
                Anmi(vars.$floatsharebox, 'fadeInUp', 'fast');
                Anmi(vars.$floatsharemask, 'fadeIn', 'normal');
            });
            vars.$floatsharemask.on('click',function(){
                Anmi(vars.$floatsharebox, 'fadeOutUp', 'fast');
                Anmi(vars.$floatsharemask, 'fadeOut', 'normal', function(){
                    vars.$floatsharemask.hide();
                });
            });
        }

        window.onerror = function () {
            vars.po.addClass('loaded');
        };
        if(vars.formclear.length){
            vars.formclear.on('click',function(){
                if(vars.searchbox.find('input[name="search"]').val()){
                    vars.searchbox.submit();
                }
            });
        }
        if(vars.$backtotop.length){
            vars.$window.scroll(function() {
                var topnow = vars.$window.scrollTop();
                if (topnow > 500) {
                    vars.$backtotop.addClass('backtotop_show');
                } else {
                    vars.$backtotop.removeClass('backtotop_show')
                }
                var opac = topnow>270 ? .9 : topnow/300;
                vars.topnav.css({background:"rgba(49,63,70,"+opac+")"});
                vars.hd.css({opacity:opac*2});
            });
            vars.$backtotop.on('click', function () {
                vars.hb.animate({scrollTop: 0}, 500);
                return false;
            });
        }
        if(vars.taglist.length){
            var acls = 'active';
            vars.taglist.find('a').on('click', function(){
                var input = $(this).find('input');
                if($(this).hasClass(acls)){
                    input.val('');
                    $(this).removeClass(acls);
                }else{
                    input.val($(this).attr('data-value'));
                    $(this).addClass(acls);
                }
            });
        }
        if(vars.$swipe.length){
            vars.$swipe.each(function(){ runslider($(this))});
        }
        /*share part*/
        if( vars.$iconshare.length){
            vars.$iconshare.on('click', function(){
                vars.$sharemask.show();
                Anmi(vars.$share, 'fadeInUp', 'fast');
                Anmi(vars.$sharemask, 'fadeIn', 'normal');
            });
            vars.$shareclose.on('click', function(){
                Anmi(vars.$share, 'fadeOutDown', 'fast');
                Anmi(vars.$sharemask, 'fadeOut', 'normal', function(){
                    vars.$sharemask.hide();
                });
            });
        }
        if(vars.dopost.length && vars.former.length){
            vars.dopost.on('click', function(){
                if(!check_Error()){
                    return false;
                }
                var ajaxframeid = 'ajaxframe';
                var ajaxframe = document.getElementById(ajaxframeid);
                if(ajaxframe == null) {
                    ajaxframe = document.createElement("iframe");
                    ajaxframe.name = ajaxframeid;
                    ajaxframe.id = ajaxframeid;
                    ajaxframe.style.display = 'none';
                    vars.$body.append(ajaxframe);
                }
                vars.former[0].target = ajaxframeid;

                ajaxpostHandle = [ajaxframeid];
                _attachEvent(ajaxframe, 'load', ajaxpost_load);
                if(vars.former.attr('accept-charset')){
                    document.charset=vars.former.attr('accept-charset');
                }
                vars.former[0].submit();
            });
        }
        function anchorError(str){
            var reg=/errorhandle_\(['"]([^"']*)['"],/g;
            var s = str.match(reg);
            return RegExp.$1;
        }
        function anchorSucceed(str){
            var reg=/succeedhandle_\(['"][^"']*['"],\s*['"]([^"']*)['"],/g;
            var s = str.match(reg);
            return RegExp.$1;
        }

        function Anmi(obj, x, fast, callback) {
            var ani = ' animated';
            if(fast == 'fast'){
                ani = ' animated-fast';
            }
            obj.removeClass().addClass(x + ani).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
                $(this).removeClass();
                if(callback && typeof callback == 'function') {
                    callback();
                }
            });
        }
        function runslider(_this){
            var position = _this.find('ul.position');
            new Swipe2(_this[0], {startSlide: 0,speed: 500,auto: 3000,continuous:true,callback:function(index){
                if(position.length>0){
                    var selectors=position[0].children;
                    for(var t=0;t<selectors.length;t++){
                        selectors[t].className=selectors[t].className.replace("current","");
                    }
                    selectors[(index)%(selectors.length)].className="current";
                }
            }} );
        }
        function strip_tags (input, allowed) {
            allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
            var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
                commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
            return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
                return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
            });
        }
        function trim(string){
            return string.replace(/(^\s*)|(\s*$)/g, "");
        }

        function _attachEvent(obj, evt, func, eventobj) {
            eventobj = !eventobj ? obj : eventobj;
            if(obj.addEventListener) {
                obj.addEventListener(evt, func, false);
            } else if(eventobj.attachEvent) {
                obj.attachEvent('on' + evt, func);
            }
        }
        function ajaxpost_load() { }
    });
})(jQuery, window);