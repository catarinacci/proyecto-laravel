CREATE DATABASE IF NOT EXISTS laravel_master;
USE laravel_master;

CREATE TABLE IF NOT EXISTS users(
    id               int(255) auto_increment NOT NULL,
    role             varchar(20),
    name             varchar(100),
    surname          varchar(200),
    nick             varchar(100),
    email            varchar(255),
    password         varchar(255),
    image            varchar(255),
    created_at        datetime,
    updated_at       datetime,
    remember_token   varchar(255),
    CONSTRAINT pk_users PRIMARY KEY(id)
)ENGINE=InnoDb;

INSERT INTO users VALUES(NULL, 'user', 'Gabriel', 'Catarinacci', 'gabrielcatarinacciweb', 'catarinacci@gmail.com', 'pass', NULL, CURRENT_TIME(), CURRENT_TIME(), NULL );
INSERT INTO users VALUES(NULL, 'user', 'Nicolas', 'Danelon', 'nicholas', 'nicolas@gmail.com', 'pass' NULL, CURRENT_TIME(), CURRENT_TIME(), NULL );
INSERT INTO users VALUES(NULL, 'user', 'javier', 'Luiselli', 'oswald', 'javier@gmail.com', 'pass' NULL, CURRENT_TIME(), CURRENT_TIME(), NULL );

CREATE TABLE IF NOT EXISTS images(
    id               int(255) auto_increment NOT NULL,
    user_id          int(255),
    image_path       varchar(255),
    description      text,
    created_at        datetime,
    updated_at       datetime,
    CONSTRAINT pk_images PRIMARY KEY(id),
    CONSTRAINT fk_images_users FOREIGN KEY(user_id) REFERENCES users(id)
)ENGINE=InnoDb;

INSERT INTO images VALUES(NULL, 1, 'test.jpg', 'descripción de prueba 1', CURRENT_TIME(), CURRENT_TIME());
INSERT INTO images VALUES(NULL, 1, 'playa.jpg', 'descripción de prueba 2', CURRENT_TIME(), CURRENT_TIME());
INSERT INTO images VALUES(NULL, 1, 'arena.jpg', 'descripción de prueba 3', CURRENT_TIME(), CURRENT_TIME());
INSERT INTO images VALUES(NULL, 3, 'familia.jpg', 'descripción de prueba 4', CURRENT_TIME(), CURRENT_TIME());

CREATE TABLE IF NOT EXISTS comments(
    id               int(255) auto_increment NOT NULL,
    user_id          int(255),
    image_id         int(255),
    content          text,
    created_at        datetime,
    updated_at       datetime,
    CONSTRAINT pk_images PRIMARY KEY(id),
    CONSTRAINT fk_comments_users FOREIGN KEY(user_id) REFERENCES users(id),
    CONSTRAINT fk_comments_images FOREIGN KEY(image_id) REFERENCES images(id)
)ENGINE=InnoDb;

INSERT INTO comments VALUES(NULL, 1, 4, 'Buena foto de familia!!', CURRENT_TIME(), CURRENT_TIME());
INSERT INTO comments VALUES(NULL, 2, 1, 'Buena foto de playa!!', CURRENT_TIME(), CURRENT_TIME());
INSERT INTO comments VALUES(NULL, 2, 4, 'Que bueno!!', CURRENT_TIME(), CURRENT_TIME());

CREATE TABLE IF NOT EXISTS likes(
    id               int(255) auto_increment NOT NULL,
    user_id          int(255),
    image_id         int(255),
    created_at        datetime,
    updated_at       datetime,
    CONSTRAINT pk_likes PRIMARY KEY(id),
    CONSTRAINT fk_likes_users FOREIGN KEY(user_id) REFERENCES users(id),
    CONSTRAINT fk_likes_images FOREIGN KEY(image_id) REFERENCES images(id)
)ENGINE=InnoDb;

INSERT INTO likes VALUES(NULL, 1, 4, CURRENT_TIME(), CURRENT_TIME());
INSERT INTO likes VALUES(NULL, 2, 4, CURRENT_TIME(), CURRENT_TIME());
INSERT INTO likes VALUES(NULL, 3, 1, CURRENT_TIME(), CURRENT_TIME());
INSERT INTO likes VALUES(NULL, 3, 2, CURRENT_TIME(), CURRENT_TIME());
INSERT INTO likes VALUES(NULL, 2, 1, CURRENT_TIME(), CURRENT_TIME());
