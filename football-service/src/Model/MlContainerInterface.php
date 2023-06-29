<?php
namespace Sportal\FootballApi\Model;

interface MlContainerInterface
{

    /**
     * Check if the container has loaded the specified language code.
     * @param string $langCode
     * @return bool
     */
    public function hasContent($langCode);

    /**
     * Add a translated content with the following language into the container.
     * @param string $langCode
     * @param array $content
     */
    public function addContent($langCode, array $content);

    /**
     *
     * Set the language in which the container should translate content.
     * @param string $langCode
     */
    public function setLanguage($langCode);

    /**
     * @return mixed Unique identifier of the object.
     */
    public function getId();

    /**
     * @return string Name of the container in persistance (player, team, etc).
     */
    public function getContainerName();
}