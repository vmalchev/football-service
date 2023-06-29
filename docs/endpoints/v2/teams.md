# Teams

## Creating and Updating

- POST `/v2/teams` to create new
- PUT `/v2/teams/{team_id}` to replace existing

#### Input

```json
{
  "name": "Test Team",
  "type": "CLUB",
  "country_id": "14",
  "venue_id": "323",
  "social": {
    "web": "test",
    "twitter_id": "test",
    "facebook_id": "test",
    "instagram_id": "test",
    "wikipedia_id": "test"
  },
  "founded": 1912
}
```

#### Validation

- `name` required
- `type` required, enum: `PLACEHOLDER`, `NATIONAL`, `CLUB`
- `country_id` required valid country form `/countries`
- `venue_id` valid venue id, optional
- `social` - optional object with social data
- `founded` - integer, optional

#### Output

```json
{
  "id": "21592",
  "name": "Test Team",
  "type": "club",
  "founded": 1912,
  "country": {
    "id": "14",
    "name": "Bulgaria",
    "code": null
  },
  "venue": {
    "id": "323",
    "name": "Jan Breydel Stadion",
    "country": {
      "id": "44",
      "name": "Belgium",
      "code": null
    },
    "city": {
      "id": "7",
      "name": "Bruges",
      "country": {
        "id": "44",
        "name": "Belgium",
        "code": null
      }
    },
    "profile": {
      "lat": 51.1933324,
      "lng": 3.1805337,
      "capacity": 29472
    }
  },
  "social": {
    "web": "test",
    "twitter_id": "test",
    "facebook_id": "test",
    "instagram_id": "test",
    "wikipedia_id": "test"
  },
  "coach": null
}
```