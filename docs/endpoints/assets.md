# Adding Assets to football entities

## Supported asset types

All entities that can have assets and the supported types are listed in the following endpoint:

The dimensions **must** be respected when attaching assets to entities

GET `/v2/assets/types`

```json
[
  {
    "entity": "PLAYER",
    "asset_type": "IMAGE",
    "dimensions": {
      "width": 277,
      "height": 388
    },
    "contexts": [
      {
        "type": "TEAM"
      }
    ]
  },
  {
    "entity": "PLAYER",
    "asset_type": "THUMBNAIL",
    "dimensions": {
      "width": 150,
      "height": 150
    }
  },
  {
    "entity": "TEAM",
    "asset_type": "LOGO",
    "dimensions": {
      "width": 150,
      "height": 150
    }
  },
  {
    "entity": "TEAM",
    "asset_type": "HOME_KIT",
    "dimensions": {
      "width": 98,
      "height": 128
    }
  },
  {
    "entity": "TEAM",
    "asset_type": "AWAY_KIT",
    "dimensions": {
      "width": 98,
      "height": 128
    }
  },
  {
    "entity": "TEAM",
    "asset_type": "SQUAD_IMAGE",
    "dimensions": {
      "width": 658,
      "height": 303
    },
    "contexts": [
      {
        "type": "SEASON"
      }
    ]
  },
  {
    "entity": "COACH",
    "asset_type": "IMAGE",
    "dimensions": {
      "width": 277,
      "height": 388
    }
  },
  {
    "entity": "VENUE",
    "asset_type": "IMAGE",
    "dimensions": {
      "width": 600,
      "height": 450
    }
  },
  {
    "entity": "COUNTRY",
    "asset_type": "FLAG",
    "dimensions": {
      "width": 128,
      "height": 85
    }
  },
  {
    "entity": "REFEREE",
    "asset_type": "IMAGE",
    "dimensions": {
      "width": 277,
      "height": 388
    }
  },
  {
    "entity": "PRESIDENT",
    "asset_type": "IMAGE",
    "dimensions": {
      "width": 277,
      "height": 388
    }
  },
  {
    "entity": "TOURNAMENT",
    "asset_type": "LOGO",
    "dimensions": {
      "width": 150,
      "height": 150
    }
  }
]
```
## Modifying related assets for entities

- POST `/v2/assets`
- Supports an array of assets
- Replaces any existing asset information by key `entity`, `entity_id`, `type`, `context_type`, `context_id`

### Example

```json
[
  {
    "entity": "TEAM",
    "entity_id": "102",
    "type": "LOGO",
    "path": "folder/team-102-logo.png",
    "context_type": null,
    "context_id": null
  }
]

```

### Validation
- `entity` one listed in `/v2/assets/types`, required
- `entity_id` an existing `id` for the given `entity`
- `type` an `asset_type` from `/v2/assets/types`. The combination of `entity` and `type` must be present in `/v2/assets/types`
- `path` the image-api path to the asset
- `context_type` an additional type of entity to tag the asset with (optional)
- `context_id` an entity identifier to tag the asset with (optional)