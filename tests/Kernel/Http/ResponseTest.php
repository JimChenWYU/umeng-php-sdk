<?php

namespace EasyUmeng\Tests\Kernel\Http;

use EasyUmeng\Kernel\Http\Response;
use EasyUmeng\Kernel\Support\Collection;
use EasyUmeng\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testBasicFeatures()
    {
        $response = new Response(200, ['content-type:application/json'], '{"name": "easyumeng"}');

        self::assertInstanceOf(\GuzzleHttp\Psr7\Response::class, $response);

        self::assertSame('{"name": "easyumeng"}', (string) $response);
        self::assertSame('{"name": "easyumeng"}', $response->getBodyContents());
        self::assertSame('{"name":"easyumeng"}', $response->toJson());
        self::assertSame(['name' => 'easyumeng'], $response->toArray());
        self::assertSame('easyumeng', $response->toObject()->name);
        self::assertInstanceOf(Collection::class, $response->toCollection());
        self::assertSame(['name' => 'easyumeng'], $response->toCollection()->all());
    }

    public function testInvalidArrayableContents()
    {
        $response = new Response(200, [], 'not json string');

        self::assertInstanceOf(\GuzzleHttp\Psr7\Response::class, $response);

        self::assertSame([], $response->toArray());

        // #1291
        $json = "{\"name\":\"小明\x09了死烧部全们你把并\"}";
        \json_decode($json, true);
        self::assertSame(\JSON_ERROR_CTRL_CHAR, \json_last_error());

        $response = new Response(200, ['Content-Type' => ['application/json']], $json);
        self::assertInstanceOf(\GuzzleHttp\Psr7\Response::class, $response);
        self::assertSame(['name' => '小明了死烧部全们你把并'], $response->toArray());
    }
}
