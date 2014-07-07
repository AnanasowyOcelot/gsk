jQuery(document).ready(function () {
    $(".dataInput").datepicker({ dateFormat: 'yy-mm-dd', dayNamesMin: ['Nd', 'Pn', 'Wt', 'Śr', 'Cz', 'Pt', 'So'], monthNames: ['Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec', 'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'] });
});

//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
function zaznaczZdjecie(id_boxu, el) {
    if ($(el).attr('checked')) {
        $("#" + id_boxu).css("background-color", "red");
    }
    else {
        $("#" + id_boxu).css("background-color", "#EFEFEF");
    }
}

//xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
function f_przelacz(el) {
    if ($("#" + el).is(":visible")) {
        $("#" + el).hide("highlight", {}, 300);
    }
    else {
        $("#" + el).show("highlight", {}, 300);
    }
}

//==============================================================
function f_ukryj_konsole() {
    $("#konsola").toggle();
}

//==============================================================
function selectAllRow(id, name) {
    $("INPUT[@class=" + name + "][type='checkbox']").attr('checked', $('#' + id).is(':checked'));
}

//==============================================================
function sortujKolumne(modul, kolumna, typ, parametry) {
    var link = "/cms/" + modul + "/index/col:" + kolumna + ",typ:" + typ;
    if (parametry != "") {
        link += "," + parametry;
    }
    window.location = link;
}

//==============================================================
function szukaj() {
    var parametry = '';
    var modul = $('#s_modul').val();
    var a_parametry = [];

    $('#formSzukaj input, #formSzukaj select').each(function () {
        var field = $(this);
        if (field.attr("attr_in") == "1") {
            if (field.val() != "") {
                var tmp = field.attr("attr_s_name") + ":" + field.val();
                a_parametry.push(tmp);
            }
        }
    });

    //if (a_parametry.length > 0) {
        var link = "/cms/" + modul + "/index/" + a_parametry.join(",");
        window.location = link;
    //}
}
