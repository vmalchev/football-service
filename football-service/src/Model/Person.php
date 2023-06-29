<?php
namespace Sportal\FootballApi\Model;

use Swagger\Annotations as SWG;

abstract class Person extends PartialPerson implements SurrogateKeyInterface
{

    /**
     * Person's nationality
     * @var \Sportal\FootballApi\Model\Country
     * @SWG\Property()
     */
    private $country;

    /**
     * Date when the person was born
     * @var \DateTime
     * @SWG\Property(type="string", format="date", example="1985-02-05")
     */
    private $birthdate;

    /**
     * Human readable first(given) name
     * @var string
     * @SWG\Property(example="Cristiano", property="first_name")
     */
    private $firstName;

    /**
     * Human readable last(family) name
     * @var string
     * @SWG\Property(example="Ronaldo", property="last_name")
     */
    private $lastName;

    /**
     * True if currently active, false if retired
     * @var boolean
     * @SWG\Property()
     */
    private $active;

    /**
     * Person's gender
     * @var string
     * @SWG\Property(property="gender", enum=PERSON_GENDER)
     */
    private $gender;

    /**
     * Set birthdate
     *
     * @param \DateTime $birthdate
     *
     * @return Person
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;
        
        return $this;
    }

    /**
     * Get birthdate
     *
     * @return \DateTime
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Person
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Person
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Person
     */
    public function setActive($active)
    {
        $this->active = $active;
        
        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set country
     *
     * @param \Sportal\FootballApi\Model\Country $country
     *
     * @return Person
     */
    public function setCountry(\Sportal\FootballApi\Model\Country $country)
    {
        $this->country = $country;
        
        return $this;
    }

    /**
     * Get country
     *
     * @return \Sportal\FootballApi\Model\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $gender
     * @return $this
     */
    public function setGender(string $gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPersistanceMap()
     */
    public function getPersistanceMap()
    {
        return [
            'name' => $this->getName(),
            'country_id' => $this->country->getId(),
            'birthdate' => isset($this->birthdate)? $this->birthdate->format('Y-m-d') : null,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'active' => $this->active,
            'gender' => $this->gender
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Sportal\FootballApi\Model\ModelInterface::getPrimaryKeyMap()
     */
    public function getPrimaryKeyMap()
    {
        return [
            'id' => $this->getId()
        ];
    }

    /**
     * {@inheritDoc}
     * @see JsonSerializable::jsonSerialize()
     */
    public function jsonSerialize()
    {
        $data = [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'country' => $this->country,
            'gender' => $this->gender
        ];
        
        $data = $this->addAssetUrls($data);
        
        if ($this->birthdate !== null) {
            $data['birthdate'] = $this->birthdate->format('Y-m-d');
        }
        if ($this->firstName !== null) {
            $data['first_name'] = $this->firstName;
        }
        if ($this->lastName !== null) {
            $data['last_name'] = $this->lastName;
        }
        if ($this->active !== null) {
            $data['active'] = $this->active;
        }
        
        return $this->translateContent($data);
    }

    public function getMlContentModels()
    {
        return [
            $this,
            $this->country
        ];
    }
}

