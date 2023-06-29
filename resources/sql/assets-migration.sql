--insert into public.asset(id, "type", entity, entity_id, "path")


-- COUNTRY - FLAG
--select uuid_generate_v4(), 'flag', 'country', id, CONCAT('country/flag/',flag) from country where flag is not null

-- TOURNAMENT - LOGO
--select uuid_generate_v4(), 'logo', 'tournament', id, CONCAT('tournament/logo/',logo) from tournament where logo is not null

-- ODD PROVIDER - LOGO
--select uuid_generate_v4(), 'logo', 'odd_provider', id, CONCAT('odd_provider/logo/',logo) from odd_provider where logo is not null

-- PLAYER - THUMB, IMAGE
--select uuid_generate_v4(), 'thumb', 'player', id, CONCAT('player/thumb/',thumb) from player where thumb is not null
--select uuid_generate_v4(), 'image', 'player', id, CONCAT('player/image/',image) from player where image is not null

-- TEAM - LOGO, HOME_KIT, AWAY_KIT, SQUAD_IMAGE
--select uuid_generate_v4(), 'logo', 'team', id, CONCAT('team/logo/',logo_filename) from team where logo_filename is not null
--select uuid_generate_v4(), 'home_kit', 'team', id, CONCAT('team/home_kit/',home_kit) from team where home_kit is not null
--select uuid_generate_v4(), 'away_kit', 'team', id, CONCAT('team/away_kit/',away_kit) from team where away_kit is not null
--select uuid_generate_v4(), 'squad_image', 'team', id, CONCAT('team/squad_image/',squad_image) from team where squad_image is not null

-- VENUE - IMAGE
--select uuid_generate_v4(), 'image', 'venue', id, CONCAT('venue/image/',image) from venue where image is not null