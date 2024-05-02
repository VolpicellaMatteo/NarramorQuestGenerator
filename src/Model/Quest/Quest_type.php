<?php

namespace App\Model\Quest;

use App\Model\Quest\Quest;

class Quest_type{

    public static function getAllQuestType() : array{
        return [
            new Quest('Bring item'),
            new Quest('Fetch item'),
            new Quest('Get stolen item'),
            new Quest('Save kidnapped NPC'),
            new Quest('Kill monster'),
            new Quest('Retrive object')
        ];
    }
}

?>