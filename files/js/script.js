"use strict";

$(document).ready(function() {

    var modules = $(".content").attr("dt-modules"), dom = {page: $("body")};

    /**************************************/
    /*************** MODAL ****************/
    /**************************************/

    if(modules.indexOf("#modal#") >= 0) {

        dom.msg = $("footer span");
        dom.win = $("#modal");

        var showModal = function(msg, btn, callback) {
            var modal_body = dom.win.find("div.modal-body"),
                modal_footer = dom.win.find("div.modal-footer"),
                labels = modal_footer.attr("dt-btn").split(","),
                buttons = {
                    "dismiss": ("<button type='button' class='btn btn-danger' data-dismiss='modal'>" + labels[0] + "</button>"),
                    "continue": ("<button type='button' class='btn btn-warning' id='modal-button-continue'>" + labels[1] + "</button>")
                },
                trigger = '',
                callback = callback || 0;
            modal_body.html(msg);
            modal_footer.html("");
            $.each(btn, function(i) {$(buttons[btn[i]]).appendTo(modal_footer);});
            if(typeof callback === "function") {
                trigger = $("#modal-button-continue");
                trigger.on("click", function() {callback();});
            }
            dom.win.modal("show");
        },
        getMsg = function(action) {
            var txt = dom.msg.filter(function() {return $(this).attr("dt-action") === action;});
            return txt.text();
        };

        if(dom.msg.eq(0).text() !== "") showModal(dom.msg.text(), ["dismiss"]);

    }

    /*************************************/
    /*************** AJAX ****************/
    /*************************************/

    if(modules.indexOf("#ajax#") >= 0) {

        var callAjax = function(target, serie, beforeCall, afterCall) {
            return $.ajax({
                beforeSend: function() {if(typeof beforeCall === 'function') beforeCall();},
                type: "POST",
                dataType: "json",
                url: target,
                data: serie
            }).done(function(res) {if(typeof afterCall === 'function') afterCall(res);});
        }, systemBusy = false, ajaxCompleted = false;

    }

    /*************************************/
    /*************** SOCIAL **************/
    /*************************************/

    if(modules.indexOf("#social#") >= 0) {

        dom.social = $("a[role='share']");

        dom.social.on("click", function(e) {
            var title = $("title"),
                url = "http://www.allcrossword.com/Apps/Characters/index.php",
                dir = [
                    "https://www.facebook.com/sharer/sharer.php?u=" + window.location.href + "&t=" + title.text() + "&v=3",
                    "https://plus.google.com/share?url=" + window.location.href,
                    "https://twitter.com/intent/tweet?status=" + title.text() + "%20" + window.location.href + "&related=micropat"
                ],
                win = $(window),
                w = Number($(this).attr("parm-w")),
                h = Number($(this).attr("parm-h")),
                l = Math.round((win.width() - w) / 2),
                t = Math.round((win.height() - h) / 2);
            e.preventDefault();
            (l < 0 || t < 0) ? window.open(dir[dom.social.index($(this))], "_blank") :
                window.open(dir[dom.social.index($(this))], "_blank", "width= " + w + ", height= " + h + ", left=" + l + ", top=" + t);
            callAjax("files/php/controllers/share.php", {"file": $(this).attr("dt-target")}, "", function(res) {console.log(res);});
        });

    }

    /***************************************/
    /*************** TABS ******************/
    /***************************************/

    if(modules.indexOf("#tabs#") >= 0) {

        dom.menuItems = $("header.submenu a");

        dom.menuItems.on("click", function(e) {
            var button = $("header.submenu span.btn-label"), tab = $("div.tab");
            button.text($(this).text());
            tab.each(function() {$(this).removeClass("hidden").addClass("hidden");});
            tab.eq(dom.menuItems.index($(this))).removeClass("hidden");
            e.preventDefault();
        });

        dom.menuItems.eq(dom.menuItems.eq(0).closest("ul").attr("dt-pointer")).trigger("click");

    }

    /****************************************/
    /*************** NAV ********************/
    /****************************************/

    if(modules.indexOf("#nav#") >= 0) {

        dom.goTop = $(".btn-gotop");

        dom.goTop.on("click", function() {dom.page.animate({scrollTop: 0}, "fast");});

    }

    /***************************************/
    /*************** OPTIONS ***************/
    /***************************************/

    if(modules.indexOf("#options#") >= 0) {

        dom.checkbutton = $(".checkbutton");

        dom.checkbutton.on("click", function() {
            var role = $(this).attr("role"),
                holder = $(this).closest(".checkgroup"),
                buttons = [holder.find("[role='main']"), holder.find("[role='control']"), holder.find("[role='slave']")],
                aux;
            if((role !== "main" && !buttons[0].hasClass("text-warning")) || holder.hasClass("dismiss")) buttons[0].addClass("text-warning");
            if(role === "main" || holder.hasClass("dismiss")) {
                holder.toggleClass("dismiss");
                if(holder.hasClass("dismiss")) $.each(buttons, function(i) {buttons[i].each(function() {$(this).removeClass("text-warning")});});
                else if(role === "main") buttons[1].addClass("text-warning");
            }
            if(role === "control" && !$(this).hasClass("text-warning")) {
                $.each(buttons, function(i) {if(i > 0) buttons[i].each(function() {$(this).removeClass("text-warning")});});
                $(this).addClass("text-warning");
            }
            else if(role === "slave") {
                $(this).toggleClass("text-warning");
                buttons[1].removeClass("text-warning");
                aux = holder.find(".text-warning");
                if(aux.length === 1 || aux.length > buttons[2].length) {
                    buttons[2].each(function() {$(this).removeClass("text-warning");});
                    buttons[1].addClass("text-warning");
                }
            }
        });

    }

    /****************************************/
    /*************** SELECTOR ***************/
    /****************************************/

    if(modules.indexOf("#selectors#") >= 0) {

        dom.selectButton = $(".selectButton");

        dom.selectButton.on("click", function() {
            var holder = $(this).closest(".sheet"),
                checkbutton = holder.find(".checkgroup"),
                labels = holder.find("label.rangevalue"),
                buttons = holder.find(".selectButton"),
                index = buttons.index($(this)),
                ranges = $.parseJSON(dom.page.attr("dt-settings")),
                val = Number(labels.eq(index > 1).text()),
                altval = Number(labels.eq(index < 2).text());
            checkbutton.removeClass("dismiss");
            checkbutton.children().removeClass("text-warning").addClass("text-warning");
            val += (index % 2 === 0 ? 1 : -1);
            if(val < ranges[holder.attr("dt-type")][0] || val > ranges[holder.attr("dt-type")][1]) val = ranges[holder.attr("dt-type")][val > ranges[holder.attr("dt-type")]];
            if((val > altval && index < 2) || (val < altval && index > 1)) val = altval;
            labels.eq(index > 1).text(val);
        });

    }

    /*****************************************/
    /*************** FORM ********************/
    /*****************************************/

    if(modules.indexOf("#form#") >= 0) {

        dom.form = $("form");

        dom.form.on("submit", function(e) {
            var data = {}, series = $(this).find(".serie"), buttons, aux, error;
            if(series.length) {
                series.each(function(i) {
                    aux = {};
                    buttons = [$(this).find("[role='main']"), $(this).find("[role='control']"), $(this).find("[role='slave']"), $(this).find("label.rangevalue")];
                    aux["active"] = buttons[0].length ? Number(buttons[0].hasClass("text-warning")) : buttons[1].index($(this).find(".text-warning"));
                    aux["options"] = buttons[0].length ? {} : "";
                    aux["range"] = buttons[0].length ? {} : "";
                    aux["counter"] = 0;
                    if(buttons[2].length) {
                        buttons[2].each(function(j) {
                            if(buttons[1].hasClass("text-warning") || $(this).hasClass("text-warning")) aux["options"][aux["counter"]++] = $(this).attr("value");
                        });
                    }
                    else if(buttons[3].length) buttons[3].each(function(j) {aux["range"][j] = Number($(this).text());});
                    data[i] = buttons[0].length ? JSON.stringify(aux) : JSON.stringify(aux["active"]);
                    if(aux["active"] < 0) error = true;
                });
                if(error) {
                    showModal(getMsg("survey"), ["dismiss"]);
                    e.preventDefault();
                }
                else {
                    aux = $("input[name='data']");
                    aux.val(JSON.stringify(data));
                    dom.page.addClass("hidden");
                }
            }
            else {
                aux = $.trim($("textarea").val());
                if(aux.length && !systemBusy) {
                    if(ajaxCompleted) dom.form.eq(0).submit();
                    else {
                        e.preventDefault();
                        systemBusy = true;
                        aux = $(".kaptcha");
                        buttons = $("input[name='kaptcha']");
                        callAjax("files/php/controllers/kaptcha.php", {"k": aux.attr("dt-ref"), "v": buttons.val()}, "", function(res) {
                            if(res) {
                                ajaxCompleted = true;
                                dom.form.eq(0).submit();
                            }
                            else {
                                showModal(getMsg("no_kaptcha"), ["dismiss"]);
                                systemBusy = false;
                            }
                        });
                    }
                }
                else {
                    if(!ajaxCompleted) e.preventDefault();
                    if(!systemBusy) showModal(getMsg("no_message"), ["dismiss"]);
                }
            }
        });

    }

    /***************************************/
    /*************** EDIT ******************/
    /***************************************/

    if(modules.indexOf("#edit#") >= 0) {

        var valueInspector,
            assignEdit = function() {

                dom.fields = $("[contenteditable]");
                dom.buttonEdit = $("button.btn-edit");

                dom.fields.unbind();
                dom.buttonEdit.unbind();

                dom.fields.on("keypress", function(e) {return e.which != 13;});

                dom.fields.on("focus", function() {
                    var holder = $(this).parent();
                    valueInspector = $.trim($(this).text());
                    if(holder.hasClass("text") && $(this).hasClass("text-warning")) {
                        $(this).text("");
                        $(this).removeClass("text-warning");
                    }
                });

                dom.fields.on("blur", function() {
                    var holder = $(this).parent(), content = $.trim($(this).text()), go = true;
                    if(content === "") {
                        $(this).text(holder.attr("dt-default"));
                        if(holder.hasClass("text")) $(this).addClass("text-warning");
                        else go = false;
                    }
                    if(go && valueInspector !== content) {
                        var data = JSON.parse(holder.attr("dt-data"));
                        data.value = content;
                        callAjax("files/php/controllers/update.php", data);
                    }
                });

                dom.buttonEdit.on("click", function() {
                    var elm = $(this), aux = [], cb;
                    aux["who"] = elm.closest(".sheet").find("label").text();
                    switch($(this).attr("dt-action")) {
                        case "add":
                            aux["data"] = JSON.parse(elm.attr("dt-data"));
                            aux["parent"] = elm.closest(".box").find(".sheets");
                            aux["attr"] = aux["parent"].find(".sheet");
                            aux["elm"] = $("<div class='sheet' style='display:none'><div class='header' dt-default='" + aux["data"].attribute + "'><label class='txt' contenteditable>" + aux["data"].attribute + "</label><span class='bar'></span></div><div class='text'><span class='txt text-warning' contentEditable></span><span class='bar'></span></div><p class='actions'><button type='button' dt-action='delete_subsection' class='btn btn-danger btn-edit'>x</button></p></div>");
                            aux["elm"].appendTo(aux["parent"]).slideDown("slow");
                            callAjax("files/php/controllers/add.php", aux["data"], "", function(res) {
                                var aux = [];
                                aux["holder"] = $(".sheets[dt-index='" + res.index + "']");
                                aux["header"] = aux["holder"].find("div.header:last");
                                aux["text"] = aux["holder"].find("div.text:last");
                                aux["button"] = aux["holder"].find("button.btn-edit:last");
                                aux["header"].attr("dt-data", "{\"index\":\"" + res.index + "\",\"subindex\":\"" + res.subindex + "\",\"which\":\"attribute\"}");
                                aux["text"].attr("dt-data", "{\"index\":\"" + res.index + "\",\"subindex\":\"" + res.subindex + "\",\"which\":\"value\"}").attr("dt-default", res.labels.default).find("span.txt").text(res.labels.default);
                                aux["button"].attr("title", res.labels.title).attr("dt-data", "{\"index\":\"" + res.index + "\",\"subindex\":\"" + res.subindex + "\"}");
                                assignEdit();
                            });
                            return;
                        case "delete_section":
                        case "delete_subsection":
                            cb = function() {
                                var aux = elm.closest(elm.attr("dt-action") === "delete_section" ? ".box" : ".sheet");
                                callAjax("files/php/controllers/delete.php", JSON.parse(elm.attr("dt-data")));
                                dom.win.modal("hide");
                                aux.slideUp("slow", function() {aux.remove();});
                            };
                            break;
                        case "refill":
                            cb = function() {
                                var data = JSON.parse(elm.attr("dt-data")), aux = elm.closest(".sheet").find("span.txt");
                                data.refill = true;
                                data.value = aux.text().replace(aux.parent().attr("dt-default"), "");
                                callAjax("files/php/controllers/update.php", data, function() {
                                    var modal_body = dom.win.find("div.modal-body"), modal_footer = dom.win.find("div.modal-footer");
                                    modal_body.html("<img src='files/assets/loading.gif' />");
                                    modal_footer.text("");
                                }, function(res) {
                                    var aux = elm.closest(".sheet").find("span.txt");
                                    if(aux.hasClass("text-warning")) aux.removeClass("text-warning").text("");
                                    aux.text(res);
                                    dom.win.modal("hide");
                                });
                            };
                            break;
                    }
                    showModal(
                        getMsg(
                            $(this).attr("dt-action")) + (
                                aux["who"] === undefined ? "" : "<br/><b>" + aux["who"] + "</b>"
                            ) + ($(this).attr("dt-extra") === undefined ? "" : "<br/><small>" + $(this).attr("dt-extra") + "</small>"
                        ),
                        typeof cb === "function" ? ["continue", "dismiss"] : ["dismiss"],
                        cb
                    );
                });

            };

        assignEdit();

    }

    /****************************************/
    /*************** ACTIONS ****************/
    /****************************************/

    if(modules.indexOf("#actions#") >= 0) {

        dom.actionButtons = $("button[role='command']");

        dom.actionButtons.on("click", function() {
            var elm = $(this), cb;
            switch($(this).attr("dt-action")) {
                case "reset":
                case "recover_sections":
                case "recover_subsections":
                    cb = function() {
                        callAjax("files/php/controllers/" + (elm.attr("dt-action") === "reset" ? "reset" : "recover") + ".php", JSON.parse(elm.attr("dt-data")), "", function() {window.location.reload();});
                    };
                    break;
                case "pdf":
                    window.open("pdf.php");
                    return;
                case "blank":
                    cb = function() {
                        var aux = $(".text-warning");
                        aux.each(function() {$(this).removeClass("text-warning");});
                        dom.form.submit();
                    };
                    break;
                case "full":
                    cb = function() {dom.form.submit();};
                    break;
            }
            showModal(getMsg($(this).attr("dt-action")), typeof cb === "function" ? ["continue", "dismiss"] : ["dismiss"], cb);
        });

    }

    dom.page.removeClass("hidden");
    dom.alert = $("span[dt-action='result']");
    if(dom.alert.text() !== "") showModal(dom.alert.text(), ["dismiss"]);

});