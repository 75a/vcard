const DEFAULT_LANGUAGE = "en";
const ANIMATION_TIMER = 450;
const ANIMATION_SPEED = 60;
const CHARS_PRO_TICK = 2;
const UNSELECTED_LANGUAGE_CLASS = "color-def";
const SELECTED_LANGUAGE_CLASS = "color-dark";
const LANGUAGE_CLASS = "lang-switch";

class ContentLoader
{
    constructor(data)
    {
        this.data = data;
    }

    loadValue(valueIndex,lang)
    {
        let newValue;
        if (lang === undefined){
            newValue = (this.data)[valueIndex];
        } else {
            newValue = (this.data)["webcontent"][valueIndex][lang];
        }
        return newValue;
    }

    isLanguageValid(language)
    {
        return !!this.data["languages"][language];

    }

    replaceData(valueIndex, lang)
    {
        const htmlElement = document.getElementById(valueIndex);
        htmlElement.innerHTML = this.loadValue(valueIndex,lang);
    }

    replaceDataWithAnimation(valueIndex,lang)
    {
        const htmlElement = document.getElementById(valueIndex);
        const newValue = this.loadValue(valueIndex, lang);
        htmlElement.innerHTML = "";

        const newTextLength = newValue.length;
        let currentTextLength = 0;
        const charsPerTick = CHARS_PRO_TICK;
        setTimeout(function(){
            const addNewText = setInterval(function () {
                if (currentTextLength >= newTextLength - charsPerTick) {
                    window.clearInterval(addNewText);
                    htmlElement.innerHTML = newValue;
                } else {

                    currentTextLength += charsPerTick;
                    htmlElement.innerHTML = newValue.substring(0, currentTextLength);
                }
            }, ANIMATION_SPEED);
        },ANIMATION_TIMER);
    }

    addMailHyperlink()
    {
        let mailElement = document.getElementById("email");
        mailElement.setAttribute("href", `mailto:${this.loadValue('email')}`);
    }
}

class JSONFetcher
{
    async fetchData(link)
    {
        let response = await fetch(link);
        return await response.json();
    }
}

class VCard
{
    contentLoader;
    loadContent(dataSource)
    {
        this.dataSource = dataSource;
        this.fetchDataFromJSON()
            .then((fetchedData) => {
                this.contentLoader = new ContentLoader(fetchedData);
                this.applyClientLanguageFromLocalStorage();
                return this.contentLoader;
            })
            .then(() => this.loadContentUponVisit());
        this.programLanguageNavigation();
    }

    loadContentUponVisit()
    {
        this.contentLoader.replaceDataWithAnimation("email");
        this.contentLoader.addMailHyperlink();
        this.loadContentOnLanguageSwitch();
    }

    loadContentOnLanguageSwitch()
    {
        this.contentLoader.replaceData("name",this.language)
        this.contentLoader.replaceData("shortdesc",this.language)
        this.contentLoader.replaceData("longdesc",this.language)
    }

    programLanguageNavigation()
    {
        document.addEventListener('click', (event) => {
            if (event.target.matches('.'+LANGUAGE_CLASS)) {
                this.onClientSwitchLanguage(event);
            }
        }, false);
    }

    setLangMenuOptionHighlighted ()
    {
        let languageNavElements = document.getElementsByClassName(LANGUAGE_CLASS);

        Array.prototype.forEach.call(languageNavElements, navElement => {
            if (navElement.text.localeCompare(this.language) === 0) {
                navElement.className = LANGUAGE_CLASS + ' ' + UNSELECTED_LANGUAGE_CLASS;
            } else {
                navElement.className = LANGUAGE_CLASS + ' ' + SELECTED_LANGUAGE_CLASS;
            }
        });
    }

    onClientSwitchLanguage(event)
    {
        event.preventDefault();
        this.language = (this.contentLoader.isLanguageValid(event.target.text)) ? (event.target.text) : DEFAULT_LANGUAGE;
        this.setLangMenuOptionHighlighted();
        this.setLocalStorageLanguage();
        this.loadContentOnLanguageSwitch();
    }

    setLocalStorageLanguage()
    {
        localStorage.setItem("lang",this.language);
    }

    applyClientLanguageFromLocalStorage()
    {

        const urlParameterLanguage = ParameterReader.getParameterValue('language');

        if (urlParameterLanguage !== ""){
            this.language = (this.contentLoader.isLanguageValid(urlParameterLanguage)) ? (urlParameterLanguage) : DEFAULT_LANGUAGE;
            this.setLocalStorageLanguage();
        } else {

            let localStorageLang = localStorage.getItem("lang");
            this.language = (localStorageLang === null)? DEFAULT_LANGUAGE : localStorageLang;
        }
        this.setLangMenuOptionHighlighted();
    }

    async fetchDataFromJSON()
    {
        this.jsonFetcher = new JSONFetcher();
        return await this.jsonFetcher.fetchData(this.dataSource);
    }
}


class NoJSARedirector
{
    subPage;
    constructor()
    {
        this.subPage = document.location.href.split('/')[3];
    }

    isUserNotOnTheMainPage()
    {
        return (this.subPage !== "") && !(this.isUserBeingRedirected());
    }

    redirectUserToTheMainPage()
    {
        //window.location.replace(`${location.href}?language=${this.subPage}`)
    }

    isUserBeingRedirected()
    {
        return ParameterReader.hasParameter('language');
    }
}

class ParameterReader
{
    static urlParams = new URLSearchParams(window.location.search);

    static hasParameter(parameter)
    {
        return this.urlParams.has(parameter);
    }

    static getParameterValue(parameter)
    {
        if (this.hasParameter(parameter)) {
            return this.urlParams.get(parameter);
        } else {
            return "";
        }
    }

    static clearURL()
    {
        window.history.replaceState({}, document.title, "/");
    }
}

class App
{
    constructor()
    {
        const noJSPrevent = new NoJSARedirector();
        if (noJSPrevent.isUserNotOnTheMainPage()) {
            noJSPrevent.redirectUserToTheMainPage();
        } else {
            let vcard = new VCard();
            vcard.loadContent("../content/content.json");
            ParameterReader.clearURL();
        }
    }
}
new App();