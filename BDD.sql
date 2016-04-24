CREATE TABLE Utilisateurs (
	loginLille1 char(30),
	idCarte char(8),
	login char(30) PRIMARY KEY,
	mdp char(60),
	droit integer DEFAULT '0'
);

CREATE TABLE Clients (
	loginLille1 char(30) PRIMARY KEY,
	solde float(7,2),
	idCarte char(8),
	credit boolean
);


CREATE TABLE Prix (
	produit char(30),
	prix float(7,2)
);


CREATE TABLE Transactions (
	id serial PRIMARY KEY,
	type char(15),
	date datetime,
	montant float(7,2),
	quantite integer(2),
	utilisateur char(30) REFERENCES Utilisateur(login),
	client char(30) REFERENCES Client(loginLille1),
	valide boolean
);
