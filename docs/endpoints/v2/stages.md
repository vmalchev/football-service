# V2 Stages

- Stages define the structure of a Season.
- A Season can have multiple stages.
- All matches **have** to be related to one Stage.

## Endpoints

### GET `/v2/seasons/{id}/stages`

```json
{
  "stages": [
    {
      "id": "123",
      "type": "LEAGUE",
      "start_date": "2020-07-12",
      "end_date": "2021-04-13",
      "name": "efbet League",
      "order_in_season": 1,
      "coverage": "LIVE"
    },
    {
      "id": null,
      "type": "LEAGUE",
      "start_date": "2021-04-15",
      "end_date": "2021-07-15",
      "name": "efbet League - First 8",
      "order_in_season": 2,
      "coverage": "LIVE"
    },
    {
      "id": null,
      "type": "LEAGUE",
      "start_date": "2021-04-15",
      "end_date": "2021-07-15",
      "name": "efbet League - Relegation playoff",
      "order_in_season": 3,
      "coverage": "LIVE"
    }
  ]
}
```

### POST `/v2/seasons/{id}/stages`

```json
{
  "stages": [
    {
      "id": "123",
      "type": "LEAGUE",
      "start_date": "2020-07-12",
      "end_date": "2021-04-13",
      "name": "efbet League",
      "order_in_season": 1,
      "coverage": "LIVE"
    },
    {
      "id": null,
      "type": "LEAGUE",
      "start_date": "2021-04-15",
      "end_date": "2021-07-15",
      "name": "efbet League - First 8",
      "order_in_season": 2,
      "coverage": "LIVE"
    },
    {
      "id": null,
      "type": "LEAGUE",
      "start_date": "2021-04-15",
      "end_date": "2021-07-15",
      "name": "efbet League - Relegation playoff",
      "order_in_season": 3,
      "coverage": "LIVE"
    }
  ]
}
```

#### Notes

- `coverage` indicates whether the users of the API can expect live results for matches within the stage or not. It is an enum with `LIVE` or `NOT_LIVE` values

#### Behaviour

- Sending an object with `id: null` will create a new stage
- Sending an object with an existing `id` will replace the data for the stage
- It is not required to add all stages in the array: only the ones edited can be added.
  **This is important as only the edited stages get blacklisted**
- Only `coverage` is an optional field
- `order_in_season` has to be unique within the `season_id`
- `name` has to be unique within the season
- **New stages (`id:null`) can only be added to a season which does not have a link to a data provider**

## Workflows

### Reorder stages

Season and stages are added and managed by the primary data provider (enetpulse), but a user wants to reorder them.

The field controlling the order on the database is `order_in_season`

The workflow is as follows:

1. Stages get automatically added by the data provider
2. User reorders all or part of the stages in a season (by editing `order_in_season`) through POST `/v2/seasons/{id}/stages`
3. The stages which have a changed `order_in_season` get a blacklist entry
4. The data provider is free to add new stages

### Edit single stage data

1. Stаge is already created by data provider
2. Any of the fields can be edited by sending a POST request with an object with the selected `stage.id`

### Add new stages to a season

1. Season is not linked to a data provider (has no id mapping to enetpulse)
2. Users can add new stages to the season by sending an array of objects with `id: null`

The link to data provider rule is important because a user might add new stage and the data provider can later add the same stage (sementically the same, but
technically two records). The end result will be two stages in the database.