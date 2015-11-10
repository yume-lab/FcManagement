-- テーブル作成SQL

-- ユーザーマスタ
CREATE TABLE users (
  id INT unsigned NOT NULL AUTO_INCREMENT,
  email VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL,
  updated datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE uni_users(email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ユーザーマスタ' AUTO_INCREMENT=1;

-- 店舗マスタ
CREATE TABLE stores (
  id INT unsigned NOT NULL AUTO_INCREMENT,
  store_category_id INT unsigned NOT NULL,
  name VARCHAR(255) NOT NULL,
  phone_number VARCHAR(20) NOT NULL,
  zip_code VARCHAR(10) NOT NULL,
  address_1 VARCHAR(255) NOT NULL,
  address_2 VARCHAR(255) NOT NULL,
  address_3 VARCHAR(255) NOT NULL,
  opened TIME,
  closed TIME,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL,
  updated datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='店舗マスタ' AUTO_INCREMENT=1;

-- 店舗の種別マスタ
CREATE TABLE store_categories (
  id INT unsigned NOT NULL AUTO_INCREMENT,
  alias VARCHAR(20) NOT NULL,
  name VARCHAR(50) NOT NULL,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL,
  updated datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE uni_store_categories(alias)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='店舗種別マスタ' AUTO_INCREMENT=1;

-- ユーザーの役割マスタ
CREATE TABLE roles (
  id INT unsigned NOT NULL AUTO_INCREMENT,
  alias VARCHAR(20) NOT NULL,
  name VARCHAR(50) NOT NULL,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL,
  updated datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE uni_roles(alias)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='役割マスタ' AUTO_INCREMENT=1;

-- ユーザーが管理している店舗の紐付けマスタ
CREATE TABLE user_stores (
  id INT unsigned NOT NULL AUTO_INCREMENT,
  user_id INT unsigned NOT NULL,
  store_id INT unsigned NOT NULL,
  role_id INT unsigned NOT NULL,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL,
  updated datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE uni_user_stores(user_id, store_id, role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ユーザー管理店舗マスタ' AUTO_INCREMENT=1;

-- 従業員マスタ
CREATE TABLE employees (
  id INT unsigned NOT NULL AUTO_INCREMENT,
  role_id INT unsigned NOT NULL,
  store_id INT unsigned NOT NULL,
  email VARCHAR(50) NOT NULL,
  phone_number VARCHAR(20) NOT NULL,
  zip_code VARCHAR(10) NOT NULL,
  address_1 VARCHAR(255) NOT NULL,
  address_2 VARCHAR(255) NOT NULL,
  address_3 VARCHAR(255) NOT NULL,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL,
  updated datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='従業員マスタ' AUTO_INCREMENT=1;

-- シフト管理用データ
CREATE TABLE shift_tables (
  id INT unsigned NOT NULL AUTO_INCREMENT,
  store_id INT unsigned NOT NULL,
  target_ym INT(6) unsigned NOT NULL,
  body TEXT DEFAULT NULL ,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL,
  updated datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE uni_shift_tables(store_id, target_ym)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='シフト表データ' AUTO_INCREMENT=1;

-- 確定シフトデータ
CREATE TABLE fixed_shift_tables (
  id INT unsigned NOT NULL AUTO_INCREMENT,
  store_id INT unsigned NOT NULL,
  target_ym INT(6) unsigned NOT NULL,
  hash VARCHAR(255) NOT NULL,
  body TEXT DEFAULT NULL ,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL,
  updated datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE uni_fixed_shift_tables(store_id, target_ym, hash)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='確定シフト表データ' AUTO_INCREMENT=1;

-- 勤務表データ
CREATE TABLE time_cards (
  id INT unsigned NOT NULL AUTO_INCREMENT,
  store_id INT unsigned NOT NULL,
  employee_id INT unsigned NOT NULL,
  target_ym INT(6) unsigned NOT NULL,
  body TEXT DEFAULT NULL ,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL,
  updated datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE uni_time_cards(store_id, employee_id, target_ym)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='勤務表データ' AUTO_INCREMENT=1;

-- 最新の勤怠データ
CREATE TABLE latest_time_cards (
  id INT unsigned NOT NULL AUTO_INCREMENT,
  store_id INT unsigned NOT NULL,
  employee_id INT unsigned NOT NULL,
  time_card_state_id INT unsigned NOT NULL,
  created datetime NOT NULL,
  updated datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE uni_latest_time_cards(store_id, employee_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='勤務表打刻最新データ' AUTO_INCREMENT=1;

-- 勤怠状態マスタ
CREATE TABLE time_card_states (
  id INT unsigned NOT NULL AUTO_INCREMENT,
  alias VARCHAR(20) NOT NULL,
  name VARCHAR(50) NOT NULL,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL,
  updated datetime NOT NULL,
  PRIMARY KEY (id),
  UNIQUE uni_time_card_states(alias)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='勤怠状態マスタ' AUTO_INCREMENT=1;
