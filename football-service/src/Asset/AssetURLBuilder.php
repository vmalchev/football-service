<?php
namespace Sportal\FootballApi\Asset;

class AssetURLBuilder
{

    /**
     *
     * @var AssetURLBuilderInterface
     */
    private static $instance = null;

    /**
     * Set the AssetURLBuilder used by the models.
     * @param AssetURLBuilderInterface $builder
     */
    public static function setInstance(AssetURLBuilderInterface $builder)
    {
        static::$instance = $builder;
    }

    /**
     * Return instance of AssetURLBuilderInterface
     * @return AssetURLBuilderInterface
     */
    public static function getInstance()
    {
        return static::$instance;
    }
}