# Match lineups v2

Management for lineup related information for a given match

## Lineup player types

Lists all valid player types in the context of lineups. These correspond to the `type_id` property in lineup input and formations.

GET `/event_player_types`

```json
[
  {
    "id": 3,
    "name": "Вратар",
    "category": "start",
    "code": "goalkeeper"
  },
  {
    "id": 4,
    "name": "Защитник",
    "category": "start",
    "code": "defence"
  },
  {
    "id": 5,
    "name": "Халф",
    "category": "start",
    "code": "midfield"
  },
  {
    "id": 6,
    "name": "Нападател",
    "category": "start",
    "code": "forward"
  },
  {
    "id": 9,
    "name": "Не на разположение",
    "category": "unknown",
    "code": "unavailable"
  },
  {
    "id": 10,
    "name": "National team duty",
    "category": "unknown",
    "code": "national_team_duty"
  },
  {
    "id": 11,
    "name": "Под въпрос",
    "category": "unknown",
    "code": "doubtful"
  },
  {
    "id": 2,
    "name": "Смяна",
    "category": "sub",
    "code": "substitute_player"
  },
  {
    "id": 7,
    "name": "Контузен",
    "category": "miss",
    "code": "injured"
  },
  {
    "id": 8,
    "name": "Наказан",
    "category": "miss",
    "code": "suspended"
  },
  {
    "id": 1,
    "name": "Титуляр",
    "category": "start",
    "code": "starter"
  }
]
```

## Lineup formations

Lists all valid formations with the available positions and the corresponding `type_id`.

GET `/v2/lineup_formations`

## Example

```json
[
  {
    "formation": "3-5-2",
    "positions": [
      {
        "position_x": 1,
        "position_y": 5,
        "type_id": 3,
        "number": 1
      },
      {
        "position_x": 3,
        "position_y": 3,
        "type_id": 4,
        "number": 2
      },
      {
        "position_x": 3,
        "position_y": 5,
        "type_id": 4,
        "number": 3
      },
      {
        "position_x": 3,
        "position_y": 7,
        "type_id": 4,
        "number": 4
      },
      {
        "position_x": 7,
        "position_y": 1,
        "type_id": 5,
        "number": 5
      },
      {
        "position_x": 7,
        "position_y": 3,
        "type_id": 5,
        "number": 6
      },
      {
        "position_x": 7,
        "position_y": 5,
        "type_id": 5,
        "number": 7
      },
      {
        "position_x": 7,
        "position_y": 7,
        "type_id": 5,
        "number": 8
      },
      {
        "position_x": 7,
        "position_y": 9,
        "type_id": 5,
        "number": 9
      },
      {
        "position_x": 10,
        "position_y": 4,
        "type_id": 6,
        "number": 10
      },
      {
        "position_x": 10,
        "position_y": 6,
        "type_id": 6,
        "number": 11
      }
    ]
  }
]
```

## Updating or creating a lineup

PUT `/v2/matches/{match_id}/lineups`

**NOTE** Replaces or adds all lineup related data with the input provided

## Example

```json
{
  "status": "CONFIRMED",
  "home_team": {
    "formation": "4-4-2",
    "coach_id": "240",
    "players": [
      {
        "type_id": "6",
        "player_id": "1769",
        "position_x": 10,
        "position_y": 3,
        "shirt_number": 10
      },
      {
        "type_id": "5",
        "player_id": "1735",
        "position_x": 7,
        "position_y": 4,
        "shirt_number": 16
      },
      {
        "type_id": "5",
        "player_id": "1721",
        "position_x": 7,
        "position_y": 2,
        "shirt_number": 30
      },
      {
        "type_id": "6",
        "player_id": "2144",
        "position_x": 10,
        "position_y": 5,
        "shirt_number": 11
      },
      {
        "type_id": "3",
        "player_id": "1555",
        "position_x": 1,
        "position_y": 5,
        "shirt_number": 13
      },
      {
        "type_id": "5",
        "player_id": "2125",
        "position_x": 7,
        "position_y": 6,
        "shirt_number": 14
      },
      {
        "type_id": "6",
        "player_id": "1716",
        "position_x": 10,
        "position_y": 7,
        "shirt_number": 27
      },
      {
        "type_id": "4",
        "player_id": "1798",
        "position_x": 3,
        "position_y": 5,
        "shirt_number": 8
      },
      {
        "type_id": "4",
        "player_id": "2034",
        "position_x": 3,
        "position_y": 7,
        "shirt_number": 21
      },
      {
        "type_id": "4",
        "player_id": "2050",
        "position_x": 3,
        "position_y": 3,
        "shirt_number": 2
      },
      {
        "type_id": "5",
        "player_id": "9240",
        "position_x": 7,
        "position_y": 8,
        "shirt_number": 31
      }
    ]
  },
  "away_team": {
    "formation": "4-3-3",
    "coach_id": "129",
    "players": [
      {
        "type_id": "6",
        "player_id": "2177",
        "position_x": 10,
        "position_y": 3,
        "shirt_number": 10
      },
      {
        "type_id": "5",
        "player_id": "2176",
        "position_x": 7,
        "position_y": 4,
        "shirt_number": 16
      },
      {
        "type_id": "5",
        "player_id": "1644",
        "position_x": 7,
        "position_y": 2,
        "shirt_number": 30
      },
      {
        "type_id": "6",
        "player_id": "1951",
        "position_x": 10,
        "position_y": 5,
        "shirt_number": 11
      },
      {
        "type_id": "3",
        "player_id": "1616",
        "position_x": 1,
        "position_y": 5,
        "shirt_number": 13
      },
      {
        "type_id": "5",
        "player_id": "1852",
        "position_x": 7,
        "position_y": 6,
        "shirt_number": 14
      },
      {
        "type_id": "6",
        "player_id": "1881",
        "position_x": 10,
        "position_y": 7,
        "shirt_number": 27
      },
      {
        "type_id": "4",
        "player_id": "1860",
        "position_x": 3,
        "position_y": 5,
        "shirt_number": 8
      },
      {
        "type_id": "4",
        "player_id": "2183",
        "position_x": 3,
        "position_y": 7,
        "shirt_number": 21
      },
      {
        "type_id": "4",
        "player_id": "2101",
        "position_x": 3,
        "position_y": 3,
        "shirt_number": 2
      },
      {
        "type_id": "5",
        "player_id": "1957",
        "position_x": 7,
        "position_y": 8,
        "shirt_number": 31
      }
    ]
  }
}
```

