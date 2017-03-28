<?php
declare(strict_types=1);
namespace JsonApiPhp\JsonApi\Test;

use JsonApiPhp\JsonApi\Document\Document;
use JsonApiPhp\JsonApi\Document\Resource\Relationship\Linkage;
use JsonApiPhp\JsonApi\Document\Resource\Relationship\Relationship;
use JsonApiPhp\JsonApi\Document\Resource\Resource;
use JsonApiPhp\JsonApi\Document\Resource\ResourceId;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    public function testFromTheReadmeFile()
    {
        $json = <<<JSON
{
    "data": {
        "type": "articles",
        "id": "1",
        "attributes": {
            "title": "Rails is Omakase"
        },
        "relationships": {
            "author": {
                "data": {
                    "type": "people",
                    "id": "9"
                },
                "links": {
                    "self": "\/articles\/1\/relationships\/author",
                    "related": "\/articles\/1\/author"
                }
            }
        }
    }
}
JSON;

        $articles = new Resource('articles', '1');
        $author = Relationship::fromLinkage(
            Linkage::fromSingleResourceId(
                new ResourceId('people', '9')
            )
        );
        $author->setLink('self', '/articles/1/relationships/author');
        $author->setLink('related', '/articles/1/author');
        $articles->setRelationship('author', $author);
        $articles->setAttribute('title', 'Rails is Omakase');
        $doc = Document::fromData($articles);

        $this->assertEquals(
            $json,
            json_encode($doc, JSON_PRETTY_PRINT)
        );
    }
}