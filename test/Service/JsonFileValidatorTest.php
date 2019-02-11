<?php

namespace De\Idrinth\ConfigCheck\Test\Service;

use De\Idrinth\ConfigCheck\Service\JsonFileValidator;
use JsonSchema\Validator;

class JsonFileValidatorTest extends FileValidatorTest
{
    /**
     * @return JsonFileValidator
     */
    protected function getInstance()
    {
        return new JsonFileValidator($this->getSchemaStoreMock(), $this->getMockBuilder(Validator::class)->getMock());
    }

    /**
     * @return void
     */
    public function testCheckUnparsableJson()
    {
        $file = $this->getValidFileMock("broken");
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(
            1,
            $return,
            "there were less messages returned than expected"
        );
        $this->assertInstanceOf(
            'De\Idrinth\ConfigCheck\Message\ErrorMessage',
            $return[0],
            "broken files are not considered errors"
        );
    }

    /**
     * @return void
     */
    public function testCheckNoObjectJson()
    {
        $file = $this->getValidFileMock("[\"\"]");
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(
            1,
            $return,
            "there were less messages returned than expected"
        );
        var_dump($return);
        $this->assertInstanceOf(
            'De\Idrinth\ConfigCheck\Message\NoticeMessage',
            $return[0],
            "Json not being an object is not a notice"
        );
    }

    /**
     * @return void
     */
    public function testCheckNoSchemaJson()
    {
        $file = $this->getValidFileMock("{\"\":\"\"}");
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(
            1,
            $return,
            "there were less messages returned than expected"
        );
        $this->assertInstanceOf(
            'De\Idrinth\ConfigCheck\Message\NoticeMessage',
            $return[0],
            "Lack of schema is not a notice"
        );
    }

    /**
     * @return void
     */
    public function testCheckSchemaJson()
    {
        $file = $this->getValidFileMock("{\"\$schema\":\"#id\"}");
        $instance = $this->getInstance();
        $return = $instance->check($file);
        $this->assertCount(
            0,
            $return,
            "there were more messages returned than expected"
        );
    }
}
