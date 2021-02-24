<?php

class LanguageMenuOption
{
    private static string $activeClassName;
    private static string $inactiveClassName;

    private string $langShortCode;
    private string $thisClass;
    private string $url;

    public function __construct()
    {
        $this->setActive(false);
    }

    public static function setActiveClassName(string $className): void
    {
        self::$activeClassName = $className;
    }

    public static function setInactiveClassName(string $className): void
    {
        self::$inactiveClassName = $className;
    }

    public function setURL (string $url): void
    {
        $this->url = $url;
    }

    public function setLanguage(string $language): void
    {
        $this->langShortCode = $language;
    }

    public function setActive(bool $active): void
    {
        $this->setThisClass($active);
    }

    public function show(): void
    {
        echo "<li>
                   <a   class=\"{$this->thisClass}\"
                        data-type=\"lang\"
                        data-language=\"{$this->langShortCode}\"
                        href=\"{$this->url}\">
                    {$this->langShortCode}
                   </a>
              </li>";
    }

    private function setThisClass(bool $active): void
    {
        ($active ? $this->thisClass = self::$activeClassName : $this->thisClass = self::$inactiveClassName);
    }
}