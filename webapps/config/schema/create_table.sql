-- テーブル作成SQL

-- ユーザーマスタ
CREATE TABLE users (
  id INT unsigned NOT NULL,
  email VARCHAR(50) NOT NULL,
  password VARCHAR(255) NOT NULL,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ユーザーマスタ' AUTO_INCREMENT=1;

-- 店舗マスタ
CREATE TABLE stores (
  id INT unsigned NOT NULL,
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
  created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='店舗マスタ' AUTO_INCREMENT=1;

-- 店舗の種別マスタ
CREATE TABLE store_categories (
  id INT unsigned NOT NULL,
  alias VARCHAR(20) NOT NULL,
  name VARCHAR(50) NOT NULL,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE (alias)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='店舗種別マスタ' AUTO_INCREMENT=1;

-- ユーザーの役割マスタ
CREATE TABLE roles (
  id INT unsigned NOT NULL,
  alias VARCHAR(20) NOT NULL,
  name VARCHAR(50) NOT NULL,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE (alias)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='役割マスタ' AUTO_INCREMENT=1;

-- ユーザーが管理している店舗の紐付けマスタ
CREATE TABLE user_stores (
  id INT unsigned NOT NULL,
  user_id INT unsigned NOT NULL,
  store_id INT unsigned NOT NULL,
  role_id INT unsigned NOT NULL,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE (user_id, store_id, role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='ユーザー管理店舗マスタ' AUTO_INCREMENT=1;

-- 従業員マスタ
CREATE TABLE employees (
  id INT unsigned NOT NULL,
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
  name VARCHAR(255) NOT NULL,
  is_deleted TINYINT(1) NOT NULL DEFAULT 0,
  created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='従業員マスタ' AUTO_INCREMENT=1;

