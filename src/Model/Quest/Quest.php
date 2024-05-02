<?php

namespace App\Model\Quest;

class Quest{

    public function __construct(
        private string $desc,
    ) {
    }

    public function getDesc(): string
    {
        return $this->desc;
    }
}

?>