# Venues V2

## Create and update

- To create a new entry POST `/v2/venues`
- To replace existing venue data `/v2/venues/{venue_id}`

**Example**

```json
{
  "name": "Test venue",
  "country_id": "14",
  "city_id": "1734",
  "profile": {
    "lat": 42,
    "lng": 50,
    "capacity": 9635
  }
}
```

### Validation

- name required
- country_id valid from `/countries`
- city_id optional, valid city
- profile.lat and profile.lng geo coordinates
- capacity positive int
- name, country_id, city_id is a unique key

## Find by id

Same response is available after a successful POST or PUT

**Example**

```json
{
  "id": "39647",
  "name": "Test venue",
  "country": {
    "id": "14",
    "name": "Bulgaria",
    "code": null
  },
  "city": {
    "id": "1734",
    "name": "Test City",
    "country": {
      "id": "14",
      "name": "Bulgaria",
      "code": null
    }
  },
  "profile": {
    "lat": 42,
    "lng": 50,
    "capacity": 9635
  }
}
```
