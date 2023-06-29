# Search endpoint

The search endpoint provides the ability to query for different entity types

**Searching should not be used in client facing applications, only server-to-server calls**

## Supported entity types

- `player`
- `team`
- `coach`
- `venue`   
- `referee`
- `tournament`
- `city`
- `country`
- `president`
- `country`

## Examples

### GET `/search?query=Sofia&entity_type=city&input_language=en`

```json
[
  {
    "id": 1,
    "name": "Sofia",
    "country_id": 14,
    "entity_type": "city",
    "origin": "Sofia"
  },
  {
    "id": 1764,
    "name": "Sofia",
    "country_id": 96,
    "entity_type": "city",
    "origin": "Sofia"
  }
]
```

`input_language` can be changed to a different `code` available from `/v2/languages`.

**Example** `/search?query=София&entity_type=city&input_language=bg`

### GET `/search?query=Mourinho&entity_type=coach&input_language=en`

```json
[
  {
    "id": 5101,
    "name": "Jose Riveiro",
    "country": {
      "id": 17,
      "name": "Испания",
      "url_flag": null
    },
    "url_image": null,
    "birthdate": "1975-09-15",
    "entity_type": "coach",
    "origin": "Jose"
  }
]
```

### GET `/search?query=test&entity_type=president&input_language=en`

```json
[
  {
    "id": 6,
    "name": "Test 3",
    "entity_type": "president",
    "origin": "test"
  },
  {
    "id": 8,
    "name": "Test 2",
    "entity_type": "president",
    "origin": "test"
  },
  {
    "id": 9,
    "name": "David Barry 1 Test",
    "entity_type": "president",
    "origin": "test"
  }
]
```

### GET `/search?query=Manchester&entity_type=team&input_language=en`

```json
[
  {
    "id": 102,
    "name": "Манчестър Юнайтед",
    "national": false,
    "country": {
      "id": 15,
      "name": "Англия",
      "url_flag": "https://fapi.sportal.bg/assets/country/flag/15-England-flag.png"
    },
    "type": "club",
    "venue": {
      "id": 57,
      "name": "Олд Трафорд",
      "city": "Manchester",
      "country": {
        "id": 15,
        "name": "Англия",
        "url_flag": "https://fapi.sportal.bg/assets/country/flag/15-England-flag.png"
      },
      "lat": 53.463056,
      "lng": -2.291389,
      "capacity": 75643,
      "url_image": "https://fapi.sportal.bg/assets/venue/image/57-Old-Trafford-image.jpeg"
    },
    "social": {
      "web": "http://www.manutd.com/",
      "twitter_id": "MANUTD",
      "facebook_id": "manchesterunited",
      "instagram_id": "MANCHESTERUNITED",
      "wikipedia_id": "Manchester_United_F.C."
    },
    "founded": 1878,
    "url_logo": null,
    "url_home_kit": null,
    "url_away_kit": null,
    "url_squad_image": null,
    "entity_type": "team",
    "origin": "Manchester"
  }
]
```
