# React Task Board

A Kanban-style task board for managing projects, with a React frontend and Symfony API backend.

## What it does

Provides a drag-and-drop board interface for organising tasks into columns (e.g. To Do, In Progress, Done) across multiple projects. Think Trello or Jira boards, but self-hosted and lightweight.

## Tech Stack

### Frontend
- **React 19** with JSX
- **Vite 8** for dev server and bundling
- **ESLint** for linting

### Backend
- **Symfony 6.4** (PHP 8.5)
- **Doctrine ORM** with PostgreSQL
- **Nelmio CORS Bundle** for cross-origin API access

### Infrastructure
- **PostgreSQL** (via Docker Compose or local install)
- **Composer** for PHP dependencies
- **npm** for frontend dependencies

## Data Model

- **Project** — a board with a name, description, and columns
- **Column** — a named list within a project (e.g. "To Do"), with a position for ordering
- **Task** — a card with a title, description, and position within a column

## Project Structure

```
task-board/
├── src/                    # React frontend source
├── public/                 # Frontend static assets
├── task-board-api/         # Symfony API
│   ├── src/
│   │   ├── Controller/     # API controllers
│   │   ├── Entity/         # Doctrine entities (Project, Column, Task)
│   │   └── Repository/     # Doctrine repositories
│   ├── config/             # Symfony config (routes, services, packages)
│   ├── migrations/         # Database migrations
│   └── templates/          # Twig templates
├── package.json            # Frontend dependencies
└── composer.json           # Outer PHP dependencies
```

## Getting Started

### Prerequisites
- PHP 8.1+
- Composer
- Node.js and npm
- PostgreSQL

### Backend
```bash
cd task-board/task-board-api
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony server:start  # or use the PhpStorm built-in server
```

### Frontend
```bash
cd task-board
npm install
npm run dev
```

The Vite dev server runs on `http://localhost:5173` and the Symfony API on `http://localhost:8080`.
