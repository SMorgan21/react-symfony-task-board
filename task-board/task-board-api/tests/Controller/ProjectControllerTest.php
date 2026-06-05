<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProjectControllerTest extends WebTestCase
{
    private function createProject(KernelBrowser $client): int
    {
        $client->request('POST', '/api/projects', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Test Project', 'description' =>
                'Test'])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        return $data['id'];
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/projects');

        self::assertResponseIsSuccessful();
    }
    public function testCreate(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/projects', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Test Project', 'description' => 'Test'])
        );
        self::assertResponseIsSuccessful();
    }
    public function testDelete(): void
    {
        $client = static::createClient();
        $id = $this->createProject($client);
        $client->request('DELETE', '/api/projects/' . $id);
        self::assertResponseIsSuccessful();
    }
    public function testUpdate(): void
    {
        $client = static::createClient();
        $id = $this->createProject($client);
        $client->request('PUT', '/api/projects/' . $id, [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Updated Project', 'description' => 'Updated'])
        );
        self::assertResponseIsSuccessful();
    }
    public function testShow(): void
    {
        $client = static::createClient();
        $id = $this->createProject($client);
        $client->request('GET', '/api/projects/' . $id);
        self::assertResponseIsSuccessful();
    }

    public function testValidateCreate(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/projects', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => '', 'description' => 'Test'])
        );
        self::assertResponseStatusCodeSame(400);
    }

    public function testValidateUpdate(): void
    {
        $client = static::createClient();
        $id = $this->createProject($client);
        $client->request('PUT', '/api/projects/' . $id, [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => '', 'description' => 'Test'])
        );
        self::assertResponseStatusCodeSame(400);
    }

}
