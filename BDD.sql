CREATE TABLE Utilisateur (
	loglille1 char(30),
	login char(30) PRIMARY KEY,
	mdp char(30),
	droit integer DEFAULT 0
);

CREATE TABLE Client (
	loglille1 char(30) PRIMARY KEY,
	solde float(5.2),
	idcarte integer,
	credit boolean
);


CREATE TABLE Prix (
	produit char(30),
	prix float(5.2)
);


CREATE TABLE Transactions (
	id serial PRIMARY KEY,
	type char(15),
	date datetime,
	montant float(5.2),
	quantite integer(2),
	utilisateur char(30) REFERENCES Utilisateur(login),
	client char(30) REFERENCES Client(loglille1),
	valide boolean
);
