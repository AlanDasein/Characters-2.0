<?php

class Characters {

    public $counter;
    public $active;
    public $values;

    public function __construct() {
        $this->counter = 0;
    }

    public function add($param) {
        $index = !isset($param["index"]) ? $this->counter : $param["index"];
        foreach($param["values"] as $k => $v) {
            $this->values[$index][!isset($param["index"]) ? $k : count($this->values[$index])] = array("attribute" => $v["attribute"], "value" => $v["value"], "active" => 1);
        }
        if(!isset($param["index"])) {
            $this->active[$this->counter] = 1;
            $this->counter++;
        }
        if(isset($param["index"])) return count($this->values[$index]) - 1;
    }

    public function update($param) {
        $this->values[$param["index"]][$param["subindex"]][$param["which"]] = $param["value"];
    }

    public function delete($param) {
        if(!isset($param["subindex"])) $this->active[$param["index"]] = 0;
        else $this->values[$param["index"]][$param["subindex"]]["active"] = 0;
    }

    public function recover($param) {
        $stop = !isset($param["index"]) ? $this->counter : 1;
        for($i = 0;$i < $stop;$i++) {
            if(!isset($param["index"])) $this->active[$i] = 1;
            else {
                for($j = 0, $k = count($this->values[$param["index"]]);$j < $k;$j++) $this->values[$param["index"]][$j]["active"] = 1;
            }
        }
    }

    public function reckon($param = array()) {
        $aux = 0;
        $stop = !isset($param["index"]) ? $this->counter : count($this->values[$param["subindex"]]);
        for($i = 0;$i < $stop;$i++) {
            if(!isset($param["index"])) {
                if($this->active[$i] === 1) $aux++;
            }
            else {
                if($this->values[$param["index"]][$i]["active"] === 1) $aux++;
            }
        }
        return $aux;
    }

}