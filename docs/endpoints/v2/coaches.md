# Coaches v2

## Listing all coaches

### GET `/v2/coaches?offset=0&limit=1000`

**Note** offset and limit are required (max limit = 1000)

```json
{
  "coaches": [
    {
      "id": "5864",
      "name": "Ben Kinds",
      "country": {
        "id": "25",
        "name": "Netherlands",
        "code": null
      },
      "birthdate": "1983-01-11"
    }
  ],
  "page_meta": {
    "total": 5862,
    "offset": 0,
    "limit": 1
  }
}
```

## Creating and Updating

- POST `/v2/coaches` to create new coach
- PUT `/v2/coaches/{coach_id}` to replace existing data

### Input

```json
{
  "name": "Test Coach",
  "country_id": "14",
  "birthdate": "2021-01-11"
}
```

### Output

```json
{
  "id": "5867",
  "name": "Test Coach",
  "country": {
    "id": "14",
    "name": "Bulgaria",
    "code": null
  },
  "birthdate": "2021-01-11"
}
```

### Validation

- name required
- country_id: valid country, required
- birthdate: optional Y-m-d format
