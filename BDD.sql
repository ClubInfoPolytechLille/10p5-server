CREATE TABLE Utilisateurs (
	login char(30) PRIMARY KEY,
	mdp char(255),
	idCarte char(8),
	droit integer DEFAULT '0'
);

CREATE TABLE Sessions (
	jeton char(30) PRIMARY KEY,
	utilisateur char(30) REFERENCES Utilisateurs(login)
	date datetime DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Clients (
	idCarte char(8) PRIMARY KEY,
	solde float(7,2),
	decouvert boolean DEFAULT false
);


CREATE TABLE Prix (
	produit char(30),
	prix float(7,2)
);


CREATE TABLE Transactions (
	id serial PRIMARY KEY,
	type char(15),
	date datetime DEFAULT CURRENT_TIMESTAMP,
	montant float(7,2),
	quantite integer(2),
	utilisateur char(30) REFERENCES Utilisateur(login),
	client char(30) REFERENCES Client(loginLille1),
	valide boolean
);
