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
        if (!($this->createdAt instanceof \DateTime)) {
            $this->createdAt = new \DateTime($this->createdAt);
        }
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
        if (!($this->updatedAt instanceof \DateTime)) {
            $this->updatedAt = new \DateTime($this->updatedAt);
        }
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
        $this->updatedAt = new \DateTime();
        $eventsManager = new EventsManager();

        $eventsManager->attach(
            'model:beforeSave',
            function (Event $event, AbstractModel $model) {
                if ($model->getCreatedAt() instanceof \DateTime) {
                    $model->createdAt = date('Y-m-d H:i:s', $model->getCreatedAt()->getTimestamp());
                }
                if ($model->getUpdatedAt() instanceof \DateTime) {
                    $model->updatedAt = date('Y-m-d H:i:s', $model->getUpdatedAt()->getTimestamp());
                }
            }
        );
        $eventsManager->attach(
            'model:afterSave',
            function (Event $event, AbstractModel $model) {
                if (!($this->createdAt instanceof \DateTime)) {
                    $model->createdAt = new \DateTime($model->createdAt);
                }
                if (!is_object($this->getUpdatedAt())) {
                    $model->updatedAt = new \DateTime($model->createdAt);
                }
            }
        );

        $eventsManager->attach(
            'model:afterFetch',
            function (Event $event, AbstractModel $model) {
                if (!($this->createdAt instanceof \DateTime)) {
                    $model->createdAt = new \DateTime($model->createdAt);
                }
                if (!is_object($this->getUpdatedAt())) {
                    $model->updatedAt = new \DateTime($model->createdAt);
                }
            }
        );

        $this->setEventsManager($eventsManager);
    }
}
