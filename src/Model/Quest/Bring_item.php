<?php

namespace App\Model\Quest;
use App\Service\DatabaseService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class Bring_item
{
    private $idplayer;
    private $idnpc;
    private $language;


    public function __construct($player, $npc, $language)
    {
        $this->idplayer = $player;
        $this->idnpc = $npc;
        $this->language = $language;
    }


    public function generateQuest(DatabaseService $databaseService)
    {

        //prendo item random compatibile con l'organizzazione del'npc
        $npcOrg = $databaseService->getNpcOrg($this->idnpc);
        $item = $databaseService->getNpcCompatibleItemBringItem($npcOrg,$this->idplayer);
        $questReciver = $databaseService->getQuestReciver($npcOrg);
        $qReciverOrg = $databaseService->getNpcOrg($questReciver["id"]);
        $qReciverRoom = $databaseService->getQReciverRoom(strtolower($qReciverOrg),$this->idplayer);
        $why = $databaseService->getBringItemWhy($item['id']);
        
        

        $paramString = "Tipo di quest: Bring item  
            L '\''item si chiama  {$item['title']} 
            è di type {$item['type']} 
            e il suo subtype è {$item['subType']} 
            che si chiama {$questReciver['title']} 
            che fa parte dell'\''organizzazione {$questReciver['organization']} 
            Il quest reciver si trova nella zona {$questReciver['title']} 
            controllata dalla fazione {$qReciverRoom['faction']} 
            si trova nel place {$qReciverRoom['place']} 
            nel building {$qReciverRoom['building']} 
            Il motivo della missione è il seguente: {$why['why']}";
        
        $prompt = $this->getGPTquest($paramString);

        $params = [
            'item'=> $item,
            'quest_reciver' => $questReciver,
            'qreciver_room' => $qReciverRoom,
            'why' => $why,
            'questBot'=> $prompt['a']
        ];

        return $params;

    }

    public function getGPTquest($params) {

        $params = str_replace(array("\r", "\n"), '', $params);
    
        $prompt = " curl -X POST https://bankchat.accomazzi.net/rpg/q \
            -H \"Content-Type: application/json\" \
            -d '{
                \"q\": \"$params\",
                \"sid\": \"123\"
            }'
        ";
    
        $response = shell_exec($prompt);
    
        $data = json_decode($response, true);

        // Controlla se la decodifica è riuscita correttamente
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            die("Errore nella decodifica del JSON");
        }
        
        // Converte l'array associativo PHP in una stringa PHP formattata
        //$stringaPHP = var_export($data, true); 
       
        return $data;
    }
    
}






    // function arrayToString($array, $prefix = '') {
        //     $result = [];
        //     foreach ($array as $key => $value) {
        //         if (is_array($value)) {
        //             $result[] = arrayToString($value, $prefix . $key . '.');
        //         } else {
        //             $result[] = $prefix . $key . ' = ' . $value;
        //         }
        //     }
        //     return implode(' | ', $result);
        // }
        
        // // Costruisci la stringa con tutti i parametri
        // $paramString = arrayToString($params);

        // dump($paramString);