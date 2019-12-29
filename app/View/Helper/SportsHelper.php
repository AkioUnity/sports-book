<?php

App::uses('AppHelper', 'View/Helper');

class SportsHelper extends AppHelper
{
    /**
     * Helper name
     *
     * @var string
     */
    public $name = 'Sports';

    public function name2Icon($id, $feed)
    {
        // TODO: import ID
        $ico = array(
            "OddService"    =>  array(
                6046   =>  "Football",
                621569  =>  "BeachVolleyball",
                687887  =>  "Futsal",
                274791  =>  "RugbyUnion",
                274792  =>  "RugbyLeague",
                154914  =>  "Baseball",
                154830   =>  "Volleyball",
                131506   =>  "AmericanFootball",
                54094   =>  "Tennis",
                48242   =>  "Basketball",
                35709   =>  "Handball",
                35706   =>  "Floorball",
                35232   =>  "IceHockey",
                452674  =>  "Cricket",
                389537  =>  "AustralianRules",
                530129  =>  "Hockey",
                687888  =>  "Horse Racing",
                687889  =>  "Golf",
                687890  =>  "E-Games",
                388764  =>  "Waterpolo",
                307126  =>  "Curling",
                46957   =>  "Bandy",
                154919  =>  "Boxing",
                154923  =>  "Darts",
                165874  =>  "MotorSports",
                261354  =>  "AlpineSkiing",
                262622  =>  "Snooker",
                265917  =>  "TableTennis",
                291987  =>  "EquineSports",
                1149093  =>  "Badminton",
            )
        );

        if (isset($ico[$feed][$id])) {
            return $ico[$feed][$id];
        }

        return null;
    }
}