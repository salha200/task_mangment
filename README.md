# Task Management API

## Overview

This project is an advanced Task Management API that allows users to create, update, view, delete, and assign tasks with various properties such as title, description, priority, due date, status, and assigned user. The project also includes role-based access control with roles such as Admin, Manager, and User, focusing on a deep understanding of models using features like `fillable`, `guarded`, `primaryKey`, `table`, and `timestamps` and used spati the separite the permission

## Features

- **CRUD Operations**: Create, Read, Update, and Delete tasks and users.
- **Role Management**: Admins can manage all tasks and users. Managers can manage tasks they created or assigned. Users can only update tasks assigned to them.
- **Task Assignment**: Managers can assign tasks to users.
- **Date Handling**: Proper handling of dates using Accessors and Mutators.
- **Soft Deletes**: Tasks and users can be soft deleted and restored.
- **Query Scopes**: Advanced filtering of tasks based on priority and status.
## Requirment


- laravel 10
- php
- xampp
## API Endpoints
### Tasks
- POST /tasks: Create a new task.
- GET /tasks: List all tasks with filtering options (by priority and status).
- GET /tasks/{id}: Show details of a specific task.
- PUT /tasks/{id}: Update a task (only the assigned user can update).
- DELETE /tasks/{id}: Soft delete a task.
- POST /tasks/{id}/assign: Assign a task to a user (Managers only).
