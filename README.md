# 🚀 HireHub API Documentation

## 📌 Overview

This document provides a complete reference for all available API endpoints in the **HireHub** system, including request examples and responses.

---

## 🔐 Authentication Accounts

### 👑 Admin Account

* **Email:** [admin@gmail.com](mailto:admin@gmail.com)
* **Password:** password

### 👤 Client Account

* **Email:** [client2@hirehub.com](mailto:client2@hirehub.com)
* **Password:** password

### 👤 Freelancer Account

* **Email:** [free2@hirehub.com](mailto:client2@hirehub.com)
* **Password:** password

---

## 📬 API Collection (Postman)

You can test the API using the Postman collection file:

👉 [Download Postman Collection](HireHub.postman_collection.json)

### How to use:

1. Open Postman
2. Click Import
3. Upload the JSON file
4. Start testing 🚀


## 📊 Admin Endpoints
### 📈 Get Statistics

**GET** `/api/admin/statistics`

#### Response

```json
{
  "data": {
    "total_users": 50,
    "total_projects": 50,
    "total_proposals": 5,
    "total_proposals_value": "2250.00",
    "active_projects": 50
  }
}
```

---

## 🏠 Home

### 🆕 Latest Projects

**GET** `/api/home`

#### Response (shortened)

```json
{
  "latest_projects": [
    {
      "id": 1,
      "title": "HireHub Project #1",
      "formatted_budget": "$425.00/hr",
      "deadline_status": "13 days left"
    }
  ]
}
```

---

## 📁 Projects

### 📋 Get All Projects (Paginated)

**GET** `/api/projects`

#### Features:

* Pagination
* Client info
* Tags
* Proposals count

---

### 🔍 Get Single Project

**GET** `/api/project/{id}`

#### Response

```json
{
  "data": {
    "id": 2,
    "title": "HireHub Project #2",
    "formatted_budget": "$4230.00 USD",
    "deadline_status": "29 days left",
    "proposals_count": 5
  }
}
```

---

### ➕ Create Project

**POST** `/api/project`

#### Request

```json
{
  "title": "Build Laravel API...",
  "description": "Detailed description...",
  "budget": 1000,
  "budget_type": "fixed",
  "status": "open",
  "deadline": "2026-05-01",
  "tags": [1, 2]
}
```

---

## 💼 Freelancers

### 📋 Get Freelancers

**GET** `/api/freelancers`

---

### 👤 Get Freelancer Details

**GET** `/api/freelancer/{id}`

> Note: ID must be ≥ 26

---

## 👤 User

### 🙋 Get Current User

**GET** `/api/user`

---

## 🧾 Profile

### 📄 Get Profile

**GET** `/api/profile`

---

### ✏️ Update Profile

**PUT** `/api/profile`

#### Response

```json
{
  "message": "Profile updated successfully"
}
```

---

### 🛠️ Update Skills

**POST** `/api/profile/skills`

#### Request

```json
{
  "skills": [
    { "id": 1, "years": 3 },
    { "id": 2, "years": 5 }
  ]
}
```

---

## 📩 Proposals

### ➕ Create Proposal

**POST** `/api/project/{id}/proposals`

---

### 📄 Get Proposal

**GET** `/api/proposals/{id}`

---

### ✅ Accept Proposal

**POST** `/api/proposals/{id}/accept`

#### Response

```json
{
  "message": "Accepted"
}
```

---

## ⚡ Performance Testing

### 🧪 Compare Performance

**GET** `/api/performance-test`

#### Response

```json
{
  "message": "HireHub Phase 3 Performance Comparison",
  "stats": {
    "projects": {
      "old_way": { "total_queries": 52 },
      "new_way": { "total_queries": 2 }
    }
  }
}
```

---

## ⚙️ Notes

* All endpoints follow RESTful conventions
* Pagination is applied where needed
* Eager loading is used to prevent N+1 query issues
* Authentication is required for protected routes

---

## 🧠 Best Practices

* Avoid unnecessary data loading
* Use optimized queries (`with`, `withCount`)
* Keep responses clean and minimal
* Always validate request data

---

## 🏁 Final Thoughts

This API is designed with performance, scalability, and clean architecture in mind.
All endpoints are optimized and ready for production-level usage.

---
