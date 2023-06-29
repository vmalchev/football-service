<?php

namespace Sportal\FootballApi\Asset;

use Sportal\FootballApi\Util\NameUtil;

trait ContainsAssets
{

    /**
     *
     * @var array
     */
    private $assets;

    /**
     * @return array
     */
    abstract public function getAssetTypes();

    /**
     * @return mixed
     */
    abstract public function getId();

    /**
     * @return string
     */
    abstract public function getName();

    public function setAssetFilenames(array $data)
    {
        $types = $this->getAssetTypes();

        foreach ($types as $type) {
            if (!empty($data[$type])) {
                $this->setAssetFilename($type, $data[$type]);
            } else {
                $this->setAssetFilename($type, null);
            }
        }

        return $this;
    }

    public function hasAsset($type)
    {
        return isset($this->assets[$type]);
    }

    public function getAssetFilename($type)
    {
        return isset($this->assets[$type]) ? $this->assets[$type] : null;
    }

    public function setAssetFilename($type, $filename)
    {
        $this->checkSupport($type);
        if (!empty($filename)) {
            $this->assets[$type] = $filename;
        } else {
            $this->assets[$type] = null;
        }

        return $this;
    }

    public function generateAssetName($type)
    {
        $this->checkSupport($type);
        $name = str_replace(" ", "-", NameUtil::normalizeName($this->getName()));
        return $this->getId() . "-" . preg_replace("/[^a-zA-Z0-9\-]+/", "", $name) . "-" . $type;
    }

    public function getAssetModelName()
    {
        return NameUtil::persistanceName(get_class($this));
    }

    public function isSupported($type)
    {
        return in_array($type, $this->getAssetTypes());
    }

    public function getAssetUrls()
    {
        $assetUrls = [];

        $builder = AssetURLBuilder::getInstance();

        foreach ($this->getAssetTypes() as $type) {
            $filename = $this->assets[$type] ?? null;
            if (is_null($filename) || is_null($builder)) {
                $assetUrls['url_' . $type] = null;
            } else {
                $assetUrls['url_' . $type] = $builder->getAssetUrl($this->getAssetModelName(), $filename, $type);
            }
        }

        return $assetUrls;
    }

    public function addAssetUrls(array $into)
    {
        $builder = AssetURLBuilder::getInstance();

        foreach ($this->getAssetTypes() as $type) {
            $filename = $this->assets[$type] ?? null;
            if (is_null($filename) || is_null($builder)) {
                $into['url_' . $type] = null;
            } else {
                $into['url_' . $type] = $builder->getAssetUrl($this->getAssetModelName(), $filename, $type);
            }
        }

        return $into;
    }

    protected function checkSupport($type)
    {
        if (!$this->isSupported($type)) {
            throw new \InvalidArgumentException('Asset type ' . $type . ' is not supported by ' . get_class($this));
        }
    }
}