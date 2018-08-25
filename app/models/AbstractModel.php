<?php

use Phalcon\Mvc\Model;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;

/**
 * Class Product
 * @package App\Model
 */
class AbstractModel extends Model
{

    /**
     * @var \DateTime
     */
    public $createdAt;

    /**
     * @var \DateTime
     */
    public $updatedAt;

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Add converting DateTime to string and conversely
     *
     * TODO: Use reflection to autodetect \DateTime field and other types
     */
    protected function initialize()
    {
        $this->createdAt = new \DateTime();
        $eventsManager = new EventsManager();

        $eventsManager->attach(
            'model:beforeSave',
            function (Event $event, AbstractModel $model) {
                if ($model->createdAt instanceof \DateTime) {
                    $model->createdAt = date('Y-m-d H:i:s', $this->createdAt->getTimestamp());
                }
                if ($model->updatedAt instanceof \DateTime) {
                    $model->updatedAt = date('Y-m-d H:i:s', $this->updatedAt->getTimestamp());
                }
            }
        );

        $eventsManager->attach(
            'model:afterFetch',
            function (Event $event, AbstractModel $model) {
                if (!is_object($this->createdAt)) {
                    $model->createdAt = new \DateTime($this->createdAt);
                }
                if (!is_object($this->updatedAt)) {
                    $model->updatedAt = new \DateTime($this->updatedAt);
                }
            }
        );

        $this->setEventsManager($eventsManager);
    }
}
