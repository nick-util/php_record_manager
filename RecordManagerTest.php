<?php
use PHPUnit\Framework\TestCase;

require_once "./RecordManager.php";

class RecordManagerTest extends TestCase
{
    private $manager;

    protected function setUp()
    {
        $this->manager = new RecordManager();
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

        $results = $this->manager->getColumnValues('car', $array);
        $this->assertEquals($results, $truth);
    }

    public function testgetColumnValuesUnique()
    {
        $array = [
            ["car" => "chevy", "fruit"=> "apple", "vegetable" =>"kale"],
            ["car"=>"chevy", "fruit"=>"pear", "vegetable" => "kale"],
            ["car" => "bmw", "fruit"=> "pear", "vegetable" =>"corn"],
            ["car"=>"jeep", "fruit"=>"orange", "vegetable" => "kale"]
        ];

        $truth = [
            'chevy',
            'bmw',
            'jeep'
        ];

        $results = $this->manager->getColumnValuesUnique('car', $array);
        $this->assertEquals($results, $truth);

        $truth = [
            'apple',
            'pear',
            'orange'
        ];

        $results = $this->manager->getColumnValuesUnique('fruit', $array);
        $this->assertEquals($results, $truth);

        $truth = [
            'kale',
            'corn'
        ];

        $results = $this->manager->getColumnValuesUnique('vegetable', $array);
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
        echo var_dump($results);
        echo var_dump($truth);
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
        echo var_dump($results);
        //echo var_dump($truth);
        $this->assertEquals($results, $truth);
    }

}
