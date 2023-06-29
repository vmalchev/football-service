# Knockout scheme query

Example for EURO 2020

```sql
SELECT
    round_name,
    group_order,
    group_id,
    matches,
    JSON_ARRAYAGG(team) AS teams,
    child_object_id
FROM (SELECT
          rt.value AS round_order,
          rt.name AS round_name,
          de.draw_order AS group_order,
          de.id AS group_id,
          JSON_OBJECT(
                  'id', t.id,
                  'name', t.name,
                  'three_letter_code', l.name,
                  'undecided', MIN(IF(p.name = 'ToBeDecided', IF(p.value = 'yes', 1, 0), NULL)),
                  'national', MIN(IF(p.name = 'IsNationalTeam', IF(p.value = 'yes', 1, 0), NULL))
              ) AS team,
          IF(de.draw_eventFK != 0,de.draw_eventFK,NULL) AS child_object_id,
          JSON_ARRAYAGG(IF(ded.id IS NOT NULL, JSON_OBJECT('id', ded.objectFK, 'kickoff_time',
                                                           DATE_FORMAT(CONVERT_TZ(ded.startdate, 'Europe/Copenhagen', 'GMT'), '%Y-%m-%dT%TZ')), NULL)) AS matches
      FROM
          draw d
              LEFT JOIN draw_event de ON
              de.drawFK = d.id
              LEFT JOIN round_type rt ON
              rt.id = de.round_typeFK
              LEFT JOIN draw_event_participants dep ON
              dep.draw_eventFK = de.id
              LEFT JOIN participant t ON
              t.id = dep.participantFK
              LEFT JOIN draw_event_detail ded ON
              ded.draw_eventFK = de.id
              LEFT JOIN `language` l ON
                  l.`object` ='participant' AND l.objectFK = t.id AND l.language_typeFK = 4
              LEFT JOIN property p ON
                  p.`object` ='participant' AND p.objectFK = t.id AND p.name IN ('ToBeDecided','IsNationalTeam')
      WHERE
          d.id=:drawId
      GROUP BY
          dep.id
      ORDER BY
          rt.value DESC,
          de.draw_order ASC,
          dep.`number` ASC) AS knockout_temp
GROUP BY group_id
ORDER BY
    round_order DESC,
    group_order ASC
```