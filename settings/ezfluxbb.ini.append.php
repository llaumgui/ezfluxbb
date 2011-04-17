<?php /* #?ini charset="utf-8"?

[FluxBBInfo]
# FluxBB version: Use a full version with format x.y.z
Version=1.4.4

# Path to your FluxBB board
Path=./path_to/fluxBB

# Board's url
BoardURL=http://www.exemple.com


[DataBase]
# DB's Charset.
Charset=utf-8


[Queries]
# Stadards queries in FluxBB. Use this settings file for your hack or use an old
# FluxBB version. eZFluxBB is tested with the last upstream FluxBB version.

# Stats block
Stats=SELECT SUM(f.num_topics) as num_topics, SUM(f.num_posts) as num_posts, (SELECT COUNT(id)-1 FROM %db_prefix%users) as num_members FROM %db_prefix%forums f
LastMember=SELECT id, username FROM %db_prefix%users ORDER BY registered DESC LIMIT 1

# Online block
Online=SELECT user_id, ident FROM %db_prefix%online WHERE idle=0 ORDER BY ident

# User informations
User=SELECT u.*, g.*, o.logged, o.idle FROM %db_prefix%users AS u INNER JOIN %db_prefix%groups AS g ON u.group_id=g.g_id LEFT JOIN %db_prefix%online AS o ON o.user_id=u.id WHERE u.id=%d GROUP BY u.id
UserOnline[Inser]=INSERT INTO %db_prefix%online (user_id, ident, logged) VALUES( %d, '%s', %d )
UserOnline[Update]=UPDATE %db_prefix%online SET logged=%d WHERE user_id=%d
UserOnline[UpdateAnonym]=UPDATE %db_prefix%online SET logged=%d WHERE ident='%s'

[QueriesTopics]
# Queries related to Topics fetches

Select=t.id topic_id, t.subject topic_name, t.poster creator, t.num_replies, t.posted published, t.last_post_id, t.last_post last_post_published, t.last_poster last_post_creator
From=%db_prefix%topics t
Where=t.id > 0 AND t.forum_id %s

GroupID[Select]=f.id forum_id, f.forum_name
GroupID[InnerJoin]=INNER JOIN %db_prefix%forums AS f ON f.id=t.forum_id
GroupID[LeftJoin]=LEFT JOIN %db_prefix%forum_perms AS fp ON (fp.forum_id=f.id AND fp.group_id=%d)
GroupID[Where]=(fp.read_forum IS NULL OR fp.read_forum=1) AND t.moved_to IS NULL

GetFirstMessage[Select]=p.id post_id, p.message
GetFirstMessage[InnerJoin]=INNER JOIN %db_prefix%posts p ON (p.topic_id=t.id AND p.id=t.first_post_id)

*/ ?>