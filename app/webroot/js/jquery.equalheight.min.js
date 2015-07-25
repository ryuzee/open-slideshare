/*
 *  EqualHeight.js - v1.0.1
 *  https://github.com/JorenVanHee/EqualHeight.js
 *
 *  Made by Joren Van Hee
 *  Under MIT License
 */
!function(a,b){function c(b,c){this.elements=b,this.options=a.extend({},e,c),this._defaults=e,this._name=d,this.active=!1,this.init()}var d="equalHeight",e={wait:!1,responsive:!0};c.prototype={init:function(){this.options.wait||this.start(),this.options.responsive&&a(b).on("resize",a.proxy(this.onWindowResize,this))},magic:function(){var b=0;this.reset();for(var c=0;c<this.elements.length;c++){var d=a(this.elements[c]).height();b=d>b?d:b}if(this.active){for(var e=0;e<this.elements.length;e++){var f=a(this.elements[e]);"table-cell"===f.css("display")?f.css("height",b):f.css("min-height",b)}this.active||this.reset()}},reset:function(){this.elements.css("min-height",""),this.elements.css("height","")},start:function(){this.active=!0,this.magic()},stop:function(){this.active=!1,this.reset()},onWindowResize:function(){this.active&&this.magic()}},a.fn[d]=function(a){return new c(this,a)}}(jQuery,window,document);