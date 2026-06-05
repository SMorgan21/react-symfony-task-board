<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TaskControllerTest extends WebTestCase
{
    private function createProject(KernelBrowser $client): int
    {
        $client->request('POST', '/api/projects', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Test Project', 'description' => 'Test'])
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        return $data['id'];
    }

    private function createColumn(KernelBrowser $client): int
    {
        $projectId = $this->createProject($client);
        $client->request('POST', '/api/columns', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['name' => 'Test Column', 'position' => 1, 'project' => $projectId])
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        return $data['id'];
    }

    private function createTask(KernelBrowser $client): int
    {
        $columnId = $this->createColumn($client);
        $client->request('POST', '/api/tasks', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['title' => 'Test Task', 'position' => 1, 'taskColumn' => $columnId])
        );
        $data = json_decode($client->getResponse()->getContent(), true);
        return $data['id'];
    }

    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/tasks');
        self::assertResponseIsSuccessful();
    }

    public function testCreate(): void
    {
        $client = static::createClient();
        $columnId = $this->createColumn($client);
        $client->request('POST', '/api/tasks', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['title' => 'Test Task', 'position' => 1, 'taskColumn' => $columnId])
        );
        self::assertResponseIsSuccessful();
    }

    public function testShow(): void
    {
        $client = static::createClient();
        $id = $this->createTask($client);
        $client->request('GET', '/api/tasks/' . $id);
        self::assertResponseIsSuccessful();
    }

    public function testUpdate(): void
    {
        $client = static::createClient();
        $id = $this->createTask($client);
        $columnId = $this->createColumn($client);
        $client->request('PUT', '/api/tasks/' . $id, [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['title' => 'Updated Task', 'position' => 2, 'taskColumn' => $columnId])
        );
        self::assertResponseIsSuccessful();
    }

    public function testDelete(): void
    {
        $client = static::createClient();
        $id = $this->createTask($client);
        $client->request('DELETE', '/api/tasks/' . $id);
        self::assertResponseIsSuccessful();
    }

    public function testValidateCreate(): void
    {
        $client = static::createClient();
        $columnId = $this->createColumn($client);
        $client->request('POST', '/api/tasks', [], [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['title' => '', 'position' => 1, 'taskColumn' => $columnId])
        );
        self::assertResponseStatusCodeSame(400);
    }
}
