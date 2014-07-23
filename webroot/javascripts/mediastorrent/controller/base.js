/**
 * Created by salorium on 15/03/14.
 */
Base.controller = {
    setHost: function (args) {
        Base.model.conf.base_url = args[0];
        Base.model.conf.ssl = args[1];
    },
    makeUrlBase: function (base) {
        if (base) {
            return "http" + (Base.model.conf.ssl ? "s" : "") + "://" + base + "/";
        } else {
            return "http" + (Base.model.conf.ssl ? "s" : "") + "://" + Base.model.conf.base_url + "/";
        }
    },
    tableScroll: function () {
        Array.prototype.clone = function () {
            var newArray = (this instanceof Array) ? [] : {};
            for (i in this) {
                if (i == 'clone') continue;
                if (this[i] && typeof this[i] == "object") {
                    newArray[i] = this[i].clone();
                } else newArray[i] = this[i]
            }
            return newArray;
        }
    },
    setUtilisateur: function (args) {
        Base.model.utilisateur.login = args[0];
        Base.model.utilisateur.keyconnexion = args[1];
        Base.model.utilisateur.role = args[2];
    },

    fixeHeightContainer: function () {
        Base.view.fixedHeightContainer();
    },
    redirection: function (url) {
        Base.model.redirection.compteur = 5;
        Base.view.showRedirection();
        Base.model.redirection.timer = setInterval(function () {
            Base.model.redirection.compteur--;
            Base.view.updateCptRedirection();
            if (Base.model.redirection.compteur == 0) {
                clearInterval(Base.model.redirection.timer);
                $(location).attr('href', url);
            }
        }, 1000);
    },
    checkerCheckbox: function (e) {
        var classs = $(e).attr("class");
        classs = "." + classs;
        console.log($(e).is(':checked'));
        if ($(e).is(':checked')) {
            //Checked

            $(classs).prop('checked', true);
        } else {
            //Non checked
            $(classs).prop('checked', false);
        }
        console.log(classs);
    }
};