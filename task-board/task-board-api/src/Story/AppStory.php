<?php

namespace App\Story;

use App\Factory\ColumnFactory;
use App\Factory\ProjectFactory;
use App\Factory\TaskFactory;
use Zenstruck\Foundry\Attribute\AsFixture;
use Zenstruck\Foundry\Story;

#[AsFixture(name: 'main')]
final class AppStory extends Story
{
    public function build(): void
    {
        $projects = ProjectFactory::createMany(3);

        foreach ($projects as $project) {
            $columns = ColumnFactory::createMany(3, ['project' => $project]);

            foreach ($columns as $column) {
                TaskFactory::createMany(4, ['taskColumn' => $column]);
            }
        }
    }
}
