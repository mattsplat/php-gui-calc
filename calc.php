<?php

require 'vendor/autoload.php';

use Gui\Application;
use Gui\Components\Label;
use Gui\Components\InputText;
use Gui\Components\Button;
use Gui\Components\Shape;

const APP_WIDTH = 480;
const CENTER = APP_WIDTH/2;

$application = new Application([
    'title' => 'PHP Calculator',
    'left' => 30,
    'top' => 40,
    'width' => APP_WIDTH,
    'height' => 400
]);

$application->on('start', function () {
    $title = new Label([
        'text' => 'Calculator',
        'top' => 25,
        'left' => 170,
        'fontSize' => 20,
    ]);


    $display = ${'inputText' . 'Display'} = new InputText([
        'top' => 60,
        'left' => 50,
        'fontSize' => 25,
        'width' => 380,
    ]);

    $body = new Shape([
        'backgroundColor' => '#dadaf1',
        'borderColor' => '#888',
        'left' => 30,
        'top' => 100,
        'width' => 420,
        'height' => 275
    ]);

    $button_list = [];
    $button_map = [
        ['(',')','^', 'CE'],
        [7,8,9, '/'],
        [4,5,6,'*'],
        [1,2,3,'-'],
        ['0','.','=','+']
    ];

    $top_position = 110;
    foreach($button_map as $row) {
        $c = 0;
        $button_width = 400/ count($row);
        var_dump($button_width);

        foreach ($row as $key) {
            $calculatedLeft = ($c++ * $button_width);
            if($key =='') continue;

            ${'button' . ucfirst($key)} = new Button([
                'value' => $key,
                'top' => $top_position,
                'left' => 40 + $calculatedLeft,
                'width' => $button_width,
                'height' => 50,
            ]);

            $button_list[] = [
                'key' => $key,
                'label' => $key,
                'object' => ${'button' . ucfirst($key)}
            ];
        }
        $top_position += 50;
    }


    foreach($button_list as $button){

        switch ($button['key']){

            case '=':
                $button['object']->on('click', function () use ($display){
                    if(strlen($display->getValue()) > 0 ){
                        $calc = new \NXP\MathExecutor();
                        $new_value = $calc->execute($display->getValue());
                        $display->setValue($new_value);
                    }
                });
                break;

            case 'CE':
                $button['object']->on('click', function () use ($display){

                    $display->setValue('');
                });
                break;

            default:
                $button['object']->on('click', function () use ($button, $display){
                    $new_value = $display->getValue() . $button['object']->getValue();
                    $display->setValue($new_value);
                });
        }

    }


});


$application->run();
