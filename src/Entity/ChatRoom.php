<?php


namespace App\Entity;

class ChatRoom
{
    /**
     * @var string|null
     */
    private $currentState;

    /**
     * @return string|null
     */
    public function getCurrentState(): ?string
    {
        return $this->currentState;
    }

    /**
     * @param string|null $currentState
     * @return ChatRoom
     */
    public function setCurrentState(?string $currentState): ChatRoom
    {
        $this->currentState = $currentState;
        return $this;
    }
}
