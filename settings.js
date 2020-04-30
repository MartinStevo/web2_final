

$(document).ready(function () {

    var setBut = $("#setBut");
    var statisticsBut = $("#statsBut");
    var allStats = $(".stats");

    var prompstathistorylog = $("#login-history");
    var showloginhistory = $("#showloginhistory");
    var promptgloballogin = $("#global-login");
    var showgloballogin = $("#show-global-login")
    var settingsSel = $("#settings");

    var showpromptpasswd = $("#showpromptpasswd");
    var showpasswdchange = $("#passwd-change");

    var showpromptemail = $("#showemailchange");
    var showemailchange = $("#email-change");

    var showpromptkey = $("#showkey");
    var showapikey = $("#api-key");

    settingsSel.hide();
    showpasswdchange.hide();
    showemailchange.hide();
    showapikey.hide();
    showloginhistory.hide();
    showgloballogin.hide();
    prompstathistorylog.hide();
    promptgloballogin.hide();

    setBut.click(function () {
        settingsSel.show();
        allStats.hide();
        showpromptpasswd.click(function () {
            showpasswdchange.show();
            showemailchange.hide();
            showapikey.hide();

        });
        showpromptemail.click(function () {
            showpasswdchange.hide();
            showemailchange.show();
            showapikey.hide();

        });
        showpromptkey.click(function () {
            showpasswdchange.hide();
            showemailchange.hide();
            showapikey.show();

        });

    });

    statisticsBut.click(function () {
        settingsSel.hide();
        prompstathistorylog.show();
        promptgloballogin.show();
        prompstathistorylog.click(function(){
            showloginhistory.show();
            showgloballogin.hide();
        });
        promptgloballogin.click(function(){
            showloginhistory.hide();
            showgloballogin.show();
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