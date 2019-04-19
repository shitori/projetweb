$(document).ready(function () {
    console.log("main.js OK")
    var matiere = [
        "Français",
        "Mathématiques",
        "Anglais",
        "Espagnol",
        "Italien",
        "Histoire",
        "Géographie",
        "Éducation Civique",
        "Sciences de la Vie et de la Terre",
        "Technologie",
        "Éducation Musical",
        "Art Plastique",
        "Éducation Physique et Sportive",
        "Physique",
        "Chimie",
        "Science de l'ingénieur",
        "Philosophie",
        "Science Économique",
    ];

    var ville = [
        "Paris",
        "Marseille",
        "Lyon",
        "Toulouse",
        "Nice",
        "Nantes",
        "Montpellier",
        "Bordeaux",
        "Strasbourg",
        "Lille"
    ];

    $("#inputMat").autocomplete({
        source: matiere
    });

    $("#inputCity").autocomplete({
        source: ville
    });

    $(function () {
        $('#slider-container').slider({
            range: true,
            min: 0,
            max: 100,
            values: [0, 100],
            create: function() {
                $("#amount").val("0 € - 100 €");
            },
            slide: function (event, ui) {
                $("#amount").val( ui.values[0] +" € - " +  ui.values[1]+" €" );
                var mi = ui.values[0];
                var mx = ui.values[1];
                filterSystem(mi, mx);
            }
        })
    });

    function filterSystem(minPrice, maxPrice) {
        $(".system").parent().hide().filter(function () {
            var price = parseInt($(this).children(".system").data("price"), 10);
            return price >= minPrice && price <= maxPrice;
        }).show();
    }

    $( ".form-check-input" ).on( "click", function() {
        var refLvl = parseInt($("input:checked" ).val(), 10);
        $(".card-header").parent().hide().filter(function () {
            var level = parseInt($(this).children(".card-header").data("lvl"), 10);
            console.log(refLvl);
            return level>=refLvl;
        }).show();
    });

    $("input:checkbox").on("change", function () {
        var a = $("input:checkbox:checked").map(function () {
            return $(this).val()
        }).get()
        $("#tabela1 tr").hide();
        var cities = $(".city").filter(function () {
            var city = $(this).text(),
                index = $.inArray(city, a);
            return index >= 0
        }).parent().show();
    }).first().change();

    $(".filter-letter").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".card-title").filter(function() {

            $(this).parent().parent().toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});