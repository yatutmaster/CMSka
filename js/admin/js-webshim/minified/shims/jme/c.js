webshims.register("mediacontrols",function(a,b,c){"use strict";var d=b.cfg.mediaelement.jme,e=d.selector,f='<button class="{%class%}" type="button" aria-label="{%text%}"></button>',g='<div class="{%class%} media-range"></div>',h='<div  class="{%class%}">00:00</div>',i=function(){var a,b="";return"function"==typeof c.Audio&&(a=new Audio,a.volume=.55,b=a.volume=""),b}(),j=function(){var b={},c=/\{\{(.+?)\}\}/gim;return function(e,f){return e||(e=d.barTemplate),(!b[e]||f)&&(b[e]=e.replace(c,function(b,c){var d=a.jme.plugins[c];return d&&d.structure?d.structure.replace("{%class%}",c).replace("{%text%}",d.text||""):b})),b[e]||""}}(),k=function(){k.loaded||(k.loaded=!0,b.loader.loadList(["mediacontrols-lazy","range-ui"]))},l=function(c){c||(c="_create");var d=function(e,f){var g=this,h=arguments;k(),b.ready("mediacontrols-lazy",function(){return d!=g[c]&&a.data(f[0])?g[c].apply(g,h):void b.error("stop too much recursion")})};return d};d.barTemplate||(d.barTemplate='<div class="play-pause-container">{{play-pause}}</div><div class="playlist-container"><div class="playlist-box">{{playlist-prev}}{{playlist-next}}</div></div><div class="currenttime-container">{{currenttime-display}}</div><div class="progress-container">{{time-slider}}</div><div class="duration-container">{{duration-display}}</div><div class="mute-container">{{mute-unmute}}</div><div class="volume-container">{{volume-slider}}</div><div class="subtitle-container"><div class="subtitle-controls">{{captions}}</div></div><div class="fullscreen-container">{{fullscreen}}</div>'),d.barStructure||(d.barStructure='<div class="jme-media-overlay"></div><div class="jme-controlbar'+i+'" tabindex="-1"><div class="jme-cb-box"></div></div>'),b.loader.addModule("mediacontrols-lazy",{src:"jme/mediacontrols-lazy"});var m={_create:l()};a.jme.plugins.useractivity=m,a.jme.defineProp("controlbar",{set:function(c,e){e=!!e;var f,g,h=a.jme.data(c),i=a("div.jme-mediaoverlay, div.jme-controlbar",h.player),k="";return e&&!i[0]?h._controlbar?h._controlbar.appendTo(h.player):(h.media.prop("controls",!1),k=j(),h._controlbar=a(d.barStructure),i=h._controlbar.find("div.jme-cb-box").addClass("media-controls"),f=h._controlbar.filter(".jme-media-overlay").addClass("play-pause"),f=f.add(i),a(k).appendTo(i),h._controlbar.appendTo(h.player),h.player.jmeFn("addControls",f),g=function(){var a,b=[{size:290,name:"xx-small"},{size:380,name:"x-small"},{size:490,name:"small"},{size:756,name:"medium"},{size:1024,name:"large"}],c=b.length;return function(){var d="x-large",e=0,f=h.player.outerWidth(),g=Math.max(parseInt(h.player.css("fontSize"),10)||16,13);for(f*=16/g;c>e;e++)if(b[e].size>=f){d=b[e].name;break}a!=d&&(a=d,h.player.attr("data-playersize",d))}}(),m._create(h.player,h.media,h.player),g(),b.ready("dom-support",function(){h.player.onWSOff("updateshadowdom",g),b.addShadowDom()})):e||i.detach(),i=null,f=null,e}}),a.jme.registerPlugin("play-pause",{structure:f,text:"play / pause",_create:l()}),a.jme.registerPlugin("mute-unmute",{structure:f,text:"mute / unmute",_create:l()}),a.jme.registerPlugin("volume-slider",{structure:g,_create:l()}),a.jme.registerPlugin("time-slider",{structure:g,options:{format:["mm","ss"]},_create:l()}),a.jme.defineProp("format",{set:function(b,c){a.isArray(c)||(c=c.split(":"));var d=a.jme.data(b);return d.format=c,a(b).triggerHandler("updatetimeformat"),d.player.triggerHandler("updatetimeformat"),"noDataSet"}}),a.jme.registerPlugin("duration-display",{structure:h,options:{format:"mm:ss"},_create:l()}),a.jme.defineProp("countdown",{set:function(b,c){var d=a.jme.data(b);return d.countdown=!!c,a(b).triggerHandler("updatetimeformat"),d.player.triggerHandler("updatetimeformat"),"noDataSet"}}),a.jme.registerPlugin("currenttime-display",{structure:h,options:{format:"mm:ss",countdown:!1},_create:l()}),a.jme.registerPlugin("poster-display",{structure:"<div />",options:{},_create:l()}),a.jme.registerPlugin("fullscreen",{options:{fullscreen:!0,autoplayfs:!1},structure:f,text:"enter fullscreen / exit fullscreen",_create:l()}),a.jme.registerPlugin("captions",{structure:f,text:"subtitles",_create:function(b,c,d){var e=c.find("track");b.wsclonedcheckbox=a(b).clone().attr({role:"checkbox"}).insertBefore(b),d.attr("data-tracks",e.length>1?"many":e.length),b.attr("aria-haspopup","true"),l().apply(this,arguments)}}),b.ready(b.cfg.mediaelement.plugins.concat(["mediaelement"]),function(){b.addReady(function(b,c){a(e,b).add(c.filter(e)).jmeProp("controlbar",!0)})}),b.ready("WINDOWLOAD",k)});