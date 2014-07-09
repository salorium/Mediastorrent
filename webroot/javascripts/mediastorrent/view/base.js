/**
 * Created by salorium on 15/03/14.
 */
Base.view = {
    showRedirection: function () {
        $(".container").append("<h2>Redirection dans <span id='redi'>" + Base.model.redirection.compteur + "</span> seconde.</h2>");
    },
    updateCptRedirection: function () {
        $("#redi").html(Base.model.redirection.compteur);
    },
    fixedHeightContainer: function () {
        this.hauteurWindows = $(window).height();
        Base.view.fixedHeight(".container", this.hauteurWindows - 75);
    },
    fixedHeight: function (container, hauteur) {
        $(container).css("height", hauteur);
    },
    boxmodal: {
        del: function (id) {
            Base.model.boxmodal.allmodal[id].remove();
        },
        make: function (titre, contenu) {
            $loader = $('<div style="background-color: rgba(0,0,0,0.2); position: absolute; top: 0px;left: 0px; bottom: 0px;right: 0px;">' +
                '<div style="width: 50%;height: 50%;" class="addTorrent">' +
                '<div class="addTorrentTitle">' +
                '<a>' + titre.replace(/([A-Z]+)/g, '<span class="secondary">$1</span>') + '</a><a class="close" onclick="Base.view.boxmodal.del(' + Base.model.boxmodal.cpt + ');">&times;</a></div>' +
                '<div class="addTorrentContenu">' + contenu +
                '</div>' +
                '</div>');
            $("body").append($loader);
            Base.model.boxmodal.allmodal[Base.model.boxmodal.cpt] = $loader;
            Base.model.boxmodal.cpt++;
        }
    },
    loader: {
        recursiveHauteurParent: function (container, $breaks, h) {
            console.log(container);
            if (!$breaks.attr("id")) {
                $breaks.attr("id", "IDLOADERCATCHER");
            }
            console.log($breaks.attr("id"));
            console.log(container == $breaks);

            var i = 0;
            var childs = $(container).children(":not(script)");
            console.log(childs[0]);
            var exit = $(childs[0]).attr("id") == $breaks.attr("id");

            var nbmax = childs.length;
            while (i < nbmax && !exit) {
                console.log(Base.model.html.hauteur($(childs[i])));
                console.log(childs[i]);

                if ($(childs[i + 1]).attr("id") == $breaks.attr("id")) {
                    exit = true;
                    if ($breaks.attr('id') === "IDLOADERCATCHER")
                        $breaks.removeAttr('id');
                }
                else {
                    i++;
                }


            }
            if ($breaks.attr('id') === "IDLOADERCATCHER")
                $breaks.removeAttr('id');


        },
        make: function (container) {
            $container = $("#" + container);
            $loader = $('<div style="background-color: rgba(0,0,0,0.9); position: absolute; top: 0px;left: 0px; bottom: 0px;right: 0px;display: none"><h3 style="color: #ffffff;">Veuillez patienter</h3><span class="loader loader-circles"></span></div>');
            //console.log($container.top);
            //$loader.height($container.height());
            //$loader.width($container.width());
            /*var i = 0;
             var $parent = $container.parent();
             var childs = $container.parent().children(":not(script)");
             var exit = $(childs[0]).attr("id") == container;
             var nbmax= childs.length;
             while( i < nbmax && !exit){
             console.log(Base.model.html.hauteur($(childs[i])));
             console.log(childs[i]);
             if ($(childs[i+1]).attr("id") == container ){
             exit = true;
             }
             else{
             i++;
             }


             }
             if ( ! $("body").attr("id")){
             $("body").attr("id","BODYLOADERCATCHER");
             }
             console.log(this.recursiveHauteurParent($parent.parent(),$parent));
             */
            $("#" + container).css("position", "relative");
            $loader.css("padding", $("#" + container).css("padding"));
            $("#" + container).append($loader);
            return $loader;
            // $container.parent().append($loader);
        }
    },
    image: {
        input: function (container, name, id, url, input, height) {
            $("#" + container).append('<label for="' + id + '">' + name + ' :</label>');
            var $poster = $('<input class="large-2" type="text" name="' + id + '" id="' + id + '" value="' + url + '">');
            if (input)
                $("#" + container).append($('<div class="row"></div>').append($('<div class="large-12"></div>').append($poster)));
            var $img = $('<img height="' + height + 'px" src="' + Base.controller.makeUrlBase() + "proxy/imageSetHeight/" + Base.model.converter.paramUrl(url) + "/" + height + ".jpg" + '">');
            $("#" + container).append($img);
            if (input) {
                $poster.on("change keyup update input", function () {
                    if ($.trim($poster.val()).length > 1) {
                        $img.attr("src", Base.controller.makeUrlBase() + "proxy/imageSetHeight/" + Base.model.converter.paramUrl($poster.val()) + "/" + height + ".jpg");
                    } else {
                        $img.attr("src", Base.controller.makeUrlBase() + "proxy/imageSetHeight/non/" + height + ".jpg");
                    }
                });
                $poster.on("paste", function () {
                    setTimeout(function () {
                        $poster.change();
                    }, 1);
                });
            }
        },
        chooser: function (container, name, id, images, height, width) {
            $("#" + container).append('<label for="' + id + '">' + name + ' :</label>');
            var $poster = $('<input class="large-2" type="text" name="' + id + '" id="' + id + '" value="' + images.url[0][0] + '">');
            $("#" + container).append($('<div class="row"></div>').append($('<div class="large-12"></div>').append($poster)));
            var $img = $('<img height="' + height + 'px" src="' + Base.controller.makeUrlBase() + "proxy/imageSetHeight/" + Base.model.converter.paramUrl(images.url[0][0]) + "/" + height + ".jpg" + '">');
            var $divimg = $('<div style="width: ' + (images.url[0][1] * height / images.url[0][2]) + 'px; height:' + height + 'px; position:relative;"></div>');
            $divimg.append($img);
            var $span = $('<span style="position: absolute; top: 0;right: 0;background-color: rgba(0,0,0,0.8);color: #ffffff; padding: 5px;">' + images.url[0][1] + 'x' + images.url[0][2] + '</span>');
            $divimg.append($span);
            $("#" + container).append($divimg);

            $poster.on("change keyup update input", function () {
                if ($.trim($poster.val()).length > 1) {
                    $img.attr("src", Base.controller.makeUrlBase() + "proxy/imageSetHeight/" + Base.model.converter.paramUrl($poster.val()) + "/" + height + ".jpg");
                } else {
                    $img.attr("src", Base.controller.makeUrlBase() + "proxy/imageSetHeight/non/" + height + ".jpg");
                }
            });
            $poster.on("paste", function () {
                setTimeout(function () {
                    $poster.change();
                }, 1);
            });
            var $fieldset = $('<fieldset><legend>' + name + '</legend></fieldset>');
            var $divminiatureimage = $('<div style="height: 300px;overflow-y: auto;overflow-x: hidden;"></div>');
            for (i = 0; i < images.url.length; i++) {

                var $divcontaineminiatureimage = $('<div data-url="' + images.url[i][0] + '" data-x="' + images.url[i][1] + '" data-y="' + images.url[i][2] + '" class="' + (i == 0 ? "active" : "") + ' miniature" style="width: ' + (width) + 'px; position:relative;"></div>');
                $divcontaineminiatureimage.append('<img  width="' + width + 'px" src="' + Base.controller.makeUrlBase() + "proxy/imageSetWidth/" + Base.model.converter.paramUrl(images.url[i][0]) + "/" + width + ".jpg" + '">')
                $divcontaineminiatureimage.append('<span style="position: absolute; top: 0;right: 0;background-color: rgba(0,0,0,0.8);color: #ffffff; padding: 5px;">' + images.url[i][1] + 'x' + images.url[i][2] + '</span>');
                $divminiatureimage.append($('<div style="width: ' + (width) + 'px; height:' + (width * images.ratio + 5) + 'px; position:relative;float: left;"></div>').append($divcontaineminiatureimage));
                $divcontaineminiatureimage.on("click", function (e) {
                    $(".miniature").removeClass("active");
                    $(e.currentTarget).addClass("active");
                    $poster.val($(e.currentTarget).attr("data-url"));
                    $poster.change();
                    $divimg.width($(e.currentTarget).attr("data-x") * height / $(e.currentTarget).attr("data-y"));
                    $span.html($(e.currentTarget).attr("data-x") + "x" + $(e.currentTarget).attr("data-y"));
                });
            }
            $("#" + container).append($fieldset);
            $fieldset.append($divminiatureimage);
        }

    },
    noty: {
        generate: function (type, texte, layout) {
            var n = noty({
                text: texte,
                type: type,
                dismissQueue: true,
                layout: (layout ? layout : 'bottomRight'),
                theme: 'defaultTheme',
                timeout: 2000
            });

        },
        generateConfirm: function (texte, call_oui, call_non) {
            var n = noty({
                text: texte,
                type: 'alert',
                dismissQueue: true,
                layout: 'center',
                theme: 'defaultTheme',
                buttons: [
                    {addClass: 'btn btn-primary', text: 'Oui', onClick: function ($noty) {
                        $noty.close();
                        if (call_oui)
                            call_oui();
                    }
                    },
                    {addClass: 'btn btn-danger', text: 'Non', onClick: function ($noty) {
                        $noty.close();
                        if (call_non)
                            call_non();
                    }
                    }
                ]
            });
        }
    }
};