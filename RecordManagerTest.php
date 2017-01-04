<?php
/*
* Testing namespace is defined locally on phpunit install
* To run tests: "phpunit --verbose --debug RecordManagerTest.php"
*/
use PHPUnit\Framework\TestCase;
require_once "./RecordManager.php";

class RecordManagerTest extends TestCase
{
    private $manager;

    protected function setUp()
    {
        $this->manager = new RecordManager();
    }


    public function testDePluck(){
        $array = [
            ["car" => "chevy", "fruit"=> "apple", "vegetable" =>"spinach"],
            ["car"=>"ford", "fruit"=>"pear", "vegetable" => "kale"],
            ["car" => "bmw", "fruit"=> "banana", "vegetable" =>"corn"],
            ["car"=>"jeep", "fruit"=>"orange", "vegetable" => "peas"]
        ];

        $truth = [
            ["fruit"=> "apple", "vegetable" =>"spinach"],
            ["fruit"=>"pear", "vegetable" => "kale"],
            ["fruit"=> "banana", "vegetable" =>"corn"],
            ["fruit"=>"orange", "vegetable" => "peas"]
        ];



        $results = $this->manager->dePluck($array, 'car');
        echo var_dump($results);
        $this->assertEquals($results, $truth);
    }


    public function testDePlucks(){
        $array = [
            ["car" => "chevy", "fruit"=> "apple", "vegetable" =>"spinach"],
            ["car"=>"ford", "fruit"=>"pear", "vegetable" => "kale"],
            ["car" => "bmw", "fruit"=> "banana", "vegetable" =>"corn"],
            ["car"=>"jeep", "fruit"=>"orange", "vegetable" => "peas"]
        ];

        $truth = [
            ["fruit"=> "apple"],
            ["fruit"=>"pear"],
            ["fruit"=> "banana"],
            ["fruit"=>"orange"]
        ];



        $results = $this->manager->dePlucks($array, ['car', 'vegetable']);
        echo var_dump($results);
        $this->assertEquals($results, $truth);
    }

    public function testGetColumnValues()
    {
        $array = [
            ["car" => "chevy", "fruit"=> "apple", "vegetable" =>"spinach"],
            ["car"=>"ford", "fruit"=>"pear", "vegetable" => "kale"],
            ["car" => "bmw", "fruit"=> "banana", "vegetable" =>"corn"],
            ["car"=>"jeep", "fruit"=>"orange", "vegetable" => "peas"]
        ];

        $truth = [
            'chevy',
            'ford',
            'bmw',
            'jeep'
        ];

        $results = $this->manager->getArrayColumnValues('car', $array);
        $this->assertEquals($results, $truth);
    }

    public function testgetColumnValuesUnique()
    {
        $array = [
            ["car" => "chevy", "fruit"=> "apple", "vegetable" =>"kale"],
            ["car"=>"chevy", "fruit"=>"pear", "vegetable" => NULL],
            ["car" => "bmw", "fruit"=> "pear", "vegetable" =>"corn"],
            ["car"=>"jeep", "fruit"=>"orange", "vegetable" => "kale"]
        ];

        $truth = [
            'chevy',
            'bmw',
            'jeep'
        ];

        $results = $this->manager->getArrayColumnValuesUnique('car', $array);
        $this->assertEquals($results, $truth);

        $truth = [
            'apple',
            'pear',
            'orange'
        ];

        $results = $this->manager->getArrayColumnValuesUnique('fruit', $array);
        $this->assertEquals($results, $truth);

        $truth = [
            'kale',
            'corn'
        ];

        $results = $this->manager->getArrayColumnValuesUnique('vegetable', $array);
        $this->assertEquals($results, $truth);
    }

    public function testMakeArray2DUnique()
    {
        $array = [
            ["car" => "chevy", "fruit"=> "apple", "vegetable" =>"kale"],
            ["car"=>"chevy", "fruit"=>"pear", "vegetable" => "kale"],
            ["car" => "bmw", "fruit"=> "pear", "vegetable" =>"corn"],
            ["car"=>"jeep", "fruit"=>"orange", "vegetable" => "kale"]
        ];

        $truth = [
            "car" => ["chevy", "bmw", "jeep"],
            "fruit"=>["apple","pear", "orange"],
            "vegetable" =>["kale", "corn"]
        ];

        $results = $this->manager->makeArray2DUnique($array);
        $this->assertEquals($results, $truth);
    }

