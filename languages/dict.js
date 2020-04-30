
localStorage.setItem('lang', $(this).attr('id'));

// load, on DOMContentLoaded
var lang = localStorage.getItem('lang') || 'en';

$(function () {
    var skBtn = $("#sk");
    var enBtn = $("#en");
 
var arrLang = {
    "en": {
        "showkey":"Show api key",
        "showemailchange":"Change email",
        "showpromptpasswd":"Change password",
        "set-menu":"Settings",
        "stats-menu":"Statistics",
        "signout-menu":"Sign out",
        "user-menu":"Profile",
        "about-menu": "About us",
        "team-menu": "Team",
        "doc-menu":"Documentation",
        "home-menu": "Home",
        "login-menu": "Sign in",
        "register-menu": "Sign up",
        "registration":"Registration",
        "ldaplogin": "Sign in with LDAP",
        "s1": "Car shock absorber",
        "s2": "Inverted pendulum",
        "s3": "Ball on a stick",
        "s4": "Náklon lietadla",
        "login_message": "Registration was succesfull,please log in.",
        "logout_message": "You signed out successfully, we are looking forward for your next visit!",
        "login_promp": "Please sign in.",
        "invalid_auth_code": "Wrong autentificator code.",
        "invalid_passwd": "Wrong password.",
        "invalid_login": "Wrong login",
        "ldap_ref": "LDAP bind failef please check your internet connection/credentials.",
        "ldap_bin_failed": "LDAP bind failed.",
        "login_exists": "Login already exists,please choose different one.",
        "login_inp": "login",
        "passwd_inp": "password",
        "auth_code_inp": "Authentificator code",
        "name_inp": "name",
        "surname_inp": "surname",
        "submit": "Submit",
        "reg_button": "Register",
        "normal_login": "Classical login",
        "ldap_login": "LDAP login",
        "h1-idx" :"We offer you modern solutions, in the world of Computer aided systems.",
        "h2-idx" : "Our personal goal is to make this world a better place",
        "h3-idx":"We are powered by:",
        "h4-idx": "Our partners:",
        "passwd_reset": "Forgot password?"

    },
    "sk": {
        "showkey":"Ukázať api key",
        "showemailchange":"Zmena emailu",
        "showpromptpasswd":"Zmena hesla",
        "set-menu":"Nastavenia",
        "stats-menu":"Štatistika",
        "signout-menu":"Odhlásiť sa",
        "user-menu":"Profil",
        "about-menu": "O nás",
        "team-menu": "Tím",
        "doc-menu":"Dokumentácia",
        "home-menu": "Domov",
        "login-menu": "Prihlásenie",
        "register-menu": "Registrácia",
        "registration":"Registration",
        "ldaplogin": "Prihlásenie cez LDAP",
        "registration": "Registrácia",
        "s1": "Tlmič automobilu",
        "s2": "Inverzné kyvadlo",
        "s3": "Gulička na tyči",
        "s4": "Náklon lietadla",
        "login_message": "Registrácia prebehla úspešne,prosím prihláste sa!",
        "logout_message": "Odhlásenie prebehlo úspešne,tešíme sa na vašu dalšiu návštevu!",
        "login_promp": "Prosím prihláste sa.",
        "invalid_auth_code": "Nesprávny autentifikačný kód.",
        "invalid_passwd": "Nesprávne heslo.",
        "invalid_login": "Nesprávne prihlasovacie meno.",
        "ldap_ref": "Nepodarilo sa nadviazať spojenie s LDAP serverom, prosím skontrolujte svoje pripojenie/údaje.",
        "ldap_bin_failed": "Bind z LDAP serverom sa nepodaril,skúste prosím znova",
        "login_exists": "Prihlasovacie meno už existuje, prosím vyberte si iné.",
        "login_inp": "Prihlasovacie meno",
        "passwd_inp": "Heslo",
        "auth_code_inp": "Autentifikačný kód",
        "name": "Meno",
        "surname": "Priezvisko",
        "submit": "Potvrdiť",
        "normal_login": "Klasické prihlásenie",
        "ldap_login": "Prihlásenie cez LDAP",
        "h1-idx" :"Ponúkame vám moderné riešenia zo sveta Computer aided systémov1!",
        "h2-idx" : "Našim osobným cieľom je spraviť tento svet lepší.",
        "h3-idx":"Poháňa nás:",
        "h4-idx": "Naši partneri:",
        "passwd_reset": "Zabudli ste heslo?"
    }
};


$(document).ready(function () {
    

    // The default language is English
    var lang = "en";
    $(".lang").each(function (index, element) {
        $(this).text(arrLang[lang][$(this).attr("key")]);
    });
});

// get/set the selected language
    $(".translate").click(function () {
    var lang = $(this).attr("id");

    $(".lang").each(function (index, element) {
        $(this).text(arrLang[lang][$(this).attr("key")]);
    });
    
});
});