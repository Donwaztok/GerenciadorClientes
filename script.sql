CREATE DATABASE GerenciadorClientes;
USE GerenciadorClientes;

CREATE TABLE Usuario (
	ID_Usuario int AUTO_INCREMENT NOT NULL,
	Login varchar(255) NOT NULL,
	Senha varchar(255) NOT NULL,
	Email varchar(255),
    Created DATETIME DEFAULT CURRENT_TIMESTAMP,
    Updated DATETIME ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (ID_Usuario)
);

CREATE TABLE Cliente (
	ID_Cliente int AUTO_INCREMENT NOT NULL,
	Nome varchar(255) NOT NULL,
	Nasc date,
	CPF varchar(20),
	RG varchar(20),
	Telefone varchar(50),
	PRIMARY KEY (ID_Cliente)
);

CREATE TABLE Endereco (
	ID_Endereco int AUTO_INCREMENT NOT NULL,
	ID_Cliente int NOT NULL,
	Rua varchar(255) NOT NULL,
	Numero int(10),
	Bairro varchar(255),
	CEP varchar(10),
	Cidade varchar(100),
	Estado varchar(100),
	PRIMARY KEY (ID_Endereco),
	FOREIGN KEY (ID_Cliente) REFERENCES Cliente(ID_Cliente)
);