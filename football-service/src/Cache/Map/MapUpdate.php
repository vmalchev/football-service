<?php
namespace Sportal\FootballApi\Cache\Map;

use Sportal\FootballApi\Cache\Cache;
use Predis\Transaction\MultiExec;
use Sportal\FootballApi\Util\ModelNameUtil;
use Sportal\FootballApi\Cache\CollectionUpdateInterface;

class MapUpdate implements CollectionUpdateInterface
{

    /**
     *
     * @var array
     */
    private $add;

    /**
     *
     * @var array
     */
    private $remove;

    private $nameUtil;

    public function __construct(ModelNameUtil $nameUtil)
    {
        $this->nameUtil = $nameUtil;
    }

    public function generateKey($className, $name)
    {
        return $this->nameUtil->persistanceName($className) . Cache::DELIM . 'map' . Cache::DELIM . $name;
    }

    public function add($instance, $mapName = null)
    {
        $maps = $instance->getMapEntries();
        if (! empty($maps)) {
            $className = get_class($instance);
            foreach ($maps as $name => $entries) {
                if (empty($mapName) || $name == $mapName) {
                    $mapKey = $this->generateKey($className, $name);
                    $this->add[$mapKey] = isset($this->add[$mapKey]) ? $this->add[$mapKey] + $entries : $entries;
                }
            }
        }
    }

    public function delete($instance)
    {
        $maps = $instance->getMapEntries();
        if (! empty($maps)) {
            $className = get_class($instance);
            foreach ($maps as $name => $entries) {
                $mapKey = $this->generateKey($className, $name);
                $fields = array_keys($entries);
                $this->remove[$mapKey] = isset($this->remove[$mapKey]) ? array_merge($this->remove[$mapKey], $fields) : $fields;
            }
        }
    }

    public function update($existing, $updated)
    {
        $this->delete($existing);
        $this->add($updated);
    }

    public function hasUpdates()
    {
        return ! empty($this->add) || ! empty($this->remove);
    }

    public function cancel()
    {
        $this->add = null;
        $this->remove = null;
    }

    public function flush(MultiExec $tx, array $existsMap, $shouldCreate)
    {
        if (! empty($this->remove)) {
            foreach ($this->remove as $key => $fields) {
                $tx->hdel($key, $fields);
            }
        }
        
        if (! empty($this->add)) {
            foreach ($this->add as $key => $dict) {
                if ($shouldCreate || ! empty($existsMap[$key])) {
                    $tx->hdel($key, [
                        Cache::EMPTY_COLLECTION
                    ]);
                    $tx->hmset($key, $dict);
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Cache\CollectionUpdateInterface::getClassName()
     */
    public function getClassName()
    {
        return MappableInterface::class;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Cache\CollectionUpdateInterface::getUpdateKeys()
     */
    public function getUpdateKeys()
    {
        if (! empty($this->add)) {
            return array_keys($this->add);
        }
        return [];
    }
}