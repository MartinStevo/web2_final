

$(document).ready(function () {
    var setBut = $("#setBut");
    var statisticsBut = $("#statsBut");
    var allStats = $(".stats");

    var promptqueryhist = $("#query-hist");
    var showquery= $("#showquery");
    var hidequery = $("#hidequery");
    var showqueries = $("#showuserqueries");
    var showpdfqueries = $("#pdf-queries");

    var promptpagehist = $("#page-hist");
    var showpage = $("#showpage");
    var hidepage = $("#hidepage");
    var showpagehist = $("#show-page-history");


    var prompstathistorylog = $("#login-history");
    var showloginhistory = $("#showloginhistory");
    var showpdflogin = $("#pdf-login");

    var promptgloballogin = $("#global-login");
    var showgloballogin = $("#show-global-login")

    var hideglobal = $("#hideglobal");
    var showglobal = $("#showglobal");
    var sentglobal = $("#sentglobal");

    var hidelogin = $("#hidelogin");
    var showlogin = $("#showlogin");
    var sentlogin = $("#sentlogin");

    var settingsSel = $("#settings");

    var showpromptpasswd = $("#showpromptpasswd");
    var showpasswdchange = $("#passwd-change");
    var hidepasswd = $("#hidepasswd");

    var showpromptemail = $("#showemailchange");
    var showemailchange = $("#email-change");
    var hideemail = $("#hideemail");


    var showpromptkey = $("#showkey");
    var showapikey = $("#api-key");
    var hidekey = $("#hidekey");


    hideemail.hide();
    hidekey.hide();

    settingsSel.hide();
    showpasswdchange.hide();
    showemailchange.hide();
    showapikey.hide();
    showloginhistory.hide();
    showgloballogin.hide();
    prompstathistorylog.hide();
    promptgloballogin.hide();
    hidepasswd.hide();

    hideglobal.hide();
    showglobal.hide();
    sentglobal.hide();
    allStats.hide();
    showpdflogin.hide();
    showpdfqueries.hide();

    setBut.click(function () {
        settingsSel.show();
        allStats.hide();
        showpdflogin.hide();
        showpromptpasswd.click(function () {
            showpasswdchange.show();
            showemailchange.hide();
            showapikey.hide();
            hidepasswd.show();
            showpromptpasswd.hide();
            showpromptkey.show();
            showpromptemail.show();
            hideemail.hide();
            hidekey.hide();


            hidepasswd.click(function () {
                showpasswdchange.hide();
                showpromptpasswd.show();
                hidepasswd.hide();

            })



        });
        showpromptemail.click(function () {
            showpasswdchange.hide();
            ///
            showpromptkey.show();
            showpromptpasswd.show();
            showpromptemail.hide();
            ///
            showemailchange.show();
            showapikey.hide();
            hideemail.show();
            hidepasswd.hide();
            hidekey.hide();


            hideemail.click(function () {
                showemailchange.hide();
                showpromptemail.show();
                hideemail.hide();
            })

        });
        showpromptkey.click(function () {
            showpasswdchange.hide();
            showemailchange.hide();
            showapikey.show();
            ///
            showpromptkey.hide();
            showpromptemail.show();
            showpromptpasswd.show();
            ///
            hidekey.show();
            hideemail.hide();
            hidepasswd.hide();

            hidekey.click(function () {
                hidekey.hide();
                showpromptkey.show();
                showapikey.hide();

            })

        });

    });

    statisticsBut.click(function () {
        showglobal.show();
        sentglobal.show();
        settingsSel.hide();
        showlogin.show();
        sentlogin.show();
        promptqueryhist.show();//try thus
        promptpagehist.show();//try thus
        showpage.show();
        showquery.show();
        showpdflogin.show();
        showpdfqueries.show(); //try thus

        prompstathistorylog.show();
        promptgloballogin.show();
        showlogin.click(function () {
            showlogin.hide();
            hidelogin.show();
                        showloginhistory.show();
        });
        showpage.click(function() {
            showpage.hide();
            hidepage.show();
            showpagehist.show();
        });
        hidepage.click(function() {
            showpage.show();
            hidepage.hide();
            showpagehist.hide();
        });
        showquery.click(function() {
            hidequery.show();
            showquery.hide();
            showqueries.show();
        });
        hidequery.click(function() {
            hidequery.hide();
            showquery.show();
            showqueries.hide();
        });

        hidelogin.click(function () {
            showlogin.show();
            hidelogin.hide();
            showloginhistory.hide();

        });

});
});



function copytoClipboard() {
    var copyText = document.getElementById("myKey");
    copyText.select();
    copyText.setSelectionRange(0, 99999)
    document.execCommand("copy");
    alert("Copied the text: " + copyText.value);
}
