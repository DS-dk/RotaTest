<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RotaTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function test_postData(){
        //Just Add the Same Day Date in both case (One Employee/Multiple Employee)
        $multipleEmployee = array([
            'Staff Name' => 'Black Widow',
            'shiftStartTime' => '2022-11-14 09:00:00',
            'shiftEndTime' => '2022-11-14 15:00:00'
        ],[
            'Staff Name' => 'Gammora',
            'shiftStartTime' => '2022-11-14 12:00:00',
            'shiftEndTime' => '2022-11-14 15:00:00'
        ]);
        $oneEmployee = array([
            'Staff Name' => 'Black Widow',
            'shiftStartTime' => '2022-11-14 09:00:00',
            'shiftEndTime' => '2022-11-14 15:00:00'
        ]);

        $response = $this->post('/postData',$oneEmployee);
        $response->dd();
    }
}
