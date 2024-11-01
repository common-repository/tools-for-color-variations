(function ($) {
    'use strict';
    var FLYverifenrtogo = true;
    var FLYingprincipencour;
    $(window).load(function () {
        FLYingprincipencour = $("#_thumbnail_id").val();
        //apply bouton
        $(".post-type-product #postimagediv").append('<div class="openvariantspicturesContent"><a  class="openvariantspictures button button-primary button-large">Colorize the product image</a></div>');
        $(".openvariantspictures").click(function () {
            if ($('#FMYmodifRGB').is(":hidden")) {
                $('.flycontentdeclipopup').hide();
                $('#my_FLY_product_data').addClass('Flybigshow');
                $('#FMYmodifRGBbackround').show();
                $('#FMYmodifRGB').show(200);
            } else {
                $('.flycontentdeclipopup').show();

                $('#my_FLY_product_data').removeClass('Flybigshow');
                $('#FMYmodifRGB').hide(200);
                $('#FMYmodifRGBbackround').hide();
            }
        });
        $(".FLYsaveArray").change(function () {
            var FLYtmpvalue = "";

            if ($(this).attr("type") == "checkbox") {

                if (this.checked) {
                    FLYtmpvalue = "checked";
                    $('.box' + $(this).attr("id")).removeClass("FLYhidden");
                } else {
                    $('.box' + $(this).attr("id")).addClass("FLYhidden");
                }

            } else {
                FLYtmpvalue = $(this).val();
            }
            $(this).attr("value", FLYtmpvalue);
            FLYsaveRGBdecli();
        });

        $("#FLYgoTOdecliGO").click(function () {
            FLYopendeclipopupi();
        });

        $("#FLYgoTOdeclimodifphotos").click(function () {
            var button = $(this),
                custom_uploader = wp.media({
                    title: 'Select picture of the variant',
                    library: {
                        type: 'image'
                    },
                    multiple: false
                }).on('select', function () { // it also has "open" and "close" events
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    $("#FLYgoTOdecliIMG").attr("src", attachment.url);
                    $("#FLYimagepostfull").val(attachment.url);
                }).on('open', function () { //

                }).open();

        });

        $("#FLYmodifgroupphotos").click(function () {
            var FLYmsgConfirm = $("#FLYLmsgconfirm").val();
            var button = $(this),
                custom_uploader = wp.media({
                    title: 'Select picture of the variant',
                    library: {
                        type: 'image'
                    },
                    multiple: false
                }).on('select', function () { // it also has "open" and "close" events
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    var FLYlistSQLVariants = "";
                    if (confirm(FLYmsgConfirm)) {
                        $('.variantspictures_decli').each(function (e) {
                            if ($(this).hasClass("selected")) {
                                if (FLYlistSQLVariants != "") {
                                    FLYlistSQLVariants += ",";
                                }
                                FLYlistSQLVariants += $(this).attr('data-id');
                                $('#img' + $(this).attr('data-id')).attr('src', attachment.url);
                            }
                        });
                        $.ajax({
                            url: ajaxurl,
                            type: "POST",
                            data: {
                                'action': 'FLYupdateVariants',
                                'action2': 'FLYupdateVariants',
                                'variable': 'thrunbail',
                                'valeur': attachment.id,
                                'type': 'char',
                                'listSQLVariants': FLYlistSQLVariants

                            }
                        }).done(function (response) {
                            console.log(response);
                        });
                    } else {

                    }
                }).on('open', function () { //

                }).open();
        });

        $(".variantspictures_decli").click(function () {
            if ($(this).hasClass("selected")) {
                $(this).removeClass("selected");
            } else {
                $(this).addClass("selected");
            }
        });

        $(".flyfiltredecli").change(function () {
            $(".variantspictures_decli").hide(40);
            flyfiltredecli();
        });
    });


    function FLYopendeclipopupi() {
        var urlimage = $("#FLYgoTOdecliIMG").attr('src');
        var FLYmesstostop = "";
        FLYverifenrtogo = true;
        if (!urlimage) {
            FLYverifenrtogo = false;
            FLYmesstostop = $("#FLYgoTOdecliIMG").attr('msgerr');
        }

        if (FLYverifenrtogo) {
            FLYpopupdecliGO();
        } else {
            alert(FLYmesstostop);
        }
    }

    function FLYpopupdecliGO() {
        var urlimage = $("#FLYimagepostfull").val();
        var infosdecli = $("#FLYListedeclitoexport").val();
        var terafly = $("#FLYterafly").val();
        var titleimaFLY = $("#titleimaFLY").val();
        openWindowWithPost({
            reloa: "true",
            urlimaFLY: urlimage,
            infosdecli: infosdecli,
            terafly: terafly,
            titleimaFLY: titleimaFLY
        });

    }

    function openWindowWithPost(data) {
        var SERVISEurl="https://decli.fr/wp-content/colorise/";
        var hfly = screen.height;
        var wfly = screen.width;
        var flyinfopopup = 'toolbar=no ,location=0,status=no,titlebar=no,menubar=no,width=' + wfly + ',height=' + hfly;
        var popupdeclifr = window.open(SERVISEurl+"wait.php", "declifr", flyinfopopup);
        setTimeout(function () {
            var form = document.createElement("form");
            form.target = "declifr";
            form.method = "POST";
            form.action = SERVISEurl;
            form.style.display = "none";
            for (var key in data) {
                var input = document.createElement("input");
                input.type = "hidden";
                input.name = key;
                input.value = data[key];
                form.appendChild(input);
            }
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);

        }, 1000);
    }

    var FLYselectedVariant = "";
    function flyfiltredecli() {
        var tmp2fly = "";
        $(".variantspictures_decli").hide(40);
        $(".variantspictures_decli").removeClass("selected");
        $('.flyfiltredecli').each(function (i) {
            var tmpfly = $(this).children("option:selected").val();
            if (tmpfly != "00") {
                tmp2fly += "." + tmpfly;
            }
        });
        FLYselectedVariant = tmp2fly;
        if (tmp2fly == "") {
            $(".variantspictures_decli").show(500);
        } else {
            $(tmp2fly).show(500);
            $(tmp2fly).addClass("selected");
        }

    }

    function FLYsaveRGBdecli() {
        var arrayflytmp = "";
        var flynopassarane = false;
        var Chaineflyselectdeclitmp = "";
        $('.FLYsaveArray').each(function (i) {
            arrayflytmp += "[id]" + $(this).attr('id') + "[val]" + $(this).attr('value');

            if ($(this).attr("type") == "checkbox") {
                if (this.checked) {
                    Chaineflyselectdeclitmp += "[at]" + $(this).attr('namedeclifly');
                    flynopassarane = true;
                } else {
                    flynopassarane = false;
                }
            } else {
                if (flynopassarane) {
                    Chaineflyselectdeclitmp += "[de]" + $(this).attr('namecolo');
                    Chaineflyselectdeclitmp += "[co]" + $(this).attr('value');
                }
            }

        });
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                'action': 'FLYupdateVariants',
                'action2': 'FLYupdateRGBfic',
                'valeur': arrayflytmp

            }
        }).done(function (response) {
            $("#FLYListedeclitoexport").val(Chaineflyselectdeclitmp);
        });

    }

})(jQuery);