<?php


namespace Sportal\FootballApi\Application\Player\Output\Get;


use Sportal\FootballApi\Application\City;
use Sportal\FootballApi\Application\Country;
use Sportal\FootballApi\Application\Player\Dto\PlayerEditSocialDto;
use Sportal\FootballApi\Application\Player\Dto\PlayerProfileDto;
use Sportal\FootballApi\Application\Player\Output\Get\Dto;
use Sportal\FootballApi\Domain\Player\IPlayerEntity;

class Mapper
{
    /**
     * @var Country\Output\Get\Mapper
     */
    private Country\Output\Get\Mapper $countryMapper;

    /**
     * @var City\Output\Get\Mapper
     */
    private City\Output\Get\Mapper $cityMapper;

    public function __construct(Country\Output\Get\Mapper $countryMapper, City\Output\Get\Mapper $cityMapper)
    {
        $this->countryMapper = $countryMapper;
        $this->cityMapper = $cityMapper;
    }

    public function map(?IPlayerEntity $playerEntity): ?Dto
    {
        if (is_null($playerEntity)) {
            return null;
        }

        if (!is_null($playerEntity->getSocial())) {
            $social = new PlayerEditSocialDto(
                $playerEntity->getSocial()->getWeb(),
                $playerEntity->getSocial()->getTwitterId(),
                $playerEntity->getSocial()->getFacebookId(),
                $playerEntity->getSocial()->getInstagramId(),
                $playerEntity->getSocial()->getWikipediaId(),
                $playerEntity->getSocial()->getYoutubeChannelId()
            );
        } else {
            $social = null;
        }

        if (!is_null($playerEntity->getProfile())) {
            $profile = new PlayerProfileDto(
                $playerEntity->getProfile()->getHeight(),
                $playerEntity->getProfile()->getWeight(),
            );
        } else {
            $profile = null;
        }

        return new Dto(
            $playerEntity->getId(),
            $playerEntity->getName(),
            $this->countryMapper->map($playerEntity->getCountry()),
            $playerEntity->getActive(),
            !is_null($playerEntity->getBirthdate()) ? $playerEntity->getBirthdate()->format("Y-m-d") : null,
            $this->cityMapper->map($playerEntity->getBirthCity()),
            $profile,
            $social,
            !is_null($playerEntity->getPosition()) ? $playerEntity->getPosition()->getKey() : null,
            $playerEntity->getGender()
        );
    }

}