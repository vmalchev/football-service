<?php
namespace Sportal\FootballApi\Model;

use Sportal\FootballApi\Util\ArrayUtil;

trait ContainsJsonColumns
{

    abstract public function getJsonData();

    public function getChangedJsonColumns(JsonColumnContainerInterface $updated)
    {
        $existing = $this->getJsonData();
        $updatedData = $updated->getJsonData();
        $changedKeys = [];
        foreach ($existing as $key => $existingArr) {
            if (! empty($updatedData[$key])) {
                $new = ArrayUtil::getMerged($existingArr, $updatedData[$key]);
                if ($new != $existingArr) {
                    $changedKeys[] = $key;
                }
            }
        }
        return $changedKeys;
    }

    public function getJsonPersistance()
    {
        $data = $this->getJsonData();
        foreach ($data as $key => $value) {
            $data[$key] = ! empty($value) ? json_encode($value) : null;
        }
        return $data;
    }

    public function mergeJsonData(array $into)
    {
        foreach ($this->getJsonData() as $jsonArr) {
            foreach ($jsonArr as $key => $value) {
                $into[$key] = $value;
            }
        }
        return $into;
    }

    public function updateJsonColumns(JsonColumnContainerInterface $updated)
    {
        $existing = $this->getJsonData();
        $updatedData = $updated->getJsonData();
        foreach ($existing as $key => $existingArr) {
            if (! empty($updatedData[$key])) {
                $new = ArrayUtil::getMerged($existingArr, $updatedData[$key]);
                $method = 'set' . str_replace('_', '', ucwords($key));
                call_user_func([
                    $this,
                    $method
                ], $new);
            }
        }
    }
}