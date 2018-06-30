create table payment_method
(
  id           int auto_increment
    primary key,
  identifier   varchar(500)            not null,
  type         varchar(30)             null,
  created_at   datetime                not null,
  updated_at   datetime                not null,
  user_id      int                     not null,
  sort_key     smallint(6) default '0' not null,
  auth_enabled tinyint(1)              null
);

create table product
(
  id    int auto_increment
    primary key,
  code  varchar(255) not null,
  name  varchar(255) not null,
  price int          not null
);

create table secure_data
(
  token                 varchar(36)  not null
    primary key,
  data                  text         null,
  secure_vault_provider varchar(500) null,
  created_at            datetime     not null,
  updated_at            datetime     not null,
  exp_date              datetime     null
);

create table store
(
  id         int auto_increment
    primary key,
  identifier varchar(255)  not null,
  name       varchar(255)  not null,
  latitude   varchar(20)   not null,
  longitude  varchar(20)   not null,
  address    varchar(2000) not null,
  created_at datetime      not null
);

create table orders
(
  id         int auto_increment
    primary key,
  identifier varchar(255) not null,
  store_id   int          not null,
  created_at datetime     not null,
  updated_at datetime     not null,
  status     varchar(200) null,
  constraint order_store_id_fk
  foreign key (store_id) references store (id)
);

create table order_product
(
  id         int auto_increment
    primary key,
  order_id   int         null,
  product_id int         null,
  quantity   smallint(6) null,
  constraint order_product_order_id_fk
  foreign key (order_id) references orders (id),
  constraint order_product_product_id_fk
  foreign key (product_id) references product (id)
);

create table user
(
  id                   int auto_increment
    primary key,
  username             varchar(255)             not null,
  auth_key             varchar(32)              not null,
  password_hash        varchar(255)             not null,
  password_reset_token varchar(255)             null,
  email                varchar(255)             not null,
  status               smallint(6) default '10' not null,
  created_at           int                      not null,
  updated_at           int                      not null,
  constraint username
  unique (username),
  constraint password_reset_token
  unique (password_reset_token),
  constraint email
  unique (email)
)
  collate = utf8_unicode_ci;

create table bank_account
(
  id         int auto_increment
    primary key,
  user_id    int         not null,
  token_id   varchar(36) not null,
  name       varchar(30) null,
  created_at datetime    not null,
  updated_at datetime    not null,
  constraint bank_account_user_id_fk
  foreign key (user_id) references user (id),
  constraint bank_account_secure_data_token_fk
  foreign key (token_id) references secure_data (token)
);

create table card
(
  id         int auto_increment
    primary key,
  user_id    int         not null,
  token_id   varchar(36) not null,
  created_at datetime    not null,
  updated_at datetime    not null,
  constraint card_user_id_fk
  foreign key (user_id) references user (id),
  constraint card_secure_data_token_fk
  foreign key (token_id) references secure_data (token)
);

create table crypto_currency
(
  id         int auto_increment
    primary key,
  user_id    int         not null,
  token_id   varchar(36) not null,
  type       varchar(30) null,
  created_at datetime    not null,
  updated_at datetime    not null,
  constraint crypto_currency_user_id_fk
  foreign key (user_id) references user (id),
  constraint crypto_currency_secure_data_token_fk
  foreign key (token_id) references secure_data (token)
);

create table device
(
  id         int auto_increment
    primary key,
  nfc_data   text     null,
  created_at datetime not null,
  updated_at datetime not null,
  user_id    int      not null,
  constraint device_user_id_fk
  foreign key (user_id) references user (id)
);

create table transaction
(
  id                int auto_increment
    primary key,
  order_id          int      not null,
  user_id           int      null,
  created_at        datetime not null,
  updated_at        datetime not null,
  payment_method_id int      not null,
  constraint transaction_order_id_fk
  foreign key (order_id) references orders (id),
  constraint transaction_user_id_fk
  foreign key (user_id) references user (id),
  constraint transaction_payment_method_id_fk
  foreign key (payment_method_id) references payment_method (id)
);

