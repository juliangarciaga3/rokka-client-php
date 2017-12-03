<?php

namespace Core;

use Rokka\Client\Core\Stack;
use Rokka\Client\Core\StackExpression;
use Rokka\Client\Core\StackOperation;

class StackTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function createFromJsonDataProvider()
    {
        $data = '{
          "organization": "testorg",
          "name": "test",
          "created": "2017-06-30T08:42:25+00:00",
          "link": "/stacks/liip/test",
          "stack_operations": [
            {
              "name": "resize",
              "options": {
                "width": 410,
                "height": 410
              }
            },
            {
              "name": "crop",
              "options": {
                "width": 300,
                "height": 300
              }
            }
          ],
          "stack_options": {
            "autoformat": true
          },
          "stack_expressions": [
            {
              "expression": "options.dpr > 2",
              "overrides": {
                "options": {
                  "jpg.quality": 20,
                  "webp.quality": 30
                }
              }
            }
          ]
        }';

        $stack = new Stack('testorg', 'test', [], [], new \DateTime('2017-06-30T08:42:25+00:00'));

        $stack->addStackOption('autoformat', true);
        $stack->addStackOperation(new StackOperation('resize', ['width' => 410, 'height' => 410]));
        $stack->addStackOperation(new StackOperation('crop', ['width' => 300, 'height' => 300]));
        $stack->addStackExpression(new StackExpression('options.dpr > 2', ['jpg.quality' => 20, 'webp.quality' => 30]));

        return ['base' => [$stack, $data, false]];
    }

    /**
     * @dataProvider createFromJsonDataProvider
     *
     * @param $expected
     * @param $data
     * @param bool $isArray
     */
    public function testCreateFromJson($expected, $data, $isArray = false)
    {
        $sourceImage = Stack::createFromJsonResponse($data, $isArray);
        $this->assertEquals($expected, $sourceImage);
    }
}