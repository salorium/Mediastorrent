/**
 * Created by salorium on 15/03/14.
 */
var Base = new Object();
Base.model =  {
    conf : {
        base_url : 'mediastorrent',
        ssl : false,
        containerHeight : function(){
            return $(".container").height();
        }
    },
    redirection : {
        "compteur" : null,
        "timer" : null
    },

    converter : {
        paramUrl:function(val){
            return val.replace(/\//gi,"\\");
        },
        iv: function(val){
            var v = (val==null) ? 0 : parseInt(val + "");
            return(isNaN(v) ? null : v);
        },
        round: function(num, p)
        {
            var v = Math.floor(num * Math.pow(10, p)) / Math.pow(10, p);
            var s = v + "";
            var d = s.indexOf(".");
            var n = 0;
            if(d >- 1)
            {
                var ind = s.length - d;
                p++;
                if(ind < p)
                    n = p - ind;
            }
            else
            {
                if(p > 0)
                {
                    n = p;
                    s = s + ".";
                }
            }
            for(var i = 0; i < n; i++)
                s += "0";
            return(s);
        },
        time: function(tm,noRound)
        {
            if((noRound==null) && (tm >= 2419200))
                return "\u221e";
//		var val = tm % (604800 * 52);
            var val = tm;
            var w = this.iv(val / 604800);
            val = val % 604800;
            var d = this.iv(val / 86400);
            val = val % 86400;
            var h = this.iv(val / 3600);
            val = val % 3600;
            var m = this.iv(val / 60);
            val = this.iv(val % 60);
            var v = 0;
            var ret = "";
            if(w > 0)
            {
                ret = w + "sem ";
                v++;
            }
            if(d > 0)
            {
                ret += d + "j ";
                v++;
            }
            if((h > 0) && (v < 2))
            {
                ret += h + "h ";
                v++;
            }
            if((m > 0) && (v < 2))
            {
                ret += m + "min ";
                v++;
            }
            if(v < 2)
                ret += val + "s ";
            return( ret.substring(0,ret.length-1) );
        },
        bytes: function(bt, p)
        {
            p = (p == null) ? 1 : p;
            var a = new Array("o", "Ko", "Mo", "Go", "To", "Po");
            var ndx = 0;
            if(bt == 0)
                ndx = 1;
            else
            {
                if(bt < 1024)
                {
                    bt /= 1024;
                    ndx = 1;
                }
                else
                {
                    while(bt >= 1024)
                    {
                        bt /= 1024;
                        ndx++;
                    }
                }
            }
            return(this.round(bt, p) + " " + a[ndx]);
        },
        speed: function(bt)
        {
            return((bt>0) ? this.bytes(bt)+ "/" + "s" : "");
        },
        date: function(dt,timeOnly)
        {
            if(dt>3600*24*365)
            {
                var today = new Date();
                today.setTime(dt*1000);
                var month = today.getMonth()+1;
                month = (month < 10) ? ("0" + month) : month;
                var day = today.getDate();
                day = (day < 10) ? ("0" + day) : day;
                var h = today.getHours();
                var m = today.getMinutes();
                var s = today.getSeconds();
                var am = "";

                if(this.iv(0))
                {
                    if(h>12)
                    {
                        h = h-12;
                        am = " PM";
                    }
                    else
                        am = " AM";
                }
                h = (h < 10) ? ("0" + h) : h;
                m = (m < 10) ? ("0" + m) : m;
                s = (s < 10) ? ("0" + s) : s;
                var tm = h+":"+m+":"+s+am;
                var dt = '';
                if(!timeOnly)
                {
                    switch(this.iv(0))
                    {
                        case 1:
                        {
                            dt = today.getFullYear()+"-"+month+"-"+day+" ";
                            break;
                        }
                        case 2:
                        {
                            dt = month+"/"+day+"/"+today.getFullYear()+" ";
                            break;
                        }
                        default:
                        {
                            dt = day+"/"+month+"/"+today.getFullYear()+" ";
                            break;
                        }
                    }
                }
                return(dt+tm);
            }
            return('');
        }
    },
    html : {
        hauteur : function(element){
            return $(element).height() + parseInt( $(element).css("margin-top")) + parseInt( $(element).css("margin-bottom"))+parseInt( $(element).css("border-top-width"))+parseInt( $(element).css("border-bottom-width")) + parseInt($(element).css("padding-top")) +parseInt($(element).css("padding-bottom"));
        },
        hauteurInterieur: function(element){
            return $(element).height() - parseInt( $(element).css("border-top-width")) - parseInt( $(element).css("border-bottom-width")) - parseInt($(element).css("padding-top")) -parseInt($(element).css("padding-bottom"));
        }
    },

    utilisateur : {
        login : null,
        keyconnexion:null
    },
    pannelClicDroit: {
        make: function(lines,x,y){
            //$div1 = $("<div></div>");
            //$div = $("<div></div>").css({"position":"absolute","top":y-1+"px","left":x-1+"px","z-index": 100,"padding":"10px","border" :"black 1px","background": "white"});
           // $div1 = $('<ul style="position: absolute; left: '+(x-1)+'px; top: '+(y-1)+'px; z-index: 2030; display: block; overflow: visible;" class="CMenu"><li class="menuitem"><a class="exp">Priorité</a><ul class="CMenu" style=""><li class="menuitem"><a class="menu-cmd" href="javascript://void();">Haute</a></li><li class="menuitem"><a class="menu-cmd dis">Moyenne</a></li><li class="menuitem"><hr class="menu-line"></li><li class="menuitem"><a class="menu-cmd" href="javascript://void();">Ne pas télécharger</a></li></ul></li><li class="menuitem"><a class="exp">Stratégie de téléchargement</a><ul class="CMenu" style=""><li class="menuitem"><a class="menu-cmd" href="javascript://void();">Normal</a></li><li class="menuitem"><hr class="menu-line"></li><li class="menuitem"><a class="menu-cmd dis">Commencer par le début</a></li><li class="menuitem"><a class="menu-cmd" href="javascript://void();">Commencer par la fin</a></li></ul></li><li class="menuitem"><a class="exp">Voir</a><ul class="CMenu" style=""><li class="menuitem"><a class="menu-cmd" href="javascript://void();">En liste</a></li><li class="menuitem"><a class="menu-cmd dis">En arbre</a></li></ul></li><li class="menuitem"><hr class="menu-line"></li><li class="menuitem"><a class="menu-cmd" href="javascript://void();">Télécharger le fichier</a></li><li class="menuitem"><a class="menu-cmd dis">Décompression...</a></li><li class="menuitem"><a class="menu-cmd" href="javascript://void();">Media info</a></li><li class="menuitem"><a class="menu-cmd dis">Screenshots</a></li></ul>');
           // $div1.append($div);
            var $div1 = $("<ul></ul>").css({position: "absolute", left: (x-1)+'px', top: (y-1)+'px', "z-index" : "2030", display: "block", overflow: "visible"}).addClass("CMenu");
            $.each(lines, function(k,v){
                $li = $("<li class='menuitem'></li>");
                $a = $("<a class='"+( v.dest instanceof Array ? "exp'":"'")+">"+ v.nom+"</a>");
                $li.append($a);
                if (v.dest instanceof Array){
                    $ul = $("<ul class='CMenu'></ul>");
                    $.each(v.dest, function(kk,vv){
                        $lii = $("<li class='menuitem'></li>" );
                        $aa = $("<a>"+vv.nom+"</a>");
                        $lii.append($aa);
                        $aa.click ( function(eee){
                            eee.preventDefault();
                            $div1.remove();
                            vv.dest();
                        });
                        $ul.append($lii);
                    });
                    $li.append($ul);
                }else{
                    $a.click ( function(ee){
                        ee.preventDefault();
                        $div1.remove();
                        v.dest();
                    });
                }
                $div1.append($li);
            });
            $div1.appendTo("body");
            $div1.show();
            $div1.mouseleave(
                function(e){
                    e.preventDefault();
                    $(e.currentTarget).remove();
                }
            );

        }
    },
    tableau : {
        compareObjet : function (o1, o2, sortColumn, sortOrder){
            return sortOrder > 0 ? (typeof o1[sortColumn] == "string" ? o1[sortColumn].toLowerCase():o1[sortColumn])< (typeof o2[sortColumn] == "string" ? o2[sortColumn].toLowerCase():o2[sortColumn]) : (typeof o1[sortColumn] == "string" ? o1[sortColumn].toLowerCase():o1[sortColumn]) > (typeof o2[sortColumn] == "string" ? o2[sortColumn].toLowerCase():o2[sortColumn]);
        },
        fusion: function (t, tmp, de1, vers1, de2, vers2, count, posInB, sortColumn, sortOrder){
            for(var i = 0 ; i < count ; i++)
            {
                if(de2 > vers2)
                    tmp[posInB++] = t[de1++];
                else if(de1 > vers1)
                    tmp[posInB++] = t[de2++];
                else if(this.compareObjet(t[de1], t[de2], sortColumn, sortOrder))
                    tmp[posInB++] = t[de1++];
                else
                    tmp[posInB++] = t[de2++];
            }
            return tmp;
        },
        triFusion : function (t, sortColumn, sortOrder){
            var tmp = [];
            var sortLength = 1, de1, de2, de3, i;
            while(sortLength < t.length)
            {
                de1 = 0;
                while(de1 < t.length)
                {
                    de2 = de1 + sortLength;
                    de3 = de2 + sortLength;
                    if(de2>t.length) de2 = t.length;
                    if(de3>t.length) de3 = t.length;
                    tmp = this.fusion(t, tmp, de1, de2-1, de2, de3-1, de3-de1, de1, sortColumn, sortOrder);
                    de1 = de3;
                }

                for(i = 0 ; i < t.length ; i++)
                    t[i] = tmp[i];

                sortLength *= 2;
            }
            return t;
        }
    },
    path :{
        basename : function(path){
            var res = path.split("/");
            return res[res.length-1];
        },
        ext : function(path){
            var res = path.split(".");
            return res[res.length-1];
        }
    }

};