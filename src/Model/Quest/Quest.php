<?php

namespace App\Model\Quest;

class Quest{

    private QuestType $type;

    public function __construct(
        private string $desc,
        
    ) {
        switch($desc)
        {
            case "Bring item":
                $type = QuestType::BRING;
                break;
            case "Fetch item":
                $type = QuestType::FETCH;
                break;
            case "Get stolen item":
                $type = QuestType::STOLEN_ITEM;
                break;
            case "Save kidnapped NPC":
                $type = QuestType::SAVE_NPC;
                break;
            case "Kill monster":
                $type = QuestType::KILL_ENEMIE;
                break;
            case "Retrive object":
                $type = QuestType::RETRIVE_OBJECT;
                break;
        }
    }

    public function getDesc(): string
    {
        return $this->desc;
    }
}

?>