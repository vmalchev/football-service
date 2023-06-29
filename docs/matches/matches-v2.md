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

## Refactoring v1

### Meta fields

in the v1 data structure there are two meta fields which are not part of the match domain, but are set on the `event` table: `teamstats_available` and
`lineup_available`

These fields are set when a lineup is available and when team stats are added.

- https://bitbucket.org/sportal-media-platform/football-api/src/19da3f989b89fe1594ef62be6ebc445d3165b777/football-service/src/Import/LineupImporter.php#lines-87
- https://bitbucket.org/sportal-media-platform/football-api/src/19da3f989b89fe1594ef62be6ebc445d3165b777/football-service/src/Import/EventTeamStatsImporter.php#lines-59

For v2 we should not use these fields anymore, it's cumbersome to update and maintain them. Especially the teamstats since in the future they can come from an
external API or other resource.

To maintain compatibility with v1 we should do the following

- The importers should be able to update these fields even if there is a blacklist.
- If the editor manually edits a match stats and lineups should still be able to come through the provider.
- We only alter the lineup_available flag on the v1 repository method. The idea is to avoid overwriting any manually entered data.
  https://bitbucket.org/sportal-media-platform/football-api/src/19da3f989b89fe1594ef62be6ebc445d3165b777/football-service/src/Repository/EventRepository.php#lines-558
- The same logic applies to
  teamstats_available https://bitbucket.org/sportal-media-platform/football-api/src/19da3f989b89fe1594ef62be6ebc445d3165b777/football-service/src/Repository/EventRepository.php#lines-566
- We trigger the lineup available event when a v2 lineup is created

### Match Minute

#### V1 implementation

In v1 the match minute is computed via the enetpulse SQL scripts. The logic is
here: https://bitbucket.org/sportal-media-platform/enetpulse-football-feed/src/8263bcea22dd46ff593a37b27b210f3e67cab7a6/src/Event/EventParser. php#lines-216

and here:
https://bitbucket.org/sportal-media-platform/enetpulse-football-feed/src/8263bcea22dd46ff593a37b27b210f3e67cab7a6/src/Event/EventParser.php#lines-145

The enetpulse library exposes two fields:

- `minute` this is the minute without any additional time added by the referee. Valid ranges are 0-45, 46-90, 91-120
- `injury_minute` this is the added time by the referee due to injuries or other stoppages.

#### V2 Implementation

For v2 we should create a domain component which calculates the match minute because:

- we can not expect editors to update the match every minute during live
- we will save writes to the database for both v1 and v2 code

It should take the following input:

- current match status
- the phase started at timestamp
- the current phase start minute: first_half = 0 min, second_half = 45 min, extra_time_first_half = 90 min, extra_time_second_half = 115 min
- the current phase duration: first_half = 45 min, second_half = 45 min, extra_time_first_half = 15 min, extra_time_second_half = 15 min

##### Algorithm

1. Calculate current timestamp - phase_started_at match property
2. disregard seconds and round to next minute (football minutes start at 1)
3. check if minute is past the current phase duration and calculate injury minute. Example here:
   https://bitbucket.org/sportal-media-platform/enetpulse-football-feed/src/8263bcea22dd46ff593a37b27b210f3e67cab7a6/src/Event/EventParser.php#lines-216
4. add the phase start minute

## Round Code

Currently, the round is a free value string inside the match object. It will be kept as a free text in the initial version of the v2 endpoint, but the
`key`will be grouped inside a `round` object to allow further fields to be added.

We will later introduce a `Round` entity which will also at least have `name` as an attribute

