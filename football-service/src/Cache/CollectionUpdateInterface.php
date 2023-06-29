<?php
namespace Sportal\FootballApi\Cache;

use Predis\Transaction\MultiExec;

interface CollectionUpdateInterface
{

    public function generateKey($className, $name);

    public function add($instance, $name = null);

    public function delete($instance);

    public function update($existing, $updated);

    public function hasUpdates();

    public function cancel();

    public function flush(MultiExec $tx, array $existsMap, $shouldCreate);

    public function getClassName();

    public function getUpdateKeys();
}