create table bestellingen(
    id int primary key,
    lever_straat varchar(3000),
    lever_huisnummer varchar(3000),
    lever_gemeente varchar(3000),
    lever_postcode varchar(3000),
    factuur_straat varchar(3000),
    factuur_huisnummer varchar(3000),
    factuur_gemeente varchar(3000),
    factuur_postcode varchar(3000),
    levermethode_id varchar(3000),
    betaalmethode_id varchar(3000),
    user_id varchar(3000),
    datum varchar(3000),
    totaal int
);

create table bestellingen_producten(
    bestelling_id int,
    product_id int,
    aantal int
);

create table betaalmethoden(
    id int primary key,
    naam varchar(3000),
    kosten_geld varchar(3000),
    kosten_procent varchar(3000),
    img_path varchar(3000)
);

create table categorie(
    id int primary key,
    naam varchar(3000),
    active bit
);

create table configuratie(
    winkel_naam varchar(3000),
    aantal_uitgelicht int,
    aantal_nieuwste int
);

create table levermethoden(
    id int primary key,
    naam varchar(3000),
    kosten_geld varchar(3000),
    kosten_procent varchar(3000),
    img_path varchar(3000),
    duur varchar(3000)
);

create table password_reset(
    id int primary key,
    email varchar(3000),
    datum varchar(3000)
);

create table producten(
    id int primary key,
    categorie int,
    naam varchar(3000),
    prijs int,
    beschrijving varchar(3000),
    datum_toegevoegd varchar(3000),
    img_path varchar(3000),
    uitgelicht varchar(3000),
    rating varchar(3000),
    aantal_ratings int,
    active bit
);

create table reviews(
    id int primary key,
    user_id int,
    product_id int,
    comment varchar(3000),
    rating varchar(3000),
    datum varchar(3000),
    title varchar(3000)
);

create table users(
    id int primary key,
    password varchar(3000),
    naam varchar(3000),
    voornaam varchar(3000),
    authority varchar(3000),
    email varchar(3000),
    facebook_id varchar(3000),
    img_path varchar(3000),
    active varchar(3000)
);