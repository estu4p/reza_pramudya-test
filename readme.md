# CLT Management API Documentation

## Overview

This project is a Laravel-based REST API for managing hierarchical data structure:

- Supplier → Layups → Layers

It also supports:

- Import / Export per Supplier
- Conflict detection & resolution strategies
- Clean architecture (Service + Repository pattern)

---

## Base URL

http://127.0.0.1:8000/api

---

## Data Structure

### Supplier

```json
{
    "id": 1,
    "name": "Supplier A"
}
```

### Layup

```json
{
    "id": 1,
    "supplier_id": 1,
    "name": "Layup A"
}
```

### Layer

```json
{
    "id": 1,
    "layup_id": 1,
    "layer_order": 1,
    "thickness": 10.5,
    "width": 100,
    "angle": 45
}
```

## API ENDPOINTS

### SUPPLIER

- **Get All Suppliers**
  `GET /suppliers`
- **Create Supplier**
  `POST /suppliers`
- **Body**:
    ```json
    {
        "name": "Supplier A"
    }
    ```
- **Get Supplier Detail**:
    - `GET /suppliers/{id}`
- **Update Supplier**:
    - `PUT /suppliers/{id}`
- **Body**:
    ````json
        {
            "name": "Updated Supplier"
        }
        ```
    ````
- **Delete Supplier**:
    - `DELETE /suppliers/{id}`

### LAYUPS (Nested under Supplier)

- **Get Layups**:
    - `GET /suppliers/{supplier_id}/layups`
- **Create Layup**:
    - `POST /suppliers/{supplier_id}/layups`
- **Body**:
    ```json
    {
        "name": "Layup A"
    }
    ```
- **Update Layup**:
    - `PUT /suppliers/{supplier_id}/layups/{id}`
- **Delete Layup**:
    - `DELETE /suppliers/{supplier_id}/layups/{id}`

### LAYERS (Nested under Layup)

- **Get Layers**:
    - `GET /layups/{layup_id}/layers`
- **Create Layers**:
    - `POST /layups/{layup_id}/layers`
- **Body:**:
    ```json
    {
        "layer_order": 1,
        "thickness": 10.5,
        "width": 100,
        "angle": 45
    }
    ```
- **Update Layers**:
    - `PUT /layups/{layup_id}/layers/{id}`

- **Delete Layer**:
    - `DELETE /layups/{layup_id}/layers/{id}`

---

### EXPORT SUPPLIER

- **Export Full Structure**:
    - `GET /suppliers/{id}/export`
- **Response**:
    ```json
    {
        "id": 1,
        "name": "Supplier A",
        "layups": [
            {
                "id": 1,
                "name": "Layup A",
                "layers": [
                    {
                        "layer_order": 1,
                        "thickness": 10,
                        "width": 100,
                        "angle": 45
                    }
                ]
            }
        ]
    }
    ```

### IMPORT SUPPLIER

- **Import Data**:
    - `POST /suppliers/{id}/import?strategy=overwrite`

- **Body**:

    ```json
    {
        "layups": [
            {
                "name": "Layup A",
                "layers": [
                    {
                        "layer_order": 1,
                        "thickness": 12,
                        "width": 100,
                        "angle": 45
                    }
                ]
            }
        ]
    }
    ```

- **IMPORT STRATEGY**:
  | Strategy | Description |
  |------------|------------------------------------|
  | overwrite | Replace existing data |
  | skip | Keep existing data |
  | duplicate | Create new layup with suffix |
  | reject | Abort import if conflict exists |

#### CONFLICT HANDLING RULES

- **Layup Conflict**:
    - Same name under same supplier
    - Treated as same entity (no duplicate)

- **Layer Conflict**:
- Conflict occurs if:
    - Same `layer_order`
    - AND any field differs:
        - `thickness`
        - `width`
        - `angle`

#### REJECT RESPONSE

    ```json
    {
        "status": "failed",
        "message": "Conflict detected",
        "conflicts": [
            {
                "layup": "Layup A",
                "layer_order": 1,
                "existing": {
                    "thickness": 10
                },
                "incoming": {
                    "thickness": 12
                }
            }
        ]
    }
    ```

## HTTP STATUS CODES

| Code | Meaning          |
| ---- | ---------------- |
| 200  | Success          |
| 201  | Created          |
| 404  | Not Found        |
| 409  | Conflict         |
| 422  | Validation Error |
| 500  | Server Error     |

## ARCHITECTURE

This project uses:

- Service Layer Pattern
- Repository Pattern
- Interface Binding
- Form Request Validation
- Route Model Binding

## TESTING TOOLS

Recommended:

- Postman
- Laravel Feature Test
- Thunder Client

## NOTES

This API is designed for:

- scalable hierarchical data
- conflict-aware import system
- clean and maintainable Laravel architecture
