# Match Events v2

## Adding and updating match events

- PUT `/v2/matches/{match_id}/events`

### Example

```json
{
  "events": [
    {
      "id": "123",
      "type_code": "YELLOW_RED_CARD",
      "team_position": "HOME",
      "minute": 1,
      "primary_player_id": "64",
      "secondary_player_id": "65",
      "sort_order": 2
    }
  ]
}
```

### Behaviour

- The `events` array should contain all match events that should be persisted. The endpoint will do a replace of all existing events.
- `events` which are not present in the array will be deleted

### Validation

- `id` optional: if updating an existing event, this should be filled with the existing `event.id`. If it is not added a new `event.id` will be generated. 
- `type_code` required enum, representing the type of event. Supported types: `YELLOW_RED_CARD, PENALTY_SHOOTOUT_MISSED, PENALTY_SHOOTOUT_SCORED, SUBSTITUTION, 
  GOAL, RED_CARD, PENALTY_MISS, YELLOW_CARD, 
  ASSIST, PENALTY_GOAL, OWN_GOAL`
- `team_position` required enum, `HOME` or `AWAY`. Indicates which team the event belongs to.
- `minute` Required: the minute of the match when the event occurred. Range 0-121
- `primary_player_id` e.g goalscorer, for substitutions this is the player coming off
- `secondary_player_id` e.g player providing the assist, for substitution this is the player coming on 
- `sortorder` optional. If two events have the same `minute` this is the chronological order in which they occurred.

### Successful Response

Lists all match events as they are stored

```json
{
  "events": [
    {
      "id": "2303774",
      "match_id": "685038",
      "type_code": "GOAL",
      "team_position": "AWAY",
      "minute": 18,
      "team_id": "92",
      "primary_player": {
        "id": "4971",
        "name": "Mohamed Salah",
        "country": {
          "id": "97",
          "name": "Egypt",
          "code": null
        },
        "active": true,
        "birthdate": "1992-06-15",
        "birth_city": null,
        "profile": {
          "height": "175",
          "weight": "71"
        },
        "social": null,
        "position": "FORWARD"
      },
      "secondary_player": {
        "id": "1692",
        "name": "Roberto Firmino",
        "country": {
          "id": "16",
          "name": "Brazil",
          "code": null
        },
        "active": true,
        "birthdate": "1991-10-02",
        "birth_city": null,
        "profile": {
          "height": "181",
          "weight": "76"
        },
        "social": {
          "twitter_id": "robertofirmino",
          "facebook_id": "roberto_firmino",
          "instagram_id": "roberto_firmino",
          "wikipedia_id": "Roberto_Firmino"
        },
        "position": "FORWARD"
      },
      "score": {
        "home": 0,
        "away": 1
      }
    }
  ]
}
```