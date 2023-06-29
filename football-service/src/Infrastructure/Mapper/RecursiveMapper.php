<?php


namespace Sportal\FootballApi\Infrastructure\Mapper;


class RecursiveMapper
{
    private IPropertyResolver $propertyResolver;

    /**
     * RecursiveMapper constructor.
     * @param IPropertyResolver $propertyResolver
     */
    public function __construct(IPropertyResolver $propertyResolver)
    {
        $this->propertyResolver = $propertyResolver;
    }

    /**
     * @param mixed $data
     * @param callable $dataFilter
     * @param callable $mapper
     * @return array
     */
    public function map($data, callable $dataFilter, callable $mapper): array
    {
        $allResults = [];
        if ($dataFilter($data) === true) {
            $result = $mapper($data);
            if (!is_null($result)) {
                $allResults[] = $result;
            }
        }

        $nestedProperties = [];
        if (is_array($data)) {
            foreach ($data as $property) {
                $nestedProperties[] = $property;
            }
        } else if (is_object($data)) {
            $nestedProperties = $this->propertyResolver->resolve($data);
        }

        foreach ($nestedProperties as $property) {
            $nestedResults = $this->map($property, $dataFilter, $mapper);
            foreach ($nestedResults as $result) {
                $allResults[] = $result;
            }
        }

        return $allResults;
    }
}