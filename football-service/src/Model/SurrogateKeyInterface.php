<?php
namespace Sportal\FootballApi\Model;

interface SurrogateKeyInterface extends ModelInterface
{

    public function getId();

    public function setId($id);
}