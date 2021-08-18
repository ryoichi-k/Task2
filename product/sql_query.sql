--ChatWorks提出用



-- 課題３の本番環境SQLクエリを作成しましたので、ご査収願います。
-- お忙しい中申し訳ございませんがよろしくお願いいたします。
-- userテーブル新規作成
-- ーーーーーーーーーーーーーーーーーーーーーーーーーー
CREATE TABLE user (
id SERIAL PRIMARY KEY,
login_id TEXT NOT NULL,
login_pass TEXT NOT NULL,
name TEXT NOT NULL,
name_kana TEXT NOT NULL,
birth_year VARCHAR(4),
birth_month VARCHAR(2),
birth_day VARCHAR(2),
gender TINYINT NOT NULL,
mail TEXT NOT NULL,
tel1 VARCHAR(5) NOT NULL,
tel2 VARCHAR(5) NOT NULL,
tel3 VARCHAR(5) NOT NULL,
postal_code1 VARCHAR(3) NOT NULL,
postal_code2 VARCHAR(4) NOT NULL,
pref TINYINT NOT NULL,
city VARCHAR(15) NOT NULL,
address VARCHAR(100) NOT NULL,
other VARCHAR(100),
memo TEXT,
status TINYINT NOT NULL,
last_login_date TIMESTAMP(6) NULL DEFAULT NULL,
created_at TIMESTAMP(6) DEFAULT CURRENT_TIMESTAMP(6),
updated_at TIMESTAMP(6) NULL DEFAULT NULL,
delete_flg BOOLEAN DEFAULT FALSE
);
-- ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー

-- reservationテーブル新規作成
-- ーーーーーーーーーーーーーーーーーーーーーーーーーー
CREATE TABLE reservation (
id SERIAL PRIMARY KEY,
room_detail_id BIGINT UNSIGNED NOT NULL,
user_id BIGINT UNSIGNED NOT NULL,
name TEXT NOT NULL,
name_kana TEXT NOT NULL,
mail TEXT NOT NULL,
tel1 VARCHAR(5) NOT NULL,
tel2 VARCHAR(5) NOT NULL,
tel3 VARCHAR(5) NOT NULL,
number SMALLINT NOT NULL,
total_price INT NOT NULL,
payment_id BIGINT UNSIGNED NOT NULL,
status TINYINT NOT NULL DEFAULT 1 ,
created_at TIMESTAMP(6) DEFAULT CURRENT_TIMESTAMP(6),
updated_at TIMESTAMP(6) NULL DEFAULT NULL,
delete_flg BOOLEAN DEFAULT FALSE
);
-- ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー

-- reservation_detailテーブル新規作成
-- ーーーーーーーーーーーーーーーーーーーーーーーーーー
CREATE TABLE reservation_detail (
id SERIAL PRIMARY KEY,
reservation_id BIGINT UNSIGNED NOT NULL,
date DATE NOT NULL,
price MIDIUMINT NOT NULL,
);
-- ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー

-- m_paymentテーブル新規作成
-- ーーーーーーーーーーーーーーーーーーーーーーーーーー
CREATE TABLE m_payment (
id SERIAL PRIMARY KEY,
name VARCHAR(100) NOT NULL,
);
-- ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー

-- m_paymentテーブルINSERT文
-- ーーーーーーーーーーーーーーーーーーーーーーーーーー
INSERT INTO m_payment (id, name) VALUES
(1, '各種クレジットカード決済（今すぐ）'),
(2, '各種クレジットカード決済（当日）'),
(3, '現金決済（当日）');
-- ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー

-- userテーブルINSERT文
-- ーーーーーーーーーーーーーーーーーーーーーーーーーー
INSERT INTO `user`(`id`, `login_id`, `login_pass`, `name`, `name_kana`, `birth_year`, `birth_month`, `birth_day`, `gender`, `mail`, `tel1`, `tel2`, `tel3`, `postal_code1`, `postal_code2`, `pref`, `city`, `address`, `other`, `memo`, `status`) VALUES (1,'login','pass','田中テスト','タナカテスト','1990','10','29',1,'test@gmail.com','074','0000','0000','112','0101',2,'板橋区','10番地','板橋マンション200号室','メモ',1);
-- ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
