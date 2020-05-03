$(function () {
    var ldapBtn = $("#ldap");
    var classicalBtn = $("#classical");
    var ldapForm = $("#ldap_form");
    var classicalForm = $("#classical_form");
    var passwdForm = $(".forget_passwd");
    ldapForm.hide();
    classicalForm.show();
    passwdForm.show();


    ldapBtn.click(function () {
        ldapForm.show();
        classicalForm.hide();
        ldapBtn.attr("class", "active");
        passwdForm.hide();
        classicalBtn.attr("class", "inactive underlineHover");
    });
    classicalBtn.click(function () {
        ldapForm.hide();
        classicalForm.show();
        passwdForm.show();

        ldapBtn.attr("class", "inactive underlineHover");
        classicalBtn.attr("class", "active");
    });
});