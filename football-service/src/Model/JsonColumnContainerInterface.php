<?php
namespace Sportal\FootballApi\Model;

interface JsonColumnContainerInterface
{

    /**
     * Return a list of assoc arrays with column names => JSON data
     * @return array
     */
    public function getJsonData();

    /**
     * Get list of columns which have been changed in the updated model
     * @param JsonColumnContainerInterface $updated
     * @return array
     */
    public function getChangedJsonColumns(JsonColumnContainerInterface $updated);

    /**
     * Update the json columns with the ones in the updated object
     * @param JsonColumnContainerInterface $updated
     * @return void
     */
    public function updateJsonColumns(JsonColumnContainerInterface $updated);
}