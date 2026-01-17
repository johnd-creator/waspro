# 📱 Mobile API Guide for Flutter Developers

## Overview
This document provides guidance for Flutter developers integrating with the WASPRO backend API.

---

## [2026-01-11] Initial Documentation Setup

**Files Created:**
- `docs/api/mobile-guide.md` - This file

**Description:**
- Created mobile API guide for Flutter developers
- Established structure for documenting API changes

---

## API Base URL

```
Development: http://localhost:8000/api
Production:  https://your-domain.com/api
```

---

## Authentication

### Web Session Auth
For web-based Flutter (WebView):
- Login via POST to `/login`
- Session cookie managed automatically

### Token-Based Auth (Sanctum)
For native Flutter app:
- Login via POST to `/api/login`
- Receive token in response
- Include token in header: `Authorization: Bearer {token}`

### Login Endpoint

#### Request
```http
POST /api/login
Content-Type: application/json

{
  "email_address": "user@example.com",
  "password": "password123"
}
```

#### Response (Success - 200)
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "user_id": 1,
      "nama_lengkap": "John Doe",
      "email_address": "user@example.com",
      "unit_id": 1,
      "aktif": true,
      "peran": ["Operator"]
    },
    "token": "1|abc123xyz...",
    "unit": {
      "unit_id": 1,
      "nama_unit": "Unit Pembangkit Pusat",
      "kota": "Jakarta"
    }
  }
}
```

#### Response (Error - 401)
```json
{
  "success": false,
  "message": "Invalid credentials",
  "errors": {
    "email_address": ["These credentials do not match our records."]
  }
}
```

---

## API Response Format

All API responses follow this structure:

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    // Response data here
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error occurred",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### Paginated Response
```json
{
  "success": true,
  "data": [
    // Array of items
  ],
  "meta": {
    "current_page": 1,
    "per_page": 10,
    "total": 100,
    "last_page": 10
  },
  "links": {
    "first": "http://localhost:8000/api/resource?page=1",
    "last": "http://localhost:8000/api/resource?page=10",
    "prev": null,
    "next": "http://localhost:8000/api/resource?page=2"
  }
}
```

---

## Available Endpoints

### Authentication
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/login` | User login | No |
| POST | `/api/logout` | User logout | Yes |
| POST | `/api/register` | User registration (if enabled) | No |
| GET | `/api/me` | Get current user info | Yes |

### Waste Logs (Log Penyimpanan Limbah)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/log-penyimpanan` | List all waste logs (scoped to user's unit) | Yes |
| POST | `/api/log-penyimpanan` | Create new waste log | Yes |
| GET | `/api/log-penyimpanan/{id}` | Get specific waste log | Yes |
| PUT | `/api/log-penyimpanan/{id}` | Update waste log | Yes |
| DELETE | `/api/log-penyimpanan/{id}` | Delete waste log | Yes |
| GET | `/api/log-penyimpanan/expired` | Get expired waste | Yes |
| GET | `/api/log-penyimpanan/critical` | Get critical waste (expires in 3 days) | Yes |
| GET | `/api/log-penyimpanan/warning` | Get warning waste (expires in 7 days) | Yes |

### Waste Types (Jenis Limbah)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/jenis-limbah` | List all waste types | Yes |
| GET | `/api/jenis-limbah/{kode}` | Get specific waste type | Yes |

### Companies (Perusahaan Penghasil)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/perusahaan-penghasil` | List all companies | Yes |
| GET | `/api/perusahaan-penghasil/{id}` | Get specific company | Yes |
| POST | `/api/perusahaan-penghasil` | Create new company | Yes (Admin only) |
| PUT | `/api/perusahaan-penghasil/{id}` | Update company | Yes (Admin only) |

### Units (Unit Pembangkit)
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/unit-pembangkit` | List all units | Yes |
| GET | `/api/unit-pembangkit/{id}` | Get specific unit | Yes |

### Dashboard
| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/dashboard` | Get dashboard statistics | Yes |

---

## Important Notes for Flutter Developers

### 1. Unit Scope
- All data endpoints automatically filter by user's `unit_id`
- Super Admin can see all units
- Other users only see their unit's data
- No need to manually filter by unit in Flutter app

### 2. Pagination
- Default per page: 10 items
- Use `page` query parameter to navigate
- Check `links` in response for next/prev URLs

### 3. Date Formats
- All dates in ISO 8601 format: `2026-01-11T10:30:00+07:00`
- Timezone: Asia/Jakarta (UTC+7)
- Store as DateTime in Flutter

### 4. File Uploads
- Use `multipart/form-data`
- Max file size: 10MB (configurable)
- Upload to `/api/log-penyimpanan/{id}/upload-document`
- Supported formats: PDF, JPG, PNG

### 5. Offline Sync
- Include `client_uuid` in requests
- Include `created_at_client` for new records
- Check `synced_at` timestamp for sync status

### 6. Rate Limiting
- 120 requests per 60 seconds
- Headers returned: `X-RateLimit-Limit`, `X-RateLimit-Remaining`
- Implement retry with exponential backoff

### 7. Error Handling
- Handle HTTP status codes appropriately
- 400: Validation error - show form errors
- 401: Unauthorized - redirect to login
- 403: Forbidden - show permission denied
- 404: Not found - show not found message
- 422: Unprocessable entity - show validation errors
- 429: Too many requests - implement retry
- 500: Server error - show generic error message

---

## Change Log Template

```markdown
### [YYYY-MM-DD] [Type] API Change Description

**Endpoint Changed:**
- `GET /api/resource` - Description of change

**Breaking Change:** Yes/No

**Old Request/Response:**
```json
// Old format
```

**New Request/Response:**
```json
// New format
```

**Flutter Migration Guide:**
- Step 1: ...
- Step 2: ...
```

---

## Flutter Integration Example

### Using Dio Package
```dart
import 'package:dio/dio.dart';

class ApiService {
  final Dio _dio = Dio();

  ApiService() {
    _dio.options.baseUrl = 'http://localhost:8000/api';
    _dio.options.headers['Accept'] = 'application/json';
    _dio.interceptors.add(AuthInterceptor());
  }

  Future<void> login(String email, String password) async {
    try {
      final response = await _dio.post('/login', data: {
        'email_address': email,
        'password': password,
      });

      final token = response.data['data']['token'];
      _dio.options.headers['Authorization'] = 'Bearer $token';
    } on DioException catch (e) {
      // Handle error
    }
  }

  Future<List<WasteLog>> getWasteLogs() async {
    try {
      final response = await _dio.get('/log-penyimpanan');
      final data = response.data['data'] as List;
      return data.map((json) => WasteLog.fromJson(json)).toList();
    } on DioException catch (e) {
      // Handle error
      rethrow;
    }
  }
}
```

---

## OpenAPI Specification

Complete API documentation available at:
- **File:** `docs/openapi/k3-api.yaml`
- **Postman Collection:** `docs/postman/k3-api.postman_collection.json`

Import these into your tools to generate client code.

---

## Testing API

### Using cURL
```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email_address":"user@example.com","password":"password123"}'

# Get waste logs (with token)
curl -X GET http://localhost:8000/api/log-penyimpanan \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Using Postman
1. Import `docs/postman/k3-api.postman_collection.json`
2. Set environment variables:
   - `base_url`: http://localhost:8000/api
   - `token`: Copy from login response

---

## Support & Questions

For API-related questions:
- Check OpenAPI spec: `docs/openapi/k3-api.yaml`
- Check backend notes: `docs/development/backend.md`
- Contact backend team via project communication channel

---

**Last Updated:** 2026-01-11
