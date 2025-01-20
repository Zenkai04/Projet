create table if not exists restaurants (
  id serial primary key,
  name varchar(255) not null,
  address varchar(255) not null,
  phone varchar(255) not null,
  entree varchar(255) not null,
  idMenu integer not null,
  created_at timestamp not null default current_timestamp,
  updated_at timestamp not null default current_timestamp
);

create table if not exists menus (
  id serial primary key,
  name varchar(255) not null,
  price float not null,
  restaurant_id integer not null,
  created_at timestamp not null default current_timestamp,
  updated_at timestamp not null default current_timestamp
);

create table if not exists users (
  id serial primary key,
  nom varchar(255) not null,
  prenom varchar(255) not null,
  email varchar(255) not null,
  password varchar(255) not null,
  role varchar(255) not null,
  created_at timestamp not null default current_timestamp,
  updated_at timestamp not null default current_timestamp
);

create table if not exists orders (
  id serial primary key,
  idUser integer not null,
  idMenu integer not null,
  created_at timestamp not null default current_timestamp,
  updated_at timestamp not null default current_timestamp
);

create table if not exists comments (
  id serial primary key,
  idUser integer not null,
  idRestaurant integer not null,
  content text not null,
  reponse integer not null,
  created_at timestamp not null default current_timestamp,
  updated_at timestamp not null default current_timestamp
);