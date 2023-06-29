<?php


namespace Sportal\FootballApi\Application\President\Service;


use Sportal\FootballApi\Application\Exception\DuplicateKeyException;
use Sportal\FootballApi\Application\Exception\NoSuchEntityException;
use Sportal\FootballApi\Application\IDto;
use Sportal\FootballApi\Application\IService;
use Sportal\FootballApi\Application\President\Input;
use Sportal\FootballApi\Application\President\Output;
use Sportal\FootballApi\Domain\President\IPresidentEntityFactory;
use Sportal\FootballApi\Domain\President\IPresidentModel;
use Sportal\FootballApi\Domain\President\IPresidentRepository;
use Sportal\FootballApi\Infrastructure\AOP\AttachAssets;
use Sportal\FootballApi\Application\AOP\AttachEventNotification;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationEntityType;
use Sportal\FootballApi\Domain\EventNotification\EventNotificationOperationType;


class Update implements IService
{
    private IPresidentModel $presidentModel;
    private IPresidentEntityFactory $presidentFactory;
    private IPresidentRepository $presidentRepository;
    private Input\Update\Mapper $inputMapper;
    private Output\Profile\Mapper $outputMapper;

    /**
     * Update constructor.
     * @param IPresidentModel $presidentModel
     * @param IPresidentEntityFactory $presidentFactory
     * @param IPresidentRepository $presidentRepository
     * @param Input\Update\Mapper $inputMapper
     * @param Output\Profile\Mapper $outputMapper
     */
    public function __construct(IPresidentModel $presidentModel, IPresidentEntityFactory $presidentFactory, IPresidentRepository $presidentRepository, Input\Update\Mapper $inputMapper, Output\Profile\Mapper $outputMapper)
    {
        $this->presidentModel = $presidentModel;
        $this->presidentFactory = $presidentFactory;
        $this->presidentRepository = $presidentRepository;
        $this->inputMapper = $inputMapper;
        $this->outputMapper = $outputMapper;
    }

    /**
     * @AttachAssets
     * @AttachEventNotification(object=EventNotificationEntityType::PRESIDENT,operation=EventNotificationOperationType::UPDATE)
     * @param IDto $inputDto
     * @return Output\Profile\Dto
     * @throws DuplicateKeyException
     * @throws NoSuchEntityException
     */
    public function process(IDto $inputDto): Output\Profile\Dto
    {
        /** @var Input\Update\Dto $inputDto */
        if (!$this->presidentRepository->exists($inputDto->getId())) {
            throw new NoSuchEntityException('president_id ' . $inputDto->getId());
        }

        $duplicateEntity = $this->presidentRepository->findByName($inputDto->getName());
        if (!is_null($duplicateEntity) && $duplicateEntity->getId() != $inputDto->getId()) {
            throw new DuplicateKeyException('Duplicate key (name).');
        }

        $presidentEntity = $this->presidentFactory
            ->setEntity($this->inputMapper->map($inputDto))
            ->setId($inputDto->getId())
            ->create();

        $presidentEntity = $this->presidentModel->setPresident($presidentEntity)
            ->update()
            ->searchUpdate()
            ->blacklist()
            ->getPresident();

        return $this->outputMapper->map($presidentEntity);
    }

}