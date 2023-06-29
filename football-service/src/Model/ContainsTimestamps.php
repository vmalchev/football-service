<?php
namespace Sportal\FootballApi\Model;

trait ContainsTimestamps
{

    /**
     *
     * @var \DateTime[]
     */
    protected $timestamps;

    /**
     *
     * @param \DateTime[] $data
     */
    public function setTimestamps(array $data)
    {
        $names = $this->getTimestampNames();
        foreach ($names as $key) {
            if (! empty($data[$key])) {
                $this->timestamps[$key] = new \DateTime($data[$key]);
            }
        }
    }

    public function getTimestamps()
    {
        $data = [];
        if ($this->timestamps !== null) {
            foreach ($this->timestamps as $key => $timestamp) {
                $data[$key] = $timestamp->format(\DateTime::ATOM);
            }
        }
        
        return $data;
    }

    /**
     *
     * @return array
     */
    public function getPersistanceTimestamps()
    {
        $data = [];
        $names = $this->getTimestampNames();
        foreach ($names as $key) {
            $data[$key] = isset($this->timestamps[$key]) ? $this->timestamps[$key]->format("Y-m-d H:i:s") : null;
        }
        return $data;
    }

    /**
     * @return array
     */
    abstract public function getTimestampNames();
}