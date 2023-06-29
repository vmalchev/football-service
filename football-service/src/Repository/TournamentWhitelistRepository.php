<?php
namespace Sportal\FootballApi\Repository;

class TournamentWhitelistRepository extends Repository
{

    public function find($id)
    {
        return $this->getByPk('\TournamentWhitelist',
            array(
                'provider' => $id['provider'],
                'provider_id' => $id['provider_id']
            ), function ($row) {
                return $row['provider_id'];
            });
    }

    public function findAll($filter = array())
    {
        return $this->getAll('\TournamentWhitelist', $filter,
            function ($row) {
                return $row['provider_id'];
            });
    }

    public function getModelClass()
    {
        return '\TournamentWhitelist';
    }
}
