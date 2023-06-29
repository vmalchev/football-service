# Translations

## Adding Translations

The POST `/translations` endpoint supports the following types.

Translations are added with a valid `entity_id`

```json
[
  {
    "entity": "LINEUP_PLAYER_TYPE"
  },
  {
    "entity": "COACH"
  },
  {
    "entity": "VENUE"
  },
  {
    "entity": "TOURNAMENT"
  },
  {
    "entity": "TEAM"
  },
  {
    "entity": "COUNTRY"
  },
  {
    "entity": "MATCH_STATUS"
  },
  {
    "entity": "STAGE"
  },
  {
    "entity": "PLAYER"
  },
  {
    "entity": "REFEREE"
  },
  {
    "entity": "CITY"
  },
  {
    "entity": "PRESIDENT"
  },
  {
    "entity": "STANDING_RULE"
  }
]
```

### Example

POST `/translations`

```json
[
  {
    "key": {
      "entity": "CITY",
      "entity_id": "1734",
      "language": "bg"
    },
    "content": {
      "name": "Тестов град"
    }
  }
]
```

The endpoint supports a bulk update of translations. It will replace any existing translations by the key: `entity`, `entity_id`, `language`

#### Validation

* `language` valid code from `/v2/languages`
* all fields are required
* `entity_id` in the example is the city for which translation is added. The field is polymorphic if the `entity` is `TEAM` it should refer to the `team.id`
* the `content.name` format is the same for all currently supported entities

