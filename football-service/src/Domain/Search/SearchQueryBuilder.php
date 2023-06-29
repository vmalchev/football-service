<?php

namespace Sportal\FootballApi\Domain\Search;


use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Doctrine\DBAL\Query\QueryBuilder;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Database\Query\SortDirection;


class SearchQueryBuilder
{
    const MAX_RESULTS = 100;

    private $queryBuilderMap = [
//        EntityType::PLAYER()->getValue() => $playerRepository,
//        EntityType::TEAM()->getValue() => $teamRepository,
//        EntityType::COACH()->getValue() => $coachRepository,
//        EntityType::VENUE()->getValue() => $tournamentSeasonRepository,
//        EntityType::TOURNAMENT()->getValue() => $tournamentSeasonRepository,
    ];

    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    private $queryParameter;

    private $expressionFilter;

    private $query;


    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
        $this->expressionFilter = new CompositeExpression(CompositeExpression::TYPE_AND);
    }

    public function build(IDto $queryDto)
    {
        $this->queryParameter = $queryDto;

//        $ENTITY_TYPE_BUILDER_MAP = [
//            EntityType::PLAYER()->getValue() => ,
//            EntityType::TEAM()->getValue() => ,
//            EntityType::COACH()->getValue() => ,
//            EntityType::VENUE()->getValue() => ,
//            EntityType::TOURNAMENT()->getValue() => ,
//            EntityType::COUNTRY()->getValue()
//        ];

        if (!isset($this->queryParameter->scope)) {
            $this->select()
                ->player()
                ->team()
                ->coach()
                ->venue()
                ->referee()
                ->tournament()
                ->president()
                ->city()
                ->country()
                ->baseFilter()
                ->filterByEntityType()
                ->filterByLanguageCode()
                ->applyFilter()
                ->order()
                ->limit();
        } else {
            $this->select()
                ->playerInScope()
                ->teamInScope()
                ->coachInScope()
                ->venueInScope()
                ->referee()
                ->tournamentInScope()
                ->presidentInScope()
                ->city()
                ->country()
                ->baseFilter()
                ->filterByEntityType()
                ->filterByLanguageCode()
                ->applyFilter()
                ->orderByScope()
                ->limit();
        }

        return $this->queryBuilder;
    }


    public function select()
    {
        $columns = [
            'mlContent.entity as entity_type',
            "to_jsonb(player.*) as player",
            "to_jsonb(team.*) as team",
            "to_jsonb(coach.*) as coach",
            "to_jsonb(venue.*) as venue",
            "to_jsonb(referee.*) as referee",
            "to_jsonb(tournament.*) as tournament",
            "to_jsonb(president.*) as president",
            "to_jsonb(city.*) as city",
            "to_jsonb(country.*) as country",
            "to_jsonb(team_venue.*) as team_venue"
        ];

        $cases = [];
        foreach ($this->queryParameter->query as $name) {
            $cases[] = "(case when to_tsvector('simple', mlContent.name) @@ to_tsquery('simple', '"
                . $this->prepareSearchQuery($name) . ":*') then '" . $this->reduceWhitespace($name) . "' end)";
        }

        $origin = "CONCAT_WS(','," . implode(',', $cases) . ') as origin';
        $columns[] = $origin;

        $this->queryBuilder
            ->select($columns)
            ->from('ml_content', 'mlContent')
            ->distinct();

        return $this;
    }

    public function tournament()
    {
        $this->queryBuilder->leftJoin(
            'mlContent', 'tournament', 'tournament',
            'mlContent.entity_id = tournament.id
                and mlContent.entity=\'tournament\''
        );

        return $this;
    }

    public function tournamentInScope()
    {
        $tournamentScope = isset($this->queryParameter->scope->tournament) ?
            ' and tournament.id in (' . implode(',', $this->queryParameter->scope->tournament) . ')' : '';
        $this->queryBuilder->leftJoin(
            'mlContent', 'tournament', 'tournament',
            '(mlContent.entity_id = tournament.id and mlContent.entity=\'tournament\')' . $tournamentScope
        );

        return $this;
    }

    public function player()
    {
        $this->queryBuilder->leftJoin(
            'mlContent', 'player', 'player',
            'mlContent.entity_id = player.id
                and mlContent.entity=\'player\''
        );

        return $this;
    }

    public function playerInScope()
    {
        $allScopesOr = $this->queryBuilder->expr()->orX();
        if (isset($this->queryParameter->scope->team)) {
            $allScopesOr->add('player.id in (select player_id from team_player where team_id in (' . implode(',', $this->queryParameter->scope->team) . ') and active=true)');
        }
        if (isset($this->queryParameter->scope->player)) {
            $allScopesOr->add('player.id in (' . implode(',', $this->queryParameter->scope->player) . ')');
        }
        if (isset($this->queryParameter->scope->tournament)) {
            $allScopesOr->add('player.id in (select player_id from team_player 
                    inner join tournament_season_team on team_player.team_id=tournament_season_team.team_id
                    inner join tournament_season on tournament_season_team.tournament_season_id = tournament_season.id 
                    where tournament_id in (' . implode(',', $this->queryParameter->scope->tournament)  .
                ') and team_player.active=true)');
        }

        $joinAnd = $this->queryBuilder->expr()->andX()
            ->add("mlContent.entity_id = player.id")
            ->add("mlContent.entity='player'")
            ->add($allScopesOr);

        $this->queryBuilder->leftJoin(
            'mlContent', 'player', 'player', (string) $joinAnd
        );

        return $this;
    }

    public function team()
    {
        $this->queryBuilder->leftJoin(
            'mlContent', 'team', 'team',
            'mlContent.entity_id = team.id
                and mlContent.entity=\'team\'
                and (team.undecided is null or team.undecided = false)'
        )->leftJoin('team', 'venue', 'team_venue', 'team_venue.id=team.venue_id');

        return $this;
    }

    public function teamInScope()
    {
        $teamAndTournamentOr = $this->queryBuilder->expr()->orX();
        if (isset($this->queryParameter->scope->team)) {
            $teamAndTournamentOr->add('team.id in (' . implode(',', $this->queryParameter->scope->team)  . ')');
        }
        if (isset($this->queryParameter->scope->tournament)) {
            $teamAndTournamentOr->add('team.id in (
            select team_id from tournament_season
                    inner join tournament_season_team on tournament_season.id=tournament_season_team.tournament_season_id
                    where tournament_id in (' . implode(',', $this->queryParameter->scope->tournament) . '))');
        }

        $joinAnd = $this->queryBuilder->expr()->andX()
            ->add("mlContent.entity_id = team.id")
            ->add("mlContent.entity='team' and (team.undecided is null or team.undecided = false)")
            ->add($teamAndTournamentOr);

        $this->queryBuilder->leftJoin(
            'mlContent', 'team', 'team', (string) $joinAnd
        )->leftJoin('team', 'venue', 'team_venue', 'team_venue.id=team.venue_id');

        return $this;
    }

    public function country()
    {
        $this->queryBuilder->leftJoin(
            'mlContent', 'country', 'country',
            'team.country_id = country.id 
                or player.country_id = country.id
                or coach.country_id = country.id 
                or venue.country_id = country.id 
                or city.country_id = country.id 
                or tournament.country_id = country.id
                or referee.country_id = country.id'
        );

        return $this;
    }

    public function city()
    {
        $this->queryBuilder->leftJoin(
            'mlContent', 'city', 'city',
            '(mlContent.entity_id = city.id and mlContent.entity=\'city\')'
        );

        return $this;
    }

    public function coach()
    {
        $this->queryBuilder->leftJoin(
            'mlContent', 'coach', 'coach',
            '(mlContent.entity_id = coach.id and mlContent.entity=\'coach\')'
        );

        return $this;
    }


    public function coachInScope()
    {
        $allScopesOr = $this->queryBuilder->expr()->orX();
        if (isset($this->queryParameter->scope->coach)) {
            $allScopesOr->add('coach.id in (' . implode(',', $this->queryParameter->scope->coach) . ')');
        }
        if (isset($this->queryParameter->scope->team)) {
            $allScopesOr->add('coach.id in (select coach_id from team_coach where team_id in (' . implode(',', $this->queryParameter->scope->team) . ') and active=true)');
        }

        if (isset($this->queryParameter->scope->tournament)) {
            $allScopesOr->add(
                'coach.id in (
                    select coach_id from team_coach 
                    inner join tournament_season_team on team_coach.team_id=tournament_season_team.team_id
                    inner join tournament_season on tournament_season_team.tournament_season_id = tournament_season.id
                    where tournament_id in (' . implode(',', $this->queryParameter->scope->tournament)  . ') and team_coach.active=true)'
            );
        }

        $joinAnd = $this->queryBuilder->expr()->andX()
            ->add("mlContent.entity_id = coach.id")
            ->add("mlContent.entity='coach'")
            ->add($allScopesOr);

        $this->queryBuilder->leftJoin(
            'mlContent', 'coach', 'coach', (string) $joinAnd
        );

        return $this;
    }

    public function president()
    {
        $this->queryBuilder->leftJoin(
            'mlContent', 'president', 'president',
            '(mlContent.entity_id = president.id and mlContent.entity=\'president\')'
        );
        return $this;
    }

    public function presidentInScope()
    {
        $allScopesOr = $this->queryBuilder->expr()->orX();
        if (isset($this->queryParameter->scope->president)) {
            $allScopesOr->add('president.id in (' . implode(',', $this->queryParameter->scope->president) . ')');
        }
        if (isset($this->queryParameter->scope->team)) {
            $allScopesOr->add('president.id in (select president_id from team where id in (' . implode(',', $this->queryParameter->scope->team) . '))');
        }

        if (isset($this->queryParameter->scope->tournament)) {
            $allScopesOr->add(
                'president.id in (
                    select president_id from team
                    inner join tournament_season_team on team.id = tournament_season_team.team_id
                    inner join tournament_season on tournament_season.id=tournament_season_team.tournament_season_id
                    where tournament_id in (' . implode(',', $this->queryParameter->scope->tournament) . '))'
            );
        }

        $joinAnd = $this->queryBuilder->expr()->andX()
            ->add('mlContent.entity_id = president.id')
            ->add("mlContent.entity='president'")
            ->add($allScopesOr);

        $this->queryBuilder->leftJoin(
            'mlContent', 'president', 'president', (string) $joinAnd
        );

        return $this;
    }

    public function venue()
    {
        $this->queryBuilder->leftJoin(
            'mlContent', 'venue', 'venue',
            '(mlContent.entity_id = venue.id and mlContent.entity=\'venue\')'
        );

        return $this;
    }

    public function venueInScope()
    {
        $allScopesOr = $this->queryBuilder->expr()->orX();
        if (isset($this->queryParameter->scope->venue)) {
            $allScopesOr->add('venue.id in (' . implode(',', $this->queryParameter->scope->venue) . ')');
        }
        if (isset($this->queryParameter->scope->team)) {
            $allScopesOr->add('venue.id in (select venue_id from team where id in (' . implode(',', $this->queryParameter->scope->team) . '))');
        }

        if (isset($this->queryParameter->scope->tournament)) {
            $allScopesOr->add(
                'venue.id in (
                    select venue_id from team
                    inner join tournament_season_team on team.id = tournament_season_team.team_id
                    inner join tournament_season on tournament_season.id=tournament_season_team.tournament_season_id
                    where tournament_id in (' . implode(',', $this->queryParameter->scope->tournament) . '))'
            );
        }

        $joinAnd = $this->queryBuilder->expr()->andX()
            ->add("mlContent.entity_id = venue.id")
            ->add("mlContent.entity='venue'")
            ->add($allScopesOr);

        $this->queryBuilder->leftJoin(
            'mlContent', 'venue', 'venue', (string) $joinAnd
        );

        return $this;
    }

    public function referee()
    {
        $this->queryBuilder->leftJoin(
            'mlContent', 'referee', 'referee',
            '(mlContent.entity_id = referee.id and mlContent.entity=\'referee\')'
        );

        return $this;
    }

    public function baseFilter()
    {
        $query = '';
        $queryNames = array_unique($this->queryParameter->query);

        $lastQueryName = end($queryNames);

        foreach ($queryNames as $queryName) {
            $entityName = trim($queryName);
            $entityName = $this->escapeBrackets($entityName);

            $subQuery = '';
            if (strpos($entityName, ' ') !== false) {
                $entityName = $this->reduceWhitespace($entityName);

                $names = explode(" ", $entityName);

                $subQuery .= '(';
                $itemCount = count($names);
                $index = 0;
                foreach ($names as $name) {
                    if (++$index == $itemCount) {
                        $subQuery .= $name . ":*)";
                    } else {
                        $subQuery .= $name . ":* & ";
                    }
                }

            }

            if ($lastQueryName == $queryName && strpos($entityName, ' ') !== false) {
                $query .= $subQuery;
            } elseif ($lastQueryName != $queryName && strpos($entityName, ' ') !== false) {
                $query .= $subQuery . " | ";
            } elseif ($lastQueryName != $queryName) {
                $query .= $entityName . ":* | ";
            } else {
                $query .= $entityName . ":*";
            }
        }

        $orX = $this->queryBuilder->expr()
            ->orX()
            ->add('team.id is not null')
            ->add('player.id is not null')
            ->add('coach.id is not null')
            ->add('tournament.id is not null')
            ->add('city.id is not null')
            ->add('venue.id is not null')
            ->add('referee.id is not null')
            ->add('president.id is not null');


        $this->query = $query;
        $searchRule = $this->queryBuilder
            ->expr()
            ->andX()
            ->add(
                "to_tsvector('simple', mlContent.name) @@ to_tsquery('simple', '" . $query . "')"
            );

        $this->expressionFilter->add(
            $this->queryBuilder
                ->expr()
                ->andX()
                ->add($orX)
                ->add($searchRule)
        );

        return $this;
    }

    public function filterByEntityType()
    {
        if (is_null($this->queryParameter->entityTypes)) {
            return $this;
        }

        $this->expressionFilter->add(
            $this->queryBuilder
                ->expr()
                ->in('mlContent.entity', ':entityTypes')
        );

        $this->queryBuilder->setParameter(
            'entityTypes',
            $this->queryParameter->entityTypes,
            \Doctrine\DBAL\Connection::PARAM_STR_ARRAY
        );

        return $this;
    }

    public function filterByLanguageCode()
    {
        if (is_null($this->queryParameter->languageCode)) {
            return $this;
        }

        $this->expressionFilter->add(
            $this->queryBuilder
                ->expr()
                ->eq('mlContent.language_code', ':languageCode')
        );

        $this->queryBuilder->setParameter(
            'languageCode',
            $this->queryParameter->languageCode
        );

        return $this;
    }

    public function applyFilter()
    {
        $this->queryBuilder->where($this->expressionFilter);

        return $this;
    }

    public function order()
    {
        $this->queryBuilder->addSelect("ts_rank_cd(to_tsvector('simple', mlContent.name), to_tsquery('simple', '" . $this->query . "'), 1) as search_rank");
        $this->queryBuilder->addOrderBy("search_rank", SortDirection::DESC);
        return $this;
    }


    public function orderByScope()
    {
        $this->queryBuilder->addSelect("ts_rank_cd(to_tsvector('simple', mlContent.name), to_tsquery('simple', '" . $this->query . "'), 1) as search_rank");
        $this->queryBuilder->addOrderBy("search_rank", SortDirection::DESC);

        return $this;
    }

    public function limit()
    {
        $this->queryBuilder->setMaxResults(self::MAX_RESULTS);

        return $this;
    }

    private function prepareSearchQuery(string $query): string
    {
        $query = preg_replace('/\s+/', ' ', trim($query));
        $query = $this->escapeBrackets($query);
        return str_replace(' ', ':*&', $query);
    }

    private function reduceWhitespace(string $query): string
    {
        return preg_replace('/\s+/', ' ', trim($query));
    }

    private function escapeBrackets(string $query): string
    {
        return str_replace(array(')', '('),  array('\)', '\('), $query);
    }
}