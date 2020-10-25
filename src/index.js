const LANGUAGE_SWITCH_CLASS = "lang-switch";
const ACTIVE_LANGUAGE_CLASS = "lang-switch color-def";
const NONACTIVE_LANGUAGE_CLASS = "lang-switch color-dark";

class App{
    jsonData;

    setElementInnerHTML(HTMLElementId, newInnerHTML){
        document.getElementById(HTMLElementId).innerHTML = newInnerHTML;
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
        })
    }

    hasUserLanguageAlreadySet(){
        let storedLanguage = localStorage.getItem("language");
        if (storedLanguage !== null){
            return true;
        }
        return false;
    }

    getLocalStorageLanguage(){
        return localStorage.getItem("language")
    }

    loadContentDynamicallyInLanguage(language){
        this.translate([
            'longdesc',
            'shortdesc',
            'name'
        ], language);
        document.documentElement.lang = language;
        window.history.pushState(language, language, language);

        this.setLanguageMenuOptionSelected(language);
        localStorage.language = language;
    }

    doLoadFromStorage(){
        let body = document.getElementsByTagName("BODY")[0]
        if (body.getAttribute("data-loadfromstorage") !== null){
            return true;
        }
        return false;
    }

    async fetchAsync (src) {
        return await (await fetch(src)).json();
    }


}

let app = new App();
let storedJSONData;
app.fetchAsync('../content/content.json')
    .then(data => {
        app.jsonData = data;
        app.setElementInnerHTML('email',data['email']);
        app.setElementHref('email','mailto:'+data['email']);
        app.addLanguageLinksListeners();
        if (!app.doLoadFromStorage()){
            localStorage.language = document.documentElement.lang;
        }

        if (app.hasUserLanguageAlreadySet() && app.doLoadFromStorage()){
            app.loadContentDynamicallyInLanguage(app.getLocalStorageLanguage());
        }
    });
