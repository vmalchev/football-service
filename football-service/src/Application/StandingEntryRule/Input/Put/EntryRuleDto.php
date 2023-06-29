<?php


namespace Sportal\FootballApi\Application\StandingEntryRule\Input\Put;


use App\Validation\Identifier;
use Swagger\Annotations as SWG;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @SWG\Definition(definition="v2_StandingEntryRuleInput")
 */
class EntryRuleDto
{
    /**
     * @SWG\Property(property="standing_rule_id")
     * @var string
     */
    private string $standing_rule_id;

    /**
     * @SWG\Property(property="rank")
     * @var int
     */
    private int $rank;

    /**
     * Dto constructor.
     * @param string $standing_rule_id
     * @param int $rank
     */
    public function __construct(string $standing_rule_id, int $rank)
    {
        $this->standing_rule_id = $standing_rule_id;
        $this->rank = $rank;
    }

    /**
     * @return string
     */
    public function getStandingRuleId(): string
    {
        return $this->standing_rule_id;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    public static function getValidatorConstraints(): Assert\Collection
    {
        return new Assert\Collection(
            [
                'standing_rule_id' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => ['digit', 'numeric']]),
                    new Identifier(),
                ],
                'rank' => [
                    new Assert\NotBlank(),
                    new Assert\Type('int'),
                    new Assert\GreaterThanOrEqual(1)
                ]
            ]
        );
    }
}