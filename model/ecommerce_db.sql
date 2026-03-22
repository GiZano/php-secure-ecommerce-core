-- Istruzioni per l'importazione in phpMyAdmin:
-- 1. Apri phpMyAdmin
-- 2. Vai nella scheda "Importa"
-- 3. Seleziona questo file e clicca su "Esegui"

CREATE DATABASE IF NOT EXISTS 5h_26_negozio_online;
USE 5h_26_negozio_online;

-- Creazione tabella Utenti
CREATE TABLE IF NOT EXISTS utenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cognome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    data_registrazione TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Creazione tabella Prodotti
CREATE TABLE IF NOT EXISTS prodotti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descrizione TEXT,
    prezzo DECIMAL(10,2) NOT NULL,
    quantita_magazzino INT NOT NULL DEFAULT 0
);

-- Creazione tabella Ordini (con chiave esterna su Utenti)
CREATE TABLE IF NOT EXISTS ordini (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utente INT NOT NULL,
    data_ordine TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    totale DECIMAL(10,2) NOT NULL,
    stato_ordine ENUM('In elaborazione', 'Spedito', 'Consegnato', 'Annullato') DEFAULT 'In elaborazione',
    FOREIGN KEY (id_utente) REFERENCES utenti(id) ON DELETE CASCADE
);

-- Creazione tabella Dettagli Ordine (Associazione Molti-a-Molti tra Ordini e Prodotti)
CREATE TABLE IF NOT EXISTS dettagli_ordine (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_ordine INT NOT NULL,
    id_prodotto INT NOT NULL,
    quantita INT NOT NULL,
    prezzo_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_ordine) REFERENCES ordini(id) ON DELETE CASCADE,
    FOREIGN KEY (id_prodotto) REFERENCES prodotti(id) ON DELETE RESTRICT
);

-- Popolamento dati di test

INSERT INTO utenti (nome, cognome, email) VALUES
('Mario', 'Rossi', 'mario.rossi@example.com'),
('Giulia', 'Bianchi', 'giulia.bianchi@example.com'),
('Luca', 'Verdi', 'luca.verdi@example.com');

INSERT INTO prodotti (nome, descrizione, prezzo, quantita_magazzino) VALUES
('Smartphone XYZ', 'Smartphone di ultima generazione', 599.99, 50),
('Laptop Pro', 'Notebook potente per sviluppatori', 1299.00, 20),
('Cuffie Bluetooth', 'Cuffie con cancellazione del rumore', 89.50, 100),
('Mouse Wireless', 'Mouse ergonomico', 25.00, 200);

-- Ordine 1 di Mario Rossi
INSERT INTO ordini (id_utente, totale, stato_ordine) VALUES (1, 689.49, 'Spedito');
INSERT INTO dettagli_ordine (id_ordine, id_prodotto, quantita, prezzo_unitario) VALUES 
(1, 1, 1, 599.99), 
(1, 3, 1, 89.50);

-- Ordine 2 di Giulia Bianchi
INSERT INTO ordini (id_utente, totale, stato_ordine) VALUES (2, 25.00, 'In elaborazione');
INSERT INTO dettagli_ordine (id_ordine, id_prodotto, quantita, prezzo_unitario) VALUES 
(2, 4, 1, 25.00);

-- Ordine 3 di Mario Rossi
INSERT INTO ordini (id_utente, totale, stato_ordine) VALUES (1, 1299.00, 'Consegnato');
INSERT INTO dettagli_ordine (id_ordine, id_prodotto, quantita, prezzo_unitario) VALUES 
(3, 2, 1, 1299.00);
