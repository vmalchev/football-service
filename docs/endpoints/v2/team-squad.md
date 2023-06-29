# Team Squad v2

Management for all players that have been or are currently part of a team

## GET Team Squad

### Example

`/v2/teams/{team_id}/squad?memberStatus=ACTIVE`

```json
{
  "team": {
    "id": "102",
    "name": "Manchester United",
    "type": "club",
    "country": {
      "id": "15",
      "name": "England",
      "code": null
    },
    "venue": {
      "id": "57",
      "name": "Old Trafford",
      "country": {
        "id": "15",
        "name": "England",
        "code": null
      },
      "city": {
        "id": "1",
        "name": "Manchester",
        "country": {
          "id": "15",
          "name": "England",
          "code": null
        }
      },
      "profile": {
        "lat": 53.463056,
        "lng": -2.291389,
        "capacity": 75643
      }
    },
    "social": {
      "web": "http:\/\/www.manutd.com\/",
      "twitter_id": "MANUTD",
      "facebook_id": "manchesterunited",
      "instagram_id": "MANCHESTERUNITED",
      "wikipedia_id": "Manchester_United_F.C."
    },
    "founded": 1879
  },
  "players": [
    {
      "player": {
        "id": "3400",
        "name": "Cristiano Ronaldo",
        "country": {
          "id": "31",
          "name": "Portugal",
          "code": null
        },
        "active": true,
        "birthdate": "1985-02-05",
        "birth_city": null,
        "profile": {
          "height": "185",
          "weight": "80"
        },
        "social": {
          "web": "http:\/\/www.cristianoronaldo.com\/",
          "twitter_id": "Cristiano",
          "facebook_id": "cristiano",
          "instagram_id": "cristiano",
          "wikipedia_id": "Cristiano_Ronaldo",
          "youtube_channel_id": "CristianoRonaldo"
        },
        "position": "FORWARD"
      },
      "status": "ACTIVE",
      "contract_type": "PERMANENT",
      "start_date": "2012-10-10",
      "end_date": "2013-10-10",
      "shirt_number": 1
    }
  ]
}
```

### Query parameters

- `memberStatus` enum `ACTIVE`, `INACTIVE`, `ALL`. Can be used to view historical members. Defaults to `ACTIVE` if not specified

## Updating team squad

PATCH `/v2/teams/{team_id}/squad`

### Input

The entire squad will be replaced with the data in the `players` array upon success

```json
{
  "players": [
    {
      "player_id": "3400",
      "status": "ACTIVE",
      "shirt_number": 1,
      "end_date": "2013-10-10",
      "start_date": "2012-10-10",
      "contract_type": "PERMANENT"
    }
  ]
}
```

### Validation

- `player_id` required, a valid player.id
- `status` required, enum `ACTIVE`, `INACTIVE`. Whether the player is currently part of the squad or was a historical member
- `shirt_number` optional, the player's shirt number at the time when he played for the club
- `end_date` optional, date when the player's contract ended
- `start_date` optional, date when the player's contract started
- `contract_type` optional, enum `PERMANENT`, `LOAN`, `UNKNOWN`
- the endpoint does not allow to add the same player twice with `status` = `ACTIVE`. The player can have
multiple `INACTIVE` entries

### Output

Same as the example above, provides all players saved in the squad.