create table users(
    id int AUTO_INCREMENT primary key,
    email varchar(200) unique not null,
    pass varchar(150) not null
);
create table categorias(
    id int AUTO_INCREMENT primary key,
    nombre varchar(50) UNIQUE not null
);
create table posts(
    id int AUTO_INCREMENT primary key,
    titulo varchar(120) UNIQUE not null,
    contenido text not null,
    status enum("PUBLICADO", "BORRADOR"),
    categoria_id int not null,
    constraint fk_post_cat FOREIGN KEY(categoria_id) REFERENCES categorias(id) on delete cascade
);