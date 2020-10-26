<?php
require_once 'class.LanguageMenuOption.php';

class ContentInjector
{
    private array $jsonContent;
    private string $language;

    public function loadJSONContents(string $src): void
    {
        $contentJSON = file_get_contents($src);
        $this->jsonContent = json_decode($contentJSON, true);
    }

    public function isUserOnLanguageSpecificWebpage(): bool
    {
        if (isset($_GET['lang'])){
            $langParameter = $_GET['lang'];
            if ($langParameter !== "" && array_key_exists($langParameter,$this->getAvailableLanguages())) {
                return true;
            }
        }
        return false;
    }

    public function getDisplayLanguage(): string
    {
        if (isset($_GET['lang'])){
            $langParameter = $_GET['lang'];
            if ($langParameter !== "" && array_key_exists($langParameter,$this->getAvailableLanguages())) {
                return strtolower($langParameter);
            }
        }
        return $this->jsonContent["defaultLanguage"];
    }

    public function getAvailableLanguages(): array
    {
        return $this->jsonContent["languages"];
    }

    public function getBodyData(): string
    {
        if ($this->isUserOnLanguageSpecificWebpage()) {
            return '';
        } else {
            return 'data-loadfromstorage';
        }
    }

    public function setContentLanguage(string $language): void
    {
        $this->language = $language;
    }

    public function content(string $key): string
    {
        if (isset($this->jsonContent[$key])) {
            return $this->jsonContent[$key];
        }
        if (isset($this->jsonContent['webcontent'][$key][$this->language])){
            return $this->jsonContent['webcontent'][$key][$this->language];
        }
    }

    public function renderLanguageMenuOptions(): void
    {
        foreach($this->getAvailableLanguages() as $language => $isActive){
            if ($isActive){
                $menuOption = new LanguageMenuOption();
                $menuOption->setLanguage($language);
                $menuOption->setURL($this->content('canonical') . '/' . $language);
                if ($language == $this->getDisplayLanguage()) {
                    $menuOption->setActive(true);
                }
                $menuOption->show();
            }
        }
    }
}