### Validation

- `status` optional, enum, `CONFIRMED`, `UNCONFIRMED`
- `home_team` and `away_team` have the same properties, both are optional
- `formation` optional string, one of `/v2/lineup_formations`
- `coach_id` optional, valid `coach.id`. This is the team's coach for the given match (might be different from the permament coach)
- `players.type_id` required, one of the ids in `/event_player_types`
- `players.player_id` required, valid `player.id`
- `position_x`, optional, field coordinate, min = 1, max = 11; 1 = goalkeepr, 11 = forward
- `position_y`, optional, field coordinate, min = 1, max = 9, 1 = most right, 9 = most left
- `shirt_number`, the player's shirt_number for the current match

### Other rules

- if a `formation` is specified for a given team, all starting players must have `position_x` and `position_y`
- if `players` are specified for a team, there must be at least 11 starters
- a player can not be specified twice in the same lineup

### Output

See example below

# GET /v2/matches/{id}/lineups

**NOTE** example is shortened since the response is big

```json
{
  "match_id": "900",
  "status": "CONFIRMED",
  "home_team": {
    "formation": "3-4-3",
    "coach": {
      "id": "159",
      "name": "Slaven Bilic",
      "country": {
        "id": "45",
        "name": "Croatia",
        "code": null
      },
      "birthdate": "1968-09-11"
    },
    "team_id": "94",
    "players": [
      {
        "type": {
          "id": "3",
          "name": "Goalkeeper",
          "category": "start",
          "code": "goalkeeper"
        },
        "player": {
          "id": "1555",
          "name": "Adrian",
          "country": {
            "id": "17",
            "name": "Spain",
            "code": null
          },
          "active": true,
          "birthdate": "1987-01-03",
          "birth_city": null,
          "profile": {
            "height": "190",
            "weight": "80"
          },
          "social": null,
          "position": "KEEPER"
        },
        "position_x": 1,
        "position_y": 5,
        "shirt_number": 13
      },
      {
        "type": {
          "id": "4",
          "name": "Defence",
          "category": "start",
          "code": "defence"
        },
        "player": {
          "id": "2050",
          "name": "Winston Reid",
          "country": {
            "id": "106",
            "name": "New Zealand",
            "code": null
          },
          "active": true,
          "birthdate": "1988-07-03",
          "birth_city": null,
          "profile": {
            "height": "190",
            "weight": "87"
          },
          "social": null,
          "position": "DEFENDER"
        },
        "position_x": 3,
        "position_y": 3,
        "shirt_number": 2
      }
    ]
  },
  "away_team": {
    "formation": "4-5-1",
    "coach": {
      "id": "120",
      "name": "David Moyes",
      "country": {
        "id": "93",
        "name": "Scotland",
        "code": null
      },
      "birthdate": "1963-04-25"
    },
    "team_id": "85",
    "players": [
      {
        "type": {
          "id": "3",
          "name": "Goalkeeper",
          "category": "start",
          "code": "goalkeeper"
        },
        "player": {
          "id": "2177",
          "name": "Jordan Pickford",
          "country": {
            "id": "15",
            "name": "England",
            "code": null
          },
          "active": true,
          "birthdate": "1994-03-07",
          "birth_city": null,
          "profile": {
            "height": "185",
            "weight": "77"
          },
          "social": null,
          "position": "KEEPER"
        },
        "position_x": 1,
        "position_y": 5,
        "shirt_number": 13
      },
      {
        "type": {
          "id": "4",
          "name": "Defence",
          "category": "start",
          "code": "defence"
        },
        "player": {
          "id": "2176",
          "name": "Javier Manquillo",
          "country": {
            "id": "17",
            "name": "Spain",
            "code": null
          },
          "active": true,
          "birthdate": "1994-05-05",
          "birth_city": null,
          "profile": {
            "height": "178",
            "weight": "62"
          },
          "social": null,
          "position": "DEFENDER"
        },
        "position_x": 3,
        "position_y": 2,
        "shirt_number": 21
      }
    ]
  }
}
```