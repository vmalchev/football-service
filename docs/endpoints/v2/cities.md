# Cities v2

## Creating and Updating

- POST `/v2/cities` to create a new city
- PUT `/v2/cities/{city_id}` to replace existing city data

#### Input

```json
{
  "name": "Test City",
  "country_id": "14"
}
```

#### Output

```json
{
  "id": "1734",
  "name": "Test City",
  "country": {
    "id": "14",
    "name": "Bulgaria",
    "code": null
  }
}
```

#### Validation

- name required
- country_id required, a valid id from `/countries`
- name, country_id is a unique key