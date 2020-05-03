
$(function () {

 
    var loadedLangs = {};

    function setLoadedLang(lang) {
        $(".lang").each(function (index, element) {
            $(this).text(loadedLangs[lang][$(this).attr("key")]);
        });
        $(".lang-placeholder").each(function (index, element) {
            $(this).attr('placeholder', loadedLangs[lang][$(this).attr("key")]);
        });
        $(".lang-value").each(function (index, element) {
            $(this).attr('value', loadedLangs[lang][$(this).attr("key")]);
        });
    }

    function setLang(lang) {
        if (loadedLangs[lang] !== undefined) {
            setLoadedLang(lang);
        } else {
            $.getJSON("languages/" + lang + ".json", function (data) {
                loadedLangs[lang] = data;
                setLoadedLang(lang);
            });
        }
    }

    $(document).ready(function () {
        var lang = localStorage.getItem('lang');
        if (lang === null) lang = 'en';
        setLang(lang);
        $("#" + lang).attr("class", "translate-active");
    });

    // get/set the selected language
    $(".translate").click(function () {
        var lang = $(this).attr("id");
        localStorage.setItem('lang', lang);
        setLang(lang);
        $(".translate-active").attr("class", "translate");
        $(this).attr("class", "translate-active");
    });

});