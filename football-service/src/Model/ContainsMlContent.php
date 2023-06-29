<?php

namespace Sportal\FootballApi\Model;

use Sportal\FootballApi\Util\NameUtil;

trait ContainsMlContent
{

    /**
     *
     * @var array
     */
    protected $mlContent;

    /**
     *
     * @var string
     */
    protected $langCode = null;

    /**
     *
     * @param unknown $langCode
     * @param array $content
     */
    public function addContent($langCode, array $content)
    {
        $this->mlContent[$langCode] = $content;
    }

    /**
     *
     * @param string $langCode
     * @return boolean
     */
    public function hasContent($langCode)
    {
        return $langCode && isset($this->mlContent[$langCode]);
    }

    /**
     *
     * @param string $langCode
     */
    public function setLanguage($langCode)
    {
        $this->langCode = $langCode;
    }

    /**
     *
     * @param array $content
     * @return mixed
     */
    protected function translateContent(array $content)
    {
        if ($this->langCode !== null && isset($this->mlContent[$this->langCode])) {
            foreach ($this->mlContent[$this->langCode] as $key => $value) {
                if (!empty($value) && array_key_exists($key, $content)) {
                    $content[$key] = $value;
                }
            }
        }
        return $content;
    }

    public function getContainerName()
    {
        return NameUtil::persistanceName(get_class($this));
    }

}