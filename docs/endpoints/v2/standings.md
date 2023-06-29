# Adding Standings

- General format PUT `/v2/standings/{standing_type}/{entity_type}/{entity_id}`

## League standing

- PUT `/v2/standings/league/stage/123`

### Supported entities

- `stage`, `group`

### Example

```json
{
  "entries": [
    {
      "rank": 1,
      "team_id": "17",
      "wins": 3,
      "draws": 1,
      "played": 4,
      "points": 10,
      "losses": 0,
      "goals_for": 7,
      "goals_against": 4
    },
    {
      "rank": 2,
      "team_id": "34",
      "wins": 3,
      "draws": 0,
      "played": 4,
      "points": 9,
      "losses": 1,
      "goals_for": 10,
      "goals_against": 4
    }
  ]
}

```

### Validation

- All fields in example are required
- No duplicate teams are allowed

### Adding rules to league standings

- list of all rules (with ids) can be found in `/standing_rules` endpoint
- to attach standing rules **PUT** `/v2/standings/league/{entity}/{id}/rules`

#### Example

```json
{
  "rules": [
    {
      "rank": 1,
      "standing_rule_id": "5"
    },
    {
      "rank": 2,
      "standing_rule_id": "4"
    }
  ]
}
```

#### Validation

- Standing for the league must exist before attaching rules
- `standung_rule_id` must be a valid rule from `/standing_rules`

## Topscorer

- PUT `/v2/standings/topscorer/season/123`

### Supported entities

- `stage`, `group`, `season`

### Example

```json
{
  "entries": [
    {
      "rank": 1,
      "team_id": "17",
      "player_id": "3400",
      "goals": 18,
      "played": 29,
      "assists": 1,
      "minutes": 1867,
      "penalties": 7,
      "red_cards": 0,
      "scored_first": 7,
      "yellow_cards": 4
    },
    {
      "rank": 2,
      "team_id": "34",
      "player_id": "100",
      "goals": 18,
      "played": 29,
      "assists": 1,
      "minutes": 1867,
      "penalties": 7,
      "red_cards": 0,
      "scored_first": 7,
      "yellow_cards": 4
    }
  ]
}

```

### Validation

- `rank`, `team_id`, `player_id`, `goals` are required
- no duplicate players allowed