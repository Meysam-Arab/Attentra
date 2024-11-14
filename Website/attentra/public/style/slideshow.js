/*	This work is licensed under Creative Commons GNU LGPL License.

	License: http://creativecommons.org/licenses/LGPL/2.1/

	Author:  Stefan Goessner/2005-2006
	Web:     http://goessner.net/ 
*/
var Slideshow = {
   version: 1.0,
   // == user customisable ===
   clickables: { a: true, button: true, input: true, object: true, textarea: true, select: true, option: true },
   incrementables: { blockquote: { filter: "self, parent" }, 
                     //dd: { filter: "self, parent" },
                     //dt: { filter: "self, parent" },
                     h2: { filter: "self, parent" },
                     h3: { filter: "self, parent" },
                     h4: { filter: "self, parent" },
                     h5: { filter: "self, parent" },
                     h6: { filter: "self, parent" },
                     li: { filter: "self, parent" },
                     p: { filter: "self" }, 
                     pre: { filter: "self" }, 
                     img: { filter: "self, parent" }, 
                     object: { filter: "self, parent" },
                     table: { filter: "self, parent" },
                     td: { filter: "self, parent" },
                     th: { filter: "self, parent" },
                     tr: { filter: "parent, grandparent" }
                   },
   autoincrementables: { ol: true, ul: true, dl: true },
   autoincrement: false,
   statusbar: true,
   navbuttons: { incfontbutton:   function(){Slideshow.changefontsize(+Slideshow.fontdelta);},
                 decfontbutton:   function(){Slideshow.changefontsize(-Slideshow.fontdelta);},
                 contentbutton:   function(){Slideshow.gotoslide(Slideshow.tocidx(), true, true);},
                 homebutton:      function(){Slideshow.gotoslide(1, true, true);},
                 prevslidebutton: function(){Slideshow.previous(false);},
                 previtembutton:  function(){Slideshow.previous(true);},
                 nextitembutton:  function(){Slideshow.next(true);},
                 nextslidebutton: function(){Slideshow.next(false);},
                 endbutton:       function(){Slideshow.gotoslide(Slideshow.count,true,true);} },
   fontsize: 125,  // in percent, corresponding to body.font-size in css file
   fontdelta: 5,   // increase/decrease fontsize by this value
   mousesensitive: true,
   tocidx: 0,
   tocitems: { toc: "<li><a href=\"#s{\$slideidx}\">{\$slidetitle}</a></li>",
               tocbox: "<option value=\"#s{\$slideidx}\" title=\"{\$slidetitle}\">{\$slidetitle}</option>" },
   keydown: function(evt) {
      evt = evt || window.event;
      var key = evt.keyCode || evt.which;
      if (key && !evt.ctrlKey && !evt.altKey) {
         switch (key) {
            case 33: // page up  ... previous slide
               Slideshow.previous(false); evt.cancel = !Slideshow.showall; break;
            case 37: // left arrow ... previous item
               Slideshow.previous(true); evt.cancel = !Slideshow.showall; break;
            case 32: // space bar
            case 39: // right arrow
               Slideshow.next(true); evt.cancel = !Slideshow.showall; break;
            case 13: // carriage return  ... next slide
            case 34: // page down
               Slideshow.next(false); evt.cancel = !Slideshow.showall; break;
            case 35: // end  ... last slide (not recognised by opera)
               Slideshow.gotoslide(Slideshow.count, true, true); evt.cancel = !Slideshow.showall; break;
            case 36: // home ... first slide (not recognised by opera)
               Slideshow.gotoslide(1, true, true); evt.cancel = !Slideshow.showall; break;
            case 65: // A ... show All
            case 80: // P ... Print mode
               Slideshow.toggleshowall(!Slideshow.showall); evt.cancel = true; break;
            case 67: // C ... goto contents
               Slideshow.gotoslide(Slideshow.tocidx, true, true); evt.cancel = true; break;
            case 77: // M ... toggle mouse sensitivity
               Slideshow.mousenavigation(Slideshow.mousesensitive = !Slideshow.mousesensitive); evt.cancel = true; break;
            case 83: // S ... toggle statusbar
               Slideshow.togglestatusbar(); evt.cancel = true; break;
            case 61:  // + ... increase fontsize
            case 107:
               Slideshow.changefontsize(+Slideshow.fontdelta); evt.cancel = true; break;
            case 109:  // - ... decrease fontsize
               Slideshow.changefontsize(-Slideshow.fontdelta); evt.cancel = true; break;
            default: break;
         }
         if (evt.cancel) evt.returnValue = false;
      }
      return !evt.cancel;
   },

   // == program logic ===
   count: 0,                       // # of slides ..
   curidx: 0,                      // current slide index ..
   mousedownpos: null,             // last mouse down position ..
   contentselected: false,         // indicates content selection ..
   showall: true,
   init: function() {
      Slideshow.curidx = 1;
      Slideshow.importproperties();
      Slideshow.registerslides();
      document.body.innerHTML = Slideshow.injectproperties(document.body.innerHTML);
      Slideshow.buildtocs();
      Slideshow.registeranchors();
      Slideshow.toggleshowall(false);
      Slideshow.updatestatus();
      document.body.style.fontSize = Slideshow.fontsize+"%";
      document.getElementById("s1").style.display = "block";
      document.onkeydown = Slideshow.keydown;
      Slideshow.mousenavigation(Slideshow.mousesensitive);
      Slideshow.registerbuttons();
      if (window.location.hash)
         Slideshow.gotoslide(window.location.hash.substr(2), true, true);
   },
   registerslides: function() {
      var div = document.getElementsByTagName("div");
      Slideshow.count = 0;
      for (var i in div)
         if (Slideshow.hasclass(div[i], "slide"))
            div[i].setAttribute("id", "s"+(++Slideshow.count));
   },
   registeranchors: function() {
      var a = document.getElementsByTagName("a"),
          loc = (window.location.hostname+window.location.pathname).replace(/\\/g, "/");
      for (var i in a) {
         if (a[i].href && a[i].href.indexOf(loc) >= 0 && a[i].href.lastIndexOf("#") >= 0) {
            a[i].href = "javascript:Slideshow.gotoslide(" + a[i].href.substr(a[i].href.lastIndexOf("#")+2)+",true,true)";
         }
      }
   },
   registerbuttons: function() {
      var button;
      for (var b in Slideshow.navbuttons)
         if (button = document.getElementById(b))
            button.onclick = Slideshow.navbuttons[b];
   },
   importproperties: function() {  // from html meta section ..
      var meta = document.getElementsByTagName("meta"), elem;
      for (var i in meta)
         if (meta[i].attributes && meta[i].attributes["name"] && meta[i].attributes["name"].value in Slideshow)
            switch (typeof(Slideshow[meta[i].attributes["name"].value])) {
               case "number": Slideshow[meta[i].attributes["name"].value] = parseInt(meta[i].attributes["content"].value); break;
               case "boolean": Slideshow[meta[i].attributes["name"].value] = meta[i].attributes["content"].value == "true" ? true : false; break;
               default: Slideshow[meta[i].attributes["name"].value] = meta[i].attributes["content"].value; break;
            }
   },
   injectproperties: function(str) {
      var meta = document.getElementsByTagName("meta"), elem;
      for (var i in meta) {
         if (meta[i].attributes && meta[i].attributes["name"])
            str = str.replace(new RegExp("{\\$"+meta[i].attributes["name"].value+"}","g"), meta[i].attributes["content"].value);
      }
      return str = str.replace(/{\$generator}/g, "ActivePresenter HTML Slideshow")
                      .replace(/{\$version}/g, Slideshow.version)
                      .replace(/{\$title}/g, document.title)
                      .replace(/{\$slidecount}/g, Slideshow.count);
   },
   buildtocs: function() {
      var toc = document.getElementById("toc"), list = "",
          tocbox = document.getElementById("tocbox");
      if (toc) {
         for (var i=0; i<Slideshow.count; i++)
            list += Slideshow.tocitems.toc.replace(/{\$slideidx}/g, i+1).replace(/{\$slidetitle}/, document.getElementById("s"+(i+1)).getElementsByTagName("h1")[0].innerHTML);
         toc.innerHTML = list;
         while (toc && !Slideshow.hasclass(toc, "slide")) toc = toc.parentNode;
         if (toc) Slideshow.tocidx = toc.getAttribute("id").substr(1);
      }
      if (tocbox) {
         tocbox.innerHTML = "";
         for (var i=0; i<Slideshow.count; i++)
            tocbox.options[tocbox.length] = new Option((i+1)+". "+document.getElementById("s"+(i+1)).getElementsByTagName("h1")[0].innerHTML, "#s"+(i+1));
         tocbox.onchange = function() { Slideshow.gotoslide(this.selectedIndex+1, true, true); };
      }
   },
   next: function(deep) {
      if (!Slideshow.showall) {
         var slide = document.getElementById("s"+Slideshow.curidx),
             item = Slideshow.firstitem(slide, Slideshow.isitemhidden);
         if (deep) {  // next item
            if (item)
               Slideshow.displayitem(item, true);
            else
               Slideshow.gotoslide(Slideshow.curidx+1, false, false);
         }
         else if (item)  // complete slide ..
            while (item = Slideshow.firstitem(slide, Slideshow.isitemhidden))
               Slideshow.displayitem(item, true);
         else           // next slide
            Slideshow.gotoslide(Slideshow.curidx+1, true, false);
         Slideshow.updatestatus();
      }
   },
   previous: function(deep) {
      if (!Slideshow.showall) {
         var slide = document.getElementById("s"+Slideshow.curidx);
         if (deep) {
            var item = Slideshow.lastitem(slide, Slideshow.isitemvisible);
            if (item)
               Slideshow.displayitem(item, false);
            else
               Slideshow.gotoslide(Slideshow.curidx-1, true, false);
         }
         else
            Slideshow.gotoslide(Slideshow.curidx-1, true, false);
         Slideshow.updatestatus();
      }
   },
   gotoslide: function(i, showitems, updatestatus) {
      if (!Slideshow.showall && i > 0 && i <= Slideshow.count && i != Slideshow.curidx) {
         document.getElementById("s"+Slideshow.curidx).style.display = "none";
         var slide = document.getElementById("s"+(Slideshow.curidx=i)), item;
         while (item = Slideshow.firstitem(slide, showitems ? Slideshow.isitemhidden : Slideshow.isitemvisible))
            Slideshow.displayitem(item, showitems);
         slide.style.display = "block";
         if (updatestatus)
            Slideshow.updatestatus();
      }
   },
   firstitem: function(root, filter) {
      var found = filter(root);
      for (var node=root.firstChild; node!=null && !found; node = node.nextSibling)
         found = Slideshow.firstitem(node, filter);
      return found;
   },
   lastitem: function(root, filter) {
      var found = null;
      for (var node=root.lastChild; node!=null && !found; node = node.previousSibling)
         found = Slideshow.lastitem(node, filter);
      return found || filter(root);
   },
   isitem: function(node, visible) {
      var nodename;
      return node && node.nodeType == 1   // elements only ..
          && (nodename=node.nodeName.toLowerCase()) in Slideshow.incrementables
          && (   Slideshow.incrementables[nodename].filter.match("\\bself\\b") && (Slideshow.hasclass(node, "incremental") || (Slideshow.autoincrement && nodename in Slideshow.autoincrementables))
              || Slideshow.incrementables[nodename].filter.match("\\bparent\\b") && (Slideshow.hasclass(node.parentNode, "incremental") || (Slideshow.autoincrement && node.parentNode.nodeName.toLowerCase() in Slideshow.autoincrementables))
              || Slideshow.incrementables[nodename].filter.match("\\bgrandparent\\b") && (Slideshow.hasclass(node.parentNode.parentNode, "incremental") || (Slideshow.autoincrement && node.parentNode.parentNode.nodeName.toLowerCase() in Slideshow.autoincrementables))
             )
          && (visible ? (node.style.visibility != "hidden")
                      : (node.style.visibility == "hidden"))
          ? node : null;
   },
   isitemvisible: function(node) { return Slideshow.isitem(node, true); },
   isitemhidden: function(node) { return Slideshow.isitem(node, false); },
   displayitem: function(item, show) {
      if (item) item.style.visibility = (show ? "visible" : "hidden");
   },
   updatestatus: function() {
      if (Slideshow.statusbar) {
         var eos = document.getElementById("eos"), 
             idx = document.getElementById("slideidx"),
             tocbox = document.getElementById("tocbox");
         if (eos) 
            eos.style.visibility = Slideshow.firstitem(document.getElementById("s"+Slideshow.curidx), Slideshow.isitemhidden) != null
                                 ? "visible" : "hidden";
         if (idx) 
            idx.innerHTML = Slideshow.curidx;
         if (tocbox)
            tocbox.selectedIndex = Slideshow.curidx-1;
      }
   },
   changefontsize: function(delta) {
      document.body.style.fontSize = (Slideshow.fontsize+=delta)+"%";
   },
   togglestatusbar: function() {
      document.getElementById("statusbar").style.display = (Slideshow.statusbar = !Slideshow.statusbar) ? "block" : "none";
   },
   toggleshowall: function(showall) {
      var slide, item;
      for (var i=0; i<Slideshow.count; i++) {
         slide = document.getElementById("s"+(i+1));
         slide.style.display = showall ? "block" : "none";
         while (item = Slideshow.firstitem(slide, showall ? Slideshow.isitemhidden : Slideshow.isitemvisible)) 
            Slideshow.displayitem(item, showall);
         var divs = slide.getElementsByTagName("div");
         for (var j in divs)
            if (Slideshow.hasclass(divs[j], "handout"))
               divs[j].style.display = showall ? "block" : "none";
      }
      if (!showall)
         document.getElementById("s"+Slideshow.curidx).style.display = "block";
      if (Slideshow.statusbar) 
         document.getElementById("statusbar").style.display = showall ? "none" : "block";
      Slideshow.showall = showall;
   },
   hasclass: function(elem, classname) {
      var classattr = null;
      return (classattr=(elem.attributes && elem.attributes["class"])) 
          && classattr.nodeValue.match("\\b"+classname+"\\b");
   },
   selectedcontent: function() {
      return window.getSelection ? window.getSelection().toString() 
                                 : document.getSelection ? document.getSelection() 
                                                         : document.selection ? document.selection.createRange().text
                                                                              : "";
   },
   mousenavigation: function(on) {
      if (on) {
         document.onmousedown = Slideshow.mousedown;
         document.onmouseup = Slideshow.mouseup;
      }
      else
         document.onmousedown = document.onmouseup = null;
   },
   mousepos: function(e) {
      return e.pageX ? {x: e.pageX, y: e.pageY} 
                     : {x: e.x+document.body.scrollLeft, y: e.y+document.body.scrollTop};
   },
   mousedown: function(evt) {
      evt = evt||window.event;
      Slideshow.mousedownpos = Slideshow.mousepos(evt);
      Slideshow.contentselected = !!Slideshow.selectedcontent() || ((evt.target || evt.srcElement).nodeName.toLowerCase() in Slideshow.clickables);
      return true;
   },
   mouseup: function(evt) {
      evt = evt||window.event;
      var pos = Slideshow.mousepos(evt);
      if (pos.x == Slideshow.mousedownpos.x && pos.y == Slideshow.mousedownpos.y && !Slideshow.contentselected) {
         Slideshow.next(true);
         return evt.returnValue = !(evt.cancel = true);
      }
      return false;
   }
};
window.onload = Slideshow.init;
