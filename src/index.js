const LANGUAGE_SWITCH_CLASS = "lang-switch";
const ACTIVE_LANGUAGE_CLASS = "lang-switch color-def";
const NONACTIVE_LANGUAGE_CLASS = "lang-switch color-dark";

class App{
    jsonData;

    setElementInnerHTML(HTMLElementId, newInnerHTML){
        document.getElementById(HTMLElementId).innerHTML = newInnerHTML;
    }

    setElementInnerHTMLAnimated(HTMLElementId, newInnerHTML, speed){
        document.getElementById(HTMLElementId).innerHTML = "";
        let i = 0;
        function typeWriter() {
            if (i < newInnerHTML.length) {
                document.getElementById(HTMLElementId).innerHTML += newInnerHTML.charAt(i);
                i++;
                setTimeout(typeWriter, speed);
            }
        }
        setTimeout(typeWriter, 250);
    }

    setElementHref(HTMLElementId, newHref){
        document.getElementById(HTMLElementId).href = newHref;
    }

    addLanguageLinksListeners(){
        document.addEventListener('click', (event) => {
            if (event.target.matches('.'+LANGUAGE_SWITCH_CLASS)) {
                this.changeLanguage(event);
            }
        }, false);
    }

    changeLanguage(event){
        event.preventDefault();
        let newLanguage = event.target.getAttribute('data-language');
        this.loadContentDynamicallyInLanguage(newLanguage);
    }


    translate(arrayOfIds, toLanguage){
        Array.prototype.forEach.call(arrayOfIds, id => {
            this.setElementInnerHTML(id,this.jsonData['webcontent'][id][toLanguage]);
        });
    }

    setLanguageMenuOptionSelected(language){
        let languageOptions = document.querySelectorAll("[data-type='lang']");
        Array.prototype.forEach.call(languageOptions, langOption => {
            if (langOption.getAttribute('data-language') === language){
                langOption.className = ACTIVE_LANGUAGE_CLASS;
            } else {
                langOption.className = NONACTIVE_LANGUAGE_CLASS;
            }
        });
    }

    hasUserLanguageAlreadySet(){
        let storedLanguage = localStorage.getItem("language");
        return storedLanguage !== null;
    }

    getLocalStorageLanguage(){
        return localStorage.getItem("language");
    }

    loadContentDynamicallyInLanguage(language){
        this.translate([
            'longdesc',
            'shortdesc',
            'name'
        ], language);
        document.title = this.jsonData["webcontent"]["metaTitle"][language];
        document.documentElement.lang = language;
        window.history.pushState(language, language, language);

        this.setLanguageMenuOptionSelected(language);
        localStorage.language = language;
    }

    doLoadFromStorage(){
        let body = document.getElementsByTagName("BODY")[0];
        return body.getAttribute("data-loadfromstorage") !== null;
    }

    async fetchAsync (src) {
        return await (await fetch(src)).json();
    }
}

let app = new App();
app.fetchAsync('../content/content.json')
    .then(data => {
        app.jsonData = data;
        app.setElementInnerHTMLAnimated('email',app.jsonData['email'], 50);
        app.setElementHref('email','mailto:'+data['email']);
        app.addLanguageLinksListeners();
        if (!app.doLoadFromStorage()){
            localStorage.language = document.documentElement.lang;
        }

        if (app.hasUserLanguageAlreadySet() && app.doLoadFromStorage()){
            app.loadContentDynamicallyInLanguage(app.getLocalStorageLanguage());
        }
    });