    public function testSelectArrayRows()
    {
        $array = [
            ["car" => "chevy", "fruit"=> "apple", "vegetable" =>"kale"],
            ["car"=>"chevy", "fruit"=>"pear", "vegetable" => "kale"],
            ["car" => "bmw", "fruit"=> "pear", "vegetable" =>"corn"],
            ["car"=>"jeep", "fruit"=>"orange", "vegetable" => "kale"]
        ];

        $truth = [
            ["car" => "chevy", "fruit"=> "apple", "vegetable" =>"kale"],
            ["car"=>"chevy", "fruit"=>"pear", "vegetable" => "kale"]
        ];

        $results = $this->manager->selectArrayRows('car', 'chevy', $array);
        $this->assertEquals($results, $truth);
    }

    public function testSplitOnKey()
    {
        $array = [
            ["car" => "chevy", "fruit"=> "apple", "vegetable" =>"kale"],
            ["car"=>"chevy", "fruit"=>"pear", "vegetable" => "kale"],
            ["car" => "bmw", "fruit"=> "pear", "vegetable" =>"corn"],
            ["car"=>"jeep", "fruit"=>"orange", "vegetable" => "kale"]
        ];

          $truth = [
            'chevy' => [
                ["car" => "chevy", "fruit"=> "apple", "vegetable" =>"kale"],
                ["car"=>"chevy", "fruit"=>"pear", "vegetable" => "kale"],
            ],
            'bmw' =>  [
                ["car" => "bmw", "fruit"=> "pear", "vegetable" =>"corn"]
            ],
            'jeep' => [
                ["car"=>"jeep", "fruit"=>"orange", "vegetable" => "kale"]
            ]
        ];

        $results = $this->manager->splitOnKey('car', $array);
        $this->assertEquals($results, $truth);
    }



    public function testSplitOnKeyUnique()
    {
        $array = [
            ["car" => "chevy", "fruit"=> "apple", "vegetable" =>"kale"],
            ["car"=>"chevy", "fruit"=>"pear", "vegetable" => "kale"],
            ["car" => "bmw", "fruit"=> "pear", "vegetable" =>"corn"],
            ["car"=>"jeep", "fruit"=>"orange", "vegetable" => "kale"]
        ];

        $truth_car = [
            ["car" => ["chevy"], "fruit"=> ["apple","pear"], "vegetable" =>["kale"]],
            ["car" => ["bmw"], "fruit"=> ["pear"], "vegetable" =>["corn"]],
            ["car"=>["jeep"], "fruit"=>["orange"], "vegetable" => ["kale"]]
        ];

        $truth_fruit = [
            ["car" => ["chevy"], "fruit"=> ["apple"], "vegetable" =>["kale"]],
            ["car"=>["chevy", "bmw"], "fruit"=>["pear"], "vegetable" => ["kale","corn"]],
            ["car"=>["jeep"], "fruit"=>["orange"], "vegetable" => ["kale"]]
        ];

        $results = $this->manager->splitOnKeyUnique('car', $array);
        $this->assertEquals($results, $truth_car);

        $results = $this->manager->splitOnKeyUnique('fruit', $array);
        $this->assertEquals($results, $truth_fruit);
    }

    public function testRemoveEmptyValues(){
        $array = ["car" => "chevy", "fruit"=> "", "vegetable" =>"spinach", "test" => "other", "tets2" => " "];
        $truth = ["car" => "chevy", "vegetable" =>"spinach", "test" => "other"];
        $results = $this->manager->removeEmptyValues($array);
        $this->assertEquals($results, $truth);
    }


    public function testRemove2DEmptyValues(){
        $array =[
            ["car" => "chevy", "fruit"=> "", "vegetable" =>"spinach"],
            ["car"=>"ford", "fruit"=>"", "vegetable" => "kale"],
            ["car" => "bmw", "fruit"=> "banana", "vegetable" =>""],
            ["car"=>"", "fruit"=>"orange", "vegetable" => "peas"]
        ];

        $truth = [
            ["car" => "chevy", "vegetable" =>"spinach"],
            ["car"=>"ford", "vegetable" => "kale"],
            ["car" => "bmw", "fruit"=> "banana"],
            ["fruit"=>"orange", "vegetable" => "peas"]
        ];

        $results = $this->manager->remove2DEmptyValues($array);
        $this->assertEquals($results, $truth);
    }

}
