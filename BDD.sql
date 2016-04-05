CREATE TABLE Utilisateur (
	loglille1 char(30),
	login char(30) PRIMARY KEY,
	mdp char(30),
	droit integer CHECK(droit >= 0 AND droit<=3)
);


CREATE TABLE Client (
	nom char(30),
	prenom char(30),
	loglille1 char(30) PRIMARY KEY,
	solde float5.2,
	idcarte integer,
	credit boolean
);


CREATE TABLE Prix (
	produit char(30),
	prix float5.2
);


CREATE TABLE Transactions (
	id serial PRIMARY KEY,
	type char(15),
	date char(15),
	montant float5.2,
	quantite interger2,
	utilisateur char(30) REFERENCES Utilisateur(login),
	client char(30) REFERENCES Client(loglille1),
	valide boolean
);
