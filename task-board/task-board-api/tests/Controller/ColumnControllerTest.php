<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ColumnControllerTest extends WebTestCase
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
    private function createColumn(KernelBrowser $client): int
    {
        $projectId = $this->createProject($client);
        $client->request('POST', '/api/columns', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'Test Column',
                'position' => 1,
                'project' => $projectId
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        return $data['id'];
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/columns');
        self::assertResponseIsSuccessful();
    }

    public function testCreate(): void
    {
        $client = static::createClient();
        $projectId = $this->createProject($client);
        $client->request('POST', '/api/columns', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Test Column', 'position' => 1, 'project' => $projectId])
        );
        self::assertResponseIsSuccessful();
    }

    public function testShow(): void
    {
        $client = static::createClient();
        $id = $this->createColumn($client);
        $client->request('GET', '/api/columns/' . $id);
        self::assertResponseIsSuccessful();
    }

    public function testUpdate(): void
    {
        $client = static::createClient();
        $id = $this->createColumn($client);
        $projectId = $this->createProject($client);
        $client->request('PUT', '/api/columns/' . $id, [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Updated Column', 'position' => 2, 'project' => $projectId])
        );
        self::assertResponseIsSuccessful();
    }

    public function testDelete(): void
    {
        $client = static::createClient();
        $id = $this->createColumn($client);
        $client->request('DELETE', '/api/columns/' . $id);
        self::assertResponseIsSuccessful();
    }

    public function testValidateCreate(): void
    {
        $client = static::createClient();
        $projectId = $this->createProject($client);
        $client->request('POST', '/api/columns', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => '', 'position' => 1, 'project' => $projectId])
        );
        self::assertResponseStatusCodeSame(400);
    }
}
