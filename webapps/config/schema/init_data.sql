-- マスタ系の初期データ

INSERT INTO store_categories
  (`alias`, `name`, `is_deleted`, `created`, `updated`)
VALUES
  ('/office', '本部', '0', now(), now()),
  ('/retail', '直営店', '0', now(), now()),
  ('/affiliation', '本加盟店部', '0', now(), now());

INSERT INTO roles
  (`alias`, `name`, `is_deleted`, `created`, `updated`)
VALUES
  ('/office', '本部', '0', now(), now()),
  ('/owner', '店舗オーナー', '0', now(), now()),
  ('/manager', '店長', '0', now(), now()),
  ('/part', 'アルバイト・パート', '0', now(), now()),
  ('/help', '他店のヘルプ', '0', now(), now());

INSERT INTO time_card_states
  (`alias`, `name`, `is_deleted`, `created`, `updated`)
VALUES
  ('/in', '出勤', '0', now(), now()),
  ('/out', '退勤', '0', now(), now()),
  ('/break_in', '休憩入', '0', now(), now()),
  ('/break_out', '休憩終', '0', now(), now());
