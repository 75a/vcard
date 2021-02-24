<?php
require_once 'class.LanguageMenuOption.php';

class ContentInjector
{
    private array $jsonContent;
    private string $language;

    public function loadJSON(string $src): void
    {
        $contentJSON = file_get_contents($src);
        $this->jsonContent = json_decode($contentJSON, true);
    }

    public function getDisplayLanguage(): string
    {
        if (isset($_GET['lang'])) {
            $langParameter = $_GET['lang'];
            if (array_key_exists($langParameter,$this->getAvailableLanguages())) {
                return strtolower($langParameter);
            }
        }
        return $this->jsonContent["default_language"];
    }

    public function getBodyData(): string
    {
        return ($this->isUserOnLanguageSpecificWebPage() ? '' : 'data-loadfromstorage');
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
        if (isset($this->jsonContent['web_content'][$key][$this->language])){
            return $this->jsonContent['web_content'][$key][$this->language];
        }
    }

    public function renderLanguageMenuOptions(): void
    {
        foreach($this->getAvailableLanguages() as $language => $isActive){
            if ($isActive){
                $menuOption = new LanguageMenuOption();
                $menuOption->setLanguage($language);
                $menuOption->setURL($this->getCanonicalUrl() . '/' . $language);
                if ($language == $this->getDisplayLanguage()) {
                    $menuOption->setActive(true);
                }
                $menuOption->show();
            }
        }
    }

    public function injectExternalLinks(): void
    {
        foreach ($this->getIconLinksData() as $service => $serviceData){
            $iconSrc = $serviceData["icon_src"];
            $url = $serviceData["url"];
            echo "
                <a href=\"{$url}\" target=\"_blank\">
                    <img class=\"social-icon\" src=\"{$iconSrc}\" alt=\"$service\">
                </a>
            ";
        }
    }

    private function isUserOnLanguageSpecificWebPage(): bool
    {
        if (isset($_GET['lang'])){
            $langParameter = $_GET['lang'];
            if ($langParameter !== "" && array_key_exists($langParameter,$this->getAvailableLanguages())) {
                return true;
            }
        }
        return false;
    }

    private function getAvailableLanguages(): array
    {
        return $this->jsonContent["languages"];
    }

    private function getIconLinksData(): array
    {
        return $this->jsonContent["icon_links"];
    }

    private function getCanonicalUrl(): string
    {
        $canonical = $this->content('canonical');
        return (substr($canonical, -1) === "/" ? substr($canonical, 0, -1) : $canonical);
    }
}