-- マスタ系の初期データ

INSERT INTO store_categories
VALUES
  ('/office', '本部', '0', now(), now()),
  ('/retail', '直営店', '0', now(), now()),
  ('/affiliation', '本加盟店部', '0', now(), now());

INSERT INTO roles
VALUES
  ('/office', '本部', '0', now(), now()),
  ('/owner', '店舗オーナー', '0', now(), now()),
  ('/manager', '店長', '0', now(), now()),
  ('/part', 'アルバイト・パート', '0', now(), now()),
  ('/help', '他店のヘルプ', '0', now(), now());
