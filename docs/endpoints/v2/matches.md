# Matches V2

## Example GET Match object

```json
{
  "id": "655185",
  "status": {
    "id": "1",
    "name": "Finished",
    "type": "finished",
    "code": "finished"
  },
  "kickoff_time": "2020-12-09T20:00:00+00:00",
  "home_team": {
    "id": "104",
    "name": "Manchester City",
    "type": "club"
  },
  "away_team": {
    "id": "289",
    "name": "Marseille",
    "type": "club"
  },
  "round": {
    "key": "6"
  },
  "stage": {
    "id": "15515",
    "name": "Champions League Group Stage",
    "type": "GROUP",
    "start_date": "2020-10-20",
    "end_date": "2020-12-09"
  },
  "season": {
    "id": "6388",
    "name": "2020\/2021",
    "status": "ACTIVE",
    "tournament": {
      "id": "27",
      "name": "Champions League",
      "country": {
        "id": "101",
        "name": "International",
        "code": null
      }
    }
  },
  "group": {
    "id": "923",
    "name": "C"
  },
  "venue": {
    "id": "59",
    "name": "Etihad Stadium"
  },
  "referees": [
    {
      "id": "158",
      "name": "Halil Meler",
      "role": "REFEREE"
    }
  ],
  "score": {
    "total": {
      "home": 3,
      "away": 0
    },
    "regular_time": {
      "home": 3,
      "away": 0
    },
    "half_time": {
      "home": 2,
      "away": 0
    },
    "extra_time": {
      "home": 1,
      "away": 0
    },
    "penalty_shootout": {
      "home": 5,
      "away": 3
    },
    "aggregate": {
      "home": 6,
      "away": 3
    }
  },
  "spectators": 0,
  "minute": {
    "regular_time": 90,
    "injury_time": 3
  },
  "coverage": "LIVE",
  "finished_at": "2020-12-09T21:55:00+00:00",
  "phase_started_at": "2020-12-09T21:06:45+00:00"
}
```

## POST/PUT /v2/matches/{id} behaviour

### Example

```json
{
  "status_id": "1",
  "kickoff_time": "2020-12-09T20:00:00+00:00",
  "home_team_id": "104",
  "away_team_id": "289",
  "round_key": "6",
  "stage_id": "15515",
  "group_id": "923",
  "venue_id": "59",
  "referees": [
    {
      "referee_id": "158",
      "role": "REFEREE"
    }
  ],
  "score": {
    "total": {
      "home": 3,
      "away": 0
    },
    "regular_time": {
      "home": 3,
      "away": 0
    },
    "half_time": {
      "home": 2,
      "away": 0
    },
    "extra_time": {
      "home": 1,
      "away": 0
    },
    "penalty_shootout": {
      "home": 5,
      "away": 3
    }
  },
  "spectators": 0,
  "coverage": "LIVE",
  "finished_at": "2020-12-09T21:55:00+00:00",
  "phase_started_at": "2020-12-09T21:06:45+00:00"
}
```

### Behaviour

#### Always required fields

- `status_id` valid status from https://football.api.sportal365.com/event_status
- `kickoff_time` valid timestamp
- `home_team_id` valid team in Football API database != away_team_id
- `away_team_id` valid team in Football API database != home_team_id
- `round_key` - not empty string
- `stage_id` - valid tournament_season_stage id

#### Sometimes required fields

- `group_id` if `stage.type` is `GROUP`, must be a group part of the stage
- `score.total` if the match status is != `not_started` or `cancelled` types
- `phase_started_at` if the match status is `first_half`, `second_half`, `extra_time_first_half` or `extra_time_second_half`

#### Optional fields

- `referee_id` - valid referee
- `venue_id` - valid venue
- `spectators` - integer >= 0
- `coverage` - `LIVE` or `NOT_LIVE`
- `finished_at` - allowed if match has status = `finished`
