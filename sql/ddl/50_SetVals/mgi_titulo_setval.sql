-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: mgi_titulo
-- Actualizacion Nro de Secuencia: mgi_titulo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgi_titulo_seq',(SELECT MAX(titulo) FROM mgi_titulo));


