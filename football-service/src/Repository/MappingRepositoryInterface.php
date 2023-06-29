<?php
namespace Sportal\FootballApi\Repository;

interface MappingRepositoryInterface
{

    /**
     * Return our own (if any) identifier for the specified remote identifier or null if no mapping is found.
     * @param string $className Class name of the object for which a lookup is requested.
     * @param mixed $remoteId
     * @return mixed|null
     */
    public function getOwnId($className, $remoteId);

    /**
     * Set a mapping for the specified object between the remote identifier and our own identifier.
     * @param string $className Class name of the object for which a lookup is set.
     * @param mixed $remoteId
     * @param mixed $ownId
     */
    public function setMapping($className, $remoteId, $ownId);

    /**
     *
     * @param string $className
     * @return array List of remote ids for the given class name
     */
    public function getRemoteIds($className);

    /**
     * Find the remote id mapping for the specified className and id in our own database.
     * @param string $className
     * @param mixed $ownId
     * @return mixed|null
     */
    public function getRemoteId($className, $ownId);

    /**
     * @return string name of the source to which we map ids
     */
    public function getSourceName();
}