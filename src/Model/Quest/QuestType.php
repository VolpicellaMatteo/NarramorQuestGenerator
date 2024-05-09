<?php

namespace App\Model\Quest;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

enum QuestType : string
{
    case BRING = "Bring item";
    case FETCH = "Fetch item";
    case STOLEN_ITEM = "Get The Stolen Item";
    case SAVE_NPC = "Save the Kidnapped NPC";
    case KILL_ENEMIE = "Kill monster";
    case RETRIVE_OBJECT = "Retrive object";
      
}
