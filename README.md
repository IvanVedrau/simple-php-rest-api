# Simple PHP REST API (JSON Storage)

This project is a simple REST-style API written in **PHP**, using **JSON files as data storage**.
It was created as a learning project to understand backend fundamentals without using frameworks.

---

## 🚀 Features
- CRUD operations for users and campaigns
- User ↔ Campaign assignment system
- JSON-based data persistence
- REST-like endpoints
- HTTP status codes and error handling

---

## 🛠️ Technologies
- PHP
- JSON file storage
- REST-style routing (without frameworks)

---

## 📌 API Endpoints

### Users
- GET /api/users
- GET /api/users/{id}
- POST /api/users
- PUT /api/users/{id}
- DELETE /api/users/{id}

### Campaigns
- GET /api/campaigns
- GET /api/campaigns/{id}
- POST /api/campaigns
- PUT /api/campaigns/{id}
- DELETE /api/campaigns/{id}

### Assignments
- POST /api/assign
- GET /api/users/{id}/campaigns
- GET /api/campaigns/{id}/users
- GET /api/assigned/{userId}/{campaignId}

---

## 🧠 What I Learned
- Handling HTTP requests in PHP
- Building REST-like APIs without frameworks
- Working with JSON as a simple database
- Managing relationships between entities
- API structure and routing logic

---

## ⚠️ Notes
This project does not use a real database or authentication and is intended for learning purposes only.
