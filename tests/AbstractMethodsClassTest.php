<?php

namespace Imanghafoori\LaravelMicroscope\Tests;

use function array_filter;
use function file_get_contents;
use Imanghafoori\LaravelMicroscope\Analyzers\ClassMethods;
use Imanghafoori\LaravelMicroscope\LaravelMicroscopeServiceProvider;
use function is_array;
use Orchestra\Testbench\TestCase;
use function substr;
use function token_get_all;

class AbstractMethodsClassTest extends TestCase
{
    /** @var array */
    private $classToken;

    protected function setUp(): void
    {
        parent::setUp();
        $this->classToken = ClassMethods::read($this->getTokens());
    }

    protected function getPackageProviders($app)
    {
        return [LaravelMicroscopeServiceProvider::class];
    }

    /** @test */
    public function check_is_abstract_method_test()
    {
        $class = $this->classToken;
        // Checks all the methods are abstract
        $this->assertTrue($class['methods'][0]['is_abstract']);
        $this->assertTrue($class['methods'][1]['is_abstract']);
        $this->assertTrue($class['methods'][2]['is_abstract']);
        $this->assertTrue($class['methods'][3]['is_abstract']);
        $this->assertTrue($class['methods'][4]['is_abstract']);
        $this->assertTrue($class['methods'][5]['is_abstract']);
        $this->assertTrue($class['methods'][6]['is_abstract']);
        $this->assertTrue($class['methods'][8]['is_abstract']);
        $this->assertTrue($class['methods'][9]['is_abstract']);
        $this->assertTrue($class['methods'][10]['is_abstract']);
        $this->assertTrue($class['methods'][11]['is_abstract']);
        $this->assertTrue($class['methods'][12]['is_abstract']);
        $this->assertTrue($class['methods'][13]['is_abstract']);
        $this->assertTrue($class['methods'][14]['is_abstract']);
        $this->assertTrue($class['methods'][15]['is_abstract']);
        $this->assertTrue($class['methods'][16]['is_abstract']);
        $this->assertTrue($class['methods'][17]['is_abstract']);
        $this->assertTrue($class['methods'][18]['is_abstract']);
        $this->assertTrue($class['methods'][19]['is_abstract']);
        $this->assertTrue($class['methods'][20]['is_abstract']);
        $this->assertTrue($class['methods'][21]['is_abstract']);
        $this->assertTrue($class['methods'][22]['is_abstract']);
        $this->assertTrue($class['methods'][23]['is_abstract']);
        $this->assertTrue($class['methods'][24]['is_abstract']);
        $this->assertTrue($class['methods'][25]['is_abstract']);
        $this->assertFalse($class['methods'][26]['is_abstract']);
    }

    /** @test */
    public function check_return_types_test()
    {
        $class = $this->classToken;
        //check is nullable return types
        $this->assertNull($class['methods'][0]['nullable_return_type']);
        $this->assertFalse($class['methods'][6]['nullable_return_type']);
        $this->assertTrue($class['methods'][13]['nullable_return_type']);

        $this->assertNull($class['methods'][0]['returnType']);
        $this->assertEquals( 'test', $class['methods'][6]['returnType'][1] );
        $this->assertEquals( 'string', $class['methods'][7]['returnType'][1] );
        $this->assertEquals( 'bool', $class['methods'][8]['returnType'][1] );
        $this->assertEquals( 'int', $class['methods'][9]['returnType'][1] );
        $this->assertEquals( 'array', $class['methods'][10]['returnType'][1] );
        $this->assertEquals( 'void', $class['methods'][11]['returnType'][1] );
        $this->assertEquals( 'float', $class['methods'][12]['returnType'][1] );
        $this->assertEquals( 'string', $class['methods'][13]['returnType'][1] );
    }

    /** @test
     * @dataProvider checkVisibility
     */
    public function check_visibility_test($index, $visibility)
    {
        $class = $this->classToken;
        $this->assertEquals($class['methods'][$index]['visibility'][1], $visibility);
    }

    public function checkVisibility(): array
    {
        return [
            [0, 'public'],
            [1, 'public'],
            [2, 'protected'],
            [3, 'public'],
            [4, 'public'],
            [5, 'protected'],
            [6, 'public'],
            [7, 'public'],
            [8, 'public'],
            [9, 'public'],
            [22, 'public'],
            [23, 'public'],
            [24, 'protected'],
            [25, 'public'],
        ];
    }

    /** @test */
    public function check_is_static_method_test()
    {
        $class = $this->classToken;
        $this->assertTrue($class['methods'][3]['is_static']);
        $this->assertTrue($class['methods'][4]['is_static']);
        $this->assertTrue($class['methods'][5]['is_static']);
        $this->assertTrue($class['methods'][25]['is_static']);
    }

    /** @test  */
    public function abstract_class_general_body_test()
    {
        $class = $this->classToken;
        $this->assertEquals( [311, 'abstract_sample', 7], $class['name'] );
        $this->assertCount(27, $class['methods']);
        $this->assertTrue($class['is_abstract']);
        $this->assertEquals( 364, $class['type'] );
    }

    /** @test */
    public function check_parameter_methods()
    {
        $class = $this->classToken;
        // check function has parameter
        $this->assertEquals( '$parameter1', $class['methods'][14]['signature'][0][1] );
        // check nullable type cast method parameters
        $this->assertEquals( '?', $class['methods'][15]['signature'][0] );
        $this->assertEquals( 'int', $class['methods'][15]['signature'][1][1] );
        $this->assertEquals( '$parameter1', $class['methods'][15]['signature'][3][1] );
        // check type casting of parameters
        $this->assertEquals( 'int', $class['methods'][16]['signature'][0][1] );
        // number of parameter
        $signatures = $class['methods'][17]['signature'];
        $parameters = array_filter($signatures, function ($item) {
            if (is_array($item) && substr($item[1], 0, 1) == '$') {
                return true;
            }

            return false;
        });
        $this->assertCount(3, $parameters);
        // check multi parameter with type
        $this->assertEquals( '...', $class['methods'][18]['signature'][0][1] );
        $this->assertEquals( '$parameter2', $class['methods'][18]['signature'][1][1] );
        // check multi parameter with type casting
        $this->assertEquals( 'string', $class['methods'][19]['signature'][0][1] );
        $this->assertEquals( '...', $class['methods'][19]['signature'][2][1] );
        $this->assertEquals( '$parameter1', $class['methods'][19]['signature'][3][1] );
        // check method with nullable multi parameter
        $this->assertEquals( '?', $class['methods'][20]['signature'][0] );
        $this->assertEquals( 'string', $class['methods'][20]['signature'][1][1] );
        $this->assertEquals( '...', $class['methods'][20]['signature'][3][1] );
        $this->assertEquals( '$parameter1', $class['methods'][20]['signature'][4][1] );
        // check default value of parameters
        $this->assertEquals( '$parameter1', $class['methods'][21]['signature'][0][1] );
        $this->assertEquals( '=', $class['methods'][21]['signature'][2] );
        $this->assertEquals( 'null', $class['methods'][21]['signature'][4][1] );
    }

    /**
     * get tokens of stubs classes.
     *
     * @return array
     */
    private function getTokens()
    {
        return token_get_all(file_get_contents(__DIR__.'/stubs/abstract_sample_class.php'));
    }
}