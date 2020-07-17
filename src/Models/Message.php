<?php

declare(strict_types=1);

namespace App\Models;


use DateTime;

class Message
{
    private $text;
    private $date;

    /**
     * @param mixed $date
     * @return Message
     */
    public function setDate(DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @param string $text
     * @return Message
     */
    public function setText(string $text): Message
    {
        $this->text = $text;
        return $this;
    }
}