<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

/**
 * @SWG\Definition(required={"id", "name", "country"})
 */
class Player extends Person
{

    const POSITION_UNKNOWN = 'unknown';

    /**
     * Playing position of the player
     * @var string
     * @SWG\Property(enum={"keeper", "defender", "midfielder", "forward"})
     */
    private $position;

    /**
     * @var array
     */
    private $profile;

    /**
     * @var array
     */
    private $social;

    const THUMB_KEY = 'thumb';

    const IMAGE_KEY = 'image';

    /**
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Set profile
     *
     * @param array $profile
     *
     * @return Player
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
        
        return $this;
    }

    /**
     * Get profile
     *
     * @return array
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set social
     *
     * @param array $social
     *
     * @return Player
     */
    public function setSocial($social)
    {
        $this->social = $social;
        
        return $this;
    }

    /**
     * Get social
     *
     * @return array
     */
    public function getSocial()
    {
        return $this->social;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return array_merge(parent::getPersistanceMap(),
            [
                'profile' => ! empty($this->profile) ? json_encode($this->profile) : null,
                'social' => ! empty($this->social) ? json_encode($this->social) : null,
                'position' => $this->position
            ]);
    }

    /**
     * {@inheritDoc}
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        $data = parent::jsonSerialize();
        if ($this->position !== null) {
            $data['position'] = $this->position;
        }
        
        if (! empty($this->profile)) {
            foreach ($this->profile as $key => $value) {
                $data[$key] = (string) $value;
            }
        }
        if (! empty($this->social)) {
            $data['social'] = $this->social;
        }
        
        return $data;
    }

    public static function getAssetColumns()
    {
        return static::$assetTypes;
    }
}

