<?php
namespace Sportal\FootballApi\Database;

interface SurrogateKeyInterface extends ModelInterface
{

    public function getId();

    public function setId($id);
}