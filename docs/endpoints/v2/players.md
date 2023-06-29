# Players v2

## Creating or updating

- POST `/v2/players` to create new
- PUT `/v2/players/{player_id}` to replace existing

### Input

```json
{
  "name": "Test Player",
  "country_id": "14",
  "active": true,
  "birthdate": "2020-04-12",
  "birth_city_id": "1",
  "profile": {
    "height": "185",
    "weight": "85"
  },
  "social": {
    "web": "test",
    "twitter_id": "test",
    "facebook_id": "test",
    "instagram_id": "test",
    "wikipedia_id": "test",
    "youtube_channel_id": "test"
  },
  "position": "forward"
}
```

### Validation

- `name` required
- `country_id` valid country from `/countries`, nationality of player
- `active` whether the player is currently playing or not, optional
- `birhdate` Y-m-d date of birth, optional
- `birth_city_id` valid `city.id` indicating where the player is born
- `profile.height` height in cm, optional
- `profile.weight` weight in kg, optional
- `social` various social links, optional
- `position` - enum: `KEEPER`, `DEFENDER`, `MIDFIELDER`, `FORWARD`, optional

### Output

```json
{
  "id": "207779",
  "name": "Test Player",
  "country": {
    "id": "14",
    "name": "Bulgaria",
    "code": null
  },
  "active": true,
  "birthdate": "2020-04-12",
  "birth_city": {
    "id": "1",
    "name": "Manchester",
    "country": {
      "id": "15",
      "name": "England",
      "code": null
    }
  },
  "profile": {
    "height": "185",
    "weight": "85"
  },
  "social": {
    "web": "test",
    "twitter_id": "test",
    "facebook_id": "test",
    "instagram_id": "test",
    "wikipedia_id": "test",
    "youtube_channel_id": "test"
  },
  "position": "FORWARD"
}
```

## Listing players

GET `/v2/players?offset=0&limit=1000`

**Note** offset and limit are required (max limit = 1000)

### Example

```json
{
  "players": [
    {
      "id": "207779",
      "name": "Test Player",
      "country": {
        "id": "14",
        "name": "Bulgaria",
        "code": null
      },
      "active": true,
      "birthdate": "2020-04-12",
      "birth_city": {
        "id": "1",
        "name": "Manchester",
        "country": null
      },
      "profile": {
        "height": "185",
        "weight": "85"
      },
      "social": {
        "web": "test",
        "twitter_id": "test",
        "facebook_id": "test",
        "instagram_id": "test",
        "wikipedia_id": "test",
        "youtube_channel_id": "test"
      },
      "position": "FORWARD"
    }
  ],
  "page_meta": {
    "total": 207730,
    "offset": 0,
    "limit": 1
  }
}
```
